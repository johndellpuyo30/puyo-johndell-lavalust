<?php
$isEdit = $mode === 'edit';
$title = $isEdit ? 'Edit Product' : 'Add Product';
$action = $isEdit
    ? site_url('products/update/' . (int) $id)
    : site_url('products/store');

$value = function ($key) use ($product) {
    return htmlspecialchars((string) ($product[$key] ?? ''), ENT_QUOTES, 'UTF-8');
};
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="<?= htmlspecialchars(base_url('assets/css/products.css'), ENT_QUOTES, 'UTF-8') ?>">
</head>
<body>
    <main class="form-shell">
        <a href="<?= htmlspecialchars(site_url('products'), ENT_QUOTES, 'UTF-8') ?>" class="back-link">← Back to Products</a>
        <div class="form-card">
            <h1><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h1>
            <p class="muted"><?= $isEdit ? 'Update the selected product record.' : 'Enter the information for the new product.' ?></p>

            <form method="post" action="<?= htmlspecialchars($action, ENT_QUOTES, 'UTF-8') ?>" class="stack-form">
                <div class="field">
                    <label for="product_name">Product Name</label>
                    <input id="product_name" name="product_name" type="text" maxlength="100" value="<?= $value('product_name') ?>" required>
                    <?php if (isset($errors['product_name'])): ?><small class="field-error"><?= htmlspecialchars($errors['product_name'], ENT_QUOTES, 'UTF-8') ?></small><?php endif; ?>
                </div>

                <div class="field">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="4" required><?= $value('description') ?></textarea>
                    <?php if (isset($errors['description'])): ?><small class="field-error"><?= htmlspecialchars($errors['description'], ENT_QUOTES, 'UTF-8') ?></small><?php endif; ?>
                </div>

                <div class="form-grid">
                    <div class="field">
                        <label for="price">Price</label>
                        <input id="price" name="price" type="number" min="0" step="0.01" value="<?= $value('price') ?>" required>
                        <?php if (isset($errors['price'])): ?><small class="field-error"><?= htmlspecialchars($errors['price'], ENT_QUOTES, 'UTF-8') ?></small><?php endif; ?>
                    </div>

                    <div class="field">
                        <label for="quantity">Quantity</label>
                        <input id="quantity" name="quantity" type="number" min="0" step="1" value="<?= $value('quantity') ?>" required>
                        <?php if (isset($errors['quantity'])): ?><small class="field-error"><?= htmlspecialchars($errors['quantity'], ENT_QUOTES, 'UTF-8') ?></small><?php endif; ?>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="<?= htmlspecialchars(site_url('products'), ENT_QUOTES, 'UTF-8') ?>" class="btn light">Cancel</a>
                    <button type="submit" class="btn primary"><?= $isEdit ? 'Save Changes' : 'Create Product' ?></button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
