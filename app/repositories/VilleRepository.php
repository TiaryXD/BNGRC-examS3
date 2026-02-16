<?php

namespace app\repositories;

use PDO;

class VilleRepository {
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function get_ville()
    {
        $sql = "SELECT v.*, r.nom as region_nom
                FROM villes v
                LEFT JOIN regions r ON v.region_id = r.id
                ORDER BY v.nom";

        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert_ville($nom, $regionId, $population = null)
    {
        $sql = "INSERT INTO villes(nom, region_id, population)
                VALUES(?,?,?)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$nom, $regionId, $population]);

        return $this->pdo->lastInsertId();
    }
}
