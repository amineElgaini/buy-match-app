<?php include __DIR__ . '/partials/nav.php'; ?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Gestion des utilisateurs</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            padding: 40px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #2a5298;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
        }

        th {
            background-color: #2a5298;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        form {
            display: inline-block;
        }

        button {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        .disable {
            background-color: #e74c3c;
            color: white;
        }

        .enable {
            background-color: #27ae60;
            color: white;
        }

        .disabled-label {
            color: #e74c3c;
            font-weight: bold;
        }

        .active-label {
            color: #27ae60;
            font-weight: bold;
        }

        .table-container {
            max-width: 900px;
            margin: auto;
        }
    </style>
</head>

<body>
    <div class="table-container">
        <h2>Gestion des utilisateurs</h2>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['name']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= ucfirst($user['role']) ?></td>
                        <td>
                            <?php if ($user['active']): ?>
                                <span class="active-label">Actif</span>
                            <?php else: ?>
                                <span class="disabled-label">Désactivé</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($user['active'] && $user['role'] !== 'admin'): ?>
                                <form method="POST" action="/buy-match/admin/users/disable">
                                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                    <button type="submit" class="disable">Désactiver</button>
                                </form>
                            <?php elseif (!$user['active']): ?>
                                <form method="POST" action="/buy-match/admin/users/enable">
                                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                    <button type="submit" class="enable">Activer</button>
                                </form>
                            <?php else: ?>
                                <span>—</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>
