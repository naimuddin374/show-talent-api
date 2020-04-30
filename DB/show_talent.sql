-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 29, 2020 at 12:38 PM
-- Server version: 10.1.35-MariaDB
-- PHP Version: 7.2.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `show_talent`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` int(11) NOT NULL DEFAULT '1' COMMENT '1=Credit, 2=Debit',
  `amount` float(8,2) NOT NULL,
  `last_balance` float(8,2) NOT NULL,
  `available_balance` float(8,2) NOT NULL,
  `comment` text,
  `status` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `user_id`, `type`, `amount`, `last_balance`, `available_balance`, `comment`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 100.00, 0.00, 100.00, 'This is the first account credit amount', 0, '2020-04-22 07:15:57', '2020-04-22 07:15:05');

-- --------------------------------------------------------

--
-- Table structure for table `ads`
--

CREATE TABLE `ads` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL DEFAULT '0',
  `category_id` int(11) NOT NULL DEFAULT '0',
  `title` text,
  `image` text,
  `video` text,
  `hyperlink` text,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '0=Pending,1=Approved,2=Reject',
  `reject_note` text,
  `reopen_note` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ads`
--

INSERT INTO `ads` (`id`, `user_id`, `page_id`, `category_id`, `title`, `image`, `video`, `hyperlink`, `start_date`, `end_date`, `status`, `reject_note`, `reopen_note`, `created_at`, `updated_at`) VALUES
(1, 1, 0, 1, 'Test Ad Title', NULL, 'https://www.youtube.com/', 'www.google.com', '2020-04-25', '2020-05-25', 1, NULL, NULL, '2020-04-23 07:36:27', '2020-04-23 01:32:15');

-- --------------------------------------------------------

--
-- Table structure for table `ad_budgets`
--

