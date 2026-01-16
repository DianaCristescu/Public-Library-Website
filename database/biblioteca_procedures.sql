DELIMITER //

	-- Procedure 1: Get all Collections
    DROP PROCEDURE IF EXISTS GetCollections //
	CREATE PROCEDURE GetCollections(
    )
	BEGIN
		SELECT `id`, `name` FROM `collections` ORDER BY `id`;
	END //
    
DELIMITER ;

DELIMITER //

	-- Procedure 2: Get Categories for a specific Collection
    DROP PROCEDURE IF EXISTS GetCategoriesFromCollection //
	CREATE PROCEDURE GetCategoriesFromCollection(
		IN `col_id` INT
	)
	BEGIN
		SELECT `id`, `name` FROM `categories` WHERE `collection_id` = `col_id`;
	END //

DELIMITER ;

DELIMITER //

	-- Procedure 3: Get All Books Ordered From Last to First Added
	DROP PROCEDURE IF EXISTS GetLatestArrivals //
	CREATE PROCEDURE GetLatestArrivals(
		IN `page_num` INT,
        OUT `max_page_num` INT
	)
	BEGIN
		DECLARE `items_per_page` INT DEFAULT 30;
		DECLARE `offset_val` INT;
        
        SELECT
			GREATEST(1, CEIL(COUNT(*)/`items_per_page`))
		INTO `max_page_num`
		FROM `books`;
		
		IF `page_num` < 1 THEN
			SET `page_num` = 1;
		END IF;
		IF `page_num` > `max_page_num` THEN
			SET `page_num` = `max_page_num`;
		END IF;
		
		SET `offset_val` = (`page_num` - 1) * `items_per_page`;
	
		SELECT 
			b.`id`, 
			b.`title`, 
			b.`image_path`, 
			b.`date_added`,
			GROUP_CONCAT(a.`name` SEPARATOR ', ') AS `author_names`
		FROM books AS b
		LEFT JOIN `book_authors` AS ba ON b.`id` = ba.`book_id`
		LEFT JOIN `authors` AS a ON ba.`author_id` = a.`id`
		GROUP BY b.`id`
		ORDER BY b.`date_added` DESC
		LIMIT `items_per_page` OFFSET `offset_val`; 
	END //
    
DELIMITER ;

DELIMITER //

	-- Procedure 4: Get All Books From a Collection
	DROP PROCEDURE IF EXISTS GetBooksFromCollection;
	CREATE PROCEDURE GetBooksFromCollection (
		IN `target_collection_id` INT,
		IN `page_num` INT,
		OUT `max_page_num` INT
	)
	BEGIN
		DECLARE `items_per_page` INT DEFAULT 30;
		DECLARE `offset_val` INT;
		
		SELECT
			GREATEST(1, CEIL(COUNT(*)/`items_per_page`))
		INTO `max_page_num`
		FROM `books` as b
		WHERE b.`collection_id` = `target_collection_id`;
		
		IF `page_num` < 1 THEN
			SET `page_num` = 1;
		END IF;
		IF `page_num` > `max_page_num` THEN
			SET `page_num` = `max_page_num`;
		END IF;
		
		SET `offset_val` = (`page_num` - 1) * `items_per_page`;
		
		SELECT
			b.`id`,
			b.`title`,
			b.`image_path`,
			b.`date_added`,
			GROUP_CONCAT(a.`name` SEPARATOR ', ') AS `author_names`
		FROM `books` AS b
		LEFT JOIN `book_authors` AS ba ON b.`id` = ba.`book_id`
		LEFT JOIN `authors` AS a ON ba.`author_id` = a.`id`
		WHERE b.`collection_id` = `target_collection_id`
		GROUP BY b.`id`
		LIMIT `items_per_page` OFFSET `offset_val`;
	END //

DELIMITER ;

