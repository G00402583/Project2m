<?php
session_start();

$servername = "maliqproject";
$username   = "root";
$password   = "";
$dbname     = "book_inventory_db";

// Direct DB connection 
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $u = htmlspecialchars(trim($_POST['username'] ?? ''));
    $p = htmlspecialchars(trim($_POST['password'] ?? ''));

    if (!$u || !$p) {
        echo "Error: All fields are required.";
        exit;
    }

    // Prepared statement 
    $st = $conn->prepare("SELECT user_id, username, password FROM Users WHERE username = ?");
    $st->bind_param("s", $u);
    $st->execute();
    $rs = $st->get_result();

    if ($rs->num_rows > 0) {
        $row = $rs->fetch_assoc();
        if (password_verify($p, $row['password'])) {
            $_SESSION['user_id']  = $row['user_id'];
            $_SESSION['username'] = $row['username'];
            $redirect = $_POST['redirect_to'] ?? 'bookinventory.php';
if (!preg_match('/^[a-zA-Z0-9_\-]+\.php$/', $redirect)) {
    $redirect = 'bookinventory.php';
}
header("Location: $redirect");

            exit;
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "User not found.";
    }

    $st->close();
}

$conn->close();
?>
