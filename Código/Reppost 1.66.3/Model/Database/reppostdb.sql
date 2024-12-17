SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
-- Configuración de la zona horaria
SET time_zone = "-05:00";
-- Configuración de caracteres
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */
;
SET NAMES utf8mb4;
-- Tabla members
CREATE TABLE members (
  member_id INT(11) NOT NULL AUTO_INCREMENT,
  firstname VARCHAR(30) NOT NULL,
  lastname VARCHAR(30) NOT NULL,
  city VARCHAR(30) NOT NULL,
  country VARCHAR(30) NOT NULL,
  email VARCHAR(50) NOT NULL UNIQUE,
  gender VARCHAR(10) NOT NULL,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  image VARCHAR(255) NOT NULL,
  birthdate DATE NOT NULL,
  mobile VARCHAR(20) NOT NULL,
  work VARCHAR(50) NOT NULL,
  tokens DECIMAL(18, 8) NOT NULL DEFAULT 0.00000000,
  email_verified TINYINT(1) NOT NULL DEFAULT 0,
  verification_code VARCHAR(10) DEFAULT NULL,
  verification_expires DATETIME DEFAULT NULL,
  reset_code VARCHAR(10) DEFAULT NULL,
  reset_expires DATETIME DEFAULT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (member_id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
-- Tabla post
CREATE TABLE `post` (
  `post_id` INT(11) NOT NULL AUTO_INCREMENT,
  `member_id` INT(11) NOT NULL,
  `content` VARCHAR(1000) NOT NULL,
  `date_posted` DATETIME NOT NULL,
  `token_reward` DECIMAL(20, 8) NOT NULL DEFAULT 0.00000015,
  `previous_hash` VARCHAR(64) NOT NULL,
  `current_hash` VARCHAR(64) NOT NULL,
  PRIMARY KEY (`post_id`),
  FOREIGN KEY (`member_id`) REFERENCES `members`(`member_id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
-- Tabla post_images
CREATE TABLE `post_images` (
  `image_id` INT(11) NOT NULL AUTO_INCREMENT,
  `post_id` INT(11) NOT NULL,
  `image_path` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`image_id`),
  FOREIGN KEY (`post_id`) REFERENCES `post`(`post_id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
-- Tabla friends
CREATE TABLE `friends` (
  `my_friend_id` INT(11) NOT NULL AUTO_INCREMENT,
  `my_id` INT(11) NOT NULL,
  `friends_id` INT(11) NOT NULL,
  PRIMARY KEY (`my_friend_id`),
  FOREIGN KEY (`my_id`) REFERENCES `members`(`member_id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
-- Tabla message
CREATE TABLE `message` (
  `message_id` INT(11) NOT NULL AUTO_INCREMENT,
  `sender_id` INT(11) NOT NULL,
  `receiver_id` INT(11) NOT NULL,
  `content` VARCHAR(500) NOT NULL,
  `date_sended` DATETIME NOT NULL,
  PRIMARY KEY (`message_id`),
  FOREIGN KEY (`sender_id`) REFERENCES `members`(`member_id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
-- Tabla photos
CREATE TABLE `photos` (
  `photos_id` INT(11) NOT NULL AUTO_INCREMENT,
  `location` VARCHAR(100) NOT NULL,
  `member_id` INT(11) NOT NULL,
  PRIMARY KEY (`photos_id`),
  FOREIGN KEY (`member_id`) REFERENCES `members`(`member_id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
-- Nueva tabla para almacenar las reacciones
CREATE TABLE `post_reactions` (
  `reaction_id` INT(11) NOT NULL AUTO_INCREMENT,
  `post_id` INT(11) NOT NULL,
  `member_id` INT(11) NOT NULL,
  `reaction_value` DECIMAL(20, 8) NOT NULL DEFAULT 0.00000005,
  -- Valor por cada reacción
  PRIMARY KEY (`reaction_id`),
  FOREIGN KEY (`post_id`) REFERENCES `post`(`post_id`) ON DELETE CASCADE,
  FOREIGN KEY (`member_id`) REFERENCES `members`(`member_id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
COMMIT;
-- Restaurar configuraciones
/*!40101 SET CHARACTER_SET_CLIENT = @OLD_CHARACTER_SET_CLIENT */
;
SET CHARACTER_SET_RESULTS = @OLD_CHARACTER_SET_RESULTS;