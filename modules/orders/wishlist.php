<?php

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';

require_login();

$userId = (int) current_user()['user_id'];

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
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['remove_wishlist']);

$isMoveRequest =
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['move_to_cart']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf_token();
}

/*
|--------------------------------------------------------------------------
| ADD TO WISHLIST
|--------------------------------------------------------------------------
*/

if ($isAddRequest) {
    if ($productId <= 0) {
        $_SESSION['wishlist_error'] = 'Invalid product selected.';
        wishlist_redirect(lg_url('/modules/products/index.php'));
    }

    $productCheck = $pdo->prepare(
        'SELECT product_id FROM Product WHERE product_id = ? LIMIT 1'
    );

    $productCheck->execute([$productId]);

    if (!$productCheck->fetch()) {
        $_SESSION['wishlist_error'] = 'This product does not exist.';
        wishlist_redirect(lg_url('/modules/products/index.php'));
    }

    $exists = $pdo->prepare(
        'SELECT wishlist_id
         FROM Wishlist
         WHERE user_id = ? AND product_id = ?
         LIMIT 1'
    );

    $exists->execute([$userId, $productId]);

    if ($exists->fetch()) {
        $_SESSION['wishlist_message'] =
            'This product is already in your wishlist.';
    } else {
        try {
            $insert = $pdo->prepare(
                'INSERT INTO Wishlist (user_id, product_id)
                 VALUES (?, ?)'
            );

            $insert->execute([$userId, $productId]);

            $_SESSION['wishlist_message'] =
                'Product added to your wishlist.';
        } catch (PDOException $e) {
            error_log($e->getMessage());

            $_SESSION['wishlist_error'] =
                'The product could not be added to your wishlist.';
        }
    }

    wishlist_redirect(
        lg_url('/modules/products/product.php?id=' . $productId)
    );
}

/*
|--------------------------------------------------------------------------
| REMOVE FROM WISHLIST
|--------------------------------------------------------------------------
*/

if ($isRemoveRequest && $productId > 0) {
    $delete = $pdo->prepare(
        'DELETE FROM Wishlist
         WHERE user_id = ? AND product_id = ?'
    );

    $delete->execute([$userId, $productId]);

    $_SESSION['wishlist_message'] =
        'Product removed from your wishlist.';

    wishlist_redirect(
        lg_url('/modules/orders/wishlist.php')
    );
}

/*
|--------------------------------------------------------------------------
| MOVE WISHLIST PRODUCT TO CART
|--------------------------------------------------------------------------
*/

if ($isMoveRequest && $productId > 0) {
    $productStmt = $pdo->prepare(
        'SELECT product_id, stock
         FROM Product
         WHERE product_id = ?'
    );

    $productStmt->execute([$productId]);
    $product = $productStmt->fetch();

    if (!$product || (int) $product['stock'] < 1) {
        $_SESSION['wishlist_error'] =
            'This product is currently out of stock.';
    } else {
        $cartStmt = $pdo->prepare(
            'SELECT cart_id FROM Cart WHERE user_id = ?'
        );

        $cartStmt->execute([$userId]);
        $cartId = $cartStmt->fetchColumn();

        if (!$cartId) {
            $pdo->prepare(
                'INSERT INTO Cart (user_id) VALUES (?)'
            )->execute([$userId]);

            $cartId = $pdo->lastInsertId();
        }

        $cartItemStmt = $pdo->prepare(
            'SELECT cart_item_id, quantity
             FROM Cart_Item
             WHERE cart_id = ?
             AND product_id = ?
             AND variant_id IS NULL
             LIMIT 1'
        );

        $cartItemStmt->execute([$cartId, $productId]);
        $existingItem = $cartItemStmt->fetch();

        if ($existingItem) {
            $newQuantity =
                (int) $existingItem['quantity'] + 1;

            if ($newQuantity > (int) $product['stock']) {
                $_SESSION['wishlist_error'] =
                    'The product is already in your bag at its stock limit.';
            } else {
                $pdo->prepare(
                    'UPDATE Cart_Item
                     SET quantity = ?
                     WHERE cart_item_id = ?'
                )->execute([
                    $newQuantity,
                    $existingItem['cart_item_id']
                ]);
            }
        } else {
            $pdo->prepare(
                'INSERT INTO Cart_Item
                    (cart_id, product_id, variant_id, quantity)
                 VALUES (?, ?, NULL, 1)'
            )->execute([$cartId, $productId]);
        }

        if (!isset($_SESSION['wishlist_error'])) {
            $pdo->prepare(
                'DELETE FROM Wishlist
                 WHERE user_id = ? AND product_id = ?'
            )->execute([$userId, $productId]);

            $_SESSION['wishlist_message'] =
                'Product moved to your bag.';
        }
    }

    wishlist_redirect(
        lg_url('/modules/orders/wishlist.php')
    );
}

/*
|--------------------------------------------------------------------------
| LOAD WISHLIST
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare(
    'SELECT
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
     ORDER BY w.wishlist_id DESC'
);

$stmt->execute([$userId]);
$wishlistItems = $stmt->fetchAll();

$pageKey = 'orders/wishlist';

require_once __DIR__ . '/../../includes/header.php';
require __DIR__ . '/../../views/orders/wishlist.view.php';
require_once __DIR__ . '/../../includes/footer.php';