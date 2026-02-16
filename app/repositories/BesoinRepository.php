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

    public function insert_besoin($villeId, $typeId, $description, $quantite, $unite, $remarque = null, $prixUnitaire = null)
    {
        $sql = "INSERT INTO besoins
                (ville_id, type_id, description, quantite, unite, remarque, prix_unitaire)
                VALUES (?,?,?,?,?,?,?)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $villeId,
            $typeId,
            $description,
            $quantite,
            $unite,
            $remarque,
            $prixUnitaire
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

    public function getBesoinsByVilleAndType(int $villeId, int $typeId): array
    {
        $sql = "
        SELECT b.*
        FROM besoins b
        WHERE b.ville_id = :ville_id
            AND b.type_id  = :type_id
        ORDER BY b.created_at DESC
        ";
        $st = $this->pdo->prepare($sql);
        $st->execute([':ville_id' => $villeId, ':type_id' => $typeId]);
        return $st->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getBesoinById(int $id): ?array
    {
        $sql = "SELECT * FROM besoins WHERE id = :id LIMIT 1";
        $st = $this->pdo->prepare($sql);
        $st->execute([':id' => $id]);
        $row = $st->fetch(\PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function getTotalDistribuePourBesoin(int $besoinId): float
    {
        $sql = "SELECT COALESCE(SUM(quantite),0) AS total
                FROM distributions
                WHERE besoin_id = :id";
        $st = $this->pdo->prepare($sql);
        $st->execute([':id' => $besoinId]);
        return (float)($st->fetch(\PDO::FETCH_ASSOC)['total'] ?? 0);
    }

    public function getBesoinByVilleAndDonNom(int $villeId, string $donNom): ?array
    {
        $sql = "
            SELECT *
            FROM besoins
            WHERE ville_id = :ville_id
            AND LOWER(TRIM(description)) = LOWER(TRIM(:don_nom))
            LIMIT 1
        ";
        $st = $this->pdo->prepare($sql);
        $st->execute([
            ':ville_id' => $villeId,
            ':don_nom'  => $donNom,
        ]);

        $row = $st->fetch(\PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function getBesoinsAvecResteByVille(int $villeId): array
    {
        $sql = "
            SELECT 
                b.id,
                b.description,
                b.quantite,
                b.unite,
                b.remarque,
                b.created_at,
                t.nom AS type_nom,

                COALESCE(SUM(di.quantite), 0) AS total_distribue,
                (b.quantite - COALESCE(SUM(di.quantite), 0)) AS reste

            FROM besoins b
            JOIN types t ON t.id = b.type_id
            LEFT JOIN distributions di ON di.besoin_id = b.id
            WHERE b.ville_id = :ville_id
            GROUP BY b.id, b.description, b.quantite, b.unite, b.remarque, b.created_at, t.nom
            ORDER BY t.nom, b.description
        ";
        $st = $this->pdo->prepare($sql);
        $st->execute([':ville_id' => $villeId]);
        return $st->fetchAll(\PDO::FETCH_ASSOC);
    }

}
