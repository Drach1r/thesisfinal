-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 20, 2024 at 04:23 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `buffalo_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `bom`
--

CREATE TABLE `bom` (
  `BOMID` int(11) NOT NULL,
  `ProductID` int(11) DEFAULT NULL,
  `RawMaterialID` int(11) DEFAULT NULL,
  `QuantityRequired` decimal(19,3) DEFAULT NULL,
  `qty_unit` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bom`
--

INSERT INTO `bom` (`BOMID`, `ProductID`, `RawMaterialID`, `QuantityRequired`, `qty_unit`) VALUES
(33, 101, 201, 3000.000, 'g'),
(34, 101, 205, 4500.000, 'ml'),
(35, 101, 210, 10500.000, 'g'),
(36, 101, 203, 7725.000, 'g'),
(37, 101, 206, 124275.000, 'ml'),
(38, 102, 201, 375.000, 'g'),
(39, 102, 202, 50.000, 'g'),
(40, 102, 203, 250.000, 'g'),
(41, 102, 204, 28.300, 'g'),
(42, 102, 205, 2000.000, 'ml'),
(43, 102, 206, 3000.000, 'ml'),
(44, 103, 205, 2000.000, 'ml'),
(45, 103, 204, 28.300, 'g'),
(46, 103, 201, 100.000, 'g');

-- --------------------------------------------------------

--
-- Table structure for table `carabaos`
--

CREATE TABLE `carabaos` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `age` int(11) DEFAULT NULL,
  `gender` varchar(10) NOT NULL,
  `member_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `carabaos`
--

INSERT INTO `carabaos` (`id`, `name`, `age`, `gender`, `member_id`) VALUES
(2, '2WVC17001', 7, 'Female', 1),
(4, '6WVC18017', 7, 'FEMALE', 2),
(5, '5WVC14026', 3, 'FEMALE', 2),
(12, '5WVC11402', 5, 'FEMALE', 10),
(13, '2WVC17009', 5, 'FEMALE', 10),
(14, '5WVC22027', 12, 'Male', 3),
(15, '5WVC22078', 0, 'Female', 3),
(16, '6WVC19021', 0, 'Female', 3),
(17, '2WVC12002', 1, 'FEMALE', 7),
(21, '4', 0, 'Female', 15),
(22, 'PM', 0, 'Female', 15),
(23, '5WVC11488', 0, 'Female', 12),
(24, '2', 0, 'Female', 29),
(25, '5WVC1017', 0, 'Female', 17),
(26, '5WVC17106', 0, 'Female', 14),
(27, '5WVC17108', 0, 'Female', 14),
(28, '5WVC19011', 0, 'Female', 23),
(29, '2WVC17001(2)', 0, 'Female', 25),
(30, '3', 0, 'Female', 26),
(31, '5WVC18085', 0, 'Female', 27),
(32, '5WVC17111', 0, 'Female', 20),
(33, '6WVC20030', 0, 'Female', 1),
(34, '6', 0, 'Female', 30),
(35, '5WVC19023', 0, 'Female', 13);

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `CustomerID` int(11) NOT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `Phone` varchar(20) DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `createdAt` date NOT NULL DEFAULT current_timestamp(),
  `tin` int(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `position` varchar(100) NOT NULL,
  `day` varchar(255) NOT NULL,
  `time_in` time NOT NULL,
  `time_out` time NOT NULL,
  `labour_hours` int(11) NOT NULL,
  `salary` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`id`, `employee_id`, `name`, `position`, `day`, `time_in`, `time_out`, `labour_hours`, `salary`) VALUES
(1, 0, 'Lilibeth Dumdumaya', 'General Manager', 'Mon, Tues, Wed, Thurs, Fri', '08:00:00', '04:00:00', 8, 500.00),
(2, 0, 'Jennifer Renie Caro', 'General Manager Assistant', 'Mon, Tues, Wed, Thurs, Fri', '08:00:00', '04:00:00', 8, 470.00),
(3, 0, 'Guarlie Caro', 'Treasurer', 'Mon, Tues, Wed, Thurs, Fri', '08:00:00', '04:00:00', 8, 450.00),
(4, 0, 'Renz Paul Caballero', 'Bookkeeper', 'Mon, Tues, Wed, Thurs, Fri', '08:00:00', '04:00:00', 8, 470.00),
(5, 0, 'Erica Plamera', 'Sales-Office Assistant', 'Mon, Tues, Wed, Thurs, Fri', '08:00:00', '04:00:00', 8, 470.00),
(6, 0, 'Dolly Cabayao', 'Head Processor (QA Milk)', 'Mon, Tues, Wed, Thurs, Fri', '08:00:00', '04:00:00', 8, 470.00),
(7, 0, 'Edward Oblino', 'Head Processor (Bakery)', 'Mon, Tues, Wed, Thurs, Fri', '08:00:00', '04:00:00', 8, 470.00),
(8, 0, 'Leo Laudato', 'Processor', 'Mon, Tues, Wed', '06:00:00', '02:00:00', 8, 450.00),
(9, 0, 'Novy Dumalogdog', 'Processor', 'Mon, Tues, Thurs', '06:00:00', '02:00:00', 8, 450.00),
(10, 0, 'Sherwin Sarmiento', 'Processor', 'Mon, Tues,  Fri', '07:00:00', '03:00:00', 8, 450.00),
(11, 0, 'Clark Vincent Garbino', 'Processor', 'Tues, Wed, Thurs', '08:00:00', '04:00:00', 8, 450.00),
(12, 0, 'N/A', 'Processor', 'Mon, Tues, Wed, Thurs, Fri', '06:00:00', '04:00:00', 24, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` int(11) NOT NULL,
  `member_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `uploadedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `files`
