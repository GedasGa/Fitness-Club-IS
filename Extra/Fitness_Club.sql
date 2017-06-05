-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 05, 2017 at 05:52 PM
-- Server version: 5.7.18-0ubuntu0.16.04.1
-- PHP Version: 7.0.18-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Fitness_Club`
--

-- --------------------------------------------------------

--
-- Table structure for table `ADDRESS`
--

CREATE TABLE `ADDRESS` (
  `id_address` int(11) NOT NULL,
  `street` varchar(30) NOT NULL,
  `house_number` varchar(11) NOT NULL,
  `post_code` int(8) NOT NULL,
  `fk_city_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ADDRESS`
--

INSERT INTO `ADDRESS` (`id_address`, `street`, `house_number`, `post_code`, `fk_city_id`) VALUES
(1, 'Kalvariju g.', '69a', 745987, 1),
(2, 'Savanoriu pr.', '345b', 956241, 1),
(3, 'Konstitucijos pr.', '30', 541275, 1),
(4, 'Sananoriu pr.', '442', 745622, 2),
(5, 'Karaliaus Mindaugo pr.', '146', 746267, 2),
(6, 'Siaures pr.', '25', 546672, 3),
(7, 'Amerikos g.', '1a', 546123, 3),
(8, 'Gangnam', '222A', 5554, 4),
(9, 'Gangnam', '222A', 5554, 4);

-- --------------------------------------------------------

--
-- Table structure for table `CITY`
--

CREATE TABLE `CITY` (
  `id_city` int(11) NOT NULL,
  `city` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `CITY`
--

INSERT INTO `CITY` (`id_city`, `city`) VALUES
(1, 'Vilnius'),
(2, 'Kaunas'),
(3, 'Klaipeda'),
(4, 'Seoul');

-- --------------------------------------------------------

--
-- Table structure for table `CUSTOMER`
--

CREATE TABLE `CUSTOMER` (
  `personal_id` bigint(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `surname` varchar(20) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `email` varchar(30) NOT NULL,
  `foto` varchar(255) NOT NULL,
  `first_registration` date NOT NULL,
  `social_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `CUSTOMER`
--

INSERT INTO `CUSTOMER` (`personal_id`, `name`, `surname`, `phone_number`, `email`, `foto`, `first_registration`, `social_status`) VALUES
(351454466, 'Minseok', 'Parks', '010555506', 'mini@gmail.com', '', '2017-05-11', 3),
(38912215513, 'Jonas', 'Karusis', '869076785', 'karusis@gmail.com', 'foto url', '2016-12-17', 3),
(39311215513, 'Jonas', 'Durnelis', '869045785', 'jonelisdurnelis@gmail.com', 'foto url', '2014-11-15', 2),
(39510215512, 'Antanas', 'Siaulys', '865430545', 'antanas.siaulys@gmail.com', 'foto url', '2016-01-01', 3),
(39512215513, 'Petras', 'Stasiulis', '863214780', 'stasiulis@gmail.com', 'foto url', '2014-10-01', 3),
(39512215578, 'Jonas', 'Antanaitis', '869576785', 'andriusis@yahoo.com', 'foto url', '2016-01-15', 4),
(39512217513, 'Justas', 'Baltrusaitis', '865430521', 'justasb@gmail.com', 'foto url', '2016-03-10', 2),
(47512215513, 'Urte', 'Andriuskyte', '869045781', 'raudonplauke@gmail.com', 'foto url', '2013-10-15', 3),
(48510215513, 'Agne', 'Ziburiene', '869045781', 'agnieska@gmail.com', 'foto url', '2014-11-10', 4),
(49411115513, 'Migle', 'Pauliukaite', '869035781', 'miglepauliuk@gmail.com', 'foto url', '2013-01-01', 1),
(49412115513, 'Ugne', 'Stasiulyte', '869786281', 'ugne69@gmail.com', 'foto url', '2015-10-01', 2);

