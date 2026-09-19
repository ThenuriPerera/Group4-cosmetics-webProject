<?php
if (!defined('LG_VIEW')) {
    http_response_code(404);
    exit;
}
?>

<section class="beauty-quiz-shell">

<?php if ($quizResult): ?>

    <div class="beauty-result-dashboard">

        <!-- =====================================================
             1. MAIN RESULT
             ===================================================== -->

        <section class="profile-result-card">

            <div class="profile-result-content">

                <span class="result-mini-label">
                    YOUR BEAUTY PROFILE
                </span>

                <p class="result-overline">
                    Based on your quiz answers
                </p>

                <h1 class="profile-result-title">
                    <?= htmlspecialchars(
                        ucfirst($quizResult['skin_type']),
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>

                    <span>Skin</span>
                </h1>

                <p class="profile-result-summary">
                    <?= htmlspecialchars(
                        $quizResult['routine']['summary'],
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </p>

            </div>


            <div class="profile-result-stats">

                <div class="profile-stat">

                    <span class="profile-stat-icon">
                        ◇
                    </span>

                    <div>
                        <small>SKIN TYPE</small>

                        <strong>
                            <?= htmlspecialchars(
                                ucfirst($quizResult['skin_type']),
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </strong>
                    </div>

                </div>


                <div class="profile-stat">

                    <span class="profile-stat-icon">
                        ✦
                    </span>

                    <div>
                        <small>MAIN CONCERN</small>

                        <strong>
                            <?= htmlspecialchars(
                                $quizResult['concern_label'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </strong>
                    </div>

                </div>


                <div class="profile-stat">

                    <span class="profile-stat-icon">
                        ♡
                    </span>

                    <div>
                        <small>UNDERTONE</small>

                        <strong>
                            <?= htmlspecialchars(
                                $quizResult['undertone_label'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </strong>
                    </div>

                </div>

            </div>

        </section>



        <!-- =====================================================
             QUIZ ANSWERS - COLLAPSED
             ===================================================== -->

        <details class="quiz-answer-details">

            <summary>
                <span>
                    View the answers used for this result
                </span>

                <span class="details-arrow">
                    +
                </span>
            </summary>

            <div class="quiz-answer-details-content">

                <div>

                    <small>
                        GENERAL SKIN FEEL
                    </small>

                    <strong>
                        <?= htmlspecialchars(
                            ucfirst($quizResult['oiliness']),
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </strong>

                </div>


                <div>

                    <small>
                        TIGHT AFTER WASHING
                    </small>

                    <strong>
                        <?= $quizResult['tightness'] === 'yes'
                            ? 'Yes'
                            : 'No' ?>
                    </strong>

                </div>


                <div>

                    <small>
                        MIDDAY SHINE
                    </small>

                    <strong>
                        <?= $quizResult['shine'] === 'yes'
                            ? 'Yes'
                            : 'No' ?>
                    </strong>

                </div>

            </div>

        </details>



        <!-- =====================================================
             2. ROUTINE
             ===================================================== -->

        <section class="beauty-result-section">

            <div class="result-section-heading">

                <div>

                    <span class="section-number">
                        01
                    </span>

                    <div>

                        <p class="section-label">
                            YOUR DAILY ROUTINE
                        </p>

                        <h2>
                            Your simple skincare plan
                        </h2>

                    </div>

                </div>

                <p>
                    Start with these essential steps.
                    You can adjust products later as you learn
                    what works best for your skin.
                </p>

            </div>


            <div class="daily-routine-grid">


                <!-- MORNING -->

                <article class="daily-routine-card routine-am">

                    <div class="daily-routine-header">

                        <div class="routine-time-icon">
                            ☀
                        </div>

                        <div>

                            <small>
                                AM ROUTINE
                            </small>

                            <h3>
                                Morning
                            </h3>

                        </div>

                    </div>


                    <div class="routine-list">

                        <?php foreach (
                            $quizResult['routine']['morning']
                            as $index => $step
                        ): ?>

                            <div class="routine-list-item">

                                <div class="routine-list-number">
                                    <?= $index + 1 ?>
                                </div>

                                <div>

                                    <h4>
                                        <?= htmlspecialchars(
                                            $step['title'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                    </h4>

                                    <p>
                                        <?= htmlspecialchars(
                                            $step['text'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                    </p>

                                </div>

                            </div>

                        <?php endforeach; ?>

                    </div>

                </article>



                <!-- EVENING -->

                <article class="daily-routine-card routine-pm">

                    <div class="daily-routine-header">

                        <div class="routine-time-icon">
                            ☾
                        </div>

                        <div>

                            <small>
                                PM ROUTINE
                            </small>

                            <h3>
                                Evening
                            </h3>

                        </div>

                    </div>


                    <div class="routine-list">

                        <?php foreach (
                            $quizResult['routine']['evening']
                            as $index => $step
                        ): ?>

                            <div class="routine-list-item">

                                <div class="routine-list-number">
                                    <?= $index + 1 ?>
                                </div>

                                <div>

                                    <h4>
                                        <?= htmlspecialchars(
                                            $step['title'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                    </h4>

                                    <p>
                                        <?= htmlspecialchars(
                                            $step['text'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                    </p>

                                </div>

                            </div>

                        <?php endforeach; ?>

                    </div>

                </article>

            </div>

        </section>



        <!-- =====================================================
             3. MAIN CONCERN
             ===================================================== -->

        <section class="beauty-result-section concern-section">

            <div class="concern-card">

                <div class="concern-card-top">

                    <span class="concern-icon">
                        ✦
                    </span>

                    <div>

                        <p>
                            YOUR PRIORITY
                        </p>

                        <h2>
                            <?= htmlspecialchars(
                                $quizResult['concern_label'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </h2>

                    </div>

                </div>


                <div class="concern-card-body">

                    <div class="concern-main-advice">

                        <small>
                            WHAT TO FOCUS ON
                        </small>

                        <h3>
                            <?= htmlspecialchars(
                                $quizResult['concern_advice']['title'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </h3>

                        <p>
                            <?= htmlspecialchars(
                                $quizResult['concern_advice']['text'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </p>

                    </div>


                    <div class="important-note">

                        <span class="important-note-label">
                            IMPORTANT
                        </span>

                        <p>
                            <?= htmlspecialchars(
                                $quizResult['concern_advice']['avoid'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </p>

                    </div>

                </div>

            </div>

        </section>



        <!-- =====================================================
             4. EXTRA PROFILE INFORMATION
             ===================================================== -->

        <section class="beauty-result-section">

            <div class="result-section-heading compact-heading">

                <div>

                    <span class="section-number">
                        02
                    </span>

                    <div>

                        <p class="section-label">
                            EXTRA PROFILE DETAILS
                        </p>

                        <h2>
                            Know your skin better
                        </h2>

                    </div>

                </div>

            </div>


            <div class="profile-extra-grid">


                <!-- SKIN TYPE TIP -->

                <article class="profile-extra-card">

                    <div class="extra-card-icon">
                        ♡
                    </div>

                    <p class="extra-card-label">
                        SKIN TYPE TIP
                    </p>

                    <h3>
                        For your
                        <?= htmlspecialchars(
                            ucfirst($quizResult['skin_type']),
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                        skin
                    </h3>

                    <p>
                        <?= htmlspecialchars(
                            $quizResult['routine']['tip'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </p>

                </article>



                <!-- UNDERTONE -->

                <article class="profile-extra-card undertone-card">

                    <div class="extra-card-icon">
                        ◇
                    </div>

                    <p class="extra-card-label">
                        YOUR UNDERTONE
                    </p>

                    <h3>
                        <?= htmlspecialchars(
                            $quizResult['undertone_label'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                        Undertone
                    </h3>

                    <p>
                        Your undertone is mainly useful when
                        choosing complexion products such as
                        foundation and concealer.
                    </p>

                    <a
                        class="extra-card-link"
                        href="<?= htmlspecialchars(
                            lg_url(
                                'modules/products/shade-finder.php'
                            ),
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>"
                    >
                        Try Shade Finder
                        <span>→</span>
                    </a>

                </article>

            </div>

        </section>



        <!-- =====================================================
             5. SHOP ROUTINE
             ===================================================== -->

        <section class="beauty-result-section">

            <div class="result-section-heading">

                <div>

                    <span class="section-number">
                        03
                    </span>

                    <div>

                        <p class="section-label">
                            SHOP YOUR ROUTINE
                        </p>

                        <h2>
                            Start with the essentials
                        </h2>

                    </div>

                </div>

                <p>
                    Browse the Luminé Glow catalogue by the
                    main steps of your recommended routine.
                </p>

            </div>


            <div class="routine-product-grid">


                <!-- CLEANSER -->

                <a
                    class="routine-product-card"
                    href="<?= htmlspecialchars(
                        lg_url(
                            'modules/products/index.php'
                            . '?category_id=2'
                            . '&sub_category='
                            . urlencode('Cleansers')
                        ),
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                >

                    <span class="routine-product-number">
                        01
                    </span>

                    <div>

                        <small>
                            FIRST STEP
                        </small>

                        <h3>
                            Cleanser
                        </h3>

                        <p>
                            Remove oil, sunscreen,
                            makeup and daily buildup.
                        </p>

                    </div>

                    <span class="routine-product-arrow">
                        →
                    </span>

                </a>



                <!-- TREATMENT -->

                <a
                    class="routine-product-card"
                    href="<?= htmlspecialchars(
                        lg_url(
                            'modules/products/index.php'
                            . '?category_id=2'
                            . '&sub_category='
                            . urlencode('Treatments')
                        ),
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                >

                    <span class="routine-product-number">
                        02
                    </span>

                    <div>

                        <small>
                            TARGET STEP
                        </small>

                        <h3>
                            Treatment
                        </h3>

                        <p>
                            Focus on your main skin concern
                            without overloading your routine.
                        </p>

                    </div>

                    <span class="routine-product-arrow">
                        →
                    </span>

                </a>



                <!-- MOISTURIZER -->

                <a
                    class="routine-product-card"
                    href="<?= htmlspecialchars(
                        lg_url(
                            'modules/products/index.php'
                            . '?category_id=2'
                            . '&sub_category='
                            . urlencode('Moisturizers')
                        ),
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                >

                    <span class="routine-product-number">
                        03
                    </span>

                    <div>

                        <small>
                            FINAL STEP
                        </small>

                        <h3>
                            Moisturizer
                        </h3>

                        <p>
                            Help keep your skin comfortable
                            and support its moisture barrier.
                        </p>

                    </div>

                    <span class="routine-product-arrow">
                        →
                    </span>

                </a>

            </div>

        </section>



        <!-- =====================================================
             6. FINAL ACTIONS
             ===================================================== -->

        <section class="beauty-result-footer">

            <div>

                <p>
                    READY TO EXPLORE?
                </p>

                <h2>
                    Build your routine with Luminé Glow
                </h2>

            </div>


            <div class="beauty-result-actions">

                <a
                    class="beauty-main-action"
                    href="<?= htmlspecialchars(
                        lg_url(
                            'modules/products/index.php?category_id=2'
                        ),
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                >
                    Explore Skincare
                </a>


                <a
                    class="beauty-outline-action"
                    href="<?= htmlspecialchars(
                        lg_url(
                            'modules/products/beauty-quiz.php'
                        ),
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                >
                    Retake Quiz
                </a>

            </div>

        </section>


        <p class="beauty-result-disclaimer">
            This quiz provides general cosmetic skincare guidance,
            not a medical diagnosis. Patch-test new products and
            seek professional advice for persistent, painful or
            severe skin concerns.
        </p>

    </div>


<?php else: ?>

    <!-- =====================================================
         QUIZ FORM
         ===================================================== -->

    <div class="beauty-quiz-intro">

        <p class="quiz-kicker">
            FIND YOUR ROUTINE
        </p>

        <h2>
            Tell us a little about your skin
        </h2>

        <p>
            Three quick steps will help us build a simple
            skincare routine around how your skin feels and
            the concern you want to focus on.
        </p>

    </div>


    <form
        method="post"
        id="quiz-form"
        class="quiz-form"
    >

        <input
            type="hidden"
            name="csrf_token"
            value="<?= htmlspecialchars(
                csrf_token(),
                ENT_QUOTES,
                'UTF-8'
            ) ?>"
        >


        <div class="quiz-progress">

            <div class="quiz-progress-top">

                <span id="quiz-progress-label">
                    Step 1 of 3
                </span>

                <span>
                    Beauty profile
                </span>

            </div>

            <div class="quiz-progress-track">

                <div
                    id="quiz-progress-fill"
                    class="quiz-progress-fill"
                ></div>

            </div>

        </div>



        <!-- STEP 1 -->

        <div
            class="quiz-step"
            data-step="1"
        >

            <p class="step-eyebrow">
                STEP 01
            </p>

            <h3>
                How does your skin usually feel?
            </h3>

            <p class="step-description">
                Think about how your face normally feels
                throughout an average day.
            </p>


            <label class="quiz-field">

                <span>
                    General skin feel
                </span>

                <select
                    name="oiliness"
                    required
                >

                    <option value="balanced">
                        Fairly balanced
                    </option>

                    <option value="oily">
                        Oily / shiny most of the day
                    </option>

                    <option value="dry">
                        Dry / tight / flaky
                    </option>

                </select>

            </label>


            <div class="quiz-navigation quiz-navigation-end">

                <button
                    type="button"
                    class="next-btn quiz-primary-btn"
                >
                    Continue
                </button>

            </div>

        </div>



        <!-- STEP 2 -->

        <div
            class="quiz-step"
            data-step="2"
            hidden
        >

            <p class="step-eyebrow">
                STEP 02
            </p>

            <h3>
                A little more about your skin
            </h3>

            <p class="step-description">
                These answers help distinguish dry,
                oily and combination characteristics.
            </p>


            <div class="quiz-two-column">

                <label class="quiz-field">

                    <span>
                        Does your skin feel tight after washing?
                    </span>

                    <select
                        name="tightness"
                        required
                    >

                        <option value="no">
                            No
                        </option>

                        <option value="yes">
                            Yes
                        </option>

                    </select>

                </label>


                <label class="quiz-field">

                    <span>
                        Do you see visible shine by midday?
                    </span>

                    <select
                        name="shine"
                        required
                    >

                        <option value="no">
                            No
                        </option>

                        <option value="yes">
                            Yes
                        </option>

                    </select>

                </label>

            </div>


            <label class="quiz-field">

                <span>
                    Your undertone
                </span>

                <select
                    name="undertone"
                    required
                >

                    <option value="neutral">
                        Neutral
                    </option>

                    <option value="warm">
                        Warm
                    </option>

                    <option value="cool">
                        Cool
                    </option>

                </select>

                <small>
                    Undertone helps with future makeup
                    and shade suggestions.
                </small>

            </label>


            <div class="quiz-navigation">

                <button
                    type="button"
                    class="prev-btn quiz-secondary-btn"
                >
                    Back
                </button>

                <button
                    type="button"
                    class="next-btn quiz-primary-btn"
                >
                    Continue
                </button>

            </div>

        </div>



        <!-- STEP 3 -->

        <div
            class="quiz-step"
            data-step="3"
            hidden
        >

            <p class="step-eyebrow">
                STEP 03
            </p>

            <h3>
                What would you most like to focus on?
            </h3>

            <p class="step-description">
                Choose one main concern so your routine
                advice can stay simple and focused.
            </p>


            <label class="quiz-field">

                <span>
                    Main skin concern
                </span>

                <select
                    name="concern"
                    required
                >

                    <option value="none">
                        No major concern
                    </option>

                    <option value="breakouts">
                        Breakouts & clogged pores
                    </option>

                    <option value="dryness">
                        Dryness & dehydration
                    </option>

                    <option value="redness">
                        Redness & sensitivity
                    </option>

                    <option value="dark-spots">
                        Dark spots & uneven tone
                    </option>

                    <option value="dullness">
                        Dullness
                    </option>

                    <option value="fine-lines">
                        Fine lines & early ageing
                    </option>

                </select>

            </label>


            <div class="quiz-navigation">

                <button
                    type="button"
                    class="prev-btn quiz-secondary-btn"
                >
                    Back
                </button>

                <button
                    type="submit"
                    id="quiz-submit"
                    class="quiz-primary-btn"
                >
                    Create My Routine
                </button>

            </div>

        </div>

    </form>

<?php endif; ?>

</section>