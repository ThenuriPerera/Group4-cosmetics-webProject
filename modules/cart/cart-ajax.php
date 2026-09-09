<?php
/* AJAX endpoint used by cart.php's JS to update quantity or remove an item*/
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
header('Content-Type: application/json');

if (!is_logged_in()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}

$userId = current_user()['user_id'];
$action = $_POST['action'] ?? '';
$cartItemId = $_POST['cart_item_id'] ?? null;

// Confirm this cart item actually belongs to the logged-in user's cart
$check = $pdo->prepare(
    "SELECT ci.* FROM Cart_Item ci JOIN Cart c ON ci.cart_id = c.cart_id WHERE ci.cart_item_id = ? AND c.user_id = ?"
);
$check->execute([$cartItemId, $userId]);
$item = $check->fetch();

if (!$item) {
    http_response_code(404);
    echo json_encode(['success' => false, 'error' => 'Item not found']);
    exit;
}

if ($action === 'update_quantity') {
    $qty = max(1, (int)($_POST['quantity'] ?? 1));
    $pdo->prepare("UPDATE Cart_Item SET quantity = ? WHERE cart_item_id = ?")->execute([$qty, $cartItemId]);
} elseif ($action === 'remove') {
    $pdo->prepare("DELETE FROM Cart_Item WHERE cart_item_id = ?")->execute([$cartItemId]);
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Unknown action']);
    exit;
}

// Recalculate total
$cartIdStmt = $pdo->prepare("SELECT cart_id FROM Cart WHERE user_id = ?");
$cartIdStmt->execute([$userId]);
$cartId = $cartIdStmt->fetchColumn();

$totalStmt = $pdo->prepare(
    "SELECT COALESCE(SUM(ci.quantity * p.price), 0) FROM Cart_Item ci JOIN Product p ON ci.product_id = p.product_id WHERE ci.cart_id = ?"
);
$totalStmt->execute([$cartId]);
$total = $totalStmt->fetchColumn();

echo json_encode(['success' => true, 'cart_total' => number_format($total, 2)]);