--

INSERT INTO `files` (`id`, `member_id`, `name`, `path`, `uploadedAt`) VALUES
(3, 6, 'produced_data_2023-08-03 (1).xls', 'assets/files/produced_data_2023-08-03 (1).xls', '2023-08-03 01:13:15'),
(4, 9, 'produced_data_2023-08-03 (1).xls', 'assets/files/produced_data_2023-08-03 (1).xls', '2023-08-03 01:26:24'),
(5, 10, 'produced_data_2023-08-03 (1).xls', 'assets/files/produced_data_2023-08-03 (1).xls', '2023-08-03 01:30:05'),
(6, 10, 'Regform 2nd2nd.pdf', NULL, '2023-10-27 02:16:35');

-- --------------------------------------------------------

--
-- Table structure for table `manufacturing_mat`
--

CREATE TABLE `manufacturing_mat` (
  `id` int(11) NOT NULL,
  `order_id` varchar(20) DEFAULT NULL,
  `RawMaterialID` int(11) DEFAULT NULL,
  `issued_quantity` decimal(19,3) DEFAULT NULL,
  `qty_unit` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `manufacturing_orders`
--

CREATE TABLE `manufacturing_orders` (
  `order_id` varchar(20) NOT NULL,
  `ProductID` int(11) DEFAULT NULL,
  `o_quantity` int(11) DEFAULT NULL,
  `batch_amount` int(20) NOT NULL,
  `o_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `status` varchar(20) DEFAULT 'In Production'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE `member` (
  `id` int(11) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `age` int(30) NOT NULL,
  `address` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `gender` varchar(255) NOT NULL,
  `religion` varchar(255) NOT NULL,
  `birthday` date NOT NULL,
  `work` varchar(255) NOT NULL,
  `contact` bigint(12) NOT NULL,
  `education` varchar(255) NOT NULL,
  `tin` varchar(255) NOT NULL,
  `dateApplied` date NOT NULL,
  `n_emergency` varchar(255) NOT NULL,
  `relation` varchar(255) NOT NULL,
  `cn_emergency` bigint(12) NOT NULL,
  `stat` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`id`, `firstname`, `lastname`, `age`, `address`, `status`, `gender`, `religion`, `birthday`, `work`, `contact`, `education`, `tin`, `dateApplied`, `n_emergency`, `relation`, `cn_emergency`, `stat`) VALUES
(1, 'RAUL', 'HINALAO', 0, 'CALINOG, ILOILO', 'MARRIED', 'MALE', 'ROMAN CATHOLIC', '2023-07-19', 'FARMER', 9956670995, 'ELEMENTARY', '1231241231', '2023-07-19', 'Magdalena Palmes', 'Wife', 9289457760, '0'),
(2, 'SALVADOR', 'LEBANAN', 51, 'MAITE GRANDE LAMBUNAO', 'MARRIED', 'MALE', 'ROMAN CATHOLIC', '1972-06-15', 'FARMER', 9102932472, 'L.I.S.T', 'N/A', '2018-02-07', 'MA. PILAR LEBANAN', 'WIFE', 91029472, '0'),
(3, 'HALEM', 'LATAÑAFRANCIA', 44, 'JAYOBO LAMBUNAO, ILOILO', 'MARRIED', 'FEMALE', 'ROMAN CATHOLIC', '1978-10-18', 'TEACHER', 9663651446, 'COLLEGE GRADUATE', '926-649-191', '2021-06-28', 'HARNL M. LASTIMOSO', 'BROTHER', 9301743177, '0'),
(6, 'SAMUEL', 'LOPEZ', 57, 'MARIBONG, LAMBUNAO', 'MARRIED', 'MALE', 'ROMAN CATHOLIC', '1965-10-11', 'BRGY. KAGAWAD', 0, 'COLLEGE GRADUATE', '', '2019-12-27', 'FRANNIE M. LOPEZ', 'WIFE', 0, '0'),
(7, 'HENRY', 'ORBINO', 62, 'IMPALIDAN, CALINOG, ILOILO', 'MARRIED', 'MALE', 'ROMAN CATHOLIC', '1961-06-04', 'FARMER', 0, 'SECONDARY', '', '2020-01-30', 'CHITA ORBINO', '', 0, '0'),
(8, 'GLENN', 'TAGUNDADO', 34, 'MANAULAN LAMBUNAO', 'SINGLE', 'MALE', 'ROMAN CATHOLIC', '1988-10-22', 'FARMER', 9104364066, 'COLLEGE GRADUATE', '', '2023-08-03', 'MOTHER', 'MOTHER', 9104364066, '0'),
(9, 'ROBERT', 'GARBINO', 53, 'BRGY. DALID, CALINOG', 'MARRIED', 'MALE', 'ROMAN CATHOLIC', '1970-05-02', 'FARMER', 9466308165, 'SECONDARY', '', '2021-09-08', 'ROWENA P. GARBINO', 'WIFE', 9466308165, '0'),
(10, 'WILMA', 'CAPILLO', 58, 'MARIBONG, LAMBUNAO', 'MARRIED', 'FEMALE', 'ROMAN CATHOLIC', '1964-10-26', 'FARMER', 9302669393, 'COLLEGE GRADUATE', '1231241231', '2021-11-17', 'DIONISIO CAPILLO', 'HUSBAND', 9302669393, '0'),
(12, 'ROMEO', 'ARAÑA', 0, 'N/A', '2', '1', 'ROMAN CATHOLIC', '2024-01-14', 'FARMER', 9273926730, 'COLLEGE GRADUATE', 'N/A', '2024-01-14', 'Mimia Laban', 'Wife', 0, 'Approved'),
(13, 'RYAN', 'CARMEN', 0, 'CALINOG, ILOILO', '2', '1', 'ROMAN CATHOLIC', '2024-01-15', 'FARMER', 9270029045, 'COLLEGE GRADUATE', 'N/A', '2024-01-15', 'Randy Carmen', 'WIFE', 0, 'Approved'),
(14, 'JOSE ROBERTO', 'CASTROMAYOR', 0, 'CALINOG, ILOILO', '2', '1', 'ROMAN CATHOLIC', '2024-01-15', 'FARMER', 9480906387, 'COLLEGE GRADUATE', 'N/A', '2024-01-15', 'Ma.Bobette Catromayor', 'WIFE', 9569349681, 'Approved'),
(15, 'PCC', 'PRODUCTION', 0, 'SIMSIMAN, CALINOG', '1', '1', 'ROMAN CATHOLIC', '2024-01-30', 'N/A', 0, 'N/A', 'N/A', '2024-01-30', 'N/A', 'N/A', 0, 'Approved'),
(17, 'JORGE', 'CAPILLO', 0, 'N/A', '2', '1', 'ROMAN CATHOLIC', '2024-02-08', 'N/A', 9318040996, 'N/A', 'N/A', '2024-02-08', 'DIONISIO CAPILLO', 'N/A', 9693759343, 'Approved'),
(18, 'CEARIAN', 'CORNELIA', 0, 'N/A', '1', '1', 'ROMAN CATHOLIC', '2024-02-08', 'N/A', 0, 'N/A', 'N/A', '2024-02-08', 'N/A', 'N/A', 0, 'Approved'),
(19, 'REX JR', 'DUMDUMAYA', 0, 'N/A', '2', '1', 'ROMAN CATHOLIC', '2024-02-08', 'N/A', 9503611110, 'N/A', 'N/A', '2024-02-08', 'LILIBETH DUMDUMAYA', 'WIFE', 0, 'Approved'),
(20, 'RUDY', 'GREGORIO', 0, 'N/A', '2', '1', 'ROMAN CATHOLIC', '2024-02-08', 'N/A', 9515966479, 'N/A', 'N/A', '2024-02-08', 'ERNA GREGORIO', 'WIFE', 0, 'Approved'),
(21, 'JUNEDEN', 'LANDOY', 0, 'N/A', '2', '1', 'ROMAN CATHOLIC', '2024-02-08', 'N/A', 9507981937, 'N/A', 'N/A', '2024-02-08', 'ROLYN LANDOY', 'WIFE', 9480987635, 'Approved'),
(22, 'JERICO', 'LARRODER', 0, 'N/A', '1', '1', 'N/A', '2024-02-08', 'N/A', 0, 'N/A', 'N/A', '2024-02-08', 'N/A', 'N/A', 0, 'Approved'),
(23, 'EDWIN', 'LARRODER', 0, 'N/A', '2', '1', 'ROMAN CATHOLIC', '2024-02-08', 'N/A', 9452510759, 'N/A', 'N/A', '2024-02-08', 'JOLINDA LARRODER', 'WIFE', 9384114633, 'Approved'),
(24, 'JOHN PAUL', 'LARRODER', 0, 'N/A', '1', '1', 'ROMAN CATHOLIC', '2024-02-08', 'N/A', 0, 'N/A', 'N/A', '2024-02-08', 'N/A', 'N/A', 0, 'Approved'),
(25, 'RAUL', 'LARRODER', 0, 'N/A', '2', '1', 'ROMAN CATHOLIC', '2024-02-08', 'N/A', 9077613720, 'N/A', 'N/A', '2024-02-08', 'EDWIN LARRODER', 'BROTHER', 0, 'Approved'),
(26, 'GIL', 'LAZO', 0, 'N/A', '2', '1', 'ROMAN CATHOLIC', '2024-02-08', 'N/A', 9090306512, 'N/A', 'N/A', '2024-02-08', 'N/A', 'N/A', 0, 'Approved'),
(27, 'ANJO', 'PALMES', 0, 'N/A', '2', '1', 'ROMAN CATHOLIC', '2024-02-08', 'N/A', 9466979300, 'N/A', 'N/A', '2024-02-08', 'N/A', 'N/A', 0, 'Approved'),
(29, 'DIONISIO', 'CAPILLO', 0, 'N/A', '2', '1', 'R', '2024-02-19', 'N/A', 0, 'N/A', 'N/A', '2024-02-19', 'WILMA CAPILLO', 'WIFE', 0, 'Approved'),
(30, 'ZALDY', 'LABANON', 0, 'N/A', '2', '1', 'RELIGION', '2024-02-20', 'N/A', 0, 'N/A', 'N/A', '2024-02-20', 'N/A', 'N/A', 0, 'Approved');

-- --------------------------------------------------------

--
-- Table structure for table `produced`
--

CREATE TABLE `produced` (
  `transaction_id` int(11) NOT NULL,
  `member_id` int(11) DEFAULT NULL,
  `carabao_id` int(11) DEFAULT NULL,
  `milkslip` decimal(10,2) DEFAULT NULL,
  `actual` decimal(10,2) DEFAULT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produced`
--

INSERT INTO `produced` (`transaction_id`, `member_id`, `carabao_id`, `milkslip`, `actual`, `date`) VALUES
(1, 12, 23, 3.50, 3.50, '2023-01-01'),
(2, 29, 24, 3.20, 3.20, '2023-01-01'),
(3, 17, 25, 3.00, NULL, '2023-01-01'),
(4, 10, 13, 4.30, NULL, '2023-01-01'),
(5, 14, 26, 3.35, NULL, '2023-01-01'),
(6, 14, 27, 5.30, NULL, '2023-01-01'),
(7, 23, 28, 3.10, NULL, '2023-01-01'),
(8, 25, 29, 3.20, NULL, '2023-01-01'),
(9, 3, 14, 3.00, NULL, '2023-01-01'),
(10, 26, 30, 1.50, NULL, '2023-01-01'),
(11, 2, 4, 8.00, NULL, '2023-01-01'),
(12, 2, 5, 9.40, NULL, '2023-01-01'),
(13, 7, 17, 2.75, NULL, '2023-01-01'),
(14, 27, 31, 2.00, NULL, '2023-01-01'),
(15, 15, 21, 7.70, NULL, '2023-01-01'),
(16, 12, 23, 3.60, NULL, '2023-01-02'),
(17, 20, 32, 4.50, NULL, '2023-01-02'),
(18, 1, 2, 7.10, NULL, '2023-01-02'),
(19, 1, 33, 6.30, NULL, '2023-01-02'),
(20, 30, 34, 20.00, NULL, '2023-01-02'),
(21, 23, 28, 2.80, NULL, '2023-01-02'),
(22, 25, 29, 2.55, NULL, '2023-01-02'),
(23, 26, 30, 1.90, NULL, '2023-01-02'),
(24, 2, 4, 3.70, NULL, '2023-01-02'),
(25, 2, 5, 5.00, NULL, '2023-01-02'),
(26, 27, 31, 1.70, NULL, '2023-01-02'),
(27, 15, 21, 9.40, NULL, '2023-01-02'),
(28, 12, 23, 3.60, NULL, '2023-01-03'),
(29, 29, 24, 9.05, NULL, '2023-01-03'),
(30, 10, 13, 6.40, NULL, '2023-01-03'),
(31, 14, 26, 5.90, NULL, '2023-01-03'),
(32, 14, 27, 4.00, NULL, '2023-01-03'),
(33, 23, 28, 3.20, NULL, '2023-01-03'),
(34, 3, 14, 2.40, NULL, '2023-01-03'),
(35, 26, 30, 2.00, NULL, '2023-01-03'),
(36, 15, 21, 8.20, NULL, '2023-01-03'),
(37, 12, 23, 4.00, NULL, '2023-01-04'),
(38, 29, 24, 4.00, NULL, '2023-01-04'),
(39, 17, 25, 7.00, NULL, '2023-01-04'),
(40, 10, 13, 3.50, NULL, '2023-01-04'),
(41, 13, 35, 12.90, NULL, '2023-01-04'),
(42, 20, 32, 2.20, NULL, '2023-01-04'),
(43, 1, 2, 10.50, NULL, '2023-01-04'),
(44, 1, 33, 6.10, NULL, '2023-01-04'),
(45, 23, 28, 3.00, NULL, '2023-01-04'),
(46, 25, 29, 6.70, NULL, '2023-01-04'),
(47, 3, 14, 1.50, NULL, '2023-01-04'),
(48, 26, 30, 2.20, NULL, '2023-01-04'),
(49, 2, 4, 4.00, NULL, '2023-01-04'),
(50, 2, 5, 3.50, NULL, '2023-01-04'),
(51, 7, 17, 4.30, NULL, '2023-01-04'),
(52, 15, 21, 8.60, NULL, '2023-01-04'),
(53, 12, 23, 3.60, NULL, '2023-01-05'),
(54, 29, 24, 3.30, NULL, '2023-01-05'),
(55, 17, 25, 4.00, NULL, '2023-01-05'),
(56, 10, 13, 3.40, NULL, '2023-01-05'),
(57, 14, 26, 5.70, NULL, '2023-01-05'),
(58, 14, 27, 3.40, NULL, '2023-01-05'),
(59, 20, 32, 4.30, NULL, '2023-01-05'),
(60, 23, 28, 3.00, NULL, '2023-01-05'),
(61, 25, 29, 3.70, NULL, '2023-01-05'),
(62, 26, 30, 2.30, NULL, '2023-01-05'),
(63, 2, 4, 9.40, NULL, '2023-01-05'),
(64, 2, 5, 8.80, NULL, '2023-01-05'),
(65, 27, 31, 4.60, NULL, '2023-01-05'),
(66, 15, 21, 8.70, NULL, '2023-01-05'),
(67, 12, 23, 3.60, NULL, '2023-01-06'),
(68, 29, 24, 2.30, NULL, '2023-01-06'),
(69, 17, 25, 3.50, NULL, '2023-01-06'),
(70, 10, 13, 3.20, NULL, '2023-01-06'),
(71, 1, 2, 14.10, NULL, '2023-01-06'),
(72, 23, 28, 3.00, NULL, '2023-01-06'),
(73, 25, 29, 2.20, NULL, '2023-01-06'),
(74, 26, 30, 2.20, NULL, '2023-01-06'),
(75, 2, 4, 3.80, NULL, '2023-01-06'),
(76, 2, 5, 4.80, NULL, '2023-01-06'),
(77, 7, 17, 2.30, NULL, '2023-01-06'),
(78, 15, 21, 8.60, NULL, '2023-01-06'),
(79, 12, 23, 3.85, NULL, '2023-01-07'),
(80, 29, 24, 3.40, NULL, '2023-01-07'),
(81, 17, 25, 3.80, NULL, '2023-01-07'),
(82, 10, 13, 3.00, NULL, '2023-01-07'),
(83, 14, 26, 5.60, NULL, '2023-01-07'),
(84, 14, 27, 3.50, NULL, '2023-01-07'),
(85, 20, 32, 4.20, NULL, '2023-01-07'),
(86, 30, 34, 20.00, NULL, '2023-01-07'),
(87, 23, 28, 2.65, NULL, '2023-01-07'),
(88, 25, 29, 2.90, NULL, '2023-01-07'),
(89, 26, 30, 2.10, NULL, '2023-01-07'),
(90, 2, 4, 4.00, NULL, '2023-01-07'),
(91, 2, 5, 4.20, NULL, '2023-01-07'),
(92, 7, 17, 6.45, NULL, '2023-01-07'),
(93, 15, 21, 8.60, NULL, '2023-01-07'),
(94, 12, 23, 4.00, NULL, '2023-01-08'),
(95, 17, 25, 3.70, NULL, '2023-01-08'),
(96, 13, 35, 12.80, NULL, '2023-01-08'),
(97, 14, 26, 3.20, NULL, '2023-01-08'),
(98, 14, 27, 1.50, NULL, '2023-01-08'),
(99, 20, 32, 2.10, NULL, '2023-01-08'),
(100, 1, 2, 8.70, NULL, '2023-01-08'),
(101, 1, 33, 6.10, NULL, '2023-01-08'),
(102, 23, 28, 2.90, NULL, '2023-01-08'),
(103, 25, 29, 3.30, NULL, '2023-01-08'),
(104, 2, 4, 4.00, NULL, '2023-01-08'),
(105, 2, 5, 4.00, NULL, '2023-01-08'),
(106, 7, 17, 3.00, NULL, '2023-01-08'),
(107, 15, 21, 6.80, NULL, '2023-01-08'),
(108, 12, 23, 3.60, NULL, '2023-01-09'),
(109, 29, 24, 7.65, NULL, '2023-01-09'),
(110, 17, 25, 3.80, NULL, '2023-01-09'),
(111, 10, 13, 6.05, NULL, '2023-01-09'),
(112, 14, 26, 2.70, NULL, '2023-01-09'),
(113, 14, 27, 2.10, NULL, '2023-01-09'),
(114, 23, 28, 2.80, NULL, '2023-01-09'),
(115, 25, 29, 2.30, NULL, '2023-01-09'),
(116, 3, 14, 4.70, NULL, '2023-01-09'),
(117, 26, 30, 3.80, NULL, '2023-01-09'),
(118, 2, 4, 4.50, NULL, '2023-01-09'),
(119, 2, 5, 4.50, NULL, '2023-01-09'),
(120, 7, 17, 3.40, NULL, '2023-01-09'),
(121, 27, 31, 4.70, NULL, '2023-01-09'),
(122, 15, 21, 8.40, NULL, '2023-01-09'),
(123, 12, 23, 3.60, NULL, '2023-01-10'),
(124, 29, 24, 4.20, NULL, '2023-01-10'),
(125, 17, 25, 3.20, NULL, '2023-01-10'),
(126, 10, 13, 3.60, NULL, '2023-01-10'),
(127, 20, 32, 4.70, NULL, '2023-01-10'),
(128, 1, 2, 7.10, NULL, '2023-01-10'),
(129, 1, 33, 7.10, NULL, '2023-01-10'),
(130, 23, 28, 2.80, NULL, '2023-01-10'),
(131, 25, 29, 3.00, NULL, '2023-01-10'),
(132, 26, 30, 2.40, NULL, '2023-01-10'),
(133, 7, 17, 7.00, NULL, '2023-01-10'),
(134, 15, 21, 8.00, NULL, '2023-01-10'),
(135, 12, 23, 3.80, NULL, '2023-01-11'),
(136, 17, 25, 3.15, NULL, '2023-01-11'),
(137, 13, 35, 9.60, NULL, '2023-01-11'),
(138, 14, 26, 4.20, NULL, '2023-01-11'),
(139, 14, 27, 6.20, NULL, '2023-01-11'),
(140, 30, 34, 17.00, NULL, '2023-01-11'),
(141, 23, 28, 2.50, NULL, '2023-01-11'),
(142, 25, 29, 2.70, NULL, '2023-01-11'),
(143, 3, 14, 3.00, NULL, '2023-01-11'),
(144, 26, 30, 2.20, NULL, '2023-01-11'),
(145, 2, 4, 4.30, NULL, '2023-01-11'),
(146, 2, 5, 4.20, NULL, '2023-01-11'),
(147, 7, 17, 3.00, NULL, '2023-01-11'),
(148, 27, 31, 3.15, NULL, '2023-01-11'),
(149, 15, 21, 8.00, NULL, '2023-01-11'),
(150, 12, 23, 3.45, NULL, '2023-01-12'),
(151, 29, 24, 9.00, NULL, '2023-01-12'),
(152, 17, 25, 3.60, NULL, '2023-01-12'),
(153, 10, 13, 8.50, NULL, '2023-01-12'),
(154, 1, 2, 9.00, NULL, '2023-01-12'),
(155, 1, 33, 6.40, NULL, '2023-01-12'),
(156, 23, 28, 2.45, NULL, '2023-01-12'),
(157, 25, 29, 2.75, NULL, '2023-01-12'),
(158, 2, 4, 4.25, NULL, '2023-01-12'),
(159, 2, 5, 4.20, NULL, '2023-01-12'),
(160, 7, 17, 3.75, NULL, '2023-01-12'),
(161, 15, 21, 7.90, NULL, '2023-01-12'),
(162, 12, 23, 3.55, NULL, '2023-01-13'),
(163, 29, 24, 4.35, NULL, '2023-01-13'),
(164, 17, 25, 3.20, NULL, '2023-01-13'),
(165, 10, 13, 4.30, NULL, '2023-01-13'),
(166, 13, 35, 6.35, NULL, '2023-01-13'),
(167, 14, 26, 5.75, NULL, '2023-01-13'),
(168, 14, 27, 4.00, NULL, '2023-01-13'),
(169, 20, 32, 7.30, NULL, '2023-01-13'),
(170, 23, 28, 2.30, NULL, '2023-01-13'),
(171, 3, 14, 1.50, NULL, '2023-01-13'),
(172, 26, 30, 4.25, NULL, '2023-01-13'),
(173, 2, 4, 8.30, NULL, '2023-01-13'),
(174, 2, 5, 8.80, NULL, '2023-01-13'),
(175, 7, 17, 3.35, NULL, '2023-01-13'),
(176, 27, 31, 3.70, NULL, '2023-01-13'),
(177, 15, 21, 8.70, NULL, '2023-01-13'),
(178, 12, 23, 3.70, NULL, '2023-01-14'),
(179, 29, 24, 4.40, NULL, '2023-01-14'),
(180, 17, 25, 4.10, NULL, '2023-01-14'),
(181, 10, 13, 4.00, NULL, '2023-01-14'),
(182, 1, 2, 7.00, NULL, '2023-01-14'),
(183, 1, 33, 7.00, NULL, '2023-01-14'),
(184, 23, 28, 2.30, NULL, '2023-01-14'),
(185, 25, 29, 3.50, NULL, '2023-01-14'),
(186, 26, 30, 2.20, NULL, '2023-01-14'),
(187, 2, 4, 4.00, NULL, '2023-01-14'),
(188, 2, 5, 4.50, NULL, '2023-01-14'),
(189, 7, 17, 6.20, NULL, '2023-01-14'),
(190, 27, 31, 2.00, NULL, '2023-01-14'),
(191, 15, 21, 6.50, NULL, '2023-01-14'),
(192, 12, 23, 3.70, NULL, '2023-01-15'),
(193, 29, 24, 4.60, NULL, '2023-01-15'),
(194, 17, 25, 2.10, NULL, '2023-01-15'),
(195, 10, 13, 3.70, NULL, '2023-01-15'),
(196, 14, 26, 5.10, NULL, '2023-01-15'),
(197, 14, 27, 4.10, NULL, '2023-01-15'),
(198, 20, 32, 5.00, NULL, '2023-01-15'),
(199, 1, 2, 4.10, NULL, '2023-01-15'),
(200, 1, 33, 3.20, NULL, '2023-01-15'),
(201, 30, 34, 20.00, NULL, '2023-01-15'),
(202, 23, 28, 2.30, NULL, '2023-01-15'),
(203, 2, 4, 4.30, NULL, '2023-01-15'),
(204, 2, 5, 4.80, NULL, '2023-01-15'),
(205, 7, 17, 3.00, NULL, '2023-01-15'),
(206, 15, 21, 9.90, NULL, '2023-01-15'),
(209, 12, 23, 3.50, NULL, '2023-01-16'),
(210, 17, 25, 3.50, NULL, '2023-01-16');

-- --------------------------------------------------------

--
-- Table structure for table `productlist`
--

CREATE TABLE `productlist` (
  `ProductID` int(11) NOT NULL,
  `Name` text NOT NULL,
  `Description` text DEFAULT NULL,
  `measure` int(25) NOT NULL,
  `Unit` text NOT NULL,
  `price` int(25) NOT NULL,
  `prod_time` int(50) NOT NULL,
  `batch_amount` int(25) NOT NULL,
  `pack` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `productlist`
--

INSERT INTO `productlist` (`ProductID`, `Name`, `Description`, `measure`, `Unit`, `price`, `prod_time`, `batch_amount`, `pack`) VALUES
(101, 'Fresh Milk', 'Fresh Milk', 200, 'ml', 0, 7200, 750, 'Pouch'),
(102, 'Choco Milk', 'Flavored Milk', 200, 'ml', 22, 3600, 26, 'Bottle'),
(103, 'Pastillias', 'Pastillias', 500, 'g', 700, 9000, 1, 'Container');

-- --------------------------------------------------------

--
-- Table structure for table `product_stock`
--

CREATE TABLE `product_stock` (
  `TransactionID` int(11) NOT NULL,
  `ProductID` int(11) DEFAULT NULL,
  `stock_in` decimal(19,3) DEFAULT NULL,
  `stock_out` decimal(19,3) DEFAULT NULL,
  `TransactionDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` int(11) NOT NULL,
  `RawMaterialID` int(11) DEFAULT NULL,
  `buyer` varchar(255) DEFAULT NULL,
  `PurchaseDate` date DEFAULT NULL,
  `arrived_date` date NOT NULL DEFAULT current_timestamp(),
  `qty_purchased` int(11) DEFAULT NULL,
  `unit` varchar(25) NOT NULL,
  `p_amount` decimal(19,2) NOT NULL,
  `status` tinytext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rawmaterials`
--

CREATE TABLE `rawmaterials` (
  `RawMaterialID` int(11) NOT NULL,
  `Name` text NOT NULL,
  `Unit` text NOT NULL,
  `price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rawmaterials`
--

INSERT INTO `rawmaterials` (`RawMaterialID`, `Name`, `Unit`, `price`) VALUES
(201, 'Sugar', 'Kg', 80),
(202, 'Flavoring', 'Kg', 200),
(203, 'Skim Milk', 'Kg', 180),
(204, 'Corn Starch', 'Kg', 90),
(205, 'Raw Milk', 'Kg', 80),
(206, 'Mineral Water', 'L', 80),
(207, 'Rennet', 'Kg', 750),
(208, 'Salt', 'Kg', 20),
(209, 'Red Cane', 'Kg', 340),
(210, 'Powder Milk', 'Kg', 350);

-- --------------------------------------------------------

--
-- Table structure for table `rawmilk`
--

CREATE TABLE `rawmilk` (
  `rawmilk_id` int(11) NOT NULL,
  `daily_total` float NOT NULL,
  `transaction_day` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rawmilk`
--

INSERT INTO `rawmilk` (`rawmilk_id`, `daily_total`, `transaction_day`) VALUES
(4, 0, '2024-01-22'),
(5, 0, '2024-01-23'),
(6, 0, '2024-01-24'),
(7, 0, '2024-01-25'),
(8, 16, '2024-01-29'),
(9, 0, '2024-01-30'),
(10, 0, '2024-01-31'),
(11, 0, '2024-02-01'),
(12, 0, '2024-02-03'),
(13, 0, '2024-02-05'),
(14, 0, '2024-02-06'),
(15, -13, '2024-02-07');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `SaleID` varchar(20) NOT NULL,
  `CustomerID` int(11) DEFAULT NULL,
  `SaleDate` date DEFAULT NULL,
  `TotalAmount` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `salesitems`
--

CREATE TABLE `salesitems` (
  `SalesItemID` int(11) NOT NULL,
  `SaleID` varchar(20) DEFAULT NULL,
  `ProductID` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

CREATE TABLE `stock` (
  `StockID` int(11) NOT NULL,
  `RawMaterialID` int(11) DEFAULT NULL,
  `stock_in` decimal(19,3) NOT NULL,
  `stock_out` decimal(19,3) NOT NULL,
  `unit` varchar(20) DEFAULT NULL,
  `transaction_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stock`
--

INSERT INTO `stock` (`StockID`, `RawMaterialID`, `stock_in`, `stock_out`, `unit`, `transaction_date`) VALUES
(306, 205, 3500.000, 0.000, 'ml', '2023-01-01'),
(307, 205, 3200.000, 0.000, 'ml', '2023-01-01');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(30) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `userType` tinyint(1) NOT NULL COMMENT '1= admin \r\n2= production manager \r\n3 = sales manager\r\n4 = bookkeeper'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `fname`, `email`, `password`, `userType`) VALUES
(1, 'Admin', 'admin@gmail.com', 'admin', 1),
(5, 'superadmin', 'super@gmail.com', 'super', 5),
(6, 'Lilibeth Dumdumaya', 'lilibethdumdumaya@gmail.com', 'admin', 1),
(7, 'Renz Paul Caballero', 'renzcaballero@gmail.com', 'bookkeeper', 4),
(8, 'Erica Plamera', 'ericaplam@gmail.com', 'sales', 3),
(9, 'Dolly Cabayao', 'dollycabayao@gmail.com', 'production', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bom`
--
ALTER TABLE `bom`
  ADD PRIMARY KEY (`BOMID`),
  ADD KEY `ProductID` (`ProductID`),
  ADD KEY `RawMaterialID` (`RawMaterialID`);

--
-- Indexes for table `carabaos`
--
ALTER TABLE `carabaos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carabaos_ibfk_1` (`member_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`CustomerID`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `files_ibfk_1` (`member_id`);

--
-- Indexes for table `manufacturing_mat`
--
ALTER TABLE `manufacturing_mat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `RawMaterialID` (`RawMaterialID`);

--
-- Indexes for table `manufacturing_orders`
--
ALTER TABLE `manufacturing_orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `ProductID` (`ProductID`);

--
-- Indexes for table `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `produced`
--
ALTER TABLE `produced`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `member_id` (`member_id`),
  ADD KEY `carabao_id` (`carabao_id`);

--
-- Indexes for table `productlist`
--
ALTER TABLE `productlist`
  ADD PRIMARY KEY (`ProductID`);

--
-- Indexes for table `product_stock`
--
ALTER TABLE `product_stock`
  ADD PRIMARY KEY (`TransactionID`),
  ADD KEY `ProductID` (`ProductID`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `RawMaterialID` (`RawMaterialID`);

--
-- Indexes for table `rawmaterials`
--
ALTER TABLE `rawmaterials`
  ADD PRIMARY KEY (`RawMaterialID`);

--
-- Indexes for table `rawmilk`
--
ALTER TABLE `rawmilk`
  ADD PRIMARY KEY (`rawmilk_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`SaleID`),
  ADD KEY `CustomerID` (`CustomerID`);

--
-- Indexes for table `salesitems`
--
ALTER TABLE `salesitems`
  ADD PRIMARY KEY (`SalesItemID`),
  ADD KEY `SaleID` (`SaleID`),
  ADD KEY `ProductID` (`ProductID`);

--
-- Indexes for table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`StockID`),
  ADD KEY `idx_RawMaterialID` (`RawMaterialID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bom`
--
ALTER TABLE `bom`
  MODIFY `BOMID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `carabaos`
--
ALTER TABLE `carabaos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `CustomerID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `manufacturing_mat`
--
ALTER TABLE `manufacturing_mat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=320;

--
-- AUTO_INCREMENT for table `member`
--
ALTER TABLE `member`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `produced`
--
ALTER TABLE `produced`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=211;

--
-- AUTO_INCREMENT for table `product_stock`
--
ALTER TABLE `product_stock`
  MODIFY `TransactionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rawmilk`
--
ALTER TABLE `rawmilk`
  MODIFY `rawmilk_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `salesitems`
--
ALTER TABLE `salesitems`
  MODIFY `SalesItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `stock`
--
ALTER TABLE `stock`
  MODIFY `StockID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=308;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bom`
--
ALTER TABLE `bom`
  ADD CONSTRAINT `bom_ibfk_1` FOREIGN KEY (`ProductID`) REFERENCES `productlist` (`ProductID`),
  ADD CONSTRAINT `bom_ibfk_2` FOREIGN KEY (`RawMaterialID`) REFERENCES `rawmaterials` (`RawMaterialID`);

--
-- Constraints for table `carabaos`
--
ALTER TABLE `carabaos`
  ADD CONSTRAINT `carabaos_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `member` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `files`
--
ALTER TABLE `files`
  ADD CONSTRAINT `files_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `member` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `manufacturing_mat`
--
ALTER TABLE `manufacturing_mat`
  ADD CONSTRAINT `manufacturing_mat_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `manufacturing_orders` (`order_id`),
  ADD CONSTRAINT `manufacturing_mat_ibfk_2` FOREIGN KEY (`RawMaterialID`) REFERENCES `rawmaterials` (`RawMaterialID`);

--
-- Constraints for table `manufacturing_orders`
--
ALTER TABLE `manufacturing_orders`
  ADD CONSTRAINT `manufacturing_orders_ibfk_1` FOREIGN KEY (`ProductID`) REFERENCES `productlist` (`ProductID`);

--
-- Constraints for table `produced`
--
ALTER TABLE `produced`
  ADD CONSTRAINT `produced_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `member` (`id`),
  ADD CONSTRAINT `produced_ibfk_2` FOREIGN KEY (`carabao_id`) REFERENCES `carabaos` (`id`);

--
-- Constraints for table `product_stock`
--
ALTER TABLE `product_stock`
  ADD CONSTRAINT `product_stock_ibfk_1` FOREIGN KEY (`ProductID`) REFERENCES `productlist` (`ProductID`);

--
-- Constraints for table `purchases`
--
ALTER TABLE `purchases`
  ADD CONSTRAINT `purchases_ibfk_1` FOREIGN KEY (`RawMaterialID`) REFERENCES `rawmaterials` (`RawMaterialID`);

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `customers` (`CustomerID`);

--
-- Constraints for table `salesitems`
--
ALTER TABLE `salesitems`
  ADD CONSTRAINT `salesitems_ibfk_1` FOREIGN KEY (`SaleID`) REFERENCES `sales` (`SaleID`),
  ADD CONSTRAINT `salesitems_ibfk_2` FOREIGN KEY (`ProductID`) REFERENCES `productlist` (`ProductID`);

--
-- Constraints for table `stock`
--
ALTER TABLE `stock`
  ADD CONSTRAINT `stock_ibfk_1` FOREIGN KEY (`RawMaterialID`) REFERENCES `rawmaterials` (`RawMaterialID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
