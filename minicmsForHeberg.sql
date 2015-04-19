-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Sam 18 Avril 2015 à 18:45
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
-- Contenu de la table `contenir`
--

INSERT INTO `contenir` (`idPage`, `idLink`) VALUES
(10, 3),
(10, 4),
(7, 2),
(7, 3),
(3, 3),
(3, 4),
(3, 5),
(4, 2),
(4, 4),
(4, 5),
(2, 4),
(2, 6),
(6, 2),
(6, 3);

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
-- Contenu de la table `footer`
--

INSERT INTO `footer` (`id`, `contenu`, `logo`, `published`) VALUES
(1, '<p><strong>Bas de page </strong>!<strong><br /></strong></p>', 'xsaqcsqend.jpg', 1),
(2, '<p>Page du Bas</p>', '', 1);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `header`
--

INSERT INTO `header` (`id`, `contenu`, `logo`, `published`) VALUES
(1, 'Principaux points d''intérêt', 'dezsfe.jpg', 1),
(2, 'Super !', '', 1),
(3, 'Tu as trouv&eacute;\npour ton PHP dans la box', '', 1);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Contenu de la table `links`
--

INSERT INTO `links` (`id`, `url`, `description`, `published`) VALUES
(2, 'lien2.html', 'lien2', 0),
(3, 'lien3.html', 'lien3', 1),
(4, 'lien5.html', 'lien5', 0),
(5, 'lien6.html', 'lien6', 1),
(6, 'lien7.html', 'lien7', 1);

-- --------------------------------------------------------

--
-- Structure de la table `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `name` varchar(255) NOT NULL,
  `contenu` text,
  `url` varchar(255) NOT NULL,
  `published` tinyint(1) NOT NULL,
  `idHeader` int(11) DEFAULT NULL,
  `idFooter` int(11) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `orderDisplay` int(11) DEFAULT NULL,
  `hashtable` text,
  PRIMARY KEY (`id`),
  KEY `idHeader` (`idHeader`),
  KEY `idFooter` (`idFooter`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Contenu de la table `pages`
--

INSERT INTO `pages` (`id`, `title`, `name`, `contenu`, `url`, `published`, `idHeader`, `idFooter`, `image`, `orderDisplay`, `hashtable`) VALUES
(2, 'Me Contacter', 'Contact', '<p>Mon tel : 066 6 6 6 665</p><p>Mon adresse</p>', 'contact.html', 1, 1, 1, '', 6, NULL),
(3, 'itty', 'Graph', '<p>Jordan Orbit</p>', 'painting.html', 1, 3, 2, 'SAM_0775.JPG', 3, '[]'),
(4, 'Voyage - Espagne', 'Espagne', '<p>Principalement connu pour ses boites.</p>', 'Spain.html', 1, 2, 1, '', 2, '[]'),
(6, 'Voyage - Belgique', 'Belgique', '<p>Principalement reconnu pour sa bi&egrave;re</p>', 'Belgium.html', 1, 1, 1, 'SAM_0776.JPG', 1, '[]'),
(7, 'Bar', 'Activités', '', 'bar.html', 1, 1, 1, '', 5, '[]'),
(10, 'King kong', 'Jungle', '', 'junng.html', 1, 2, 1, 'SAM_0778.JPG', 4, '[]');

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
  ADD CONSTRAINT `pages_ibfk_1` FOREIGN KEY (`idHeader`) REFERENCES `header` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `pages_ibfk_2` FOREIGN KEY (`idFooter`) REFERENCES `footer` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
