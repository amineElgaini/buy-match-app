<?php

require_once __DIR__ . '/../models/User.php';

class UserController
{
    public function showLogin()
    {
        require '../app/views/login.php';
    }

    public function login()
    {
        $user = User::login($_POST['email'], $_POST['password']);

        if (!$user) {
            die('Invalid credentials or account disabled');
        }

        $_SESSION['user_id']   = $user->id;
        $_SESSION['user_role'] = $user->role;
        $_SESSION['user_name'] = $user->name;
        $_SESSION['user_email'] = $user->email;

        header('Location: /buy-match/matches');
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

        $user->save();

        header('Location: /buy-match/login');
    }

    public function logout()
    {
        session_destroy();
        header('Location: /buy-match/login');
    }

    public function profile()
    {
        require '../app/views/update-profile.php';
    }

    public function updateProfile()
    {
        if (!isset($_SESSION['user_id'])) {
            // Not logged in
            header('Location: /buy-match/login');
            exit;
        }

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
            // Update session data
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
