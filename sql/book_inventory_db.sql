-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 02, 2025 at 10:16 AM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `book_inventory_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `bank_details`
--

DROP TABLE IF EXISTS `bank_details`;
CREATE TABLE IF NOT EXISTS `bank_details` (
  `bank_details_id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `bank_name` varchar(100) NOT NULL,
  `account_number` varchar(50) NOT NULL,
  `routing_number` varchar(50) NOT NULL,
  PRIMARY KEY (`bank_details_id`),
  KEY `order_id` (`order_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


--
-- Dumping data for table `bank_details`
--

INSERT INTO `bank_details` (`bank_details_id`, `order_id`, `bank_name`, `account_number`, `routing_number`) VALUES
(1, 1, 'aib', '12312', '123');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

DROP TABLE IF EXISTS `books`;
CREATE TABLE IF NOT EXISTS `books` (
  `book_id` int NOT NULL AUTO_INCREMENT,
  `book_title` varchar(100) NOT NULL,
  `book_author` varchar(100) NOT NULL,
  `genre` varchar(50) NOT NULL,
  `category_id` int NOT NULL,
  `description` text,
  `image_url` varchar(255) DEFAULT 'default.jpg',
  PRIMARY KEY (`book_id`),
  KEY `category_id` (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`book_id`, `book_title`, `book_author`, `genre`, `category_id`, `description`, `image_url`) VALUES
(1, 'The Great Gatsby', 'F. Scott Fitzgerald', 'Novel', 1, 'A classic novel set in the 1920s.', 'default.jpg'),
(2, 'Pride and Prejudice', 'Jane Austen', 'Classic Romance', 1, 'A story of love and social standing in Regency-era England.', 'default.jpg'),
(3, 'Moby Dick', 'Herman Melville', 'Adventure', 1, 'The epic tale of a sea captain\'s obsession with a white whale.', 'default.jpg'),
(4, 'Crime and Punishment', 'Fyodor Dostoevsky', 'Psychological Fiction', 1, 'A complex exploration of morality and redemption.', 'default.jpg'),
(5, 'Sapiens', 'Yuval Noah Harari', 'History', 2, 'A brief history of humankind.', 'default.jpg'),
(6, 'Educated', 'Tara Westover', 'Memoir', 2, 'A memoir of resilience and self-discovery.', 'default.jpg'),
(7, 'The Immortal Life of Henrietta Lacks', 'Rebecca Skloot', 'Science', 2, 'The story of a woman whose cells changed medical history.', 'default.jpg'),
(8, 'Thinking, Fast and Slow', 'Daniel Kahneman', 'Psychology', 2, 'A groundbreaking exploration of human thought processes.', 'default.jpg'),
(9, 'A Brief History of Time', 'Stephen Hawking', 'Physics', 3, 'A journey through the universe\'s mysteries.', 'default.jpg'),
(10, 'The Selfish Gene', 'Richard Dawkins', 'Biology', 3, 'A fresh perspective on evolution.', 'default.jpg'),
(11, 'Astrophysics for People in a Hurry', 'Neil deGrasse Tyson', 'Astrophysics', 3, 'An accessible guide to the cosmos.', 'default.jpg'),
(12, 'The Elegant Universe', 'Brian Greene', 'Physics', 3, 'A deep dive into string theory and parallel universes.', 'default.jpg'),
(13, 'The Hobbit', 'J.R.R. Tolkien', 'Adventure Fantasy', 4, 'A quest to recover a lost treasure guarded by a dragon.', 'default.jpg'),
(14, 'Harry Potter and the Philosopher\'s Stone', 'J.K. Rowling', 'Fantasy', 4, 'The story of a young wizard discovering his destiny.', 'default.jpg'),
(15, 'American Gods', 'Neil Gaiman', 'Urban Fantasy', 4, 'A battle between old and new gods.', 'default.jpg'),
(16, 'The Name of the Wind', 'Patrick Rothfuss', 'Epic Fantasy', 4, 'The tale of a legendary musician and magician.', 'default.jpg'),
(17, 'The Notebook', 'Nicholas Sparks', 'Contemporary Romance', 5, 'A timeless love story spanning decades.', 'default.jpg'),
(18, 'Outlander', 'Diana Gabaldon', 'Historical Romance', 5, 'A nurse time-travels to 18th-century Scotland.', 'default.jpg'),
(19, 'Me Before You', 'Jojo Moyes', 'Modern Romance', 5, 'An emotional story of an unexpected bond.', 'default.jpg'),
(20, 'The Time Traveler\'s Wife', 'Audrey Niffenegger', 'Sci-Fi Romance', 5, 'A love story that defies time itself.', 'default.jpg'),
(21, 'The Girl with the Dragon Tattoo', 'Stieg Larsson', 'Mystery', 6, 'A gripping mystery surrounding a missing woman.', 'default.jpg'),
(22, 'Gone Girl', 'Gillian Flynn', 'Thriller', 6, 'A psychological thriller involving a troubled marriage.', 'default.jpg'),
(23, 'Big Little Lies', 'Liane Moriarty', 'Mystery', 6, 'A dark comedy that uncovers secrets in a suburban town.', 'default.jpg'),
(24, 'The Silent Patient', 'Alex Michaelides', 'Thriller', 6, 'A psychological thriller about a woman who shot her husband.', 'default.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `category_id` int NOT NULL AUTO_INCREMENT,
  `category_name` varchar(50) NOT NULL,
  PRIMARY KEY (`category_id`),
  UNIQUE KEY `category_name` (`category_name`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`) VALUES
