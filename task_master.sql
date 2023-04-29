-- -------------------------------------------------------------
-- TablePlus 5.3.6(496)
--
-- https://tableplus.com/
--
-- Database: task_master
-- Generation Time: 2023-04-29 10:49:05.3940
-- -------------------------------------------------------------


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


CREATE TABLE `task` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `content` varchar(255) NOT NULL,
  `complete_date` date DEFAULT NULL,
  `task_section_id` int NOT NULL,
  `task_board_id` int NOT NULL,
  `is_completed` tinyint(1) NOT NULL DEFAULT '0',
  `sort_index` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `task_section_id` (`task_section_id`),
  KEY `task_board_id` (`task_board_id`),
  CONSTRAINT `task_ibfk_1` FOREIGN KEY (`task_section_id`) REFERENCES `task_section` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `task_ibfk_2` FOREIGN KEY (`task_board_id`) REFERENCES `task_board` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `task_board` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `section_num` int NOT NULL DEFAULT '0',
  `task_num` int NOT NULL DEFAULT '0',
  `user_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `task_board_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `task_section` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `content` varchar(255) NOT NULL,
  `sort_index` int NOT NULL DEFAULT '0',
  `task_board_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `task_board_id` (`task_board_id`) USING BTREE,
  CONSTRAINT `task_section_ibfk_1` FOREIGN KEY (`task_board_id`) REFERENCES `task_board` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `task` (`id`, `name`, `content`, `complete_date`, `task_section_id`, `task_board_id`, `is_completed`, `sort_index`) VALUES
(1, 'UI Design', 'Different pages of UI design need to be done.', '2023-04-26', 1, 2, 0, 1),
(2, 'Prototype', 'Design the prototype for the app.', '2023-04-26', 7, 2, 1, 0),
(4, 'Coding', 'Finish the coding part.', '2023-04-24', 1, 2, 0, 0),
(10, 'Task A', 'Here\'s Task A\'s Steps', '2023-04-13', 1, 2, 1, 2),
(11, 'Task D', 'Stepssss', '2023-04-29', 4, 7, 0, 0);

INSERT INTO `task_board` (`id`, `name`, `description`, `section_num`, `task_num`, `user_id`) VALUES
(2, 'CISC3003 Project', 'This is a project about full-stack development.', 4, 6, 3),
(3, 'CISC4001 Project', 'Testing on Client side JavaScript.', 0, 0, 3),
(4, 'CISC3025 Project', 'It\'s a project for CISC3025', 0, 0, 3),
(7, 'CISC3000', 'Database', 2, 1, 3),
(8, 'CISC4000', 'What class is this?', 0, 0, 3);

INSERT INTO `task_section` (`id`, `name`, `content`, `sort_index`, `task_board_id`) VALUES
(1, 'TODO', 'Here is where the todo placed.', 0, 2),
(4, 'Todoo', 'Yeah', 0, 7),
(7, 'Finished', 'All the finished stuff will be here.', 0, 2),
(9, 'Testing', 'Here will be placing the finished tasks.', 0, 2),
(20, 'Finished', 'Testing', 0, 7);

INSERT INTO `user` (`id`, `name`, `email`, `password`, `token`) VALUES
(3, 'tester', 'tester@gmail.com', '$2y$10$Yg3NW/aTsaWT40Dav2mDDeaBLSd4tVajiS6y4mFhHL9DlxTb8K1Tq', 'gcbcba1vrfsc5tq0lv4gs6jros');



/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;