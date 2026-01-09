<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Ticket.php';
require_once __DIR__ . '/../models/Comment.php';

class UserController
{
    private Ticket $ticketModel;
    private Comment $commentModel;

    public function __construct()
    {
        $this->ticketModel  = new Ticket();
        $this->commentModel = new Comment();
    }

    public function showLogin()
    {
        View::render('login', [
            'title' => 'Login',
        ]);
        // require '../app/views/login.php';
    }

    public function login()
    {
        $user = User::login($_POST['email'], $_POST['password']);

        if (!$user) {
            throw new AppException("User not found", 404);
        }

        $_SESSION['user_id']   = $user->id;
        $_SESSION['user_role'] = $user->role;
        $_SESSION['user_name'] = $user->name;
        $_SESSION['user_email'] = $user->email;

        header('Location: /buy-match/matches');
        exit;
    }

    public function showRegister()
    {
        require '../app/views/register.php';
    }

    public function register()
    {
        $user = new User();
        $user->name = $_POST['name'];
        $user->email = $_POST['email'];
        $user->password = $_POST['password'];
        $user->role = $_POST['role'] ?? 'user';

        if (empty($user->name)) {
            throw new AppException("Name is required", 422);
        }

        $user->save();

        header('Location: /buy-match/login');
        exit;
    }

    public function logout()
    {
        session_destroy();
        header('Location: /buy-match/login');
        exit;
    }

    public function profile()
    {
        $userId = $_SESSION['user_id'];
        $matches = $this->ticketModel->getMatchesByUser($userId);

        foreach ($matches as &$match) {
            $match['comment'] = $this->commentModel->getUserCommentForMatch($userId, $match['id']);
        }
        unset($match);



        $purchasedMatches = $matches;

        require '../app/views/update-profile.php';
    }

    public function updateProfile()
    {
        $user = User::find($_SESSION['user_id']);
        if (!$user) {
            header('Location: /buy-match/profile?error=' . urlencode("Utilisateur introuvable."));
            exit;
        }

        $user->name  = $_POST['name'] ?? $user->name;
        $user->email = $_POST['email'] ?? $user->email;

        if (!empty($_POST['password'])) {
            $user->password = $_POST['password'];
        }

        $success = $user->save();

        if ($success) {
            $_SESSION['user_name']  = $user->name;
            $_SESSION['user_email'] = $user->email;

            header('Location: /buy-match/profile?success=1');
            exit;
        } else {
            header('Location: /buy-match/profile?error=' . urlencode("Impossible de mettre à jour le profil."));
            exit;
        }
    }
}
