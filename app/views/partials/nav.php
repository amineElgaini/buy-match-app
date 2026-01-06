<style>
    /* Navbar Container */
    header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 30px;
        background: linear-gradient(135deg, #1e3c72, #2a5298);
        color: white;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        position: sticky;
        top: 0;
        z-index: 1000;
    }

    /* Logo / Brand */
    header h1 {
        font-size: 24px;
        margin: 0;
        font-weight: 700;
    }

    /* Navigation Links */
    nav a {
        color: white;
        text-decoration: none;
        margin-left: 25px;
        font-weight: 500;
        transition: all 0.3s ease;
        position: relative;
    }

    /* Hover effect */
    nav a::after {
        content: "";
        position: absolute;
        width: 0%;
        height: 2px;
        bottom: -4px;
        left: 0;
        background-color: #ffdd57;
        transition: width 0.3s;
    }

    nav a:hover::after {
        width: 100%;
    }

    nav a:hover {
        color: #ffdd57;
    }

    /* Responsive */
    @media (max-width: 600px) {
        header {
            flex-direction: column;
            align-items: flex-start;
        }

        nav {
            margin-top: 10px;
        }

        nav a {
            display: block;
            margin: 8px 0 0 0;
        }
    }
</style>

<header>
    <h1>BuyMatch</h1>
    <nav>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="/buy-match/matches">Matches</a>
            <a href="/buy-match/profile">Profil</a>
            <?= $_SESSION['user_role'] === 'organizer' ? '<a href="/buy-match/match-form">Créer un match</a>' : '' ?>

            <?= $_SESSION['user_role'] === 'admin' ? '<a href="/buy-match/admin-dashboard">Dashbaord</a>' : '' ?>
            <?= $_SESSION['user_role'] === 'organizer' ? '<a href="/buy-match/organizer-dashboard">Dashbaord</a>' : '' ?>
            <a href="/buy-match/logout">Déconnexion</a>
        <?php else: ?>
            <a href="/buy-match/login">Connexion</a>
            <a href="/buy-match/register">Créer un compte</a>
        <?php endif; ?>
    </nav>
</header>
