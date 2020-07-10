-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.11-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             10.3.0.5771
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for dicegame
CREATE DATABASE IF NOT EXISTS `dicegame` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `dicegame`;

-- Dumping structure for table dicegame.authedusers
CREATE TABLE IF NOT EXISTS `authedusers` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Username1` mediumtext DEFAULT NULL,
  `Username2` mediumtext DEFAULT NULL,
  `Username1Wins` mediumint(9) DEFAULT 0,
  `Username2Wins` mediumint(9) DEFAULT 0,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COMMENT='This database includes all authorised users!';

-- Dumping data for table dicegame.authedusers: ~1 rows (approximately)
/*!40000 ALTER TABLE `authedusers` DISABLE KEYS */;
INSERT INTO `authedusers` (`ID`, `Username1`, `Username2`, `Username1Wins`, `Username2Wins`) VALUES
	(9, 'Kyle', 'Matt', 10, 5);
/*!40000 ALTER TABLE `authedusers` ENABLE KEYS */;

-- Dumping structure for table dicegame.matches
CREATE TABLE IF NOT EXISTS `matches` (
  `MatchID` int(11) NOT NULL AUTO_INCREMENT,
  `Username1` mediumtext DEFAULT NULL,
  `Username2` mediumtext DEFAULT NULL,
  `User1Score` mediumint(9) NOT NULL DEFAULT 0,
  `User2Score` mediumint(9) NOT NULL DEFAULT 0,
  `Round` mediumint(9) NOT NULL DEFAULT 0,
  `WinnerUsername` mediumtext DEFAULT NULL,
  PRIMARY KEY (`MatchID`)
) ENGINE=InnoDB AUTO_INCREMENT=543544 DEFAULT CHARSET=utf8mb4 COMMENT='Data for game matches!';

-- Dumping data for table dicegame.matches: ~3 rows (approximately)
/*!40000 ALTER TABLE `matches` DISABLE KEYS */;
INSERT INTO `matches` (`MatchID`, `Username1`, `Username2`, `User1Score`, `User2Score`, `Round`, `WinnerUsername`) VALUES
	(77380, 'Kyle', 'Matt', 76, 58, 4, 'Kyle'),
	(80298, 'Kyle', 'Matt', 48, 30, 4, 'Kyle'),
	(93982, 'Kyle', 'Matt', 60, 82, 4, 'Matt');
/*!40000 ALTER TABLE `matches` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
