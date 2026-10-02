<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Product Management</title>
    <link rel="stylesheet" href="<?= htmlspecialchars(base_url('assets/css/products.css'), ENT_QUOTES, 'UTF-8') ?>">
</head>
<body class="auth-page">
    <main class="auth-card">
        <div class="brand-mark">PL</div>
        <h1>Product Management</h1>
        <p class="muted">Sign in to access the protected CRUD pages.</p>

        <?php if (!empty($error)): ?>
            <div class="alert error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>

        <?php if (!empty($notice)): ?>
            <div class="alert success"><?= htmlspecialchars($notice, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>

        <form method="post" action="<?= htmlspecialchars(site_url('login'), ENT_QUOTES, 'UTF-8') ?>" class="stack-form">
            <div class="field">
                <label for="username">Username</label>
                <input id="username" name="username" type="text" autocomplete="username" required>
            </div>

            <div class="field">
                <label for="password">Password</label>
                <input id="password" name="password" type="password" autocomplete="current-password" required>
            </div>

            <button class="btn primary full" type="submit">Login</button>
        </form>

        <p class="demo-note">Demo account after importing the SQL: <strong>admin</strong> / <strong>admin123</strong></p>
    </main>
</body>
</html>
