<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['theme'])) {
    $theme = htmlspecialchars(trim($_POST['theme']));
    setcookie('theme', $theme, time() + (70 * 24 * 60 * 60), "/");
    echo "Theme set to $theme";
} else {
    echo "Invalid request.";
}
?>