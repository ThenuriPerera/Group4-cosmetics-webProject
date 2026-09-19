
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


// ------------------------------------------------------------
// Update profile info
// ------------------------------------------------------------

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {

    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);

    $pdo->prepare(
        "UPDATE User
         SET name = ?, phone = ?
         WHERE user_id = ?"
    )->execute([
        $name,
        $phone,
        $userId
    ]);

    $_SESSION['user']['name'] = $name;

    $message = 'Profile updated.';
}


// ------------------------------------------------------------
// Add address
// ------------------------------------------------------------

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_address'])) {

    $pdo->prepare(
        "INSERT INTO Address
        (user_id, street, city, postal_code, state, country)
        VALUES (?, ?, ?, ?, ?, ?)"
    )->execute([
        $userId,
        trim($_POST['street']),
        trim($_POST['city']),
        trim($_POST['postal_code']),
        trim($_POST['state']),
        trim($_POST['country'])
    ]);

    $message = 'Address added.';
}


// ------------------------------------------------------------
// Delete address
// ------------------------------------------------------------

if (isset($_GET['delete_address'])) {

    $addressId = (int) $_GET['delete_address'];

    /*
     * First check whether this address belongs to the
     * currently logged-in user.
     */
    $checkAddress = $pdo->prepare(
        "SELECT address_id
         FROM Address
         WHERE address_id = ? AND user_id = ?"
    );

    $checkAddress->execute([
        $addressId,
        $userId
    ]);

    $addressExists = $checkAddress->fetchColumn();


    if ($addressExists) {

        /*
         * Check whether this address is already used
         * by an existing order.
         */
        $checkOrders = $pdo->prepare(
            "SELECT COUNT(*)
             FROM `Order`
             WHERE address_id = ?"
        );

        $checkOrders->execute([
            $addressId
        ]);

        $orderCount = (int) $checkOrders->fetchColumn();


        if ($orderCount > 0) {

            /*
             * Do not delete an address that is linked
             * to an order because the database foreign
             * key protects the order history.
             */
            $message = 'This address cannot be deleted because it is linked to an existing order.';

        } else {

            /*
             * Safe to delete because no order is using it.
             */
            $deleteAddress = $pdo->prepare(
                "DELETE FROM Address
                 WHERE address_id = ? AND user_id = ?"
            );

            $deleteAddress->execute([
                $addressId,
                $userId
            ]);

            $message = 'Address deleted.';
        }

    } else {

        $message = 'Address not found.';
    }
}


// ------------------------------------------------------------
// Get user
// ------------------------------------------------------------

$user = $pdo->prepare(
    "SELECT *
     FROM User
     WHERE user_id = ?"
);

$user->execute([
    $userId
]);

$user = $user->fetch();


// ------------------------------------------------------------
// Get addresses
// ------------------------------------------------------------

$addresses = $pdo->prepare(
    "SELECT *
     FROM Address
     WHERE user_id = ?"
);

$addresses->execute([
    $userId
]);

$addresses = $addresses->fetchAll();


// ------------------------------------------------------------
// View
// ------------------------------------------------------------

$pageKey = 'auth/profile';

require_once __DIR__ . '/../../includes/header.php';

require __DIR__ . '/../../views/auth/profile.view.php';

require_once __DIR__ . '/../../includes/footer.php';
