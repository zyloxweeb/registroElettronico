-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Apr 28, 2024 alle 13:20
-- Versione del server: 10.4.28-MariaDB
-- Versione PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `registro`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `classes`
--

CREATE TABLE `classes` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `classes`
--

INSERT INTO `classes` (`id`, `name`) VALUES
(1, '5BIA'),
(2, '5AIA'),
(3, '3BIA'),
(4, '4AET');

-- --------------------------------------------------------

--
-- Struttura della tabella `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `courses`
--

INSERT INTO `courses` (`id`, `name`, `subject`, `description`) VALUES
(1, 'Sistemi e Reti', 'Sistemi e Reti', NULL),
(2, 'GPOI', 'GPOI', NULL),
(3, 'Italiano', 'Italiano', NULL),
(4, 'Matematica', 'Matematica', NULL),
(5, 'TIPSIT', 'TIPSIT', NULL),
(6, 'Informatica', 'Informatica', NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `grades`
--

CREATE TABLE `grades` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `grade` decimal(4,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `grades`
--

INSERT INTO `grades` (`id`, `student_id`, `teacher_id`, `course_id`, `grade`) VALUES
(9, 11, NULL, 5, 7.00),
(10, 11, NULL, 5, 6.00),
(11, 24, NULL, 5, 9.00),
(12, 11, NULL, 1, 9.00),
(13, 24, NULL, 1, 4.00);

-- --------------------------------------------------------

--
-- Struttura della tabella `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `class` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `students`
--

INSERT INTO `students` (`id`, `user_id`, `name`, `class`) VALUES
(11, NULL, 'Candeago', '5BIA'),
(12, NULL, 'Corso', '5BIA'),
(13, NULL, 'De Martin', '5BIA'),
(14, NULL, 'De Riva', '5BIA'),
(15, NULL, 'Fattor', '5BIA'),
(16, NULL, 'Giglio', '5BIA'),
(17, NULL, 'Giolai', '5BIA'),
(18, NULL, 'Pais', '5BIA'),
(19, NULL, 'Sossai', '5BIA'),
(20, NULL, 'Topinelli', '5BIA'),
(21, NULL, 'Rel', '5BIA'),
(22, NULL, 'Zhu', '5BIA'),
(23, NULL, 'Dal Molin', '5AIA'),
(24, NULL, 'Bissoli', '5AIA'),
(29, NULL, 'Dal Molin', '5AIA');

-- --------------------------------------------------------

--
-- Struttura della tabella `teachers`
--

CREATE TABLE `teachers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `subject` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `teachers`
--

INSERT INTO `teachers` (`id`, `user_id`, `name`, `subject`) VALUES
(25, NULL, 'Piazza', 'Sistemi e Reti'),
(26, NULL, 'Pinto', 'GPOI'),
(27, NULL, 'Franco', 'Italiano'),
(28, NULL, 'Iarabek', 'Matematica'),
(30, NULL, 'D Archivio', 'TIPSIT'),
(31, NULL, 'Bua Corona', 'Informatica');

-- --------------------------------------------------------

--
-- Struttura della tabella `teacher_classes`
--

CREATE TABLE `teacher_classes` (
  `id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `teacher_classes`
--

INSERT INTO `teacher_classes` (`id`, `teacher_id`, `class_id`) VALUES
(3, 31, 1),
(4, 25, 1),
(5, 26, 1),
(6, 26, 2),
(7, 30, 1),
(8, 25, 2),
(9, 30, 2);

-- --------------------------------------------------------

