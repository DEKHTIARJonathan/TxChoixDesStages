/*
Structure de la base de données pour la Billetterie évènementielle de l'UTC

Développée et mise au point par Matthieu Guffroy et Jonathan Dekhtiar


*/

/* ===================================================================*/

DROP TABLE IF EXISTS `assos`;
CREATE TABLE IF NOT EXISTS `assos` (
  `asso_id` int(11) NOT NULL AUTO_INCREMENT,
  `asso_nom` varchar(255) NOT NULL,
  `asso_login` varchar(255) NOT NULL,
  PRIMARY KEY (`asso_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/* ===================================================================*/

DROP TABLE IF EXISTS `events`;
CREATE TABLE IF NOT EXISTS `events` (
  `event_id` int(11) NOT NULL AUTO_INCREMENT,
  `event_name` varchar(255) NOT NULL,
  `event_date` datetime NOT NULL,
  `event_place_max` int(8) NOT NULL,
  `event_place_invit_max` int(8) NOT NULL,
  `event_place_max_exterieurs` int(8) NOT NULL,
  `event_place_max_cotisant` int(8) NOT NULL,
  `event_place_max_tremplin` int(8) NOT NULL,
  `event_place_max_guest` int(8) DEFAULT NULL,
  PRIMARY KEY (`event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/* ===================================================================*/

DROP TABLE IF EXISTS `places`;
CREATE TABLE IF NOT EXISTS `places` (
  `place_id` int(11) NOT NULL AUTO_INCREMENT,
  `place_event_id` int(11) NOT NULL,
  `place_badge` varchar(40) NOT NULL,
  `place_code` varchar(20) NOT NULL,
  `place_vendeur` int(8) NOT NULL COMMENT 'login CAS ou PAY_UTC',
  `place_tarif` int(3) NOT NULL,
  `place_tarif_id` int(11) NOT NULL,
  `place_date_vente` datetime NOT NULL,
  `place_date_entree` datetime NOT NULL,
  `place_poste_entree` varchar(3) NOT NULL,
  PRIMARY KEY (`place_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/* ===================================================================*/

DROP TABLE IF EXISTS `plages_ventes`;
CREATE TABLE IF NOT EXISTS `plages_ventes` (
  `plages_ventes_id` int(11) NOT NULL AUTO_INCREMENT,
  `plages_ventes_event_id` int(11) DEFAULT NULL,
  `plages_ventes_tarif_id` int(11) DEFAULT NULL,
  `plages_ventes_start` datetime NOT NULL,
  `plages_ventes_end` datetime NOT NULL,
  PRIMARY KEY (`plages_ventes_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/* ===================================================================*/

DROP TABLE IF EXISTS `resp_asso`;
CREATE TABLE IF NOT EXISTS `resp_asso` (
  `resp_asso_id` int(11) NOT NULL AUTO_INCREMENT,
  `asso_id` int(11) NOT NULL,
  `resp_asso_login` varchar(8) NOT NULL,
  PRIMARY KEY (`resp_asso_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/* ===================================================================*/

DROP TABLE IF EXISTS `tarifs`;
CREATE TABLE IF NOT EXISTS `tarifs` (
  `tarif_id` int(11) NOT NULL AUTO_INCREMENT,
  `tarif_event_id` int(11) NOT NULL,
  `tarif_name` varchar(255) NOT NULL,
  `tarif_prix` int(4) NOT NULL,
  `tarif_max_sold` int(8) NOT NULL,
  `tarif_exterieur_max` int(8) DEFAULT NULL,
  `tarif_cotisant_max` int(8) DEFAULT NULL,
  `tarif_tremplin_max` int(8) DEFAULT NULL,
  `tarif_guest_max` int(8) DEFAULT NULL,
  `tarif_total_max` int(8) NOT NULL,
  PRIMARY KEY (`tarif_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/* ===================================================================*/

DROP TABLE IF EXISTS `vendeurs`;
CREATE TABLE IF NOT EXISTS `vendeurs` (
  `vendeur_id` int(11) NOT NULL AUTO_INCREMENT,
  `vendeur_login` varchar(8) NOT NULL,
  `vendeur_event_id` int(11) NOT NULL,
  PRIMARY KEY (`vendeur_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;