-- --------------------------------------------------------

--
-- Table structure for table `EMPLOYEE`
--

CREATE TABLE `EMPLOYEE` (
  `personal_id` bigint(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `surname` varchar(20) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `email` varchar(30) NOT NULL,
  `recruitment_date` date NOT NULL,
  `position` int(16) NOT NULL,
  `fk_fitness_club_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `EMPLOYEE`
--

INSERT INTO `EMPLOYEE` (`personal_id`, `name`, `surname`, `phone_number`, `email`, `recruitment_date`, `position`, `fk_fitness_club_id`) VALUES
(45145444, 'Hanpyo', 'Kim', '010555500', 'animal@naver.com', '2017-03-01', 2, 8),
(38512208443, 'Stasys', 'Pazereckas', '865078942', 'staska@gmail.com', '2012-12-05', 1, 1),
(38512208743, 'Almantas', 'Jurgis', '865024942', 'jurgis66@gmail.com', '2015-12-05', 2, 2),
(39505128853, 'Petras', 'Jonaitis', '865072401', 'p.jonaitis@gmail.com', '2014-10-24', 2, 7),
(39510118753, 'Julius', 'Alisauskas', '865072401', 'julius55@gmail.com', '2014-05-25', 2, 6),
(39512078753, 'Justas', 'Petrauskas', '865078978', 'justelis@gmail.com', '2013-05-25', 2, 4),
(39512178753, 'Mantas', 'Petrusaitis', '865078942', 'mantelio@gmail.com', '2013-05-05', 3, 4),
(48506208743, 'Julija', 'Jurgaityte', '865078942', 'jurgaituke@gmail.com', '2015-12-05', 1, 2),
(48511248573, 'Egle', 'Jankauskaite', '863878941', 'e.jankauskaite@gmail.com', '2014-10-05', 1, 3),
(49010148872, 'Gintare', 'Eskyte', '863890640', 'gintare.e@gmail.com', '2016-01-01', 2, 3),
(49510118753, 'Milda', 'Aliosaite', '865078978', 'mildaaaa@gmail,com', '2014-05-25', 1, 5);

-- --------------------------------------------------------

--
-- Table structure for table `ENTRANCE_TIME`
--

CREATE TABLE `ENTRANCE_TIME` (
  `id_entrance_time` int(11) NOT NULL,
  `weekday` varchar(20) NOT NULL,
  `from` time NOT NULL,
  `till` time NOT NULL,
  `fk_subscription_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ENTRANCE_TIME`
--

INSERT INTO `ENTRANCE_TIME` (`id_entrance_time`, `weekday`, `from`, `till`, `fk_subscription_id`) VALUES
(1, 'pirmadienis', '07:00:00', '15:00:00', 1),
(2, 'pirmadienis', '07:00:00', '15:00:00', 2),
(3, 'pirmadienis', '07:00:00', '15:00:00', 3),
(4, 'pirmadienis', '07:00:00', '15:00:00', 4),
(5, 'pirmadienis', '07:00:00', '15:00:00', 5),
(6, 'pirmadienis', '07:00:00', '15:00:00', 6),
(7, 'pirmadienis', '07:00:00', '15:00:00', 7),
(8, 'pirmadienis', '07:00:00', '15:00:00', 8),
(9, 'pirmadienis', '07:00:00', '15:00:00', 9),
(10, 'pirmadienis', '07:00:00', '15:00:00', 10),
(11, 'antradienis', '07:00:00', '15:00:00', 1),
(12, 'antradienis', '07:00:00', '15:00:00', 2),
(13, 'antradienis', '07:00:00', '15:00:00', 3),
(14, 'antradienis', '07:00:00', '15:00:00', 4),
(15, 'antradienis', '07:00:00', '15:00:00', 5),
(16, 'antradienis', '07:00:00', '15:00:00', 6),
(17, 'antradienis', '07:00:00', '15:00:00', 7),
(18, 'antradienis', '07:00:00', '15:00:00', 8),
(19, 'antradienis', '07:00:00', '15:00:00', 9),
(20, 'antradienis', '07:00:00', '15:00:00', 10),
(21, 'treciadienis', '10:00:00', '17:00:00', 1),
(22, 'treciadienis', '10:00:00', '17:00:00', 2),
(23, 'treciadienis', '10:00:00', '17:00:00', 3),
(24, 'treciadienis', '10:00:00', '17:00:00', 4),
(25, 'treciadienis', '10:00:00', '17:00:00', 5),
(26, 'treciadienis', '10:00:00', '17:00:00', 6),
(27, 'treciadienis', '10:00:00', '17:00:00', 7),
(28, 'treciadienis', '10:00:00', '17:00:00', 8),
(29, 'treciadienis', '10:00:00', '17:00:00', 9),
(30, 'treciadienis', '10:00:00', '17:00:00', 10),
(31, 'ketvirtadienis', '12:00:00', '18:00:00', 1),
(32, 'ketvirtadienis', '12:00:00', '17:00:00', 2),
(33, 'ketvirtadienis', '10:00:00', '17:00:00', 3),
(34, 'ketvirtadienis', '10:00:00', '17:00:00', 4),
(35, 'ketvirtadienis', '10:00:00', '17:00:00', 5),
(36, 'ketvirtadienis', '10:00:00', '15:00:00', 6),
(37, 'ketvirtadienis', '10:00:00', '15:00:00', 7),
(38, 'ketvirtadienis', '10:00:00', '15:00:00', 8),
(39, 'ketvirtadienis', '10:00:00', '15:00:00', 9),
(40, 'ketvirtadienis', '10:00:00', '17:00:00', 10),
(41, 'penktadienis', '10:00:00', '15:00:00', 1),
(42, 'penktadienis', '10:00:00', '15:00:00', 2),
(43, 'penktadienis', '10:00:00', '17:00:00', 3),
(44, 'penktadienis', '10:00:00', '17:00:00', 4),
(45, 'penktadienis', '10:00:00', '17:00:00', 5),
(46, 'penktadienis', '10:00:00', '15:00:00', 6),
(47, 'penktadienis', '12:00:00', '17:00:00', 7),
(48, 'penktadienis', '10:00:00', '17:00:00', 8),
(49, 'penktadienis', '10:00:00', '15:00:00', 9),
(50, 'penktadienis', '10:00:00', '17:00:00', 10),
(51, 'sestadienis', '10:00:00', '15:00:00', 1),
(52, 'sestadienis', '10:00:00', '15:00:00', 2),
(53, 'sestadienis', '10:00:00', '15:00:00', 3),
(54, 'sestadienis', '10:00:00', '15:00:00', 4),
(55, 'sestadienis', '10:00:00', '15:00:00', 5),
(56, 'sestadienis', '10:00:00', '15:00:00', 6),
(57, 'sestadienis', '10:00:00', '15:00:00', 7),
(58, 'sestadienis', '10:00:00', '15:00:00', 8),
(59, 'sestadienis', '10:00:00', '15:00:00', 9),
(60, 'sestadienis', '10:00:00', '15:00:00', 10),
(61, 'sekmadienis', '10:00:00', '15:00:00', 1),
(62, 'sekmadienis', '10:00:00', '15:00:00', 2),
(63, 'sekmadienis', '10:00:00', '15:00:00', 3),
(64, 'sekmadienis', '10:00:00', '15:00:00', 4),
(65, 'sekmadienis', '10:00:00', '15:00:00', 5),
(66, 'sekmadienis', '10:00:00', '15:00:00', 6),
(67, 'sekmadienis', '10:00:00', '15:00:00', 7),
(68, 'sekmadienis', '10:00:00', '15:00:00', 8),
(69, 'sekmadienis', '10:00:00', '15:00:00', 9),
(70, 'sekmadienis', '10:00:00', '15:00:00', 10),
(71, 'Sunday', '00:00:00', '00:00:00', 11);

-- --------------------------------------------------------

--
-- Table structure for table `FITNESS_CLUB`
--

CREATE TABLE `FITNESS_CLUB` (
  `id_fitness_club` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `features` varchar(255) NOT NULL,
  `fk_address_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `FITNESS_CLUB`
--

INSERT INTO `FITNESS_CLUB` (`id_fitness_club`, `name`, `features`, `fk_address_id`) VALUES
(1, 'Power Team Vilnius', 'iformacija apie sporto kluba', 1),
(2, 'Power Team Vilnius', 'iformacija apie sporto kluba', 2),
(3, 'Power Team Vilnius', 'informacija apie sporto kluba', 3),
(4, 'Power Team Kaunas', 'informacija apie sporto kluba', 4),
(5, 'Power Team Kaunas', 'informacija apie sporto kluba', 5),
(6, 'Power Team Klaipeda', 'informacija apie sporto kluba', 6),
(7, 'Power Team Klaipeda', 'informacija apie sporto kluba', 7),
(8, 'Golds Gym', 'Old School', 9);

-- --------------------------------------------------------

--
-- Table structure for table `INVOICE`
--

CREATE TABLE `INVOICE` (
  `number` int(11) NOT NULL,
  `invoice_date` date NOT NULL,
  `invoice_amount` decimal(8,2) NOT NULL,
  `fk_subscription_id` int(11) NOT NULL,
  `fk_employee_id` bigint(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `INVOICE`
--

INSERT INTO `INVOICE` (`number`, `invoice_date`, `invoice_amount`, `fk_subscription_id`, `fk_employee_id`) VALUES
(1, '2015-01-02', '35.00', 1, 38512208443),
(2, '2016-01-02', '70.00', 2, 38512208443),
(3, '2016-02-12', '39.00', 3, 38512208743),
(4, '2015-05-12', '25.00', 4, 39505128853),
(5, '2015-07-12', '350.00', 5, 38512208743),
(6, '2014-05-12', '70.00', 6, 49510118753),
(7, '2015-05-12', '35.00', 7, 39505128853),
(8, '2015-05-29', '35.00', 8, 38512208743),
(9, '2016-02-02', '25.00', 9, 48511248573),
(10, '2016-03-02', '39.00', 10, 39512178753),
(11, '2017-06-01', '45.00', 11, 45145444);

-- --------------------------------------------------------

--
-- Table structure for table `PAYMENT`
--

CREATE TABLE `PAYMENT` (
  `id_payment` int(11) NOT NULL,
  `payment_date` date NOT NULL,
  `payment_amount` double(8,2) NOT NULL,
  `fk_customer_id` bigint(11) NOT NULL,
  `fk_invoice_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `PAYMENT`
--

INSERT INTO `PAYMENT` (`id_payment`, `payment_date`, `payment_amount`, `fk_customer_id`, `fk_invoice_id`) VALUES
(1, '2015-01-12', 25.00, 38912215513, 1),
(2, '2016-01-02', 25.00, 39311215513, 2),
(3, '2015-10-02', 39.00, 39510215512, 3),
(4, '2016-03-02', 350.00, 39512215513, 4),
(5, '2016-01-17', 70.00, 39512215578, 5),
(6, '2015-05-05', 40.00, 39512217513, 6),
(7, '2015-05-12', 35.00, 47512215513, 7),
(8, '2016-03-05', 39.00, 48510215513, 8),
(9, '2016-01-05', 70.00, 49411115513, 9),
(10, '2016-02-05', 35.00, 49412115513, 10),
(11, '2017-06-01', 45.00, 351454466, 11);

-- --------------------------------------------------------

--
-- Table structure for table `position`
--

CREATE TABLE `position` (
  `id_position` int(11) NOT NULL,
  `name` char(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `position`
--

INSERT INTO `position` (`id_position`, `name`) VALUES
(1, 'administratorius'),
(2, 'treneris'),
(3, 'valytojas');

-- --------------------------------------------------------

--
-- Table structure for table `social_statuses`
--

CREATE TABLE `social_statuses` (
  `id_social_status` int(11) NOT NULL,
  `name` char(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `social_statuses`
--

INSERT INTO `social_statuses` (`id_social_status`, `name`) VALUES
(1, 'bedarbis'),
(2, 'dirbantis'),
(3, 'studentas'),
(4, 'moksleivis'),
(5, 'pencininkas');

-- --------------------------------------------------------

--
-- Table structure for table `SUBSCRIPTION`
--

CREATE TABLE `SUBSCRIPTION` (
  `id_subscription` int(11) NOT NULL,
  `valid_from` date NOT NULL,
  `valid_till` date NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `type` int(11) NOT NULL,
  `fk_customer_id` bigint(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `SUBSCRIPTION`
--

INSERT INTO `SUBSCRIPTION` (`id_subscription`, `valid_from`, `valid_till`, `price`, `type`, `fk_customer_id`) VALUES
(1, '2015-12-14', '2016-12-14', '30.00', 2, 38912215513),
(2, '2015-12-12', '2017-12-12', '700.00', 2, 39311215513),
(3, '2015-11-14', '2015-12-14', '35.00', 2, 39510215512),
(4, '2016-02-01', '2016-03-01', '35.00', 5, 39512215578),
(5, '2016-02-03', '2016-03-03', '39.00', 3, 39512215578),
(6, '2012-11-01', '2013-01-01', '70.00', 3, 39512217513),
(7, '2016-02-05', '2016-03-05', '45.00', 3, 47512215513),
(8, '2016-02-10', '2016-03-10', '25.00', 2, 48510215513),
(9, '2015-12-14', '2016-12-14', '350.00', 3, 49411115513),
(10, '2015-12-14', '2016-01-20', '25.00', 1, 49412115513),
(11, '2017-03-01', '2017-04-01', '45.00', 4, 351454466);

-- --------------------------------------------------------

--
-- Table structure for table `types`
--

CREATE TABLE `types` (
  `id_type` int(11) NOT NULL,
  `name` char(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `types`
--

INSERT INTO `types` (`id_type`, `name`) VALUES
(1, 'rytinis'),
(2, 'dieninis'),
(3, 'vakarinis'),
(4, 'pilnas'),
(5, 'lengvatinis');

-- --------------------------------------------------------

--
-- Table structure for table `VISIT`
--

CREATE TABLE `VISIT` (
  `id_visit` int(11) NOT NULL,
  `visit_date` date NOT NULL,
  `time` time NOT NULL,
  `fk_customer_id` bigint(11) NOT NULL,
  `fk_fitness_club_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `VISIT`
--

INSERT INTO `VISIT` (`id_visit`, `visit_date`, `time`, `fk_customer_id`, `fk_fitness_club_id`) VALUES
(1, '2016-01-12', '19:25:12', 38912215513, 4),
(2, '2016-01-12', '07:32:15', 39510215512, 3),
(3, '2016-02-12', '09:25:12', 39512215578, 3),
(4, '2016-01-12', '09:32:12', 39311215513, 4),
(5, '2016-01-12', '09:32:12', 39512215578, 3),
(6, '2016-02-12', '09:25:12', 38912215513, 3),
(7, '2016-01-08', '17:05:15', 39311215513, 1),
(8, '2016-03-12', '07:05:15', 39512217513, 3),
(9, '2016-01-17', '07:05:15', 39512217513, 2),
(10, '2016-01-17', '07:05:15', 39512217513, 3),
(11, '2016-01-18', '17:05:15', 39512217513, 2),
(12, '2016-01-17', '17:05:15', 39512215513, 4),
(13, '2016-01-17', '17:05:15', 39512215513, 1),
(14, '2016-02-02', '17:05:15', 48510215513, 6),
(15, '2016-02-09', '17:25:15', 39510215512, 4),
(16, '2016-02-07', '17:25:15', 39311215513, 2),
(17, '2016-02-07', '18:20:05', 47512215513, 2),
(18, '2016-02-07', '17:25:15', 47512215513, 1),
(19, '2016-02-07', '17:25:15', 39311215513, 5),
(20, '2016-02-07', '11:25:23', 39311215513, 4),
(21, '2016-02-07', '11:30:12', 39510215512, 6),
(22, '2016-01-07', '12:15:20', 39512215513, 6),
(23, '2016-01-07', '12:15:20', 38912215513, 7),
(24, '2016-03-01', '12:15:20', 39510215512, 4),
(25, '2016-01-07', '12:15:20', 49411115513, 3),
(26, '2016-01-07', '18:05:15', 39512217513, 2),
(27, '2016-01-07', '17:05:15', 38912215513, 5),
(28, '2016-03-07', '07:05:15', 38912215513, 2),
(29, '2016-01-07', '09:05:15', 38912215513, 2),
(30, '2016-01-07', '07:05:00', 39311215513, 4),
(31, '2016-01-02', '11:55:00', 351454466, 8);

-- --------------------------------------------------------

--
-- Table structure for table `WORKING_HOURS`
--

CREATE TABLE `WORKING_HOURS` (
  `id_working_hours` int(11) NOT NULL,
  `weekday` varchar(20) NOT NULL,
  `from` time NOT NULL,
  `till` time NOT NULL,
  `fk_fitness_club_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `WORKING_HOURS`
--

INSERT INTO `WORKING_HOURS` (`id_working_hours`, `weekday`, `from`, `till`, `fk_fitness_club_id`) VALUES
(1, 'pirmadienis', '07:00:00', '22:00:00', 1),
(2, 'pirmadienis', '07:00:00', '22:00:00', 2),
(3, 'pirmadienis', '07:00:00', '22:00:00', 3),
(4, 'pirmadienis', '07:00:00', '22:00:00', 4),
(5, 'pirmadienis', '07:00:00', '22:00:00', 5),
(6, 'pirmadienis', '07:00:00', '22:00:00', 6),
(7, 'pirmadienis', '07:00:00', '22:00:00', 7),
(8, 'antradienis', '07:00:00', '22:00:00', 1),
(9, 'antradienis', '07:00:00', '22:00:00', 2),
(10, 'antradienis', '07:00:00', '22:00:00', 3),
(11, 'antradienis', '07:00:00', '22:00:00', 4),
(12, 'antradienis', '00:00:00', '00:00:00', 5),
(13, 'antradienis', '00:00:00', '00:00:00', 6),
(14, 'antradienis', '00:00:00', '00:00:00', 7),
(15, 'treciadienis', '07:00:00', '22:00:00', 1),
(16, 'treciadienis', '07:00:00', '22:00:00', 2),
(17, 'treciadienis', '07:00:00', '22:00:00', 3),
(18, 'treciadienis', '07:00:00', '22:00:00', 4),
(19, 'treciadienis', '07:00:00', '22:00:00', 5),
(20, 'treciadienis', '07:00:00', '22:00:00', 6),
(21, 'treciadienis', '07:00:00', '22:00:00', 7),
(22, 'ketvirtadienis', '07:00:00', '22:00:00', 1),
(23, 'ketvirtadienis', '07:00:00', '22:00:00', 2),
(24, 'ketvirtadienis', '07:00:00', '22:00:00', 3),
(25, 'ketvirtadienis', '07:00:00', '22:00:00', 4),
(26, 'ketvirtadienis', '07:00:00', '22:00:00', 5),
(27, 'ketvirtadienis', '07:00:00', '22:00:00', 6),
(28, 'ketvirtadienis', '07:00:00', '22:00:00', 7),
(29, 'penktadienis', '07:00:00', '23:00:00', 1),
(30, 'penktadienis', '07:00:00', '22:00:00', 2),
(31, ' penktadienis', '07:00:00', '22:00:00', 3),
(32, 'penktadienis', '07:00:00', '22:00:00', 4),
(33, 'penktadienis', '07:00:00', '22:00:00', 5),
(34, 'penktadienis', '07:00:00', '22:00:00', 6),
(35, 'penktadienis', '07:00:00', '17:00:00', 7),
(36, 'sestadienis', '07:00:00', '17:00:00', 1),
(37, 'sestadienis', '07:00:00', '15:00:00', 2),
(38, 'sestadienis', '10:00:00', '17:00:00', 3),
(39, 'sekmadienis', '07:00:00', '15:00:00', 4),
(40, 'sekmadienis', '07:00:00', '15:00:00', 5),
(41, 'sekmadienis', '07:00:00', '15:00:00', 3),
(42, 'sekmadienis', '07:00:00', '15:00:00', 4),
(43, 'sekmadienis', '07:00:00', '15:00:00', 5),
(44, 'sekmadienis', '07:00:00', '15:00:00', 6),
(45, 'sekmadienis', '07:00:00', '15:00:00', 7);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ADDRESS`
--
ALTER TABLE `ADDRESS`
  ADD PRIMARY KEY (`id_address`),
  ADD KEY `turi1` (`fk_city_id`);

--
-- Indexes for table `CITY`
--
ALTER TABLE `CITY`
  ADD PRIMARY KEY (`id_city`);

--
-- Indexes for table `CUSTOMER`
--
ALTER TABLE `CUSTOMER`
  ADD PRIMARY KEY (`personal_id`),
  ADD KEY `soc_statusas` (`social_status`);

--
-- Indexes for table `EMPLOYEE`
--
ALTER TABLE `EMPLOYEE`
  ADD PRIMARY KEY (`personal_id`),
  ADD KEY `pareigos` (`position`),
  ADD KEY `dirba` (`fk_fitness_club_id`);

--
-- Indexes for table `ENTRANCE_TIME`
--
ALTER TABLE `ENTRANCE_TIME`
  ADD PRIMARY KEY (`id_entrance_time`),
  ADD KEY `nustatytas` (`fk_subscription_id`);

--
-- Indexes for table `FITNESS_CLUB`
--
ALTER TABLE `FITNESS_CLUB`
  ADD PRIMARY KEY (`id_fitness_club`),
  ADD UNIQUE KEY `fk_adresas_ID` (`fk_address_id`);

--
-- Indexes for table `INVOICE`
--
ALTER TABLE `INVOICE`
  ADD PRIMARY KEY (`number`),
  ADD KEY `uz` (`fk_subscription_id`),
  ADD KEY `israso` (`fk_employee_id`);

--
-- Indexes for table `PAYMENT`
--
ALTER TABLE `PAYMENT`
  ADD PRIMARY KEY (`id_payment`),
  ADD KEY `atlieka` (`fk_customer_id`),
  ADD KEY `apmoka` (`fk_invoice_id`);

--
-- Indexes for table `position`
--
ALTER TABLE `position`
  ADD PRIMARY KEY (`id_position`);

--
-- Indexes for table `social_statuses`
--
ALTER TABLE `social_statuses`
  ADD PRIMARY KEY (`id_social_status`);

--
-- Indexes for table `SUBSCRIPTION`
--
ALTER TABLE `SUBSCRIPTION`
  ADD PRIMARY KEY (`id_subscription`),
  ADD KEY `tipas` (`type`),
  ADD KEY `turi2` (`fk_customer_id`);

--
-- Indexes for table `types`
--
ALTER TABLE `types`
  ADD PRIMARY KEY (`id_type`);

--
-- Indexes for table `VISIT`
--
ALTER TABLE `VISIT`
  ADD PRIMARY KEY (`id_visit`),
  ADD KEY `lankesi` (`fk_customer_id`),
  ADD KEY `buvo` (`fk_fitness_club_id`);

--
-- Indexes for table `WORKING_HOURS`
--
ALTER TABLE `WORKING_HOURS`
  ADD PRIMARY KEY (`id_working_hours`),
  ADD KEY `veikia` (`fk_fitness_club_id`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ADDRESS`
--
ALTER TABLE `ADDRESS`
  ADD CONSTRAINT `turi1` FOREIGN KEY (`fk_city_id`) REFERENCES `CITY` (`id_city`);

--
-- Constraints for table `CUSTOMER`
--
ALTER TABLE `CUSTOMER`
  ADD CONSTRAINT `CUSTOMER_ibfk_1` FOREIGN KEY (`social_status`) REFERENCES `social_statuses` (`id_social_status`);

--
-- Constraints for table `EMPLOYEE`
--
ALTER TABLE `EMPLOYEE`
  ADD CONSTRAINT `EMPLOYEE_ibfk_1` FOREIGN KEY (`position`) REFERENCES `position` (`id_position`),
  ADD CONSTRAINT `dirba` FOREIGN KEY (`fk_fitness_club_id`) REFERENCES `FITNESS_CLUB` (`id_fitness_club`);

--
-- Constraints for table `ENTRANCE_TIME`
--
ALTER TABLE `ENTRANCE_TIME`
  ADD CONSTRAINT `nustatytas` FOREIGN KEY (`fk_subscription_id`) REFERENCES `SUBSCRIPTION` (`id_subscription`);

--
-- Constraints for table `FITNESS_CLUB`
--
ALTER TABLE `FITNESS_CLUB`
  ADD CONSTRAINT `turi3` FOREIGN KEY (`fk_address_id`) REFERENCES `ADDRESS` (`id_address`);

--
-- Constraints for table `INVOICE`
--
ALTER TABLE `INVOICE`
  ADD CONSTRAINT `israso` FOREIGN KEY (`fk_employee_id`) REFERENCES `EMPLOYEE` (`personal_id`),
  ADD CONSTRAINT `uz` FOREIGN KEY (`fk_subscription_id`) REFERENCES `SUBSCRIPTION` (`id_subscription`);

--
-- Constraints for table `PAYMENT`
--
ALTER TABLE `PAYMENT`
  ADD CONSTRAINT `apmoka` FOREIGN KEY (`fk_invoice_id`) REFERENCES `INVOICE` (`number`),
  ADD CONSTRAINT `atlieka` FOREIGN KEY (`fk_customer_id`) REFERENCES `CUSTOMER` (`personal_id`);

--
-- Constraints for table `SUBSCRIPTION`
--
ALTER TABLE `SUBSCRIPTION`
  ADD CONSTRAINT `SUBSCRIPTION_ibfk_1` FOREIGN KEY (`type`) REFERENCES `types` (`id_type`),
  ADD CONSTRAINT `turi2` FOREIGN KEY (`fk_customer_id`) REFERENCES `CUSTOMER` (`personal_id`);

--
-- Constraints for table `VISIT`
--
ALTER TABLE `VISIT`
  ADD CONSTRAINT `buvo` FOREIGN KEY (`fk_fitness_club_id`) REFERENCES `FITNESS_CLUB` (`id_fitness_club`),
  ADD CONSTRAINT `lankesi` FOREIGN KEY (`fk_customer_id`) REFERENCES `CUSTOMER` (`personal_id`);

--
-- Constraints for table `WORKING_HOURS`
--
ALTER TABLE `WORKING_HOURS`
  ADD CONSTRAINT `veikia` FOREIGN KEY (`fk_fitness_club_id`) REFERENCES `FITNESS_CLUB` (`id_fitness_club`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