--
-- Struttura della tabella `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('student','teacher','administration') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'preside', '$2y$10$.3k1tKrIbvGKqlRHLkKfzuTatLLWQNghbeheGKfrdfzhjlz8Rwarq', 'administration'),
(11, 'Candeago', '$2y$10$ZzrcTGsuuFbbrRY/PvBZ1ug5CgZCIgx8iFVzOjDoSLMS9N0Nvamca', 'student'),
(12, 'Corso', '$2y$10$M4slSdlXvn4G/a/RHsgnsOa28ElKqGaMtikAjTaHaTH6wInZsnzyK', 'student'),
(13, 'De Martin', '$2y$10$ESM4VG135Pav8h/y/aNsP.eMxatj0AL0o3ugQ.Ny0QoFO5MyTlXjy', 'student'),
(14, 'De Riva', '$2y$10$vfooTF7MvFiVY2zLaeuH4OtG9cWNPjzdhx0d9gtREJgxVBRUVhmJ2', 'student'),
(15, 'Fattor', '$2y$10$1fH4h2JO061qHLqT6a2EOudvu7qgXzd8zmQXV/0Hm6x0jmPgJQ1Z6', 'student'),
(16, 'Giglio', '$2y$10$1HPTJ57IGUsKBIDNCjoE.eBC1soLnRyrXKgFr/hecLEPejOqPdwRq', 'student'),
(17, 'Giolai', '$2y$10$tL6RFTzfi9Ey4mkRKggIGeJ7.DV/JihNWRmbrnG7XsEegUvfxJ1cO', 'student'),
(18, 'Pais', '$2y$10$g0qTXOMxb/lniQVWPCrOg.GQDGvJpasePr8hC3NtKg2zP1JGddVam', 'student'),
(19, 'Sossai', '$2y$10$yUJpzh.dy1JbWxvx8eoaquzA/mZXAujnbRWInnD1YS6tolY00C/qa', 'student'),
(20, 'Topinelli', '$2y$10$ygl5o1OAx1PqOobpbLdqjeTjuDO/wFuv1pWotZUG6r3hmMfLOWlj.', 'student'),
(21, 'Rel', '$2y$10$li7U/iDQakZkVydd.0QVrO1M5fLKi4vubdj626G3jj/.eJZN970Da', 'student'),
(22, 'Zhu', '$2y$10$Suqq0DSdqYRxofaaEBa3qO/rJGckPcC.MHyJmnfP17guWlaaNgwHW', 'student'),
(23, 'Dal Molin', '$2y$10$956JiACPVDr3GSiUxIt7ZuWo3qTp3mxZjii2PZSLFdx2dBgiuCu3C', 'student'),
(24, 'Bissoli', '$2y$10$O30QvlUQh9i6vjaPq4MUIOHrszNprW7OwezXvAQVYEeBjDjBfJRR.', 'student'),
(25, 'Piazza', '$2y$10$fY5rgzA7kJxubn0dJPHBsOHi8FTgqzdUOSyPl2/xT74T7qUJ/RcBi', 'teacher'),
(26, 'Pinto', '$2y$10$uOB8ifhfbgll.rxMSi6zIuD0m.14831DwTg9KJte2kSDqDqvV1h3C', 'teacher'),
(27, 'Franco', '$2y$10$PtnxJn4VXJY/TumBD24yhu1MQjnl/lSTRkqnja0JBHQ8hZfZCwbc2', 'teacher'),
(28, 'Iarabek', '$2y$10$onNHfcd.1UpdrihWnhQeleEAlTEF98pY8QP5JiqE0pY5wlywIsPKu', 'teacher'),
(29, 'Dal Molin', '$2y$10$wpvLXOKhBEBtHEGtmNhfVOPRconu1SsRLyPYEpDvBPHFH9fH2K.RW', 'student'),
(30, 'D Archivio', '$2y$10$abCpwLiDaqd.MxxI9PJCo.qS6RmipCnozI/JbWF5OFEtvV5924S96', 'teacher'),
(31, 'Bua Corona', '$2y$10$Qzi8AmhN/4SVyhOfGNDo.OFJWvvJPyMAiF.IPNxbgjj6lAliIDDfO', 'teacher');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indici per le tabelle `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indici per le tabelle `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indici per le tabelle `teacher_classes`
--
ALTER TABLE `teacher_classes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indici per le tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `classes`
--
ALTER TABLE `classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT per la tabella `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT per la tabella `grades`
--
ALTER TABLE `grades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT per la tabella `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT per la tabella `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT per la tabella `teacher_classes`
--
ALTER TABLE `teacher_classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT per la tabella `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `grades`
--
ALTER TABLE `grades`
  ADD CONSTRAINT `grades_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`),
  ADD CONSTRAINT `grades_ibfk_2` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`),
  ADD CONSTRAINT `grades_ibfk_3` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`);

--
-- Limiti per la tabella `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`id`) REFERENCES `users` (`id`);

--
-- Limiti per la tabella `teachers`
--
ALTER TABLE `teachers`
  ADD CONSTRAINT `teachers_ibfk_1` FOREIGN KEY (`id`) REFERENCES `users` (`id`);

--
-- Limiti per la tabella `teacher_classes`
--
ALTER TABLE `teacher_classes`
  ADD CONSTRAINT `teacher_classes_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`),
  ADD CONSTRAINT `teacher_classes_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
