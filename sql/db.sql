-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Jeu 08 Mai 2014 à 00:23
-- Version du serveur: 5.6.17
-- Version de PHP: 5.4.27-1~dotdeb.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `stagestx`
--

-- --------------------------------------------------------

--
-- Structure de la table `stages`
--

DROP TABLE IF EXISTS `stages`;
CREATE TABLE IF NOT EXISTS `stages` (
  `idStage` int(11) NOT NULL AUTO_INCREMENT,
  `titreStage` varchar(255) NOT NULL,
  `nomEtudiant` varchar(255) NOT NULL,
  `nomEntreprise` varchar(255) NOT NULL,
  `pays` varchar(255) NOT NULL,
  `uv` enum('TN09','TN10') NOT NULL,
  `descriptionComplete` text NOT NULL,
  PRIMARY KEY (`idStage`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=368 ;

-- --------------------------------------------------------

--
-- Structure de la table `votes`
--

DROP TABLE IF EXISTS `votes`;
CREATE TABLE IF NOT EXISTS `votes` (
  `login` varchar(255) NOT NULL,
  `stage` int(11) NOT NULL,
  `note` tinyint(3) NOT NULL,
  PRIMARY KEY (`login`,`stage`),
  KEY `fk_stage` (`stage`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `votes`
--
ALTER TABLE `votes`
  ADD CONSTRAINT `fk_stage` FOREIGN KEY (`stage`) REFERENCES `stages` (`idStage`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
