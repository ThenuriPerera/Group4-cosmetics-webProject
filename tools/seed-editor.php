<?php

require_once __DIR__ . '/../config/db.php';

$email = 'editor@lumineglow.com';
$password = 'Editor@123';
$role = 'editor';

try {
    // Check whether this email already exists
    $check = $pdo->prepare("SELECT user_id FROM `user` WHERE email = ?");
    $check->execute([$email]);

    if ($check->fetch()) {
        die("Editor account already exists: $email");
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Create Editor account
    $stmt = $pdo->prepare("
        INSERT INTO `user` (email, password, role)
        VALUES (?, ?, ?)
    ");

    $stmt->execute([
        $email,
        $hashedPassword,
        $role
    ]);

    echo "Editor account created successfully!<br>";
    echo "Email: " . htmlspecialchars($email) . "<br>";
    echo "Password: " . htmlspecialchars($password) . "<br>";
    echo "Role: editor";

} catch (PDOException $e) {
    die("Error creating Editor account: " . $e->getMessage());
}