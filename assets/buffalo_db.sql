-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 15, 2024 at 06:59 AM
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
(21, 'AM', 0, 'Female', 15),
(22, 'PM', 0, 'Female', 15);

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
  `createdAt` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`CustomerID`, `Name`, `Email`, `Phone`, `Address`, `createdAt`) VALUES
(1, 'Customer 1', 'customer1@gmail.com', '09956670995', 'customer1', '2024-02-01'),
(2, 'Customer 2', 'test@test.com', '123213123123121', 'test', '2024-02-08');

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
  `issued_quantity` int(11) DEFAULT NULL,
  `qty_unit` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `manufacturing_mat`
--

INSERT INTO `manufacturing_mat` (`id`, `order_id`, `RawMaterialID`, `issued_quantity`, `qty_unit`) VALUES
(142, 'MO-001', 201, 375, 'g'),
(143, 'MO-001', 202, 50, 'g'),
(144, 'MO-001', 203, 250, 'g'),
(145, 'MO-001', 204, 28, 'g'),
(146, 'MO-001', 205, 2000, 'ml'),
(147, 'MO-001', 206, 3000, 'ml'),
(148, 'MO-002', 205, 2000, 'ml'),
(149, 'MO-002', 204, 28, 'g'),
(150, 'MO-002', 201, 100, 'g'),
(151, 'MO-003', 201, 375, 'g'),
(152, 'MO-003', 202, 50, 'g'),
(153, 'MO-003', 203, 250, 'g'),
(154, 'MO-003', 204, 28, 'g'),
(155, 'MO-003', 205, 2000, 'ml'),
(156, 'MO-003', 206, 3000, 'ml');

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
  `status` varchar(20) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `manufacturing_orders`
--

INSERT INTO `manufacturing_orders` (`order_id`, `ProductID`, `o_quantity`, `batch_amount`, `o_date`, `due_date`, `status`) VALUES
('MO-001', 102, 1, 26, '2024-02-08', '2024-02-08', 'In Stock'),
('MO-002', 103, 1, 1, '2024-02-08', '2024-02-08', 'In Stock'),
('MO-003', 102, 1, 26, '2024-02-14', '2024-02-14', 'In Stock');

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
(15, 'PCC', 'PCC', 0, 'SIMSIMAN, CALINOG', '1', '1', 'ROMAN CATHOLIC', '2024-01-30', 'N/A', 0, 'N/A', 'N/A', '2024-01-30', 'N/A', 'N/A', 0, 'Approved'),
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
(28, 'test', 'test', 0, 'test', '1', '1', 'test', '2024-02-08', 't', 0, 'testt', 't', '2024-02-08', 't', 't', 0, 'Approved');

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
  `date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produced`
--

INSERT INTO `produced` (`transaction_id`, `member_id`, `carabao_id`, `milkslip`, `actual`, `date`) VALUES
(1, 10, 13, 3.60, 3.60, '2024-02-07'),
(2, 10, 13, 3.70, 3.70, '2024-02-07'),
(3, 10, 13, 3.40, 3.40, '2024-02-07'),
(4, 10, 13, 3.70, 3.70, '2024-02-07'),
(5, 10, 13, 3.40, 3.40, '2024-02-07'),
(6, 10, 13, 3.10, 3.10, '2024-02-08'),
(7, 1, 2, 5.40, 5.40, '2024-02-08'),
(8, 1, 2, 5.60, 5.60, '2024-02-08'),
(9, 1, 2, 6.15, 6.15, '2024-02-08'),
(10, 3, 14, 1.40, 1.40, '2024-02-08'),
(11, 3, 14, 4.50, 4.50, '2024-02-08'),
(12, 2, 4, 6.80, 6.80, '2024-02-08'),
(13, 2, 4, 6.50, 6.50, '2024-02-08'),
(14, 2, 4, 3.00, 3.00, '2024-02-08'),
(15, 2, 4, 3.00, 3.00, '2024-02-08'),
(16, 2, 5, 5.00, 5.00, '2024-02-08'),
(17, 2, 5, 5.00, 5.00, '2024-02-08'),
(18, 2, 5, 2.50, 2.50, '2024-02-08'),
(19, 2, 5, 2.70, 2.70, '2024-02-08'),
(20, 7, 17, 4.80, 4.80, '2024-02-08'),
(21, 7, 17, 2.50, 2.50, '2024-02-08'),
(22, 7, 17, 2.20, 2.20, '2024-02-08'),
(23, 7, 17, 2.10, 2.10, '2024-02-08'),
(24, 7, 17, 2.30, 2.30, '2024-02-08'),
(25, 15, 21, 6.50, 6.50, '2024-02-08'),
(26, 15, 21, 6.30, 6.30, '2024-02-08'),
(27, 15, 21, 4.40, 4.40, '2024-02-08'),
(28, 15, 21, 4.70, 4.70, '2024-02-08'),
(29, 15, 21, 4.70, 4.70, '2024-02-08'),
(30, 15, 21, 1.90, 1.90, '2024-02-08'),
(31, 15, 21, 3.70, 3.70, '2024-02-08'),
(32, 15, 22, 1.10, 1.00, '2024-02-08'),
(33, 15, 22, 1.20, 1.20, '2024-02-08'),
(34, 15, 22, 1.10, 1.10, '2024-02-08'),
(35, 15, 22, 3.90, 3.90, '2024-02-08'),
(36, 15, 22, 3.10, 3.10, '2024-02-08'),
(37, 15, 22, 6.30, 6.30, '2024-02-08'),
(38, 3, 14, 10.00, NULL, '2024-02-13');

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

