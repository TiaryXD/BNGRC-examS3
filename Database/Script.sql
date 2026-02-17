CREATE DATABASE BNGRC;
USE BNGRC;
CREATE TABLE admin (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL
);

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
    nom         VARCHAR(50) NOT NULL UNIQUE      -- Nature, Materiaux, Argent
);

-- 5. Besoins exprimés par ville
CREATE TABLE besoins (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    ville_id      INT NOT NULL,
    type_id       TINYINT NOT NULL,
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
);

CREATE TABLE dons (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    type_id        TINYINT NOT NULL,
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
);

CREATE TABLE distributions (
    id                INT AUTO_INCREMENT PRIMARY KEY,
    besoin_id         INT NOT NULL,
    don_id            INT NOT NULL,
    quantite          DECIMAL(12,2) NOT NULL,
    remarque          TEXT DEFAULT NULL,
    created_by        INT DEFAULT NULL,
    date_distribution DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_distributions_besoin
      FOREIGN KEY (besoin_id) REFERENCES besoins(id) ON DELETE CASCADE,

    CONSTRAINT fk_distributions_don
      FOREIGN KEY (don_id) REFERENCES dons(id) ON DELETE RESTRICT,

    CONSTRAINT fk_distributions_admin
      FOREIGN KEY (created_by) REFERENCES admin(id) ON DELETE SET NULL
);

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
('Ambohidratrimo', 1, 150000),
('Brickaville',  2,   35000),
('Marovoay',     3,   45000);

-- 3. Types (les 3 catégories demandées)

DELETE FROM villes WHERE nom IN ('Ambohidratrimo');

CREATE VIEW v_distributions_ville AS
SELECT
    di.id                AS distribution_id,
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
-- Materiaux
(2, 'Tôle', 400, 'pièce', '2026-01-22', 'Ministère Habitat'),
(2, 'Bois', 120, 'm3', '2026-01-25', 'Entreprise privée'),
-- Financier
(3, 'Aide financière', 15000000, 'Ar', '2026-01-28', 'Banque BNI');


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
-- Materiaux
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

    /* Montant total du besoin (si Nature/Materiaux) */
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


CREATE TABLE ventes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  don_id INT NOT NULL,
  quantite DECIMAL(12,2) NOT NULL,
  prix_unitaire DECIMAL(12,2) NOT NULL,
  reduction_pct DECIMAL(5,2) NOT NULL DEFAULT 0,
  prix_unitaire_final DECIMAL(12,2) NOT NULL,
  montant_total DECIMAL(12,2) NOT NULL,
  remarque TEXT DEFAULT NULL,
  created_by INT DEFAULT NULL,
  date_vente DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

  CONSTRAINT fk_ventes_don
    FOREIGN KEY (don_id) REFERENCES dons(id) ON DELETE RESTRICT,

  CONSTRAINT fk_ventes_admin
    FOREIGN KEY (created_by) REFERENCES admin(id) ON DELETE SET NULL
);

CREATE TABLE parametres (
  id INT AUTO_INCREMENT PRIMARY KEY,
  cle VARCHAR(80) NOT NULL UNIQUE,
  valeur VARCHAR(255) NOT NULL
);

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

INSERT INTO regions (nom) VALUES
('Analamanga'),
('Atsinanana'),
('Boeny'),
('Diana'),
('Atsimo-Andrefana');

INSERT INTO types (nom) VALUES
('Nature'),
('Materiaux'),
('Argent');

INSERT INTO villes (nom, region_id, population) VALUES
('Toamasina',   (SELECT id FROM regions WHERE nom='Atsinanana' LIMIT 1), NULL),
('Nosy Be',     (SELECT id FROM regions WHERE nom='Diana' LIMIT 1), NULL),
('Mananjary',   NULL, NULL),
('Farafangana', NULL, NULL),
('Morondava',   NULL, NULL);

INSERT INTO besoins
(ville_id, type_id, description, quantite, unite, prix_unitaire, is_base, created_at)
VALUES
((SELECT id FROM villes WHERE nom='Toamasina'),
 (SELECT id FROM types WHERE nom='Materiaux'),
 'Bâche',200,'pièce',15000,1,'2026-02-15'),

-- 2
((SELECT id FROM villes WHERE nom='Nosy Be'),
 (SELECT id FROM types WHERE nom='Materiaux'),
 'Tôle',40,'pièce',25000,1,'2026-02-15'),

