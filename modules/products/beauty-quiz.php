<?php
/**
 * MODULE OWNER: Member 2 (Product Catalog & Smart Features)
 *
 * Beauty Quiz
 * - 3-step skin quiz
 * - Saves answers to Skin_Quiz
 * - Updates Beauty_Profile
 * - Calculates skin type
 * - Generates personalised skincare routine advice
 * - Uses Post/Redirect/Get to prevent duplicate form submissions
 */

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';

require_login();

/* =========================================================
   ALLOWED VALUES
   ========================================================= */

$allowedOiliness = [
    'oily',
    'dry',
    'balanced'
];

$allowedYesNo = [
    'yes',
    'no'
];

$allowedUndertones = [
    'cool',
    'neutral',
    'warm'
];

$concernLabels = [
    'none' => 'No major concern',
    'breakouts' => 'Breakouts & clogged pores',
    'dryness' => 'Dryness & dehydration',
    'redness' => 'Redness & sensitivity',
    'dark-spots' => 'Dark spots & uneven tone',
    'dullness' => 'Dullness',
    'fine-lines' => 'Fine lines & early ageing'
];


/* =========================================================
   CALCULATE SKIN TYPE
   ========================================================= */

function lg_quiz_skin_type(
    string $oiliness,
    string $tightness,
    string $shine
): string {

    $drySigns =
        $oiliness === 'dry'
        || $tightness === 'yes';

    $oilySigns =
        $oiliness === 'oily'
        || $shine === 'yes';

    /*
     * If the answers contain both oily and dry signs,
     * classify the skin as combination.
     */
    if ($drySigns && $oilySigns) {
        return 'combination';
    }

    if ($oilySigns) {
        return 'oily';
    }

    if ($drySigns) {
        return 'dry';
    }

    return 'normal';
}


/* =========================================================
   ROUTINE GENERATOR
   ========================================================= */

