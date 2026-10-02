CREATE DATABASE IF NOT EXISTS mydb
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE mydb;

DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    firstname VARCHAR(100) NOT NULL,
    lastname VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    username VARCHAR(40) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_users_email (email),
    UNIQUE KEY uq_users_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO users (firstname, lastname, email, username) VALUES
('John Dell', 'Puyo', 'john.puyo@example.com', 'jdpuyo'),
('Angela', 'Reyes', 'angela.reyes@example.com', 'angelareyes'),
('Marco', 'Santos', 'marco.santos@example.com', 'marcosantos'),
('Nina', 'Garcia', 'nina.garcia@example.com', 'ninagarcia');
