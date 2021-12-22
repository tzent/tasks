CREATE DATABASE IF NOT EXISTS `api_admin_db` COLLATE utf8mb4_general_ci;
GRANT ALL PRIVILEGES ON `api_admin_db`.* TO 'task'@'%';
FLUSH PRIVILEGES;
