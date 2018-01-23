-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Ven 12 Janvier 2018 à 23:59
-- Version du serveur :  10.1.19-MariaDB
-- Version de PHP :  5.5.38

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de données :  `tp1_php_imta`
--
CREATE DATABASE IF NOT EXISTS `tp1_php_imta` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `tp1_php_imta`;

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

DROP TABLE IF EXISTS `categorie`;
CREATE TABLE `categorie` (
  `nom_categorie` varchar(20) NOT NULL,
  `date_ajout` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `statut` varchar(10) NOT NULL DEFAULT 'YES'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `categorie`
--

INSERT INTO `categorie` (`nom_categorie`, `date_ajout`, `statut`) VALUES
('enseignant', '2017-12-15 19:26:54', 'YES'),
('etudiant', '2017-12-15 19:26:54', 'YES');

-- --------------------------------------------------------

--
-- Structure de la table `pages_persos`
--

DROP TABLE IF EXISTS `pages_persos`;
CREATE TABLE `pages_persos` (
  `titre` varchar(20) NOT NULL,
  `icone` varchar(30) NOT NULL DEFAULT 'link',
  `date_ajout` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `statut` varchar(10) NOT NULL DEFAULT 'YES'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `pages_persos`
--

INSERT INTO `pages_persos` (`titre`, `icone`, `date_ajout`, `statut`) VALUES
('Facebook', 'facebook-official', '2017-12-15 19:18:51', 'YES'),
('Linkedin', 'linkedin', '2017-12-15 19:18:51', 'YES'),
('Page perso', 'link', '2017-12-15 19:18:51', 'YES'),
('Site WEB', 'globe', '2017-12-15 19:18:51', 'YES'),
('Twitter', 'twitter', '2017-12-15 19:18:51', 'YES');

-- --------------------------------------------------------

--
-- Structure de la table `personnes`
--

DROP TABLE IF EXISTS `personnes`;
CREATE TABLE `personnes` (
  `id` int(11) NOT NULL,
  `nom` varchar(25) NOT NULL,
  `prenom` varchar(200) NOT NULL,
  `age` int(11) NOT NULL DEFAULT '10',
  `sexe` varchar(10) NOT NULL DEFAULT 'homme',
  `categorie` varchar(20) NOT NULL DEFAULT 'etudiant',
  `email` varchar(255) NOT NULL,
  `date_inscription` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `chemin_image` text NOT NULL,
  `statut` varchar(10) NOT NULL DEFAULT 'YES'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `personnes`
--

INSERT INTO `personnes` (`id`, `nom`, `prenom`, `age`, `sexe`, `categorie`, `email`, `date_inscription`, `chemin_image`, `statut`) VALUES
(1, 'KETREMINDIE', 'Alvihn', 27, 'homme', 'etudiant', 'alvihn.ketremindie@telecom-bretagne.eu', '2017-12-20 01:47:43', 'photo_profils/20171220014743_1500_60636600.jpg', 'YES'),
(2, 'KETREMINDIE', 'Junior', 27, 'homme', 'etudiant', 'junior.ketremindie@telecom-bretagne.eu', '2017-12-20 02:05:05', 'photo_profils/20171220020505_4542_18329400.jpg', 'YES'),
(3, 'KETREMINDIE', 'Louis', 27, 'homme', 'enseignant', 'louis.ketremindie@telecom-bretagne.eu', '2017-12-20 02:06:24', 'photo_profils/20171220020624_1148_58384000.jpg', 'YES'),
(4, 'TUO', 'Allassane Kounikarga', 25, 'homme', 'etudiant', 'allassanekounikarga.tuo@telecom-bretagne.eu', '2017-12-20 20:25:29', '', 'YES'),
(5, '515dd51d', 'fzdzea', 58, 'homme', 'enseignant', 'fzdzea.515dd51d@telecom-bretagne.eu', '2017-12-20 22:02:48', '', 'NO'),
(6, 'N''ZI', 'Koffi Yannick', 26, 'femme', 'etudiant', 'koffiyannick.nzi@telecom-bretagne.eu', '2017-12-22 21:46:23', '', 'YES'),
(7, 'KETREMINDIE', 'Manuella', 19, 'femme', 'etudiant', 'manuella.ketremindie@telecom-bretagne.eu', '2017-12-22 22:09:18', '', 'YES'),
(10, 'N''ziGoalchangte', 'Koffi ', 29, 'homme', 'etudiant', 'koffi.nzigoalchangte@telecom-bretagne.eu', '2017-12-22 22:57:08', '', 'NO'),
(11, 'KETREMINDIE', 'Grace', 19, 'femme', 'etudiant', 'grace.ketremindie@telecom-bretagne.eu', '2017-12-22 23:04:06', '', 'YES'),
(12, 'N''djékié', 'Sanhou Stanislas', 29, 'homme', 'etudiant', 'sanhoustanislas.ndjekie@telecom-bretagne.eu', '2017-12-23 03:55:14', '', 'YES'),
(13, 'CABA', 'Mohamed Moustapha', 28, 'homme', 'etudiant', 'mohamedmoustapha.caba@telecom-bretagne.eu', '2017-12-23 03:56:13', '', 'YES');

-- --------------------------------------------------------

--
-- Structure de la table `statut`
--

DROP TABLE IF EXISTS `statut`;
CREATE TABLE `statut` (
  `valeur_statut` varchar(10) NOT NULL,
  `date_ajout` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `statut`
--

INSERT INTO `statut` (`valeur_statut`, `date_ajout`, `description`) VALUES
('NO', '2017-12-15 19:35:57', 'Valeur indiquant que l''element a ete desactive'),
('YES', '2017-12-15 19:35:57', 'Valeur indiquant que l''element est actif');

-- --------------------------------------------------------

--
-- Structure de la table `url_pages_persos`
--

DROP TABLE IF EXISTS `url_pages_persos`;
CREATE TABLE `url_pages_persos` (
  `id_personne` int(11) NOT NULL,
  `titres_page` varchar(20) NOT NULL,
  `url` text NOT NULL,
  `date_ajout` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `statut` varchar(10) NOT NULL DEFAULT 'YES'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `url_pages_persos`
--

INSERT INTO `url_pages_persos` (`id_personne`, `titres_page`, `url`, `date_ajout`, `statut`) VALUES
(1, 'Facebook', 'https://www.w3schools.com/howto/tryit.asp?filename=tryhow_css_danger', '2017-12-20 01:47:43', 'YES'),
(1, 'Linkedin', 'https://www.w3schools.com/howto/tryit.asp?filename=tryhow_css_danger', '2017-12-20 01:47:43', 'YES'),
(1, 'Page perso', 'https://www.w3schools.com/howto/tryit.asp?filename=tryhow_css_danger', '2017-12-20 01:47:43', 'YES'),
(1, 'Site WEB', 'https://www.w3schools.com/howto/tryit.asp?filename=tryhow_css_danger', '2017-12-20 01:47:43', 'YES'),
(1, 'Twitter', 'https://www.w3schools.com/howto/tryit.asp?filename=tryhow_css_danger', '2017-12-20 01:47:43', 'YES'),
(3, 'Facebook', 'https://forum.alsacreations.com/topic-4-43064-1-DeuxDIVquisechevauchent.html', '2017-12-20 02:06:24', 'YES'),
(3, 'Linkedin', 'https://forum.alsacreations.com/topic-4-43064-1-DeuxDIVquisechevauchent.html', '2017-12-20 02:06:24', 'YES'),
(6, 'Facebook', '', '2017-12-23 04:07:53', 'YES'),
(6, 'Linkedin', '', '2017-12-23 04:07:53', 'YES'),
(6, 'Page perso', '', '2017-12-23 04:07:53', 'YES'),
(6, 'Site WEB', '', '2017-12-23 04:07:53', 'YES'),
(6, 'Twitter', '', '2017-12-23 04:07:53', 'YES'),
(7, 'Facebook', '', '2017-12-23 03:37:11', 'YES'),
(7, 'Linkedin', '', '2017-12-23 03:37:11', 'YES'),
(7, 'Page perso', '', '2017-12-23 03:37:12', 'YES'),
(7, 'Site WEB', '', '2017-12-23 03:37:13', 'YES'),
(7, 'Twitter', '', '2017-12-23 03:37:13', 'YES'),
(10, 'Facebook', 'sssdd', '2017-12-22 23:23:37', 'YES'),
(10, 'Linkedin', 'dfffg', '2017-12-22 23:23:37', 'YES'),
(10, 'Page perso', 'sdfffd', '2017-12-22 23:23:37', 'YES'),
(10, 'Site WEB', 'dddff', '2017-12-22 23:23:37', 'YES'),
(11, 'Facebook', '', '2017-12-23 03:52:55', 'YES'),
(11, 'Linkedin', '', '2017-12-23 03:52:55', 'YES'),
(11, 'Page perso', '', '2017-12-23 03:52:55', 'YES'),
(11, 'Site WEB', '', '2017-12-23 03:52:55', 'YES'),
(11, 'Twitter', '', '2017-12-23 03:52:55', 'YES'),
(12, 'Facebook', '', '2017-12-23 03:55:15', 'YES'),
(12, 'Linkedin', '', '2017-12-23 03:55:15', 'YES'),
(12, 'Page perso', '', '2017-12-23 03:55:15', 'YES'),
(12, 'Site WEB', '', '2017-12-23 03:55:15', 'YES'),
(12, 'Twitter', '', '2017-12-23 03:55:15', 'YES'),
(13, 'Facebook', '', '2017-12-23 03:56:13', 'YES'),
(13, 'Linkedin', '', '2017-12-23 03:56:13', 'YES'),
(13, 'Page perso', '', '2017-12-23 03:56:13', 'YES'),
(13, 'Site WEB', 'ddfff', '2017-12-23 03:56:14', 'YES'),
(13, 'Twitter', '', '2017-12-23 03:56:14', 'YES');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`nom_categorie`),
  ADD KEY `date_ajout` (`date_ajout`),
  ADD KEY `statut` (`statut`);

--
-- Index pour la table `pages_persos`
--
ALTER TABLE `pages_persos`
  ADD PRIMARY KEY (`titre`),
  ADD KEY `icone` (`icone`),
  ADD KEY `statut` (`statut`),
  ADD KEY `date_ajout` (`date_ajout`);

--
-- Index pour la table `personnes`
--
ALTER TABLE `personnes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code_pers` (`email`,`categorie`),
  ADD KEY `categorie` (`categorie`),
  ADD KEY `statut` (`statut`),
  ADD KEY `date_inscription` (`date_inscription`);

--
-- Index pour la table `statut`
--
ALTER TABLE `statut`
  ADD PRIMARY KEY (`valeur_statut`),
  ADD KEY `date_ajout` (`date_ajout`);

--
-- Index pour la table `url_pages_persos`
--
ALTER TABLE `url_pages_persos`
  ADD PRIMARY KEY (`id_personne`,`titres_page`),
  ADD KEY `id_personnes` (`id_personne`),
  ADD KEY `id_pages_persos` (`titres_page`),
  ADD KEY `statut` (`statut`),
  ADD KEY `date_ajout` (`date_ajout`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `personnes`
--
ALTER TABLE `personnes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `categorie`
--
ALTER TABLE `categorie`
  ADD CONSTRAINT `fk_categorie_statut` FOREIGN KEY (`statut`) REFERENCES `statut` (`valeur_statut`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `pages_persos`
--
ALTER TABLE `pages_persos`
  ADD CONSTRAINT `fk_page_statut` FOREIGN KEY (`statut`) REFERENCES `statut` (`valeur_statut`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `personnes`
--
ALTER TABLE `personnes`
  ADD CONSTRAINT `fk_personne_categorie` FOREIGN KEY (`categorie`) REFERENCES `categorie` (`nom_categorie`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_personne_statut` FOREIGN KEY (`statut`) REFERENCES `statut` (`valeur_statut`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `url_pages_persos`
--
ALTER TABLE `url_pages_persos`
  ADD CONSTRAINT `fk_url_page` FOREIGN KEY (`titres_page`) REFERENCES `pages_persos` (`titre`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_url_personne` FOREIGN KEY (`id_personne`) REFERENCES `personnes` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_url_statut` FOREIGN KEY (`statut`) REFERENCES `statut` (`valeur_statut`) ON UPDATE CASCADE;
