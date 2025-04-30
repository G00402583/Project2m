<?php


if (isset($_GET['avatar'])) {
    // Prevent path injection
    $avatarPath = basename($_GET['avatar']);

    // Check if file exists 
    if (file_exists("images/avatars/" . $avatarPath)) {
        setcookie('selected_avatar', $avatarPath, time() + (30 * 24 * 60 * 60), "/");

        // Output updated avatar img
        echo "<img src='images/avatars/{$avatarPath}' style='width:50px; height:50px; cursor:pointer;' onclick='openAvatarModal()'>";
    } else {
        echo "Avatar not found.";
    }
} else {
    echo "No avatar selected.";
}
?>
