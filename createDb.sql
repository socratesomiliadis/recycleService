CREATE DATABASE IF NOT EXISTS `recycle` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `recycle`;

CREATE TABLE `user` (
  `id` int AUTO_INCREMENT PRIMARY KEY,
  `username` varchar(25) NOT NULL,
  `password` varchar(30) NOT NULL,
  `role` int NOT NULL,
  `currentPoints` int NOT NULL,
  CONSTRAINT uc_user UNIQUE (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `request` (
  `id` int AUTO_INCREMENT PRIMARY KEY,
  `reward_id` int NOT NULL,
  `user_id` int,
  `pending` BIT DEFAULT 1,
  `approved` BIT DEFAULT NULL,
  FOREIGN KEY (user_id) REFERENCES user(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `points` (
  `user_id` int PRIMARY KEY,
  `paper` int,
  `glass` int,
  `aluminum` int,
  FOREIGN KEY (user_id) REFERENCES user(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;