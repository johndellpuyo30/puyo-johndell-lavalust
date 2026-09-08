<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #eef2f7;
            color: #1e293b;
        }

        .page {
            width: min(1150px, calc(100% - 36px));
            margin: 50px auto;
        }

        .top-section {
            background: linear-gradient(135deg, #0f172a, #1e3a5f);
            padding: 32px;
            border-radius: 16px 16px 0 0;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
        }

        .title-area h1 {
            margin: 0;
            font-size: 30px;
            font-weight: 700;
        }

        .title-area p {
            margin: 8px 0 0;
            color: #cbd5e1;
            font-size: 14px;
        }

        .status {
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 9px 15px;
            border-radius: 20px;
            font-size: 13px;
            white-space: nowrap;
        }

        .content {
            background: #ffffff;
            border-radius: 0 0 16px 16px;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.10);
            overflow: hidden;
        }

        .table-header {
            padding: 20px 26px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-header h2 {
            margin: 0;
            font-size: 17px;
        }

        .record-count {
            color: #64748b;
            font-size: 13px;
        }

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            padding: 15px 20px;
            text-align: left;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            color: #475569;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            white-space: nowrap;
        }

        td {
            padding: 17px 20px;
            border-bottom: 1px solid #edf2f7;
            color: #334155;
            font-size: 14px;
            white-space: nowrap;
        }

        tbody tr {
            transition: background 0.2s ease;
        }

        tbody tr:hover {
            background: #f8fafc;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        .id {
            font-weight: bold;
            color: #2563eb;
        }

        .name {
            font-weight: 600;
            color: #0f172a;
        }

        .email {
            color: #475569;
        }

        .username {
            display: inline-block;
            background: #e0ecff;
            color: #1d4ed8;
            padding: 6px 11px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .empty {
            padding: 55px 20px;
            text-align: center;
            color: #64748b;
        }

        .empty h3 {
            margin: 0 0 8px;
            color: #334155;
        }

        .empty p {
            margin: 0;
            font-size: 14px;
        }

        .footer {
            padding: 16px 26px;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            font-size: 12px;
            color: #64748b;
        }

        @media (max-width: 700px) {
            .page {
                width: calc(100% - 20px);
                margin: 20px auto;
            }

            .top-section {
                flex-direction: column;
                align-items: flex-start;
                padding: 24px;
            }

            .title-area h1 {
                font-size: 25px;
            }

            .table-header {
                padding: 18px 20px;
            }

            th,
            td {
                padding: 14px 16px;
            }
        }
    </style>
</head>

<body>

<div class="page">

    <div class="top-section">
        <div class="title-area">
            <h1>User Management</h1>
            <p>
                Records retrieved dynamically from the
                <strong>users</strong> table.
            </p>
        </div>

        <div class="status">
            Database Records
        </div>
    </div>

    <div class="content">

        <?php if (!empty($users)) : ?>

            <div class="table-header">
                <h2>Users</h2>

                <div class="record-count">
                    <?= count($users) ?> record<?= count($users) !== 1 ? 's' : '' ?>
                </div>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Username</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php foreach ($users as $user) : ?>

                        <?php
                        $id = is_array($user)
                            ? ($user['id'] ?? '')
                            : ($user->id ?? '');

                        $firstname = is_array($user)
                            ? ($user['firstname'] ?? '')
                            : ($user->firstname ?? '');

                        $lastname = is_array($user)
                            ? ($user['lastname'] ?? '')
                            : ($user->lastname ?? '');

                        $email = is_array($user)
                            ? ($user['email'] ?? '')
                            : ($user->email ?? '');

                        $username = is_array($user)
                            ? ($user['username'] ?? '')
                            : ($user->username ?? '');
                        ?>

                        <tr>
                            <td class="id">
                                <?= htmlspecialchars((string) $id, ENT_QUOTES, 'UTF-8') ?>
                            </td>

                            <td class="name">
                                <?= htmlspecialchars((string) $firstname, ENT_QUOTES, 'UTF-8') ?>
                            </td>

                            <td>
                                <?= htmlspecialchars((string) $lastname, ENT_QUOTES, 'UTF-8') ?>
                            </td>

                            <td class="email">
                                <?= htmlspecialchars((string) $email, ENT_QUOTES, 'UTF-8') ?>
                            </td>

                            <td>
                                <span class="username">
                                    <?= htmlspecialchars((string) $username, ENT_QUOTES, 'UTF-8') ?>
                                </span>
                            </td>
                        </tr>

                    <?php endforeach; ?>

                    </tbody>
                </table>
            </div>

            <div class="footer">
                Data is retrieved from the MySQL users table through LavaLust MVC.
            </div>

        <?php else : ?>

            <div class="empty">
                <h3>No User Records</h3>
                <p>No user records found.</p>
            </div>

        <?php endif; ?>

    </div>

</div>

</body>
</html>