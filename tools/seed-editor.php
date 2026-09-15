<?php

require_once __DIR__ . '/../config/db.php';

$name = 'Store Editor';
$email = 'editor@lumineglow.com';
$password = 'Editor@123';
$role = 'editor';

try {
    $check = $pdo->prepare(
        'SELECT user_id FROM `User` WHERE email = ?'
    );

    $check->execute([$email]);

    if ($check->fetch()) {
        exit("Editor account already exists: {$email}");
    }

    $hashedPassword = password_hash(
        $password,
        PASSWORD_DEFAULT
    );

    $stmt = $pdo->prepare(
        "INSERT INTO `User`
            (name, email, password, role, status)
         VALUES (?, ?, ?, ?, 'active')"
    );

    $stmt->execute([
        $name,
        $email,
        $hashedPassword,
        $role
    ]);

    echo "Editor account created successfully.<br>";
    echo "Email: " . htmlspecialchars($email) . "<br>";
    echo "Password: " . htmlspecialchars($password) . "<br>";
    echo "Role: editor";

} catch (PDOException $e) {
    exit(
        'Error creating Editor account: ' .
        htmlspecialchars($e->getMessage())
    );
}