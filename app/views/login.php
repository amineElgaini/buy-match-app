<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card {
            background: #fff;
            padding: 30px;
            width: 360px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, .2);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        input {
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
            background: #1e3c72;
            color: white;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background: #16315d;
        }

        .link {
            text-align: center;
            margin-top: 15px;
        }

        .link a {
            color: #1e3c72;
            text-decoration: none;
        }
    </style>
</head>

<body>

    <div class="card">
        <h2>Connexion</h2>

        <form method="POST" action="/buy-match/login">
            <input type="email" name="email" placeholder="Adresse email" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <button type="submit">Se connecter</button>
        </form>

        <div class="link">
            <a href="/buy-match/register">Créer un compte</a>
        </div>
    </div>

</body>

</html>