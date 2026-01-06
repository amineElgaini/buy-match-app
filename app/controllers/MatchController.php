<?php

require_once __DIR__ . '/../models/MatchGame.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Ticket.php';


class MatchController
{
    private MatchGame $matchModel;
    private Category $categoryModel;
    private Ticket $ticket;

    public function __construct()
    {
        // Initialize models (they internally get PDO connection)
        $this->matchModel = new MatchGame();
        $this->categoryModel = new Category();
        $this->ticket = new Ticket();
    }

    /**
     * List all approved matches
     */
    public function index()
    {
        // Fetch all approved matches
        $matches = $this->matchModel->getApprovedMatches();
        require '../app/views/matches.php';
    }

    /**
     * Show details for a single match
     * @param int $id Match ID
     */
    public function show($id)
    {
        // Find match by ID
        $match = $this->matchModel->find((int)$id);
        $buyedTicketCount = $this->ticket->buyedTicketCount((int)$id);

        if (!$match) {
            http_response_code(404);
            echo "Match introuvable";
            return;
        }

        // Get categories for this match (used for ticket selection)
        $categories = $this->categoryModel->byMatch($match['id']);

        require '../app/views/match-detail.php';
    }

    public function createMatch()
    {
        // Only organizers can create matches
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'organizer') {
            http_response_code(403);
            echo "Accès interdit";
            exit;
        }

        require '../app/views/match-form.php';
    }

    public function storeMatch()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'organizer') {
            http_response_code(403);
            echo "Accès interdit";
            exit;
        }

        $this->matchModel->organizer_id = $_SESSION['user_id'];
        $this->matchModel->team1_name   = $_POST['team1_name'];
        $this->matchModel->team1_logo   = $_POST['team1_logo'] ?? null;
        $this->matchModel->team2_name   = $_POST['team2_name'];
        $this->matchModel->team2_logo   = $_POST['team2_logo'] ?? null;
        $this->matchModel->date_time    = $_POST['date_time'];
        $this->matchModel->duration     = (int) ($_POST['duration'] ?? 90);
        $this->matchModel->location     = $_POST['location'];
        $this->matchModel->max_seats    = (int) ($_POST['max_seats'] ?? 2000);
        $this->matchModel->status       = 'pending'; // new matches pending approval

        $success = $this->matchModel->create();

        if ($success) {
            header('Location: /buy-match/matches?success=1');
            exit;
        } else {
            header('Location: /buy-match/matches/create?error=' . urlencode("Impossible de créer le match."));
            exit;
        }
    }
}
