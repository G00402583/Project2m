<?php
// Connect to database
$conn = new mysqli("maliqproject", "root", "", "book_inventory_db");

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit;
}

// Fetch avatar image paths from the avatars table
$sql = "SELECT id, image_path FROM avatars";
$result = $conn->query($sql);

$images = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $images[] = $row;
    }
    header('Content-Type: application/json');
    echo json_encode($images);
} else {
    http_response_code(404);
    echo json_encode(["error" => "No avatars found"]);
}

$conn->close();
?>
