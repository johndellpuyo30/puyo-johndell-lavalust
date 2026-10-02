<?php
$noticeMessages = [
    'created' => 'Product added successfully.',
    'updated' => 'Product updated successfully.',
    'deleted' => 'Product deleted successfully.',
    'not-found' => 'Product not found.'
];
$noticeText = $noticeMessages[$notice] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="<?= htmlspecialchars(base_url('assets/css/products.css'), ENT_QUOTES, 'UTF-8') ?>">
</head>
<body>
    <nav class="topbar">
        <div>
            <strong>Product Management</strong>
            <span class="nav-user">Signed in as <?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?></span>
        </div>
        <a href="<?= htmlspecialchars(site_url('logout'), ENT_QUOTES, 'UTF-8') ?>" class="btn light">Logout</a>
    </nav>

    <main class="container">
        <section class="page-header">
            <div>
                <h1>Products</h1>
                <p class="muted">Create, view, edit, and delete product records stored in MySQL.</p>
            </div>
            <a href="<?= htmlspecialchars(site_url('products/create'), ENT_QUOTES, 'UTF-8') ?>" class="btn primary">Add Product</a>
        </section>

        <?php if ($noticeText !== ''): ?>
            <div class="alert success"><?= htmlspecialchars($noticeText, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>

        <?php if (!empty($products)): ?>
            <div class="table-card">
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Product Name</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product): ?>
                                <?php $id = (int) ($product['id'] ?? 0); ?>
                                <tr>
                                    <td><?= $id ?></td>
                                    <td><strong><?= htmlspecialchars((string) ($product['product_name'] ?? ''), ENT_QUOTES, 'UTF-8') ?></strong></td>
                                    <td><?= htmlspecialchars((string) ($product['description'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                                    <td>₱<?= number_format((float) ($product['price'] ?? 0), 2) ?></td>
                                    <td><?= (int) ($product['quantity'] ?? 0) ?></td>
                                    <td><?= htmlspecialchars((string) ($product['created_at'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                                    <td>
                                        <div class="actions">
                                            <a href="<?= htmlspecialchars(site_url('products/edit/' . $id), ENT_QUOTES, 'UTF-8') ?>" class="btn small edit">Edit</a>
                                            <a href="<?= htmlspecialchars(site_url('products/delete/' . $id), ENT_QUOTES, 'UTF-8') ?>" class="btn small danger">Delete</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <h2>No products yet</h2>
                <p class="muted">Add your first product to test the Create operation.</p>
                <a href="<?= htmlspecialchars(site_url('products/create'), ENT_QUOTES, 'UTF-8') ?>" class="btn primary">Add First Product</a>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
