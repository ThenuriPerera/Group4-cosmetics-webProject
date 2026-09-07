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

    header('Location: /modules/products/index.php?skin_type_result=' . $resultType);
    exit;
}

require_once __DIR__ . '/../../includes/header.php';
?>
<section class="beauty-quiz">
    <h1>Beauty Quiz</h1>
    <form method="post" id="quiz-form">
        <div class="quiz-step" data-step="1">
            <h2>Step 1 of 3</h2>
            <label>How does your skin generally feel?
                <select name="oiliness">
                    <option value="oily">Oily / shiny most of the day</option>
                    <option value="dry">Tight / flaky, especially after washing</option>
                    <option value="balanced">Fairly balanced</option>
                </select>
            </label>
            <button type="button" class="next-btn">Next</button>
        </div>

        <div class="quiz-step" data-step="2" hidden>
            <h2>Step 2 of 3</h2>
            <label>Does your skin feel tight after washing?
                <select name="tightness"><option value="no">No</option><option value="yes">Yes</option></select>
            </label>
            <label>Do you see visible shine by midday?
                <select name="shine"><option value="no">No</option><option value="yes">Yes</option></select>
            </label>
            <label>Undertone
                <select name="undertone">
                    <option value="cool">Cool</option>
                    <option value="neutral">Neutral</option>
                    <option value="warm">Warm</option>
                </select>
            </label>
            <button type="button" class="prev-btn">Back</button>
            <button type="button" class="next-btn">Next</button>
        </div>

        <div class="quiz-step" data-step="3" hidden>
            <h2>Step 3 of 3</h2>
            <label>Main skin concern <input type="text" name="concern" placeholder="e.g. acne, dryness, redness"></label>
            <button type="button" class="prev-btn">Back</button>
            <button type="submit">Get My Recommendations</button>
        </div>
    </form>
</section>

<script>
const steps = document.querySelectorAll('.quiz-step');
let current = 0;
function showStep(i) {
    steps.forEach((s, idx) => s.hidden = idx !== i);
}
document.querySelectorAll('.next-btn').forEach(btn => {
    btn.addEventListener('click', () => { current = Math.min(current + 1, steps.length - 1); showStep(current); });
});
document.querySelectorAll('.prev-btn').forEach(btn => {
    btn.addEventListener('click', () => { current = Math.max(current - 1, 0); showStep(current); });
});
</script>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
