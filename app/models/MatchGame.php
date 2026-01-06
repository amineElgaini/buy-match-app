<?php

require_once __DIR__ . './../../config/database.php';

class MatchGame
{
    // ✅ Attributes (table columns)
    public ?int $id = null;
    public int $organizer_id;
    public string $team1_name;
    public ?string $team1_logo = null;
    public string $team2_name;
    public ?string $team2_logo = null;
    public string $date_time;
    public int $duration = 90;
    public string $location;
    public int $max_seats = 2000;
    public string $status = 'pending';
    public ?string $created_at = null;

    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    // Inside MatchGame class
    public function create(): bool
    {
        $stmt = $this->pdo->prepare("
        INSERT INTO matches 
        (organizer_id, team1_name, team1_logo, team2_name, team2_logo, date_time, duration, location, max_seats, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

        return $stmt->execute([
            $this->organizer_id,
            $this->team1_name,
            $this->team1_logo,
            $this->team2_name,
            $this->team2_logo,
            $this->date_time,
            $this->duration,
            $this->location,
            $this->max_seats,
            $this->status
        ]);
    }


    // Fetch all approved matches
    public function getApprovedMatches(): array
    {
        $stmt = $this->pdo->query(
            "SELECT m.*, u.name AS organizer_name
             FROM matches m
             JOIN users u ON u.id = m.organizer_id
             WHERE m.status = 'approved'
             ORDER BY m.date_time ASC"
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Find a match by ID
    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            "SELECT m.*, u.name AS organizer_name
             FROM matches m
             JOIN users u ON u.id = m.organizer_id
             WHERE m.id = ? AND m.status = 'approved'"
        );
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function countByOrganizer(int $organizerId): int
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM matches WHERE organizer_id=?");
        $stmt->execute([$organizerId]);
        return (int) $stmt->fetchColumn();
    }

    public function countByStatus(int $organizerId, string $status): int
    {
        $stmt = $this->pdo->prepare("
        SELECT COUNT(*) FROM matches WHERE organizer_id=? AND status=?
    ");
        $stmt->execute([$organizerId, $status]);
        return (int) $stmt->fetchColumn();
    }

    public function countAll(): int
    {
        return (int) $this->pdo->query("SELECT COUNT(*) FROM matches")->fetchColumn();
    }

    public function countStatusGlobal(string $status): int
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM matches WHERE status=?");
        $stmt->execute([$status]);
        return (int) $stmt->fetchColumn();
    }
}
