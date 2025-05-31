-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3308
-- Generation Time: May 31, 2025 at 11:10 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `course_suggestion`
--

-- --------------------------------------------------------

--
-- Table structure for table `answers`
--

CREATE TABLE `answers` (
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `answer_text` varchar(255) NOT NULL,
  `score` int(11) NOT NULL,
  `category` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `answers`
--

INSERT INTO `answers` (`id`, `question_id`, `answer_text`, `score`, `category`) VALUES
(1, 1, 'Absolutely, I love being creative!', 3, 'creativity'),
(2, 1, 'Sometimes, when I feel inspired.', 2, 'creativity'),
(3, 1, 'Not really, creativity is not my thing.', 0, 'creativity'),
(4, 2, 'Yes, I enjoy building and fixing things.', 3, 'engineering'),
(5, 2, 'Sometimes, I like to tinker.', 2, 'engineering'),
(6, 2, 'No, I prefer not to work with machines.', 0, 'engineering'),
(7, 3, 'Very interested in people and social behavior.', 3, 'social'),
(8, 3, 'Sometimes, I find it intriguing.', 2, 'social'),
(9, 3, 'Not much, I prefer to be independent.', 0, 'social'),
(10, 4, 'I love scientific research and discovery.', 3, 'science'),
(11, 4, 'Sometimes, I am curious about science.', 2, 'science'),
(12, 4, 'Not really, science is not for me.', 0, 'science'),
(13, 5, 'Helping others with health is my passion.', 3, 'health'),
(14, 5, 'Sometimes, I care about wellbeing.', 2, 'health'),
(15, 5, 'No, health fields don’t interest me.', 0, 'health'),
(16, 6, 'I love music, theater, or performing arts.', 3, 'performance'),
(17, 6, 'Sometimes, I enjoy watching performances.', 2, 'performance'),
(18, 6, 'No, performing arts are not my thing.', 0, 'performance'),
(19, 7, 'I am very concerned about the environment.', 3, 'environment'),
(20, 7, 'Sometimes, I try to be eco-friendly.', 2, 'environment'),
(21, 7, 'Not much, environment is not my focus.', 0, 'environment'),
(22, 8, 'Technology and programming excite me.', 3, 'tech'),
(23, 8, 'Sometimes, I like to explore tech.', 2, 'tech'),
(24, 8, 'No, tech is not for me.', 0, 'tech'),
(25, 9, 'I feel drawn to teaching and educating others.', 3, 'teaching'),
(26, 9, 'Sometimes, I like to share knowledge.', 2, 'teaching'),
(27, 9, 'No, teaching is not my path.', 0, 'teaching'),
(28, 10, 'Law and justice interest me deeply.', 3, 'law'),
(29, 10, 'Sometimes, I follow legal and political news.', 2, 'law'),
(30, 10, 'No, law is not something I want to pursue.', 0, 'law'),
(31, 11, 'I want to start or manage a business.', 3, 'business'),
(32, 11, 'Sometimes, I like entrepreneurial ideas.', 2, 'business'),
(33, 11, 'No, business is not my interest.', 0, 'business'),
(34, 12, 'Human behavior and mind fascinate me.', 3, 'psychology'),
(35, 12, 'Sometimes, I think about how people think.', 2, 'psychology'),
(36, 12, 'No, psychology is not for me.', 0, 'psychology');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `course_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `tags` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `course_name`, `description`, `tags`) VALUES
(1, 'Computer Science', 'Focuses on algorithms, software, and problem-solving.', 'logic,tech'),
(2, 'Mechanical Engineering', 'Designing machines and systems.', 'engineering,logic'),
(3, 'Fine Arts', 'For students with strong creative and artistic skills.', 'creativity,art');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `question_text` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `question_text`) VALUES
(1, 'Do you enjoy creating or imagining new ideas or art?'),
(2, 'Are you interested in designing, building, or fixing things?'),
(3, 'Do you like understanding people and social behaviors?'),
(4, 'Are you curious about scientific research and experiments?'),
(5, 'Do you want to help improve people’s health and wellbeing?'),
(6, 'Are you passionate about music, theater, or performing arts?'),
(7, 'Are you concerned about environmental protection and nature?'),
(8, 'Do you enjoy working with computers, software, or technology?'),
(9, 'Do you feel drawn to teaching or sharing knowledge with others?'),
(10, 'Are you interested in law, justice, or governance?'),
(11, 'Do you want to start or run a business or manage projects?'),
(12, 'Are you fascinated by human behavior and mental processes?');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `answers`
--
ALTER TABLE `answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `answers`
--
ALTER TABLE `answers`
  ADD CONSTRAINT `answers_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
