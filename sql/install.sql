-- phpMyAdmin SQL Dump
-- version 4.7.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 27, 2017 at 01:54 PM
-- Server version: 5.6.38
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------

--
-- Table structure for table `AuthAssignment`
--

CREATE TABLE `AuthAssignment` (
  `item_name` varchar(64) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `AuthItem`
--

CREATE TABLE `AuthItem` (
  `name` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `description` text,
  `rule_name` varchar(64) DEFAULT NULL,
  `data` text,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `AuthItemChild`
--

CREATE TABLE `AuthItemChild` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `AuthRule`
--

CREATE TABLE `AuthRule` (
  `name` varchar(64) NOT NULL,
  `data` text,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `image`
--

CREATE TABLE `image` (
  `id` int(11) NOT NULL,
  `filePath` varchar(400) NOT NULL,
  `itemId` int(20) NOT NULL,
  `isMain` int(1) DEFAULT NULL,
  `modelName` varchar(150) NOT NULL,
  `urlAlias` varchar(400) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ImageManager`
--

CREATE TABLE `ImageManager` (
  `id` int(10) UNSIGNED NOT NULL,
  `fileName` varchar(128) NOT NULL,
  `fileHash` varchar(32) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime DEFAULT NULL,
  `createdBy` int(10) UNSIGNED DEFAULT NULL,
  `modifiedBy` int(10) UNSIGNED DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ingredient_store_section`
--

CREATE TABLE `ingredient_store_section` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `migration`
--

CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `profile_field`
--

CREATE TABLE `profile_field` (
  `id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `type_id` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  `required` tinyint(1) NOT NULL,
  `configuration` text,
  `error_message` varchar(255) DEFAULT NULL,
  `default_value` varchar(255) DEFAULT NULL,
  `read_only` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `profile_field_type`
--

CREATE TABLE `profile_field_type` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `profile_field_value`
--

CREATE TABLE `profile_field_value` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `field_id` int(11) NOT NULL,
  `value` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `quick_meals`
--

CREATE TABLE `quick_meals` (
  `id` int(8) NOT NULL,
  `name` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `recipe`
--

CREATE TABLE `recipe` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `rating` int(1) DEFAULT NULL,
  `ImageManager_image_id` int(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `recipe_ingredient`
--

CREATE TABLE `recipe_ingredient` (
  `id` int(11) NOT NULL,
  `recipe_id` int(11) NOT NULL,
  `ingredient` text,
  `ingredient_store_section_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `recipe_planner`
--

CREATE TABLE `recipe_planner` (
  `id` int(11) NOT NULL,
  `recipe_id` int(11) NOT NULL,
  `date` timestamp NULL DEFAULT NULL,
  `timeofday` varchar(25) NOT NULL DEFAULT 'Dinner'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `recipe_planner_ingredient`
--

CREATE TABLE `recipe_planner_ingredient` (
  `id` int(11) NOT NULL,
  `recipe_ingredient_id` int(11) NOT NULL,
  `collected` tinyint(1) NOT NULL DEFAULT '0',
  `recipe_planner_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `recipe_step`
--

CREATE TABLE `recipe_step` (
  `id` int(11) NOT NULL,
  `recipe_id` int(11) NOT NULL,
  `step` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(64) NOT NULL,
  `email` varchar(128) NOT NULL,
  `password_hash` varchar(128) NOT NULL,
  `password_reset_token` varchar(48) DEFAULT NULL,
  `auth_key` varchar(128) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '2',
  `last_visit_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `delete_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `AuthAssignment`
--
ALTER TABLE `AuthAssignment`
  ADD PRIMARY KEY (`item_name`,`user_id`);

--
-- Indexes for table `AuthItem`
--
ALTER TABLE `AuthItem`
  ADD PRIMARY KEY (`name`),
  ADD KEY `AuthItem_rule_name_fk` (`rule_name`),
  ADD KEY `AuthItem_type_idx` (`type`);

--
-- Indexes for table `AuthItemChild`
--
ALTER TABLE `AuthItemChild`
  ADD PRIMARY KEY (`parent`,`child`),
  ADD KEY `AuthItemChild_child_fk` (`child`);

--
-- Indexes for table `AuthRule`
--
ALTER TABLE `AuthRule`
  ADD PRIMARY KEY (`name`);

--
-- Indexes for table `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ImageManager`
--
ALTER TABLE `ImageManager`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ingredient_store_section`
--
ALTER TABLE `ingredient_store_section`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migration`
--
ALTER TABLE `migration`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `profile_field`
--
ALTER TABLE `profile_field`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ProfileField_name_uk` (`name`),
  ADD KEY `ProfileField_type_ix` (`type_id`);

--
-- Indexes for table `profile_field_type`
--
ALTER TABLE `profile_field_type`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ProfileFieldType_name_uk` (`name`);

--
-- Indexes for table `profile_field_value`
--
ALTER TABLE `profile_field_value`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Profile_field_ix` (`field_id`),
  ADD KEY `Profile_user_fk` (`user_id`);

--
-- Indexes for table `quick_meals`
--
ALTER TABLE `quick_meals`
  ADD KEY `id` (`id`);

--
-- Indexes for table `recipe`
--
ALTER TABLE `recipe`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `recipe_ingredient`
--
ALTER TABLE `recipe_ingredient`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ingredient_store_section_id` (`ingredient_store_section_id`),
  ADD KEY `recipe_id` (`recipe_id`);

--
-- Indexes for table `recipe_planner`
--
ALTER TABLE `recipe_planner`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recipe_id` (`recipe_id`);

--
-- Indexes for table `recipe_planner_ingredient`
--
ALTER TABLE `recipe_planner_ingredient`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recipe_ingredient_id` (`recipe_ingredient_id`),
  ADD KEY `recipe_planner_id` (`recipe_planner_id`);

--
-- Indexes for table `recipe_step`
--
ALTER TABLE `recipe_step`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recipe_id` (`recipe_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `User_status_ix` (`status`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `image`
--
ALTER TABLE `image`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;
--
-- AUTO_INCREMENT for table `ImageManager`
--
ALTER TABLE `ImageManager`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;
--
-- AUTO_INCREMENT for table `ingredient_store_section`
--
ALTER TABLE `ingredient_store_section`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT for table `profile_field`
--
ALTER TABLE `profile_field`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `profile_field_type`
--
ALTER TABLE `profile_field_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `profile_field_value`
--
ALTER TABLE `profile_field_value`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `recipe`
--
ALTER TABLE `recipe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=140;
--
-- AUTO_INCREMENT for table `recipe_ingredient`
--
ALTER TABLE `recipe_ingredient`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1407;
--
-- AUTO_INCREMENT for table `recipe_planner`
--
ALTER TABLE `recipe_planner`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=285;
--
-- AUTO_INCREMENT for table `recipe_planner_ingredient`
--
ALTER TABLE `recipe_planner_ingredient`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3047;
--
-- AUTO_INCREMENT for table `recipe_step`
--
ALTER TABLE `recipe_step`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=661;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `AuthAssignment`
--
ALTER TABLE `AuthAssignment`
  ADD CONSTRAINT `AuthAssignment_item_name_fk` FOREIGN KEY (`item_name`) REFERENCES `AuthItem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `AuthItem`
--
ALTER TABLE `AuthItem`
  ADD CONSTRAINT `AuthItem_rule_name_fk` FOREIGN KEY (`rule_name`) REFERENCES `AuthRule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `AuthItemChild`
--
ALTER TABLE `AuthItemChild`
  ADD CONSTRAINT `AuthItemChild_child_fk` FOREIGN KEY (`child`) REFERENCES `AuthItem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `AuthItemChild_parent_fk` FOREIGN KEY (`parent`) REFERENCES `AuthItem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `profile_field`
--
ALTER TABLE `profile_field`
  ADD CONSTRAINT `ProfileField_type_fk` FOREIGN KEY (`type_id`) REFERENCES `profile_field_type` (`id`);

--
-- Constraints for table `profile_field_value`
--
ALTER TABLE `profile_field_value`
  ADD CONSTRAINT `Profile_field_fk` FOREIGN KEY (`field_id`) REFERENCES `profile_field` (`id`),
  ADD CONSTRAINT `Profile_user_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `recipe_ingredient`
--
ALTER TABLE `recipe_ingredient`
  ADD CONSTRAINT `recipe_ingredient_ibfk_1` FOREIGN KEY (`recipe_id`) REFERENCES `recipe` (`id`),
  ADD CONSTRAINT `recipe_ingredient_ibfk_2` FOREIGN KEY (`ingredient_store_section_id`) REFERENCES `ingredient_store_section` (`id`);

--
-- Constraints for table `recipe_planner`
--
ALTER TABLE `recipe_planner`
  ADD CONSTRAINT `recipe_planner_ibfk_1` FOREIGN KEY (`recipe_id`) REFERENCES `recipe` (`id`);

--
-- Constraints for table `recipe_planner_ingredient`
--
ALTER TABLE `recipe_planner_ingredient`
  ADD CONSTRAINT `recipe_planner_ingredient_ibfk_1` FOREIGN KEY (`recipe_ingredient_id`) REFERENCES `recipe_ingredient` (`id`),
  ADD CONSTRAINT `recipe_planner_ingredient_ibfk_2` FOREIGN KEY (`recipe_planner_id`) REFERENCES `recipe_planner` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
