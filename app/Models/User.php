<?php

require_once __DIR__ . './../../config/database.php';

class User
{
    public ?int $id = null;
    public string $name;
    public string $email;
    public string $password;
    public string $role = 'user';
    public bool $active = true;
    public ?string $created_at = null;

    public function save(): bool
    {
        $pdo = Database::getConnection();

        if ($this->id === null) {
            $stmt = $pdo->prepare("
                INSERT INTO users (name, email, password, role, active)
                VALUES (?, ?, ?, ?, ?)
            ");
            $result = $stmt->execute([
                $this->name,
                $this->email,
                password_hash($this->password, PASSWORD_DEFAULT),
                $this->role,
                $this->active
            ]);
            $this->id = (int)$pdo->lastInsertId();
            return $result;
        }

        $stmt = $pdo->prepare("
            UPDATE users SET name=?, email=?, role=?, active=? WHERE id=?
        ");
        return $stmt->execute([
            $this->name,
            $this->email,
            $this->role,
            $this->active,
            $this->id
        ]);
    }

    public function disable(): bool
    {
        $this->active = false;
        return $this->save();
    }

    public static function findByEmail(string $email): ?User
    {
        $stmt = Database::getConnection()
            ->prepare("SELECT * FROM users WHERE email=?");
        $stmt->execute([$email]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) return null;

        $user = new User();
        foreach ($data as $key => $value) {
            $user->$key = $value;
        }
        return $user;
    }

    public static function login(string $email, string $password): ?User
    {
        $user = self::findByEmail($email);

        if (!$user) {
            return null;
        }

        if (!$user->active) {
            return null;
        }

        if (!password_verify($password, $user->password)) {
            return null;
        }

        return $user;
    }

}
