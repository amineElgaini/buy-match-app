<?php

require_once __DIR__ . '/../Models/Ticket.php';
require_once __DIR__ . '/../Models/MatchGame.php';
require_once __DIR__ . '/../services/MailService.php';
require_once __DIR__ . '/../services/PdfService.php';

class TicketController
{
    private Ticket $ticket;
    private MatchGame $matchGame;

    public function __construct()
    {
        $this->ticket = new Ticket();
        $this->matchGame = new MatchGame();
    }

    public function store()
    {
        $match_id = (int) $_POST['match_id'];
        $user_id  = $_SESSION['user_id'];
        $category_id = (int) $_POST['category_id'];

        if ($this->ticket->isReachedMaxTickets($match_id, $user_id)) {
            $errorMsg = urlencode("Vous avez déjà atteint le maximum de 3 tickets pour ce match.");
            header('Location: /buy-match/matches/' . $match_id . '?error=' . $errorMsg);
            exit;
        }

        $this->ticket->user_id     = $user_id;
        $this->ticket->match_id    = $match_id;
        $this->ticket->category_id = $category_id;
        $this->ticket->seat_number = strtoupper(substr(uniqid(), -4));
        $this->ticket->qr_code     = 'QR-' . uniqid();

        $this->ticket->save();
        // MailService::send($_SESSION['email'], 'Confirmation de votre ticket', 'Merci pour votre achat de ticket, votre code QR est le suivant : ' . $this->ticket->qr_code);
        // MailService::send('amineelgaini1444@gmail.com', 'Confirmation de votre ticket', 'Merci pour votre achat de ticket, votre code QR est le suivant : ' . $this->ticket->qr_code);
        $this->sendTicket();

        header('Location: /buy-match/matches/' . $match_id . '?success=1');
        exit;
    }

    public function sendTicket()
    {
        $html = "
            <h1>Ticket Confirmation</h1>
            <p>Name: Amine</p>
            <p>Order ID: #12345</p>
        ";

        $pdfPath = PdfService::generate($html, 'ticket_12345.pdf');

        $sent = MailService::send(
            'amineelgaini1444@gmail.com',
            'Your Ticket',
            "<p>Please find your ticket attached.</p>",
            $pdfPath
        );

        if ($sent) {
            echo "Email sent successfully!";
        } else {
            echo "Failed to send email.";
        }
    }


    public function myTickets()
    {
        $tickets = $this->ticket->getByUser($_SESSION['user_id']);
        require '../app/views/tickets/my.php';
    }
}
