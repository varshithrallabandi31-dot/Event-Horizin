-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 26, 2025 at 01:55 PM
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
-- Database: `event_platform`
--

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `organizer_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime DEFAULT NULL,
  `location_name` varchar(255) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `kit_config` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`kit_config`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `organizer_id`, `title`, `description`, `start_time`, `end_time`, `location_name`, `latitude`, `longitude`, `image_url`, `category`, `kit_config`, `created_at`) VALUES
(1, 1, 'Sports meet', 'Join us for Sports meet! A Art event.', '2025-12-14 15:20:00', NULL, 'Hyderabad', 40.71280000, -74.00600000, 'https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?auto=format&fit=crop&w=800&q=80', 'Art', NULL, '2025-12-23 04:45:13'),
(2, 1, 'Hi', 'Join us for Hi! A Social event.', '2025-12-20 14:30:00', NULL, 'Banglore', 40.71280000, -74.00600000, 'https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?auto=format&fit=crop&w=800&q=80', 'Social', '{\"title\":\"The ultimate Social Gathering\",\"sections\":[{\"title\":\"Event Overview\",\"content\":\"Welcome to our event! We are excited to have you.\"},{\"title\":\"Important Instructions\",\"content\":\"Please arrive 15 minutes early and bring your digital ticket.\"},{\"title\":\"\",\"content\":\"\"}]}', '2025-12-23 04:57:26'),
(3, 1, 'Music concert', 'Join us for Music concert! A Music event.', '2026-01-01 00:00:00', NULL, 'Bengaluru', 40.71280000, -74.00600000, 'https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?auto=format&fit=crop&w=800&q=80', 'Music', '{\"title\":\"Concentric Concert\",\"sections\":[{\"title\":\"Event Overview\",\"content\":\"Welcome to our event! We are excited to have you.\"},{\"title\":\"Important Instructions\",\"content\":\"Please arrive 15 minutes early and bring your digital ticket.\"}]}', '2025-12-23 07:24:03');

-- --------------------------------------------------------

--
-- Table structure for table `event_memories`
--

CREATE TABLE `event_memories` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `image_url` varchar(255) NOT NULL,
  `caption` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event_memories`
--

INSERT INTO `event_memories` (`id`, `event_id`, `user_id`, `image_url`, `caption`, `created_at`) VALUES
(1, 1, NULL, 'https://commondatastorage.googleapis.com/codeskulptor-demos/riceracer_assets/img/car_1.png', 'Best ever', '2025-12-23 11:33:34'),
(2, 1, 2, 'https://unsplash.com/photos/people-inside-conference-cuKJre3nyYc', 'BEst event ', '2025-12-26 05:46:26'),
(3, 1, 2, 'https://pngtree.com/freepng/flower-jpg-vector_11243673.html', ' b', '2025-12-26 05:47:29'),
(4, 1, 2, 'https://unsplash.com/photos/a-blue-and-white-icon-with-two-arrows-Sr5e_Bes-VQ', 'Best ever', '2025-12-26 05:49:13');

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `question` varchar(255) NOT NULL,
  `answer` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`id`, `event_id`, `question`, `answer`, `created_at`) VALUES
