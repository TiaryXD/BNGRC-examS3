CREATE DATABASE BNGRC;
USE BNGRC;
CREATE TABLE admin (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(255) NOT NULL,
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
    created_by      INT UNSIGNED DEFAULT NULL,
    date_distribution      DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (besoin_id)  REFERENCES besoins(id)  ON DELETE CASCADE,
    FOREIGN KEY (don_id)     REFERENCES dons(id)     ON DELETE RESTRICT,
    FOREIGN KEY (created_by) REFERENCES admin(id)    ON DELETE SET NULL,

);
