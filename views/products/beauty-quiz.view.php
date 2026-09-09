<?php if (!defined('LG_VIEW')) { http_response_code(404); exit; } ?>
<section class="beauty-quiz">

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
