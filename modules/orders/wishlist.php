<?php

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';

require_login();

$user = current_user();
$userId = (int) ($user['user_id'] ?? 0);

function wishlist_redirect($url)
{
    header('Location: ' . $url);
    exit;
}

$productId = (int) (
    $_POST['product_id']
    ?? $_GET['product_id']
    ?? 0
);

$isAddRequest =
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['add_wishlist']);

$isRemoveRequest =
    (
        $_SERVER['REQUEST_METHOD'] === 'POST'
        && isset($_POST['remove_wishlist'])
    )
    || (
        $_SERVER['REQUEST_METHOD'] === 'GET'
        && ($_GET['action'] ?? '') === 'remove'
    );

if ($isAddRequest) {

    if ($productId <= 0) {
        $_SESSION['wishlist_error'] =
            'Invalid product selected.';

        wishlist_redirect(
            lg_url('/modules/products/index.php')
        );
    }

    $productCheck = $pdo->prepare("
        SELECT product_id
        FROM Product
        WHERE product_id = ?
        LIMIT 1
    ");

    $productCheck->execute([$productId]);

    if (!$productCheck->fetch()) {
        $_SESSION['wishlist_error'] =
            'This product does not exist.';

        wishlist_redirect(
            lg_url('/modules/products/index.php')
        );
    }

    $alreadyExists = $pdo->prepare("
        SELECT wishlist_id
        FROM Wishlist
        WHERE user_id = ?
        AND product_id = ?
        LIMIT 1
    ");

    $alreadyExists->execute([
        $userId,
        $productId
    ]);

    if ($alreadyExists->fetch()) {
        $_SESSION['wishlist_message'] =
            'This product is already in your wishlist.';
    } else {
        try {
            $insert = $pdo->prepare("
                INSERT INTO Wishlist
                    (user_id, product_id)
                VALUES
                    (?, ?)
            ");

            $insert->execute([
                $userId,
                $productId
            ]);

            $_SESSION['wishlist_message'] =
                'Product added to your wishlist.';
        } catch (PDOException $e) {
            error_log($e->getMessage());

            $_SESSION['wishlist_error'] =
                'The product could not be added to your wishlist.';
        }
    }

    wishlist_redirect(
        lg_url(
            '/modules/products/product.php?id=' .
            $productId
        )
    );
}

if ($isRemoveRequest && $productId > 0) {

    $delete = $pdo->prepare("
        DELETE FROM Wishlist
        WHERE user_id = ?
        AND product_id = ?
    ");

    $delete->execute([
        $userId,
        $productId
    ]);

    $_SESSION['wishlist_message'] =
        'Product removed from your wishlist.';

    wishlist_redirect(
        lg_url('/modules/orders/wishlist.php')
    );
}

$stmt = $pdo->prepare("
    SELECT
        w.wishlist_id,
        p.product_id,
        p.product_name,
        p.price,
        p.image,
        p.description,
        p.stock,
        b.brand_name,
        c.category_name
    FROM Wishlist w
    INNER JOIN Product p
        ON p.product_id = w.product_id
    LEFT JOIN Brand b
        ON b.brand_id = p.brand_id
    LEFT JOIN Category c
        ON c.category_id = p.category_id
    WHERE w.user_id = ?
    ORDER BY w.wishlist_id DESC
");

$stmt->execute([$userId]);
$wishlistItems = $stmt->fetchAll();

$pageKey = 'orders/wishlist';

require_once __DIR__ . '/../../includes/header.php';
require __DIR__ . '/../../views/orders/wishlist.view.php';
require_once __DIR__ . '/../../includes/footer.php';