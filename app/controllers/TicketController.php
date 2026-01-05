<?php

require_once __DIR__ . '/../models/Ticket.php';

class TicketController
{
    public function buy()
    {
        $ticket = new Ticket();
        $ticket->user_id = $_SESSION['user']['id'];
        $ticket->match_id = $_POST['match_id'];
        $ticket->category_id = $_POST['category_id'];
        $ticket->seat_number = $_POST['seat'];
        $ticket->qr_code = uniqid('QR-');

        if (!$ticket->save()) {
            die('Ticket limit reached or match not available');
        }

        header('Location: /tickets/my');
    }

    public function myTickets()
    {
        require '../app/views/tickets/my.php';
    }
}
