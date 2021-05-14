SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE DATABASE IF NOT EXISTS `skuska` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_slovak_ci;
USE `skuska`;

DROP TABLE IF EXISTS `answers`;
CREATE TABLE IF NOT EXISTS `answers` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `submission_id` int UNSIGNED DEFAULT NULL,
  `question_id` int UNSIGNED DEFAULT NULL,
  `points` decimal(5,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `question_id` (`question_id`),
  KEY `submission_id` (`submission_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

DROP TABLE IF EXISTS `answers_expression`;
CREATE TABLE IF NOT EXISTS `answers_expression` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `answer_id` int UNSIGNED NOT NULL,
  `MathML_expression` text CHARACTER SET utf8mb4 COLLATE utf8mb4_slovak_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `answer_id` (`answer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

DROP TABLE IF EXISTS `answers_image`;
CREATE TABLE IF NOT EXISTS `answers_image` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `answer_id` int UNSIGNED NOT NULL,
  `image_url` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_slovak_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `answer_id` (`answer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

DROP TABLE IF EXISTS `answers_option`;
CREATE TABLE IF NOT EXISTS `answers_option` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `answer_id` int UNSIGNED DEFAULT NULL,
  `option_id` int UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `answer_id` (`answer_id`),
  KEY `option_id` (`option_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

DROP TABLE IF EXISTS `answers_pair`;
CREATE TABLE IF NOT EXISTS `answers_pair` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `answer_id` int UNSIGNED DEFAULT NULL,
  `question_pair_id` int UNSIGNED DEFAULT NULL,
  `option_id` int UNSIGNED DEFAULT NULL,
  `partial_points` decimal(4,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `answer_id` (`answer_id`),
  KEY `option_id` (`option_id`),
  KEY `question_pair_id` (`question_pair_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

DROP TABLE IF EXISTS `answers_simple`;
CREATE TABLE IF NOT EXISTS `answers_simple` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `answer_id` int UNSIGNED DEFAULT NULL,
  `answer` text CHARACTER SET utf8mb4 COLLATE utf8mb4_slovak_ci,
  PRIMARY KEY (`id`),
  KEY `answer_id` (`answer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

DROP TABLE IF EXISTS `options`;
CREATE TABLE IF NOT EXISTS `options` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `question_id` int UNSIGNED DEFAULT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_slovak_ci,
  PRIMARY KEY (`id`),
  KEY `question_id` (`question_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

INSERT INTO `options` (`id`, `question_id`, `value`) VALUES
(1, 3, 'maj'),
(2, 3, 'marec'),
(3, 3, 'jun'),
(4, 4, '10'),
(5, 4, '10'),
(6, 4, '15'),
(7, 4, 'netusim'),
(8, 5, 'marec'),
(9, 5, 'jun'),
(10, 5, 'septeber'),
(11, 5, 'december'),
(12, 6, 'fabia'),
(13, 6, 'a4'),
(14, 6, 'ibiza'),
(15, 12, 'neviem este'),
(16, 12, 'ano'),
(17, 12, 'nie'),
(18, 13, 'pravdepodobne'),
(19, 13, 'fungovat'),
(20, 13, 'uvidime');

DROP TABLE IF EXISTS `questions`;
CREATE TABLE IF NOT EXISTS `questions` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `test_id` int UNSIGNED DEFAULT NULL,
  `question` text CHARACTER SET utf8mb4 COLLATE utf8mb4_slovak_ci,
  `type` enum('simple','option','pair','image','expression') CHARACTER SET utf8mb4 COLLATE utf8mb4_slovak_ci DEFAULT NULL,
  `max_points` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `test_id` (`test_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

INSERT INTO `questions` (`id`, `test_id`, `question`, `type`, `max_points`) VALUES
(1, 1, 'Aky je rok?', 'simple', 1),
(2, 1, 'Kolko dni ma januar?', 'simple', 1),
(3, 2, 'Aky je mesiac?', 'option', 1),
(4, 2, '5+5 =', 'option', 2),
(5, 3, 'Prirad', 'pair', 4),
(6, 3, 'Pospajaj', 'pair', 3),
(7, 4, 'Nakresli domcek 1 tahom', 'image', 5),
(8, 4, 'Nakresli mona lisu', 'image', 10),
(9, 5, 'Napis Pytagorovu vetu', 'expression', 2),
(10, 5, 'Napis ako sa pocita koren kvadratickej rovnice (ani ja neviem)', 'expression', 3),
(11, 6, 'Bude to fungovat?', 'simple', 1),
(12, 6, 'A toto bude fungovat?', 'option', 2),
(13, 6, 'Pospajaj', 'pair', 3),
(14, 6, 'Tu treba vyznacit ze ide o kreslenie v createTest', 'image', 10),
(15, 6, 'Tu treba vyznacit ze ide o rovnicu v createTest', 'expression', 10);

DROP TABLE IF EXISTS `questions_expression`;
CREATE TABLE IF NOT EXISTS `questions_expression` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `question_id` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `question_id` (`question_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

INSERT INTO `questions_expression` (`id`, `question_id`) VALUES
(1, 9),
(2, 10),
(3, 15);

DROP TABLE IF EXISTS `questions_image`;
CREATE TABLE IF NOT EXISTS `questions_image` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `question_id` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `question_id` (`question_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

INSERT INTO `questions_image` (`id`, `question_id`) VALUES
(1, 7),
(2, 8),
(3, 14);

DROP TABLE IF EXISTS `questions_option`;
CREATE TABLE IF NOT EXISTS `questions_option` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `question_id` int UNSIGNED DEFAULT NULL,
  `option_id` int UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `question_id` (`question_id`),
  KEY `option_id` (`option_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

INSERT INTO `questions_option` (`id`, `question_id`, `option_id`) VALUES
(1, 3, 1),
(2, 4, 4),
(3, 12, 15);

DROP TABLE IF EXISTS `questions_pair`;
CREATE TABLE IF NOT EXISTS `questions_pair` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `question_id` int UNSIGNED DEFAULT NULL,
  `answer` text CHARACTER SET utf8mb4 COLLATE utf8mb4_slovak_ci,
  `option_id` int UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `option_id` (`option_id`),
  KEY `question_id` (`question_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

INSERT INTO `questions_pair` (`id`, `question_id`, `answer`, `option_id`) VALUES
(1, 5, 'jar', 8),
(2, 5, 'leto', 9),
(3, 5, 'jesen', 10),
(4, 5, 'zima', 11),
(5, 6, 'skoda', 12),
(6, 6, 'audi', 13),
(7, 6, 'seat', 14),
(8, 13, 'Toto', 18),
(9, 13, 'nebude', 19),
(10, 13, 'ale', 20);

DROP TABLE IF EXISTS `questions_simple`;
CREATE TABLE IF NOT EXISTS `questions_simple` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `question_id` int UNSIGNED DEFAULT NULL,
  `answer` text CHARACTER SET utf8mb4 COLLATE utf8mb4_slovak_ci,
  PRIMARY KEY (`id`),
  KEY `question_id` (`question_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

INSERT INTO `questions_simple` (`id`, `question_id`, `answer`) VALUES
(1, 1, '2021'),
(2, 2, '31'),
(3, 11, 'neviem este');

DROP TABLE IF EXISTS `students`;
CREATE TABLE IF NOT EXISTS `students` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_slovak_ci DEFAULT NULL,
  `surname` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_slovak_ci DEFAULT NULL,
  `student_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_slovak_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `student_code` (`student_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

DROP TABLE IF EXISTS `students_status`;
CREATE TABLE IF NOT EXISTS `students_status` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` int UNSIGNED NOT NULL,
  `test_id` int UNSIGNED NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `student_id` (`student_id`,`test_id`),
  KEY `test_id` (`test_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

DROP TABLE IF EXISTS `submissions`;
CREATE TABLE IF NOT EXISTS `submissions` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` int UNSIGNED DEFAULT NULL,
  `test_id` int UNSIGNED DEFAULT NULL,
  `total_points` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `test_id` (`test_id`),
  KEY `student_id` (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

DROP TABLE IF EXISTS `teachers`;
CREATE TABLE IF NOT EXISTS `teachers` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_slovak_ci DEFAULT NULL,
  `surname` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_slovak_ci DEFAULT NULL,
  `email` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_slovak_ci DEFAULT NULL,
  `password` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_slovak_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

INSERT INTO `teachers` (`id`, `name`, `surname`, `email`, `password`) VALUES
(1, 'Janko', 'Hrasko', 'janko@hrasko.com', '$2y$10$N7rx5VnaU.oDdLwA6bDNV.gyjKVsVZIckc3B23f3ayRK0KgYQZore');

DROP TABLE IF EXISTS `tests`;
CREATE TABLE IF NOT EXISTS `tests` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_slovak_ci NOT NULL,
  `teacher_id` int UNSIGNED DEFAULT NULL,
  `total_points` int DEFAULT NULL,
  `active` tinyint DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `time` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `teacher_id` (`teacher_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

INSERT INTO `tests` (`id`, `code`, `teacher_id`, `total_points`, `active`, `created_at`, `time`) VALUES
(1, 'simple', 1, 2, 1, '2021-05-14 09:57:55', 2),
(2, 'option', 1, 3, 1, '2021-05-14 09:58:40', 3),
(3, 'pairs1', 1, 7, 1, '2021-05-14 10:00:19', 1),
(4, 'Image1', 1, 15, 1, '2021-05-14 10:00:53', 1),
(5, 'expres', 1, 5, 1, '2021-05-14 10:02:24', 4),
(6, 'compl1', 1, 26, 1, '2021-05-14 10:04:11', 10);


ALTER TABLE `answers`
  ADD CONSTRAINT `answers_ibfk_1` FOREIGN KEY (`submission_id`) REFERENCES `submissions` (`id`),
  ADD CONSTRAINT `answers_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`);

ALTER TABLE `answers_expression`
  ADD CONSTRAINT `answers_expression_ibfk_1` FOREIGN KEY (`answer_id`) REFERENCES `answers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE `answers_image`
  ADD CONSTRAINT `answers_image_ibfk_1` FOREIGN KEY (`answer_id`) REFERENCES `answers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE `answers_option`
  ADD CONSTRAINT `answers_option_ibfk_1` FOREIGN KEY (`answer_id`) REFERENCES `answers` (`id`),
  ADD CONSTRAINT `answers_option_ibfk_2` FOREIGN KEY (`option_id`) REFERENCES `options` (`id`);

ALTER TABLE `answers_pair`
  ADD CONSTRAINT `answers_pair_ibfk_1` FOREIGN KEY (`answer_id`) REFERENCES `answers` (`id`),
  ADD CONSTRAINT `answers_pair_ibfk_2` FOREIGN KEY (`option_id`) REFERENCES `options` (`id`),
  ADD CONSTRAINT `answers_pair_ibfk_3` FOREIGN KEY (`question_pair_id`) REFERENCES `questions_pair` (`id`);

ALTER TABLE `answers_simple`
  ADD CONSTRAINT `answers_simple_ibfk_1` FOREIGN KEY (`answer_id`) REFERENCES `answers` (`id`);

ALTER TABLE `options`
  ADD CONSTRAINT `options_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`);

ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`test_id`) REFERENCES `tests` (`id`);

ALTER TABLE `questions_expression`
  ADD CONSTRAINT `questions_expression_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE `questions_image`
  ADD CONSTRAINT `questions_image_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE `questions_option`
  ADD CONSTRAINT `questions_option_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`),
  ADD CONSTRAINT `questions_option_ibfk_2` FOREIGN KEY (`option_id`) REFERENCES `options` (`id`);

ALTER TABLE `questions_pair`
  ADD CONSTRAINT `questions_pair_ibfk_1` FOREIGN KEY (`option_id`) REFERENCES `options` (`id`),
  ADD CONSTRAINT `questions_pair_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`);

ALTER TABLE `questions_simple`
  ADD CONSTRAINT `questions_simple_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`);

ALTER TABLE `students_status`
  ADD CONSTRAINT `students_status_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `students_status_ibfk_2` FOREIGN KEY (`test_id`) REFERENCES `tests` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE `submissions`
  ADD CONSTRAINT `submissions_ibfk_1` FOREIGN KEY (`test_id`) REFERENCES `tests` (`id`),
  ADD CONSTRAINT `submissions_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`);

ALTER TABLE `tests`
  ADD CONSTRAINT `tests_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`);
SET FOREIGN_KEY_CHECKS=1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
