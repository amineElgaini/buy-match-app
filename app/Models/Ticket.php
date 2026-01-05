<?php

require_once __DIR__ . '/../core/Database.php';

class Ticket
{
    public ?int $id = null;
    public int $user_id;
    public int $match_id;
    public int $category_id;
    public string $seat_number;
    public ?string $qr_code = null;
    public ?string $created_at = null;

    public function canBuy(): bool
    {
        $stmt = Database::getConnection()->prepare("
            SELECT COUNT(*) FROM tickets
            WHERE user_id=? AND match_id=?
        ");
        $stmt->execute([$this->user_id, $this->match_id]);
        return $stmt->fetchColumn() < 4;
    }

    public function save(): bool
    {
        if (!$this->canBuy()) {
            return false;
        }

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            INSERT INTO tickets
            (user_id, match_id, category_id, seat_number, qr_code)
            VALUES (?, ?, ?, ?, ?)
        ");

        $result = $stmt->execute([
            $this->user_id,
            $this->match_id,
            $this->category_id,
            $this->seat_number,
            $this->qr_code
        ]);
        $this->id = (int)$pdo->lastInsertId();
        return $result;
    }
}
