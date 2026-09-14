<?php if (!defined('LG_VIEW')) { http_response_code(404); exit; } ?>
<section class="admin-products">

    <h1>Category Manager</h1>
    <p>Add, edit, and organize the categories that your products belong to.</p>

    <?php if ($message): ?><p class="success"><?= htmlspecialchars($message) ?></p><?php endif; ?>
    <?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>

    <h2><?= $editCategory ? 'Edit Category' : 'Add New Category' ?></h2>
    <form method="post">
        <?php if ($editCategory): ?>
            <input type="hidden" name="category_id" value="<?= $editCategory['category_id'] ?>">
        <?php endif; ?>

        <label>Name
            <input type="text" name="category_name"
                   value="<?= htmlspecialchars($editCategory['category_name'] ?? '') ?>"
                   required>
        </label>

        <label>Description
            <textarea name="category_description"><?= htmlspecialchars($editCategory['category_description'] ?? '') ?></textarea>
        </label>

        <button type="submit" name="save_category">
            <?= $editCategory ? 'Update' : 'Add' ?> Category
        </button>

        <?php if ($editCategory): ?>
            <a href="<?= htmlspecialchars(LG_BASE_PATH, ENT_QUOTES, 'UTF-8') ?>/modules/products/categories.php">Cancel</a>
        <?php endif; ?>
    </form>

    <h2>All Categories</h2>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Products</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($categories as $c): ?>
            <tr>
                <td><?= htmlspecialchars($c['category_name']) ?></td>
                <td><?= htmlspecialchars($c['category_description'] ?? '—') ?></td>
                <td><?= $c['product_count'] ?></td>
                <td>
                    <a href="?edit=<?= $c['category_id'] ?>">Edit</a> |
                    <?php if ($c['product_count'] == 0): ?>
                        <a href="?delete=<?= $c['category_id'] ?>"
                           onclick="return confirm('Delete this category?');">Delete</a>
                    <?php else: ?>
                        <span title="Cannot delete — products are using this category"
                              style="color: #999; cursor: not-allowed;">Delete</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</section>