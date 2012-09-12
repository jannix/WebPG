

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

CREATE DATABASE `webpg` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `webpg`;

CREATE TABLE `character_models` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
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
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE `character_object_model_effects` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `id_omodels` smallint(5) unsigned NOT NULL,
  `field` varchar(15) NOT NULL,
  `value` smallint(6) NOT NULL,
  `type` char(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


CREATE TABLE `character_object_models` (
  `id` smallint(5) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `duration` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `character_objects` (
  `id_omodels` smallint(5) unsigned NOT NULL,
  `id_character` int(11) unsigned NOT NULL,
  `position` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `characters` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `id_cmodels` smallint(5) unsigned NOT NULL,
  `id_user` int(11) unsigned NOT NULL,
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
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE `chat_messages` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `id_user` int(10) unsigned NOT NULL,
  `time` int(10) unsigned NOT NULL,
  `text` varchar(255) NOT NULL,
  `chan` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE `images` (
  `id` smallint(5) unsigned NOT NULL,
  `imagename` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `map_cases` (
  `x` tinyint(4) unsigned NOT NULL,
  `y` tinyint(4) unsigned NOT NULL,
  `passable` tinyint(1) NOT NULL,
  `id_image` smallint(5) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `parties` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(50) NOT NULL,
  `time_gamestart` int(11) unsigned NOT NULL,
  `time_gameend` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `login` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL,
  `last_time` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

