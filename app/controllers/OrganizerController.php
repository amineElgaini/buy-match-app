<?php

require_once __DIR__ . '/../models/MatchGame.php';
require_once __DIR__ . '/../models/Ticket.php';

class OrganizerController
{
    private MatchGame $match;
    private Ticket $ticket;

    public function __construct()
    {
        $this->match  = new MatchGame();
        $this->ticket = new Ticket();
    }

    public function dashboard()
    {
        $organizerId = $_SESSION['user_id'];

        $stats = [
            'total_matches'   => $this->match->countByOrganizer($organizerId),
            'approved'        => $this->match->countByStatus($organizerId, 'approved'),
            'pending'         => $this->match->countByStatus($organizerId, 'pending'),
            'refused'        => $this->match->countByStatus($organizerId, 'refused'),
            'tickets_sold'    => $this->ticket->soldByOrganizer($organizerId),
            'total_revenue'   => $this->ticket->revenueByOrganizer($organizerId)
        ];

        require '../app/views/organizer-dashboard.php';
    }
}
