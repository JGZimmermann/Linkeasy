SET foreign_key_checks = 0;

DROP TABLE IF EXISTS tags_in_posts;
DROP TABLE IF EXISTS posts_updates;
DROP TABLE IF EXISTS posts;
DROP TABLE IF EXISTS tags;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
                     id INT AUTO_INCREMENT PRIMARY KEY,
                     name VARCHAR(255) NOT NULL,
                     email VARCHAR(255) UNIQUE NOT NULL,
                     encrypted_password VARCHAR(255) NOT NULL,
                     role ENUM('STUDENT', 'PROFESSOR', 'MODERATOR') NOT NULL,
                     photo_url VARCHAR(255)
);

CREATE TABLE posts (
                     id INT AUTO_INCREMENT PRIMARY KEY,
                     title VARCHAR(255) NOT NULL,
                     body TEXT NOT NULL,
                     date DATETIME NOT NULL,
                     user_id INT,
                     FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE posts_updates (
                             id INT AUTO_INCREMENT PRIMARY KEY,
                             content VARCHAR(255) NOT NULL,
                             status ENUM('PENDING', 'APPROVED', 'REJECTED') NOT NULL,
                             user_id INT NOT NULL,
                             post_id INT NOT NULL,
                             FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                             FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
);

CREATE TABLE tags (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(255) NOT NULL
);

CREATE TABLE tags_in_posts (
                             id INT AUTO_INCREMENT PRIMARY KEY,
                             tag_id INT,
                             post_id INT,
                             FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE SET NULL,
                             FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE SET NULL
);

SET foreign_key_checks = 1;
