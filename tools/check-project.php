<?php
/** Read-only local checks. Run through PHP CLI, never through the browser. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
$root = dirname(__DIR__);
$failures = 0;
$checked = 0;
echo "Lumine Glow project checks\n";
echo "PHP " . PHP_VERSION . "\n\n";
if (!function_exists('exec')) {
    echo "PHP exec() is disabled; individual PHP syntax checks cannot run.\n";
    $failures++;
} else {
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS));
    foreach ($iterator as $file) {
        $path = $file->getPathname();
        if (strpos($path, DIRECTORY_SEPARATOR . '.git' . DIRECTORY_SEPARATOR) !== false || $file->getExtension() !== 'php') continue;
        $output = [];
        $code = 0;
        exec(escapeshellarg(PHP_BINARY) . ' -l ' . escapeshellarg($path) . ' 2>&1', $output, $code);
        $checked++;
        if ($code !== 0) { echo implode("\n", $output) . "\n"; $failures++; }
    }
    echo "Syntax checked: $checked PHP files.\n";
}
foreach (['base','layout','components','pages/home','pages/auth','pages/catalogue','pages/beauty','pages/account','pages/shopping','pages/admin'] as $style) {
    if (!is_file($root . '/assets/css/' . $style . '.css')) { echo "Missing stylesheet: $style\n"; $failures++; }
}
if (!is_file($root . '/assets/images/dp.png')) { echo "Missing reference hero image.\n"; $failures++; }

// Exercise the real path helper in a separate PHP process for each deployment shape.
if (function_exists('exec')) {
    foreach (['', '/Group4-cosmetics-webProject', '/test/Group4-cosmetics-webProject'] as $base) {
        $test = '$_SERVER["SCRIPT_FILENAME"]=' . var_export($root . '/modules/auth/login.php', true) . ';'
            . '$_SERVER["SCRIPT_NAME"]=' . var_export($base . '/modules/auth/login.php', true) . ';'
            . 'require ' . var_export($root . '/config/paths.php', true) . ';'
            . 'echo lg_url("assets/css/base.css");';
        $output = []; $code = 0;
        exec(escapeshellarg(PHP_BINARY) . ' -r ' . escapeshellarg($test), $output, $code);
        if ($code !== 0 || implode('', $output) !== $base . '/assets/css/base.css') {
            echo "URL prefix check failed: " . ($base ?: '(site root)') . "\n"; $failures++;
        }
    }
}
if (in_array('--database', $argv, true)) {
    if (!extension_loaded('pdo_mysql')) { echo "PDO MySQL extension is missing.\n"; $failures++; }
    else {
        require $root . '/config/db.php';
        // SELECT/SHOW only: no schema changes, inserts, deletes or payments.
        $required = [
            'Product' => ['product_id','product_name','price','image','sub_category','product_type'],
            'Cart_Item' => ['cart_item_id','cart_id','product_id','variant_id','quantity'],
            'Order' => ['order_id','user_id','order_status'],
            'Wishlist' => ['wishlist_id','user_id','product_id'],
        ];
        foreach ($required as $table => $columns) {
            try {
                $found = $pdo->query('SHOW COLUMNS FROM `' . $table . '`')->fetchAll(PDO::FETCH_COLUMN);
                $missing = array_diff($columns, $found);
                if ($missing) { echo "$table is missing: " . implode(', ', $missing) . "\n"; $failures++; }
                else echo "$table: required columns found.\n";
            } catch (PDOException $e) { echo "Could not inspect $table. Check that the original Group4 database is selected.\n"; $failures++; }
        }
    }
}
echo "\n" . ($failures ? "$failures issue(s) found." : 'Checks passed.') . "\n";
echo "These checks do not test browser layout, authentication workflows or payments.\n";
exit($failures ? 1 : 0);
