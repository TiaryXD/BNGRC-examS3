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

    public function getTotalDonsParGenre(): array
    {
        $sql = "
            SELECT
                LOWER(TRIM(d.description)) AS don_nom,
                d.unite AS unite,
                SUM(d.quantite) AS total_dons
            FROM dons d
            GROUP BY LOWER(TRIM(d.description)), d.unite
            ORDER BY don_nom
        ";

        return $this->pdo->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }


    public function getTotalDistributionsParGenre(): array
    {
        $sql = "
            SELECT
                CASE
                WHEN LOWER(di.description) LIKE '%riz%' THEN 'riz'
                WHEN LOWER(di.description) LIKE '%eau%' THEN 'eau'
                ELSE LOWER(TRIM(di.description))
                END AS don_nom,
                b.unite AS unite,
                COALESCE(SUM(di.quantite), 0) AS total_distributions
            FROM distributions di
            LEFT JOIN besoins b ON b.id = di.besoin_id
            GROUP BY don_nom, b.unite
            ORDER BY don_nom
        ";

        return $this->pdo->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getRestePourDon(string $donNom, string $unite): float
    {
        $sqlRecus = "
        SELECT COALESCE(SUM(quantite),0) AS total
        FROM dons
        WHERE LOWER(TRIM(description)) = LOWER(TRIM(:don))
            AND unite = :unite
        ";
        $st = $this->pdo->prepare($sqlRecus);
        $st->execute([':don' => $donNom, ':unite' => $unite]);
        $recus = (float)($st->fetch(\PDO::FETCH_ASSOC)['total'] ?? 0);

        $sqlDistrib = "
        SELECT COALESCE(SUM(quantite),0) AS total
        FROM distributions
        WHERE LOWER(TRIM(description)) = LOWER(TRIM(:don))
        ";
        $st = $this->pdo->prepare($sqlDistrib);
        $st->execute([':don' => $donNom]);
        $distrib = (float)($st->fetch(\PDO::FETCH_ASSOC)['total'] ?? 0);

        return max(0, $recus - $distrib);
    }


}
