--
-- Database: `PonyPark`
--

CREATE DATABASE PonyPark;
USE PonyPark;

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE IF NOT EXISTS `Users` (
  `UserID` int NOT NULL AUTO_INCREMENT,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Password` varchar(64) DEFAULT NULL,
  `PasswordSalt` varchar(50) DEFAULT NULL,
  `PhoneNumber` varchar(20) DEFAULT NULL,
  `UserType` int NOT NULL,
  `ExternalType` varchar(10) NOT NULL,
  `ExternalID` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`UserID`),
  UNIQUE KEY `Email` (`Email`,`ExternalType`),
  UNIQUE KEY `ExternalType` (`ExternalType`,`ExternalID`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table `ParkingLocations`
--

CREATE TABLE IF NOT EXISTS `ParkingLocations` (
  `ParkingID` int NOT NULL AUTO_INCREMENT,
  `Name` varchar(50) NOT NULL,
  `Address` varchar(200) NOT NULL,
  `Cost` double DEFAULT NULL,
  `Comments` varchar(250) DEFAULT NULL,
  `NumberOfLevels` int NOT NULL,
  PRIMARY KEY (`ParkingID`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table `CommuteTimes`
--

CREATE TABLE IF NOT EXISTS `CommuteTimes` (
  `CommuteID` int NOT NULL AUTO_INCREMENT,
  `UserID` int NOT NULL,
  `Time` time NOT NULL,
  `Day` int NOT NULL,
  `WarningTime` time DEFAULT NULL,
  `TimeOfNotification` datetime DEFAULT NULL,
  PRIMARY KEY (`CommuteID`),
  UNIQUE KEY (`UserID`, `Time`, `Day`),
  CONSTRAINT FOREIGN KEY (`UserID`) REFERENCES `Users` (`UserID`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table `FavoriteGarages`
--

CREATE TABLE IF NOT EXISTS `FavoriteGarages` (
  `FavoriteID` int NOT NULL AUTO_INCREMENT,
  `UserID` int NOT NULL,
  `ParkingID` int NOT NULL,
  `Priority` int NOT NULL,
  PRIMARY KEY (`FavoriteID`),
  UNIQUE KEY `UserID` (`UserID`,`ParkingID`),
  CONSTRAINT FOREIGN KEY (`UserID`) REFERENCES `Users` (`UserID`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT FOREIGN KEY (`ParkingID`) REFERENCES `ParkingLocations` (`ParkingID`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table `Ratings`
--

CREATE TABLE IF NOT EXISTS `Ratings` (
  `RatingID` int NOT NULL AUTO_INCREMENT,
  `ParkingID` int NOT NULL,
  `UserID` int DEFAULT NULL,
  `Timestamp` datetime NOT NULL,
  `Rating` int NOT NULL,
  `Level` int NOT NULL,
  PRIMARY KEY (`RatingID`),
  CONSTRAINT FOREIGN KEY (`UserID`) REFERENCES `Users` (`UserID`)
    ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT FOREIGN KEY (`ParkingID`) REFERENCES `ParkingLocations` (`ParkingID`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table `Requests`
--

CREATE TABLE IF NOT EXISTS `Requests` (
  `RequestID` int NOT NULL AUTO_INCREMENT,
  `UserID` int NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Address` varchar(200) NOT NULL,
  `Cost` double DEFAULT NULL,
  `NumberOfLevels` int NOT NULL,
  `Comments` varchar(250) DEFAULT NULL,
  `Status` int NOT NULL,
  PRIMARY KEY (`RequestID`),
  CONSTRAINT FOREIGN KEY (`UserID`) REFERENCES `Users` (`UserID`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;