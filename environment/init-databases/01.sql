CREATE DATABASE IF NOT EXISTS `identity_db` COLLATE utf8mb4_general_ci;
GRANT ALL PRIVILEGES ON `identity_db`.* TO 'task'@'%';
FLUSH PRIVILEGES;

