<?php $id = (int) ($product['id'] ?? 0); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Product</title>
    <link rel="stylesheet" href="<?= htmlspecialchars(base_url('assets/css/products.css'), ENT_QUOTES, 'UTF-8') ?>">
</head>
<body>
    <main class="form-shell">
        <a href="<?= htmlspecialchars(site_url('products'), ENT_QUOTES, 'UTF-8') ?>" class="back-link">← Back to Products</a>
        <div class="form-card delete-card">
            <div class="danger-icon">!</div>
            <h1>Delete Product</h1>
            <p>Are you sure you want to delete <strong><?= htmlspecialchars((string) ($product['product_name'] ?? ''), ENT_QUOTES, 'UTF-8') ?></strong>?</p>
            <p class="muted">This action removes the product record from the database.</p>

            <form method="post" action="<?= htmlspecialchars(site_url('products/delete/' . $id), ENT_QUOTES, 'UTF-8') ?>" class="form-actions">
                <a href="<?= htmlspecialchars(site_url('products'), ENT_QUOTES, 'UTF-8') ?>" class="btn light">Cancel</a>
                <button type="submit" class="btn danger">Delete Product</button>
            </form>
        </div>
    </main>
</body>
</html>