-- 3
((SELECT id FROM villes WHERE nom='Mananjary'),
 (SELECT id FROM types WHERE nom='Argent'),
 'Argent',6000000,'Ar',1,1,'2026-02-15'),

-- 4
((SELECT id FROM villes WHERE nom='Toamasina'),
 (SELECT id FROM types WHERE nom='Nature'),
 'Eau',1500,'L',1000,1,'2026-02-15'),

-- 5
((SELECT id FROM villes WHERE nom='Nosy Be'),
 (SELECT id FROM types WHERE nom='Nature'),
 'Riz',300,'kg',3000,1,'2026-02-15'),

-- 6
((SELECT id FROM villes WHERE nom='Mananjary'),
 (SELECT id FROM types WHERE nom='Materiaux'),
 'Tôle',80,'pièce',25000,1,'2026-02-15'),

-- 7
((SELECT id FROM villes WHERE nom='Nosy Be'),
 (SELECT id FROM types WHERE nom='Argent'),
 'Argent',4000000,'Ar',1,1,'2026-02-15'),

-- 8
((SELECT id FROM villes WHERE nom='Farafangana'),
 (SELECT id FROM types WHERE nom='Materiaux'),
 'Bâche',150,'pièce',15000,1,'2026-02-16'),

-- 9
((SELECT id FROM villes WHERE nom='Mananjary'),
 (SELECT id FROM types WHERE nom='Nature'),
 'Riz',500,'kg',3000,1,'2026-02-15'),

-- 10
((SELECT id FROM villes WHERE nom='Farafangana'),
 (SELECT id FROM types WHERE nom='Argent'),
 'Argent',8000000,'Ar',1,1,'2026-02-16'),

-- 11
((SELECT id FROM villes WHERE nom='Morondava'),
 (SELECT id FROM types WHERE nom='Nature'),
 'Riz',700,'kg',3000,0,'2026-02-16'),

-- 12
((SELECT id FROM villes WHERE nom='Toamasina'),
 (SELECT id FROM types WHERE nom='Argent'),
 'Argent',12000000,'Ar',1,0,'2026-02-16'),

-- 13
((SELECT id FROM villes WHERE nom='Morondava'),
 (SELECT id FROM types WHERE nom='Argent'),
 'Argent',10000000,'Ar',1,0,'2026-02-16'),

-- 14
((SELECT id FROM villes WHERE nom='Farafangana'),
 (SELECT id FROM types WHERE nom='Nature'),
 'Eau',1000,'L',1000,0,'2026-02-15'),

-- 15
((SELECT id FROM villes WHERE nom='Morondava'),
 (SELECT id FROM types WHERE nom='Materiaux'),
 'Bâche',180,'pièce',15000,0,'2026-02-16'),

-- 16
((SELECT id FROM villes WHERE nom='Toamasina'),
 (SELECT id FROM types WHERE nom='Materiaux'),
 'Groupe électrogène',3,'pièce',6750000,0,'2026-02-15'),

-- 17
((SELECT id FROM villes WHERE nom='Toamasina'),
 (SELECT id FROM types WHERE nom='Nature'),
 'Riz',800,'kg',3000,0,'2026-02-16'),

-- 18
((SELECT id FROM villes WHERE nom='Nosy Be'),
 (SELECT id FROM types WHERE nom='Nature'),
 'Haricots',200,'kg',4000,0,'2026-02-16'),

-- 19
((SELECT id FROM villes WHERE nom='Mananjary'),
 (SELECT id FROM types WHERE nom='Materiaux'),
 'Clous',60,'kg',8000,0,'2026-02-16'),

-- 20
((SELECT id FROM villes WHERE nom='Morondava'),
 (SELECT id FROM types WHERE nom='Nature'),
 'Eau',1200,'L',1000,0,'2026-02-15'),

-- 21
((SELECT id FROM villes WHERE nom='Farafangana'),
 (SELECT id FROM types WHERE nom='Nature'),
 'Riz',600,'kg',3000,0,'2026-02-16'),

-- 22
((SELECT id FROM villes WHERE nom='Morondava'),
 (SELECT id FROM types WHERE nom='Materiaux'),
 'Bois',150,'pièce',10000,0,'2026-02-15'),

