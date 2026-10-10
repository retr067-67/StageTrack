-- Données MMI à ajouter à db.sql (run après la création des tables)
-- ===================================================================

-- ENTREPRISES MMI
INSERT INTO Entreprise (nom, adresse, telephone, email) VALUES
('YouTube',        '901 Cherry Ave, San Bruno, CA (bureau Paris)',  '+33 1 70 39 00 00', 'partenariats@youtube.fr'),
('Webedia',        '2 rue Paul Vaillant-Couturier, 92300 Levallois','01 41 34 60 00',    'stages@webedia.fr'),
('Brut.',          '5 passage de la Bonne Graine, 75011 Paris',     '01 80 48 96 00',    'contact@brut.media'),
('TF1',            '1 quai du Point du Jour, 92100 Boulogne',       '01 41 41 12 34',    'recrutement@tf1.fr'),
('Canal+',         '1 place du Spectacle, 92130 Issy-les-Moulineaux','01 44 25 10 00',   'stages@canalplus.com'),
('Ubisoft',        '28 rue Armand Carrel, 93100 Montreuil',         '01 48 18 50 00',    'recrutement@ubisoft.com'),
('Sony Music',     '9 rue Scribe, 75009 Paris',                     '01 44 94 04 94',    'stages@sonymusic.fr'),
('Twitch',         '350 Bush St, San Francisco (bureau Paris)',      '+33 1 80 75 00 00', 'creator@twitch.tv'),
('Cartoon Network','Turner Broadcasting, 1 rue de Berri, 75008 Paris','01 53 77 14 14',  'stages@cartoonnetwork.fr');

-- OFFRES MMI (8 nouvelles)
INSERT INTO Offre_stage (titre, description, id_entreprise, status, competences, duree, lieu, date_debut, date_fin, remuneration, promotion, annee) VALUES

('Chargé(e) de production vidéo',
 'Participez à la création et au montage de contenus vidéo pour les chaînes YouTube partenaires. Vous intégrerez l'équipe éditoriale et contribuerez à la stratégie de diffusion.',
 (SELECT id_entreprise FROM Entreprise WHERE nom='YouTube' LIMIT 1),
 'Disponible', 'Montage vidéo, Premiere Pro, After Effects, Storytelling', '4 mois',
 'Paris (Remote possible)', '2026-09-01', '2026-12-31', '600€/mois', 'MMI2 / MMI3', '2026'),

('Stage Social Media Manager',
 'Gérez les réseaux sociaux de marques médias (Instagram, TikTok, X). Vous créerez des contenus, analyserez les performances et proposerez des stratégies éditoriales.',
 (SELECT id_entreprise FROM Entreprise WHERE nom='Webedia' LIMIT 1),
 'Disponible', 'Community management, Canva, Analytics, Copywriting', '3 mois',
 'Levallois-Perret', '2026-06-01', '2026-08-31', '580€/mois', 'MMI2', '2026'),

('Assistant(e) réalisation — Brut.',
 'Rejoignez les équipes de Brut. pour participer à la production de vidéos courtes à fort impact social. Tournage, écriture, montage : vous serez au coeur du processus.',
 (SELECT id_entreprise FROM Entreprise WHERE nom='Brut.' LIMIT 1),
 'Disponible', 'Tournage smartphone, Montage, Écriture journalistique', '2 mois',
 'Paris 11e', '2026-07-01', '2026-08-31', '550€/mois', 'MMI1 / MMI2', '2026'),

('Stage Développeur(se) Web & Multimédia',
 'Participez au développement de plateformes multimédia interactives pour TF1 Group. HTML, CSS, JS et intégration CMS au programme.',
 (SELECT id_entreprise FROM Entreprise WHERE nom='TF1' LIMIT 1),
 'Disponible', 'HTML/CSS, JavaScript, React, CMS, Git', '6 mois',
 'Boulogne-Billancourt', '2026-09-01', '2027-02-28', '700€/mois', 'MMI3', '2026'),

('UX/UI Designer — Application streaming',
 'Intégrez l'équipe design de Canal+ pour repenser les interfaces de ses applications streaming. Vous participerez aux phases de recherche utilisateur, wireframing et prototypage.',
 (SELECT id_entreprise FROM Entreprise WHERE nom='Canal+' LIMIT 1),
 'Disponible', 'Figma, UX Research, Prototypage, Design System', '5 mois',
 'Issy-les-Moulineaux', '2026-09-01', '2027-01-31', '750€/mois', 'MMI3', '2026'),

('Stage Motion Design & Animation',
 'Créez des animations 2D/3D pour les campagnes marketing et jeux vidéo d'Ubisoft. Vous travaillerez avec les équipes créatives sur des projets AAA.',
 (SELECT id_entreprise FROM Entreprise WHERE nom='Ubisoft' LIMIT 1),
 'Disponible', 'After Effects, Cinema 4D, Illustrator, Motion design', '4 mois',
 'Montreuil', '2026-09-15', '2027-01-15', '680€/mois', 'MMI2 / MMI3', '2026'),

('Assistant(e) Marketing Digital — Label',
 'Participez aux actions marketing d'un label Sony Music : gestion des réseaux sociaux d'artistes, analyse de données streaming, campagnes digitales.',
 (SELECT id_entreprise FROM Entreprise WHERE nom='Sony Music' LIMIT 1),
 'Disponible', 'Marketing digital, Spotify for Artists, Meta Ads, Excel', '3 mois',
 'Paris 9e', '2026-06-15', '2026-09-15', '600€/mois', 'MMI2', '2026'),

('Stage Content Creator — Gaming',
 'Produisez des contenus originaux pour les chaînes Twitch et les réseaux sociaux du secteur gaming. Créativité, culture gaming et sens de la communauté requis.',
 (SELECT id_entreprise FROM Entreprise WHERE nom='Twitch' LIMIT 1),
 'Disponible', 'OBS Studio, Montage vidéo, Community management, Gaming', '3 mois',
 'Paris (Hybride)', '2026-07-01', '2026-09-30', '560€/mois', 'MMI1 / MMI2', '2026');

-- MAITRES DE STAGE pour les nouvelles entreprises
INSERT INTO Maitre_de_stage (nom, prenom, id_entreprise) VALUES
('Leblanc',  'Marie',    (SELECT id_entreprise FROM Entreprise WHERE nom='YouTube' LIMIT 1)),
('Rousseau', 'Thomas',   (SELECT id_entreprise FROM Entreprise WHERE nom='Webedia' LIMIT 1)),
('Ferrand',  'Julie',    (SELECT id_entreprise FROM Entreprise WHERE nom='Brut.' LIMIT 1)),
('Moreau',   'Antoine',  (SELECT id_entreprise FROM Entreprise WHERE nom='TF1' LIMIT 1)),
('Petit',    'Sarah',    (SELECT id_entreprise FROM Entreprise WHERE nom='Canal+' LIMIT 1)),
('Garcia',   'Lucas',    (SELECT id_entreprise FROM Entreprise WHERE nom='Ubisoft' LIMIT 1)),
('Bernard',  'Camille',  (SELECT id_entreprise FROM Entreprise WHERE nom='Sony Music' LIMIT 1)),
('Leroy',    'Kevin',    (SELECT id_entreprise FROM Entreprise WHERE nom='Twitch' LIMIT 1));
