-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 27, 2016 at 03:41 PM
-- Server version: 5.5.50-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `example_data`
--
CREATE DATABASE IF NOT EXISTS `example_data` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `example_data`;

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

DROP TABLE IF EXISTS `articles`;
CREATE TABLE IF NOT EXISTS `articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(300) NOT NULL,
  `title` text NOT NULL,
  `content` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Truncate table before insert `articles`
--

TRUNCATE TABLE `articles`;
--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `slug`, `title`, `content`) VALUES
(1, 'example-article', 'Example Article Title', 'Example article content.');

INSERT INTO `articles` (`id`, `slug`, `title`, `content`) VALUES
(2, 'example-article-2', 'Example Article Title 2', 'Example article content 2.');
