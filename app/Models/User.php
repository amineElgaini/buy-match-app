<?php
namespace App\Models;
use App\Config\Database;
use PDO;
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

    public function save(): bool
    {
        if ($this->id === null) {
            $stmt = $this->pdo->prepare("
            INSERT INTO users (name,email,password,role,active)
            VALUES (?,?,?,?,?)
        ");
            $result = $stmt->execute([
                $this->name,
                $this->email,
                password_hash($this->password, PASSWORD_DEFAULT),
                $this->role,
                $this->active ? 1 : 0,
            ]);
            $this->id = (int)$this->pdo->lastInsertId();
            return $result;
        }

        $params = [$this->name, $this->email];
        $sql = "UPDATE users SET name=?, email=?";

        if (!empty($this->password)) {
            $sql .= ", password=?";
            $params[] = password_hash($this->password, PASSWORD_DEFAULT);
        }

        $sql .= ", role=?, active=? WHERE id=?";
        $params[] = $this->role;
        $params[] = $this->active ? 1 : 0;
        $params[] = $this->id;

        return $this->pdo->prepare($sql)->execute($params);
    }

    public function disable(): bool
    {
        $this->active = false;
        return $this->save();
    }

    public function enable(): bool
    {
        $this->active = true;
        return $this->save();
    }

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

    public static function all(): array
    {
        $stmt = Database::getConnection()->query("SELECT * FROM users ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function login(string $email, string $password): ?User
    {
        $user = self::findByEmail($email);
        if (!$user || !$user->active) return null;
        if (!password_verify($password, $user->password)) return null;
        return $user;
    }

    public static function countAll(): int
    {
        $stmt = Database::getConnection()->query("SELECT COUNT(*) FROM users");
        return (int) $stmt->fetchColumn();
    }

    public static function countByRole(string $role): int
    {
        $stmt = Database::getConnection()->prepare("SELECT COUNT(*) FROM users WHERE role=?");
        $stmt->execute([$role]);
        return (int) $stmt->fetchColumn();
    }
}
