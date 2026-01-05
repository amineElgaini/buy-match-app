<?php

require_once __DIR__ . './../../config/database.php';

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

    public function save(): bool
    {
        $pdo = Database::getConnection();

        if ($this->id === null) {
            $stmt = $pdo->prepare("
                INSERT INTO matches
                (organizer_id, team1_name, team1_logo, team2_name, team2_logo,
                 date_time, duration, location, max_seats, status)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $result = $stmt->execute([
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
            $this->id = (int)$pdo->lastInsertId();
            return $result;
        }

        return false;
    }

    public function approve(): bool
    {
        $this->status = 'approved';
        return Database::getConnection()
            ->prepare("UPDATE matches SET status='approved' WHERE id=?")
            ->execute([$this->id]);
    }
}