(1, 'Fiction'),
(2, 'Non-Fiction'),
(3, 'Science'),
(4, 'Fantasy'),
(5, 'Romance');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
CREATE TABLE IF NOT EXISTS `customers` (
  `customer_id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `address` text NOT NULL,
  PRIMARY KEY (`customer_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customer_id`, `first_name`, `last_name`, `email`, `phone_number`, `address`) VALUES
(1, 'John', 'Doe', 'john.doe@example.com', '1234567890', '123 Elm Street'),
(2, 'Jane', 'Smith', 'jane.smith@example.com', '0987654321', '456 Oak Avenue');

-- --------------------------------------------------------

--
-- Table structure for table `merchandise`
--

DROP TABLE IF EXISTS `merchandise`;
CREATE TABLE IF NOT EXISTS `merchandise` (
  `merchandise_id` int NOT NULL AUTO_INCREMENT,
  `book_id` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock_quantity` int NOT NULL,
  PRIMARY KEY (`merchandise_id`),
  KEY `book_id` (`book_id`)
) ;

--
-- Dumping data for table `merchandise`
--

INSERT INTO `merchandise` (`merchandise_id`, `book_id`, `price`, `stock_quantity`) VALUES
(1, 1, 15.99, 50),
(2, 2, 12.99, 70),
(3, 3, 10.99, 30),
(4, 4, 14.99, 44),
(5, 5, 13.99, 60),
(6, 6, 11.99, 40),
(7, 7, 9.99, 35),
(8, 8, 19.99, 55),
(9, 9, 18.99, 60),
(10, 10, 22.99, 50),
(11, 11, 17.99, 45),
(12, 12, 20.99, 50),
(13, 13, 16.99, 40),
(14, 14, 21.99, 65),
(15, 15, 23.99, 70),
(16, 16, 24.99, 40),
(17, 17, 25.99, 60),
(18, 18, 26.99, 55),
(19, 19, 27.99, 45),
(20, 20, 28.99, 50);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `order_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `order_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `total_amount` decimal(10,2) NOT NULL,
  `order_status` varchar(20) DEFAULT 'pending',
  PRIMARY KEY (`order_id`),
  KEY `user_id` (`user_id`)
) ;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `order_date`, `total_amount`, `order_status`) VALUES
(1, 3, '2025-04-02 09:23:54', 14.99, 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE IF NOT EXISTS `order_items` (
  `order_item_id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `book_id` int NOT NULL,
  `quantity` int NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  PRIMARY KEY (`order_item_id`),
  KEY `order_id` (`order_id`),
  KEY `book_id` (`book_id`)
) ;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `book_id`, `quantity`, `subtotal`) VALUES
(1, 1, 4, 1, 14.99);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
CREATE TABLE IF NOT EXISTS `reviews` (
  `review_id` int NOT NULL AUTO_INCREMENT,
  `book_id` int NOT NULL,
  `customer_id` int NOT NULL,
  `rating` int DEFAULT NULL,
  `comment` text,
  `review_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`review_id`),
  KEY `book_id` (`book_id`),
  KEY `customer_id` (`customer_id`)
) ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'admin', 'admin@example.com', '$2y$10$bn6bEHgJ8fJ707.LvHqrm.7bipX0en0HzTUgYpz7szhDSdXER7PI6', '2025-04-02 09:10:06'),
(2, 'johndoe', 'johndoe@example.com', '$2y$10$7BEmWbn.B61LveghRmmipO7pyb826dcSwVAetkqU99hHupa7n8zVm', '2025-04-02 09:10:06'),
(3, '123', '123343244@gmail.com', '$2y$10$S3iMZFkTHTcHx/.pN6YjXextP.95qfz2IBa2xjnsP9AUzRevBl6n2', '2025-04-02 09:20:29');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


-- Step 1: Create avatars table
CREATE TABLE IF NOT EXISTS avatars (
    id INT AUTO_INCREMENT PRIMARY KEY,
    image_path VARCHAR(255) NOT NULL
);

-- Step 2: Insert your actual image file names
INSERT INTO avatars (image_path) VALUES
('img1.jpg'),
('img2.jpg'),
('img3.jpg'),
('img4.jpg'),
('img5.jpg');


-- Create table for book images
CREATE TABLE IF NOT EXISTS book_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    book_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL
);


INSERT INTO book_images (book_id, image_path) VALUES
(1, 'book1.jpg'),
(2, 'book2.jpg'),
(3, 'book3.jpg'),
(4, 'book4.jpg'),
(5, 'book5.jpg'),
(6, 'book6.jpg'),
(7, 'book7.jpg'),
(8, 'book8.jpg'),
(9, 'book9.jpg'),
(10, 'book10.jpg'),
(11, 'book11.jpg'),
(12, 'book12.jpg'),
(13, 'book13.jpg'),
(14, 'book14.jpg'),
(15, 'book15.jpg'),
(16, 'book16.jpg'),
(17, 'book17.jpg'),
(18, 'book18.jpg'),
(19, 'book19.jpg'),
(20, 'book20.jpg'),
(21, 'book21.jpg'),
(22, 'book22.jpg'),
(23, 'book23.jpg'),
(24, 'book24.jpg');


CREATE TABLE Static_Images (
    image_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    description TEXT
);

INSERT INTO Static_Images (name, file_path, description) VALUES
('bookinv.jpg', 'images/bookinv.jpg', 'Main banner image for Book Inventory'),
('cart_icon.png', 'images/cart_icon.png', 'Shopping cart icon image');
