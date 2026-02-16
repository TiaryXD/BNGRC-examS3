<?php

namespace app\repositories;

use PDO;

class RegionRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function get_region()
    {
        return $this->pdo
            ->query("SELECT * FROM regions ORDER BY nom")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert_region(string $nom)
    {
        $stmt = $this->pdo->prepare("INSERT INTO regions(nom) VALUES(?)");
        $stmt->execute([$nom]);

        return $this->pdo->lastInsertId();
    }
}