(1, 1, 'Is parking available?', 'Yes, we have a dedicated lot.', '2025-12-26 00:40:08'),
(2, 2, 'Is parking available?', 'Yes, we have a dedicated lot.', '2025-12-26 00:40:08'),
(3, 3, 'Is parking available?', 'Yes, we have a dedicated lot.', '2025-12-26 00:40:08'),
(4, 1, 'Is there a dress code?', 'Smart casual is recommended.', '2025-12-26 00:43:49'),
(5, 2, 'Is there a dress code?', 'Smart casual is recommended.', '2025-12-26 00:43:49'),
(6, 3, 'Is there a dress code?', 'Smart casual is recommended.', '2025-12-26 00:43:49'),
(7, 1, 'Can I bring a plus one?', 'Please check the ticket details.', '2025-12-26 00:43:49'),
(8, 2, 'Can I bring a plus one?', 'Please check the ticket details.', '2025-12-26 00:43:49'),
(9, 3, 'Can I bring a plus one?', 'Please check the ticket details.', '2025-12-26 00:43:49'),
(10, 1, 'Is food provided?', 'Yes, generic snacks will be available.', '2025-12-26 00:43:49'),
(11, 2, 'Is food provided?', 'Yes, generic snacks will be available.', '2025-12-26 00:43:49'),
(12, 3, 'Is food provided?', 'Yes, generic snacks will be available.', '2025-12-26 00:43:49'),
(13, 1, 'Is there wheelchair access?', 'Yes, the venue is fully accessible.', '2025-12-26 00:43:49'),
(14, 2, 'Is there wheelchair access?', 'Yes, the venue is fully accessible.', '2025-12-26 00:43:49'),
(15, 3, 'Is there wheelchair access?', 'Yes, the venue is fully accessible.', '2025-12-26 00:43:49'),
(16, 1, 'What is the refund policy?', 'Refunds are available up to 24 hours before.', '2025-12-26 00:43:49'),
(17, 2, 'What is the refund policy?', 'Refunds are available up to 24 hours before.', '2025-12-26 00:43:49'),
(18, 3, 'What is the refund policy?', 'Refunds are available up to 24 hours before.', '2025-12-26 00:43:49'),
(19, 1, 'Are pets allowed?', 'Service animals only.', '2025-12-26 00:43:49'),
(20, 2, 'Are pets allowed?', 'Service animals only.', '2025-12-26 00:43:49'),
(21, 3, 'Are pets allowed?', 'Service animals only.', '2025-12-26 00:43:49');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `event_id`, `user_id`, `content`, `created_at`) VALUES
(1, 3, 1, 'Hello', '2025-12-23 07:34:33'),
(2, 3, 2, 'how are you', '2025-12-23 07:34:52'),
(3, 1, 1, 'm', '2025-12-23 13:43:17'),
(4, 3, 1, 'hi', '2025-12-25 18:55:51'),
(5, 3, 1, 'hi', '2025-12-25 18:55:53'),
(6, 3, 1, 'hi', '2025-12-25 18:55:54'),
(7, 3, 1, 'sure', '2025-12-25 19:00:24'),
(8, 3, 1, 'sure', '2025-12-25 19:00:25'),
(9, 3, 1, 'hi', '2025-12-25 19:02:00'),
(10, 3, 1, 'hi', '2025-12-25 19:02:02'),
(11, 3, 1, 'wasup', '2025-12-25 19:06:36');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `message`, `is_read`, `created_at`) VALUES
(1, 2, 'Your RSVP for the event has been approved! You can now download the digital kit.', 0, '2025-12-23 04:47:13'),
(2, 1, 'Your RSVP for the event has been approved! You can now download the digital kit.', 0, '2025-12-23 04:57:43'),
(3, 3, 'Your RSVP for the event has been approved! You can now download the digital kit.', 0, '2025-12-23 06:23:41'),
(4, 4, 'Your RSVP for the event has been approved! You can now download the digital kit.', 0, '2025-12-23 06:33:59'),
(5, 2, 'Your RSVP for the event has been approved! You can now download the digital kit.', 0, '2025-12-23 06:50:49'),
(6, 1, 'Your RSVP for the event has been approved! You can now download the digital kit.', 0, '2025-12-23 07:24:15'),
(7, 2, 'Your RSVP for the event has been approved! You can now download the digital kit.', 0, '2025-12-23 07:32:29'),
(8, 5, 'Your RSVP for the event has been approved! You can now download the digital kit.', 0, '2025-12-23 07:43:46');

-- --------------------------------------------------------

--
-- Table structure for table `photos`
--

CREATE TABLE `photos` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `is_curated` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `polls`
--

CREATE TABLE `polls` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `question` text NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `polls`
--

INSERT INTO `polls` (`id`, `event_id`, `question`, `created_by`, `created_at`) VALUES
(1, 3, 'j', 1, '2025-12-25 19:25:38'),
(2, 3, 'fod', 1, '2025-12-26 06:19:43');

-- --------------------------------------------------------

--
-- Table structure for table `poll_options`
--

