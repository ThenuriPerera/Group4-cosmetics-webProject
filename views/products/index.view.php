<?php if (!defined('LG_VIEW')) { http_response_code(404); exit; } ?>
<section class="catalogue">


    <form method="get" class="search-bar">
        <input type="search" name="q" aria-label="Search the collection" value="<?= htmlspecialchars($search) ?>" placeholder="Search products...">
        <button type="submit">Search</button>
    </form>

    <div class="filter-tree">
        <!-- Level 1: Main Categories -->
        <div class="filter-level">
            <strong>Category</strong>
            <a href="<?= htmlspecialchars(LG_BASE_PATH, ENT_QUOTES, 'UTF-8') ?>/modules/products/index.php" class="<?= !$categoryId ? 'active' : '' ?>">All</a>
            <?php foreach ($categories as $cat): ?>
                <a href="?category_id=<?= $cat['category_id'] ?>" class="<?= $categoryId == $cat['category_id'] ? 'active' : '' ?>">
                    <?= htmlspecialchars($cat['category_name']) ?>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- Level 2: Sub-categories, only shown once a category is picked -->
        <?php if ($categoryId && $subCategories): ?>
            <div class="filter-level">
                <strong>Sub-Category</strong>
                <a href="?category_id=<?= $categoryId ?>" class="<?= !$subCategory ? 'active' : '' ?>">All</a>
                <?php foreach ($subCategories as $sc): ?>
                    <a href="?category_id=<?= $categoryId ?>&sub_category=<?= urlencode($sc) ?>" class="<?= $subCategory === $sc ? 'active' : '' ?>">
                        <?= htmlspecialchars($sc) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Level 3: Product types, only shown once a sub-category is picked -->
        <?php if ($subCategory && $productTypes): ?>
            <div class="filter-level">
                <strong>Type</strong>
                <a href="?category_id=<?= $categoryId ?>&sub_category=<?= urlencode($subCategory) ?>" class="<?= !$productType ? 'active' : '' ?>">All</a>
                <?php foreach ($productTypes as $pt): ?>
                    <a href="?category_id=<?= $categoryId ?>&sub_category=<?= urlencode($subCategory) ?>&product_type=<?= urlencode($pt) ?>" class="<?= $productType === $pt ? 'active' : '' ?>">
                        <?= htmlspecialchars($pt) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="product-grid">
        <?php foreach ($products as $p): ?>
            <div class="product-card">
                <?php $displayImage = lg_image_url($p); ?>
                <?php if ($displayImage !== ''): ?>
                <img src="<?= htmlspecialchars($displayImage, ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($p['product_name']) ?>" loading="lazy">
                <?php else: ?><div class="image-missing">Product photo coming soon</div><?php endif; ?>
                <h3><?= htmlspecialchars($p['product_name']) ?></h3>
                <p>Rs. <?= number_format($p['price'], 2) ?></p>
                <a href="<?= htmlspecialchars(LG_BASE_PATH, ENT_QUOTES, 'UTF-8') ?>/modules/products/product.php?id=<?= $p['product_id'] ?>">View</a>
            </div>
        <?php endforeach; ?>
        <?php if (empty($products)): ?>
            <div class="empty-state"><h2>No matches just yet.</h2><p>Try a different search or explore the full collection.</p><a class="btn btn-outline" href="<?= htmlspecialchars(lg_url('modules/products/index.php')) ?>">Clear filters</a></div>
        <?php endif; ?>
    </div>
</section>
