<?php

namespace app\repositories;

use PDO;

class DonRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function insert_don($typeId, $description, $quantite, $unite, $dateReception, $source = null, $remarque = null)
    {
        $sql = "INSERT INTO dons
                (type_id, description, quantite, unite, date_reception, source, remarque)
                VALUES (?,?,?,?,?,?,?)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $typeId,
            $description,
            $quantite,
            $unite,
            $dateReception,
            $source,
            $remarque
        ]);

        return $this->pdo->lastInsertId();
    }

    public function get_dons()
    {
        $sql = "SELECT d.*, t.nom as type_nom
                FROM dons d
                JOIN types t ON d.type_id = t.id
                ORDER BY d.date_reception DESC";

        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_historique()
    {
        $sql = "SELECT d.id,
                    d.description,
                    d.quantite,
                    d.unite,
                    d.date_reception,
                    d.source,
                    d.created_at,
                    t.nom AS type_nom
                FROM dons d
                JOIN types t ON d.type_id = t.id 
                ORDER BY d.created_at DESC";

        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

}
