-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 10, 2025 at 01:30 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

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
-- Table structure for table `client_reports`
--

CREATE TABLE `client_reports` (
  `id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `details` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `client_id` int(11) NOT NULL,
  `reported_by` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `contracts`
--

INSERT INTO `contracts` (`id`, `proposal_id`, `client_id`, `terms`, `status`, `logo`, `created_at`) VALUES
(2, 26, 29, 'Contract for Job ID: 17 with Freelancer: a', 'active', NULL, '2025-01-09 13:29:08');

-- --------------------------------------------------------

--
-- Table structure for table `freelance_report`
--

CREATE TABLE `freelance_report` (
  `id` int(11) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `details` varchar(255) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
  `logo` varchar(255) DEFAULT NULL,
  `skills` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `client_id`, `title`, `description`, `requirements`, `budget`, `status`, `created_at`, `address`, `company_name`, `position`, `logo`, `skills`) VALUES
(11, 20, 'Web Developer (Remote)', 'We are looking for a skilled web developer to join our team. The successful candidate will be responsible for developing, maintaining, and improving websites and web applications. You&#039;ll work on exciting projects, ensuring high-quality code, and collaborate with team members to meet client requirements. The position requires expertise in front-end and back-end development, troubleshooting issues, and creating seamless user experiences.', 'The ideal candidate will have strong proficiency in HTML, CSS, JavaScript, and experience with modern frameworks like React, Angular, or Vue.js. Experience with back-end technologies such as Node.js, PHP, or Python is necessary. Familiarity with databases (MySQL, MongoDB) and RESTful APIs is preferred. Excellent problem-solving, communication skills, and the ability to work independently and in a team are essential.', '5000.00', 'open', '2025-01-06 16:38:57', 'Urdaneta, Pangasinan', '', '', NULL, NULL),
(12, 22, 'Graphic Designer Wanted for Freelance Project', 'We are looking for a talented and creative graphic designer to assist with a variety of design projects, including branding, logo design, social media graphics, marketing materials, and more. The ideal candidate will be proficient in design software like Adobe Illustrator, Photoshop, and InDesign, with an eye for detail and a strong sense of aesthetic. You will work closely with our team to bring our brand vision to life and ensure all designs align with our brand identity. This is a freelance position with the possibility of future work based on performance.', 'Applicants must have at least 2 years of professional graphic design experience, a strong portfolio showcasing relevant work, and proficiency in Adobe Creative Suite. A good understanding of design principles and the ability to work independently and meet deadlines is required. Previous experience with both print and digital design is preferred.', '25000.00', 'closed', '2025-01-06 17:09:01', 'Sta Maria, Pangasinan', '', '', NULL, NULL),
(13, 22, 'Web Developer ', 'We are looking for a talented and motivated software developer to join our growing team. In this role, you will collaborate with cross-functional teams to design, develop, and maintain high-quality software solutions. You will be involved in all stages of the software development lifecycle, including requirement analysis, design, coding, testing, and deployment. As part of an agile team, you&#039;ll contribute to the development of scalable, efficient, and reliable applications. This is an exciting opportunity for someone who enjoys working with cutting-edge technologies and is passionate about creating innovative solutions that have a real impact.', 'The ideal candidate will have a Bachelor’s degree in Computer Science, Software Engineering, or a related field, or equivalent work experience. You should have proven experience in programming languages such as [Java, Python, JavaScript, etc.], and be familiar with both front-end technologies like [React, Angular, etc.] and back-end frameworks such as [Spring, Django, Node.js, etc.]. Strong experience with databases (SQL and NoSQL), cloud platforms (AWS, Azure, etc.), and version control systems like Git is also required. You should possess solid problem-solving skills and the ability to work effectively in both independent and collaborative environments. Additionally, knowledge of Agile methodologies and tools such as Jira or Trello is important. The role demands excellent communication skills, with the ability to articulate technical concepts to non-technical stakeholders.', '2500.00', 'open', '2025-01-08 13:29:23', 'Rosales, Pangasinan', 'NexaWorks', 'Web Developer', 'uploads/logos/677e7db3133c0_web-logo-developer-development-icon-dev-or-programmer-logo-vector.jpg', NULL),
(14, 25, 'sample work', 'sample sample sample sample', 'sample sample sample sample', '99999999.99', 'closed', '2025-01-08 19:59:58', 'tarlac city', 'sample company', 'Software Developer Project Manager', 'uploads/logos/677ed93e769ef_21-218603_illustration-of-a-group-of-people-user-account.png', 'Python, Java, Java'),
(15, 25, 'sample sample', '\r\nA sample work typically refers to a representative piece or example of a project, task, or skill set that a professional has completed, which demonstrates their capabilities, style, and approach. It&#039;s often used to showcase abilities to potential clients, employers, or collaborators.', 'Clear Purpose: Ensure the sample serves a specific goal, whether it’s to showcase a skill, provide a solution, or demonstrate creativity.\r\nRelevance: The sample work should be relevant to the job or client you are targeting.\r\nFormat: Depending on the medium, it could be a document, presentation, image gallery, video, or a web page.\r\nProfessionalism: Even for sample work, the quality should reflect your best abilities and professionalism.', '1234567.00', 'open', '2025-01-09 01:45:51', 'Baguio City', 'sample sample company', 'Software Developer Project Manager', 'uploads/logos/677f2a4f5d9eb_multitasking_632842-129.jpg', 'Social Media Marketing, Search Engine Optimization, Graphic Design, Web Designing and Developing'),
(16, 22, 'digital arts', 'Digital art is a form of art that uses digital technology as part of the creative or presentation process1234. It encompasses a wide range of techniques, from digital drawings, paintings, and illustrations to photos, videos, and even sculpture34. Digital art is placed under the larger category of new media art2. It can also refer to computational art that uses and engages with digital media', 'Educational background: While formal education is not always mandatory, many employers prefer candidates with a bachelor&#039;s degree in fine arts, graphic design, animation, or a related field.', '10000.00', 'open', '2025-01-09 08:19:51', 'Pangasinan, Philippines', 'digital company', 'artist', 'uploads/logos/677f86a7b9eba_robin.jpg', 'Photography and Editing, Graphic Design'),
(17, 29, 'video editing', 'Trimming, arranging, and sequencing raw footage to craft a cohesive narrative.\r\nAdding smooth transitions, text overlays, subtitles, and motion graphics where necessary.\r\nEnhancing visual elements through color grading and correction to maintain a consistent and polished look.', 'Tailored editing based on your specific preferences, including creative input for visual storytelling and design.\r\nBranding elements like logos, intros, and outros will be integrated seamlessly.', '10000.00', 'open', '2025-01-09 12:42:14', 'Asingan, Pangasinan', 'company x', 'Photography and Editing', 'uploads/logos/677fc426d62f4_rotate.png', 'Photography and Editing, Graphic Design');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `proposals`
--

INSERT INTO `proposals` (`id`, `job_id`, `freelancer_id`, `proposal_details`, `proposed_rate`, `status`, `created_at`, `attachment`, `profile_highlight`) VALUES
(20, 11, 21, 'I have worked on a wide variety of web development projects, from small business websites to large-scale platforms. My skills in HTML, CSS, JavaScript, and frameworks such as React, Angular, and Laravel allow me to tailor solutions to meet your specific needs. Additionally, I am well-versed in website performance optimization, SEO best practices, and ensuring a seamless user experience across all devices.', '1000.00', 'pending', '2025-01-06 17:29:31', NULL, NULL),
(21, 12, 21, 'With over 12 years of experience in graphic design, I have developed a strong portfolio that includes logo design, branding, marketing materials, social media graphics, and more. I am proficient in Adobe Illustrator, Photoshop, and InDesign, and I specialize in both digital and print design. I have worked with various clients across industries, delivering designs that help elevate their brands and effectively communicate their messages.', '15000.00', 'rejected', '2025-01-06 17:45:38', NULL, NULL),
(22, 13, 21, 'dwadw', '12223.00', 'pending', '2025-01-08 13:59:04', NULL, NULL),
(23, 12, 21, 'assss', '123.00', 'pending', '2025-01-08 14:12:51', 'uploads/DisasterRecoveryPlanTemplate.org-Disaster-Recovery-Plan-Sample (1).pdf', 'uploads/profile_highlights/DRP.pdf'),
(24, 12, 21, 'aaa', '1111.00', 'pending', '2025-01-08 14:14:11', 'uploads/DisasterRecoveryPlanTemplate.org-Disaster-Recovery-Plan-Sample (1).pdf', 'uploads/profile_highlights/DRP.pdf'),
(26, 17, 28, 'Delivering the final video in your desired format (e.g., MP4, MOV, etc.) optimized for your preferred platform (e.g., YouTube, Instagram, etc.). Initial draft: [Insert time frame, e.g., 5–7 business days after receiving materials]\r\nRevisions: [Specify time frame, e.g., 2–3 business days per round of feedback]\r\nFinal delivery: [Insert expected delivery date]', '10000.00', 'accepted', '2025-01-09 12:44:42', 'uploads/SIA (1).pdf', 'uploads/profile_highlights/SIA (1).pdf');

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `id` int(11) NOT NULL,
  `freelancer_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `feedback` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ratings`
--

INSERT INTO `ratings` (`id`, `freelancer_id`, `job_id`, `client_id`, `rating`, `feedback`, `created_at`) VALUES
(1, 28, 17, 29, 5, 'fftrddrtd', '2025-01-09 13:29:58'),
(2, 28, 17, 29, 5, 'dcrdr', '2025-01-09 14:09:07'),
(3, 28, 17, 29, 5, 'sdfghj', '2025-01-09 16:00:37'),
(4, 28, 17, 29, 5, 'sdfgh', '2025-01-09 16:06:56'),
(5, 28, 17, 29, 5, 'lkjhg', '2025-01-09 16:09:24');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `freelancer_id`, `user_id`, `report_reason`, `created_at`) VALUES
(1, 23, 22, 'wdsaw', '2025-01-08 15:02:37'),
(2, 21, 25, 'sample', '2025-01-09 04:37:25'),
(5, 27, 30, 'acwdcs', '2025-01-10 00:06:36'),
(6, 27, 30, 'csdv', '2025-01-10 00:06:40'),
(7, 27, 30, 'dvsdv', '2025-01-10 00:06:44'),
(8, 27, 30, 'svdsdvw', '2025-01-10 00:06:47'),
(9, 27, 30, 'sdvv', '2025-01-10 00:06:51');

-- --------------------------------------------------------

--
-- Table structure for table `reports_client`
--

CREATE TABLE `reports_client` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `report_reason` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `reports_client`
--

INSERT INTO `reports_client` (`id`, `user_id`, `client_id`, `report_reason`, `created_at`) VALUES
(1, 24, 0, 'asxas', '2025-01-09 07:45:43'),
(2, 24, 0, 'csdcds', '2025-01-09 07:48:01'),
(3, 24, 0, 'axs', '2025-01-09 07:51:08'),
(4, 24, 0, 'ssss', '2025-01-09 07:58:23'),
(5, 24, 0, 'sww', '2025-01-09 08:00:02'),
(6, 24, 0, 'sss', '2025-01-09 08:01:23'),
(7, 24, 0, 'csdvav', '2025-01-09 08:05:52'),
(8, 21, 0, 'aaaa', '2025-01-09 08:30:34'),
(9, 27, 0, 'gngn', '2025-01-09 23:46:35');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `saved_jobs`
--

CREATE TABLE `saved_jobs` (
  `id` int(11) NOT NULL,
  `freelancer_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `saved_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `saved_jobs`
--

INSERT INTO `saved_jobs` (`id`, `freelancer_id`, `job_id`, `saved_at`) VALUES
(52, 23, 11, '2025-01-06 17:11:17'),
(53, 21, 11, '2025-01-06 17:24:23'),
(54, 24, 11, '2025-01-08 17:23:33');

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
  `logo` varchar(255) DEFAULT NULL,
  `freelancer_level` varchar(50) DEFAULT NULL,
  `client_id` int(11) NOT NULL,
  `disabled` tinyint(4) DEFAULT 0,
  `report_threshold` int(11) DEFAULT 5
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `address`, `fullname`, `bio`, `hrate`, `availabitily`, `password`, `role`, `created_at`, `profile_picture`, `logo`, `freelancer_level`, `client_id`, `disabled`, `report_threshold`) VALUES
(12, 'Admin User', 'admin@gmail.com', '', '', '', '', '00:00:00', '$2y$10$Rd5BE4M757eOOqX1m0eO9.Dvnw.HwtL7k5sHH/3LB8O/GlJQwyj8W', 'admin', '2025-01-05 11:45:16', NULL, NULL, NULL, 1, 0, 5),
(20, 'Angel Mae', 'mae@gmail.com', '', '', '', '', '', '$2y$10$EZ6vChx0F80Q6DVHeaDjF.fNK406I6RgHOc.8FyTjI8NhyAo/.gt6', 'client', '2025-01-06 16:35:35', NULL, NULL, NULL, 2, 0, 5),
(21, 'Jane Doe', 'jane@gmail.com', 'Dagupan City Pangasinan', 'Jane Marie Anastacia Doe', 'I am a dedicated and versatile web developer with expertise in creating responsive, user-friendly websites and web applications. With a strong foundation in HTML, CSS, JavaScript, and various modern frameworks like React, Angular, and Vue.js, I am able to', '1000', '9:00 AM - 5:30 PM', '$2y$10$dsWiepJvijvUQikm8RXELeg2ybHg7mPAakrKhhp0LREobF3R6jqK2', 'freelancer', '2025-01-06 16:43:48', 'uploads/8.jpg', NULL, 'intermediate', 0, 0, 5),
(22, 'Harry Roque', 'harry@gmail.com', 'Sto Tomas Pangasinan', 'Harry Duque Roque', 'Quisque ac quam malesuada ligula tincidunt viverra. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce finibus tortor ac euismod blandit. Morbi aliquet aliquet justo. Phasellus nec sagittis leo. Cras sit amet hendrerit sem. Proin scelerisque e', '1000', '7:00 AM - 7:00 PM', '$2y$10$gs7E3CF503j5rI8Ao1oCa.TKnNPMy7vheXKJfdQI6VsLC0/Ds1TDu', 'client', '2025-01-06 17:06:57', 'uploads/SOSw049O_400x400.jpg', NULL, NULL, 3, 0, 5),
(23, 'Rodrigo Duterte', 'dig@gmail.com', 'Malasiqui, Pangasinan', 'Rodrigo Roa Duterte', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus rhoncus lorem ipsum, quis dapibus tellus malesuada sit amet. Curabitur pretium ligula nec massa aliquam, at pretium enim maximus. Vivamus sollicitudin a nisi eu scelerisque. Integer pellente', '1000', '10:00 AM - 4:00 PM ', '$2y$10$3fJoeZFmQl/nrEe38GAw7uDT.vQMIV5bj4NmBJxBnVQFwonv/3PRm', 'freelancer', '2025-01-06 17:10:48', 'uploads/Rodrigo-Duterte-1024x1024.jpg', NULL, NULL, 0, 0, 5),
(24, 'Shean Mejia', 'shean@gmail.com', 'Agat, Pangasinan, Ilocos Region,4243', 'Shean Mejia', 'sample sample sample', '2000000000', '3:00 PM - 5:00 AM', '$2y$10$eSgmNz3w2C7J/HGfGC6MLeBNO9..szRqI1CwVaFUzMfYFM0gkzU.2', 'freelancer', '2025-01-08 17:21:56', 'uploads/multitasking_632842-129.jpg', NULL, 'beginner', 0, 1, 5),
(25, 'Shean', 'cshean@gmail.com', '', '', '', '', '', '$2y$10$SrFk7.Jp2RAXtgMnIFFxwOAgc37XK5vhiRabBou7kUvNnTPywvDH.', 'client', '2025-01-08 17:28:40', NULL, NULL, NULL, 4, 0, 5),
(26, 'Zoren Espiritu', 'client@gmail.com', '', '', '', '', '', '$2y$10$DfY6AnEizg7CbkxAFsUAjec8AZP9uED4LF2jLsKyDi3GCQ.MVzzz.', 'client', '2025-01-09 06:28:17', NULL, NULL, NULL, 5, 0, 5),
(27, 'hotdog', 'moshiroxinji@gmail.com', '', '', '', '', '', '$2y$10$8zUrQyzprJJq9QDi502AEOxV4mNu.6OmFa4PMGOqkPgb9AQ4m81tK', 'freelancer', '2025-01-09 10:03:58', NULL, NULL, NULL, 0, 0, 10),
(28, 'a', 'a@gmail.com', '', '', '', '', '', '$2y$10$AxTX9LBZzHloJdLTUOvnyuygJZqoPH5mZNtB9Imz6Xdm63lmfYDW.', 'freelancer', '2025-01-09 12:23:46', NULL, NULL, NULL, 0, 0, 5),
(29, 'b', 'b@gmail.com', '', '', '', '', '', '$2y$10$S.oZkGSvTVADl5pS4YQu.OfGhK6mPpyBsARn0SiVZMVkjuyepVaHK', 'client', '2025-01-09 12:24:08', NULL, NULL, NULL, 0, 0, 5),
(30, 'nit', 'zorenespiritu.ze12@gmail.com', '', '', '', '', '', '$2y$10$mgaW5A0Myc8RMdHUFWb.e.tjcCjE1h/UZYz5aEdbXFsCZ9VdNQEBW', 'client', '2025-01-10 00:03:33', NULL, NULL, NULL, 0, 1, 5);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `client_reports`
--
ALTER TABLE `client_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_id` (`job_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `contracts`
--
ALTER TABLE `contracts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proposal_id` (`proposal_id`),
  ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `freelance_report`
--
ALTER TABLE `freelance_report`
  ADD PRIMARY KEY (`id`);

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
  ADD KEY `job_id` (`job_id`),
  ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `freelancer_id` (`freelancer_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `reports_client`
--
ALTER TABLE `reports_client`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `client_reports`
--
ALTER TABLE `client_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `contracts`
--
ALTER TABLE `contracts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `freelance_report`
--
ALTER TABLE `freelance_report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `proposals`
--
ALTER TABLE `proposals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `reports_client`
--
ALTER TABLE `reports_client`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `saved_jobs`
--
ALTER TABLE `saved_jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `client_reports`
--
ALTER TABLE `client_reports`
  ADD CONSTRAINT `client_reports_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`),
  ADD CONSTRAINT `client_reports_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

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
  ADD CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`freelancer_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `ratings_ibfk_2` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`),
  ADD CONSTRAINT `ratings_ibfk_3` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`);

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
