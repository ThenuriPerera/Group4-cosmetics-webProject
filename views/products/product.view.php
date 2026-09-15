<?php
if (!defined('LG_VIEW')) {
    http_response_code(404);
    exit;
}

$displayImage = lg_image_url($product);

$wishlistMessage = $_SESSION['wishlist_message'] ?? '';
$wishlistError = $_SESSION['wishlist_error'] ?? '';

unset($_SESSION['wishlist_message']);
unset($_SESSION['wishlist_error']);

$productId = (int) $product['product_id'];
?>

<section class="product-detail">

    <?php if ($wishlistMessage): ?>

        <p class="success">
            <?= htmlspecialchars(
                $wishlistMessage,
                ENT_QUOTES,
                'UTF-8'
            ) ?>

            <a
                href="<?= htmlspecialchars(
                    lg_url('/modules/orders/wishlist.php'),
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>"
            >
                View wishlist
            </a>
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

    <?php if ($displayImage !== ''): ?>

        <img
            src="<?= htmlspecialchars(
                $displayImage,
                ENT_QUOTES,
                'UTF-8'
            ) ?>"
            alt="<?= htmlspecialchars(
                $product['product_name'],
                ENT_QUOTES,
                'UTF-8'
            ) ?>"
        >

    <?php else: ?>

        <div class="image-missing">
            Product photo coming soon
        </div>

    <?php endif; ?>

    <div>

        <h2>
            <?= htmlspecialchars(
                $product['product_name'],
                ENT_QUOTES,
                'UTF-8'
            ) ?>
        </h2>

        <?php if ($avgRating): ?>

            <p>
                Rating:
                <?= number_format($avgRating, 1) ?>
                / 5
                (<?= count($reviews) ?> reviews)
            </p>

        <?php endif; ?>

        <p class="price">
            Rs.
            <?= number_format(
                (float) $product['price'],
                2
            ) ?>
        </p>

        <p>
            <?= nl2br(
                htmlspecialchars(
                    $product['description'] ?? '',
                    ENT_QUOTES,
                    'UTF-8'
                )
            ) ?>
        </p>

        <?php if (is_logged_in()): ?>

            <form
                method="post"
                action="<?= htmlspecialchars(
                    lg_url('/modules/cart/cart.php'),
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>"
            >

                <input
                    type="hidden"
                    name="product_id"
                    value="<?= $productId ?>"
                >

                <?php if (!empty($variants)): ?>

                    <label>
                        Variant

                        <select name="variant_id">

                            <?php foreach ($variants as $variant): ?>

                                <option
                                    value="<?= (int) $variant['variant_id'] ?>"
                                >
                                    <?= htmlspecialchars(
                                        trim(
                                            ($variant['shade'] ?? '') .
                                            ' ' .
                                            ($variant['size'] ?? '')
                                        ),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                    —

                                    Rs.
                                    <?= number_format(
                                        (float) (
                                            $variant['price']
                                            ?: $product['price']
                                        ),
                                        2
                                    ) ?>

                                    (
                                    <?= (int) $variant['stock'] ?>
                                    in stock)
                                </option>

                            <?php endforeach; ?>

                        </select>
                    </label>

                <?php endif; ?>

                <label>
                    Quantity

                    <input
                        type="number"
                        name="quantity"
                        value="1"
                        min="1"
                    >
                </label>

                <button
                    type="submit"
                    name="add_to_cart"
                >
                    Add to Cart
                </button>

            </form>

            <form
              method="post"
              action="<?= htmlspecialchars(
              lg_url('/modules/orders/wishlist.php'),
              ENT_QUOTES,
              'UTF-8'
            ) ?>"
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

    <input
        type="hidden"
        name="product_id"
        value="<?= (int) $productId ?>"
    >

    <button
        type="submit"
        name="add_wishlist"
        value="1"
    >
        ♡ Add to Wishlist
    </button>
</form>

        <?php else: ?>

            <p>
                <a
                    href="<?= htmlspecialchars(
                        lg_url('/modules/auth/login.php'),
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                >
                    Login
                </a>
                to add this product to your wishlist.
            </p>

        <?php endif; ?>

    </div>

</section>

<section class="reviews">

    <h2>Reviews</h2>

    <?php foreach ($reviews as $review): ?>

        <div class="review">

            <strong>
                <?= htmlspecialchars(
                    $review['name'],
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </strong>

            —
            <?= (int) $review['rating'] ?>/5

            <p>
                <?= htmlspecialchars(
                    $review['comment'],
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </p>

        </div>

    <?php endforeach; ?>

    <?php if (empty($reviews)): ?>

        <p>No reviews yet.</p>

    <?php endif; ?>

    <?php if (is_logged_in()): ?>

        <a
            href="<?= htmlspecialchars(
                lg_url(
                    '/modules/orders/reviews.php?product_id=' .
                    $productId
                ),
                ENT_QUOTES,
                'UTF-8'
            ) ?>"
        >
            Write a review
        </a>

    <?php endif; ?>

</section>