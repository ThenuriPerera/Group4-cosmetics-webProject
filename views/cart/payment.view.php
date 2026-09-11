<?php if (!defined('LG_VIEW')) { http_response_code(404); exit; } ?>
<section class="payment-page">

    <?php if ($discountPct > 0): ?>
        <p>Subtotal: Rs. <?= number_format($total, 2) ?> — Promo discount: <?= $discountPct ?>%</p>
    <?php endif; ?>
    <p class="payment-total">Order total: Rs. <?= number_format($discountedTotal, 2) ?></p>
    <p>You'll be redirected to Stripe's secure checkout page (test mode — use card <code>4242 4242 4242 4242</code>, any future date, any CVC).</p>

    <form method="post">
        <button type="submit" name="start_stripe_checkout">Pay with Stripe</button>
    </form>
</section>
