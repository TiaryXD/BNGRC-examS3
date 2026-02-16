<?php

namespace app\repositories;

use PDO;

class BesoinRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function insert_besoin($villeId, $typeId, $description, $quantite, $unite, $remarque = null)
    {
        $sql = "INSERT INTO besoins
                (ville_id, type_id, description, quantite, unite, remarque)
                VALUES (?,?,?,?,?,?)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $villeId,
            $typeId,
            $description,
            $quantite,
            $unite,
            $remarque
        ]);

        return $this->pdo->lastInsertId();
    }

    public function get_besoin()
    {
        $sql = "SELECT b.*, 
                       v.nom AS ville_nom,
                       t.nom AS type_nom
                FROM besoins b
                JOIN villes v ON b.ville_id = v.id
                JOIN types t ON b.type_id = t.id
                ORDER BY b.created_at DESC";

        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
     
    public function getbesoinbyidville(int $villeId)
    {
        $sql = "SELECT b.*, 
                       v.nom AS ville_nom,
                       t.nom AS type_nom
                FROM besoins b
                JOIN villes v ON b.ville_id = v.id
                JOIN types t ON b.type_id = t.id
                WHERE b.ville_id = :villeId
                ORDER BY b.created_at DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['villeId' => $villeId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