function lg_quiz_routine(
    string $skinType,
    string $concern
): array {

    /*
     * Base routine according to calculated skin type.
     */
    $skinRoutines = [

        'oily' => [
            'summary' =>
                'Your answers suggest that your skin produces noticeable oil or shine. '
                . 'A simple routine that cleans without stripping the skin and uses '
                . 'lightweight hydration is usually a good starting point.',

            'morning' => [
                [
                    'title' => 'Gentle cleanse',
                    'text' =>
                        'Wash with a gentle facial cleanser to remove overnight oil '
                        . 'without leaving the skin feeling tight.'
                ],
                [
                    'title' => 'Light hydration',
                    'text' =>
                        'Use a lightweight moisturizer. Gel or lotion textures may '
                        . 'feel more comfortable than very heavy creams.'
                ],
                [
                    'title' => 'Sun protection',
                    'text' =>
                        'Finish with broad-spectrum SPF 30 or higher every morning.'
                ]
            ],

            'evening' => [
                [
                    'title' => 'Cleanse',
                    'text' =>
                        'Remove makeup and sunscreen, then cleanse gently. Avoid '
                        . 'aggressive scrubbing even when the skin feels oily.'
                ],
                [
                    'title' => 'Target your main concern',
                    'text' =>
                        'Use one treatment suited to your selected concern rather '
                        . 'than layering many strong active products together.'
                ],
                [
                    'title' => 'Moisturize',
                    'text' =>
                        'Finish with a light moisturizer to support the skin barrier.'
                ]
            ],

            'tip' =>
                'Very harsh cleansers can leave oily skin feeling stripped. '
                . 'Gentle cleansing and consistent hydration are usually better '
                . 'than repeatedly trying to remove every trace of oil.'
        ],


        'dry' => [
            'summary' =>
                'Your answers suggest that your skin may lose moisture easily or '
                . 'feel tight. Your routine should focus on gentle cleansing and '
                . 'maintaining hydration.',

            'morning' => [
                [
                    'title' => 'Gentle start',
                    'text' =>
                        'Use a mild cleanser, or simply rinse with lukewarm water '
                        . 'if your skin does not need a full morning cleanse.'
                ],
                [
                    'title' => 'Hydrate and moisturize',
                    'text' =>
                        'Apply hydrating products followed by a comfortable '
                        . 'moisturizer to help reduce that tight feeling.'
                ],
                [
                    'title' => 'Sun protection',
                    'text' =>
                        'Finish with broad-spectrum SPF 30 or higher.'
                ]
            ],

            'evening' => [
                [
                    'title' => 'Gentle cleanse',
                    'text' =>
                        'Remove sunscreen and makeup with a non-stripping cleanser.'
                ],
                [
                    'title' => 'Add hydration',
                    'text' =>
                        'Apply a hydrating serum or similar gentle hydration step '
                        . 'while the skin is still comfortable.'
                ],
                [
                    'title' => 'Seal in moisture',
                    'text' =>
                        'Use a richer moisturizer at night if your skin still '
                        . 'feels dry after lighter products.'
                ]
            ],

            'tip' =>
                'Avoid very hot water and frequent harsh exfoliation because both '
                . 'can make dry or tight-feeling skin more uncomfortable.'
        ],


        'combination' => [
            'summary' =>
                'Your answers show both oily and dry characteristics. Combination '
                . 'skin often benefits from a balanced routine rather than treating '
                . 'the whole face as completely oily or completely dry.',

            'morning' => [
                [
                    'title' => 'Balanced cleanse',
                    'text' =>
                        'Use a gentle cleanser that removes excess oil without '
                        . 'making the drier areas feel tight.'
                ],
                [
                    'title' => 'Flexible hydration',
                    'text' =>
                        'Use a lightweight moisturizer overall. You can apply a '
                        . 'little more to areas that feel dry.'
                ],
                [
                    'title' => 'Sun protection',
                    'text' =>
                        'Use broad-spectrum SPF 30 or higher every morning.'
                ]
            ],

            'evening' => [
                [
                    'title' => 'Cleanse',
                    'text' =>
                        'Gently remove makeup, sunscreen and daily buildup.'
                ],
                [
                    'title' => 'Treat selectively',
                    'text' =>
                        'Apply stronger treatment products only where they are '
                        . 'needed instead of covering every area automatically.'
                ],
                [
                    'title' => 'Moisturize',
                    'text' =>
                        'Finish with comfortable hydration and add more moisturizer '
                        . 'to dry areas if necessary.'
                ]
            ],

            'tip' =>
                'Combination skin does not need exactly the same amount of product '
                . 'on every area of the face.'
        ],


        'normal' => [
            'summary' =>
                'Your answers suggest fairly balanced skin without strong oily or '
                . 'dry characteristics. A consistent, simple routine can help '
                . 'maintain that balance.',

            'morning' => [
                [
                    'title' => 'Gentle cleanse',
                    'text' =>
                        'Cleanse gently or rinse according to what feels comfortable '
                        . 'for your skin.'
                ],
                [
                    'title' => 'Moisturize',
                    'text' =>
                        'Use a moisturizer that feels comfortable and does not leave '
                        . 'your skin excessively dry or greasy.'
                ],
                [
                    'title' => 'Sun protection',
                    'text' =>
                        'Finish every morning with broad-spectrum SPF 30 or higher.'
                ]
            ],

            'evening' => [
                [
                    'title' => 'Cleanse',
                    'text' =>
                        'Remove sunscreen, makeup and daily buildup before bed.'
                ],
                [
                    'title' => 'Optional treatment',
                    'text' =>
                        'Use a gentle treatment only when you have a specific '
                        . 'skin concern you want to focus on.'
                ],
                [
                    'title' => 'Moisturize',
                    'text' =>
                        'Finish with a simple moisturizer.'
                ]
            ],

            'tip' =>
                'Balanced skin usually benefits from consistency. You do not need '
                . 'to add many active products when your current routine feels comfortable.'
        ]
    ];


    /*
     * Extra advice based on the concern selected by the user.
     */
    $concernAdvice = [

        'none' => [
            'title' => 'Maintain your skin balance',
            'text' =>
                'You did not select a major concern, so focus on consistency: '
                . 'gentle cleansing, comfortable moisturization and daily sun protection.',
            'avoid' =>
                'Avoid adding several unnecessary active products at the same time.'
        ],


        'breakouts' => [
            'title' => 'Focus on keeping the routine simple',
            'text' =>
                'Choose gentle, non-heavy products where possible. If your skin '
                . 'tolerates it, a salicylic-acid skincare product can be introduced '
                . 'gradually instead of using several exfoliating products together.',
            'avoid' =>
                'Avoid harsh scrubs and picking at breakouts. Persistent, painful '
                . 'or scarring acne should be discussed with a qualified healthcare professional.'
        ],


        'dryness' => [
            'title' => 'Give extra attention to hydration',
            'text' =>
                'Look for gentle hydrating products and moisturizers designed to '
                . 'support the skin barrier. Apply moisturizer before the skin '
                . 'becomes extremely dry or uncomfortable.',
            'avoid' =>
                'Avoid very hot water, harsh cleansing and excessive exfoliation.'
        ],


        'redness' => [
            'title' => 'Keep the routine calm and gentle',
            'text' =>
                'Introduce new products slowly. Simple fragrance-free or low-irritation '
                . 'formulas may be easier to tolerate when skin is easily irritated.',
            'avoid' =>
                'Avoid aggressive scrubs and introducing several strong active '
                . 'ingredients at the same time.'
        ],


        'dark-spots' => [
            'title' => 'Prioritize daily sun protection',
            'text' =>
                'Consistent sunscreen is especially important when uneven tone or '
                . 'dark marks are a concern. Gentle products containing ingredients '
                . 'such as niacinamide or vitamin C can also be considered if tolerated.',
            'avoid' =>
                'Avoid repeatedly changing products or over-exfoliating in an '
                . 'attempt to get very fast results.'
        ],


        'dullness' => [
            'title' => 'Focus on hydration and gentle brightening',
            'text' =>
                'Keep the skin hydrated and consider a gentle brightening step such '
                . 'as niacinamide or vitamin C. Introduce only one new treatment at a time.',
            'avoid' =>
                'Avoid frequent harsh scrubbing. More exfoliation does not always '
                . 'mean brighter-looking skin.'
        ],


        'fine-lines' => [
            'title' => 'Protect and moisturize consistently',
            'text' =>
                'Daily sunscreen and regular moisturization are useful foundations '
                . 'for a routine focused on the appearance of fine lines. Keep the '
                . 'routine consistent before adding multiple treatment products.',
            'avoid' =>
                'Avoid skipping sunscreen while relying only on treatment products.'
        ]
    ];


    return [
        'skin' =>
            $skinRoutines[$skinType]
            ?? $skinRoutines['normal'],

        'concern' =>
            $concernAdvice[$concern]
            ?? $concernAdvice['none']
    ];
}


