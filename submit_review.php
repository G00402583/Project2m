<link rel="icon" type="image/png" href="images/favicon.png">
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$conn = new mysqli("maliqproject", "root", "", "book_inventory_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$book_id = $_POST['book_id'];
$rating = $_POST['rating'];
$comment = htmlspecialchars(trim($_POST['comment']));
$user_id = $_SESSION['user_id'];
if (empty($rating) || empty($comment)) {
    echo "All fields are required.";
    exit;
}
$sql = "INSERT INTO Reviews (book_id, customer_id, rating, comment) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiis", $book_id, $user_id, $rating, $comment);
if ($stmt->execute()) {
    echo "Review submitted successfully!";
} else {
    echo "Error: " . $conn->error;
}
$stmt->close();
$conn->close();
?>