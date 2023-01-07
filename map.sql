-- Adminer 4.8.1 MySQL 10.9.2-MariaDB dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP DATABASE IF EXISTS `map`;
CREATE DATABASE `map` /*!40100 DEFAULT CHARACTER SET utf8mb3 */;
USE `map`;

DROP TABLE IF EXISTS `events`;
CREATE TABLE `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `marker_id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `date_from` datetime NOT NULL,
  `date_to` datetime DEFAULT NULL,
  `photo` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `events_markers_null_fk` (`marker_id`),
  KEY `events_users_id_fk` (`author_id`),
  CONSTRAINT `events_markers_null_fk` FOREIGN KEY (`marker_id`) REFERENCES `markers` (`id`),
  CONSTRAINT `events_users_id_fk` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3;

INSERT INTO `events` (`id`, `marker_id`, `author_id`, `title`, `description`, `date_from`, `date_to`, `photo`) VALUES
(1,	37,	2,	'Dronfest 2023',	'Ako vždy aj na tohtoročnom Dronfeste nebudú chýbať závody. Bude sa jednať o exhibičný závod ktorého cielom je zalietať si a predviesť závodné koptéry návštevníkom akcie.',	'2023-02-02 10:00:00',	'2023-02-02 15:00:00',	'public/photos/events/1672583511_dronfest.png'),
(5,	10,	2,	'Zraz všetkých nadšencov',	'Príďte si zalietať na pravidelne konajúci sa zraz pri moste. Všetci sú vítaní.',	'2023-01-27 13:00:00',	NULL,	'public/photos/events/1672681326_B0000478.jpg');

DROP TABLE IF EXISTS `markers`;
CREATE TABLE `markers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `lat` float NOT NULL,
  `long` float NOT NULL,
  `m_color` varchar(20) DEFAULT NULL,
  `photo` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `markers_users_null_fk` (`author_id`),
  CONSTRAINT `markers_users_null_fk` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb3;

INSERT INTO `markers` (`id`, `author_id`, `title`, `description`, `lat`, `long`, `m_color`, `photo`) VALUES
(10,	2,	'Železničný most PD',	'Veľa miesta na lietanie, no pozor na vlaky. Lietajte nízko, pretože ste v dráhe letiska v PD.',	48.7455,	18.5651,	'green',	'public/photos/zel_most.jpg'),
(11,	2,	'Lúka nad dedinou',	'Lúka nad dedinou s parkovaním na konci cesty neďaleko od nej. Pekný výhľad aj na okolité hory a obec Kocurany.',	48.7761,	18.5516,	'red',	'public/photos/luka.jpg'),
(12,	5,	'Rokoš',	'Z vrcholu Rokoša vidno na všetky strany, no keď spadnete s koptérou medzi stromy, už ju len tak nenájdete.',	48.7706,	18.4348,	'black',	'public/photos/rokos.jpg'),
(13,	6,	'Strom nad Máčovom',	'Samotný starý dub v strede poľa s peknými výhľadmi',	48.7799,	18.5097,	'red',	'public/photos/strom_macov.jpeg'),
(14,	2,	'Kopec nad internátmi',	'Kopec / lúka s výhľadom na mesto, pozor na ľudí',	49.2063,	18.7578,	'red',	'public/photos/kopec_nad.PNG'),
(15,	5,	'Miesto pod horou',	'Pekné miesto na lietanie na lúke medzi stromami, no je tu dosť veľká tráva, keďže sa tu často nekosí',	48.7622,	18.7141,	'blue',	'public/photos/pod_horou.jpg'),
(16,	5,	'Veľa miesta',	'Veľké miesto obľúbené aj pilotmi iných typov RC modelov, ktorí tu občas organizujú zrazy',	48.8079,	18.6029,	'orange',	'public/photos/vela_miesta.png'),
(17,	2,	'Tu vás nikto nezastaví',	'Miesta nekonečno no dosť teplo, pozor na prehrievanie',	18.8898,	25.7697,	'red',	'public/photos/nikto.jpg'),
(18,	6,	'Stred ostrova',	'Nádherné miesto, no dostať sa sem nie je ľahké',	-17.6402,	-149.455,	'red',	'public/photos/sunset-trail-1024x679.jpg'),
(37,	2,	'FPV Aréna Žilina',	'FPV Aréna kde sú pravidelne organizované rôzne druhy závodov.',	49.2072,	18.7819,	'black',	'public/photos/1672582006_fpvarena.jpg');

DROP TABLE IF EXISTS `ratings`;
CREATE TABLE `ratings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `marker_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb3;

INSERT INTO `ratings` (`id`, `marker_id`, `user_id`, `rating`) VALUES
(1,	11,	2,	5),
(6,	10,	2,	3),
(7,	8,	2,	3),
(8,	12,	2,	4),
(9,	15,	2,	5),
(10,	16,	2,	4),
(11,	13,	2,	3),
(13,	10,	5,	2),
(14,	12,	5,	4),
(15,	13,	5,	1),
(16,	17,	5,	4),
(17,	12,	6,	3),
(18,	11,	6,	5),
(19,	13,	6,	5),
(20,	10,	6,	2),
(21,	15,	6,	4),
(22,	14,	6,	3),
(23,	11,	5,	4),
(34,	27,	7,	4),
(35,	37,	2,	5);

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(60) NOT NULL,
  `email` varchar(60) NOT NULL,
  `passwordHash` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb3;

INSERT INTO `users` (`id`, `username`, `email`, `passwordHash`) VALUES
(2,	'martin',	'martin@gmail.com',	'$2y$10$4cYVVuhGehBcvrOoCESYveJLnXTOUXdDqPgkeQm671pSNjT.Hm0bS'),
(5,	'martin2',	'martin2@gmail.com',	'$2y$10$S4POUsV.4PzuXvvb94YJQOLLkvFGNZWxgXnmK3MziqazXTEImFjkK'),
(6,	'martin3',	'martin3@gmail.com',	'$2y$10$SNIJWTzx4OnKGbdPH4R2IOCv8klkrW441ttIDMy0OdStZbb74Qw2W'),
(7,	'martin4',	'martin4@gmail.com',	'$2y$10$Dzsdq8QL4y5cC4W9VEiV7O020CJrmGeuF6CwFOo9wm2jajSua66be'),
(8,	'martin5',	'martin5@gmail.com',	'$2y$10$b3QS7DYZH1O1hfwZnd2w5ePWeXOGPe9vrC0OmkLtyJxzzsaQCm2xm');

-- 2023-01-07 15:43:59
