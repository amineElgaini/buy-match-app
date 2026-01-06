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
            // New user → hash password
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

        // Update fields
        $params = [$this->name, $this->email];
        $sql = "UPDATE users SET name=?, email=?";

        if (!empty($this->password)) {
            $sql .= ", password=?";
            $params[] = password_hash($this->password, PASSWORD_DEFAULT);
        }

        $sql .= ", role=?, active=? WHERE id=?";
        $params[] = $this->role;
        $params[] = $this->active;
        $params[] = $this->id;

        $stmt = $pdo->prepare($sql);
        return $stmt->execute($params);
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

    public static function find(int $id): ?User
    {
        $stmt = Database::getConnection()
            ->prepare("SELECT * FROM users WHERE id=?");
        $stmt->execute([$id]);
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

        if (!$user || !$user->active) return null;

        if (!password_verify($password, $user->password)) return null;

        return $user;
    }

    public function countAll(): int
    {
        return (int) Database::getConnection()
            ->query("SELECT COUNT(*) FROM users")
            ->fetchColumn();
    }

    public function countByRole(string $role): int
    {
        $stmt = Database::getConnection()
            ->prepare("SELECT COUNT(*) FROM users WHERE role=?");
        $stmt->execute([$role]);
        return (int) $stmt->fetchColumn();
    }
}
