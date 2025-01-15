SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
-- Configuración de la zona horaria
SET time_zone = "-05:00";
-- Configuración de caracteres
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */
;
SET NAMES utf8mb4;
-- Tabla miembros
CREATE TABLE members (
  member_id INT(11) NOT NULL AUTO_INCREMENT,
  role ENUM('usuario', 'moderador') DEFAULT 'usuario',
  firstname VARCHAR(30) NOT NULL,
  lastname VARCHAR(30) NOT NULL,
  city VARCHAR(30) NOT NULL,
  country VARCHAR(30) NOT NULL,
  email VARCHAR(50) NOT NULL UNIQUE,
  gender VARCHAR(6) NOT NULL,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  image VARCHAR(255) NOT NULL,
  birthdate DATE NOT NULL,
  mobile VARCHAR(20) NOT NULL,
  work VARCHAR(50) NOT NULL,
  tokens DECIMAL(18, 8) NOT NULL DEFAULT 0.00000000,
  PRIMARY KEY (member_id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
-- Tabla publicaciones
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
-- Tabla imagenes de publicaciones
CREATE TABLE `post_images` (
  `image_id` INT(11) NOT NULL AUTO_INCREMENT,
  `post_id` INT(11) NOT NULL,
  `image_path` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`image_id`),
  FOREIGN KEY (`post_id`) REFERENCES `post`(`post_id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
-- Tabla reports en publicaciones
CREATE TABLE `post_reports` (
  `report_id` INT(11) NOT NULL AUTO_INCREMENT,
  `post_id` INT(11) NOT NULL,
  `user_id` INT(11) NOT NULL,
  `report_type` VARCHAR(50) NOT NULL,
  `report_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `status` ENUM('pendiente', 'en_revision', 'resuelto') DEFAULT 'pendiente',
  `status_response` TEXT NULL,
  PRIMARY KEY (`report_id`),
  FOREIGN KEY (`post_id`) REFERENCES post(`post_id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES members(`member_id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
-- Tabla comentarios en publicaciones
CREATE TABLE `post_comments` (
  `comment_id` INT(11) NOT NULL AUTO_INCREMENT,
  `post_id` INT(11) NOT NULL,
  `user_id` INT(11) NOT NULL,
  `comment_text` TEXT NOT NULL,
  `comment_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `reactions` JSON DEFAULT NULL,
  `reported` BOOLEAN DEFAULT 0,
  `report_type` VARCHAR(50) DEFAULT NULL,
  `report_date` TIMESTAMP NULL,
  PRIMARY KEY (`comment_id`),
  FOREIGN KEY (`post_id`) REFERENCES post(`post_id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES members(`member_id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
--Tabla reacciones comentarios
CREATE TABLE `reactions_comment` (
  `reaction_id` INT(11) NOT NULL AUTO_INCREMENT,
  `comment_id` INT(11) NOT NULL,
  `user_id` INT(11) NOT NULL,
  `reaction_type` VARCHAR(50) NOT NULL,
  `reaction_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`reaction_id`),
  FOREIGN KEY (`comment_id`) REFERENCES post_comments(`comment_id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES members(`member_id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
-- Tabla comentarios reportados
CREATE TABLE `reports_comment` (
  `report_id` INT(11) NOT NULL AUTO_INCREMENT,
  `comment_id` INT(11) NOT NULL,
  `user_id` INT(11) NOT NULL,
  `report_type` VARCHAR(255) NOT NULL,
  `report_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `status` ENUM('pendiente', 'en_revision', 'resuelto') DEFAULT 'pendiente',
  `status_response` TEXT NULL,
  PRIMARY KEY (`report_id`),
  FOREIGN KEY (`comment_id`) REFERENCES post_comments(`comment_id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES members(`member_id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
-- Tabla amigos
CREATE TABLE `friends` (
  `my_friend_id` INT(11) NOT NULL,
  `my_id` INT(11) NOT NULL,
  `friends_id` INT(11) NOT NULL,
  PRIMARY KEY (`my_friend_id`),
  FOREIGN KEY (`my_id`) REFERENCES `members`(`member_id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
-- Tabla mensajes
CREATE TABLE `message` (
  `message_id` INT(11) NOT NULL AUTO_INCREMENT,
  `sender_id` INT(11) NOT NULL,
  `receiver_id` INT(11) NOT NULL,
  `content` VARCHAR(500) NOT NULL,
  `date_sended` DATETIME NOT NULL,
  PRIMARY KEY (`message_id`),
  FOREIGN KEY (`sender_id`) REFERENCES `members`(`member_id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
-- Tabla fotos 
CREATE TABLE `photos` (
  `photos_id` INT(11) NOT NULL AUTO_INCREMENT,
  `location` VARCHAR(100) NOT NULL,
  `member_id` INT(11) NOT NULL,
  PRIMARY KEY (`photos_id`),
  FOREIGN KEY (`member_id`) REFERENCES `members`(`member_id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
-- Tabla reacciones
CREATE TABLE `post_reactions` (
  `reaction_id` INT(11) NOT NULL AUTO_INCREMENT,
  `post_id` INT(11) NOT NULL,
  `member_id` INT(11) NOT NULL,
  `reaction_value` DECIMAL(8, 8) NOT NULL DEFAULT 0.00000005,
  PRIMARY KEY (`reaction_id`),
  FOREIGN KEY (`post_id`) REFERENCES `post`(`post_id`) ON DELETE CASCADE,
  FOREIGN KEY (`member_id`) REFERENCES `members`(`member_id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
-- Tabla de usuarios bloqueados
CREATE TABLE `blocked_users` (
  `block_id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `blocked_id` INT(11) NOT NULL,
  `date_blocked` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`block_id`),
  FOREIGN KEY (`user_id`) REFERENCES `members`(`member_id`) ON DELETE CASCADE,
  FOREIGN KEY (`blocked_id`) REFERENCES `members`(`member_id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
-- Tabla de usuarios reportados
CREATE TABLE `report_users` (
  `report_id` INT(11) NOT NULL AUTO_INCREMENT,
  `reporter_id` INT(11) NOT NULL,
  `reported_id` INT(11) NOT NULL,
  `report_type` VARCHAR(255) NOT NULL,
  `report_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `status` ENUM('pendiente', 'en_revision', 'resuelto') DEFAULT 'pendiente',
  `status_response` TEXT NULL,
  PRIMARY KEY (`report_id`),
  FOREIGN KEY (`reporter_id`) REFERENCES members(`member_id`) ON DELETE CASCADE,
  FOREIGN KEY (`reported_id`) REFERENCES members(`member_id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
-- Tabla notificaciones
CREATE TABLE `notifications` (
  `notification_id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `message` TEXT NOT NULL,
  `date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`notification_id`),
  FOREIGN KEY (`user_id`) REFERENCES `members`(`member_id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
COMMIT;