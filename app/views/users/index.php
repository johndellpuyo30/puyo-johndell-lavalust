<?php
$noticeMessages = [
    'created' => 'User added successfully.',
    'updated' => 'User updated successfully.',
    'deleted' => 'User deleted successfully.',
    'not-found' => 'User not found.'
];

$noticeText = $noticeMessages[$notice] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Users</title>

    <link
        rel="stylesheet"
        href="<?= htmlspecialchars(base_url('assets/css/users-crud.css'), ENT_QUOTES, 'UTF-8') ?>"
    >
</head>

<body>

<div class="container">

    <div class="header">

        <div>
            <h1>Users</h1>
            <p>Manage user records</p>
        </div>

        <a
            href="<?= htmlspecialchars(site_url('users/create'), ENT_QUOTES, 'UTF-8') ?>"
            class="btn primary"
        >
            Add User
        </a>

    </div>


    <?php if ($noticeText !== ''): ?>

        <div class="notice">
            <?= htmlspecialchars($noticeText, ENT_QUOTES, 'UTF-8') ?>
        </div>

    <?php endif; ?>


    <?php if (!empty($users)): ?>

        <div class="table-box">

            <table>

                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Username</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                <?php foreach ($users as $user): ?>

                    <?php
                    $id = (int) ($user['id'] ?? 0);
                    $first = (string) ($user['firstname'] ?? '');
                    $last = (string) ($user['lastname'] ?? '');
                    $email = (string) ($user['email'] ?? '');
                    $username = (string) ($user['username'] ?? '');
                    ?>

                    <tr>

                        <td>
                            <?= htmlspecialchars(trim($first . ' ' . $last), ENT_QUOTES, 'UTF-8') ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>
                        </td>

                        <td>
                            @<?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?>
                        </td>

                        <td>

                            <div class="actions">

                                <a
                                    href="<?= htmlspecialchars(site_url('users/edit/' . $id), ENT_QUOTES, 'UTF-8') ?>"
                                    class="btn edit"
                                >
                                    Edit
                                </a>

                                <form
                                    method="post"
                                    action="<?= htmlspecialchars(site_url('users/delete/' . $id), ENT_QUOTES, 'UTF-8') ?>"
                                    onsubmit="return confirm('Delete this user?');"
                                >

                                    <button
                                        type="submit"
                                        class="btn delete"
                                    >
                                        Delete
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    <?php else: ?>

        <div class="empty">

            <p>No users found.</p>

            <a
                href="<?= htmlspecialchars(site_url('users/create'), ENT_QUOTES, 'UTF-8') ?>"
                class="btn primary"
            >
                Add First User
            </a>

        </div>

    <?php endif; ?>


</div>

</body>
</html>