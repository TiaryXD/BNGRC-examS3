<?php
namespace app\repositories;

class VenteRepository {
  public function __construct(private \PDO $pdo) {}

  public function getReductionPct(): float {
    $stmt = $this->pdo->prepare("SELECT valeur FROM parametres WHERE cle='vente_reduction_pct' LIMIT 1");
    $stmt->execute();
    $v = $stmt->fetchColumn();
    return $v !== false ? (float)$v : 0.0;
  }

  public function getDonsVendables(): array
    {
        $sql = "SELECT
                    d.id,
                    d.description,
                    d.quantite,
                    d.unite,
                    d.type_id,

                    b.prix_unitaire,
                    t.nom AS type_nom

                FROM dons d
                JOIN types t ON t.id = d.type_id

                LEFT JOIN besoins b
                    ON b.type_id = d.type_id
                    AND LOWER(TRIM(b.description)) = LOWER(TRIM(d.description))

                WHERE d.quantite > 0
                AND LOWER(TRIM(t.nom)) <> 'argent'

                GROUP BY d.id
                ORDER BY d.created_at DESC";

        return $this->pdo->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }


  public function getDonById(int $id): ?array {
    $stmt = $this->pdo->prepare("SELECT * FROM dons WHERE id=:id LIMIT 1");
    $stmt->execute(['id'=>$id]);
    $row = $stmt->fetch(\PDO::FETCH_ASSOC);
    return $row ?: null;
  }

  public function insertVente(array $data): int {
    $sql = "INSERT INTO ventes
            (don_id, quantite, prix_unitaire, reduction_pct, prix_unitaire_final, montant_total, remarque, created_by)
            VALUES (:don_id,:quantite,:prix_unitaire,:reduction_pct,:prix_unitaire_final,:montant_total,:remarque,:created_by)";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($data);
    return (int)$this->pdo->lastInsertId();
  }

  public function decrementStockDon(int $donId, float $quantite): void {
    $sql = "UPDATE dons SET quantite = quantite - :qte WHERE id = :id AND quantite >= :qte";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute(['qte'=>$quantite, 'id'=>$donId]);

    if ($stmt->rowCount() === 0) {
      throw new \Exception("Stock insuffisant.");
    }
  }

    public function isDonVendable(int $donId): bool
    {
        $sql = "SELECT 1
                FROM dons d
                JOIN types t ON t.id = d.type_id
                WHERE d.id = :id
                AND d.quantite > 0
                AND LOWER(TRIM(t.nom)) <> 'argent'
                AND NOT EXISTS (
                    SELECT 1
                    FROM besoins b
                    LEFT JOIN (
                    SELECT besoin_id, COALESCE(SUM(quantite),0) AS qte_dist
                    FROM distributions
                    GROUP BY besoin_id
                    ) x ON x.besoin_id = b.id
                    WHERE b.type_id = d.type_id
                    AND LOWER(TRIM(b.description)) = LOWER(TRIM(d.description))
                    AND (b.quantite - COALESCE(x.qte_dist,0)) > 0
                )
                LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $donId]);
        return (bool)$stmt->fetchColumn();
    }

    public function getPrixUnitairePourDon(int $typeId, string $desc): float
    {
        $sql = "SELECT MAX(prix_unitaire) AS pu
                FROM besoins
                WHERE type_id = :type_id
                AND LOWER(TRIM(description)) = LOWER(TRIM(:desc))
                AND prix_unitaire IS NOT NULL";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'type_id' => $typeId,
            'desc' => $desc
        ]);
        return (float)($stmt->fetchColumn() ?: 0);
    }


}
