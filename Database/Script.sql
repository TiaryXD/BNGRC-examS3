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
    id              INT AUTO_INCREMENT PRIMARY KEY,
    ville_id        INT NOT NULL,
    type_id         TINYINT NOT NULL,
    description    VARCHAR(255) NOT NULL,               -- ex: "Riz blanc", "Tôle ondulée 3m", "Aide financière urgente"
    quantite        DECIMAL(12,2) NOT NULL DEFAULT 0,
    unite           VARCHAR(50) NOT NULL,                -- kg, litre, pièce, m², Ar, sac de 50kg...
    remarque        TEXT DEFAULT NULL,
    created_at      DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ville_id) REFERENCES villes(id) ON DELETE CASCADE,
    FOREIGN KEY (type_id)  REFERENCES types(id)  ON DELETE RESTRICT
);

-- 6. Dons reçus (stock disponible)
CREATE TABLE dons (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    type_id         TINYINT NOT NULL,
    description     VARCHAR(255) NOT NULL,              
    quantite        DECIMAL(12,2) NOT NULL DEFAULT 0,
    unite           VARCHAR(50) NOT NULL,
    date_reception  DATE NOT NULL,
    source          VARCHAR(150) DEFAULT NULL,           
    remarque        TEXT DEFAULT NULL,
    created_at      DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (type_id) REFERENCES types(id) ON DELETE RESTRICT
);

-- 7. Table de liaison : attribution d'un don à un besoin
CREATE TABLE distributions (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    besoin_id       INT NOT NULL,
    don_id          INT NOT NULL,
    quantite        DECIMAL(12,2) NOT NULL,
    remarque        TEXT DEFAULT NULL,
    created_by      INT DEFAULT NULL,
    date_distribution      DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (besoin_id)  REFERENCES besoins(id)  ON DELETE CASCADE,
    FOREIGN KEY (don_id)     REFERENCES dons(id)     ON DELETE RESTRICT,
    FOREIGN KEY (created_by) REFERENCES admin(id)    ON DELETE SET NULL
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
