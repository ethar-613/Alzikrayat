CREATE DATABASE IF NOT EXISTS alzikrayat
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE alzikrayat;



CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY ,
    first_name VARCHAR(50) NOT NULL ,
    last_name VARCHAR(50) NOT NULL ,
    email VARCHAR(100) UNIQUE NOT NULL ,
    password VARCHAR(255) NOT NULL ,
    location VARCHAR(100) DEFAULT NULL,
    description TEXT DEFAULT NULL,
    occupation VARCHAR(100)  DEFAULT NULL,
    created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_users_email (email)
);

CREATE TABLE photos(
    id INT AUTO_INCREMENT PRIMARY KEY ,
    user_id INT NOT NULL ,
    file_name VARCHAR(255) NOT NULL ,
    title VARCHAR(200) NOT NULL ,
    description TEXT DEFAULT NULL ,
    date_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_photos_user 
        FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_photos_user_id (user_id),
    INDEX idx_photos_date (date_time)
);


CREATE TABLE comments(
    id INT AUTO_INCREMENT PRIMARY KEY ,
    photo_id INT NOT NULL ,
    user_id INT NOT NULL ,
    comment TEXT NOT NULL ,
    date_time  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_comments_photo
             FOREIGN KEY (photo_id) REFERENCES Photos(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_comments_user
              FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_comments_photo_id (photo_id),
    INDEX idx_comments_user_id (user_id)
)
