-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Machine: localhost
-- Genereertijd: 16 jul 2012 om 19:48
-- Serverversie: 5.5.24
-- PHP-Versie: 5.3.10-1ubuntu3.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `alphaserv3`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `acl_action`
--

CREATE TABLE IF NOT EXISTS `acl_action` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `acl_object_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `default_value` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `acl_object_id` (`acl_object_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

--
-- Gegevens worden uitgevoerd voor tabel `acl_action`
--

INSERT INTO `acl_action` (`id`, `acl_object_id`, `name`, `default_value`) VALUES
(4, 9, 'index', 0),
(5, 9, 'create', 0),
(6, 9, 'admin', 0),
(7, 13, 'use', 1),
(8, 9, 'view', 0),
(9, 9, 'delete', 0),
(10, 16, 'viewUnpublished', 0),
(11, 9, 'update', 0),
(12, 16, 'view', 1),
(13, 16, 'read', 1),
(14, 16, 'edit', 0),
(15, 16, 'delete', 0),
(16, 16, 'comment', 1),
(17, 16, 'like', 0),
(18, 23, 'view', 0);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `acl_object`
--

CREATE TABLE IF NOT EXISTS `acl_object` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;

--
-- Gegevens worden uitgevoerd voor tabel `acl_object`
--

INSERT INTO `acl_object` (`id`, `name`) VALUES
(14, 'content.blog.2'),
(16, 'content.blog.3'),
(23, 'content.comment.7'),
(15, 'contentblog.temp'),
(17, 'contentcomment.temp'),
(18, 'contentcomment.temp_'),
(19, 'contentcomment.temp__'),
(20, 'contentcomment.temp___'),
(21, 'contentcomment.temp____'),
(22, 'contentcomment.temp_____'),
(9, 'controller.content'),
(12, 'groups.admin'),
(10, 'groups.anonymous'),
(11, 'groups.world'),
(13, 'markup.plain');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `activationkey`
--

