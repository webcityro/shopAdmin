/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table webcity_mvc.categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parentID` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `applyedTo` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=118 DEFAULT CHARSET=latin1;

-- Dumping data for table webcity_mvc.categories: ~16 rows (approximately)
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` (`id`, `parentID`, `name`, `applyedTo`) VALUES
	(67, 95, 'math', 'libraries|tutorials'),
	(90, 0, 'php7', 'libraries|tutorials'),
	(91, 0, 'html', 'tutorials'),
	(92, 0, 'javascript', 'tutorials'),
	(93, 0, 'css', 'libraries|tutorials'),
	(94, 91, 'info', 'tutorials'),
	(95, 90, 'functii', 'libraries|tutorials'),
	(96, 90, 'variabile', 'tutorials'),
	(97, 90, 'loops', NULL),
	(99, 95, 'string', NULL),
	(100, 99, 'strlen', NULL),
	(101, 99, 'strpos', NULL),
	(102, 99, 'substr', NULL),
	(103, 99, 'str_replace', NULL),
	(105, 95, 'array', 'tutorials'),
	(117, 0, 'html5', 'libraries');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;

-- Dumping structure for table webcity_mvc.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fName` varchar(25) NOT NULL,
  `lName` varchar(25) DEFAULT NULL,
  `userName` varchar(32) NOT NULL,
  `password` varchar(64) NOT NULL,
  `salt` varchar(32) NOT NULL,
  `email` varchar(255) NOT NULL,
  `sex` enum('f','m') NOT NULL,
  `singUpDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `lastLogInDate` timestamp NULL DEFAULT NULL,
  `lastLogOutDate` timestamp NULL DEFAULT NULL,
  `code` varchar(32) NOT NULL,
  `active` enum('0','1') NOT NULL,
  `inActiveDate` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `userName` (`userName`,`email`,`code`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;

-- Dumping data for table webcity_mvc.users: 101 rows
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `fName`, `lName`, `userName`, `password`, `salt`, `email`, `sex`, `singUpDate`, `lastLogInDate`, `lastLogOutDate`, `code`, `active`, `inActiveDate`) VALUES
	(1, 'andy', '', 'andy87', '62f26050cfc3bf9571e806051871d4401c240e028e742e2f77c0858efec897fd', 'Ð£ƒ¦Å”ª¸#4Û¹Èò[Å±9\'ËAaaÏ”', 'andreivalcu@gmail.com', 'm', '2015-10-05 23:53:23', '2015-10-05 20:53:23', '2015-04-05 00:06:45', '2bbb5c9794aef0513737e47b3184d85c', '1', '2015-03-17');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

-- Dumping structure for table webcity_mvc.users_sessions
CREATE TABLE IF NOT EXISTS `users_sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `hash` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;