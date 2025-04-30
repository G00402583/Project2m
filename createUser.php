

<link rel="icon" type="image/png" href="images/favicon.png">
<?php
$servername = "maliqproject";
$username   = "root";
$password   = "";
$dbname     = "book_inventory_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user = htmlspecialchars(trim($_POST['username'] ?? ''));
    $em   = htmlspecialchars(trim($_POST['email'] ?? ''));
    $pw   = htmlspecialchars(trim($_POST['password'] ?? ''));

    if (!$user || !$em || !$pw) {
        echo "Error: All fields are required.";
        exit;
    }

    // check if user or email exist
    $st = $conn->prepare("SELECT user_id FROM Users WHERE username=? OR email=?");
    $st->bind_param("ss", $user, $em);
    $st->execute();
    $rs = $st->get_result();
    if ($rs->num_rows > 0) {
        echo "Error: Username or email already exists.";
        $st->close();
        exit;
    }
    $st->close();

    // insert user
    $hashed = password_hash($pw, PASSWORD_DEFAULT);
    $in = $conn->prepare("INSERT INTO Users (username, email, password) VALUES (?, ?, ?)");
    $in->bind_param("sss", $user, $em, $hashed);
    if ($in->execute()) {
        echo "User created successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
    $in->close();
}
$conn->close();
?>