DELIMITER //

	-- Procedure 5: Get All Books From a Category
	DROP PROCEDURE IF EXISTS GetBooksFromCategory;
	CREATE PROCEDURE GetBooksFromCategory (
		IN `target_category_id` INT,
		IN `page_num` INT,
		OUT `max_page_num` INT
	)
	BEGIN
		DECLARE `items_per_page` INT DEFAULT 30;
		DECLARE `offset_val` INT;
		
		SELECT
			GREATEST(1, CEIL(COUNT(DISTINCT b.id)/`items_per_page`))
		INTO `max_page_num`
		FROM `books` as b
		INNER JOIN `book_categories` AS bc ON b.`id` = bc.`book_id`
		WHERE `target_category_id` = bc.`category_id`;
		
		IF `page_num` < 1 THEN
			SET `page_num` = 1;
		END IF;
		IF `page_num` > `max_page_num` THEN
			SET `page_num` = `max_page_num`;
		END IF;
		
		SET `offset_val` = (`page_num` - 1) * `items_per_page`;
		
		SELECT
			b.`id`,
			b.`title`,
			b.`image_path`,
			b.`date_added`,
			GROUP_CONCAT(a.`name` SEPARATOR ', ') AS `author_names`
		FROM `books` AS b
		LEFT JOIN `book_authors` AS ba ON b.`id` = ba.`book_id`
		LEFT JOIN `authors` AS a ON ba.`author_id` = a.`id`
		INNER JOIN `book_categories` AS bc ON b.`id` = bc.`book_id`
		WHERE `target_category_id` = bc.`category_id`
		GROUP BY b.`id`
		LIMIT `items_per_page` OFFSET `offset_val`;
	END //

DELIMITER ;

DELIMITER //

	-- Procedure 3: Get All Info for a Book
	DROP PROCEDURE IF EXISTS GetBookInfoFromId;
	CREATE PROCEDURE GetBookInfoFromId (
		IN `target_id` INT
	)
	BEGIN
		SELECT
			b.`id`,
			b.`title`,
			b.`image_path`,
			b.`date_added`,
			b.`description`,
			b.`page_count`,
			b.`publish_date`,
			b.`stock_count`,
			b.`reserved_count`,
			b.`language_id`,
			b.`goodreads_link`,
			p.`name` AS `publisher_name`,
			GROUP_CONCAT(a.`name` SEPARATOR ', ') AS `author_names`
		FROM `books` AS b
		LEFT JOIN `book_authors` AS ba ON b.`id` = ba.`book_id`
		LEFT JOIN `authors` AS a ON ba.`author_id` = a.`id`
		LEFT JOIN `publishers` AS p ON b.`publisher_id` = p.`id`
		WHERE b.`id` = `target_id`
		GROUP BY b.`id`;
	END//

DELIMITER ;

DELIMITER //

	-- Procedure 4: Get All Info for a User
    DROP PROCEDURE IF EXISTS GetUserInfoFromId //
	CREATE PROCEDURE GetUserInfoFromId(
		IN `target_id` INT
    )
	BEGIN
		SELECT
			`email`,
            `first_name`,
            `last_name`,
            `profile_picture`
        FROM `users`
        WHERE `target_id` = `users`.`id`;
	END //
    
DELIMITER ;

DELIMITER //

	-- Procedure 5: Get Wishlist for a User
    DROP PROCEDURE IF EXISTS GetUserWishlistFromId //
	CREATE PROCEDURE GetUserWishlistFromId(
		IN `target_id` INT
    )
	BEGIN
		SELECT
			b.`id`,
			b.`title`,
			b.`image_path`,
            GROUP_CONCAT(a.`name` SEPARATOR ', ') AS `author_names`
        FROM `wishlist` AS w
        INNER JOIN `books` AS b ON b.`id` = w.`book_id`
		LEFT JOIN `book_authors` AS ba ON b.`id` = ba.`book_id`
		LEFT JOIN `authors` AS a ON ba.`author_id` = a.`id`
        WHERE `target_id` = w.`user_id`
        GROUP BY b.`id`;
	END //
    
DELIMITER ;

DELIMITER //

	-- Procedure 6: Get Borrowed Books for a User
    DROP PROCEDURE IF EXISTS GetBorrowedBooksFromId //
	CREATE PROCEDURE GetBorrowedBooksFromId(
		IN `target_id` INT
    )
	BEGIN
		SELECT
			b.`id`,
            b.`title`,
            l.`borrow_date`,
            l.`due_date`
        FROM `loans` AS l
        INNER JOIN `books` AS b ON b.`id` = l.`book_id`
        WHERE `target_id` = l.`user_id`
        GROUP BY b.`id`;
	END //
    
DELIMITER ;

DELIMITER //

	-- Procedure 7: Get Reserved Books for a User
    DROP PROCEDURE IF EXISTS GetReservedBooksFromId //
	CREATE PROCEDURE GetReservedBooksFromId(
		IN `target_id` INT
    )
	BEGIN
		SELECT
			b.`id`,
            b.`title`,
            r.`start_date`,
            r.`end_date`
        FROM `reservations` AS r
        INNER JOIN `books` AS b ON b.`id` = r.`book_id`
        WHERE `target_id` = r.`user_id`
        GROUP BY b.`id`;
	END //
    
