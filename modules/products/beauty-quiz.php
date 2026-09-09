<?php
/**
 * MODULE OWNER: Member 2 (Product Catalog & Smart Features)
 * Section 5.3 - Beauty Quiz (multi-step wizard with scoring logic)
 * Status: COMPLETE — 3-step wizard, structured answers saved to Skin_Quiz,
 * scored result saved to Beauty_Profile.
 */
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = current_user()['user_id'];

    $oiliness = $_POST['oiliness'] ?? 'balanced';   // oily / dry / balanced
    $tightness = $_POST['tightness'] ?? 'no';        // yes/no - skin feels tight after washing
    $shine = $_POST['shine'] ?? 'no';                 // yes/no - visible shine by midday
    $concern = trim($_POST['concern'] ?? '');
    $undertone = $_POST['undertone'] ?? 'neutral';

    // Simple scoring logic to turn answers into a skin_type result
    if ($oiliness === 'oily' && $shine === 'yes') {
        $resultType = 'oily';
    } elseif ($oiliness === 'dry' && $tightness === 'yes') {
        $resultType = 'dry';
    } elseif ($oiliness === 'oily' || $shine === 'yes') {
        $resultType = 'combination';
    } else {
        $resultType = 'normal';
    }

    $answers = json_encode([
        'oiliness' => $oiliness,
        'tightness' => $tightness,
        'shine' => $shine,
        'undertone' => $undertone,
        'concern' => $concern,
    ]);

    $pdo->prepare("INSERT INTO Skin_Quiz (user_id, answers, result_skin_type) VALUES (?, ?, ?)")
        ->execute([$userId, $answers, $resultType]);

    $pdo->prepare(
        "INSERT INTO Beauty_Profile (user_id, skin_type, concern) VALUES (?, ?, ?)
         ON DUPLICATE KEY UPDATE skin_type = VALUES(skin_type), concern = VALUES(concern)"
    )->execute([$userId, $resultType, $concern]);

    header('Location: ' . lg_url('/modules/products/index.php?skin_type_result=') . $resultType);
    exit;
}

// Presentation is kept in views/products/beauty-quiz.view.php.
$pageKey = 'products/beauty-quiz';
require_once __DIR__ . '/../../includes/header.php';
require __DIR__ . '/../../views/products/beauty-quiz.view.php';
require_once __DIR__ . '/../../includes/footer.php';
