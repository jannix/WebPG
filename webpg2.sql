-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Lun 18 Avril 2011 à 10:52
-- Version du serveur: 5.1.53
-- Version de PHP: 5.3.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `webpg`
--

-- --------------------------------------------------------

--
-- Structure de la table `characters`
--

DROP TABLE IF EXISTS `characters`;
CREATE TABLE `characters` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_cmodels` smallint(5) unsigned NOT NULL,
  `id_user` int(11) unsigned NOT NULL,
  `id_party` int(11) unsigned NOT NULL,
  `team` tinyint(1) unsigned NOT NULL,
  `xp` mediumint(8) unsigned NOT NULL,
  `x` tinyint(3) unsigned NOT NULL,
  `y` tinyint(3) unsigned NOT NULL,
  `hp` smallint(5) unsigned NOT NULL,
  `mp` smallint(5) unsigned NOT NULL,
  `kills` tinyint(3) unsigned NOT NULL,
  `deaths` tinyint(3) unsigned NOT NULL,
  `assists` tinyint(3) unsigned NOT NULL,
  `time_deaths` int(10) unsigned NOT NULL,
  `po` mediumint(8) unsigned NOT NULL,
  `total_po` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Contenu de la table `characters`
--

INSERT INTO `characters` (`id`, `id_cmodels`, `id_user`, `id_party`, `team`, `xp`, `x`, `y`, `hp`, `mp`, `kills`, `deaths`, `assists`, `time_deaths`, `po`, `total_po`) VALUES
(7, 0, 3, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(6, 0, 2, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `character_models`
--

DROP TABLE IF EXISTS `character_models`;
CREATE TABLE `character_models` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `id_image` smallint(5) unsigned NOT NULL,
  `hp` smallint(5) unsigned NOT NULL,
  `mp` smallint(5) unsigned NOT NULL,
  `atk_phy` smallint(5) unsigned NOT NULL,
  `atk_mag` smallint(5) unsigned NOT NULL,
  `def_phy` smallint(5) unsigned NOT NULL,
  `def_mag` smallint(5) unsigned NOT NULL,
  `luck` smallint(5) unsigned NOT NULL,
  `agility` smallint(5) unsigned NOT NULL,
  `hp_lvlup` tinyint(3) unsigned NOT NULL,
  `pm_lvlup` tinyint(3) unsigned NOT NULL,
  `atk_phy_lvlup` tinyint(3) unsigned NOT NULL,
  `atk_mag_lvlup` tinyint(3) unsigned NOT NULL,
  `def_phy_lvlup` tinyint(3) unsigned NOT NULL,
  `def_mag_lvlup` tinyint(3) unsigned NOT NULL,
  `luck_lvlup` tinyint(3) unsigned NOT NULL,
  `agility_lvlup` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `character_models`
--


-- --------------------------------------------------------

--
-- Structure de la table `character_objects`
--

DROP TABLE IF EXISTS `character_objects`;
CREATE TABLE `character_objects` (
  `id_omodels` smallint(5) unsigned NOT NULL,
  `id_character` int(11) unsigned NOT NULL,
  `position` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Contenu de la table `character_objects`
--


-- --------------------------------------------------------

--
-- Structure de la table `character_object_models`
--

DROP TABLE IF EXISTS `character_object_models`;
CREATE TABLE `character_object_models` (
  `id` smallint(5) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `duration` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Contenu de la table `character_object_models`
--


-- --------------------------------------------------------

--
-- Structure de la table `character_object_model_effects`
--

DROP TABLE IF EXISTS `character_object_model_effects`;
CREATE TABLE `character_object_model_effects` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_omodels` smallint(5) unsigned NOT NULL,
  `field` varchar(15) NOT NULL,
  `value` smallint(6) NOT NULL,
  `type` char(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `character_object_model_effects`
--


-- --------------------------------------------------------

--
-- Structure de la table `chat_messages`
--

DROP TABLE IF EXISTS `chat_messages`;
CREATE TABLE `chat_messages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(10) unsigned NOT NULL,
  `id_party` int(10) unsigned NOT NULL,
  `timepost` int(10) unsigned NOT NULL,
  `text` varchar(255) NOT NULL,
  `chan` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=58 ;

--
-- Contenu de la table `chat_messages`
--

INSERT INTO `chat_messages` (`id`, `id_user`, `id_party`, `timepost`, `text`, `chan`) VALUES
(1, 2, 0, 1303082483, 'uiuil', 0),
(2, 3, 3, 1303082576, 'yo!', 1),
(3, 2, 3, 1303082582, 'yuit', 1),
(4, 3, 3, 1303082622, 'XD', 1),
(5, 2, 3, 1303082633, 'C''est marrant comme système', 1),
(6, 3, 3, 1303082640, 'ouais', 1),
(7, 3, 3, 1303082643, 'XD', 1),
(8, 3, 3, 1303082646, 'ca bug par contre', 1),
(9, 2, 3, 1303082649, 'YEAH', 1),
(10, 3, 3, 1303082658, 'XDDDD', 1),
(11, 3, 3, 1303082659, 'ok', 1),
(12, 3, 3, 1303082661, 'on y va', 1),
(13, 2, 3, 1303083179, 'aze', 1),
(14, 2, 3, 1303083187, 'yo', 1),
(15, 2, 3, 1303083191, 'XD', 1),
(16, 2, 3, 1303083193, 'Systeme bizarre', 1),
(17, 2, 3, 1303083196, 'ok', 1),
(18, 2, 3, 1303083198, 'on a termié', 1),
(19, 2, 3, 1303083199, 'let''s go', 1),
(20, 2, 3, 1303083199, 'sef', 1),
(21, 2, 3, 1303083199, 'es', 1),
(22, 2, 3, 1303083200, 'e', 1),
(23, 2, 3, 1303083200, 'e', 1),
(24, 2, 3, 1303083201, 'zer', 1),
(25, 2, 3, 1303083201, 'zer', 1),
(26, 2, 3, 1303083201, 'ze', 1),
(27, 2, 3, 1303083201, 'rze', 1),
(28, 2, 3, 1303083201, 'rz', 1),
(29, 2, 3, 1303083202, 'er', 1),
(30, 2, 3, 1303083219, 'aze', 1),
(31, 2, 3, 1303083221, 'lol', 1),
(32, 2, 3, 1303083249, 'xd', 1),
(33, 2, 3, 1303083268, 'azea', 1),
(34, 2, 3, 1303083393, 'aze', 1),
(35, 2, 3, 1303083394, 'XD', 1),
(36, 2, 3, 1303083398, 'ertrt', 1),
(37, 2, 3, 1303083398, 'er', 1),
(38, 2, 3, 1303083398, 'e', 1),
(39, 2, 3, 1303083398, 'e', 1),
(40, 2, 3, 1303083398, 'e', 1),
(41, 2, 3, 1303083399, 'e', 1),
(42, 2, 3, 1303083399, 'e', 1),
(43, 2, 3, 1303083399, 'e', 1),
(44, 2, 3, 1303083399, 'e', 1),
(45, 2, 3, 1303083399, 'e', 1),
(46, 2, 3, 1303083399, 'e', 1),
(47, 2, 3, 1303083400, 'e', 1),
(48, 2, 3, 1303083411, 'aa', 1),
(49, 2, 3, 1303083411, 'a', 1),
(50, 2, 3, 1303083411, 'a', 1),
(51, 2, 3, 1303083411, 'a', 1),
(52, 2, 3, 1303083412, 'aa', 1),
(53, 2, 3, 1303083412, 'a', 1),
(54, 2, 3, 1303083412, 'a', 1),
(55, 2, 3, 1303083413, 'tzeze', 1),
(56, 2, 3, 1303083413, 'y', 1),
(57, 2, 3, 1303083413, 'y', 1);

-- --------------------------------------------------------

--
-- Structure de la table `images`
--

DROP TABLE IF EXISTS `images`;
CREATE TABLE `images` (
  `id` smallint(5) unsigned NOT NULL,
  `imagename` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Contenu de la table `images`
--


-- --------------------------------------------------------

--
-- Structure de la table `map_cases`
--

DROP TABLE IF EXISTS `map_cases`;
CREATE TABLE `map_cases` (
  `x` tinyint(4) unsigned NOT NULL,
  `y` tinyint(4) unsigned NOT NULL,
  `passable` tinyint(1) NOT NULL,
  `id_image` smallint(5) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Contenu de la table `map_cases`
--


-- --------------------------------------------------------

--
-- Structure de la table `parties`
--

DROP TABLE IF EXISTS `parties`;
CREATE TABLE `parties` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `id_creator` int(10) unsigned NOT NULL,
  `time_gamecreate` int(10) unsigned NOT NULL,
  `time_gamestart` int(11) unsigned NOT NULL,
  `time_gameend` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `parties`
--

INSERT INTO `parties` (`id`, `title`, `id_creator`, `time_gamecreate`, `time_gamestart`, `time_gameend`) VALUES
(3, 'MY name!', 2, 1303082561, 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL,
  `last_time` int(11) unsigned NOT NULL,
  `last_messageid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `last_time`, `last_messageid`) VALUES
(2, 'mikilo', 'f45251bb305635a286195fdc3066f707', 1303083444, 57),
(3, 'test', '098f6bcd4621d373cade4e832627b4f6', 1303083445, 57);
