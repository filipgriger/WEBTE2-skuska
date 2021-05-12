-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Hostiteľ: localhost:3306
-- Čas generovania: St 12.Máj 2021, 09:40
-- Verzia serveru: 8.0.23-0ubuntu0.20.04.1
-- Verzia PHP: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáza: `skuska`
--
CREATE DATABASE IF NOT EXISTS `skuska` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_slovak_ci;
USE `skuska`;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `answers`
--

DROP TABLE IF EXISTS `answers`;
CREATE TABLE `answers` (
  `id` int UNSIGNED NOT NULL,
  `submission_id` int UNSIGNED DEFAULT NULL,
  `question_id` int UNSIGNED DEFAULT NULL,
  `points` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `answers_option`
--

DROP TABLE IF EXISTS `answers_option`;
CREATE TABLE `answers_option` (
  `id` int UNSIGNED NOT NULL,
  `answer_id` int UNSIGNED DEFAULT NULL,
  `option_id` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `answers_pair`
--

DROP TABLE IF EXISTS `answers_pair`;
CREATE TABLE `answers_pair` (
  `id` int UNSIGNED NOT NULL,
  `answer_id` int UNSIGNED DEFAULT NULL,
  `question_pair_id` int UNSIGNED DEFAULT NULL,
  `option_id` int UNSIGNED DEFAULT NULL,
  `partial_points` decimal(4,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `answers_simple`
--

DROP TABLE IF EXISTS `answers_simple`;
CREATE TABLE `answers_simple` (
  `id` int UNSIGNED NOT NULL,
  `answer_id` int UNSIGNED DEFAULT NULL,
  `answer` text COLLATE utf8mb4_slovak_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `options`
--

DROP TABLE IF EXISTS `options`;
CREATE TABLE `options` (
  `id` int UNSIGNED NOT NULL,
  `question_id` int UNSIGNED DEFAULT NULL,
  `value` text COLLATE utf8mb4_slovak_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

--
-- Sťahujem dáta pre tabuľku `options`
--

INSERT INTO `options` (`id`, `question_id`, `value`) VALUES
(1, 3, 'maj'),
(2, 4, 'jar'),
(3, 5, 'marec'),
(4, 5, 'jun'),
(5, 5, 'septeber'),
(6, 5, 'december'),
(7, 6, 'Fabia'),
(8, 6, 'Passat'),
(9, 6, 'Ibiza');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `questions`
--

DROP TABLE IF EXISTS `questions`;
CREATE TABLE `questions` (
  `id` int UNSIGNED NOT NULL,
  `test_id` int UNSIGNED DEFAULT NULL,
  `question` text COLLATE utf8mb4_slovak_ci,
  `type` enum('simple','option','pair') COLLATE utf8mb4_slovak_ci DEFAULT NULL,
  `max_points` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

--
-- Sťahujem dáta pre tabuľku `questions`
--

INSERT INTO `questions` (`id`, `test_id`, `question`, `type`, `max_points`) VALUES
(1, 1, 'Aky je rok?', 'simple', 1),
(2, 1, '2+2=', 'simple', 1),
(3, 2, 'Aky je mesiac?', 'option', 2),
(4, 2, 'Ake je rocne obdobie?', 'option', 2),
(5, 3, 'Pospajaj', 'pair', 4),
(6, 3, 'Pospajaj', 'pair', 3);

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `questions_option`
--

DROP TABLE IF EXISTS `questions_option`;
CREATE TABLE `questions_option` (
  `id` int UNSIGNED NOT NULL,
  `question_id` int UNSIGNED DEFAULT NULL,
  `option_id` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

--
-- Sťahujem dáta pre tabuľku `questions_option`
--

INSERT INTO `questions_option` (`id`, `question_id`, `option_id`) VALUES
(1, 3, 1),
(2, 4, 2);

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `questions_pair`
--

DROP TABLE IF EXISTS `questions_pair`;
CREATE TABLE `questions_pair` (
  `id` int UNSIGNED NOT NULL,
  `question_id` int UNSIGNED DEFAULT NULL,
  `answer` text COLLATE utf8mb4_slovak_ci,
  `option_id` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

--
-- Sťahujem dáta pre tabuľku `questions_pair`
--

INSERT INTO `questions_pair` (`id`, `question_id`, `answer`, `option_id`) VALUES
(1, 5, 'jar', 3),
(2, 5, 'leto', 4),
(3, 5, 'jesen', 5),
(4, 5, 'zima', 6),
(5, 6, 'Skoda', 7),
(6, 6, 'Volkswagen', 8),
(7, 6, 'Seat', 9);

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `questions_simple`
--

DROP TABLE IF EXISTS `questions_simple`;
CREATE TABLE `questions_simple` (
  `id` int UNSIGNED NOT NULL,
  `question_id` int UNSIGNED DEFAULT NULL,
  `answer` text COLLATE utf8mb4_slovak_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

--
-- Sťahujem dáta pre tabuľku `questions_simple`
--

INSERT INTO `questions_simple` (`id`, `question_id`, `answer`) VALUES
(1, 1, '2021'),
(2, 2, '4');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `students`
--

DROP TABLE IF EXISTS `students`;
CREATE TABLE `students` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(40) COLLATE utf8mb4_slovak_ci DEFAULT NULL,
  `surname` varchar(40) COLLATE utf8mb4_slovak_ci DEFAULT NULL,
  `student_code` varchar(10) COLLATE utf8mb4_slovak_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `submissions`
--

DROP TABLE IF EXISTS `submissions`;
CREATE TABLE `submissions` (
  `id` int UNSIGNED NOT NULL,
  `student_id` int UNSIGNED DEFAULT NULL,
  `test_id` int UNSIGNED DEFAULT NULL,
  `total_points` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `teachers`
--

DROP TABLE IF EXISTS `teachers`;
CREATE TABLE `teachers` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(40) COLLATE utf8mb4_slovak_ci DEFAULT NULL,
  `surname` varchar(40) COLLATE utf8mb4_slovak_ci DEFAULT NULL,
  `email` varchar(40) COLLATE utf8mb4_slovak_ci DEFAULT NULL,
  `password` varchar(60) COLLATE utf8mb4_slovak_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

--
-- Sťahujem dáta pre tabuľku `teachers`
--

INSERT INTO `teachers` (`id`, `name`, `surname`, `email`, `password`) VALUES
(1, 'Janko', 'Hrasko', 'janko@hrasko.com', '$2y$10$BNhfZT1c45VBMbUmCi3A/uaaCiMiBXCWKJ/xn8t6FIrUPP7pbLfHW');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `tests`
--

DROP TABLE IF EXISTS `tests`;
CREATE TABLE `tests` (
  `id` int UNSIGNED NOT NULL,
  `code` varchar(6) COLLATE utf8mb4_slovak_ci NOT NULL,
  `teacher_id` int UNSIGNED DEFAULT NULL,
  `total_points` int DEFAULT NULL,
  `active` tinyint DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

--
-- Sťahujem dáta pre tabuľku `tests`
--

INSERT INTO `tests` (`id`, `code`, `teacher_id`, `total_points`, `active`, `created_at`) VALUES
(1, 'simple', 1, 2, 0, '2021-05-12 09:36:51'),
(2, 'option', 1, 4, 0, '2021-05-12 09:37:51'),
(3, 'pairs1', 1, 7, 0, '2021-05-12 09:39:08');

--
-- Kľúče pre exportované tabuľky
--

--
-- Indexy pre tabuľku `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`),
  ADD KEY `submission_id` (`submission_id`) USING BTREE;

--
-- Indexy pre tabuľku `answers_option`
--
ALTER TABLE `answers_option`
  ADD PRIMARY KEY (`id`),
  ADD KEY `answer_id` (`answer_id`),
  ADD KEY `option_id` (`option_id`);

--
-- Indexy pre tabuľku `answers_pair`
--
ALTER TABLE `answers_pair`
  ADD PRIMARY KEY (`id`),
  ADD KEY `answer_id` (`answer_id`),
  ADD KEY `option_id` (`option_id`),
  ADD KEY `question_pair_id` (`question_pair_id`);

--
-- Indexy pre tabuľku `answers_simple`
--
ALTER TABLE `answers_simple`
  ADD PRIMARY KEY (`id`),
  ADD KEY `answer_id` (`answer_id`);

--
-- Indexy pre tabuľku `options`
--
ALTER TABLE `options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexy pre tabuľku `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `test_id` (`test_id`);

--
-- Indexy pre tabuľku `questions_option`
--
ALTER TABLE `questions_option`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`),
  ADD KEY `option_id` (`option_id`);

--
-- Indexy pre tabuľku `questions_pair`
--
ALTER TABLE `questions_pair`
  ADD PRIMARY KEY (`id`),
  ADD KEY `option_id` (`option_id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexy pre tabuľku `questions_simple`
--
ALTER TABLE `questions_simple`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexy pre tabuľku `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_code` (`student_code`);

--
-- Indexy pre tabuľku `submissions`
--
ALTER TABLE `submissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `test_id` (`test_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexy pre tabuľku `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`);

--
-- Indexy pre tabuľku `tests`
--
ALTER TABLE `tests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- AUTO_INCREMENT pre exportované tabuľky
--

--
-- AUTO_INCREMENT pre tabuľku `answers`
--
ALTER TABLE `answers`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pre tabuľku `answers_option`
--
ALTER TABLE `answers_option`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pre tabuľku `answers_pair`
--
ALTER TABLE `answers_pair`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pre tabuľku `answers_simple`
--
ALTER TABLE `answers_simple`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pre tabuľku `options`
--
ALTER TABLE `options`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pre tabuľku `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pre tabuľku `questions_option`
--
ALTER TABLE `questions_option`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pre tabuľku `questions_pair`
--
ALTER TABLE `questions_pair`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pre tabuľku `questions_simple`
--
ALTER TABLE `questions_simple`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pre tabuľku `students`
--
ALTER TABLE `students`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pre tabuľku `submissions`
--
ALTER TABLE `submissions`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pre tabuľku `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pre tabuľku `tests`
--
ALTER TABLE `tests`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Obmedzenie pre exportované tabuľky
--

--
-- Obmedzenie pre tabuľku `answers`
--
ALTER TABLE `answers`
  ADD CONSTRAINT `answers_ibfk_1` FOREIGN KEY (`submission_id`) REFERENCES `submissions` (`id`),
  ADD CONSTRAINT `answers_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`);

--
-- Obmedzenie pre tabuľku `answers_option`
--
ALTER TABLE `answers_option`
  ADD CONSTRAINT `answers_option_ibfk_1` FOREIGN KEY (`answer_id`) REFERENCES `answers` (`id`),
  ADD CONSTRAINT `answers_option_ibfk_2` FOREIGN KEY (`option_id`) REFERENCES `options` (`id`);

--
-- Obmedzenie pre tabuľku `answers_pair`
--
ALTER TABLE `answers_pair`
  ADD CONSTRAINT `answers_pair_ibfk_1` FOREIGN KEY (`answer_id`) REFERENCES `answers` (`id`),
  ADD CONSTRAINT `answers_pair_ibfk_2` FOREIGN KEY (`option_id`) REFERENCES `options` (`id`),
  ADD CONSTRAINT `answers_pair_ibfk_3` FOREIGN KEY (`question_pair_id`) REFERENCES `questions_pair` (`id`);

--
-- Obmedzenie pre tabuľku `answers_simple`
--
ALTER TABLE `answers_simple`
  ADD CONSTRAINT `answers_simple_ibfk_1` FOREIGN KEY (`answer_id`) REFERENCES `answers` (`id`);

--
-- Obmedzenie pre tabuľku `options`
--
ALTER TABLE `options`
  ADD CONSTRAINT `options_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`);

--
-- Obmedzenie pre tabuľku `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`test_id`) REFERENCES `tests` (`id`);

--
-- Obmedzenie pre tabuľku `questions_option`
--
ALTER TABLE `questions_option`
  ADD CONSTRAINT `questions_option_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`),
  ADD CONSTRAINT `questions_option_ibfk_2` FOREIGN KEY (`option_id`) REFERENCES `options` (`id`);

--
-- Obmedzenie pre tabuľku `questions_pair`
--
ALTER TABLE `questions_pair`
  ADD CONSTRAINT `questions_pair_ibfk_1` FOREIGN KEY (`option_id`) REFERENCES `options` (`id`),
  ADD CONSTRAINT `questions_pair_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`);

--
-- Obmedzenie pre tabuľku `questions_simple`
--
ALTER TABLE `questions_simple`
  ADD CONSTRAINT `questions_simple_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`);

--
-- Obmedzenie pre tabuľku `submissions`
--
ALTER TABLE `submissions`
  ADD CONSTRAINT `submissions_ibfk_1` FOREIGN KEY (`test_id`) REFERENCES `tests` (`id`),
  ADD CONSTRAINT `submissions_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`);

--
-- Obmedzenie pre tabuľku `tests`
--
ALTER TABLE `tests`
  ADD CONSTRAINT `tests_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
