<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Créer un Match</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            padding: 40px 20px;
        }

        .container {
            max-width: 600px;
            margin: auto;
        }

        .card {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, .1);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #1e3c72;
        }

        input, select {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 14px;
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
        }

        button:hover {
            background: #1e3c72;
        }

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
        <h2>Créer un Match</h2>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">✅ Match créé avec succès !</div>
        <?php elseif (isset($_GET['error'])): ?>
            <div class="alert alert-error">❌ <?= htmlspecialchars($_GET['error']) ?></div>
        <?php endif; ?>

        <form method="POST" action="/buy-match/match-form">
            <input type="text" name="team1_name" placeholder="Nom de l'équipe 1" required>
            <input type="text" name="team1_logo" placeholder="Logo URL équipe 1 (optionnel)">
            <input type="text" name="team2_name" placeholder="Nom de l'équipe 2" required>
            <input type="text" name="team2_logo" placeholder="Logo URL équipe 2 (optionnel)">
            <input type="datetime-local" name="date_time" placeholder="Date et heure" required>
            <input type="number" name="duration" placeholder="Durée (minutes)" value="90" min="1">
            <input type="text" name="location" placeholder="Lieu" required>
            <input type="number" name="max_seats" placeholder="Nombre maximum de places" value="2000" min="1">

            <button type="submit">Créer le match</button>
        </form>
    </div>
</div>

</body>
</html>
