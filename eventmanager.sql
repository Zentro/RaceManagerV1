-- phpMyAdmin SQL Dump
-- https://www.phpmyadmin.net/
--
-- Generation Time: Oct 14, 2022 at 01:59 AM
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `eventmanager`
--

-- --------------------------------------------------------

--
-- Table structure for table `race`
--

CREATE TABLE `race` (
  `id` int(11) UNSIGNED NOT NULL,
  `username` text NOT NULL,
  `country` varchar(16) NOT NULL,
  `terrain` text NOT NULL,
  `terrain_filename` text NOT NULL,
  `script` text NOT NULL,
  `script_hash` text NOT NULL,
  `actor` text NOT NULL,
  `actor_filename` text NOT NULL,
  `actor_hash` text NOT NULL,
  `average_fps` text NOT NULL,
  `ror_version` text NOT NULL,
  `split_times` text NOT NULL,
  `lap_time` text NOT NULL,
  `race_name` varchar(64) NOT NULL,
  `race_version` varchar(64) NOT NULL,
  `date` text NOT NULL,
  `request_tracking_guid` varchar(255) NOT NULL,
  `ipv4` varchar(255) NOT NULL,
  `disqualified` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `race`
--
ALTER TABLE `race`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `race`
--
ALTER TABLE `race`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
