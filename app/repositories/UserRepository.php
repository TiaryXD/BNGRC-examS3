<?php

namespace app\repositories;

use app\models\UserModel;
use PDO;

class UserRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function emailExists($email)
    {
        $query = "SELECT 1 
				  FROM admin 
				  WHERE email = ? 
				  LIMIT 1";

        $st = $this->pdo->prepare($query);

        $st->execute([(string) $email]);

        return (bool) $st->fetchColumn();
    }

    public function create($email, $hash)
    {
        $query = "INSERT INTO admin(email, password)
      			  VALUES(?,?)";

        $st = $this->pdo->prepare($query);

        $st->execute([
            (string) $email,
            (string) $hash,
        ]);

        return $this->pdo->lastInsertId();
    }

    public function validateCredentials($email, $password)
    {
        $query = "SELECT password
                  FROM admin 
                  WHERE email = ?";

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$email]);

        $hash = $stmt->fetchColumn();

        return ($hash && password_verify($password, $hash));
    }

    public function getUserByMail($email)
    {
        $query = "SELECT id, email
                  FROM admin 
                  WHERE email = ?";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$email]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?? null;
    }

    public function getUserById($id)
    {
        $query = "SELECT id, email
                  FROM admin 
                  WHERE id = ?";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?? null;
    }


}