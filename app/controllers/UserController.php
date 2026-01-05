<?php

require_once __DIR__ . '/../models/User.php';

class UserController
{
    public function showLogin()
    {
        require '../app/views/auth/login.php';
    }

    public function login()
    {
        $user = User::login($_POST['email'], $_POST['password']);

        if (!$user) {
            die('Invalid credentials or account disabled');
        }

        $_SESSION['user'] = [
            'id'   => $user->id,
            'role' => $user->role,
            'name' => $user->name
        ];

        header('Location: /');
    }

    public function showRegister()
    {
        require '../app/views/auth/register.php';
    }

    public function register()
    {
        $user = new User();
        $user->name = $_POST['name'];
        $user->email = $_POST['email'];
        $user->password = $_POST['password'];
        $user->role = $_POST['role'] ?? 'user';

        $user->save();

        header('Location: /login');
    }

    public function logout()
    {
        session_destroy();
        header('Location: /login');
    }

    public function profile()
    {
        require '../app/views/user/profile.php';
    }

    public function updateProfile()
    {
        // update profile logic
        header('Location: /profile');
    }
}
