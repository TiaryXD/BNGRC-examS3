CREATE DATABASE BNGRC;
USE BNGRC;
CREATE TABLE admin (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(100) NOT NULL
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- 2. Régions (optionnel mais utile si on veut regrouper les villes)
CREATE TABLE regions (
    id          TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom         VARCHAR(100) NOT NULL UNIQUE     -- ex: Analamanga, Atsinanana, Diana...
    );

-- 3. Villes / Communes (les sinistrés sont regroupés par ville)
CREATE TABLE villes (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom         VARCHAR(150) NOT NULL,
    region_id   TINYINT UNSIGNED DEFAULT NULL,
    population  INT DEFAULT NULL,                   -- estimation, pour info
    FOREIGN KEY (region_id) REFERENCES regions(id) ON DELETE SET NULL
);

-- 4. Types de dons / besoins (très recommandé)
CREATE TABLE types (
    id          TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom         VARCHAR(50) NOT NULL UNIQUE      -- Nature, Matériaux, Argent
);

-- 5. Besoins exprimés par ville
CREATE TABLE besoins (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ville_id        INT UNSIGNED NOT NULL,
    type_id         TINYINT UNSIGNED NOT NULL,
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
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    type_id         TINYINT UNSIGNED NOT NULL,
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
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    besoin_id       INT UNSIGNED NOT NULL,
    don_id          INT UNSIGNED NOT NULL,
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

-- 4. Besoins (exemples variés et réalistes)
INSERT INTO besoins (ville_id, type_id, description, quantite, unite, remarque, created_at) VALUES
-- Antananarivo (Analamanga)
(1, 1, 'Riz blanc',               8500.00, 'kg',     'Priorité alimentaire familles sinistrées', '2025-01-15'),
(1, 1, 'Huile de cuisson',        3200.00, 'litre',  NULL, '2025-01-16'),
(1, 3, 'Aide financière urgente', 45000000.00, 'Ar', 'Pour achat matériaux reconstruction', '2025-01-14'),

-- Toamasina (Atsinanana)
(2, 1, 'Riz',                      12000.00, 'kg',    'Entrepôt central inondé', '2025-02-01'),
(2, 2, 'Tôle ondulée 3m',          450.00,   'pièce', 'Toitures emportées par cyclone', '2025-02-02'),
(2, 2, 'Clous 3 pouces',           180.00,   'kg',    NULL, '2025-02-03'),

-- Mahajanga (Boeny)
(3, 1, 'Haricots secs',            3800.00, 'kg',     'Complément alimentaire', '2025-01-20'),
(3, 3, 'Aide monétaire',           28000000.00, 'Ar', 'Reconstruction habitat', '2025-01-22'),

-- Antsiranana (Diana)
(4, 2, 'Bois de charpente 4m',     120.00,  'pièce',  'Cyclone Belal impact fort', '2025-01-10'),
(4, 1, 'Eau potable (bidons 20L)', 950.00,  'pièce',  'Accès eau potable coupé', '2025-01-11'),

-- Toliara (Atsimo-Andrefana)
(5, 1, 'Riz',                      15000.00, 'kg',    'Sécheresse + cyclone', '2025-02-05'),
(5, 3, 'Aide financière',          65000000.00, 'Ar', 'Achat vivres et semences', '2025-02-06');

-- 5. Dons reçus (stock disponible au moment du test)
INSERT INTO dons (type_id, description, quantite, unite, date_reception, source, remarque, created_at) VALUES
(1, 'Riz blanc 50kg/sac – don entreprise',  500.00,  'sac 50kg', '2025-02-01', 'Entreprise SOCITA', 'Don important pour Toamasina', '2025-02-02'),
(1, 'Riz – collecte église',                 4200.00, 'kg',      '2025-01-28', 'Communauté chrétienne Tana', NULL, '2025-01-29'),
(1, 'Huile 5L bidons',                       680.00,  'bidon',   '2025-02-03', 'Particuliers', NULL, '2025-02-04'),
(2, 'Tôle ondulée 3m – don ONG',             320.00,  'pièce',   '2025-02-02', 'ONG Habitat pour Tous', 'Livraison prévue semaine 7', '2025-02-03'),
(2, 'Clous assortis',                        450.00,  'kg',      '2025-01-30', ' quincaillerie partenaire', NULL, '2025-01-31'),
(3, 'Virement bancaire secours',             75000000.00, 'Ar',  '2025-02-04', 'Diaspora', 'Fonds d’urgence cyclone', '2025-02-05'),
(3, 'Collecte SMS – Orange Money',           32000000.00, 'Ar',  '2025-01-25', 'Campagne nationale', NULL, '2025-01-26');

-- 6. Quelques distributions déjà effectuées (exemples)
-- On distribue une partie des dons vers des besoins existants
INSERT INTO distributions (besoin_id, don_id, quantite, remarque, created_by, date_distribution) VALUES
(1, 2, 2000.00,  'Distribution première vague', 1, '2025-02-05 09:30:00'),   -- 2000 kg riz vers Antananarivo
(1, 1,  250.00,  'Sac de 50kg × 5',             1, '2025-02-06 14:15:00'),   -- 250 sacs = 12 500 kg (mais on limite à 250 sacs ici)
(4, 2, 1800.00,  'Priorité Toamasina',          1, '2025-02-07 10:45:00'),   -- riz vers Toamasina
(5, 4,  180.00,  '150 tôles livrées',           1, '2025-02-08 11:20:00'),   -- tôles vers Toamasina
(3, 6, 15000000.00, 'Virement 15M Ar',       1, '2025-02-09 15:00:00');   -- argent vers Antananarivo