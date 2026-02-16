<?php

namespace app\repositories;

use app\models\UserModel;
use PDO;

class UserRepository{
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function usernameExists(string $username): bool
    {
        $query = "SELECT 1 FROM admin WHERE username = ? LIMIT 1";
        $st = $this->pdo->prepare($query);
        $st->execute([$username]);
        return (bool) $st->fetchColumn();
    }
    
    public function emailExists(string $email): bool
    {
        $query = "SELECT 1 FROM admin WHERE email = ? LIMIT 1";
        $st = $this->pdo->prepare($query);
        $st->execute([$email]);
        return (bool) $st->fetchColumn();
    }

    public function create(string $username, string $email, string $hash): int
    {
        $query = "INSERT INTO admin (username, email, password) VALUES (?, ?, ?)";
        $st = $this->pdo->prepare($query);
        $st->execute([$username, $email, $hash]);
        return (int) $this->pdo->lastInsertId();
    }

    public function validateCredentials(string $username, string $password): bool
    {
        $query = "SELECT password FROM admin WHERE username = ?";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$username]);

        $hash = $stmt->fetchColumn();

        if ($hash === false) {
            return false;
        }

        return password_verify($password, $hash);
    }

    public function getAdminByUsername(string $username): ?array
    {
        $query = "SELECT id, username, email FROM admin WHERE username = ?";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$username]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }


    public function getAdminByEmail(string $email): ?array
    {
        $query = "SELECT id, username, email FROM admin WHERE email = ?";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }


    public function getAdminById(int $id): ?array
    {
        $query = "SELECT id, username, email FROM admin WHERE id = ?";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }


}