<?php
$isEdit = $mode === 'edit';

$title = $isEdit ? 'Edit User' : 'Add User';

$action = $isEdit
    ? site_url('users/update/' . (int) $id)
    : site_url('users/store');

$value = function ($key) use ($user) {
    return htmlspecialchars(
        (string) ($user[$key] ?? ''),
        ENT_QUOTES,
        'UTF-8'
    );
};
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></title>

    <link
        rel="stylesheet"
        href="<?= htmlspecialchars(base_url('assets/css/users-crud.css'), ENT_QUOTES, 'UTF-8') ?>"
    >

</head>

<body>


<div class="form-container">

    <a
        href="<?= htmlspecialchars(site_url('users'), ENT_QUOTES, 'UTF-8') ?>"
        class="back"
    >
        ← Back
    </a>


    <h1>
        <?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>
    </h1>

    <p class="subtitle">
        <?= $isEdit ? 'Update user information.' : 'Enter the user information.' ?>
    </p>


    <form
        method="post"
        action="<?= htmlspecialchars($action, ENT_QUOTES, 'UTF-8') ?>"
    >


        <div class="row">


            <div class="field">

                <label for="firstname">
                    First Name
                </label>

                <input
                    id="firstname"
                    name="firstname"
                    type="text"
                    value="<?= $value('firstname') ?>"
                    required
                >

                <?php if (isset($errors['firstname'])): ?>

                    <small>
                        <?= htmlspecialchars($errors['firstname'], ENT_QUOTES, 'UTF-8') ?>
                    </small>

                <?php endif; ?>

            </div>


            <div class="field">

                <label for="lastname">
                    Last Name
                </label>

                <input
                    id="lastname"
                    name="lastname"
                    type="text"
                    value="<?= $value('lastname') ?>"
                    required
                >

                <?php if (isset($errors['lastname'])): ?>

                    <small>
                        <?= htmlspecialchars($errors['lastname'], ENT_QUOTES, 'UTF-8') ?>
                    </small>

                <?php endif; ?>

            </div>


        </div>


        <div class="field">

            <label for="email">
                Email
            </label>

            <input
                id="email"
                name="email"
                type="email"
                value="<?= $value('email') ?>"
                required
            >

            <?php if (isset($errors['email'])): ?>

                <small>
                    <?= htmlspecialchars($errors['email'], ENT_QUOTES, 'UTF-8') ?>
                </small>

            <?php endif; ?>

        </div>


        <div class="field">

            <label for="username">
                Username
            </label>

            <input
                id="username"
                name="username"
                type="text"
                value="<?= $value('username') ?>"
                required
            >

            <?php if (isset($errors['username'])): ?>

                <small>
                    <?= htmlspecialchars($errors['username'], ENT_QUOTES, 'UTF-8') ?>
                </small>

            <?php endif; ?>

        </div>


        <div class="form-actions">

            <a
                href="<?= htmlspecialchars(site_url('users'), ENT_QUOTES, 'UTF-8') ?>"
                class="btn cancel"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="btn primary"
            >
                <?= $isEdit ? 'Save' : 'Create' ?>
            </button>

        </div>


    </form>

</div>


</body>
</html>