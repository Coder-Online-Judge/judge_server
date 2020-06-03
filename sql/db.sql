-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 03, 2020 at 12:47 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `judge_server`
--

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `languageId` int(11) NOT NULL,
  `languageName` text NOT NULL,
  `languageArgument` text NOT NULL,
  `isArchived` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`languageId`, `languageName`, `languageArgument`, `isArchived`) VALUES
(1, 'C', 'C', 1),
(2, 'C++', 'CPP', 0),
(3, 'C++11', 'CPP11', 1),
(4, 'C++14', 'CPP14', 1),
(5, 'Java', 'JAVA', 1);

-- --------------------------------------------------------

--
-- Table structure for table `submissions`
--

CREATE TABLE `submissions` (
  `submissionId` int(11) NOT NULL,
  `token` text DEFAULT NULL,
  `sourceCode` text NOT NULL,
  `languageId` int(11) NOT NULL,
  `timeLimit` double NOT NULL DEFAULT 2,
  `memoryLimit` int(11) NOT NULL,
  `time` double NOT NULL DEFAULT 0,
  `memory` int(11) NOT NULL DEFAULT 0,
  `verdictId` int(11) NOT NULL DEFAULT 1,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `verdict`
--

CREATE TABLE `verdict` (
  `verdictId` int(11) NOT NULL,
  `verdictStatus` text NOT NULL,
  `verdictDescription` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `verdict`
--

INSERT INTO `verdict` (`verdictId`, `verdictStatus`, `verdictDescription`) VALUES
(1, 'QUEUE', 'In Queue'),
(2, 'PROCESS', 'Processing'),
(3, 'AC', 'Accepted'),
(4, 'WA', 'Wrong Answer'),
(5, 'TLE', 'Time Limit Exceeded'),
(6, 'CE', 'Compilation Error'),
(7, 'RTE', 'Runtime Error'),
(13, 'MLE', 'Memory Limit Exceeded'),
(14, 'EFE', 'Exec Format Error'),
(15, 'OLE', 'Output Limit Exceeded'),
(16, 'IE', 'Internal Error');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`languageId`);

--
-- Indexes for table `submissions`
--
ALTER TABLE `submissions`
  ADD PRIMARY KEY (`submissionId`),
  ADD KEY `FK_Language` (`languageId`),
  ADD KEY `FK_Verdict` (`verdictId`);

--
-- Indexes for table `verdict`
--
ALTER TABLE `verdict`
  ADD PRIMARY KEY (`verdictId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `languageId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `submissions`
--
ALTER TABLE `submissions`
  MODIFY `submissionId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `verdict`
--
ALTER TABLE `verdict`
  MODIFY `verdictId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `submissions`
--
ALTER TABLE `submissions`
  ADD CONSTRAINT `FK_Language` FOREIGN KEY (`languageId`) REFERENCES `languages` (`languageId`),
  ADD CONSTRAINT `FK_Verdict` FOREIGN KEY (`verdictId`) REFERENCES `verdict` (`verdictId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;