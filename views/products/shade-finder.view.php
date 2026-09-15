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
?>

<section class="shade-finder-page">

    <div class="shade-finder-intro">
        <p class="eyebrow">LUMINÉ GLOW SHADE MATCH</p>

        <h2>Find your perfect makeup shade</h2>

        <p>
            Select the colour closest to your natural skin tone.
            We will show makeup products that match your complexion.
        </p>
    </div>

    <form
        method="post"
        id="shade-form"
        class="shade-finder-form"
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
            name="skin_tone"
            id="skin_tone_input"
            value="<?= htmlspecialchars(
                $selectedTone,
                ENT_QUOTES,
                'UTF-8'
            ) ?>"
        >

        <h3>Select your skin tone</h3>

        <p class="palette-instruction">
            Choose one colour square that looks closest to your skin.
        </p>

        <div class="eight-tone-palette">

            <?php foreach ($shadePalette as $tone => $colour): ?>

                <button
                    type="button"
                    class="tone-square <?= $selectedTone === $tone ? 'selected' : '' ?>"
                    data-tone="<?= htmlspecialchars(
                        $tone,
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                    style="background-color: <?= htmlspecialchars(
                        $colour,
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                    aria-label="Skin tone <?= substr($tone, -1) ?>"
                    aria-pressed="<?= $selectedTone === $tone ? 'true' : 'false' ?>"
                ></button>

            <?php endforeach; ?>

        </div>

        <p
            id="tone-label"
            class="selected-tone-message"
        >
            <?php if ($selectedTone): ?>
                Skin tone selected.
            <?php else: ?>
                Please select one colour.
            <?php endif; ?>
        </p>

        <button
            type="submit"
            id="find-btn"
            class="find-shade-button"
            <?= $selectedTone ? '' : 'disabled' ?>
        >
            Find matching makeup
            <span>→</span>
        </button>

    </form>

    <?php if ($selectedTone): ?>

        <section class="shade-results">

            <div class="results-header">
                <div>
                    <p class="eyebrow">MATCHING PRODUCTS</p>

                    <h2>Makeup selected for you</h2>

                    <p>
                        Foundation, concealer, blush and powder products
                        matching your skin tone.
                    </p>
                </div>

                <span class="result-count">
                    <?= count($results) ?> products
                </span>
            </div>

            <?php if (!empty($results)): ?>

                <div class="product-grid">

                    <?php foreach ($results as $product): ?>

                        <?php
                        $image = lg_image_url($product);
                        ?>

                        <article class="product-card">

                            <?php if ($image !== ''): ?>

                                <img
                                    src="<?= htmlspecialchars(
                                        $image,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                                    alt="<?= htmlspecialchars(
                                        $product['product_name'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                                    loading="lazy"
                                >

                            <?php else: ?>

                                <div class="image-missing">
                                    Makeup
                                </div>

                            <?php endif; ?>

                            <div class="product-card-content">

                                <small>
                                    <?= htmlspecialchars(
                                        $product['brand_name'] ?? 'Luminé Glow',
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </small>

                                <h3>
                                    <?= htmlspecialchars(
                                        $product['product_name'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </h3>

                                <p>
                                    <?= htmlspecialchars(
                                        $product['product_type']
                                        ?? $product['category_name']
                                        ?? 'Makeup',
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </p>

                                <strong>
                                    Rs.
                                    <?= number_format(
                                        (float) $product['price'],
                                        2
                                    ) ?>
                                </strong>

                                <a
                                    href="<?= $basePath ?>/modules/products/product.php?id=<?= (int) $product['product_id'] ?>"
                                >
                                    View product →
                                </a>

                            </div>
                        </article>

                    <?php endforeach; ?>

                </div>

            <?php else: ?>

                <div class="empty-state">
                    <h2>No matching makeup found</h2>
                    <p>Try selecting another colour.</p>

                    <a
                        href="<?= $basePath ?>/modules/products/index.php"
                    >
                        Browse all products
                    </a>
                </div>

            <?php endif; ?>

        </section>

    <?php endif; ?>

</section>