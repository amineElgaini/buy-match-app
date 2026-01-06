<?php

require_once __DIR__ . './../../config/database.php';

class Category
{
    // ✅ Attributes (table columns)
    public ?int $id = null;
    public int $match_id;
    public string $name;
    public float $price;

    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    /**
     * Get all categories for a given match
     * @param int $matchId
     * @return array
     */
    public function byMatch(int $matchId): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM categories WHERE match_id = ? ORDER BY price ASC"
        );
        $stmt->execute([$matchId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Save a new category
     */
    public function save(): bool
    {
        $sql = "INSERT INTO categories (match_id, name, price) VALUES (?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);

        $result = $stmt->execute([
            $this->match_id,
            $this->name,
            $this->price
        ]);

        if ($result) {
            $this->id = (int) $this->pdo->lastInsertId();
        }

        return $result;
    }

    /**
     * Update category
     */
    public function update(): bool
    {
        $sql = "UPDATE categories SET name = ?, price = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            $this->name,
            $this->price,
            $this->id
        ]);
    }

    /**
     * Delete category
     */
    public function delete(): bool
    {
        $sql = "DELETE FROM categories WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([$this->id]);
    }
}
