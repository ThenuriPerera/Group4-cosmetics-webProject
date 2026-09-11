<?php if (!defined('LG_VIEW')) { http_response_code(404); exit; } ?>
<?php if ($pageStyle === 'admin'): ?></div></div><?php endif; ?>
</main>
<footer class="site-footer">
<div><a class="logo" href="<?= htmlspecialchars(lg_url('index.php')) ?>">Luminé <span>Glow</span></a><p>Beauty that feels like you.<br>Discover your everyday glow.</p></div>
<div><strong>Discover</strong><a href="<?= htmlspecialchars(lg_url('modules/products/index.php')) ?>">Shop the collection</a><a href="<?= htmlspecialchars(lg_url('modules/products/shade-finder.php')) ?>">Find your shade</a><a href="<?= htmlspecialchars(lg_url('modules/products/beauty-quiz.php')) ?>">Beauty quiz</a></div>
<div><strong>Your space</strong><a href="<?= htmlspecialchars(lg_url('modules/auth/profile.php')) ?>">My account</a><a href="<?= htmlspecialchars(lg_url('modules/orders/track-order.php')) ?>">My orders</a><a href="<?= htmlspecialchars(lg_url('modules/cart/cart.php')) ?>">Shopping bag</a></div>
<p class="copyright">&copy; <?= date('Y') ?> Luminé Glow · Group 04</p>
</footer>
</body></html>
