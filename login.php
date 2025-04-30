<?php
session_start();
if (isset($_SESSION['user_id'])) {
    $redirect = $_GET['redirect_to'] ?? 'bookInventory.php';
    header("Location: $redirect");
    exit;
}
$theme = isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'purple';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - Book Inventory</title>
  <link rel="icon" type="image/png" href="images/favicon.png">
  <link rel="stylesheet" href="css/styles.css">
  <link rel="stylesheet" href="css/styles_<?php echo $theme; ?>.css">
  <link rel="icon" type="image/png" href="images/favicon.png">
  <script src="js/utils.js" defer></script>
  
</head>
<body onload="showLoginModal()">

<!-- Modal container and backdrop -->
<div id="backdrop" onclick="closeModal()"></div>
<div id="login_modal"></div>

<script>
function togglePassword() {
  const pass = document.getElementById("password");
  pass.type = pass.type === "password" ? "text" : "password";
}
</script>



</body>
</html>