-- 23
((SELECT id FROM villes WHERE nom='Toamasina'),
 (SELECT id FROM types WHERE nom='Materiaux'),
 'Tôle',120,'pièce',25000,0,'2026-02-16'),

-- 24
((SELECT id FROM villes WHERE nom='Nosy Be'),
 (SELECT id FROM types WHERE nom='Materiaux'),
 'Clous',30,'kg',8000,0,'2026-02-16'),

-- 25
((SELECT id FROM villes WHERE nom='Mananjary'),
 (SELECT id FROM types WHERE nom='Nature'),
 'Huile',120,'L',6000,0,'2026-02-16'),

-- 26
((SELECT id FROM villes WHERE nom='Farafangana'),
 (SELECT id FROM types WHERE nom='Materiaux'),
 'Bois',100,'pièce',10000,0,'2026-02-15');

INSERT INTO dons
(type_id, description, quantite, unite, date_reception, source, remarque, is_base, created_at)
VALUES

-- 1
((SELECT id FROM types WHERE nom='Argent' LIMIT 1),
 'Argent', 5000000, 'Ar', '2026-02-16', NULL, NULL, 1, '2026-02-16 08:00:00'),

-- 2
((SELECT id FROM types WHERE nom='Argent' LIMIT 1),
 'Argent', 3000000, 'Ar', '2026-02-16', NULL, NULL, 1, '2026-02-16 08:00:00'),

-- 3
((SELECT id FROM types WHERE nom='Argent' LIMIT 1),
 'Argent', 4000000, 'Ar', '2026-02-17', NULL, NULL, 1, '2026-02-17 08:00:00'),

-- 4
((SELECT id FROM types WHERE nom='Argent' LIMIT 1),
 'Argent', 1500000, 'Ar', '2026-02-17', NULL, NULL, 1, '2026-02-17 08:00:00'),

-- 5
((SELECT id FROM types WHERE nom='Argent' LIMIT 1),
 'Argent', 6000000, 'Ar', '2026-02-17', NULL, NULL, 1, '2026-02-17 08:00:00'),

-- 6
((SELECT id FROM types WHERE nom='Nature' LIMIT 1),
 'Riz', 400, 'kg', '2026-02-16', NULL, NULL, 1, '2026-02-16 08:00:00'),

-- 7
((SELECT id FROM types WHERE nom='Nature' LIMIT 1),
 'Eau', 600, 'L', '2026-02-16', NULL, NULL, 1, '2026-02-16 08:00:00'),

-- 8
((SELECT id FROM types WHERE nom='Materiaux' LIMIT 1),
 'Tôle', 50, 'pièce', '2026-02-17', NULL, NULL, 1, '2026-02-17 08:00:00'),

-- 9
((SELECT id FROM types WHERE nom='Materiaux' LIMIT 1),
 'Bâche', 70, 'pièce', '2026-02-17', NULL, NULL, 1, '2026-02-17 08:00:00'),

-- 10
((SELECT id FROM types WHERE nom='Nature' LIMIT 1),
 'Haricots', 100, 'kg', '2026-02-17', NULL, NULL, 1, '2026-02-17 08:00:00'),

-- 11
((SELECT id FROM types WHERE nom='Nature' LIMIT 1),
 'Riz', 2000, 'kg', '2026-02-18', NULL, NULL, 0, '2026-02-18 08:00:00'),

-- 12
((SELECT id FROM types WHERE nom='Materiaux' LIMIT 1),
 'Tôle', 300, 'pièce', '2026-02-18', NULL, NULL, 0, '2026-02-18 08:00:00'),

-- 13
((SELECT id FROM types WHERE nom='Nature' LIMIT 1),
 'Eau', 5000, 'L', '2026-02-18', NULL, NULL, 0, '2026-02-18 08:00:00'),

-- 14
((SELECT id FROM types WHERE nom='Argent' LIMIT 1),
 'Argent', 20000000, 'Ar', '2026-02-19', NULL, NULL, 0, '2026-02-19 08:00:00'),

-- 15
((SELECT id FROM types WHERE nom='Materiaux' LIMIT 1),
 'Bâche', 500, 'pièce', '2026-02-19', NULL, NULL, 0, '2026-02-19 08:00:00'),

-- 16
((SELECT id FROM types WHERE nom='Nature' LIMIT 1),
 'Haricots', 88, 'kg', '2026-02-17', NULL, NULL, 0, '2026-02-17 08:00:00');
