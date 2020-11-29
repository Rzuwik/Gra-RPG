-- phpMyAdmin SQL Dump
-- version 3.5.8.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Czas wygenerowania: 29 Lis 2020, 00:16
-- Wersja serwera: 10.1.14-MariaDB
-- Wersja PHP: 5.6.23

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Baza danych: `game_rpg`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `applications`
--

CREATE TABLE IF NOT EXISTS `applications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `content` text COLLATE utf8_polish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `changelog`
--

CREATE TABLE IF NOT EXISTS `changelog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ver` float NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `content` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Zrzut danych tabeli `changelog`
--

INSERT INTO `changelog` (`id`, `ver`, `date`, `content`) VALUES
(1, 0.5, '2020-11-29 00:32:55', '<ul><li>Utworzono ten changelog</li><li>Utworzono podstawowy skrypt gildii<ul><li>Tworzenie i usuwanie gildii</li><li>Dodawanie do i usuwanie z gildii</li><li>Prosty system przywilejów</li><li>Dostosowanie skryptu rankingu dla gildii</li></ul></li><li>System maili systemowych używanych przez skrypty</li></ul>'),

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  `content` text NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `cron`
--

CREATE TABLE IF NOT EXISTS `cron` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `last_action` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=2 ;

--
-- Zrzut danych tabeli `cron`
--

INSERT INTO `cron` (`id`, `last_action`) VALUES
(1, 1468306103);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `guilds`
--

CREATE TABLE IF NOT EXISTS `guilds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ownerid` int(11) NOT NULL,
  `tag` varchar(30) COLLATE utf8_polish_ci NOT NULL,
  `name` varchar(30) COLLATE utf8_polish_ci NOT NULL,
  `avatar` int(11) NOT NULL DEFAULT '0',
  `cash` int(11) NOT NULL DEFAULT '0',
  `lvl` int(11) NOT NULL DEFAULT '1',
  `rep` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `inventory`
--

CREATE TABLE IF NOT EXISTS `inventory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `obj` int(11) NOT NULL,
  `used` int(11) NOT NULL DEFAULT '0',
  `stamina` int(11) NOT NULL DEFAULT '100',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `items`
--

CREATE TABLE IF NOT EXISTS `items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `lvl` int(11) NOT NULL,
  `type` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `min_dmg` int(11) NOT NULL,
  `max_dmg` int(11) NOT NULL,
  `resist` int(11) NOT NULL,
  `cost` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=5 ;

--
-- Zrzut danych tabeli `items`
--

INSERT INTO `items` (`id`, `name`, `lvl`, `type`, `min_dmg`, `max_dmg`, `resist`, `cost`) VALUES
(1, 'Miecz wojownika', 1, 'weapon', 3, 7, 0, 150),
(2, 'Pozłacana halabarda', 4, 'weapon', 10, 14, 0, 600),
(3, 'Krwisty miecz', 10, 'weapon', 20, 26, 0, 2000),
(4, 'Skórzana kamizelka', 1, 'armor', 0, 0, 10, 150);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `locations`
--

CREATE TABLE IF NOT EXISTS `locations` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `type` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `x` int(11) NOT NULL,
  `y` int(11) NOT NULL,
  `re_xp` int(11) NOT NULL,
  `re_cash` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=4 ;

--
-- Zrzut danych tabeli `locations`
--

INSERT INTO `locations` (`id`, `name`, `type`, `x`, `y`, `re_xp`, `re_cash`) VALUES
(1, 'Avalone', 'village', 4, 1, 8, 60),
(2, 'Ampion', 'city', 5, 3, 5, 100),
(3, 'Sealgard', 'polis', 3, 3, 5, 60);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `mail`
--

CREATE TABLE IF NOT EXISTS `mail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from_id` int(11) NOT NULL,
  `to_id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `title` text COLLATE utf8_polish_ci NOT NULL,
  `content` text COLLATE utf8_polish_ci NOT NULL,
  `date` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `tavern`
--

CREATE TABLE IF NOT EXISTS `tavern` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `start` int(11) NOT NULL,
  `end` int(11) NOT NULL,
  `hours` int(11) NOT NULL,
  `is_int` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `trips`
--

CREATE TABLE IF NOT EXISTS `trips` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `start` int(11) NOT NULL,
  `end` int(11) NOT NULL,
  `seen` int(11) NOT NULL DEFAULT '1',
  `x` int(11) NOT NULL,
  `y` int(11) NOT NULL,
  `dis` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) CHARACTER SET utf8 NOT NULL,
  `password` varchar(40) CHARACTER SET utf8 NOT NULL,
  `login` varchar(25) CHARACTER SET utf8 NOT NULL,
  `status` int(100) NOT NULL DEFAULT '0',
  `avatar` int(1) NOT NULL DEFAULT '0',
  `guild` int(11) NOT NULL DEFAULT '0',
  `guild_priv` int(11) NOT NULL DEFAULT '0',
  `hp` int(100) NOT NULL DEFAULT '10',
  `max_hp` int(100) NOT NULL DEFAULT '10',
  `ap` int(100) NOT NULL DEFAULT '12',
  `max_ap` int(100) NOT NULL DEFAULT '12',
  `lvl` int(100) NOT NULL DEFAULT '1',
  `xp` int(100) NOT NULL DEFAULT '0',
  `max_xp` int(100) NOT NULL DEFAULT '30',
  `allxp` int(100) NOT NULL DEFAULT '0',
  `cash` int(100) NOT NULL DEFAULT '100',
  `str` int(100) NOT NULL DEFAULT '1',
  `dex` int(100) NOT NULL DEFAULT '1',
  `sta` int(100) NOT NULL DEFAULT '1',
  `intell` int(100) NOT NULL DEFAULT '1',
  `sp` int(100) NOT NULL DEFAULT '3',
  `pos_x` int(11) NOT NULL DEFAULT '4',
  `pos_y` int(11) NOT NULL DEFAULT '1',
  `last_comment` int(11) NOT NULL DEFAULT '0',
  `last_action` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`,`login`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `work`
--

CREATE TABLE IF NOT EXISTS `work` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `start` int(11) NOT NULL,
  `end` int(11) NOT NULL,
  `hours` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