/* =========================================================
   DEFAULT FORM VALUES
   ========================================================= */

$formValues = [
    'oiliness' => 'balanced',
    'tightness' => 'no',
    'shine' => 'no',
    'undertone' => 'neutral',
    'concern' => 'none'
];

$quizResult = null;


/* =========================================================
   FORM SUBMISSION
   ========================================================= */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    require_csrf_token();

    $oiliness = strtolower(
        trim((string) ($_POST['oiliness'] ?? 'balanced'))
    );

    $tightness = strtolower(
        trim((string) ($_POST['tightness'] ?? 'no'))
    );

    $shine = strtolower(
        trim((string) ($_POST['shine'] ?? 'no'))
    );

    $undertone = strtolower(
        trim((string) ($_POST['undertone'] ?? 'neutral'))
    );

    $concern = strtolower(
        trim((string) ($_POST['concern'] ?? 'none'))
    );


    /* Validate all submitted values */

    if (!in_array($oiliness, $allowedOiliness, true)) {
        $oiliness = 'balanced';
    }

    if (!in_array($tightness, $allowedYesNo, true)) {
        $tightness = 'no';
    }

    if (!in_array($shine, $allowedYesNo, true)) {
        $shine = 'no';
    }

    if (!in_array($undertone, $allowedUndertones, true)) {
        $undertone = 'neutral';
    }

    if (!array_key_exists($concern, $concernLabels)) {
        $concern = 'none';
    }


    /* Calculate skin type */

    $resultType = lg_quiz_skin_type(
        $oiliness,
        $tightness,
        $shine
    );


    /* Store all quiz answers as JSON */

    $answers = json_encode(
        [
            'oiliness' => $oiliness,
            'tightness' => $tightness,
            'shine' => $shine,
            'undertone' => $undertone,
            'concern' => $concern
        ],
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    );


    $userId = (int) current_user()['user_id'];


    /* Save quiz attempt */

    $quizStmt = $pdo->prepare(
        "
        INSERT INTO Skin_Quiz
        (
            user_id,
            answers,
            result_skin_type
        )
        VALUES (?, ?, ?)
        "
    );

    $quizStmt->execute([
        $userId,
        $answers,
        $resultType
    ]);


    /* Save / update the user's beauty profile */

    $profileStmt = $pdo->prepare(
        "
        INSERT INTO Beauty_Profile
        (
            user_id,
            skin_type,
            concern
        )
        VALUES (?, ?, ?)

        ON DUPLICATE KEY UPDATE

            skin_type = VALUES(skin_type),
            concern = VALUES(concern)
        "
    );

    $profileStmt->execute([
        $userId,
        $resultType,
        $concernLabels[$concern]
    ]);


    /*
     * Redirect back to the Beauty Quiz result page.
     *
     * This prevents the browser from re-submitting the quiz
     * if the customer refreshes the page.
     */

    header(
        'Location: '
        . lg_url('/modules/products/beauty-quiz.php?result=1')
    );

    exit;
}


