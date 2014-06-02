-- phpMyAdmin SQL Dump
-- version 4.2.2
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Lun 02 Juin 2014 à 14:05
-- Version du serveur :  5.6.17-1~dotdeb.1
-- Version de PHP :  5.4.27-1~dotdeb.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `stagestx`
--

-- --------------------------------------------------------

--
-- Structure de la table `stages`
--
-- Création :  Ven 09 Mai 2014 à 13:56
--

DROP TABLE IF EXISTS `stages`;
CREATE TABLE IF NOT EXISTS `stages` (
`idStage` int(11) NOT NULL,
  `titreStage` varchar(255) NOT NULL,
  `nomEtudiant` varchar(255) NOT NULL,
  `nomEntreprise` varchar(255) NOT NULL,
  `pays` varchar(255) NOT NULL,
  `uv` enum('TN09','TN10') NOT NULL,
  `descriptionComplete` text NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=181 ;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--
-- Création :  Lun 02 Juin 2014 à 14:03
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
`userid` int(11) NOT NULL,
  `casLogin` char(8) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Structure de la table `votes`
--
-- Création :  Lun 02 Juin 2014 à 14:01
--

DROP TABLE IF EXISTS `votes`;
CREATE TABLE IF NOT EXISTS `votes` (
  `login` varchar(255) NOT NULL,
  `stage` int(11) NOT NULL,
  `note` tinyint(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- RELATIONS POUR LA TABLE `votes`:
--   `login`
--       `users` -> `casLogin`
--   `stage`
--       `stages` -> `idStage`
--

--
-- Index pour les tables exportées
--

--
-- Index pour la table `stages`
--
ALTER TABLE `stages`
 ADD PRIMARY KEY (`idStage`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`userid`), ADD UNIQUE KEY `casLogin` (`casLogin`);

--
-- Index pour la table `votes`
--
ALTER TABLE `votes`
 ADD PRIMARY KEY (`login`,`stage`), ADD KEY `fk_stage` (`stage`), ADD KEY `login` (`login`), ADD FULLTEXT KEY `login_2` (`login`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `stages`
--
ALTER TABLE `stages`
MODIFY `idStage` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=181;
--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `votes`
--
ALTER TABLE `votes`
ADD CONSTRAINT `fk_user` FOREIGN KEY (`login`) REFERENCES `users` (`casLogin`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `fk_stage` FOREIGN KEY (`stage`) REFERENCES `stages` (`idStage`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
