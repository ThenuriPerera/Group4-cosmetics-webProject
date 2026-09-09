<?php if (!defined('LG_VIEW')) { http_response_code(404); exit; } ?>
<?php if ($message): ?><p class="success" role="status"><?= htmlspecialchars($message) ?></p><?php endif; ?>
<div class="profile-grid">
<section class="panel"><h2>Personal details</h2>
<form method="post">
<label>Full name<input type="text" name="name" autocomplete="name" value="<?= htmlspecialchars($user['name']) ?>" required></label>
<label>Email address<input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled></label>
<label>Phone number<input type="tel" name="phone" autocomplete="tel" value="<?= htmlspecialchars($user['phone'] ?? '') ?>"></label>
<button type="submit" name="update_profile">Save details</button>
</form>
</section>
<section class="panel"><h2>Delivery addresses</h2>
<?php foreach ($addresses as $addr): ?>
<div class="address-card"><p><?= htmlspecialchars("{$addr['street']}, {$addr['city']}, {$addr['state']} {$addr['postal_code']}, {$addr['country']}") ?></p><a href="?delete_address=<?= $addr['address_id'] ?>" data-confirm="Delete this address?">Remove address</a></div>
<?php endforeach; ?>
<?php if (!$addresses): ?><p>No saved addresses yet. Add your first address below.</p><?php endif; ?>
<h3>Add an address</h3>
<form method="post" class="form-grid">
<label class="span-full">Street address<input type="text" name="street" autocomplete="street-address" required></label>
<label>City<input type="text" name="city" autocomplete="address-level2" required></label>
<label>State / province<input type="text" name="state" autocomplete="address-level1"></label>
<label>Postal code<input type="text" name="postal_code" autocomplete="postal-code"></label>
<label>Country<input type="text" name="country" autocomplete="country-name" required></label>
<button type="submit" name="add_address">Save address</button>
</form></section></div>
