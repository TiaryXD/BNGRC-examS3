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
    // Total dons Argent (en montant)
    public function getTotalDonsArgent(): float
    {
        $sql = "SELECT COALESCE(SUM(d.quantite), 0) AS total
                FROM dons d
                JOIN types t ON d.type_id = t.id
                WHERE t.nom = 'Argent'";
        return (float) $this->pdo->query($sql)->fetchColumn();
    }

    // Total achats (en montant_total)
    public function getTotalAchatsMontant(): float
    {
        $sql = "SELECT COALESCE(SUM(a.montant_total), 0) AS total
                FROM achats a";
        return (float) $this->pdo->query($sql)->fetchColumn();
    }

    // Argent disponible = dons argent - achats
    public function getArgentDisponible(): float
    {
        return $this->getTotalDonsArgent() - $this->getTotalAchatsMontant();
    }

    // Récupérer infos d'un besoin (type + prix_unitaire + ville_id)
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

    // Liste achats (filtre ville optionnel)
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
}
