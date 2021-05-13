-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 13, 2021 at 03:43 PM
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

DROP TABLE IF EXISTS `answers`;
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
(1, 1, 16, NULL),
(2, 1, 17, NULL),
(3, 2, 10, '2.00'),
(4, 2, 11, '2.00'),
(5, 2, 12, '0.00'),
(6, 2, 13, '2.00'),
(7, 2, 14, '2.10'),
(8, 2, 15, '3.10'),
(9, 3, 10, '2.00'),
(10, 3, 11, '2.00'),
(11, 3, 12, '0.00'),
(12, 3, 13, '0.00'),
(13, 3, 14, '19.50'),
(14, 3, 15, '15.20');

-- --------------------------------------------------------

--
-- Table structure for table `answers_image`
--

DROP TABLE IF EXISTS `answers_image`;
CREATE TABLE `answers_image` (
  `id` int UNSIGNED NOT NULL,
  `answer_id` int UNSIGNED NOT NULL,
  `image_url` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_slovak_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

--
-- Dumping data for table `answers_image`
--

INSERT INTO `answers_image` (`id`, `answer_id`, `image_url`) VALUES
(1, 1, '/images/submissions/students/3/question-16.png'),
(2, 2, '/images/submissions/students/3/question-17.png'),
(3, 7, '/images/submissions/students/4/question-14.png'),
(4, 8, '/images/submissions/students/4/question-15.png'),
(5, 13, '/images/submissions/students/6/question-14.png'),
(6, 14, '/images/submissions/students/6/question-15.png');

-- --------------------------------------------------------

--
-- Table structure for table `answers_option`
--

DROP TABLE IF EXISTS `answers_option`;
CREATE TABLE `answers_option` (
  `id` int UNSIGNED NOT NULL,
  `answer_id` int UNSIGNED DEFAULT NULL,
  `option_id` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

--
-- Dumping data for table `answers_option`
--

INSERT INTO `answers_option` (`id`, `answer_id`, `option_id`) VALUES
(1, 5, 19),
(2, 11, 20);

-- --------------------------------------------------------

--
-- Table structure for table `answers_pair`
--

DROP TABLE IF EXISTS `answers_pair`;
CREATE TABLE `answers_pair` (
  `id` int UNSIGNED NOT NULL,
  `answer_id` int UNSIGNED DEFAULT NULL,
  `question_pair_id` int UNSIGNED DEFAULT NULL,
  `option_id` int UNSIGNED DEFAULT NULL,
  `partial_points` decimal(4,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

--
-- Dumping data for table `answers_pair`
--

INSERT INTO `answers_pair` (`id`, `answer_id`, `question_pair_id`, `option_id`, `partial_points`) VALUES
(1, 6, 8, 21, '1.00'),
(2, 6, 9, 22, '1.00'),
(3, 12, 8, 22, '0.00'),
(4, 12, 9, 21, '0.00');

-- --------------------------------------------------------

--
-- Table structure for table `answers_simple`
--

DROP TABLE IF EXISTS `answers_simple`;
CREATE TABLE `answers_simple` (
  `id` int UNSIGNED NOT NULL,
  `answer_id` int UNSIGNED DEFAULT NULL,
  `answer` text CHARACTER SET utf8mb4 COLLATE utf8mb4_slovak_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

--
-- Dumping data for table `answers_simple`
--

INSERT INTO `answers_simple` (`id`, `answer_id`, `answer`) VALUES
(1, 3, 'a'),
(2, 4, 'b'),
(3, 9, 'a'),
(4, 10, 'b');

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

DROP TABLE IF EXISTS `options`;
CREATE TABLE `options` (
  `id` int UNSIGNED NOT NULL,
  `question_id` int UNSIGNED DEFAULT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_slovak_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

--
-- Dumping data for table `options`
--

INSERT INTO `options` (`id`, `question_id`, `value`) VALUES
(1, 3, 'Maj'),
(2, 3, 'januar'),
(3, 3, 'februar'),
(4, 3, 'marec'),
(5, 3, 'april'),
(6, 4, 'marec'),
(7, 4, 'jun'),
(8, 4, 'septeber'),
(9, 4, 'december'),
(10, 7, '3'),
(11, 7, '1'),
(12, 7, '2'),
(13, 7, '4'),
(14, 7, '5'),
(15, 8, 'Felicia'),
(16, 8, 'Passat'),
(17, 8, 'Enzo'),
(18, 12, 'yes'),
(19, 12, 'no'),
(20, 12, 'nope'),
(21, 13, 'b'),
(22, 13, 'd');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

DROP TABLE IF EXISTS `questions`;
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
(1, 1, 'Aky je rok?', 'simple', 2),
(2, 1, 'Aky je mesiac? (slovne bez diakritiky)', 'simple', 2),
(3, 2, 'Aky je mesiac?', 'option', 2),
(4, 3, 'Prirad', 'pair', 4),
(5, 4, 'Nakresli domcek', 'image', 10),
(6, 5, '5+5', 'simple', 1),
(7, 5, '2*3/2', 'option', 2),
(8, 5, 'Prirad', 'pair', 6),
(9, 5, 'Nakresli domcek jednym tahom', 'image', 7),
(10, 6, 'a?', 'simple', 2),
(11, 6, 'b?', 'simple', 2),
(12, 6, 'yes?', 'option', 4),
(13, 6, 'asd?', 'pair', 2),
(14, 6, 'draw me', 'image', 20),
(15, 6, 'draw you', 'image', 20),
(16, 7, 'aa', 'image', 2),
(17, 7, 'bb', 'image', 2);

-- --------------------------------------------------------

--
-- Table structure for table `questions_image`
--

DROP TABLE IF EXISTS `questions_image`;
CREATE TABLE `questions_image` (
  `id` int UNSIGNED NOT NULL,
  `question_id` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

--
-- Dumping data for table `questions_image`
--

INSERT INTO `questions_image` (`id`, `question_id`) VALUES
(1, 5),
(2, 9),
(3, 14),
(4, 15),
(5, 16),
(6, 17);

-- --------------------------------------------------------

--
-- Table structure for table `questions_option`
--

DROP TABLE IF EXISTS `questions_option`;
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
(2, 7, 10),
(3, 12, 18);

-- --------------------------------------------------------

--
-- Table structure for table `questions_pair`
--

DROP TABLE IF EXISTS `questions_pair`;
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
(1, 4, 'jar', 6),
(2, 4, 'leto', 7),
(3, 4, 'jesen', 8),
(4, 4, 'zima', 9),
(5, 8, 'Skoda', 15),
(6, 8, 'Volkswagen', 16),
(7, 8, 'Ferrari', 17),
(8, 13, 'a', 21),
(9, 13, 'c', 22);

-- --------------------------------------------------------

--
-- Table structure for table `questions_simple`
--

DROP TABLE IF EXISTS `questions_simple`;
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
(2, 2, 'maj'),
(3, 6, '10'),
(4, 10, 'a'),
(5, 11, 'b');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
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
(1, 'asd', 'fgh', '11'),
(2, 'aa', 'bb', '22'),
(3, 'asd', '12', '33'),
(4, 'sd', 'ds', '212121'),
(5, 'asd', 'dsada', '32131321'),
(6, '3', '33', '4235434534'),
(7, 'fahk', 'iuhfeg', '4684'),
(8, 'hrth', 'htrhtr', '2223213'),
(9, 'efe', 'gewg', '222'),
(10, 'grhr', 'rthrthjtr', '543534'),
(11, '222', '333', '666');

-- --------------------------------------------------------

--
-- Table structure for table `students_status`
--

DROP TABLE IF EXISTS `students_status`;
CREATE TABLE `students_status` (
  `id` int UNSIGNED NOT NULL,
  `student_id` int UNSIGNED NOT NULL,
  `test_id` int UNSIGNED NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

--
-- Dumping data for table `students_status`
--

INSERT INTO `students_status` (`id`, `student_id`, `test_id`, `status`) VALUES
(26, 9, 6, 1),
(32, 10, 6, 1),
(39, 11, 6, 1);

-- --------------------------------------------------------

--
-- Table structure for table `submissions`
--

DROP TABLE IF EXISTS `submissions`;
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
(1, 3, 7, NULL, '2021-05-13 12:20:19'),
(2, 4, 6, '11.20', '2021-05-13 12:29:17'),
(3, 6, 6, '38.70', '2021-05-13 12:41:25');

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

DROP TABLE IF EXISTS `teachers`;
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
(1, 'Janko', 'Hrasko', 'janko@hrasko.com', '$2y$10$IUDuzMkOFrEcGkLN.MB.5uWyUMJLYMU5uKMPwh030Ux7AzoSld42W');

-- --------------------------------------------------------

--
-- Table structure for table `tests`
--

DROP TABLE IF EXISTS `tests`;
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
(1, 'simple', 1, 4, 1, '2021-05-13 10:37:39'),
(2, 'option', 1, 2, 1, '2021-05-13 10:38:21'),
(3, 'pairs1', 1, 4, 1, '2021-05-13 10:39:05'),
(4, 'image1', 1, 10, 1, '2021-05-13 10:39:24'),
(5, 'tstAll', 1, 16, 1, '2021-05-13 10:40:47'),
(6, 'aa', 1, 50, 1, '2021-05-13 12:14:46'),
(7, 'bbc', 1, 4, 1, '2021-05-13 12:15:53');

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
-- Indexes for table `students_status`
--
ALTER TABLE `students_status`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_id` (`student_id`,`test_id`),
  ADD KEY `test_id` (`test_id`);

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
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `answers_image`
--
ALTER TABLE `answers_image`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `answers_option`
--
ALTER TABLE `answers_option`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `answers_pair`
--
ALTER TABLE `answers_pair`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `answers_simple`
--
ALTER TABLE `answers_simple`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `options`
--
ALTER TABLE `options`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `questions_image`
--
ALTER TABLE `questions_image`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `questions_option`
--
ALTER TABLE `questions_option`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `questions_pair`
--
ALTER TABLE `questions_pair`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `questions_simple`
--
ALTER TABLE `questions_simple`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `students_status`
--
ALTER TABLE `students_status`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `submissions`
--
ALTER TABLE `submissions`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tests`
--
ALTER TABLE `tests`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
-- Constraints for table `students_status`
--
ALTER TABLE `students_status`
  ADD CONSTRAINT `students_status_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `students_status_ibfk_2` FOREIGN KEY (`test_id`) REFERENCES `tests` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

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
