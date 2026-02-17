<?php

namespace app\repositories;

class RecapRepository
{
    public function __construct(private \PDO $pdo) {}

    public function getRecap(): array
    {
        // 1) Besoins totaux & satisfaits (montant)
        $sqlBesoins = "
            SELECT
              COALESCE(SUM(
                CASE
                  WHEN t.nom = 'Argent' THEN b.quantite
                  ELSE b.quantite * COALESCE(b.prix_unitaire, 0)
                END
              ),0) AS besoins_total_montant,

              COALESCE(SUM(
                CASE
                  WHEN t.nom = 'Argent' THEN COALESCE(di.total_distribue, 0)
                  ELSE COALESCE(di.total_distribue, 0) * COALESCE(b.prix_unitaire, 0)
                END
              ),0) AS besoins_satisfaits_montant

            FROM besoins b
            JOIN types t ON t.id = b.type_id
            LEFT JOIN (
              SELECT besoin_id, SUM(quantite) AS total_distribue
              FROM distributions
              GROUP BY besoin_id
            ) di ON di.besoin_id = b.id
        ";

        $besoins = $this->pdo->query($sqlBesoins)->fetch(\PDO::FETCH_ASSOC) ?: [];

        // 2) Dons reçus (montant) : Argent = quantite ; sinon quantite * prix_unitaire (par genre)
        // genre = 1er mot (riz blanc => riz)
        $sqlDonsRecus = "
            SELECT
              COALESCE(SUM(
                CASE
                  WHEN t.nom = 'Argent' THEN d.quantite
                  ELSE d.quantite * COALESCE(p.prix, 0)
                END
              ),0) AS dons_recus_montant
            FROM dons d
            JOIN types t ON t.id = d.type_id
            LEFT JOIN (
              SELECT
                SUBSTRING_INDEX(LOWER(TRIM(description)),' ',1) AS genre,
                MAX(prix_unitaire) AS prix
              FROM besoins
              WHERE prix_unitaire IS NOT NULL
              GROUP BY SUBSTRING_INDEX(LOWER(TRIM(description)),' ',1)
            ) p
              ON p.genre = SUBSTRING_INDEX(LOWER(TRIM(d.description)),' ',1)
        ";

        $donsRecus = (float)$this->pdo->query($sqlDonsRecus)->fetchColumn();

        // 3) Dons dispatchés (montant) = distributions valorisées par prix_unitaire du besoin
        $sqlDonsDispat = "
            SELECT
              COALESCE(SUM(
                CASE
                  WHEN t.nom = 'Argent' THEN di.quantite
                  ELSE di.quantite * COALESCE(b.prix_unitaire, 0)
                END
              ),0) AS dons_dispatches_montant
            FROM distributions di
            JOIN besoins b ON b.id = di.besoin_id
            JOIN types t ON t.id = b.type_id
        ";

        $donsDispatches = (float)$this->pdo->query($sqlDonsDispat)->fetchColumn();

        return [
            'besoins_total_montant'      => (float)($besoins['besoins_total_montant'] ?? 0),
            'besoins_satisfaits_montant' => (float)($besoins['besoins_satisfaits_montant'] ?? 0),
            'dons_recus_montant'         => (float)$donsRecus,
            'dons_dispatches_montant'    => (float)$donsDispatches,
        ];
    }
}
