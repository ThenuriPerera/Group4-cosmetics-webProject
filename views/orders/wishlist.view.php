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

$wishlistCount = count($wishlistItems);

$wishlistTotal = 0;

foreach ($wishlistItems as $item) {
    $wishlistTotal += (float) $item['price'];
}

?>

<style>

.wishlist-page {
    max-width: 1200px;
    margin: 40px auto;
    padding: 0 20px 60px;
}

.wishlist-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    margin-bottom: 30px;
}

.wishlist-heading h2 {
    margin: 5px 0;
    font-size: 36px;
}

.wishlist-heading p {
    color: #777;
}

.wishlist-eyebrow {
    font-size: 12px;
    letter-spacing: 2px;
    font-weight: bold;
}

.wishlist-shop-link {
    text-decoration: none;
    padding: 12px 20px;
    border: 1px solid #222;
    border-radius: 25px;
    color: #222;
}

.wishlist-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 18px;
    margin-bottom: 35px;
}

.wishlist-stat {
    padding: 22px;
    border-radius: 16px;
    background: #f8f4f6;
}

.wishlist-stat span {
    display: block;
    color: #777;
    font-size: 14px;
    margin-bottom: 8px;
}

.wishlist-stat strong {
    font-size: 25px;
}

.wishlist-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 22px;
}

.wishlist-card {
    background: white;
    border: 1px solid #eee;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,0.06);
    transition: transform 0.2s ease;
}

.wishlist-card:hover {
    transform: translateY(-4px);
}

.wishlist-image {
    height: 230px;
    background: #f7f3f5;
    display: flex;
    align-items: center;
    justify-content: center;
}

.wishlist-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.wishlist-placeholder {
    font-size: 55px;
    color: #c8aab7;
}

.wishlist-card-content {
    padding: 18px;
}

.wishlist-brand {
    font-size: 12px;
    color: #999;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.wishlist-card h3 {
    font-size: 18px;
    margin: 8px 0;
}

.wishlist-category {
    color: #777;
    font-size: 13px;
    margin-bottom: 12px;
}

.wishlist-price {
    font-size: 20px;
    font-weight: bold;
    margin-bottom: 15px;
}

.wishlist-stock {
    display: inline-block;
    font-size: 12px;
    padding: 6px 10px;
    border-radius: 15px;
    background: #eaf7ed;
    color: #28733b;
    margin-bottom: 15px;
}

