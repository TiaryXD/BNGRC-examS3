<?php

namespace app\repositories;

use PDO;

class DistributionRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function insert_distribution($besoinId, $donId, $quantite, $createdBy, $remarque = null)
    {
        $sql = "INSERT INTO distributions
                (besoin_id, don_id, quantite, created_by, remarque)
                VALUES (?,?,?,?,?)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $besoinId,
            $donId,
            $quantite,
            $createdBy,
            $remarque
        ]);

        return $this->pdo->lastInsertId();
    }

    public function get_distribution()
    {
        $sql = "SELECT d.*, 
                       b.description as besoin_desc,
                       don.description as don_desc
                FROM distributions d
                JOIN besoins b ON d.besoin_id = b.id
                JOIN dons don ON d.don_id = don.id
                ORDER BY d.date_distribution DESC";

        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDistributionsByVilleId(int $villeId)
    {
        $sql = "SELECT *
                FROM v_distributions_ville
                WHERE ville_id = :villeId
                ORDER BY distribution_date DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['villeId' => $villeId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertDistribution(int $besoinId, string $description, float $quantite, ?string $remarque, $createdBy): void
    {
        $sql = "
        INSERT INTO distributions (besoin_id, description, quantite, remarque, created_by)
        VALUES (:besoin_id, :description, :quantite, :remarque, :created_by)
        ";
        $st = $this->pdo->prepare($sql);
        $st->execute([
        ':besoin_id' => $besoinId,
        ':description' => $description,
        ':quantite' => $quantite,
        ':remarque' => $remarque,
        ':created_by' => $createdBy,
        ]);
    }


}
