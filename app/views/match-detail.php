<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Détails du match</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            min-height: 100vh;
            padding: 40px;
        }

        .card {
            background: #fff;
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, .2);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .info {
            margin-bottom: 10px;
            font-size: 15px;
        }

        .categories {
            margin-top: 25px;
        }

        .category {
            display: flex;
            justify-content: space-between;
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        .category input {
            margin-right: 10px;
        }

        .btn {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 20px;
            background: #1e3c72;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            border: none;
            cursor: pointer;
        }

        .btn:hover {
            background: #16315d;
        }

        .empty {
            text-align: center;
            margin-top: 20px;
            color: #555;
        }
    </style>
</head>

<body>
    <?php include __DIR__ . '/partials/nav.php'; ?>

    <div class="card">
        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <div style="padding: 12px; background: #4CAF50; color: white; border-radius: 6px; margin-bottom: 15px; text-align: center;">
                ✅ Ticket acheté avec succès !
            </div>
        <?php elseif (isset($_GET['error']) && !empty($_GET['error'])): ?>
            <div style="padding: 12px; background: #f44336; color: white; border-radius: 6px; margin-bottom: 15px; text-align: center;">
                ❌ <?= htmlspecialchars($_GET['error']) ?>
            </div>
        <?php endif; ?>

        <h1>
            <?= htmlspecialchars($match['team1_name']) ?>
            vs
            <?= htmlspecialchars($match['team2_name']) ?>
        </h1>

        <div class="info">📅 Date et heure : <?= date('d/m/Y H:i', strtotime($match['date_time'])) ?></div>
        <div class="info">📍 Lieu : <?= htmlspecialchars($match['location']) ?></div>
        <div class="info">👤 Organisateur : <?= htmlspecialchars($match['organizer_name']) ?></div>
        <div class="info">⏱ Durée : <?= $match['duration'] ?> minutes</div>
        <div class="info">🎟 Places max : <?= $buyedTicketCount ?> / <?= $match['max_seats'] ?></div>

        <div class="categories">
            <h3>Choisir un ticket</h3>

            <?php if (empty($categories)): ?>
                <p class="empty">Aucune catégorie disponible pour ce match.</p>
            <?php else: ?>
                <form method="POST" action="/buy-match/tickets">
                    <input type="hidden" name="match_id" value="<?= $match['id'] ?>">

                    <?php foreach ($categories as $cat): ?>
                        <label class="category">
                            <span>
                                <input type="radio" name="category_id" value="<?= $cat['id'] ?>" required>
                                <?= htmlspecialchars($cat['name']) ?>
                            </span>
                            <strong><?= number_format($cat['price'], 2) ?> €</strong>
                        </label>
                    <?php endforeach; ?>

                    <button type="submit" class="btn">🎟 Acheter le ticket</button>
                </form>
            <?php endif; ?>
        </div>

        <a href="/buy-match/matches" class="btn">← Retour aux matchs</a>
    </div>

</body>

</html>