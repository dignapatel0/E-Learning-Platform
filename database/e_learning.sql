-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 14, 2025 at 09:48 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `e_learning`
--

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `instructor_id` int(11) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `title`, `description`, `instructor_id`, `category`, `image`, `created_at`) VALUES
(1, 'Advanced Web Development', 'Comprehensive course on web technologies, focusing on HTML5, CSS3, JavaScript, and modern frameworks.', 1, 'Web Development', 'c1.png', '2025-04-13 18:08:28'),
(2, 'Database Management Systems', 'Learn about relational databases, SQL, and NoSQL solutions for managing large-scale data.', 3, 'Database', 'c3.png', '2025-04-13 18:08:28'),
(3, 'Back-End Development with Node.js', 'A deep dive into Node.js for building scalable and efficient back-end services with RESTful APIs.', 4, 'Back-End Development', 'c2.png', '2025-04-13 18:08:28'),
(4, 'Front-End Development with React', 'Master front-end development by learning React and its ecosystem for building modern web applications.', 2, 'Front-End Development', 'c4.png', '2025-04-13 18:08:28'),
(5, 'User Experience Design (IXD)', 'Learn the principles of interaction design, user research, and usability testing for web and mobile interfaces.', 5, 'IXD Design', 'c5.png', '2025-04-13 18:08:28');

-- --------------------------------------------------------

--
-- Table structure for table `instructors`
--

CREATE TABLE `instructors` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `bio` text DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `instructors`
--

INSERT INTO `instructors` (`id`, `name`, `bio`, `email`, `photo`) VALUES
(1, 'Adam Thomas', '<p>Expert in Web Development and Front-End Technologies with 10 years of industry experience</p>', 'adam.thomas@example.com', 'i1.jpeg'),
(2, 'Sean Doyal', '<p>Front-End Development expert with a passion for responsive web design and UX/UI principles</p>', 'sean@example.com', 'i3.png'),
(3, 'Matthew Bebis', '<p>Specializes in Database Systems, Data Structures, and Cloud Computing</p>', 'matthew@example.com', 'i2.png'),
(4, 'Christine Bittle', '<p>Experienced in Back-End Development and Software Architecture, with a focus on scalable systems</p>', 'christine@example.com', 'i5.png'),
(5, 'David Neumann', '<p>Interaction Design (IXD) specialist, focusing on user-centered design and usability testing</p>', 'david@example.com', 'i4.png');

-- --------------------------------------------------------

--
-- Table structure for table `lessons`
--

CREATE TABLE `lessons` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lessons`
--

INSERT INTO `lessons` (`id`, `course_id`, `title`, `video_url`, `content`, `duration`, `sort_order`) VALUES
(1, 1, 'Introduction to Web Development', 'https://www.youtube.com/watch?v=k5li3d_2U7c', '<p>Introduction to the fundamentals of web development and modern technologies.</p>', 30, 1),
(2, 2, 'Relational Databases Overview', 'https://www.youtube.com/watch?v=OqjJjpjDRLc', '<p>An overview of relational databases, normalization, and basic SQL queries.</p>', 45, 2),
(3, 3, 'Building APIs with Node.js', 'https://www.youtube.com/watch?v=b8ZUb_Okxro', '<p>A tutorial on building RESTful APIs using Node.js and Express.js.</p>', 60, 3),
(4, 4, 'React Basics - JSX and Components', 'https://www.youtube.com/watch?v=TyZQORWcquU', '<p>Learn the basics of React, JSX syntax, and creating functional components.</p>', 40, 4),
(5, 5, 'Usability Testing Methods in IXD', 'https://www.youtube.com/watch?v=EYUL0N1Fjhg', '<p>A lesson on usability testing methods and how to conduct user feedback sessions.</p>', 50, 5),
(6, 1, 'HTML Forms and Inputs', 'https://www.youtube.com/watch?v=2O8pkybH6po', '<p>Explore how to create forms and collect user data using HTML.</p>', 18, 6),
(7, 1, 'Semantic HTML', 'https://www.youtube.com/watch?app=desktop&v=kX3TfdUqpuU', '<p>Understand the use of semantic tags in HTML5 for accessibility and SEO.</p>', 20, 7);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `first` varchar(25) DEFAULT NULL,
  `last` varchar(25) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `active` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `dateAdded` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first`, `last`, `email`, `password`, `active`, `dateAdded`) VALUES
(1, 'Digna', 'Patel', 'digna@example.com', '5f4dcc3b5aa765d61d8327deb882cf99', 'Yes', '2025-04-13 14:06:26'),
(2, 'Jinalkumari', 'Patel', 'jinal@example.ca', '5f4dcc3b5aa765d61d8327deb882cf99', 'Yes', '2025-04-13 14:06:10');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `instructor_id` (`instructor_id`);

--
-- Indexes for table `instructors`
--
ALTER TABLE `instructors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lessons`
--
ALTER TABLE `lessons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `instructors`
--
ALTER TABLE `instructors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `lessons`
--
ALTER TABLE `lessons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `lessons`
--
ALTER TABLE `lessons`
  ADD CONSTRAINT `lessons_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
