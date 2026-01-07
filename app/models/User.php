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

    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    /**
     * Save user (create or update)
     */
    public function save(): bool
    {
        if ($this->id === null) {
            // New user → hash password
            $stmt = $this->pdo->prepare("
            INSERT INTO users (name,email,password,role,active)
            VALUES (?,?,?,?,?)
        ");
            $result = $stmt->execute([
                $this->name,
                $this->email,
                password_hash($this->password, PASSWORD_DEFAULT),
                $this->role,
                $this->active ? 1 : 0, // ensure integer
            ]);
            $this->id = (int)$this->pdo->lastInsertId();
            return $result;
        }

        // Update existing user
        $params = [$this->name, $this->email];
        $sql = "UPDATE users SET name=?, email=?";

        if (!empty($this->password)) {
            $sql .= ", password=?";
            $params[] = password_hash($this->password, PASSWORD_DEFAULT);
        }

        $sql .= ", role=?, active=? WHERE id=?";
        $params[] = $this->role;
        $params[] = $this->active ? 1 : 0; // <-- FIX HERE
        $params[] = $this->id;

        return $this->pdo->prepare($sql)->execute($params);
    }


    /**
     * Disable user (active = false)
     */
    public function disable(): bool
    {
        $this->active = false;
        return $this->save();
    }

    /**
     * Enable user (active = true)
     */
    public function enable(): bool
    {
        $this->active = true;
        return $this->save();
    }

    /**
     * Find a user by ID
     */
    public static function find(int $id): ?User
    {
        $stmt = Database::getConnection()->prepare("SELECT * FROM users WHERE id=?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) return null;

        $user = new User();
        foreach ($data as $key => $value) {
            $user->$key = $value;
        }
        return $user;
    }

    /**
     * Find a user by email
     */
    public static function findByEmail(string $email): ?User
    {
        $stmt = Database::getConnection()->prepare("SELECT * FROM users WHERE email=?");
        $stmt->execute([$email]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) return null;

        $user = new User();
        foreach ($data as $key => $value) {
            $user->$key = $value;
        }
        return $user;
    }

    /**
     * Return all users
     */
    public static function all(): array
    {
        $stmt = Database::getConnection()->query("SELECT * FROM users ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Authenticate user
     */
    public static function login(string $email, string $password): ?User
    {
        $user = self::findByEmail($email);
        if (!$user || !$user->active) return null;
        if (!password_verify($password, $user->password)) return null;
        return $user;
    }

    /**
     * Count all users
     */
    public static function countAll(): int
    {
        $stmt = Database::getConnection()->query("SELECT COUNT(*) FROM users");
        return (int) $stmt->fetchColumn();
    }

    /**
     * Count users by role
     */
    public static function countByRole(string $role): int
    {
        $stmt = Database::getConnection()->prepare("SELECT COUNT(*) FROM users WHERE role=?");
        $stmt->execute([$role]);
        return (int) $stmt->fetchColumn();
    }
}
