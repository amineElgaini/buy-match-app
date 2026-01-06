<?php

require_once __DIR__ . '/../models/MatchGame.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Ticket.php';

class AdminController
{
    private MatchGame $match;
    private User $user;
    private Ticket $ticket;

    public function __construct()
    {
        $this->match  = new MatchGame();
        $this->user   = new User();
        $this->ticket = new Ticket();
    }
    public function dashboard()
    {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            http_response_code(403);
            exit('Accès interdit');
        }

        $stats = [
            'users'           => $this->user->countAll(),
            'organizers'      => $this->user->countByRole('organizer'),
            'matches'         => $this->match->countAll(),
            'approved'        => $this->match->countStatusGlobal('approved'),
            'pending'         => $this->match->countStatusGlobal('pending'),
            'tickets_sold'    => $this->ticket->soldGlobal(),
            'total_revenue'   => $this->ticket->revenueGlobal()
        ];

        require '../app/views/admin-dashboard.php';
    }

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
