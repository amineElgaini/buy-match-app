<?php

require_once __DIR__ . './../../config/database.php';

class Comment
{
    public $user_id;
    public $match_id;
    public $rating;
    public $comment;

    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function getUserCommentForMatch(int $userId, int $matchId): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT comment, rating 
            FROM comments 
            WHERE user_id = ? AND match_id = ?
        ");
        $stmt->execute([$userId, $matchId]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ?? null;
    }

    public function save(): bool
    {
        $stmt = $this->pdo->prepare("
            SELECT id FROM comments WHERE user_id = ? AND match_id = ?
        ");
        $stmt->execute([$this->user_id, $this->match_id]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            $stmt = $this->pdo->prepare("
                UPDATE comments SET comment = ?, rating = ? WHERE id = ?
            ");
            return $stmt->execute([$this->comment, $this->rating, $existing['id']]);
        } else {
            $stmt = $this->pdo->prepare("
                INSERT INTO comments (user_id, match_id, comment, rating, created_at)
                VALUES (?, ?, ?, ?, NOW())
            ");
            return $stmt->execute([$this->user_id, $this->match_id, $this->comment, $this->rating]);
        }
    }
}
