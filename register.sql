-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- 主機: localhost
-- 產生時間： 2017-11-17 08:51:05
-- 伺服器版本: 5.7.17-log
-- PHP 版本： 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `register`
--
CREATE DATABASE IF NOT EXISTS `register` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `register`;

-- --------------------------------------------------------

--
-- 資料表結構 `clinic_hour`
--

CREATE TABLE IF NOT EXISTS `clinic_hour` (
  `week_day` tinyint(4) NOT NULL,
  `am_pm` char(2) COLLATE utf8_unicode_ci NOT NULL,
  `room` tinyint(4) NOT NULL,
  `department` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `doctor` varchar(12) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`week_day`,`am_pm`,`room`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 資料表的匯出資料 `clinic_hour`
--

INSERT INTO `clinic_hour` VALUES
(1, 'am', 101, '內科', '彭小芸'),
(1, 'am', 102, '內科', '張大榮'),
(1, 'am', 103, '小兒科', '李小瑩'),
(1, 'am', 104, '外科', '郭大恆'),
(1, 'am', 107, '復健科', '顏大卿'),
(1, 'pm', 101, '內科', '鍾大勳'),
(1, 'pm', 104, '外科', '汪大年'),
(1, 'pm', 105, '外科', '歐陽大禎'),
(2, 'am', 101, '內科', '張大嘉'),
(2, 'am', 103, '小兒科', '彭小芸'),
(2, 'am', 104, '外科', '王大邦'),
(2, 'am', 107, '復健科', '顏大卿'),
(2, 'am', 108, '中醫科', '戴小雲'),
(2, 'pm', 104, '外科', '李大宏'),
(3, 'am', 101, '內科', '王大仁'),
(3, 'am', 104, '外科', '李大鋒'),
(3, 'am', 105, '外科', '王大邦'),
(3, 'am', 106, '外科', '歐陽大禎'),
(3, 'am', 107, '復健科', '顏大卿'),
(3, 'am', 108, '中醫科', '戴小雲'),
(3, 'pm', 101, '內科', '張大榮'),
(3, 'pm', 102, '內科', '呂大坤'),
(3, 'pm', 103, '小兒科', '李小瑩'),
(3, 'pm', 107, '復健科', '顏大卿'),
(3, 'pm', 108, '中醫科', '戴小雲'),
(3, 'pm', 109, '眼科', '張小清'),
(4, 'am', 101, '內科', '蕭大獻'),
(4, 'am', 103, '小兒科', '李小瑩'),
(4, 'am', 104, '外科', '郭大恆'),
(4, 'am', 105, '外科', '林大仲'),
(4, 'am', 108, '中醫科', '戴小雲'),
(4, 'pm', 101, '內科', '張大嘉'),
(4, 'pm', 103, '小兒科', '彭小芸'),
(4, 'pm', 104, '外科', '李大鋒'),
(5, 'am', 101, '內科', '呂大坤'),
(5, 'am', 104, '外科', '鍾大勳'),
(5, 'am', 107, '復健科', '顏大卿'),
(5, 'am', 108, '中醫科', '戴小雲'),
(5, 'pm', 103, '小兒科', '李小瑩'),
(5, 'pm', 104, '外科', '王大邦'),
(5, 'pm', 105, '外科', '李大鋒'),
(5, 'pm', 108, '中醫科', '戴小雲'),
(6, 'am', 101, '內科', '張大嘉');

-- --------------------------------------------------------

--
-- 資料表結構 `patient`
--

CREATE TABLE IF NOT EXISTS `patient` (
  `patient_id` char(10) COLLATE utf8_unicode_ci NOT NULL,
  `birthday` date NOT NULL,
  `phone` varchar(12) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`patient_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 資料表的匯出資料 `patient`
--

INSERT INTO `patient` VALUES
('A123456787', '1978-02-01', '0912345678');

-- --------------------------------------------------------

--
-- 資料表結構 `room_info`
--

CREATE TABLE IF NOT EXISTS `room_info` (
  `date` date NOT NULL,
  `am_pm` char(2) COLLATE utf8_unicode_ci NOT NULL,
  `room` tinyint(4) NOT NULL,
  `doctor` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `patient_id` char(10) COLLATE utf8_unicode_ci NOT NULL,
  `register_no` int(11) NOT NULL,
  PRIMARY KEY (`date`,`am_pm`,`room`,`patient_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 資料表的匯出資料 `room_info`
--

INSERT INTO `room_info` VALUES
('2017-11-08', 'am', 101, '王大仁', 'A123456787', 1),
('2017-11-08', 'am', 104, '李大鋒', 'A123456788', 2),
('2017-11-08', 'am', 104, '李大鋒', 'A123456789', 1),
('2017-11-08', 'pm', 102, '呂大坤', 'C123456787', 2),
('2017-11-08', 'pm', 102, '呂大坤', 'C123456788', 1),
('2017-11-09', 'pm', 101, '張大嘉', 'C123456789', 1),
('2017-11-20', 'am', 101, '彭小芸', 'A333444555', 1),
('2017-11-20', 'am', 102, '張大榮', 'A111111111', 2),
('2017-11-20', 'am', 102, '張大榮', 'A333444555', 1),
('2017-11-24', 'am', 101, '呂大坤', 'A222333444', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
