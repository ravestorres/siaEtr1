-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 08, 2025 at 04:04 PM
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
-- Database: `freelance_platform`
--

-- --------------------------------------------------------

--
-- Table structure for table `contracts`
--

CREATE TABLE `contracts` (
  `id` int(11) NOT NULL,
  `proposal_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `terms` text NOT NULL,
  `status` enum('active','completed','cancelled') DEFAULT 'active',
  `logo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `requirements` text DEFAULT NULL,
  `budget` decimal(10,2) NOT NULL,
  `status` enum('open','in_progress','closed') DEFAULT 'open',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `address` text NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `logo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `client_id`, `title`, `description`, `requirements`, `budget`, `status`, `created_at`, `address`, `company_name`, `position`, `logo`) VALUES
(11, 20, 'Web Developer (Remote)', 'We are looking for a skilled web developer to join our team. The successful candidate will be responsible for developing, maintaining, and improving websites and web applications. You&#039;ll work on exciting projects, ensuring high-quality code, and collaborate with team members to meet client requirements. The position requires expertise in front-end and back-end development, troubleshooting issues, and creating seamless user experiences.', 'The ideal candidate will have strong proficiency in HTML, CSS, JavaScript, and experience with modern frameworks like React, Angular, or Vue.js. Experience with back-end technologies such as Node.js, PHP, or Python is necessary. Familiarity with databases (MySQL, MongoDB) and RESTful APIs is preferred. Excellent problem-solving, communication skills, and the ability to work independently and in a team are essential.', 5000.00, 'open', '2025-01-06 16:38:57', 'Urdaneta, Pangasinan', '', '', NULL),
(12, 22, 'Graphic Designer Wanted for Freelance Project', 'We are looking for a talented and creative graphic designer to assist with a variety of design projects, including branding, logo design, social media graphics, marketing materials, and more. The ideal candidate will be proficient in design software like Adobe Illustrator, Photoshop, and InDesign, with an eye for detail and a strong sense of aesthetic. You will work closely with our team to bring our brand vision to life and ensure all designs align with our brand identity. This is a freelance position with the possibility of future work based on performance.', 'Applicants must have at least 2 years of professional graphic design experience, a strong portfolio showcasing relevant work, and proficiency in Adobe Creative Suite. A good understanding of design principles and the ability to work independently and meet deadlines is required. Previous experience with both print and digital design is preferred.', 25000.00, 'open', '2025-01-06 17:09:01', 'Sta Maria, Pangasinan', '', '', NULL),
(13, 22, 'Web Developer ', 'We are looking for a talented and motivated software developer to join our growing team. In this role, you will collaborate with cross-functional teams to design, develop, and maintain high-quality software solutions. You will be involved in all stages of the software development lifecycle, including requirement analysis, design, coding, testing, and deployment. As part of an agile team, you&#039;ll contribute to the development of scalable, efficient, and reliable applications. This is an exciting opportunity for someone who enjoys working with cutting-edge technologies and is passionate about creating innovative solutions that have a real impact.', 'The ideal candidate will have a Bachelor’s degree in Computer Science, Software Engineering, or a related field, or equivalent work experience. You should have proven experience in programming languages such as [Java, Python, JavaScript, etc.], and be familiar with both front-end technologies like [React, Angular, etc.] and back-end frameworks such as [Spring, Django, Node.js, etc.]. Strong experience with databases (SQL and NoSQL), cloud platforms (AWS, Azure, etc.), and version control systems like Git is also required. You should possess solid problem-solving skills and the ability to work effectively in both independent and collaborative environments. Additionally, knowledge of Agile methodologies and tools such as Jira or Trello is important. The role demands excellent communication skills, with the ability to articulate technical concepts to non-technical stakeholders.', 2500.00, 'open', '2025-01-08 13:29:23', 'Rosales, Pangasinan', 'NexaWorks', 'Web Developer', 'uploads/logos/677e7db3133c0_web-logo-developer-development-icon-dev-or-programmer-logo-vector.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `proposals`
--

CREATE TABLE `proposals` (
  `id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `freelancer_id` int(11) NOT NULL,
  `proposal_details` text NOT NULL,
  `proposed_rate` decimal(10,2) DEFAULT NULL,
  `status` enum('pending','accepted','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `attachment` varchar(255) DEFAULT NULL,
  `profile_highlight` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `proposals`
--

INSERT INTO `proposals` (`id`, `job_id`, `freelancer_id`, `proposal_details`, `proposed_rate`, `status`, `created_at`, `attachment`, `profile_highlight`) VALUES
(20, 11, 21, 'I have worked on a wide variety of web development projects, from small business websites to large-scale platforms. My skills in HTML, CSS, JavaScript, and frameworks such as React, Angular, and Laravel allow me to tailor solutions to meet your specific needs. Additionally, I am well-versed in website performance optimization, SEO best practices, and ensuring a seamless user experience across all devices.', 1000.00, 'pending', '2025-01-06 17:29:31', NULL, NULL),
(21, 12, 21, 'With over 12 years of experience in graphic design, I have developed a strong portfolio that includes logo design, branding, marketing materials, social media graphics, and more. I am proficient in Adobe Illustrator, Photoshop, and InDesign, and I specialize in both digital and print design. I have worked with various clients across industries, delivering designs that help elevate their brands and effectively communicate their messages.', 15000.00, 'pending', '2025-01-06 17:45:38', NULL, NULL),
(22, 13, 21, 'dwadw', 12223.00, 'pending', '2025-01-08 13:59:04', NULL, NULL),
(23, 12, 21, 'assss', 123.00, 'pending', '2025-01-08 14:12:51', 'uploads/DisasterRecoveryPlanTemplate.org-Disaster-Recovery-Plan-Sample (1).pdf', 'uploads/profile_highlights/DRP.pdf'),
(24, 12, 21, 'aaa', 1111.00, 'pending', '2025-01-08 14:14:11', 'uploads/DisasterRecoveryPlanTemplate.org-Disaster-Recovery-Plan-Sample (1).pdf', 'uploads/profile_highlights/DRP.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `id` int(11) NOT NULL,
  `freelancer_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `rating_comments` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ratings`
--

INSERT INTO `ratings` (`id`, `freelancer_id`, `user_id`, `rating`, `rating_comments`, `created_at`) VALUES
(1, 21, 22, 5, 'awdwad', '2025-01-08 15:01:14'),
(2, 21, 22, 5, 'dwadwad', '2025-01-08 15:01:20');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `freelancer_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `report_reason` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `freelancer_id`, `user_id`, `report_reason`, `created_at`) VALUES
(1, 23, 22, 'wdsaw', '2025-01-08 15:02:37');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `reviewer_id` int(11) NOT NULL,
  `reviewee_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `saved_jobs`
--

CREATE TABLE `saved_jobs` (
  `id` int(11) NOT NULL,
  `freelancer_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `saved_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `saved_jobs`
--

INSERT INTO `saved_jobs` (`id`, `freelancer_id`, `job_id`, `saved_at`) VALUES
(52, 23, 11, '2025-01-06 17:11:17'),
(53, 21, 11, '2025-01-06 17:24:23');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `bio` varchar(255) NOT NULL,
  `hrate` varchar(255) NOT NULL,
  `availabitily` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('freelancer','client','admin') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `profile_picture` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `address`, `fullname`, `bio`, `hrate`, `availabitily`, `password`, `role`, `created_at`, `profile_picture`, `logo`) VALUES
(12, 'Admin User', 'admin@gmail.com', '', '', '', '', '00:00:00', '$2y$10$Rd5BE4M757eOOqX1m0eO9.Dvnw.HwtL7k5sHH/3LB8O/GlJQwyj8W', 'admin', '2025-01-05 11:45:16', NULL, NULL),
(20, 'Angel Mae', 'mae@gmail.com', '', '', '', '', '', '$2y$10$EZ6vChx0F80Q6DVHeaDjF.fNK406I6RgHOc.8FyTjI8NhyAo/.gt6', 'client', '2025-01-06 16:35:35', NULL, NULL),
(21, 'Jane Doe', 'jane@gmail.com', 'Dagupan City Pangasinan', 'Jane Marie Anastacia Doe', 'I am a dedicated and versatile web developer with expertise in creating responsive, user-friendly websites and web applications. With a strong foundation in HTML, CSS, JavaScript, and various modern frameworks like React, Angular, and Vue.js, I am able to', '1000', '9:00 AM - 5:30 PM', '$2y$10$dsWiepJvijvUQikm8RXELeg2ybHg7mPAakrKhhp0LREobF3R6jqK2', 'freelancer', '2025-01-06 16:43:48', 'uploads/8.jpg', NULL),
(22, 'Harry Roque', 'harry@gmail.com', 'Sto Tomas Pangasinan', 'Harry Duque Roque', 'Quisque ac quam malesuada ligula tincidunt viverra. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce finibus tortor ac euismod blandit. Morbi aliquet aliquet justo. Phasellus nec sagittis leo. Cras sit amet hendrerit sem. Proin scelerisque e', '1000', '7:00 AM - 7:00 PM', '$2y$10$gs7E3CF503j5rI8Ao1oCa.TKnNPMy7vheXKJfdQI6VsLC0/Ds1TDu', 'client', '2025-01-06 17:06:57', 'uploads/SOSw049O_400x400.jpg', NULL),
(23, 'Rodrigo Duterte', 'dig@gmail.com', 'Malasiqui, Pangasinan', 'Rodrigo Roa Duterte', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus rhoncus lorem ipsum, quis dapibus tellus malesuada sit amet. Curabitur pretium ligula nec massa aliquam, at pretium enim maximus. Vivamus sollicitudin a nisi eu scelerisque. Integer pellente', '1000', '10:00 AM - 4:00 PM ', '$2y$10$3fJoeZFmQl/nrEe38GAw7uDT.vQMIV5bj4NmBJxBnVQFwonv/3PRm', 'freelancer', '2025-01-06 17:10:48', 'uploads/Rodrigo-Duterte-1024x1024.jpg', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contracts`
--
ALTER TABLE `contracts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proposal_id` (`proposal_id`),
  ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `proposals`
--
ALTER TABLE `proposals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_id` (`job_id`),
  ADD KEY `freelancer_id` (`freelancer_id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `freelancer_id` (`freelancer_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `freelancer_id` (`freelancer_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reviewer_id` (`reviewer_id`),
  ADD KEY `reviewee_id` (`reviewee_id`);

--
-- Indexes for table `saved_jobs`
--
ALTER TABLE `saved_jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `freelancer_id` (`freelancer_id`),
  ADD KEY `job_id` (`job_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contracts`
--
ALTER TABLE `contracts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `proposals`
--
ALTER TABLE `proposals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `saved_jobs`
--
ALTER TABLE `saved_jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `contracts`
--
ALTER TABLE `contracts`
  ADD CONSTRAINT `contracts_ibfk_1` FOREIGN KEY (`proposal_id`) REFERENCES `proposals` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `contracts_ibfk_2` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `jobs`
--
ALTER TABLE `jobs`
  ADD CONSTRAINT `jobs_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `proposals`
--
ALTER TABLE `proposals`
  ADD CONSTRAINT `proposals_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `proposals_ibfk_2` FOREIGN KEY (`freelancer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`freelancer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ratings_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`freelancer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reports_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`reviewer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`reviewee_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `saved_jobs`
--
ALTER TABLE `saved_jobs`
  ADD CONSTRAINT `saved_jobs_ibfk_1` FOREIGN KEY (`freelancer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `saved_jobs_ibfk_2` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