--
-- Dumping data for table `product_stock`
--

INSERT INTO `product_stock` (`TransactionID`, `ProductID`, `stock_in`, `stock_out`, `TransactionDate`) VALUES
(18, 102, 26.000, 0.000, '2024-02-08'),
(19, 103, 1.000, 0.000, '2024-02-08'),
(32, 103, 0.000, 1.000, '2024-02-14'),
(33, 102, 0.000, 26.000, '2024-02-14'),
(34, 102, 26.000, 0.000, '2024-02-14'),
(35, 102, 0.000, 5.000, '2024-02-14'),
(36, 102, 0.000, 7.000, '2024-02-14');

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

--
-- Dumping data for table `purchases`
--

INSERT INTO `purchases` (`id`, `RawMaterialID`, `buyer`, `PurchaseDate`, `arrived_date`, `qty_purchased`, `unit`, `p_amount`, `status`) VALUES
(17, 203, '12', '2024-02-08', '2024-02-12', 25, 'Kg', 1890.00, '1'),
(18, 204, '12', '2024-02-08', '2024-02-12', 2, 'Kg', 104.00, '1'),
(19, 206, '12', '2024-02-08', '2024-02-12', 200, 'L', 250.00, '1'),
(20, 206, '12', '2024-02-08', '2024-02-12', 200, 'L', 250.00, '1'),
(21, 206, '12', '2024-02-08', '2024-02-12', 200, 'L', 250.00, '1'),
(22, 206, '12', '2024-02-08', '2024-02-12', 200, 'L', 250.00, '1'),
(23, 207, '12', '2024-02-08', '2024-02-12', 1, 'L', 480.00, '1'),
(24, 209, '12', '2024-02-08', '2024-02-12', 1, 'L', 84.00, '1'),
(25, 210, '12', '2024-02-08', '2024-02-12', 25, 'Kg', 11142.00, '1'),
(26, 208, '12', '2024-02-08', '2024-02-12', 50, 'Kg', 560.00, '1'),
(27, 202, '12', '2024-02-08', '2024-02-12', 6, 'Kg', 288.00, '1'),
(29, 201, '12', '2024-02-11', '2024-02-12', 50, 'Kg', 3568.00, '1'),
(33, 201, '12', '2024-02-13', '2024-02-13', 1, 'Kg', 1.00, '1'),
(34, 201, '12', '2024-02-14', '2024-02-14', 1, 'Kg', 1.00, '0');

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
(205, 'Raw Milk', 'L', 80),
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
(15, 2, '2024-02-07');

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

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`SaleID`, `CustomerID`, `SaleDate`, `TotalAmount`) VALUES
('SO-01', 2, '2024-02-14', 700.00),
('SO-02', 1, '2024-02-14', 572.00),
('SO-03', 1, '2024-02-14', 264.00);

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

--
-- Dumping data for table `salesitems`
--

