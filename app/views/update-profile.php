<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Mon Profil</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            padding: 40px 20px;
        }

        .container {
            max-width: 500px;
            margin: auto;
        }

        .card {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, .1);
            position: relative;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #1e3c72;
        }

        .info {
            text-align: center;
            margin-bottom: 20px;
            color: #666;
        }

        input {
            width: 100%;
            padding: 14px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
            transition: 0.3s;
        }

        input:focus {
            border-color: #1e3c72;
            outline: none;
            box-shadow: 0 0 5px rgba(30, 60, 114, 0.3);
        }

        button {
            width: 100%;
            padding: 14px;
            border: none;
            background: #2a5298;
            color: white;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #1e3c72;
        }

        /* Success / Error Alerts */
        .alert {
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
        }

        .alert-success {
            background-color: #4CAF50;
            color: white;
        }

        .alert-error {
            background-color: #f44336;
            color: white;
        }
    </style>
</head>

<body>

    <?php include __DIR__ . '/partials/nav.php'; ?>

    <div class="container">
        <div class="card">
            <h2>Mon Profil</h2>

            <!-- Alerts -->
            <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                <div class="alert alert-success">
                    ✅ Profil mis à jour avec succès !
                </div>
            <?php elseif (isset($_GET['error']) && !empty($_GET['error'])): ?>
                <div class="alert alert-error">
                    ❌ <?= htmlspecialchars($_GET['error']) ?>
                </div>
            <?php endif; ?>

            <div class="info">
                Rôle : <strong><?= ucfirst($_SESSION['user_role']) ?></strong>
            </div>

            <form method="POST" action="/buy-match/profile">
                <input type="text" name="name" value="<?= htmlspecialchars($_SESSION['user_name']) ?>" placeholder="Nom complet" required>
                <input type="email" name="email" value="<?= htmlspecialchars($_SESSION['user_email'] ?? '') ?>" placeholder="Nouvel email (optionnel)">
                <input type="password" name="password" placeholder="Nouveau mot de passe (optionnel)">

                <button type="submit">Mettre à jour</button>
            </form>
        </div>
    </div>

</body>

</html>