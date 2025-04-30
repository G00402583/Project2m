<?php
$servername = "maliqproject";
$username = "root";
$password = "";
$dbname = "book_inventory_db";

// Create connection
$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Drop and recreate the database (for setup purposes)
$sql = "DROP DATABASE IF EXISTS $dbname";
$conn->query($sql);
$sql = "CREATE DATABASE $dbname";
$conn->query($sql);

// Select the new database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create Categories table
$sql = "CREATE TABLE IF NOT EXISTS Categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(50) NOT NULL UNIQUE
)";
$conn->query($sql);

// Create Books table
$sql = "CREATE TABLE IF NOT EXISTS Books (
    book_id INT AUTO_INCREMENT PRIMARY KEY,
    book_title VARCHAR(100) NOT NULL,
    book_author VARCHAR(100) NOT NULL,
    genre VARCHAR(50) NOT NULL,
    category_id INT NOT NULL,
    description TEXT,
    image_url VARCHAR(255) DEFAULT 'default.jpg',
    FOREIGN KEY (category_id) REFERENCES Categories(category_id) ON DELETE CASCADE
)";
$conn->query($sql);

// Create Merchandise table
$sql = "CREATE TABLE IF NOT EXISTS Merchandise (
    merchandise_id INT AUTO_INCREMENT PRIMARY KEY,
    book_id INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL CHECK (price > 0),
    stock_quantity INT NOT NULL CHECK (stock_quantity >= 0),
    FOREIGN KEY (book_id) REFERENCES Books(book_id) ON DELETE CASCADE
)";
$conn->query($sql);

// Create Customers table
$sql = "CREATE TABLE IF NOT EXISTS Customers (
    customer_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone_number VARCHAR(15) NOT NULL,
    address TEXT NOT NULL
)";
$conn->query($sql);

// Create Orders table
$sql = "CREATE TABLE IF NOT EXISTS Orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total_amount DECIMAL(10, 2) NOT NULL CHECK (total_amount > 0),
    order_status VARCHAR(20) DEFAULT 'pending',
    FOREIGN KEY (user_id) REFERENCES Customers(customer_id) ON DELETE CASCADE
)";
$conn->query($sql);

// Create Order_Items table
$sql = "CREATE TABLE IF NOT EXISTS Order_Items (
    order_item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    book_id INT NOT NULL,
    quantity INT NOT NULL CHECK (quantity > 0),
    subtotal DECIMAL(10, 2) NOT NULL CHECK (subtotal > 0),
    FOREIGN KEY (order_id) REFERENCES Orders(order_id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES Books(book_id) ON DELETE CASCADE
)";
$conn->query($sql);

// Create Reviews table
$sql = "CREATE TABLE IF NOT EXISTS Reviews (
    review_id INT AUTO_INCREMENT PRIMARY KEY,
    book_id INT NOT NULL,
    customer_id INT NOT NULL,
    rating INT CHECK (rating >= 1 AND rating <= 5),
    comment TEXT,
    review_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (book_id) REFERENCES Books(book_id) ON DELETE CASCADE,
    FOREIGN KEY (customer_id) REFERENCES Customers(customer_id) ON DELETE CASCADE
)";
$conn->query($sql);

// Create Users table
$sql = "CREATE TABLE IF NOT EXISTS Users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($sql);

// Create Bank_Details table
$sql = "CREATE TABLE IF NOT EXISTS Bank_Details (
    bank_details_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    bank_name VARCHAR(100) NOT NULL,
    account_number VARCHAR(50) NOT NULL,
    routing_number VARCHAR(50) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES Orders(order_id) ON DELETE CASCADE
)";
$conn->query($sql);

// Insert sample data for Categories
$sql = "INSERT INTO Categories (category_name) VALUES  
('Fiction'), ('Non-Fiction'), ('Science'), ('Fantasy'), ('Romance')";
$conn->query($sql);

