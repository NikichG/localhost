CREATE DATABASE IF NOT EXISTS `conferences_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `conferences_db`;

CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `login` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `fullname` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(20) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `role` ENUM('user', 'admin') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `bookings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `room_type` ENUM('Аудитория', 'Коворкинг', 'Кинозал') NOT NULL,
  `booking_date` DATE NOT NULL,
  `payment_method` VARCHAR(50) NOT NULL,
  `status` ENUM('Новая', 'Мероприятие назначено', 'Мероприятие завершено') DEFAULT 'Новая',
  `feedback` TEXT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `users` (`login`, `password`, `fullname`, `phone`, `email`, `role`) 
VALUES ('Admin26', '$2y$10$U2FsdGVkX19vYm9kZW5jbZ4NGe9Z7Y1D2v.Hw76q12W', 'Администратор системы', '+7 (495) 123-45-67', 'admin@conf.rf', 'admin')
ON DUPLICATE KEY UPDATE `login`=`login`;