CREATE TABLE `poll_options` (
  `id` int(11) NOT NULL,
  `poll_id` int(11) NOT NULL,
  `option_text` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `poll_options`
--

INSERT INTO `poll_options` (`id`, `poll_id`, `option_text`) VALUES
(1, 1, 'j'),
(2, 1, 'm'),
(3, 2, 'veg'),
(4, 2, 'nonveg');

-- --------------------------------------------------------

--
-- Table structure for table `poll_votes`
--

CREATE TABLE `poll_votes` (
  `id` int(11) NOT NULL,
  `poll_id` int(11) NOT NULL,
  `option_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `poll_votes`
--

INSERT INTO `poll_votes` (`id`, `poll_id`, `option_id`, `user_id`, `created_at`) VALUES
(1, 1, 1, 1, '2025-12-25 19:25:42'),
(2, 2, 3, 1, '2025-12-26 06:19:47');

-- --------------------------------------------------------

--
-- Table structure for table `rsvps`
--

CREATE TABLE `rsvps` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ticket_tier_id` int(11) DEFAULT NULL,
  `status` enum('pending','approved','rejected','checked_in') DEFAULT 'pending',
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `qr_code` varchar(255) DEFAULT NULL,
  `answers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`answers`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `referral_source` varchar(255) DEFAULT 'direct',
  `checked_in_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rsvps`
--

INSERT INTO `rsvps` (`id`, `event_id`, `user_id`, `ticket_tier_id`, `status`, `approved_at`, `rejection_reason`, `qr_code`, `answers`, `created_at`, `referral_source`, `checked_in_at`) VALUES
(1, 1, 2, NULL, 'approved', '2025-12-23 00:17:13', NULL, NULL, '{\"interest\":\"\"}', '2025-12-23 04:46:27', 'direct', NULL),
(2, 2, 1, NULL, 'approved', '2025-12-23 00:27:43', NULL, NULL, '{\"interest\":\"\"}', '2025-12-23 04:57:31', 'direct', NULL),
(3, 1, 3, NULL, 'approved', '2025-12-23 01:53:41', NULL, NULL, '{\"interest\":\"\"}', '2025-12-23 06:23:09', 'direct', NULL),
(4, 1, 4, NULL, 'approved', '2025-12-23 02:03:59', NULL, NULL, '{\"interest\":\"\"}', '2025-12-23 06:33:42', 'direct', NULL),
(5, 2, 2, NULL, 'approved', '2025-12-23 02:20:49', NULL, NULL, '{\"interest\":\"\"}', '2025-12-23 06:50:33', 'direct', NULL),
(6, 3, 1, NULL, 'approved', '2025-12-23 02:54:15', NULL, NULL, '{\"interest\":\"\"}', '2025-12-23 07:24:10', 'direct', NULL),
(7, 3, 2, NULL, 'approved', '2025-12-23 03:02:29', NULL, NULL, '{\"interest\":\"\"}', '2025-12-23 07:32:04', 'direct', NULL),
(8, 3, 5, NULL, 'approved', '2025-12-23 03:13:46', NULL, NULL, '{\"interest\":\"\"}', '2025-12-23 07:42:49', 'direct', NULL),
(9, 1, 1, NULL, 'rejected', NULL, NULL, NULL, '{\"interest\":\"Networking\"}', '2025-12-25 19:12:44', 'direct', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ticket_tiers`
--

CREATE TABLE `ticket_tiers` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) DEFAULT 0.00,
  `quantity_available` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ticket_tiers`
--

INSERT INTO `ticket_tiers` (`id`, `event_id`, `name`, `price`, `quantity_available`) VALUES
(1, 1, 'Early Bird', 10.00, 50),
(2, 2, 'Early Bird', 10.00, 50),
(3, 3, 'Early Bird', 10.00, 50),
(4, 1, 'VIP Access', 50.00, 10),
(5, 2, 'VIP Access', 50.00, 10),
(6, 3, 'VIP Access', 50.00, 10);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `interests` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`interests`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `avatar_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `phone`, `name`, `email`, `location`, `bio`, `interests`, `created_at`, `avatar_url`) VALUES
(1, '9502901416', 'varshith', '', '', 'event manager', '[]', '2025-12-23 04:42:09', '/P1/public/uploads/avatars/avatar_1_1766496988.jpg'),
(2, '7780720117', 'Krishna', 'varshivarshith77@gmail.com', 'Hyderabad', '', '[\"Tech\",\"Nightlife\",\"Business\"]', '2025-12-23 04:45:49', NULL),
(3, '9063661667', 'vamsi', 'varshivarshith4@gmail.com', 'Proddarur', '', '[\"Tech\",\"Music\"]', '2025-12-23 06:18:41', NULL),
(4, '9704234850', 'sreeja', 'ouraganisreeja@gmail.com', 'Proddarur', '', '[\"Tech\",\"Nightlife\"]', '2025-12-23 06:33:19', NULL),
(5, '9290075221', 'Anjali', 'kusumanjali4646@gmail.com', 'banglore', '', '[\"Tech\",\"Art\",\"Business\"]', '2025-12-23 07:41:36', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `organizer_id` (`organizer_id`);

--
-- Indexes for table `event_memories`
--
ALTER TABLE `event_memories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `photos`
--
ALTER TABLE `photos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `polls`
--
ALTER TABLE `polls`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `poll_options`
--
ALTER TABLE `poll_options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `poll_votes`
--
ALTER TABLE `poll_votes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rsvps`
--
ALTER TABLE `rsvps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `ticket_tier_id` (`ticket_tier_id`);

--
-- Indexes for table `ticket_tiers`
--
ALTER TABLE `ticket_tiers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `event_memories`
--
ALTER TABLE `event_memories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `photos`
--
ALTER TABLE `photos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `polls`
--
ALTER TABLE `polls`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `poll_options`
--
ALTER TABLE `poll_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `poll_votes`
--
ALTER TABLE `poll_votes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `rsvps`
--
ALTER TABLE `rsvps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `ticket_tiers`
--
ALTER TABLE `ticket_tiers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`organizer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `event_memories`
--
ALTER TABLE `event_memories`
  ADD CONSTRAINT `event_memories_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `faqs`
--
ALTER TABLE `faqs`
  ADD CONSTRAINT `faqs_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `photos`
--
ALTER TABLE `photos`
  ADD CONSTRAINT `photos_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `photos_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `rsvps`
--
ALTER TABLE `rsvps`
  ADD CONSTRAINT `rsvps_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rsvps_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rsvps_ibfk_3` FOREIGN KEY (`ticket_tier_id`) REFERENCES `ticket_tiers` (`id`);

--
-- Constraints for table `ticket_tiers`
--
ALTER TABLE `ticket_tiers`
  ADD CONSTRAINT `ticket_tiers_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
