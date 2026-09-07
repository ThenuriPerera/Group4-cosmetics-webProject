<?php
/**
 * MODULE OWNER: Member 1 (Auth & User Management)
 * Status: COMPLETE — profile view/edit + full address CRUD.
 */
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_login();

$userId = current_user()['user_id'];
$message = '';

// Update profile info
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $pdo->prepare("UPDATE User SET name = ?, phone = ? WHERE user_id = ?")
        ->execute([$name, $phone, $userId]);
    $_SESSION['user']['name'] = $name;
    $message = 'Profile updated.';
}

// Add address
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_address'])) {
    $pdo->prepare(
        "INSERT INTO Address (user_id, street, city, postal_code, state, country) VALUES (?, ?, ?, ?, ?, ?)"
    )->execute([
        $userId,
        trim($_POST['street']),
        trim($_POST['city']),
        trim($_POST['postal_code']),
        trim($_POST['state']),
        trim($_POST['country']),
    ]);
    $message = 'Address added.';
}

// Delete address
if (isset($_GET['delete_address'])) {
    $pdo->prepare("DELETE FROM Address WHERE address_id = ? AND user_id = ?")
        ->execute([$_GET['delete_address'], $userId]);
    header('Location: /modules/auth/profile.php');
    exit;
}

$user = $pdo->prepare("SELECT * FROM User WHERE user_id = ?");
$user->execute([$userId]);
$user = $user->fetch();

$addresses = $pdo->prepare("SELECT * FROM Address WHERE user_id = ?");
$addresses->execute([$userId]);
$addresses = $addresses->fetchAll();

require_once __DIR__ . '/../../includes/header.php';
?>
<section class="auth-form">
    <h1>My Profile</h1>
    <?php if ($message): ?><p class="success"><?= htmlspecialchars($message) ?></p><?php endif; ?>

    <form method="post">
        <label>Name <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required></label>
        <label>Email <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled></label>
        <label>Phone <input type="tel" name="phone" value="<?= htmlspecialchars($user['phone']) ?>"></label>
        <button type="submit" name="update_profile">Save Profile</button>
    </form>

    <h2>My Addresses</h2>
    <?php foreach ($addresses as $addr): ?>
        <div class="address-card">
            <p><?= htmlspecialchars("{$addr['street']}, {$addr['city']}, {$addr['state']} {$addr['postal_code']}, {$addr['country']}") ?></p>
            <a href="?delete_address=<?= $addr['address_id'] ?>" onclick="return confirm('Delete this address?');">Delete</a>
        </div>
    <?php endforeach; ?>
    <?php if (empty($addresses)): ?><p>No saved addresses yet.</p><?php endif; ?>

    <h3>Add New Address</h3>
    <form method="post">
        <label>Street <input type="text" name="street" required></label>
        <label>City <input type="text" name="city" required></label>
        <label>State/Province <input type="text" name="state"></label>
        <label>Postal Code <input type="text" name="postal_code"></label>
        <label>Country <input type="text" name="country" required></label>
        <button type="submit" name="add_address">Add Address</button>
    </form>
</section>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
