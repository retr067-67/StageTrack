-- app_esup database schema for StageTrack / Application ESUP

CREATE DATABASE IF NOT EXISTS app_esup DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE app_esup;

CREATE TABLE roles (
    id_role INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50) NOT NULL UNIQUE,
    description VARCHAR(255)
);

CREATE TABLE users (
    id_user INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role_id INT NOT NULL,
    id_etudiant INT NULL,
    nom VARCHAR(100) NULL,
    prenom VARCHAR(100) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id_role)
);

CREATE TABLE Entreprise (
    id_entreprise INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    adresse VARCHAR(255),
    telephone VARCHAR(20),
    email VARCHAR(100)
);

CREATE TABLE Maitre_de_stage (
    id_maitre INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    id_entreprise INT NOT NULL,
    FOREIGN KEY (id_entreprise) REFERENCES Entreprise(id_entreprise)
);

CREATE TABLE Etudiant (
    id_etudiant INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    promotion VARCHAR(50),
    matricule VARCHAR(50),
    telephone VARCHAR(20),
    date_naissance DATE,
    lieu_naissance VARCHAR(150),
    adresse_postale VARCHAR(255),
    groupe_td VARCHAR(50),
    groupe_tp VARCHAR(50)
);

CREATE TABLE Offre_stage (
    id_offre INT PRIMARY KEY AUTO_INCREMENT,
    titre VARCHAR(150) NOT NULL,
    description TEXT,
    id_entreprise INT NOT NULL,
    status VARCHAR(50),
    competences VARCHAR(255),
    duree VARCHAR(100),
    lieu VARCHAR(150),
    date_debut DATE,
    date_fin DATE,
    remuneration VARCHAR(100),
    promotion VARCHAR(100),
    annee VARCHAR(50),
    FOREIGN KEY (id_entreprise) REFERENCES Entreprise(id_entreprise)
);

CREATE TABLE Convention (
    id_convention INT PRIMARY KEY AUTO_INCREMENT,
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    status VARCHAR(50),
    id_etudiant INT NOT NULL,
    id_entreprise INT NOT NULL,
    id_maitre INT NOT NULL,
    id_offre INT,
    numero_convention VARCHAR(100),
    FOREIGN KEY (id_etudiant) REFERENCES Etudiant(id_etudiant),
    FOREIGN KEY (id_entreprise) REFERENCES Entreprise(id_entreprise),
    FOREIGN KEY (id_maitre) REFERENCES Maitre_de_stage(id_maitre),
    FOREIGN KEY (id_offre) REFERENCES Offre_stage(id_offre)
);

CREATE TABLE Visite_Suivi (
    id_visite INT PRIMARY KEY AUTO_INCREMENT,
    date_visite DATE NOT NULL,
    commentaires TEXT,
    id_etudiant INT NOT NULL,
    id_maitre INT NOT NULL,
    id_convention INT NOT NULL,
    FOREIGN KEY (id_etudiant) REFERENCES Etudiant(id_etudiant),
    FOREIGN KEY (id_maitre) REFERENCES Maitre_de_stage(id_maitre),
    FOREIGN KEY (id_convention) REFERENCES Convention(id_convention)
);

CREATE TABLE Evaluation (
    id_evaluation INT PRIMARY KEY AUTO_INCREMENT,
    note DECIMAL(4,2),
    date DATE NOT NULL,
    commentaire TEXT,
    id_convention INT NOT NULL,
    FOREIGN KEY (id_convention) REFERENCES Convention(id_convention)
);

CREATE TABLE Recherche_stage (
    id_recherche INT PRIMARY KEY AUTO_INCREMENT,
    id_etudiant INT NOT NULL,
    entreprise_contactee VARCHAR(255),
    offre_consultee VARCHAR(255),
    date_contact DATE,
    statut VARCHAR(100),
    commentaire TEXT,
    FOREIGN KEY (id_etudiant) REFERENCES Etudiant(id_etudiant)
);

CREATE TABLE Probleme (
    id_probleme INT PRIMARY KEY AUTO_INCREMENT,
    description TEXT NOT NULL,
    date_signalement DATE NOT NULL
);

INSERT INTO roles (nom, description) VALUES
('etudiant', 'Compte étudiant'),
('responsable', 'Responsable des stages'),
('admin', 'Administrateur du site');

INSERT INTO users (email, password, role_id, nom, prenom) VALUES
('responsable@example.com', SHA2('Responsable123!', 256), 2, 'Responsable', 'Stage');

INSERT INTO Entreprise (nom, adresse, telephone, email) VALUES
('Alizé Tech', '12 rue de la Gare, 77100 Meaux', '01 23 45 67 89', 'contact@alizetech.fr');

INSERT INTO Maitre_de_stage (nom, prenom, id_entreprise) VALUES
('Dupont', 'Sophie', 1);

INSERT INTO Offre_stage (titre, description, id_entreprise, status, competences, duree, lieu, date_debut, date_fin, remuneration, promotion, annee) VALUES
('Développeur Web Junior', 'Stage de développement web front-end et back-end.', 1, 'Disponible', 'HTML, CSS, PHP, MySQL', '3 mois', 'Meaux', '2026-06-15', '2026-09-15', 'Gratifié', 'MMI3', '2026');
