USE `biblioteca_database`;

-- TABLE: COLLECTIONS
INSERT INTO `collections` (`id`, `name`) VALUES
(1, 'Fiction'),
(2, 'Non-Fiction'),
(3, 'Young Adult & Children');

-- TABLE: CATEGORIES
INSERT INTO `categories` (`id`, `name`, `collection_id`) VALUES
(1, 'Fantasy & Magic', 1),
(2, 'Science Fiction', 1),
(3, 'Mystery & Thriller', 1),
(4, 'Romance', 1),
(5, 'Horror', 1),
(6, 'Historical Fiction', 1),
(7, 'Biography & Memoir', 2),
(8, 'Self-help & Psychology', 2),
(9, 'Business & Finance', 2),
(10, 'History & Politics', 2),
(11, 'Science & Nature', 2),
(12, 'Arts & Crafts', 2),
(13, 'Teenage Fiction & True Stories', 3),
(14, 'Coming of Age', 3),
(15, 'Children\'s Picture Books', 3),
(16, 'Middle Grade Adventures', 3),
(17, 'Educational Material', 3);

INSERT INTO `collections` (`id`, `name`) VALUES
(4, 'Manga & Comics');

INSERT INTO `categories` (`id`, `name`, `collection_id`) VALUES
(18, 'Comics', 4),
(19, 'Action', 4),
(20, 'Comedy', 4),
(21, 'Horror', 4),
(22, 'Fantasy', 4),
(23, 'Romance', 4);

-- TABLE: LANGUAGES
INSERT INTO `languages` (`id`, `language`) VALUES
(1, 'English'),
(2, 'German'),
(3, 'Spanish'),
(4, 'French'),
(5, 'Italian');