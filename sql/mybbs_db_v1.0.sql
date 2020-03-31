-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 2020 年 3 朁E17 日 02:43
-- サーバのバージョン： 10.1.38-MariaDB
-- PHP Version: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mybbs_db`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `board_table`
--

CREATE TABLE `board_table` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `board_table`
--

INSERT INTO `board_table` (`id`, `name`, `date`, `content`) VALUES
(8, 'test1', '2020-03-16 04:29:04', 'ãƒ†ã‚¹ãƒˆ'),
(9, 'ãƒ†ã‚¹ãƒˆ2', '2020-03-16 04:29:18', 'TEST'),
(10, 'ã¦ã™ã¨ï¼“', '2020-03-16 04:29:38', 'ãƒ†ã‚¹ãƒˆï¼“'),
(15, 'TEST4', '2020-03-16 04:29:58', 'TESTtest'),
(16, 'Tesuto', '2020-03-16 04:30:31', 'Tesuto Test'),
(17, 'ã‚ã‚', '2020-03-13 07:45:11', 'ã‚ã‚ã‚ã‚ã‚ã‚ã‚ã‚ã‚ã‚'),
(18, 'test6', '2020-03-16 05:23:05', 'test6\r\n'),
(19, 'test7', '2020-03-16 06:52:42', 'testtesttesttesttesttesttest'),
(20, 'ã¦ã™ã¨ï¼˜', '2020-03-16 06:53:25', 'ã¦ãˆãˆãˆãˆãˆãˆãˆãˆãˆãˆãˆãˆãˆãˆãˆãˆãˆãˆãˆãˆãˆãˆãˆãˆãˆãˆã™ã¨');

-- --------------------------------------------------------

--
-- テーブルの構造 `comment_table`
--

CREATE TABLE `comment_table` (
  `id` int(11) NOT NULL,
  `comment_name` varchar(20) NOT NULL,
  `comment_content` text NOT NULL,
  `comment_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `content_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `board_table`
--
ALTER TABLE `board_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comment_table`
--
ALTER TABLE `comment_table`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `board_table`
--
ALTER TABLE `board_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `comment_table`
--
ALTER TABLE `comment_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
