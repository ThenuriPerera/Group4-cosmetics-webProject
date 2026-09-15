<?php

require_once __DIR__ . '/../config/db.php';

$editorName = 'Lumine Glow Editor';
$editorEmail = 'editor@lumineglow.com';
$editorPassword = 'Editor@123';
$editorRole = 'editor';

try {
    $check = $pdo->prepare("
        SELECT user_id
        FROM `User`
        WHERE email = ?
        LIMIT 1
    ");

    $check->execute([$editorEmail]);

    if ($check->fetch()) {
        die(
            'Editor account already exists: ' .
            htmlspecialchars($editorEmail)
        );
    }

    $hashedPassword = password_hash(
        $editorPassword,
        PASSWORD_DEFAULT
    );

    $insert = $pdo->prepare("
        INSERT INTO `User`
            (name, email, password, role, status)
        VALUES
            (?, ?, ?, ?, 'active')
    ");

    $insert->execute([
        $editorName,
        $editorEmail,
        $hashedPassword,
        $editorRole
    ]);

    echo '<h2>Editor account created successfully!</h2>';
    echo '<p>Name: ' .
        htmlspecialchars($editorName) .
        '</p>';
    echo '<p>Email: ' .
        htmlspecialchars($editorEmail) .
        '</p>';
    echo '<p>Password: ' .
        htmlspecialchars($editorPassword) .
        '</p>';
    echo '<p>Role: editor</p>';

} catch (PDOException $e) {
    die(
        'Error creating Editor account: ' .
        htmlspecialchars($e->getMessage())
    );
}