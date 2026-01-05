<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/MatchGame.php';

class AdminController
{
    public function users()
    {
        require '../app/views/admin/users.php';
    }

    public function disableUser()
    {
        $user = new User();
        $user->id = $_POST['user_id'];
        $user->disable();

        header('Location: /admin/users');
    }

    public function matches()
    {
        require '../app/views/admin/matches.php';
    }

    public function approveMatch()
    {
        $match = new MatchGame();
        $match->id = $_POST['match_id'];
        $match->approve();

        header('Location: /admin/matches');
    }

    public function refuseMatch()
    {
        // similar logic
        header('Location: /admin/matches');
    }
}
