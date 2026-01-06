<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #11998e, #38ef7d);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card {
            background: #fff;
            padding: 30px;
            width: 400px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, .2);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        input,
        select {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
        }

        button {
            width: 100%;
            padding: 12px;
            border: none;
            background: #11998e;
            color: white;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background: #0d7f75;
        }

        .link {
            text-align: center;
            margin-top: 15px;
        }

        .link a {
            color: #11998e;
            text-decoration: none;
        }
    </style>
</head>

<body>

    <div class="card">
        <h2>Créer un compte</h2>

        <form method="POST" action="/buy-match/register">
            <input type="text" name="name" placeholder="Nom complet" required>
            <input type="email" name="email" placeholder="Adresse email" required>
            <input type="password" name="password" placeholder="Mot de passe" required>

            <select name="role">
                <option value="user">Utilisateur (Acheteur)</option>
                <option value="organizer">Organisateur</option>
            </select>

            <button type="submit">S'inscrire</button>
        </form>

        <div class="link">
            <a href="/buy-match/login">Déjà un compte ? Se connecter</a>
        </div>
    </div>

</body>

</html>