DELIMITER ;

DELIMITER //

	-- Procedure 8: Get Completed Books for a User
    DROP PROCEDURE IF EXISTS GetCompletedBooksForUserId //
	CREATE PROCEDURE GetCompletedBooksForUserId(
		IN `target_id` INT
    )
	BEGIN
		SELECT
			b.`id`,
			b.`title`,
			b.`image_path`,
			b.`page_count`,
            ul.`current_page`,
            GROUP_CONCAT(a.`name` SEPARATOR ', ') AS `author_names`
        FROM `user_library` AS ul
        INNER JOIN `books` AS b ON b.`id` = ul.`book_id`
		LEFT JOIN `book_authors` AS ba ON b.`id` = ba.`book_id`
		LEFT JOIN `authors` AS a ON ba.`author_id` = a.`id`
        WHERE ul.`user_id` = `target_id` AND ul.`current_page` = b.`page_count`
        GROUP BY b.`id`;
	END //
    
DELIMITER ;

DELIMITER //

	-- Procedure 9: Get In Progress (started, but not finished) Books for a User
    DROP PROCEDURE IF EXISTS GetInProgressBooksForUserId //
	CREATE PROCEDURE GetInProgressBooksForUserId(
		IN `target_id` INT
    )
	BEGIN
		SELECT
			b.`id`,
			b.`title`,
			b.`image_path`,
			b.`page_count`,
            ul.`current_page`,
            GROUP_CONCAT(a.`name` SEPARATOR ', ') AS `author_names`
        FROM `user_library` AS ul
        INNER JOIN `books` AS b ON b.`id` = ul.`book_id`
		LEFT JOIN `book_authors` AS ba ON b.`id` = ba.`book_id`
		LEFT JOIN `authors` AS a ON ba.`author_id` = a.`id`
        WHERE ul.`user_id` = `target_id` AND ul.`current_page` > 0 AND ul.`current_page` < b.`page_count`
        GROUP BY b.`id`;
	END //
    
DELIMITER ;

DELIMITER //

	-- Procedure 10: Get Just Added (not started, but in user library) Books for a User
    DROP PROCEDURE IF EXISTS GetJustAddedBooksForUserId //
	CREATE PROCEDURE GetJustAddedBooksForUserId(
		IN `target_id` INT
    )
	BEGIN
		SELECT
			b.`id`,
			b.`title`,
			b.`image_path`,
			b.`page_count`,
            ul.`current_page`,
            GROUP_CONCAT(a.`name` SEPARATOR ', ') AS `author_names`
        FROM `user_library` AS ul
        INNER JOIN `books` AS b ON b.`id` = ul.`book_id`
		LEFT JOIN `book_authors` AS ba ON b.`id` = ba.`book_id`
		LEFT JOIN `authors` AS a ON ba.`author_id` = a.`id`
        WHERE ul.`user_id` = `target_id` AND ul.`current_page` = 0
        GROUP BY b.`id`;
	END //
    
DELIMITER ;

DELIMITER //

	-- Function 1: Get User Id from Email, if a match isn't found it returns 0
	DROP FUNCTION IF EXISTS GetUserIdFromEmail //
    CREATE FUNCTION GetUserIdFromEmail(`search_email` VARCHAR(255))
    RETURNS INT
    DETERMINISTIC
    READS SQL DATA
    BEGIN
		RETURN IFNULL (
			(SELECT `id` FROM `users` WHERE `email` = `search_email` LIMIT 1),
			0
        );
    END //

DELIMITER ;

DELIMITER //

	-- Function 2: Get Password Hash from User Id
	DROP FUNCTION IF EXISTS GetPasswordHashFromUserId //
    CREATE FUNCTION GetPasswordHashFromUserId(`user_id` INT)
    RETURNS VARCHAR(255)
    DETERMINISTIC
    READS SQL DATA
    BEGIN
		RETURN (SELECT `password_hash` FROM `users` WHERE `id` = `user_id` LIMIT 1);
    END //

DELIMITER ;

DELIMITER //

	-- Function 3: Check if a book is in user_library
	DROP FUNCTION IF EXISTS BookInUserLibrary //
    CREATE FUNCTION BookInUserLibrary(`target_user_id` INT, `target_book_id` INT)
    RETURNS VARCHAR(255)
    DETERMINISTIC
    READS SQL DATA
    BEGIN
		RETURN (SELECT `book_id` FROM `user_library` AS ul WHERE ul.`user_id` = `target_user_id` AND ul.`book_id` = `target_book_id`);
    END //

DELIMITER ;