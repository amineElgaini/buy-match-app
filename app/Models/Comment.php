<?php

require_once __DIR__ . '/../core/Database.php';

class Comment
{
    public ?int $id = null;
    public int $user_id;
    public int $match_id;
    public int $rating;
    public ?string $comment = null;
    public ?string $created_at = null;

    public function save(): bool
    {
        $stmt = Database::getConnection()->prepare("
            INSERT INTO comments (user_id, match_id, rating, comment)
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([
            $this->user_id,
            $this->match_id,
            $this->rating,
            $this->comment
        ]);
    }
}
