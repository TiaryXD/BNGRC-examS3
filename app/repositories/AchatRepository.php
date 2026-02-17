<?php

namespace app\repositories;

use PDO;

class AchatRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    public function getTotalDonsArgent(): float
    {
        $sql = "SELECT COALESCE(SUM(d.quantite), 0) AS total
                FROM dons d
                JOIN types t ON d.type_id = t.id
                WHERE t.nom = 'Argent'";
        return (float) $this->pdo->query($sql)->fetchColumn();
    }

    public function getTotalAchatsMontant(): float
    {
        $sql = "SELECT COALESCE(SUM(a.montant_total), 0) AS total
                FROM achats a";
        return (float) $this->pdo->query($sql)->fetchColumn();
    }

    public function getTotalDistributionsArgent(): float
    {
        $sql = "
            SELECT COALESCE(SUM(di.quantite),0)
            FROM distributions di
            WHERE LOWER(di.description) LIKE 'aide financière%'
        ";

        return (float)$this->pdo->query($sql)->fetchColumn();
    }


    public function getArgentDisponible(): float
    {
        return
            $this->getTotalDonsArgent()
            - $this->getTotalAchatsMontant()
            - $this->getTotalDistributionsArgent();
    }

    public function getBesoinInfo(int $besoinId): ?array
    {
        $sql = "SELECT b.id, b.ville_id, b.type_id, b.description, b.prix_unitaire, b.unite,
                       t.nom AS type_nom
                FROM besoins b
                JOIN types t ON b.type_id = t.id
                WHERE b.id = :id
                LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $besoinId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function insert_achat(
        int $villeId,
        int $besoinId,
        float $quantite,
        float $prixUnitaire,
        float $montantTotal,
        ?string $remarque = null,
        ?int $createdBy = null
    ): int {
        $sql = "INSERT INTO achats
                (ville_id, besoin_id, quantite, prix_unitaire, montant_total, remarque, created_by)
                VALUES (?,?,?,?,?,?,?)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $villeId,
            $besoinId,
            $quantite,
            $prixUnitaire,
            $montantTotal,
            $remarque,
            $createdBy
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function get_achats(?int $villeId = null): array
    {
        $sql = "SELECT a.*,
                       v.nom AS ville_nom,
                       b.description AS besoin_description,
                       b.unite AS besoin_unite,
                       t.nom AS type_nom
                FROM achats a
                JOIN villes v ON a.ville_id = v.id
                JOIN besoins b ON a.besoin_id = b.id
                JOIN types t ON b.type_id = t.id";

        $params = [];
        if ($villeId) {
            $sql .= " WHERE a.ville_id = :villeId";
            $params['villeId'] = $villeId;
        }

        $sql .= " ORDER BY a.date_achat DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMontantAchatByVille(int $villeId): float
    {
        $sql = "SELECT COALESCE(SUM(montant_total),0)
                FROM achats
                WHERE ville_id = :ville_id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'ville_id' => $villeId
        ]);

        return (float)$stmt->fetchColumn();
    }
}
