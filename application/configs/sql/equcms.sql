-- phpMyAdmin SQL Dump
-- version 3.3.7deb3build0.10.10.1
-- http://www.phpmyadmin.net
--
-- Hoszt: localhost
-- Létrehozás ideje: 2011. jan. 06. 21:25
-- Szerver verzió: 5.1.49
-- PHP verzió: 5.3.3-1ubuntu9.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Adatbázis: `equcms`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet: `mvc`
--

CREATE TABLE IF NOT EXISTS `mvc` (
  `id` int(11) NOT NULL,
  `module` varchar(84) DEFAULT NULL,
  `controller` varchar(84) DEFAULT NULL,
  `action` varchar(84) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- A tábla adatainak kiíratása `mvc`
--

INSERT INTO `mvc` (`id`, `module`, `controller`, `action`) VALUES
(1, '', '', ''),
(2, 'index', 'admin', ''),
(3, 'mvc', 'admin', ''),
(4, 'mvc', 'admin', 'list'),
(5, 'mvc', 'admin', 'create'),
(6, 'user', 'admin', ''),
(7, 'user', 'admin', 'list'),
(8, 'user', 'admin', 'create'),
(9, 'user-group', 'admin', ''),
(10, 'user-group', 'admin', 'list'),
(11, 'user-group', 'admin', 'create'),
(12, 'role-resource', 'admin', ''),
(13, 'role-resource', 'admin', 'list'),
(14, 'role-resource', 'admin', 'create'),
(15, 'mvc', 'admin', 'update'),
(16, 'user', 'admin', 'update'),
(17, 'user-group', 'admin', 'update'),
(18, 'role-resource', 'admin', 'update');

-- --------------------------------------------------------

--
-- Tábla szerkezet: `resource`
--

CREATE TABLE IF NOT EXISTS `resource` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `lvl` int(11) NOT NULL,
  `resource` varchar(255) NOT NULL,
  `discr` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `resource_parent_id_idx` (`parent_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- A tábla adatainak kiíratása `resource`
--

INSERT INTO `resource` (`id`, `parent_id`, `lft`, `rgt`, `lvl`, `resource`, `discr`) VALUES
(1, NULL, 1, 36, 0, 'mvc:', 'mvc'),
(2, 1, 2, 35, 1, 'mvc:index.admin', 'mvc'),
(3, 2, 3, 10, 2, 'mvc:mvc.admin', 'mvc'),
(4, 3, 4, 5, 3, 'mvc:mvc.admin.list', 'mvc'),
(5, 3, 6, 7, 3, 'mvc:mvc.admin.create', 'mvc'),
(6, 2, 11, 18, 2, 'mvc:user.admin', 'mvc'),
(7, 6, 12, 13, 3, 'mvc:user.admin.list', 'mvc'),
(8, 6, 14, 15, 3, 'mvc:user.admin.create', 'mvc'),
(9, 2, 19, 26, 2, 'mvc:user-group.admin', 'mvc'),
(10, 9, 20, 21, 3, 'mvc:user-group.admin.list', 'mvc'),
(11, 9, 22, 23, 3, 'mvc:user-group.admin.create', 'mvc'),
(12, 2, 27, 34, 2, 'mvc:role-resource.admin', 'mvc'),
(13, 12, 28, 29, 3, 'mvc:role-resource.admin.list', 'mvc'),
(14, 12, 30, 31, 3, 'mvc:role-resource.admin.create', 'mvc'),
(15, 3, 8, 9, 3, 'mvc:mvc.admin.update', 'mvc'),
(16, 6, 16, 17, 3, 'mvc:user.admin.update', 'mvc'),
(17, 9, 24, 25, 3, 'mvc:user-group.admin.update', 'mvc'),
(18, 12, 32, 33, 3, 'mvc:role-resource.admin.update', 'mvc');

-- --------------------------------------------------------

--
-- Tábla szerkezet: `role`
--

CREATE TABLE IF NOT EXISTS `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `lvl` int(11) NOT NULL,
  `role` varchar(255) NOT NULL,
  `discr` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `role_parent_id_idx` (`parent_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- A tábla adatainak kiíratása `role`
--

INSERT INTO `role` (`id`, `parent_id`, `lft`, `rgt`, `lvl`, `role`, `discr`) VALUES
(1, NULL, 1, 4, 0, 'Administrators', 'usergroup'),
(2, 1, 2, 3, 1, 'szjani@szjani.hu', 'user');

-- --------------------------------------------------------

--
-- Tábla szerkezet: `role_resource`
--

CREATE TABLE IF NOT EXISTS `role_resource` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `resource_id` int(11) NOT NULL,
  `allowed` tinyint(1) NOT NULL,
  `privilege` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `role_resource_role_id_idx` (`role_id`),
  KEY `role_resource_resource_id_idx` (`resource_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- A tábla adatainak kiíratása `role_resource`
--

INSERT INTO `role_resource` (`id`, `role_id`, `resource_id`, `allowed`, `privilege`) VALUES
(1, 1, 1, 1, NULL);

-- --------------------------------------------------------

--
-- Tábla szerkezet: `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(32) NOT NULL,
  `activation_code` varchar(12) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_email_uniq` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- A tábla adatainak kiíratása `user`
--

INSERT INTO `user` (`id`, `email`, `password_hash`, `activation_code`) VALUES
(2, 'szjani@szjani.hu', 'f2685fd6c2bfe8f7b9dafc99bfb15006', '0bXVM7ctBhqe');

-- --------------------------------------------------------

--
-- Tábla szerkezet: `user_group`
--

CREATE TABLE IF NOT EXISTS `user_group` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- A tábla adatainak kiíratása `user_group`
--

INSERT INTO `user_group` (`id`, `name`) VALUES
(1, 'Administrators');

--
-- Megkötések a kiírt táblákhoz
--

--
-- Megkötések a táblához `mvc`
--
ALTER TABLE `mvc`
  ADD CONSTRAINT `mvc_ibfk_1` FOREIGN KEY (`id`) REFERENCES `resource` (`id`) ON DELETE CASCADE;

--
-- Megkötések a táblához `resource`
--
ALTER TABLE `resource`
  ADD CONSTRAINT `resource_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `resource` (`id`);

--
-- Megkötések a táblához `role`
--
ALTER TABLE `role`
  ADD CONSTRAINT `role_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `user_group` (`id`);

--
-- Megkötések a táblához `role_resource`
--
ALTER TABLE `role_resource`
  ADD CONSTRAINT `role_resource_ibfk_2` FOREIGN KEY (`resource_id`) REFERENCES `resource` (`id`),
  ADD CONSTRAINT `role_resource_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`);

--
-- Megkötések a táblához `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`id`) REFERENCES `role` (`id`) ON DELETE CASCADE;

--
-- Megkötések a táblához `user_group`
--
ALTER TABLE `user_group`
  ADD CONSTRAINT `user_group_ibfk_1` FOREIGN KEY (`id`) REFERENCES `role` (`id`) ON DELETE CASCADE;