// Insert sample data for Books
$sql = "INSERT INTO Books (book_title, book_author, genre, category_id, description, image_url) VALUES
('The Great Gatsby', 'F. Scott Fitzgerald', 'Novel', 1, 'A classic novel set in the 1920s.', 'default.jpg'),
('Pride and Prejudice', 'Jane Austen', 'Classic Romance', 1, 'A story of love and social standing in Regency-era England.', 'default.jpg'),
('Moby Dick', 'Herman Melville', 'Adventure', 1, 'The epic tale of a sea captain''s obsession with a white whale.', 'default.jpg'),
('Crime and Punishment', 'Fyodor Dostoevsky', 'Psychological Fiction', 1, 'A complex exploration of morality and redemption.', 'default.jpg'),
('Sapiens', 'Yuval Noah Harari', 'History', 2, 'A brief history of humankind.', 'default.jpg'),
('Educated', 'Tara Westover', 'Memoir', 2, 'A memoir of resilience and self-discovery.', 'default.jpg'),
('The Immortal Life of Henrietta Lacks', 'Rebecca Skloot', 'Science', 2, 'The story of a woman whose cells changed medical history.', 'default.jpg'),
('Thinking, Fast and Slow', 'Daniel Kahneman', 'Psychology', 2, 'A groundbreaking exploration of human thought processes.', 'default.jpg'),
('A Brief History of Time', 'Stephen Hawking', 'Physics', 3, 'A journey through the universe''s mysteries.', 'default.jpg'),
('The Selfish Gene', 'Richard Dawkins', 'Biology', 3, 'A fresh perspective on evolution.', 'default.jpg'),
('Astrophysics for People in a Hurry', 'Neil deGrasse Tyson', 'Astrophysics', 3, 'An accessible guide to the cosmos.', 'default.jpg'),
('The Elegant Universe', 'Brian Greene', 'Physics', 3, 'A deep dive into string theory and parallel universes.', 'default.jpg'),
('The Hobbit', 'J.R.R. Tolkien', 'Adventure Fantasy', 4, 'A quest to recover a lost treasure guarded by a dragon.', 'default.jpg'),
('Harry Potter and the Philosopher\'s Stone', 'J.K. Rowling', 'Fantasy', 4, 'The story of a young wizard discovering his destiny.', 'default.jpg'),
('American Gods', 'Neil Gaiman', 'Urban Fantasy', 4, 'A battle between old and new gods.', 'default.jpg'),
('The Name of the Wind', 'Patrick Rothfuss', 'Epic Fantasy', 4, 'The tale of a legendary musician and magician.', 'default.jpg'),
('The Notebook', 'Nicholas Sparks', 'Contemporary Romance', 5, 'A timeless love story spanning decades.', 'default.jpg'),
('Outlander', 'Diana Gabaldon', 'Historical Romance', 5, 'A nurse time-travels to 18th-century Scotland.', 'default.jpg'),
('Me Before You', 'Jojo Moyes', 'Modern Romance', 5, 'An emotional story of an unexpected bond.', 'default.jpg'),
('The Time Traveler\'s Wife', 'Audrey Niffenegger', 'Sci-Fi Romance', 5, 'A love story that defies time itself.', 'default.jpg'),
('The Girl with the Dragon Tattoo', 'Stieg Larsson', 'Mystery', 6, 'A gripping mystery surrounding a missing woman.', 'default.jpg'),
('Gone Girl', 'Gillian Flynn', 'Thriller', 6, 'A psychological thriller involving a troubled marriage.', 'default.jpg'),
('Big Little Lies', 'Liane Moriarty', 'Mystery', 6, 'A dark comedy that uncovers secrets in a suburban town.', 'default.jpg'),
('The Silent Patient', 'Alex Michaelides', 'Thriller', 6, 'A psychological thriller about a woman who shot her husband.', 'default.jpg')";
$conn->query($sql);

// Insert sample data for Merchandise
$sql = "INSERT INTO Merchandise (book_id, price, stock_quantity) VALUES 
(1, 15.99, 50),
(2, 12.99, 70),
(3, 10.99, 30),
(4, 14.99, 45),
(5, 13.99, 60),
(6, 11.99, 40),
(7, 9.99, 35),
(8, 19.99, 55),
(9, 18.99, 60),
(10, 22.99, 50),
(11, 17.99, 45),
(12, 20.99, 50),
(13, 16.99, 40),
(14, 21.99, 65),
(15, 23.99, 70),
(16, 24.99, 40),
(17, 25.99, 60),
(18, 26.99, 55),
(19, 27.99, 45),
(20, 28.99, 50)";
$conn->query($sql);

// Insert sample data for Customers
$sql = "INSERT INTO Customers (first_name, last_name, email, phone_number, address) VALUES 
('John', 'Doe', 'john.doe@example.com', '1234567890', '123 Elm Street'),
('Jane', 'Smith', 'jane.smith@example.com', '0987654321', '456 Oak Avenue')";
$conn->query($sql);

// Insert sample data for Users with hashed passwords
$sql = "INSERT INTO Users (username, email, password) VALUES 
('admin', 'admin@example.com', '" . password_hash('admin123', PASSWORD_DEFAULT) . "'),
('johndoe', 'johndoe@example.com', '" . password_hash('password', PASSWORD_DEFAULT) . "')";
$conn->query($sql);

echo "Database setup complete with all tables and sample data.";
$conn->close();
?>


