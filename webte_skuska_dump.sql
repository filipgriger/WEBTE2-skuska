-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 12, 2021 at 03:51 PM
-- Server version: 8.0.23-0ubuntu0.20.04.1
-- PHP Version: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `skuska`
--

-- --------------------------------------------------------

--
-- Table structure for table `answers`
--

CREATE TABLE `answers` (
  `id` int UNSIGNED NOT NULL,
  `submission_id` int UNSIGNED DEFAULT NULL,
  `question_id` int UNSIGNED DEFAULT NULL,
  `points` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

--
-- Dumping data for table `answers`
--

INSERT INTO `answers` (`id`, `submission_id`, `question_id`, `points`) VALUES
(1, 1, 1, '1.00'),
(2, 1, 2, '0.00'),
(3, 3, 8, NULL),
(4, 3, 9, NULL),
(5, 4, 8, NULL),
(6, 4, 9, NULL),
(7, 5, 8, NULL),
(8, 5, 9, NULL),
(9, 6, 8, NULL),
(10, 6, 9, NULL),
(11, 7, 8, NULL),
(12, 7, 9, NULL),
(13, 8, 8, NULL),
(14, 8, 9, NULL),
(15, 9, 8, NULL),
(16, 9, 9, NULL),
(17, 10, 8, NULL),
(18, 10, 9, NULL),
(19, 11, 8, '20.00'),
(20, 11, 9, '40.00'),
(21, 12, 8, '0.00'),
(22, 12, 9, '0.00'),
(23, 13, 8, '0.00'),
(24, 13, 9, '0.00'),
(25, 14, 10, '0.00'),
(26, 14, 11, '0.00'),
(27, 15, 10, '0.00'),
(28, 15, 11, '0.00');

-- --------------------------------------------------------

--
-- Table structure for table `answers_image`
--

CREATE TABLE `answers_image` (
  `id` int UNSIGNED NOT NULL,
  `answer_id` int UNSIGNED NOT NULL,
  `answer` text COLLATE utf8mb4_slovak_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

--
-- Dumping data for table `answers_image`
--

INSERT INTO `answers_image` (`id`, `answer_id`, `answer`) VALUES
(1, 19, ''),
(2, 20, ''),
(3, 21, '8'),
(4, 22, '9'),
(5, 23, '8'),
(6, 24, '9'),
(7, 25, '10'),
(8, 26, '11'),
(9, 27, '10'),
(10, 28, '11');

-- --------------------------------------------------------

--
-- Table structure for table `answers_option`
--

CREATE TABLE `answers_option` (
  `id` int UNSIGNED NOT NULL,
  `answer_id` int UNSIGNED DEFAULT NULL,
  `option_id` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

-- --------------------------------------------------------

--
-- Table structure for table `answers_pair`
--

CREATE TABLE `answers_pair` (
  `id` int UNSIGNED NOT NULL,
  `answer_id` int UNSIGNED DEFAULT NULL,
  `question_pair_id` int UNSIGNED DEFAULT NULL,
  `option_id` int UNSIGNED DEFAULT NULL,
  `partial_points` decimal(4,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

-- --------------------------------------------------------

--
-- Table structure for table `answers_simple`
--

CREATE TABLE `answers_simple` (
  `id` int UNSIGNED NOT NULL,
  `answer_id` int UNSIGNED DEFAULT NULL,
  `answer` text CHARACTER SET utf8mb4 COLLATE utf8mb4_slovak_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

--
-- Dumping data for table `answers_simple`
--

INSERT INTO `answers_simple` (`id`, `answer_id`, `answer`) VALUES
(1, 1, '2021'),
(2, 2, '9');

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

CREATE TABLE `options` (
  `id` int UNSIGNED NOT NULL,
  `question_id` int UNSIGNED DEFAULT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_slovak_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

--
-- Dumping data for table `options`
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
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int UNSIGNED NOT NULL,
  `test_id` int UNSIGNED DEFAULT NULL,
  `question` text CHARACTER SET utf8mb4 COLLATE utf8mb4_slovak_ci,
  `type` enum('simple','option','pair','image') CHARACTER SET utf8mb4 COLLATE utf8mb4_slovak_ci DEFAULT NULL,
  `max_points` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `test_id`, `question`, `type`, `max_points`) VALUES
(1, 1, 'Aky je rok?', 'simple', 1),
(2, 1, '2+2=', 'simple', 1),
(3, 2, 'Aky je mesiac?', 'option', 2),
(4, 2, 'Ake je rocne obdobie?', 'option', 2),
(5, 3, 'Pospajaj', 'pair', 4),
(6, 3, 'Pospajaj', 'pair', 3),
(7, 4, 'asddd?', 'image', 62),
(8, 5, 'a?', 'image', 20),
(9, 5, 'b?', 'image', 40),
(10, 6, 'aa?', 'image', 20),
(11, 6, 'bb?', 'image', 60);

-- --------------------------------------------------------

--
-- Table structure for table `questions_image`
--

CREATE TABLE `questions_image` (
  `id` int UNSIGNED NOT NULL,
  `question_id` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

--
-- Dumping data for table `questions_image`
--

INSERT INTO `questions_image` (`id`, `question_id`) VALUES
(1, 10),
(2, 11);

-- --------------------------------------------------------

--
-- Table structure for table `questions_option`
--

CREATE TABLE `questions_option` (
  `id` int UNSIGNED NOT NULL,
  `question_id` int UNSIGNED DEFAULT NULL,
  `option_id` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

--
-- Dumping data for table `questions_option`
--

INSERT INTO `questions_option` (`id`, `question_id`, `option_id`) VALUES
(1, 3, 1),
(2, 4, 2);

-- --------------------------------------------------------

--
-- Table structure for table `questions_pair`
--

CREATE TABLE `questions_pair` (
  `id` int UNSIGNED NOT NULL,
  `question_id` int UNSIGNED DEFAULT NULL,
  `answer` text CHARACTER SET utf8mb4 COLLATE utf8mb4_slovak_ci,
  `option_id` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

--
-- Dumping data for table `questions_pair`
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
-- Table structure for table `questions_simple`
--

CREATE TABLE `questions_simple` (
  `id` int UNSIGNED NOT NULL,
  `question_id` int UNSIGNED DEFAULT NULL,
  `answer` text CHARACTER SET utf8mb4 COLLATE utf8mb4_slovak_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

--
-- Dumping data for table `questions_simple`
--

INSERT INTO `questions_simple` (`id`, `question_id`, `answer`) VALUES
(1, 1, '2021'),
(2, 2, '4');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_slovak_ci DEFAULT NULL,
  `surname` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_slovak_ci DEFAULT NULL,
  `student_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_slovak_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `name`, `surname`, `student_code`) VALUES
(1, 'asd', 'fgh', '222'),
(2, '222', '333', '22'),
(3, '222', '333', '223'),
(4, 'asd', 'df', '2333'),
(5, 'asd', 'fgh', '33333'),
(6, 'fsaf', 'fsafas', '2332'),
(7, 'asd', 'fgh', '546456'),
(8, 'asd', 'fff', '1232131312'),
(9, 'gergrt', 'asds', '123'),
(10, '12312312', '343123123', '23131231');

-- --------------------------------------------------------

--
-- Table structure for table `submissions`
--

CREATE TABLE `submissions` (
  `id` int UNSIGNED NOT NULL,
  `student_id` int UNSIGNED DEFAULT NULL,
  `test_id` int UNSIGNED DEFAULT NULL,
  `total_points` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

--
-- Dumping data for table `submissions`
--

INSERT INTO `submissions` (`id`, `student_id`, `test_id`, `total_points`, `created_at`) VALUES
(1, 1, 1, '1.00', '2021-05-12 12:40:48'),
(2, 4, 5, NULL, '2021-05-12 15:01:27'),
(3, 4, 5, NULL, '2021-05-12 15:02:53'),
(4, 4, 5, NULL, '2021-05-12 15:05:03'),
(5, 4, 5, NULL, '2021-05-12 15:06:14'),
(6, 4, 5, NULL, '2021-05-12 15:07:06'),
(7, 4, 5, NULL, '2021-05-12 15:08:03'),
(8, 4, 5, NULL, '2021-05-12 15:11:30'),
(9, 4, 5, '0.00', '2021-05-12 15:13:41'),
(10, 5, 5, '0.00', '2021-05-12 15:17:46'),
(11, 6, 5, '60.00', '2021-05-12 15:23:43'),
(12, 7, 5, '0.00', '2021-05-12 15:25:10'),
(13, 8, 5, '0.00', '2021-05-12 15:39:56'),
(14, 9, 6, '0.00', '2021-05-12 15:47:53'),
(15, 10, 6, '0.00', '2021-05-12 15:48:46');

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_slovak_ci DEFAULT NULL,
  `surname` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_slovak_ci DEFAULT NULL,
  `email` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_slovak_ci DEFAULT NULL,
  `password` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_slovak_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`id`, `name`, `surname`, `email`, `password`) VALUES
(1, 'Janko', 'Hrasko', 'janko@hrasko.com', '$2y$10$BNhfZT1c45VBMbUmCi3A/uaaCiMiBXCWKJ/xn8t6FIrUPP7pbLfHW');

-- --------------------------------------------------------

--
-- Table structure for table `tests`
--

CREATE TABLE `tests` (
  `id` int UNSIGNED NOT NULL,
  `code` varchar(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_slovak_ci NOT NULL,
  `teacher_id` int UNSIGNED DEFAULT NULL,
  `total_points` int DEFAULT NULL,
  `active` tinyint DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

--
-- Dumping data for table `tests`
--

INSERT INTO `tests` (`id`, `code`, `teacher_id`, `total_points`, `active`, `created_at`) VALUES
(1, 'simple', 1, 2, 1, '2021-05-12 09:36:51'),
(2, 'option', 1, 4, 0, '2021-05-12 09:37:51'),
(3, 'pairs1', 1, 7, 0, '2021-05-12 09:39:08'),
(4, 'asd', 1, 62, 0, '2021-05-12 12:44:39'),
(5, 'ff', 1, 60, 0, '2021-05-12 13:01:41'),
(6, 'dd', 1, 80, 1, '2021-05-12 15:47:33');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`),
  ADD KEY `submission_id` (`submission_id`) USING BTREE;

--
-- Indexes for table `answers_image`
--
ALTER TABLE `answers_image`
  ADD PRIMARY KEY (`id`),
  ADD KEY `answer_id` (`answer_id`);

--
-- Indexes for table `answers_option`
--
ALTER TABLE `answers_option`
  ADD PRIMARY KEY (`id`),
  ADD KEY `answer_id` (`answer_id`),
  ADD KEY `option_id` (`option_id`);

--
-- Indexes for table `answers_pair`
--
ALTER TABLE `answers_pair`
  ADD PRIMARY KEY (`id`),
  ADD KEY `answer_id` (`answer_id`),
  ADD KEY `option_id` (`option_id`),
  ADD KEY `question_pair_id` (`question_pair_id`);

--
-- Indexes for table `answers_simple`
--
ALTER TABLE `answers_simple`
  ADD PRIMARY KEY (`id`),
  ADD KEY `answer_id` (`answer_id`);

--
-- Indexes for table `options`
--
ALTER TABLE `options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `test_id` (`test_id`);

--
-- Indexes for table `questions_image`
--
ALTER TABLE `questions_image`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `questions_option`
--
ALTER TABLE `questions_option`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`),
  ADD KEY `option_id` (`option_id`);

--
-- Indexes for table `questions_pair`
--
ALTER TABLE `questions_pair`
  ADD PRIMARY KEY (`id`),
  ADD KEY `option_id` (`option_id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `questions_simple`
--
ALTER TABLE `questions_simple`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_code` (`student_code`);

--
-- Indexes for table `submissions`
--
ALTER TABLE `submissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `test_id` (`test_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tests`
--
ALTER TABLE `tests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `answers`
--
ALTER TABLE `answers`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `answers_image`
--
ALTER TABLE `answers_image`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `answers_option`
--
ALTER TABLE `answers_option`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `answers_pair`
--
ALTER TABLE `answers_pair`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `answers_simple`
--
ALTER TABLE `answers_simple`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `options`
--
ALTER TABLE `options`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `questions_image`
--
ALTER TABLE `questions_image`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `questions_option`
--
ALTER TABLE `questions_option`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `questions_pair`
--
ALTER TABLE `questions_pair`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `questions_simple`
--
ALTER TABLE `questions_simple`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `submissions`
--
ALTER TABLE `submissions`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tests`
--
ALTER TABLE `tests`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `answers`
--
ALTER TABLE `answers`
  ADD CONSTRAINT `answers_ibfk_1` FOREIGN KEY (`submission_id`) REFERENCES `submissions` (`id`),
  ADD CONSTRAINT `answers_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`);

--
-- Constraints for table `answers_image`
--
ALTER TABLE `answers_image`
  ADD CONSTRAINT `answers_image_ibfk_1` FOREIGN KEY (`answer_id`) REFERENCES `answers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `answers_option`
--
ALTER TABLE `answers_option`
  ADD CONSTRAINT `answers_option_ibfk_1` FOREIGN KEY (`answer_id`) REFERENCES `answers` (`id`),
  ADD CONSTRAINT `answers_option_ibfk_2` FOREIGN KEY (`option_id`) REFERENCES `options` (`id`);

--
-- Constraints for table `answers_pair`
--
ALTER TABLE `answers_pair`
  ADD CONSTRAINT `answers_pair_ibfk_1` FOREIGN KEY (`answer_id`) REFERENCES `answers` (`id`),
  ADD CONSTRAINT `answers_pair_ibfk_2` FOREIGN KEY (`option_id`) REFERENCES `options` (`id`),
  ADD CONSTRAINT `answers_pair_ibfk_3` FOREIGN KEY (`question_pair_id`) REFERENCES `questions_pair` (`id`);

--
-- Constraints for table `answers_simple`
--
ALTER TABLE `answers_simple`
  ADD CONSTRAINT `answers_simple_ibfk_1` FOREIGN KEY (`answer_id`) REFERENCES `answers` (`id`);

--
-- Constraints for table `options`
--
ALTER TABLE `options`
  ADD CONSTRAINT `options_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`);

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`test_id`) REFERENCES `tests` (`id`);

--
-- Constraints for table `questions_image`
--
ALTER TABLE `questions_image`
  ADD CONSTRAINT `questions_image_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `questions_option`
--
ALTER TABLE `questions_option`
  ADD CONSTRAINT `questions_option_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`),
  ADD CONSTRAINT `questions_option_ibfk_2` FOREIGN KEY (`option_id`) REFERENCES `options` (`id`);

--
-- Constraints for table `questions_pair`
--
ALTER TABLE `questions_pair`
  ADD CONSTRAINT `questions_pair_ibfk_1` FOREIGN KEY (`option_id`) REFERENCES `options` (`id`),
  ADD CONSTRAINT `questions_pair_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`);

--
-- Constraints for table `questions_simple`
--
ALTER TABLE `questions_simple`
  ADD CONSTRAINT `questions_simple_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`);

--
-- Constraints for table `submissions`
--
ALTER TABLE `submissions`
  ADD CONSTRAINT `submissions_ibfk_1` FOREIGN KEY (`test_id`) REFERENCES `tests` (`id`),
  ADD CONSTRAINT `submissions_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`);

--
-- Constraints for table `tests`
--
ALTER TABLE `tests`
  ADD CONSTRAINT `tests_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
