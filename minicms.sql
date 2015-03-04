-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Jeu 05 Mars 2015 à 00:17
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `minicms`
--
CREATE DATABASE IF NOT EXISTS `minicms` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `minicms`;

-- --------------------------------------------------------

--
-- Structure de la table `contenir`
--

CREATE TABLE IF NOT EXISTS `contenir` (
  `idPage` int(11) NOT NULL,
  `idLink` int(11) DEFAULT NULL,
  KEY `idPage` (`idPage`),
  KEY `idLink` (`idLink`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Vider la table avant d'insérer `contenir`
--

TRUNCATE TABLE `contenir`;
--
-- Contenu de la table `contenir`
--

INSERT INTO `contenir` (`idPage`, `idLink`) VALUES
(3, 4),
(4, 2),
(4, 4),
(1, 3),
(5, 2),
(5, 3),
(5, 4),
(2, 2);

-- --------------------------------------------------------

--
-- Structure de la table `footer`
--

CREATE TABLE IF NOT EXISTS `footer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contenu` text NOT NULL,
  `logo` text,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Vider la table avant d'insérer `footer`
--

TRUNCATE TABLE `footer`;
--
-- Contenu de la table `footer`
--

INSERT INTO `footer` (`id`, `contenu`, `logo`, `published`) VALUES
(1, '<p>Bas de page</>', 'xsaqcsqend.jpg', 0),
(2, '<p>Au revoir !</p>', NULL, 0);

-- --------------------------------------------------------

--
-- Structure de la table `header`
--

CREATE TABLE IF NOT EXISTS `header` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contenu` text NOT NULL,
  `logo` text,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Vider la table avant d'insérer `header`
--

TRUNCATE TABLE `header`;
--
-- Contenu de la table `header`
--

INSERT INTO `header` (`id`, `contenu`, `logo`, `published`) VALUES
(1, '<p>Bienvenue</p>', 'dezsfe.jpg', 1);

-- --------------------------------------------------------

--
-- Structure de la table `links`
--

CREATE TABLE IF NOT EXISTS `links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` text NOT NULL,
  `description` text,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Vider la table avant d'insérer `links`
--

TRUNCATE TABLE `links`;
--
-- Contenu de la table `links`
--

INSERT INTO `links` (`id`, `url`, `description`, `published`) VALUES
(2, 'lien2.html', 'lien2', 1),
(3, 'lien3.html', 'lien3', 1),
(4, 'lien5.html', 'lien5', 0);

-- --------------------------------------------------------

--
-- Structure de la table `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `name` varchar(255) NOT NULL,
  `menu` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `published` tinyint(1) NOT NULL,
  `idHeader` int(11) DEFAULT NULL,
  `idFooter` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idHeader` (`idHeader`),
  KEY `idFooter` (`idFooter`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Vider la table avant d'insérer `pages`
--

TRUNCATE TABLE `pages`;
--
-- Contenu de la table `pages`
--

INSERT INTO `pages` (`id`, `title`, `name`, `menu`, `url`, `published`, `idHeader`, `idFooter`) VALUES
(1, 'Page d''Accueil', 'Home', '', 'home.html', 1, NULL, NULL),
(2, 'Me Contacter', 'Contact', '', 'contact.html', 1, 1, 2),
(3, 'Mes expériences', 'Experience', '', 'experience.html', 1, NULL, NULL),
(4, 'Nimp', 'Bails', '', 'bailnimp.html', 1, NULL, NULL),
(5, 'Autre', 'Autres', '', 'other.html', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Vider la table avant d'insérer `users`
--

TRUNCATE TABLE `users`;
--
-- Contenu de la table `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `email`) VALUES
(1, 'user', 'ee11cbb19052e40b07aac0ca060c23ee', 'user@mail.Com'),
(2, 'navec', '81dc9bdb52d04dc20036dbd8313ed055', 'navecbatchi@gmail.com'),
(3, 'yann', '1c5442c0461e5186126aaba26edd6857', 'yanndu92971@gmail.com'),
(4, 'kev', '13b5bfe96f3e2fe411c9f66f4a582adf', 'martinkevin2606@gmail.com');

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `contenir`
--
ALTER TABLE `contenir`
  ADD CONSTRAINT `contenir_ibfk_2` FOREIGN KEY (`idLink`) REFERENCES `links` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `contenir_ibfk_3` FOREIGN KEY (`idPage`) REFERENCES `pages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `pages`
--
ALTER TABLE `pages`
  ADD CONSTRAINT `pages_ibfk_2` FOREIGN KEY (`idFooter`) REFERENCES `footer` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `pages_ibfk_1` FOREIGN KEY (`idHeader`) REFERENCES `header` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
