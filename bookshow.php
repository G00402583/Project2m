<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$theme = isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'purple';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Main and theme styles -->
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/styles_<?php echo $theme; ?>.css">
    <link rel="icon" type="image/png" href="images/favicon.png">
    <title>Filtered Book List</title>
    
</head>

<body>
    <!-- Back Button -->
<div style="padding: 20px;">
  <a href="bookinventory.php" style="
      background-color: #007bff;
      color: white;
      padding: 10px 20px;
      text-decoration: none;
      border-radius: 6px;
      font-weight: bold;
      display: inline-block;
      transition: background-color 0.3s ease;
  " onmouseover="this.style.backgroundColor='#0056b3'" onmouseout="this.style.backgroundColor='#007bff'">
    ← Back to Book Inventory
  </a>
</div>



    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $book_genre = isset($_POST['book_genres']) ? htmlspecialchars($_POST['book_genres']) : '';
    $book_author = isset($_POST['book_author']) ? htmlspecialchars($_POST['book_author']) : '';
    $price_range = isset($_POST['price_range']) ? htmlspecialchars($_POST['price_range']) : '';

    $conn = new mysqli("maliqproject", "root", "", "book_inventory_db");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $check_desc = $conn->query("SHOW COLUMNS FROM Books LIKE 'description'");
    if ($check_desc->num_rows === 0) {
        $conn->query("ALTER TABLE Books ADD COLUMN description TEXT NULL");
    }
    $check_img = $conn->query("SHOW COLUMNS FROM Books LIKE 'image_url'");
    if ($check_img->num_rows === 0) {
        $conn->query("ALTER TABLE Books ADD COLUMN image_url VARCHAR(255) NULL");
    }

    $genre_clause = $book_genre ? "b.genre = '$book_genre'" : "1=1";
    $author_clause = $book_author ? "LOWER(b.book_author) LIKE '%" . strtolower($book_author) . "%'" : "1=1";
    $price_clause = $price_range ? "m.price <= $price_range" : "1=1";

    $sql = "SELECT b.book_id, b.book_title, b.book_author, b.genre, b.description,
               IFNULL(img.image_path, 'placeholder.jpg') AS image_url, 
               m.price, m.stock_quantity
        FROM Books b
        JOIN Merchandise m ON b.book_id = m.book_id
        LEFT JOIN book_images img ON b.book_id = img.book_id
        WHERE $genre_clause AND $author_clause AND $price_clause
        ORDER BY b.book_title";


    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        echo "<table style='width:100%; border-collapse:collapse;' border='1'>
                <tr>
                    <th>Image</th>
                    <th>Book Title</th>
                    <th>Author</th>
                    <th>Genre</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Action</th>
                </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td><img src='images/booki/{$row['image_url']}' alt='{$row['book_title']}' style='width:50px; height:70px;'>
</td>
                    <td>{$row['book_title']}</td>
                    <td>{$row['book_author']}</td>
                    <td>{$row['genre']}</td>
                    <td>{$row['description']}</td>
                    <td>\${$row['price']}</td>
                    <td>{$row['stock_quantity']}</td>
                    <td><button onclick='addToCart({$row['book_id']})'>Add to Cart</button></td>
                  </tr>";
            $book_id = $row['book_id'];
            $stmt_reviews = $conn->prepare("SELECT r.review_id, r.rating, r.comment, r.review_date, u.username 
                                                  FROM Reviews r 
                                                  JOIN Users u ON r.customer_id = u.user_id 
                                                  WHERE r.book_id = ? 
                                                  ORDER BY r.review_date DESC");
            $stmt_reviews->bind_param("i", $book_id);
            $stmt_reviews->execute();
            $result_reviews = $stmt_reviews->get_result();

            echo "<tr><td colspan='8'><h4>Customer Reviews:</h4>";
            if ($result_reviews->num_rows > 0) {
                while ($review = $result_reviews->fetch_assoc()) {
                    echo "<div class='review'>
                            <p><strong>{$review['username']}</strong> ({$review['review_date']})</p>
                            <p>Rating: {$review['rating']} Stars</p>
                            <p>{$review['comment']}</p>
                          </div>";
                }
            } else {
                echo "<p>No reviews yet.</p>";
            }
            echo "<form action='submit_review.php' method='POST'>
                    <input type='hidden' name='book_id' value='{$row['book_id']}'>
                    <label for='rating'>Rating:</label>
                    <select name='rating' id='rating' required>
                        <option value='1'>1 Star</option>
                        <option value='2'>2 Stars</option>
                        <option value='3'>3 Stars</option>
                        <option value='4'>4 Stars</option>
                        <option value='5'>5 Stars</option>
                    </select><br>
                    <label for='comment'>Review:</label>
                    <textarea name='comment' id='comment' rows='5' required></textarea><br>
                    <input type='submit' value='Submit Review'>
                  </form></td></tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No books found matching your criteria.</p>";
    }
    $conn->close();
    ?>

    <!-- Link to main JavaScript functions -->
<script src="js/cart.js"></script>

</body>

</html>
