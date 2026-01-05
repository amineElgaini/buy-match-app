<?php

require_once __DIR__ . './../../config/database.php';


class Category
{
    public ?int $id = null;
    public int $match_id;
    public string $name;
    public float $price;

    public function save(): bool
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            INSERT INTO categories (match_id, name, price)
            VALUES (?, ?, ?)
        ");
        $result = $stmt->execute([
            $this->match_id,
            $this->name,
            $this->price
        ]);
        $this->id = (int)$pdo->lastInsertId();
        return $result;
    }
}