INSERT INTO `salesitems` (`SalesItemID`, `SaleID`, `ProductID`, `quantity`, `price`) VALUES
(47, 'SO-01', 103, 1, 700.00),
(48, 'SO-02', 102, 26, 22.00),
(49, 'SO-03', 102, 5, 22.00),
(50, 'SO-03', 102, 7, 22.00);

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
(65, 205, 3600.000, 0.000, 'ml', '2024-02-07'),
(66, 205, 37000.000, 0.000, 'ml', '2024-02-07'),
(67, 205, 6300.000, 0.000, 'ml', '2024-02-08'),
(68, 205, 3100.000, 0.000, 'ml', '2024-02-08'),
(69, 205, 3900.000, 0.000, 'ml', '2024-02-08'),
(70, 205, 1100.000, 0.000, 'ml', '2024-02-08'),
(71, 205, 1200.000, 0.000, 'ml', '2024-02-08'),
(72, 205, 1000.000, 0.000, 'ml', '2024-02-08'),
(73, 205, 3700.000, 0.000, 'ml', '2024-02-08'),
(74, 205, 1900.000, 0.000, 'ml', '2024-02-08'),
(75, 205, 4700.000, 0.000, 'ml', '2024-02-08'),
(76, 205, 4700.000, 0.000, 'ml', '2024-02-08'),
(77, 205, 4400.000, 0.000, 'ml', '2024-02-08'),
(78, 205, 6300.000, 0.000, 'ml', '2024-02-08'),
(79, 205, 6500.000, 0.000, 'ml', '2024-02-08'),
(80, 205, 2300.000, 0.000, 'ml', '2024-02-08'),
(81, 205, 2100.000, 0.000, 'ml', '2024-02-08'),
(82, 205, 2200.000, 0.000, 'ml', '2024-02-08'),
(83, 205, 2500.000, 0.000, 'ml', '2024-02-08'),
(84, 205, 4800.000, 0.000, 'ml', '2024-02-08'),
(85, 205, 2700.000, 0.000, 'ml', '2024-02-08'),
(86, 205, 2500.000, 0.000, 'ml', '2024-02-08'),
(87, 205, 5000.000, 0.000, 'ml', '2024-02-08'),
(88, 205, 5000.000, 0.000, 'ml', '2024-02-08'),
(89, 205, 3000.000, 0.000, 'ml', '2024-02-08'),
(90, 205, 3000.000, 0.000, 'ml', '2024-02-08'),
(91, 205, 6500.000, 0.000, 'ml', '2024-02-08'),
(92, 205, 6800.000, 0.000, 'ml', '2024-02-08'),
(93, 205, 4500.000, 0.000, 'ml', '2024-02-08'),
(94, 205, 1400.000, 0.000, 'ml', '2024-02-08'),
(95, 205, 6150.000, 0.000, 'ml', '2024-02-08'),
(96, 205, 5600.000, 0.000, 'ml', '2024-02-08'),
(97, 205, 5400.000, 0.000, 'ml', '2024-02-08'),
(98, 205, 3100.000, 0.000, 'ml', '2024-02-08'),
(99, 205, 3400.000, 0.000, 'ml', '2024-02-07'),
(100, 205, 3700.000, 0.000, 'ml', '2024-02-07'),
(101, 205, 3400.000, 0.000, 'ml', '2024-02-07'),
(106, 203, 25000.000, 0.000, 'g', '2024-02-08'),
(107, 204, 2000.000, 0.000, 'g', '2024-02-08'),
(108, 206, 200000.000, 0.000, 'ml', '2024-02-08'),
(109, 206, 200000.000, 0.000, 'ml', '2024-02-08'),
(110, 206, 200000.000, 0.000, 'ml', '2024-02-08'),
(111, 206, 200000.000, 0.000, 'ml', '2024-02-08'),
(112, 207, 1000.000, 0.000, 'ml', '2024-02-08'),
(113, 209, 1000.000, 0.000, 'ml', '2024-02-08'),
(114, 210, 25000.000, 0.000, 'g', '2024-02-08'),
(115, 208, 50000.000, 0.000, 'g', '2024-02-08'),
(116, 202, 6000.000, 0.000, 'g', '2024-02-08'),
(117, 201, 0.000, 375.000, 'g', '2024-02-08'),
(118, 202, 0.000, 50.000, 'g', '2024-02-08'),
(119, 203, 0.000, 250.000, 'g', '2024-02-08'),
(120, 204, 0.000, 28.000, 'g', '2024-02-08'),
(121, 205, 0.000, 2000.000, 'ml', '2024-02-08'),
(122, 206, 0.000, 3000.000, 'ml', '2024-02-08'),
(123, 205, 15000.000, 0.000, 'ml', '2024-02-08'),
(125, 205, 0.000, 2000.000, 'ml', '2024-02-08'),
(126, 204, 0.000, 28.000, 'g', '2024-02-08'),
(127, 201, 0.000, 100.000, 'g', '2024-02-08'),
(129, 201, 50000.000, 0.000, 'g', '2024-02-11'),
(156, 201, 1000.000, 0.000, 'g', '2024-02-13'),
(157, 201, 0.000, 375.000, 'g', '2024-02-14'),
(158, 202, 0.000, 50.000, 'g', '2024-02-14'),
(159, 203, 0.000, 250.000, 'g', '2024-02-14'),
(160, 204, 0.000, 28.000, 'g', '2024-02-14'),
(161, 205, 0.000, 2000.000, 'ml', '2024-02-14'),
(162, 206, 0.000, 3000.000, 'ml', '2024-02-14');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `CustomerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=157;

--
-- AUTO_INCREMENT for table `member`
--
ALTER TABLE `member`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `produced`
--
ALTER TABLE `produced`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `product_stock`
--
ALTER TABLE `product_stock`
  MODIFY `TransactionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `rawmilk`
--
ALTER TABLE `rawmilk`
  MODIFY `rawmilk_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `salesitems`
--
ALTER TABLE `salesitems`
  MODIFY `SalesItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `stock`
--
ALTER TABLE `stock`
  MODIFY `StockID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=163;

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