.wishlist-actions {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.wishlist-button {
    width: 100%;
    padding: 10px;
    border-radius: 20px;
    border: none;
    cursor: pointer;
    text-align: center;
    text-decoration: none;
    font-size: 14px;
    box-sizing: border-box;
}

.wishlist-view {
    background: #222;
    color: white;
}

.wishlist-bag {
    background: #ead7df;
    color: #222;
}

.wishlist-remove {
    background: white;
    border: 1px solid #ddd;
    color: #777;
}

.wishlist-alert {
    padding: 14px 18px;
    border-radius: 10px;
    margin-bottom: 20px;
}

.wishlist-success {
    background: #eaf7ed;
    color: #28733b;
}

.wishlist-error {
    background: #fdecec;
    color: #a33;
}

.wishlist-empty {
    text-align: center;
    padding: 80px 20px;
    background: #faf7f8;
    border-radius: 20px;
}

.wishlist-empty-icon {
    font-size: 65px;
    color: #c5a2b2;
}

.wishlist-empty h3 {
    font-size: 26px;
}

.wishlist-empty a {
    display: inline-block;
    margin-top: 15px;
    padding: 12px 25px;
    background: #222;
    color: white;
    border-radius: 25px;
    text-decoration: none;
}

@media (max-width: 1000px) {

    .wishlist-grid {
        grid-template-columns: repeat(2, 1fr);
    }

}

@media (max-width: 700px) {

    .wishlist-heading {
        flex-direction: column;
        align-items: flex-start;
    }

    .wishlist-stats {
        grid-template-columns: 1fr;
    }

    .wishlist-grid {
        grid-template-columns: 1fr;
    }

}

</style>


<section class="wishlist-page">

    <?php if ($wishlistMessage): ?>

        <div class="wishlist-alert wishlist-success">
            <?= htmlspecialchars(
                $wishlistMessage,
                ENT_QUOTES,
                'UTF-8'
            ) ?>
        </div>

    <?php endif; ?>


    <?php if ($wishlistError): ?>

        <div class="wishlist-alert wishlist-error">
            <?= htmlspecialchars(
                $wishlistError,
                ENT_QUOTES,
                'UTF-8'
            ) ?>
        </div>

    <?php endif; ?>


    <div class="wishlist-heading">

        <div>

            <div class="wishlist-eyebrow">
                BEAUTY, SIMPLIFIED
            </div>

            <h2>
                My wishlist
            </h2>

            <p>
                Keep your favourite products close.
            </p>

        </div>


        <a
            class="wishlist-shop-link"
            href="<?= $basePath ?>/modules/products/index.php"
        >
            Continue Shopping →
        </a>

    </div>


    <?php if (!empty($wishlistItems)): ?>


        <!-- WISHLIST STATISTICS -->

        <div class="wishlist-stats">

            <div class="wishlist-stat">

                <span>
                    Saved Products
                </span>

                <strong>
                    <?= $wishlistCount ?>
                </strong>

            </div>


            <div class="wishlist-stat">

                <span>
                    Wishlist Value
                </span>

                <strong>
                    Rs. <?= number_format($wishlistTotal, 2) ?>
                </strong>

            </div>


            <div class="wishlist-stat">

                <span>
                    Beauty Collection
                </span>

                <strong>
                    <?= $wishlistCount ?> items
                </strong>

            </div>

        </div>


        <!-- WISHLIST PRODUCTS -->

        <div class="wishlist-grid">


            <?php foreach ($wishlistItems as $item): ?>

                <?php

                $image = lg_image_url($item);

                $stock = (int) $item['stock'];

                ?>


                <article class="wishlist-card">


                    <!-- IMAGE -->

                    <div class="wishlist-image">

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

                            <div class="wishlist-placeholder">
                                ♡
                            </div>

                        <?php endif; ?>

                    </div>


                    <!-- PRODUCT INFORMATION -->

                    <div class="wishlist-card-content">


                        <div class="wishlist-brand">

                            <?= htmlspecialchars(
                                $item['brand_name']
                                ?? 'Lumine Glow',
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>

                        </div>


                        <h3>

                            <?= htmlspecialchars(
                                $item['product_name'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>

                        </h3>


                        <div class="wishlist-category">

                            <?= htmlspecialchars(
                                $item['category_name']
                                ?? 'Beauty Product',
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>

                        </div>


                        <div class="wishlist-price">

                            Rs.
                            <?= number_format(
                                (float) $item['price'],
                                2
                            ) ?>

                        </div>


                        <?php if ($stock > 0): ?>

                            <div class="wishlist-stock">

                                ✓ In Stock

                            </div>

                        <?php else: ?>

                            <div
                                class="wishlist-stock"
                                style="
                                    background:#fdecec;
                                    color:#a33;
                                "
                            >

                                Out of Stock

                            </div>

                        <?php endif; ?>


                        <!-- ACTIONS -->

                        <div class="wishlist-actions">


                            <a
                                class="wishlist-button wishlist-view"
                                href="<?= $basePath ?>/modules/products/product.php?id=<?= (int) $item['product_id'] ?>"
                            >
                                View Product
                            </a>


                            <?php if ($stock > 0): ?>

                                <form
                                    method="post"
                                    action="<?= $basePath ?>/modules/orders/wishlist.php"
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
                                        value="<?= (int) $item['product_id'] ?>"
                                    >

                                    <button
                                        type="submit"
                                        name="move_to_cart"
                                        class="wishlist-button wishlist-bag"
                                    >
                                        Move to Bag
                                    </button>

                                </form>

                            <?php endif; ?>


                            <form
                                method="post"
                                action="<?= $basePath ?>/modules/orders/wishlist.php"
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
                                    value="<?= (int) $item['product_id'] ?>"
                                >

                                <button
                                    type="submit"
                                    name="remove_wishlist"
                                    class="wishlist-button wishlist-remove"
                                >
                                    Remove
                                </button>

                            </form>


                        </div>

                    </div>

                </article>


            <?php endforeach; ?>


        </div>


    <?php else: ?>


        <div class="wishlist-empty">

            <div class="wishlist-empty-icon">
                ♡
            </div>

            <h3>
                Your wishlist is empty
            </h3>

            <p>
                Add your favourite products to see them here.
            </p>

            <a
                href="<?= $basePath ?>/modules/products/index.php"
            >
                Explore Products
            </a>

        </div>


    <?php endif; ?>


</section>