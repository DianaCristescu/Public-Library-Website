-- DROP DATABASE IF EXISTS `biblioteca_database`;
-- CREATE DATABASE `biblioteca_database`; 
USE `biblioteca_database`;

SET NAMES utf8 ;
SET character_set_client = utf8mb4 ;

DROP TABLE IF EXISTS `collections`;
CREATE TABLE `collections` (
	`id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
	`id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(50) NOT NULL,
    `collection_id` INT,
    
    CONSTRAINT `FK_collection_categories_id` FOREIGN KEY (`collection_id`) REFERENCES `collections`(`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `authors`;
CREATE TABLE `authors` (
	`id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `publishers`;
CREATE TABLE `publishers` (
	`id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `languages`;
CREATE TABLE `languages` (
	`id` INT AUTO_INCREMENT PRIMARY KEY,
    `language` VARCHAR(50) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `books`;
CREATE TABLE `books` (
	`id` INT AUTO_INCREMENT PRIMARY KEY,
	`title` varchar(255) NOT NULL,
    `image_path` varchar(255),
    `description` TEXT,
    `page_count` INT DEFAULT 0,
    `publisher_id` INT,
    `publish_date` DATE,
    `stock_count` INT DEFAULT 0,
    `reserved_count` INT DEFAULT 0,
    `language_id` INT,
    `goodreads_link` VARCHAR(255),
    `collection_id` INT,
    `date_added` DATE DEFAULT (CURRENT_DATE),
    
    CONSTRAINT `FK_books_id_p` FOREIGN KEY (`publisher_id`) REFERENCES `publishers`(`id`),
    CONSTRAINT `FK_books_id_l` FOREIGN KEY (`language_id`) REFERENCES `languages`(`id`),
    CONSTRAINT `FK_books_id_c` FOREIGN KEY (`collection_id`) REFERENCES `collections`(`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `book_categories`;
CREATE TABLE `book_categories` (
	`book_id` INT,
    `category_id` INT,
    
    PRIMARY KEY (`book_id`, `category_id`),
    CONSTRAINT `FK_book_categories_id_b` FOREIGN KEY (`book_id`) REFERENCES `books`(`id`),
    CONSTRAINT `FK_book_categories_id_c` FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `book_authors`;
CREATE TABLE `book_authors` (
	`book_id` INT,
    `author_id` INT,
    
    PRIMARY KEY (`book_id`, `author_id`),
    CONSTRAINT `FK_book_authors_id_b` FOREIGN KEY (`book_id`) REFERENCES `books`(`id`),
    CONSTRAINT `FK_ßbook_authors_id_a` FOREIGN KEY (`author_id`) REFERENCES `authors`(`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
	`id` INT AUTO_INCREMENT PRIMARY KEY,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `first_name` VARCHAR(100) NOT NULL,
    `last_name` VARCHAR(100) NOT NULL,
    `password_hash` VARCHAR(255) NOT NULL,
    `profile_picture` VARCHAR(255) DEFAULT 'default_avatar.png',
    `role` VARCHAR(255) DEFAULT 'customer'
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `loans`;
CREATE TABLE `loans` (
    `user_id` INT NOT NULL,
    `book_id` INT NOT NULL,
    `borrow_date` DATE DEFAULT (CURRENT_DATE),
    `due_date` DATE NOT NULL,
    
    PRIMARY KEY  (`user_id`, `book_id`),
    CONSTRAINT `FK_loans_id_u` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`),
    CONSTRAINT `FK_loans_id_b` FOREIGN KEY (`book_id`) REFERENCES `books`(`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `reservations`;
CREATE TABLE `reservations` (
    `user_id` INT NOT NULL,
    `book_id` INT NOT NULL,
    `start_date` DATE DEFAULT (CURRENT_DATE),
    `end_date` DATE NOT NULL,
    
    PRIMARY KEY  (`user_id`, `book_id`),
    CONSTRAINT `FK_reservations_id_u` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`),
    CONSTRAINT `FK_reservations_id_b` FOREIGN KEY (`book_id`) REFERENCES `books`(`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `wishlist`;
CREATE TABLE `wishlist` (
	`user_id` INT NOT NULL,
    `book_id` INT NOT NULL,
    
    PRIMARY KEY  (`user_id`, `book_id`),
    CONSTRAINT `FK_wishlist_id_u` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`),
    CONSTRAINT `FK_wishlist_id_b` FOREIGN KEY (`book_id`) REFERENCES `books`(`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `user_library`;
CREATE TABLE `user_library` (
	`user_id` INT NOT NULL,
    `book_id` INT NOT NULL,
    `current_page` INT DEFAULT 0,
    `finished` BOOLEAN DEFAULT FALSE,
    `currently_loaned` BOOLEAN DEFAULT TRUE,
    `total_loaned_days` INT DEFAULT 1,
    
    PRIMARY KEY (`user_id`, `book_id`),
    CONSTRAINT `FK_user_library_id_u` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`),
    CONSTRAINT `FK_user_library_id_b` FOREIGN KEY (`book_id`) REFERENCES `books`(`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;






