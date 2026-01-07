<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Mon Profil</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f8; padding: 40px 20px; }
        .container { max-width: 700px; margin: auto; }
        .card { background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 8px 25px rgba(0,0,0,.1); margin-bottom: 25px; }
        h2 { text-align: center; margin-bottom: 25px; color: #1e3c72; }
        .info { text-align: center; margin-bottom: 20px; color: #666; }
        input, textarea { width: 100%; padding: 14px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 8px; font-size: 14px; transition: 0.3s; }
        input:focus, textarea:focus { border-color: #1e3c72; outline: none; box-shadow: 0 0 5px rgba(30,60,114,0.3); }
        button { width: 100%; padding: 14px; border: none; background: #2a5298; color: white; font-size: 16px; border-radius: 8px; cursor: pointer; transition: 0.3s; }
        button:hover { background: #1e3c72; }
        .alert { padding: 12px; border-radius: 6px; margin-bottom: 20px; text-align: center; font-weight: bold; }
        .alert-success { background-color: #4CAF50; color: white; }
        .alert-error { background-color: #f44336; color: white; }
        .match { border-top: 1px solid #ddd; padding-top: 15px; margin-top: 15px; }
        .match h3 { margin: 0 0 10px 0; color: #333; }
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

    <!-- Purchased Matches & Comment Form -->
    <div class="card">
        <h2>Mes Matches</h2>

        <?php if (!empty($purchasedMatches)): ?>
            <?php foreach ($purchasedMatches as $match): ?>
                <div class="match">
                    <h3><?= $match['team1_name'] ?> vs <?= $match['team2_name'] ?></h3>
                    <p><strong>Date :</strong> <?= $match['date_time'] ?> | <strong>Lieu :</strong> <?= $match['location'] ?></p>

                    <!-- Show existing comment if available -->
                    <?php if (!empty($match['comment'])): ?>
                        <p><strong>Votre commentaire :</strong> <?= htmlspecialchars($match['comment']['comment']) ?> (<?= $match['comment']['rating'] ?>/5)</p>
                    <?php endif; ?>

                    <!-- Comment form -->
                    <form method="POST" action="/buy-match/<?= $match['id'] ?>/comment">
                        <input type="number" name="rating" min="1" max="5" placeholder="Note (1-5)" required>
                        <textarea name="comment" rows="2" placeholder="Votre commentaire" required></textarea>
                        <button type="submit">Envoyer le commentaire</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Vous n'avez acheté aucun ticket pour le moment.</p>
        <?php endif; ?>
    </div>

</div>

</body>
</html>
