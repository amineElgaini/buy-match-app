<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Matches disponibles</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            min-height: 100vh;
            padding: 40px;
        }

        h1 {
            text-align: center;
            color: white;
            margin-bottom: 30px;
        }

        .container {
            max-width: 900px;
            margin: auto;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 20px;
        }

        .card {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, .2);
        }

        .teams {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            text-align: center;
        }

        .info {
            font-size: 14px;
            color: #555;
            margin-bottom: 6px;
        }

        .btn {
            display: block;
            margin-top: 15px;
            padding: 10px;
            text-align: center;
            background: #1e3c72;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }

        .btn:hover {
            background: #16315d;
        }

        .empty {
            color: white;
            text-align: center;
            margin-top: 50px;
        }
    </style>
</head>

<body>
    <?php include __DIR__ . '/partials/nav.php'; ?>

          <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">✅ Match créé avec succès !</div>
        <?php elseif (isset($_GET['error'])): ?>
            <div class="alert alert-error">❌ <?= htmlspecialchars($_GET['error']) ?></div>
        <?php endif; ?>

    <h1>Matches disponibles</h1>

    <?php if (empty($matches)): ?>
        <p class="empty">Aucun match disponible pour le moment.</p>
    <?php else: ?>
        <div class="container">
            <?php foreach ($matches as $match): ?>
                <div class="card">
                    <div class="teams">
                        <?= htmlspecialchars($match['team1_name']) ?>
                        vs
                        <?= htmlspecialchars($match['team2_name']) ?>
                    </div>

                    <div class="info">
                        📅 <?= date('d/m/Y H:i', strtotime($match['date_time'])) ?>
                    </div>

                    <div class="info">
                        📍 <?= htmlspecialchars($match['location']) ?>
                    </div>

                    <div class="info">
                        👤 Organisateur : <?= htmlspecialchars($match['organizer_name']) ?>
                    </div>

                    <a href="/buy-match/matches/<?= $match['id'] ?>" class="btn">
                        Voir détails
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</body>
</html>
