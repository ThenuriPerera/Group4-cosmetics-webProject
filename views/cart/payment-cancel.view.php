<?php if (!defined('LG_VIEW')) { http_response_code(404); exit; } ?>
<section class="payment-page">
<p class="eyebrow">YOUR BAG IS STILL HERE</p>
<h2>Let's try that again.</h2>
<p>You returned from checkout without completing this payment. Review your bag or return to delivery details.</p>
<div class="button-row">
<a class="btn" href="<?= htmlspecialchars(lg_url('modules/cart/checkout.php')) ?>">Return to checkout</a>
<a class="btn btn-outline" href="<?= htmlspecialchars(lg_url('modules/cart/cart.php')) ?>">View my bag</a>
</div></section>