CREATE TABLE IF NOT EXISTS `activationkey` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hash` varchar(500) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hash` (`hash`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `content`
--

CREATE TABLE IF NOT EXISTS `content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `content` text NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_date` datetime DEFAULT NULL,
  `creator_id` int(11) NOT NULL,
  `updater_id` int(11) DEFAULT NULL,
  `acl_object_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `can_comment` tinyint(1) NOT NULL,
  `markup_id` int(11) NOT NULL,
  `published` int(11) NOT NULL,
  `widgets_enabled` tinyint(1) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `html` text NOT NULL COMMENT 'the rendered version',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `creator_id` (`creator_id`),
  KEY `updater_id` (`updater_id`),
  KEY `acl_object_id` (`acl_object_id`),
  KEY `type_id` (`type_id`),
  KEY `language_id` (`language_id`),
  KEY `markup_id` (`markup_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Gegevens worden uitgevoerd voor tabel `content`
--

INSERT INTO `content` (`id`, `name`, `content`, `created_date`, `updated_date`, `creator_id`, `updater_id`, `acl_object_id`, `type_id`, `language_id`, `can_comment`, `markup_id`, `published`, `widgets_enabled`, `parent_id`, `html`) VALUES
(3, 'Test', 'hello <b>WORDL</b> :)', '2012-07-16 09:56:51', '2012-07-16 13:18:50', 12, 12, 16, 1, 1, 1, 1, 1, 1, NULL, 'hello &lt;b&gt;WORDL&lt;/b&gt; :)'),
(7, 'name', 'of the comment\r\n', '2012-07-16 19:11:14', NULL, 12, NULL, 23, 2, 1, 1, 1, 0, 0, 3, 'of the comment\r\n');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `content_type`
--

CREATE TABLE IF NOT EXISTS `content_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `class` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Gegevens worden uitgevoerd voor tabel `content_type`
--

INSERT INTO `content_type` (`id`, `name`, `class`) VALUES
(1, 'blog', 'AsBlogContent'),
(2, 'comment', 'AsCommentContent');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `group`
--

CREATE TABLE IF NOT EXISTS `group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `acl_object_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `acl_object_id` (`acl_object_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Gegevens worden uitgevoerd voor tabel `group`
--

INSERT INTO `group` (`id`, `name`, `acl_object_id`) VALUES
(2, 'anonymous', 10),
(3, 'world', 11),
(4, 'admin', 12);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `group_action`
--

CREATE TABLE IF NOT EXISTS `group_action` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `action_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `value` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `action_id` (`action_id`,`order_id`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Gegevens worden uitgevoerd voor tabel `group_action`
--

INSERT INTO `group_action` (`id`, `group_id`, `action_id`, `order_id`, `value`) VALUES
(2, 4, 4, 1, 1),
(3, 4, 5, 1, 1),
(4, 4, 6, 1, 1),
(5, 4, 8, 1, 1),
(6, 4, 9, 1, 1),
(7, 4, 10, 1, 1),
(8, 4, 11, 1, 1);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `group_user`
--

CREATE TABLE IF NOT EXISTS `group_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Gegevens worden uitgevoerd voor tabel `group_user`
--

INSERT INTO `group_user` (`id`, `group_id`, `user_id`) VALUES
(1, 4, 12);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `language`
--

CREATE TABLE IF NOT EXISTS `language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Gegevens worden uitgevoerd voor tabel `language`
--

INSERT INTO `language` (`id`, `name`) VALUES
(1, 'english');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `markup`
--

CREATE TABLE IF NOT EXISTS `markup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `acl_object_id` int(11) NOT NULL,
  `class` varchar(50) NOT NULL,
  `settings` text NOT NULL COMMENT 'json formatted settings',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `acl_object_id` (`acl_object_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Gegevens worden uitgevoerd voor tabel `markup`
--

INSERT INTO `markup` (`id`, `name`, `acl_object_id`, `class`, `settings`) VALUES
(1, 'plain', 13, 'AsPlainMarkup', '');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(10) NOT NULL,
  `email` varchar(50) NOT NULL,
  `display_group_id` int(11) DEFAULT NULL,
  `password` text NOT NULL,
  `ingame_password` text NOT NULL,
  `status` enum('banned','active','pending','admin_pending','oauth') NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `display_group_id` (`display_group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Gegevens worden uitgevoerd voor tabel `user`
--

INSERT INTO `user` (`id`, `username`, `email`, `display_group_id`, `password`, `ingame_password`, `status`) VALUES
(12, 'killme', 'bram.wall@gmail.com', NULL, '$2a$08$/IwxlaHz2nNf1bxORfHD7elkOPDb2H71mTfx/3AU4Y2EzFmIr9HKS', 'TEST', 'active');

--
-- Beperkingen voor gedumpte tabellen
--

--
-- Beperkingen voor tabel `acl_action`
--
ALTER TABLE `acl_action`
  ADD CONSTRAINT `acl_action_ibfk_1` FOREIGN KEY (`acl_object_id`) REFERENCES `acl_object` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Beperkingen voor tabel `activationkey`
--
ALTER TABLE `activationkey`
  ADD CONSTRAINT `activationkey_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Beperkingen voor tabel `content`
--
ALTER TABLE `content`
  ADD CONSTRAINT `content_ibfk_1` FOREIGN KEY (`updater_id`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `content_ibfk_2` FOREIGN KEY (`acl_object_id`) REFERENCES `acl_object` (`id`),
  ADD CONSTRAINT `content_ibfk_3` FOREIGN KEY (`type_id`) REFERENCES `content_type` (`id`),
  ADD CONSTRAINT `content_ibfk_4` FOREIGN KEY (`creator_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `content_ibfk_5` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`),
  ADD CONSTRAINT `content_ibfk_6` FOREIGN KEY (`markup_id`) REFERENCES `markup` (`id`);

--
-- Beperkingen voor tabel `group`
--
ALTER TABLE `group`
  ADD CONSTRAINT `group_ibfk_1` FOREIGN KEY (`acl_object_id`) REFERENCES `acl_object` (`id`);

--
-- Beperkingen voor tabel `group_action`
--
ALTER TABLE `group_action`
  ADD CONSTRAINT `group_action_ibfk_2` FOREIGN KEY (`action_id`) REFERENCES `acl_action` (`id`),
  ADD CONSTRAINT `group_action_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `group` (`id`);

--
-- Beperkingen voor tabel `group_user`
--
ALTER TABLE `group_user`
  ADD CONSTRAINT `group_user_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `group_user_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `group` (`id`);

--
-- Beperkingen voor tabel `markup`
--
ALTER TABLE `markup`
  ADD CONSTRAINT `markup_ibfk_2` FOREIGN KEY (`acl_object_id`) REFERENCES `acl_object` (`id`);

--
-- Beperkingen voor tabel `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`display_group_id`) REFERENCES `group` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