/* =========================================================
   LOAD MOST RECENT RESULT
   ========================================================= */

if (isset($_GET['result']) && $_GET['result'] === '1') {

    $userId = (int) current_user()['user_id'];

    $resultStmt = $pdo->prepare(
        "
        SELECT
            answers,
            result_skin_type,
            created_at

        FROM Skin_Quiz

        WHERE user_id = ?

        ORDER BY quiz_id DESC

        LIMIT 1
        "
    );

    $resultStmt->execute([$userId]);

    $latestQuiz = $resultStmt->fetch();


    if ($latestQuiz) {

        $savedAnswers = json_decode(
            $latestQuiz['answers'] ?? '{}',
            true
        );

        if (!is_array($savedAnswers)) {
            $savedAnswers = [];
        }


        $skinType = strtolower(
            (string) ($latestQuiz['result_skin_type'] ?? 'normal')
        );

        if (!in_array(
            $skinType,
            ['oily', 'dry', 'combination', 'normal'],
            true
        )) {
            $skinType = 'normal';
        }


        $concern = strtolower(
            (string) ($savedAnswers['concern'] ?? 'none')
        );

        if (!array_key_exists($concern, $concernLabels)) {
            $concern = 'none';
        }


        $undertone = strtolower(
            (string) ($savedAnswers['undertone'] ?? 'neutral')
        );

        if (!in_array($undertone, $allowedUndertones, true)) {
            $undertone = 'neutral';
        }


        $routineData = lg_quiz_routine(
            $skinType,
            $concern
        );


        $quizResult = [

            'skin_type' => $skinType,

            'skin_type_label' =>
                ucfirst($skinType) . ' skin',

            'concern' => $concern,

            'concern_label' =>
                $concernLabels[$concern],

            'undertone' => $undertone,

            'undertone_label' =>
                ucfirst($undertone),

            'oiliness' =>
                $savedAnswers['oiliness']
                ?? 'balanced',

            'tightness' =>
                $savedAnswers['tightness']
                ?? 'no',

            'shine' =>
                $savedAnswers['shine']
                ?? 'no',

            'routine' =>
                $routineData['skin'],

            'concern_advice' =>
                $routineData['concern'],

            'created_at' =>
                $latestQuiz['created_at']
        ];
    }
}


/* =========================================================
   PAGE
   ========================================================= */

$pageKey = 'products/beauty-quiz';

require_once __DIR__ . '/../../includes/header.php';

require __DIR__ . '/../../views/products/beauty-quiz.view.php';

require_once __DIR__ . '/../../includes/footer.php';