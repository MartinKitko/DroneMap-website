-- Adminer 4.8.1 MySQL 10.9.2-MariaDB dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `markers`;
CREATE TABLE `markers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `lat` float NOT NULL,
  `long` float NOT NULL,
  `m_color` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb3;

INSERT INTO `markers` (`id`, `title`, `description`, `lat`, `long`, `m_color`) VALUES
(10,	'Železničný most',	'Veľa miesta na lietanie, no pozor na vlaky. Lietajte nízko, pretože ste v dráhe letiska v PD.',	48.7455,	18.5651,	'green'),
(11,	'Lúka nad dedinou',	'Lúka nad dedinou s parkovaním na konci cesty neďaleko od nej. Pekný výhľad aj na okolité horyobec Kocurany.',	48.7761,	18.5516,	'darkGreen'),
(12,	'Rokoš',	'Z vrcholu Rokoša vidno na všetky strany, no keď spadnete s koptérou medzi stromy, už ju len tak nenájdete.',	48.7706,	18.4348,	'black'),
(13,	'Strom nad Máčovom',	'Samotný starý dub v strede poľa s peknými výhľadmi',	48.7799,	18.5097,	'red'),
(14,	'Kopec nad internátmi',	'Kopec / lúka s výhľadom na mesto, pozor na ľudí',	49.2063,	18.7578,	'red'),
(15,	'Miesto pod horou',	'Pekné miesto na lietanie na lúke medzi stromami, no je tu dosť veľká tráva, keďže sa tu často nekosí',	48.7622,	18.7141,	'blue'),
(16,	'Veľa miesta',	'Veľké miesto obľúbené aj pilotmi iných typov RC modelov, ktorí tu občas organizujú zrazy',	48.8079,	18.6029,	'orange'),
(17,	'Tu vás nikto nezastaví',	'Miesta nekonečno no dosť teplo, pozor na prehrievanie',	18.8898,	25.7697,	'red'),
(18,	'Stred ostrova',	'Nádherné miesto, no dostať sa sem nie je ľahké',	-17.6402,	-149.455,	'red');

-- 2022-11-20 20:24:25
