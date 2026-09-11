<?php if (!defined('LG_VIEW')) { http_response_code(404); exit; } ?>
<?php if ($promoError): ?><p class="error" role="alert"><?= htmlspecialchars($promoError) ?></p><?php endif; ?>
<section class="checkout-page checkout-grid">
<form method="post"><h2>Where shall we deliver?</h2>
<?php if ($addresses): ?>
<?php foreach ($addresses as $addr): ?>
<label><input type="radio" name="address_id" value="<?= $addr['address_id'] ?>" required><?= htmlspecialchars("{$addr['street']}, {$addr['city']}, {$addr['country']}") ?></label>
<?php endforeach; ?>
<?php else: ?><p>No saved addresses yet. Add a delivery address to continue.</p><?php endif; ?>
<label>Promo code<input type="text" name="promo_code" placeholder="Enter a code, if you have one"></label>
<button type="submit" name="continue_to_payment" <?= !$addresses ? 'disabled' : '' ?>>Continue to payment</button>
</form>
<form method="post"><h2>Add a new address</h2><div class="form-grid">
<label class="span-full">Street address<input type="text" name="street" autocomplete="street-address" required></label>
<label>City<input type="text" name="city" autocomplete="address-level2" required></label>
<label>State / province<input type="text" name="state" autocomplete="address-level1"></label>
<label>Postal code<input type="text" name="postal_code" autocomplete="postal-code"></label>
<label>Country<input type="text" name="country" autocomplete="country-name" required></label>
</div><button type="submit" name="add_address">Save address</button></form>
</section>
