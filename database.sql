DROP USER IF EXISTS 'derpusr'@'localhost';
CREATE USER 'derpusr'@'localhost' IDENTIFIED BY 'derpazo123';

DROP DATABASE IF EXISTS DerpPlay;
CREATE DATABASE DerpPlay CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

GRANT ALL PRIVILEGES ON DerpPlay.* TO 'derpusr'@'localhost';
FLUSH PRIVILEGES;

USE DerpPlay;

CREATE TABLE Users(
    user_id INTEGER NOT NULL AUTO_INCREMENT,
    user_name VARCHAR(10) NOT NULL,
    user_email VARCHAR(40) NOT NULL,
    user_password VARCHAR(64) NOT NULL,
    user_img VARCHAR(60) NOT NULL DEFAULT '/DerpPlay/img/profile/default.jpg',
    user_verified BOOLEAN NOT NULL DEFAULT FALSE,
    user_hash VARCHAR(32) DEFAULT NULL,
    PRIMARY KEY (user_id)
);

CREATE TABLE Videos(
    video_id INTEGER NOT NULL AUTO_INCREMENT,
    video_name VARCHAR(45) NOT NULL,
    video_desc VARCHAR(250) NOT NULL,
    video_uploader INTEGER NOT NULL,
    video_uri VARCHAR(60) NOT NULL,
    video_uploaded DATETIME NOT NULL,
    video_preview VARCHAR(60) NOT NULL DEFAULT '/DerpPlay/img/preview/default.jpg',
    PRIMARY KEY (video_id),
    FOREIGN KEY (video_uploader) REFERENCES Users (user_id) ON UPDATE CASCADE ON DELETE CASCADE
);