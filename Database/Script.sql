CREATE DATABASE BNGRC;
USE BNGRC;
CREATE TABLE admin (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- 2. Régions (optionnel mais utile si on veut regrouper les villes)
CREATE TABLE regions (
    id          TINYINT AUTO_INCREMENT PRIMARY KEY,
    nom         VARCHAR(100) NOT NULL UNIQUE     -- ex: Analamanga, Atsinanana, Diana...
    );

-- 3. Villes / Communes (les sinistrés sont regroupés par ville)
CREATE TABLE villes (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nom         VARCHAR(150) NOT NULL,
    region_id   TINYINT DEFAULT NULL,
    population  INT DEFAULT NULL,                   -- estimation, pour info
    FOREIGN KEY (region_id) REFERENCES regions(id) ON DELETE SET NULL
);

-- 4. Types de dons / besoins (très recommandé)
CREATE TABLE types (
    id          TINYINT AUTO_INCREMENT PRIMARY KEY,
    nom         VARCHAR(50) NOT NULL UNIQUE      -- Nature, Matériaux, Argent
);

-- 5. Besoins exprimés par ville
CREATE TABLE besoins (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ville_id      INT UNSIGNED NOT NULL,
    type_id       TINYINT UNSIGNED NOT NULL,
    description   VARCHAR(255) NOT NULL,
    quantite      DECIMAL(12,2) NOT NULL DEFAULT 0,
    unite         VARCHAR(50) NOT NULL,
    remarque      TEXT DEFAULT NULL,
    prix_unitaire DECIMAL(12,2) DEFAULT NULL,
    is_base       TINYINT(1) NOT NULL DEFAULT 0,
    created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_besoins_ville
      FOREIGN KEY (ville_id) REFERENCES villes(id) ON DELETE CASCADE,

    CONSTRAINT fk_besoins_type
      FOREIGN KEY (type_id) REFERENCES types(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE dons (
    id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    type_id        TINYINT UNSIGNED NOT NULL,
    description    VARCHAR(255) NOT NULL,
    quantite       DECIMAL(12,2) NOT NULL DEFAULT 0,
    unite          VARCHAR(50) NOT NULL,
    date_reception DATE NOT NULL,
    source         VARCHAR(150) DEFAULT NULL,
    remarque       TEXT DEFAULT NULL,
    is_base        TINYINT(1) NOT NULL DEFAULT 0,
    created_at     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_dons_type
      FOREIGN KEY (type_id) REFERENCES types(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE distributions (
    id                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    besoin_id         INT UNSIGNED NOT NULL,
    don_id            INT UNSIGNED NOT NULL,
    quantite          DECIMAL(12,2) NOT NULL,
    remarque          TEXT DEFAULT NULL,
    created_by        INT UNSIGNED DEFAULT NULL,
    date_distribution DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_distributions_besoin
      FOREIGN KEY (besoin_id) REFERENCES besoins(id) ON DELETE CASCADE,

    CONSTRAINT fk_distributions_don
      FOREIGN KEY (don_id) REFERENCES dons(id) ON DELETE RESTRICT,

    CONSTRAINT fk_distributions_admin
      FOREIGN KEY (created_by) REFERENCES admin(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 1. Régions (quelques régions concernées par des catastrophes récentes ou fréquentes)
INSERT INTO regions (nom) VALUES
('Analamanga'),
('Atsinanana'),
('Boeny'),
('Diana'),
('Atsimo-Andrefana');

-- 2. Villes / Communes (exemples réalistes)
INSERT INTO villes (nom, region_id, population) VALUES
('Antananarivo', 1, 2800000),
('Toamasina',    2,  325000),
('Mahajanga',    3,  245000),
('Antsiranana',  4,  130000),
('Toliara',      5,  180000),
('Ambohidratrimo', 1, 150000),   -- banlieue d'Antananarivo
('Brickaville',  2,   35000),
('Marovoay',     3,   45000);

-- 3. Types (les 3 catégories demandées)
INSERT INTO types (nom) VALUES
('Nature'),
('Matériaux'),
('Argent');

DELETE FROM villes WHERE nom IN ('Ambohidratrimo');

CREATE VIEW v_distributions_ville AS
SELECT
    di.id                AS distribution_id,
    di.description       AS distribution_description,
    di.quantite          AS distribution_quantite,
    di.remarque          AS distribution_remarque,
    di.created_by        AS distribution_created_by,
    di.date_distribution AS distribution_date,
    v.id                 AS ville_id,
    v.nom                AS ville_nom,
    b.id                 AS besoin_id,
    b.description        AS besoin_description,
    b.unite              AS besoin_unite,
    b.type_id            AS besoin_type_id,

    t.nom                AS type_nom
FROM distributions di
JOIN besoins b ON b.id = di.besoin_id
JOIN villes  v ON v.id = b.ville_id
JOIN types   t ON t.id = b.type_id;

INSERT INTO dons (type_id, description, quantite, unite, date_reception, source)
VALUES
-- Nourriture
(1, 'Riz', 5000, 'kg', '2026-01-10', 'ONG Aide Madagascar'),
(1, 'Riz', 2500, 'kg', '2026-01-15', 'UNICEF'),
(1, 'Eau', 3000, 'litre', '2026-01-12', 'Croix Rouge'),
(1, 'Eau', 1500, 'litre', '2026-01-20', 'Entreprise STAR'),
-- Secours
(1, 'Couverture', 800, 'pièce', '2026-01-18', 'ONG Humanité'),
-- Matériaux
(2, 'Tôle', 400, 'pièce', '2026-01-22', 'Ministère Habitat'),
(2, 'Bois', 120, 'm3', '2026-01-25', 'Entreprise privée'),
-- Financier
(3, 'Aide financière', 15000000, 'Ar', '2026-01-28', 'Banque BNI');


ALTER TABLE besoins 
ADD prix_unitaire DECIMAL(12,2) DEFAULT NULL;
ALTER TABLE distributions ADD description VARCHAR(255) DEFAULT NULL;

INSERT INTO distributions (besoin_id, don_id, description, quantite, remarque, created_by)
VALUES
-- Riz distribué
(1, 1, 'Riz', 2000, 'Distribution urgente', 1),
(2, 2, 'Riz', 1500, 'Aide familles sinistrées', 1),
-- Eau
(3, 3, 'Eau', 1200, 'Distribution eau potable', 1),
(3, 4, 'Eau', 800,  'Renfort cyclone', 1),
-- Couvertures
(4, 5, 'Couverture', 300, 'Protection nuit', 1),
-- Matériaux
(5, 6, 'Tôle', 120, 'Réparation habitations', 1),
(6, 7, 'Bois', 40,  'Reconstruction', 1),
-- Financier
(7, 8, 'Aide financière', 5000000, 'Aide directe ménages', 1);

CREATE TABLE achats (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    ville_id         INT NOT NULL,
    besoin_id        INT NOT NULL,
    quantite         DECIMAL(12,2) NOT NULL,
    prix_unitaire    DECIMAL(12,2) NOT NULL,
    montant_total    DECIMAL(12,2) NOT NULL,
    remarque         TEXT DEFAULT NULL,
    created_by       INT DEFAULT NULL,
    date_achat       DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ville_id)   REFERENCES villes(id)  ON DELETE CASCADE,
    FOREIGN KEY (besoin_id)  REFERENCES besoins(id) ON DELETE RESTRICT,
    FOREIGN KEY (created_by) REFERENCES admin(id)   ON DELETE SET NULL
);
CREATE OR REPLACE VIEW v_besoins_couverture AS
SELECT
    b.id,
    b.ville_id,
    b.type_id,
    b.description,
    b.quantite,
    b.unite,
    b.prix_unitaire,
    b.remarque,
    b.created_at,
    t.nom AS type_nom,

    /* Montant total du besoin (si Nature/Matériaux) */
    CASE
      WHEN b.prix_unitaire IS NULL THEN NULL
      ELSE (b.quantite * b.prix_unitaire)
    END AS montant_total,

    /* Montant couvert via achats */
    COALESCE((
        SELECT SUM(a.montant_total)
        FROM achats a
        WHERE a.besoin_id = b.id
    ), 0) AS montant_achete,

    /* Montant restant */
    CASE
      WHEN b.prix_unitaire IS NULL THEN NULL
      ELSE GREATEST(
        (b.quantite * b.prix_unitaire) - COALESCE((
            SELECT SUM(a.montant_total)
            FROM achats a
            WHERE a.besoin_id = b.id
        ), 0),
        0
      )
    END AS montant_restant,

    /* % couverture */
    CASE
      WHEN b.prix_unitaire IS NULL THEN NULL
      WHEN (b.quantite * b.prix_unitaire) <= 0 THEN 0
      ELSE ROUND(
        (
          COALESCE((
              SELECT SUM(a.montant_total)
              FROM achats a
              WHERE a.besoin_id = b.id
          ), 0) / (b.quantite * b.prix_unitaire)
        ) * 100
      , 2)
    END AS couverture_pct

FROM besoins b
JOIN types t ON t.id = b.type_id;

UPDATE besoins
SET quantite = 120000000, unite = 'Ar'
WHERE ville_id = 2 AND type_id = 3;

ALTER TABLE dons
  ADD COLUMN is_base TINYINT(1) NOT NULL DEFAULT 0;

ALTER TABLE besoins
  ADD COLUMN is_base TINYINT(1) NOT NULL DEFAULT 0;

INSERT INTO besoins
(ville_id, type_id, description, quantite, unite, remarque, prix_unitaire, is_base)
VALUES
-- Antananarivo
(1,1,'Riz',2000,'kg','Aide alimentaire',3500,1),
(1,1,'Eau',1000,'litre','Eau potable',1000,1),
(1,2,'Tôle',80,'pièce','Réhabilitation maisons',45000,1),

-- Toamasina
(2,1,'Riz',1500,'kg','Zone cyclone',3500,1),
(2,1,'Couverture',150,'pièce','Protection familles',25000,1),

-- Mahajanga
(3,2,'Bois',20,'m3','Réparation habitations',350000,1),

-- Antsiranana
(4,1,'Eau',500,'litre','Eau potable',1000,1),

-- Toliara
(5,2,'Tôle',50,'pièce','Réhabilitation',45000,1),

-- Brickaville
(7,1,'Riz',500,'kg','Urgence alimentaire',3500,1),

-- Marovoay
(8,3,'Aide financière',1300000,'Ar','Soutien sinistrés',NULL,1);

INSERT INTO dons
(type_id, description, quantite, unite, date_reception, source, remarque, is_base)
VALUES
(3,'Argent',8000000,'Ar',CURDATE(),'Etat Malagasy','Fonds urgence',1),
(3,'Argent',3500000,'Ar',CURDATE(),'ONG Internationale','Aide humanitaire',1),

(1,'Riz',1200,'kg',CURDATE(),'Donateurs privés','Collecte nationale',1),
(1,'Eau potable',1500,'litre',CURDATE(),'Association Eau','Distribution eau',1),
(1,'Couverture',120,'pièce',CURDATE(),'Croix Rouge','Protection froid',1),

(2,'Tôle',40,'pièce',CURDATE(),'Entreprise BTP','Matériaux secours',1),
(2,'Bois',12,'m3',CURDATE(),'Scierie locale','Reconstruction',1),

(1,'Riz blanc',600,'kg',CURDATE(),'Collecte publique','Aide alimentaire',1),
(1,'Eau',700,'litre',CURDATE(),'ONG locale','Eau potable',1),

(3,'Argent',1200000,'Ar',CURDATE(),'Collecte citoyenne','Solidarité',1);

INSERT INTO besoins
(ville_id, type_id, description, quantite, unite, remarque, prix_unitaire, is_base)
VALUES
-- Antananarivo
(1,1,'Riz blanc',800,'kg','Renfort alimentaire',3500,0),
(1,2,'Bois',10,'m3','Réparation écoles',350000,0),

-- Toamasina
(2,1,'Eau potable',600,'litre','Quartiers isolés',1000,0),

-- Mahajanga
(3,1,'Couverture',60,'pièce','Familles déplacées',25000,0),

-- Antsiranana
(4,2,'Tôle ondulée',25,'pièce','Maisons détruites',45000,0),

-- Toliara
(5,1,'Riz',400,'kg','Sécheresse prolongée',3500,0),

-- Brickaville
(7,2,'Bois construction',5,'m3','Pont endommagé',350000,0),

-- Marovoay
(8,1,'Eau',300,'litre','Inondations',1000,0),

-- Antananarivo
(1,3,'Aide financière urgente',500000,'Ar','Cas médicaux',NULL,0),

-- Toamasina
(2,1,'Farine',250,'kg','Aide nutritionnelle',2800,0);

INSERT INTO dons
(type_id, description, quantite, unite, date_reception, source, remarque, is_base)
VALUES
-- Argent
(3,'Argent',1500000,'Ar',CURDATE(),'Entreprise privée','Donation exceptionnelle',0),
(3,'Argent',900000,'Ar',CURDATE(),'Collecte locale','Soutien citoyens',0),

-- Nature
(1,'Riz',700,'kg',CURDATE(),'Association locale','Collecte alimentaire',0),
(1,'Riz blanc',500,'kg',CURDATE(),'ONG Asie','Aide alimentaire',0),
(1,'Eau potable',800,'litre',CURDATE(),'UNICEF','Approvisionnement eau',0),
(1,'Couverture',90,'pièce',CURDATE(),'Croix Rouge','Protection climatique',0),

-- Matériaux
(2,'Tôle',30,'pièce',CURDATE(),'Entreprise BTP','Don matériaux',0),
(2,'Bois',8,'m3',CURDATE(),'Scierie Nord','Reconstruction',0),

-- Mix
(1,'Farine',300,'kg',CURDATE(),'Programme alimentaire','Nutrition',0),
(3,'Argent',450000,'Ar',CURDATE(),'Don anonyme','Soutien urgence',0);

CREATE TABLE ventes (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  don_id INT UNSIGNED NOT NULL,
  quantite DECIMAL(12,2) NOT NULL,
  prix_unitaire DECIMAL(12,2) NOT NULL,
  reduction_pct DECIMAL(5,2) NOT NULL DEFAULT 0,
  prix_unitaire_final DECIMAL(12,2) NOT NULL,
  montant_total DECIMAL(12,2) NOT NULL,
  remarque TEXT DEFAULT NULL,
  created_by INT UNSIGNED DEFAULT NULL,
  date_vente DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

  CONSTRAINT fk_ventes_don
    FOREIGN KEY (don_id) REFERENCES dons(id) ON DELETE RESTRICT,

  CONSTRAINT fk_ventes_admin
    FOREIGN KEY (created_by) REFERENCES admin(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE parametres (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  cle VARCHAR(80) NOT NULL UNIQUE,
  valeur VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- valeur par défaut : 10% de réduction
INSERT INTO parametres (cle, valeur) VALUES ('vente_reduction_pct', '10');

SELECT
  d.*,
  t.nom AS type_nom
FROM dons d
JOIN types t ON t.id = d.type_id
WHERE d.quantite > 0
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
);
