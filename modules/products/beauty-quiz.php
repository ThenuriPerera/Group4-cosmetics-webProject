<?php
/**
 * MODULE OWNER: Member 2 (Product Catalog & Smart Features)
 * Section 5.3 - Beauty Quiz (multi-step, saved into Skin_Quiz + Beauty_Profile)
 * TODO (Member 2):
 *  - Build the actual multi-step JS quiz (assets/js/quiz.js)
 *  - Add more questions (undertone, preferences, concerns)
 *  - Trigger onboarding flow right after registration (Section 3.2)
 */
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = current_user()['user_id'];
    $answers = json_encode($_POST); // TODO: structure this properly, don't just dump raw POST
    $resultSkinType = $_POST['skin_type'] ?? 'normal'; // TODO: real scoring logic

    $pdo->prepare("INSERT INTO Skin_Quiz (user_id, answers, result_skin_type) VALUES (?, ?, ?)")
        ->execute([$userId, $answers, $resultSkinType]);

    $pdo->prepare(
        "INSERT INTO Beauty_Profile (user_id, skin_type, concern) VALUES (?, ?, ?)
         ON DUPLICATE KEY UPDATE skin_type = VALUES(skin_type), concern = VALUES(concern)"
    )->execute([$userId, $resultSkinType, $_POST['concern'] ?? '']);

    header('Location: /modules/products/index.php');
    exit;
}

require_once __DIR__ . '/../../includes/header.php';
?>
<section class="beauty-quiz">
    <h1>Beauty Quiz</h1>
    <!-- TODO: convert to multi-step JS wizard, one question per screen -->
    <form method="post">
        <label>Skin type
            <select name="skin_type">
                <option value="oily">Oily</option>
                <option value="dry">Dry</option>
                <option value="combination">Combination</option>
                <option value="normal">Normal</option>
            </select>
        </label>
        <label>Main concern <input type="text" name="concern" placeholder="e.g. acne, dryness"></label>
        <button type="submit">Get My Recommendations</button>
    </form>
</section>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
