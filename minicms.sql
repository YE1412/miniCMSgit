-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Mar 24 Février 2015 à 19:47
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
  `idLink` int(11) NOT NULL,
  KEY `idPage` (`idPage`),
  KEY `idLink` (`idLink`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `contenir`
--

INSERT INTO `contenir` (`idPage`, `idLink`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `footer`
--

CREATE TABLE IF NOT EXISTS `footer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contenu` text NOT NULL,
  `logo` text,
  PRIMARY KEY (`id`),
  KEY `c` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `footer`
--

INSERT INTO `footer` (`id`, `contenu`, `logo`) VALUES
(1, '<p>Bas de page</>', 'xsaqcsqend.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `header`
--

CREATE TABLE IF NOT EXISTS `header` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contenu` text NOT NULL,
  `logo` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `header`
--

INSERT INTO `header` (`id`, `contenu`, `logo`) VALUES
(1, '<p>Bienvenue</p>', 'dezsfe.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `links`
--

CREATE TABLE IF NOT EXISTS `links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` text NOT NULL,
  `description` text,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `idHeader` int(11) DEFAULT NULL,
  `idFooter` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idHeader` (`idHeader`),
  UNIQUE KEY `idFooter` (`idFooter`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `links`
--

INSERT INTO `links` (`id`, `url`, `description`, `published`, `idHeader`, `idFooter`) VALUES
(1, 'fezfz.html', 'Lien de la page d''accueil vers la page de contact', 1, NULL, NULL);

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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `pages`
--

INSERT INTO `pages` (`id`, `title`, `name`, `menu`, `url`, `published`) VALUES
(1, 'Premiere Page', 'home', 'houjhj', 'cfyhtg.html', 1),
(2, 'Deuxième Page', 'Contact', 'dzaed', 'fedazef.html', 1);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

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
  ADD CONSTRAINT `contenir_ibfk_1` FOREIGN KEY (`idPage`) REFERENCES `pages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `contenir_ibfk_2` FOREIGN KEY (`idLink`) REFERENCES `links` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `links`
--
ALTER TABLE `links`
  ADD CONSTRAINT `links_ibfk_2` FOREIGN KEY (`idHeader`) REFERENCES `header` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `links_ibfk_3` FOREIGN KEY (`idFooter`) REFERENCES `footer` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
