<?php
if (!defined('LG_VIEW')) {
    http_response_code(404);
    exit;
}

$basePath = htmlspecialchars(
    LG_BASE_PATH,
    ENT_QUOTES,
    'UTF-8'
);

$wishlistMessage = $_SESSION['wishlist_message'] ?? '';
$wishlistError = $_SESSION['wishlist_error'] ?? '';

unset($_SESSION['wishlist_message']);
unset($_SESSION['wishlist_error']);
?>

<section class="wishlist-page">

    <?php if ($wishlistMessage): ?>

        <p class="success">
            <?= htmlspecialchars(
                $wishlistMessage,
                ENT_QUOTES,
                'UTF-8'
            ) ?>
        </p>

    <?php endif; ?>

    <?php if ($wishlistError): ?>

        <p class="error">
            <?= htmlspecialchars(
                $wishlistError,
                ENT_QUOTES,
                'UTF-8'
            ) ?>
        </p>

    <?php endif; ?>

    <div class="wishlist-heading">

        <div>
            <p class="eyebrow">YOUR BEAUTY COLLECTION</p>

            <h2>My wishlist</h2>

            <p>
                Save products you love and view them whenever you want.
            </p>
        </div>

        <a
            class="wishlist-shop-link"
            href="<?= $basePath ?>/modules/products/index.php"
        >
            Continue shopping →
        </a>

    </div>

    <?php if (empty($wishlistItems)): ?>

        <div class="wishlist-empty">

            <div class="wishlist-empty-icon">♡</div>

            <h3>Your wishlist is empty</h3>

            <p>
                Add your favourite products to see them here.
            </p>

            <a
                class="wishlist-view-button"
                href="<?= $basePath ?>/modules/products/index.php"
            >
                Explore products
            </a>

        </div>

    <?php else: ?>

        <div class="wishlist-grid">

            <?php foreach ($wishlistItems as $item): ?>

                <?php
                $image = lg_image_url($item);
                ?>

                <article class="wishlist-card">

                    <?php if ($image !== ''): ?>

                        <img
                            src="<?= htmlspecialchars(
                                $image,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>"
                            alt="<?= htmlspecialchars(
                                $item['product_name'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>"
                        >

                    <?php else: ?>

                        <div class="wishlist-image-placeholder">
                            ♡
                        </div>

                    <?php endif; ?>

                    <div class="wishlist-card-content">

                        <small>
                            <?= htmlspecialchars(
                                $item['brand_name'] ?? 'Luminé Glow',
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </small>

                        <h3>
                            <?= htmlspecialchars(
                                $item['product_name'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </h3>

                        <p class="wishlist-category">
                            <?= htmlspecialchars(
                                $item['category_name'] ?? 'Beauty product',
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </p>

                        <strong>
                            Rs.
                            <?= number_format(
                                (float) $item['price'],
                                2
                            ) ?>
                        </strong>

                        <div class="wishlist-actions">

                            <a
                                class="wishlist-view-button"
                                href="<?= $basePath ?>/modules/products/product.php?id=<?= (int) $item['product_id'] ?>"
                            >
                                View product
                            </a>

                            <form
                                method="post"
                                action="<?= $basePath ?>/modules/orders/wishlist.php"
                            >

                                <input
                                    type="hidden"
                                    name="product_id"
                                    value="<?= (int) $item['product_id'] ?>"
                                >

                                <button
                                    type="submit"
                                    name="remove_wishlist"
                                    class="wishlist-remove-button"
                                >
                                    Remove
                                </button>

                            </form>

                        </div>

                    </div>

                </article>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</section>