CREATE TABLE `ad_budgets` (
  `id` int(10) UNSIGNED NOT NULL,
  `ad_id` int(11) NOT NULL,
  `amount` float(8,2) NOT NULL,
  `currency` varchar(20) NOT NULL DEFAULT 'BDT',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `audience_selects`
--

CREATE TABLE `audience_selects` (
  `id` int(10) UNSIGNED NOT NULL,
  `ad_id` int(11) NOT NULL,
  `user_ids` text,
  `age_start` int(11) NOT NULL DEFAULT '0',
  `age_end` int(11) NOT NULL DEFAULT '0',
  `country_ids` text,
  `city_ids` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `authors`
--

CREATE TABLE `authors` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `bio` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `authors`
--

INSERT INTO `authors` (`id`, `name`, `bio`, `created_at`, `updated_at`) VALUES
(1, 'MD. FOYSAL', 'Life is race', '2020-04-20 13:08:10', '2020-04-20 13:08:10');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '0=Pending,1=Approve,2=Reject',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Science', 1, '2020-04-13 23:29:12', '2020-04-13 23:29:12'),
(4, 'abc update', 1, '2020-04-19 11:55:20', '2020-04-19 05:55:20');

-- --------------------------------------------------------

--
-- Table structure for table `chapters`
--

CREATE TABLE `chapters` (
  `id` int(10) UNSIGNED NOT NULL,
  `ebook_id` int(11) NOT NULL,
  `sequence` int(11) NOT NULL DEFAULT '1',
  `book` text,
  `image` text,
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '0=Pending,1=Approve,2=Reject',
  `reject_note` text,
  `reopen_note` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `chapters`
--

INSERT INTO `chapters` (`id`, `ebook_id`, `sequence`, `book`, `image`, `status`, `reject_note`, `reopen_note`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Test chapter 1', NULL, 1, NULL, NULL, '2020-04-23 09:44:47', '2020-04-23 03:44:47');

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '0=Pending,1=Approve,2=Reject',
  `country_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `classifieds`
--

CREATE TABLE `classifieds` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` int(11) NOT NULL COMMENT '1=House Rent, 2=House Rent, 3=Product sale, 4=Other',
  `category_id` int(11) NOT NULL DEFAULT '0',
  `contact` varchar(20) DEFAULT NULL,
  `email` varchar(70) DEFAULT NULL,
  `title` text NOT NULL,
  `description` text,
  `image` text,
  `price` float(8,2) NOT NULL DEFAULT '0.00',
  `city_id` int(11) NOT NULL DEFAULT '0',
  `address` text,
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '0=Pending,1=Approve,2=Reject',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `classified_galleries`
--

CREATE TABLE `classified_galleries` (
  `id` int(10) UNSIGNED NOT NULL,
  `post_id` int(11) NOT NULL,
  `image` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `comment` text,
  `comment_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '0=Pending,1=Active,2=Reject',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `user_id`, `post_id`, `comment`, `comment_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'This is post comment', 0, 1, '2020-04-23 09:00:54', '2020-04-23 03:00:54');

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '0=Pending,1=Approve,2=Reject',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ebooks`
--

CREATE TABLE `ebooks` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `language` varchar(100) DEFAULT NULL,
  `author_id` int(11) NOT NULL,
  `publication_date` date DEFAULT NULL,
  `ebook_summery` text,
  `author_summery` text,
  `number_of_chapter` int(11) NOT NULL DEFAULT '1',
  `preface` text,
  `font_image` text,
  `back_image` text,
  `price` float(8,2) NOT NULL DEFAULT '0.00',
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '0=Pending,1=Approve,2=Reject',
  `reject_note` text,
  `reopen_note` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ebooks`
--

INSERT INTO `ebooks` (`id`, `user_id`, `category_id`, `title`, `language`, `author_id`, `publication_date`, `ebook_summery`, `author_summery`, `number_of_chapter`, `preface`, `font_image`, `back_image`, `price`, `status`, `reject_note`, `reopen_note`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Test Book Title', 'English', 1, '2020-04-25', 'Test ebook summery', 'Test author summery', 5, 'Test preface', NULL, NULL, 200.00, 0, NULL, NULL, '2020-04-20 13:09:32', '2020-04-20 07:09:32');

-- --------------------------------------------------------

--
-- Table structure for table `edu_infos`
--

CREATE TABLE `edu_infos` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `degree` varchar(100) DEFAULT NULL,
  `institute` varchar(150) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `passing_year` date DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1=Complete, 2=Continue, 3=Incomplete',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `image` text,
  `creation_date` date DEFAULT NULL,
  `expiration_date` date DEFAULT NULL,
  `view` int(11) NOT NULL DEFAULT '0',
  `follow` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '0=Pending,1=Approve,2=Reject',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pdt_cats`
--

CREATE TABLE `pdt_cats` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` int(11) NOT NULL DEFAULT '0' COMMENT '1=Post, 2=News link, 3=Opinion, 4=Video, 5=Image, 6=Content post, 7=Other',
  `category_id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL DEFAULT '0',
  `title` text,
  `description` text,
  `newslink` text,
  `video` text,
  `image` text,
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '0=Pending,1=Approve,2=Reject',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `type`, `category_id`, `page_id`, `title`, `description`, `newslink`, `video`, `image`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 1, 0, 'Test Post Title', 'Test description', 'https://www.prothomalo.com/', NULL, NULL, 1, '2020-04-23 07:31:46', '2020-04-23 01:31:46');

-- --------------------------------------------------------

--
-- Table structure for table `post_galleries`
--

CREATE TABLE `post_galleries` (
  `id` int(10) UNSIGNED NOT NULL,
  `post_id` int(11) NOT NULL,
  `image` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `preferences`
--

CREATE TABLE `preferences` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `ebook_id` int(11) NOT NULL,
  `comment` text,
  `rating` int(11) NOT NULL DEFAULT '1',
  `review_id` int(11) NOT NULL DEFAULT '0',
  `like` int(11) NOT NULL DEFAULT '0',
  `dislike` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '0=Pending,1=Approve,2=Reject',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `contact` varchar(30) DEFAULT NULL,
  `email` varchar(70) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `image` text,
  `balance` float(8,2) NOT NULL DEFAULT '0.00',
  `type` int(11) NOT NULL DEFAULT '1' COMMENT '1=User, 2=Support, 3=Admin',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '0=Pending,1=Active, 2=Block, 3=Reject',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `name`, `contact`, `email`, `password`, `image`, `balance`, `type`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Md Foysal', 'Foysal', '0155455444', 'foysal@adfadf.com', '900150983cd24fb0d6963f7d28e17f72', NULL, 0.00, 1, 1, '2020-04-29 06:59:57', '2020-04-19 01:44:15'),
(3, 'Md Foysal', 'Foysal', '01555545', 'foysal@beatnikbd.com', '$2y$10$.5AozD2PbhnL9jkNPtsCLeSA1LJeSGsHxROHqAI5oru4AKp0HyOyK', NULL, 0.00, 1, 1, '2020-04-29 03:59:55', '2020-04-29 03:59:55');

-- --------------------------------------------------------

--
-- Table structure for table `user_infos`
--

CREATE TABLE `user_infos` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL DEFAULT '0',
  `city_id` int(11) NOT NULL DEFAULT '0',
  `address` text,
  `dob` date DEFAULT NULL,
  `gander` varchar(20) DEFAULT NULL,
  `bio` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_infos`
--

INSERT INTO `user_infos` (`id`, `user_id`, `country_id`, `city_id`, `address`, `dob`, `gander`, `bio`, `created_at`, `updated_at`) VALUES
(1, 2, 0, 0, NULL, '2020-02-14', NULL, NULL, '2020-04-29 01:10:00', '2020-04-29 01:10:00'),
(2, 3, 0, 0, NULL, '2020-04-15', NULL, NULL, '2020-04-29 03:59:55', '2020-04-29 03:59:55');

-- --------------------------------------------------------

--
-- Table structure for table `work_exps`
--

CREATE TABLE `work_exps` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `job_title` varchar(100) NOT NULL,
  `company` varchar(100) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1=Present, 2=Past',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ads`
--
ALTER TABLE `ads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ad_budgets`
--
ALTER TABLE `ad_budgets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `audience_selects`
--
ALTER TABLE `audience_selects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `authors`
--
ALTER TABLE `authors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chapters`
--
ALTER TABLE `chapters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `classifieds`
--
ALTER TABLE `classifieds`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `classified_galleries`
--
ALTER TABLE `classified_galleries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ebooks`
--
ALTER TABLE `ebooks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `edu_infos`
--
ALTER TABLE `edu_infos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pdt_cats`
--
ALTER TABLE `pdt_cats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `post_galleries`
--
ALTER TABLE `post_galleries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `preferences`
--
ALTER TABLE `preferences`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_infos`
--
ALTER TABLE `user_infos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `work_exps`
--
ALTER TABLE `work_exps`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ads`
--
ALTER TABLE `ads`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ad_budgets`
--
ALTER TABLE `ad_budgets`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `audience_selects`
--
ALTER TABLE `audience_selects`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `authors`
--
ALTER TABLE `authors`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `chapters`
--
ALTER TABLE `chapters`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `classifieds`
--
ALTER TABLE `classifieds`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `classified_galleries`
--
ALTER TABLE `classified_galleries`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ebooks`
--
ALTER TABLE `ebooks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `edu_infos`
--
ALTER TABLE `edu_infos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pdt_cats`
--
ALTER TABLE `pdt_cats`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `post_galleries`
--
ALTER TABLE `post_galleries`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `preferences`
--
ALTER TABLE `preferences`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_infos`
--
ALTER TABLE `user_infos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `work_exps`
--
ALTER TABLE `work_exps`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
