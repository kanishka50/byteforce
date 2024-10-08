-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.39 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for inventorymgt
CREATE DATABASE IF NOT EXISTS `inventorymgt` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `inventorymgt`;

-- Dumping structure for table inventorymgt.brand
CREATE TABLE IF NOT EXISTS `brand` (
  `BrandID` int NOT NULL AUTO_INCREMENT,
  `Name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`BrandID`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table inventorymgt.brand: ~5 rows (approximately)
INSERT INTO `brand` (`BrandID`, `Name`) VALUES
	(1, 'Apple'),
	(2, 'Dell'),
	(3, 'Sony'),
	(4, 'Huawei'),
	(5, 'Redmi');

-- Dumping structure for table inventorymgt.category
CREATE TABLE IF NOT EXISTS `category` (
  `CategoryID` int NOT NULL AUTO_INCREMENT,
  `Name` varchar(45) DEFAULT NULL,
  `Description` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`CategoryID`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table inventorymgt.category: ~4 rows (approximately)
INSERT INTO `category` (`CategoryID`, `Name`, `Description`) VALUES
	(1, 'Phone', 'Smartphones and accessories'),
	(2, 'Laptop', 'Laptops and accessories'),
	(3, 'Headphone', 'Headphones and earphones'),
	(4, 'Keyboards', 'High Quality Brand new Keyboards'),
	(5, 'Camera', 'Every kind of camera is available');

-- Dumping structure for table inventorymgt.categoryhassupplier
CREATE TABLE IF NOT EXISTS `categoryhassupplier` (
  `CategoryID` int NOT NULL,
  `SupplierID` int NOT NULL,
  `Quantity` int DEFAULT NULL,
  KEY `CategoryID` (`CategoryID`),
  KEY `SupplierID` (`SupplierID`),
  CONSTRAINT `categoryhassupplier_ibfk_1` FOREIGN KEY (`CategoryID`) REFERENCES `category` (`CategoryID`),
  CONSTRAINT `categoryhassupplier_ibfk_2` FOREIGN KEY (`SupplierID`) REFERENCES `supplier` (`SupplierID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table inventorymgt.categoryhassupplier: ~4 rows (approximately)
INSERT INTO `categoryhassupplier` (`CategoryID`, `SupplierID`, `Quantity`) VALUES
	(1, 1, 50),
	(2, 2, 30),
	(3, 3, 70),
	(4, 1, NULL);

-- Dumping structure for table inventorymgt.customer
CREATE TABLE IF NOT EXISTS `customer` (
  `CustomerID` int NOT NULL AUTO_INCREMENT,
  `Name` varchar(45) DEFAULT NULL,
  `Email` varchar(45) DEFAULT NULL,
  `Telephone` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`CustomerID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table inventorymgt.customer: ~4 rows (approximately)
INSERT INTO `customer` (`CustomerID`, `Name`, `Email`, `Telephone`) VALUES
	(1, 'Alice Brown', 'alice.brown@example.com', '1231231234'),
	(2, 'Bob White', 'bob.white@exampleeee.com', '4321432143'),
	(3, 'Tharindu Ananda', 'tharindu@gmail.com', '0775858584'),
	(4, 'Vidushani Shashikala', 'vidushani@gmail.com', '0785656248');

-- Dumping structure for table inventorymgt.item
CREATE TABLE IF NOT EXISTS `item` (
  `ItemID` int NOT NULL AUTO_INCREMENT,
  `Name` varchar(45) DEFAULT NULL,
  `Quantity` int DEFAULT NULL,
  `PurchasePrice` double DEFAULT NULL,
  `SellingPrice` double DEFAULT NULL,
  `Status` tinyint DEFAULT NULL,
  `Description` varchar(500) DEFAULT NULL,
  `CategoryID` int NOT NULL,
  `BrandID` int NOT NULL,
  PRIMARY KEY (`ItemID`),
  KEY `CategoryID` (`CategoryID`),
  KEY `BrandID` (`BrandID`),
  CONSTRAINT `item_ibfk_1` FOREIGN KEY (`CategoryID`) REFERENCES `category` (`CategoryID`),
  CONSTRAINT `item_ibfk_2` FOREIGN KEY (`BrandID`) REFERENCES `brand` (`BrandID`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table inventorymgt.item: ~3 rows (approximately)
INSERT INTO `item` (`ItemID`, `Name`, `Quantity`, `PurchasePrice`, `SellingPrice`, `Status`, `Description`, `CategoryID`, `BrandID`) VALUES
	(1, 'iPhone 12', 16, 700, 800, 1, 'Latest iPhone model', 1, 1),
	(2, 'Dell XPS 13', 6, 1000, 1200, 1, '13-inch laptop', 2, 2),
	(3, 'Sony WH-1000XM4', 7, 200, 300, 1, 'Noise-cancelling headphones', 3, 3);

-- Dumping structure for table inventorymgt.itemhasorder
CREATE TABLE IF NOT EXISTS `itemhasorder` (
  `ItemID` int NOT NULL,
  `OrderID` int NOT NULL,
  `Quantity` int DEFAULT NULL,
  KEY `ItemID` (`ItemID`),
  KEY `OrderID` (`OrderID`),
  CONSTRAINT `itemhasorder_ibfk_1` FOREIGN KEY (`ItemID`) REFERENCES `item` (`ItemID`),
  CONSTRAINT `itemhasorder_ibfk_2` FOREIGN KEY (`OrderID`) REFERENCES `orders` (`OrderID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table inventorymgt.itemhasorder: ~3 rows (approximately)
INSERT INTO `itemhasorder` (`ItemID`, `OrderID`, `Quantity`) VALUES
	(1, 1, 2),
	(3, 1, 3),
	(1, 10, 2),
	(1, 11, 1);

-- Dumping structure for table inventorymgt.orders
CREATE TABLE IF NOT EXISTS `orders` (
  `OrderID` int NOT NULL AUTO_INCREMENT,
  `DateAdded` datetime DEFAULT NULL,
  `Amount` double DEFAULT NULL,
  `CustomerID` int NOT NULL,
  PRIMARY KEY (`OrderID`),
  KEY `CustomerID` (`CustomerID`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `customer` (`CustomerID`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table inventorymgt.orders: ~2 rows (approximately)
INSERT INTO `orders` (`OrderID`, `DateAdded`, `Amount`, `CustomerID`) VALUES
	(1, '2024-08-19 10:00:00', 1500, 1),
	(10, '2024-08-27 10:10:00', 1600, 2),
	(11, '2024-08-29 10:01:00', 800, 3);

-- Dumping structure for table inventorymgt.supplier
CREATE TABLE IF NOT EXISTS `supplier` (
  `SupplierID` int NOT NULL AUTO_INCREMENT,
  `Name` varchar(45) DEFAULT NULL,
  `Email` varchar(30) DEFAULT NULL,
  `Telephone` varchar(10) DEFAULT NULL,
  `Address` text,
  PRIMARY KEY (`SupplierID`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table inventorymgt.supplier: ~3 rows (approximately)
INSERT INTO `supplier` (`SupplierID`, `Name`, `Email`, `Telephone`, `Address`) VALUES
	(1, 'TechSupplier Co.', 'techsupplier@gmail.com', '5551234567', 'No. 172 Colombo Road, Kadawatha'),
	(2, 'Gadgets Inc.', 'gadgetskelaniya@gmail.com', '0701614804', 'C/58 Dalugama, Kelaniya'),
	(3, 'Electronics World', 'electroworld@gmail.com', '5557654321', '85/1 Kiribathgoda, Kelaniya');

-- Dumping structure for table inventorymgt.user
CREATE TABLE IF NOT EXISTS `user` (
  `UserID` int NOT NULL AUTO_INCREMENT,
  `FName` varchar(45) DEFAULT NULL,
  `LName` varchar(45) DEFAULT NULL,
  `Email` varchar(45) DEFAULT NULL,
  `Telephone` varchar(10) DEFAULT NULL,
  `UName` varchar(20) DEFAULT NULL,
  `PasswordHash` varchar(255) NOT NULL,
  `verification_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`UserID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table inventorymgt.user: ~4 rows (approximately)
INSERT INTO `user` (`UserID`, `FName`, `LName`, `Email`, `Telephone`, `UName`, `PasswordHash`, `verification_code`) VALUES
	(1, 'John', 'Doe', 'john.doe@example.com', '1234567890', 'johndoe', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', ''),
	(2, 'Jane', 'Smith', 'jane.smith@example.com', '0987654321', 'janesmith', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', ''),
	(3, 'Admin', 'admin', 'tekiyagaming@gmail.com', '0701614804', 'teki', '$2y$10$bu4RQHZEDw4sjQOPNL/5puvr5uYzoq1IqbzlrtRSboLCbXrIm5CGq', '66cd2d0f04e9e'),
	(4, 'Admin', 'admin', 'admin@gmail.com', '0701614804', 'admin', '$2y$10$bu4RQHZEDw4sjQOPNL/5puvr5uYzoq1IqbzlrtRSboLCbXrIm5CGq', NULL);

-- Dumping structure for trigger inventorymgt.before_update_item
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `before_update_item` BEFORE UPDATE ON `item` FOR EACH ROW BEGIN
    -- Check if the new quantity is zero
    IF NEW.Quantity = 0 THEN
        -- Set the status to 0 before the update is committed
        SET NEW.Status = 0;
    END IF;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
