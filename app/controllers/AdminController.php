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
        $stats = [
            'users'           => User::countAll(),
            'organizers'      => User::countByRole('organizer'),
            'matches'         => $this->match->countAll(),
            'approved'        => $this->match->countStatusGlobal('approved'),
            'pending'         => $this->match->countStatusGlobal('pending'),
            'refused'         => $this->match->countStatusGlobal('refused'),
            'tickets_sold'    => $this->ticket->soldGlobal(),
            'total_revenue'   => $this->ticket->revenueGlobal(),
            'pending_matches' => $this->match->getPendingMatches(),
        ];

        require '../app/views/admin-dashboard.php';
    }

    public function approveMatch($id)
    {
        $this->match->updateStatus((int)$id, 'approved');
        header('Location: /buy-match/admin-dashboard');
        exit;
    }

    public function refuseMatch($id)
    {
        $this->match->updateStatus((int)$id, 'refused');
        header('Location: /buy-match/admin-dashboard');
        exit;
    }

    public function users()
    {
        $users = User::all();
        require '../app/views/manage-users.php';
    }

    public function disableUser()
    {
        if (!empty($_POST['user_id'])) {
            $user = User::find((int)$_POST['user_id']);
            if ($user) {
                $user->disable();
            }
        }

        header('Location: /buy-match/admin/users');
        exit;
    }

    public function enableUser()
    {
        if (!empty($_POST['user_id'])) {
            $user = User::find((int)$_POST['user_id']);
            if ($user) {
                $user->enable();
            }
        }

        header('Location: /buy-match/admin/users');
        exit;
    }

    public function matches()
    {
        $allMatches = $this->match->getApprovedMatches();
        require '../app/views/matches.php';
    }
}
