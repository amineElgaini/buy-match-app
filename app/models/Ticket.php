<?php

require_once __DIR__ . './../../config/database.php';

class Ticket
{
    public ?int $id = null;
    public int $user_id;
    public int $match_id;
    public int $category_id;
    public string $seat_number;
    public ?string $qr_code = null;
    public ?string $created_at = null;

    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function save(): bool
    {
        $sql = "INSERT INTO tickets
                (user_id, match_id, category_id, seat_number, qr_code)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute([
            $this->user_id,
            $this->match_id,
            $this->category_id,
            $this->seat_number,
            $this->qr_code
        ]);

        if ($result) {
            $this->id = (int) $this->pdo->lastInsertId();
        }

        return $result;
    }

    public function getByUser(int $userId): array
    {
        $sql = "SELECT t.*, m.team1_name, m.team2_name, m.date_time, m.location
                FROM tickets t
                JOIN matches m ON m.id = t.match_id
                WHERE t.user_id = ?
                ORDER BY m.date_time DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buyedTicketCount(int $id): int
    {
        $stmt = $this->pdo->prepare(
            "SELECT COUNT(*) AS total FROM tickets WHERE match_id = ?"
        );

        $stmt->execute([$id]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? (int)$result['total'] : 0;
    }

    public function isReachedMaxTickets(int $match_id, int $user_id): bool
    {
        $stmt = $this->pdo->prepare(
            "SELECT COUNT(*) AS total FROM tickets WHERE match_id = ? AND user_id = ?"
        );

        $stmt->execute([$match_id, $user_id]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $total = $result ? (int)$result['total'] : 0;

        return $total >= 3;
    }
    public function getMatchesByUser(int $userId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT m.*
            FROM matches m
            JOIN tickets t ON t.match_id = m.id
            WHERE t.user_id = ?
            GROUP BY t.match_id
            ORDER BY m.date_time DESC
        ");
        $stmt->execute([$userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function soldByOrganizer(int $organizerId): int
    {
        $stmt = $this->pdo->prepare("
        SELECT COUNT(t.id)
        FROM tickets t
        JOIN matches m ON m.id = t.match_id
        WHERE m.organizer_id=?
    ");
        $stmt->execute([$organizerId]);
        return (int) $stmt->fetchColumn();
    }

    public function revenueByOrganizer(int $organizerId): float
    {
        $stmt = $this->pdo->prepare("
        SELECT COALESCE(SUM(c.price), 0) AS revenue
        FROM tickets t
        JOIN categories c ON c.id = t.category_id
        JOIN matches m ON m.id = t.match_id
        WHERE m.organizer_id = ?
    ");

        $stmt->execute([$organizerId]);

        return (float) $stmt->fetchColumn();
    }


    public function soldGlobal(): int
    {
        return (int) $this->pdo
            ->query("SELECT COUNT(*) FROM tickets")
            ->fetchColumn();
    }

    public function revenueGlobal(): float
    {
        $stmt = $this->pdo->query("
        SELECT COALESCE(SUM(c.price), 0)
        FROM tickets t
        JOIN categories c ON c.id = t.category_id
    ");

        return (float) $stmt->fetchColumn();
    }
}
