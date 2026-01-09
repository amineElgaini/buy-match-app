<?php
namespace App\Models;
use App\Config\Database;
use PDO;
class MatchGame
{
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

    public function getMatchesWithCommentsByOrganizer(int $organizerId): array
{
    $stmt = $this->pdo->prepare("
        SELECT 
            m.*,
            c.id AS comment_id,
            c.user_id AS commenter_id,
            u.name AS commenter_name,
            c.rating,
            c.comment,
            c.created_at AS comment_created_at
        FROM matches m
        LEFT JOIN comments c ON c.match_id = m.id
        LEFT JOIN users u ON u.id = c.user_id
        WHERE m.organizer_id = ?
        ORDER BY m.date_time ASC, c.created_at ASC
    ");
    $stmt->execute([$organizerId]);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $matches = [];
    foreach ($rows as $row) {
        $matchId = $row['id'];
        if (!isset($matches[$matchId])) {
            $matches[$matchId] = [
                'id'             => $row['id'],
                'organizer_id'   => $row['organizer_id'],
                'team1_name'     => $row['team1_name'],
                'team1_logo'     => $row['team1_logo'],
                'team2_name'     => $row['team2_name'],
                'team2_logo'     => $row['team2_logo'],
                'date_time'      => $row['date_time'],
                'duration'       => $row['duration'],
                'location'       => $row['location'],
                'max_seats'      => $row['max_seats'],
                'status'         => $row['status'],
                'created_at'     => $row['created_at'],
                'comments'       => []
            ];
        }

        if ($row['comment_id']) {
            $matches[$matchId]['comments'][] = [
                'id'            => $row['comment_id'],
                'user_id'       => $row['commenter_id'],
                'user_name'     => $row['commenter_name'],
                'rating'        => $row['rating'],
                'comment'       => $row['comment'],
                'created_at'    => $row['comment_created_at'],
            ];
        }
    }

    return array_values($matches);
}

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

    public function updateStatus(int $matchId, string $status): bool
    {
        $stmt = $this->pdo->prepare("
        UPDATE matches SET status = ? WHERE id = ?
    ");
        return $stmt->execute([$status, $matchId]);
    }


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

    public function getPendingMatches(): array
    {
        $stmt = $this->pdo->query(
            "SELECT m.*, u.name AS organizer_name
             FROM matches m
             JOIN users u ON u.id = m.organizer_id
             WHERE m.status = 'pending'
             ORDER BY m.date_time ASC"
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

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
