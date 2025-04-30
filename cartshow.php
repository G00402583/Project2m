<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect_to=cartshow.php");
    exit;
}
$theme = isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'purple';
if (!isset($_SESSION['cart'])) { $_SESSION['cart'] = []; }

$conn = new mysqli("maliqproject", "root", "", "book_inventory_db");
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

if (isset($_POST['book_id'])) {
    $book_id = intval($_POST['book_id']);
    $quantity = 1;
    if (isset($_SESSION['cart'][$book_id])) {
        $_SESSION['cart'][$book_id]['quantity'] += $quantity;
    } else {
        $stmt = $conn->prepare("SELECT b.book_id, b.book_title, m.price FROM Books b JOIN Merchandise m ON b.book_id = m.book_id WHERE b.book_id = ?");
        $stmt->bind_param("i", $book_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result && $result->num_rows > 0) {
            $book = $result->fetch_assoc();
            $_SESSION['cart'][$book_id] = [
                'title' => $book['book_title'],
                'price' => $book['price'],
                'quantity' => $quantity
            ];
        }
        $stmt->close();
    }
}
if (isset($_POST['remove_one_item'])) {
    $book_id = intval($_POST['remove_one_item']);
    if (isset($_SESSION['cart'][$book_id])) {
        $_SESSION['cart'][$book_id]['quantity'] -= 1;
        if ($_SESSION['cart'][$book_id]['quantity'] <= 0) {
            unset($_SESSION['cart'][$book_id]);
        }
    }
}
if (isset($_POST['remove_item'])) {
    $book_id = intval($_POST['remove_item']);
    if (isset($_SESSION['cart'][$book_id])) {
        unset($_SESSION['cart'][$book_id]);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/styles_<?php echo $theme; ?>.css">
    <link rel="icon" type="image/png" href="images/favicon.png">
    <title>Cart</title>
</head>
<body>


<header style="display:flex; justify-content:space-between; align-items:center; padding:20px; background:#333;">
<a href="bookinventory.php" style="color:black; font-size:24px; font-weight:bold; padding:10px 20px; background:#007bff; border-radius:5px; text-decoration:none;">My Book Store</a>
    
<nav>
            <ul style="list-style:none; display:flex; gap:20px; align-items:center;">
                <!-- Cart icon with count & mini-cart hover -->
                
                <li>
    <a href="bookshow.php" style="color:white; text-decoration:none; padding:8px 12px; background:#007bff; border-radius:5px;">Book List</a>
</li>
<li>
    <a href="userform.php" style="color:white; text-decoration:none; padding:8px 12px; background:#007bff; border-radius:5px;">Log in / Sign up</a>
</li>
            </ul>
        </nav>

        <div>
            <span id="avatarDisplay">
                <?php
                $avatar = isset($_COOKIE['selected_avatar']) ? $_COOKIE['selected_avatar'] : 'img1.jpg';
                echo "<img src='images/avatars/{$avatar}' style='width:50px; height:50px; cursor:pointer;' onclick='openAvatarModal()'>";
                
                ?>
            </span>
            <button onclick="openThemeModal()" style="margin-left:10px;">Change Theme</button>
        </div>





</header>

    <?php
    if (!empty($_SESSION['cart'])) {
        echo "<table style='width:100%; border-collapse:collapse; text-align:center;'>
                <tr>
                    <th>Title</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Actions</th>
                </tr>";
        $total = 0;
        foreach ($_SESSION['cart'] as $id => $item) {
            $subtotal = $item['price'] * $item['quantity'];
            $total += $subtotal;
            echo "<tr>
                    <td>{$item['title']}</td>
                    <td>\${$item['price']}</td>
                    <td>{$item['quantity']}</td>
                    <td>\${$subtotal}</td>
                    <td>
                        <form method='POST' style='display:inline;'>
                            <input type='hidden' name='remove_one_item' value='{$id}'>
                            <button type='submit' style='background-color:#ff9800; color:white; border:none; padding:5px; cursor:pointer;'>-1</button>
                        </form>
                        <form method='POST' style='display:inline;'>
                            <input type='hidden' name='remove_item' value='{$id}'>
                            <button type='submit' style='background-color:#dc3545; color:white; border:none; padding:5px; cursor:pointer;'>Remove</button>
                        </form>
                    </td>
                  </tr>";
        }
        echo "<tr>
                <td colspan='3'><b>Total</b></td>
                <td colspan='2'><b>\${$total}</b></td>
              </tr>";
        echo "</table>";
        echo "<div style='margin-top:20px; text-align:center;'>
                <form action='checkoutshow.php' method='POST' style='display:inline-block;'>
                    <button type='submit' style='background-color:#007bff; color:white; border:none; padding:10px 20px; cursor:pointer;'>Proceed to Checkout</button>
                </form>
                <a href='bookinventory.php' style='text-decoration:none;'>
                    <button type='button' style='background-color:#28a745; color:white; border:none; padding:10px 20px; cursor:pointer;'>Back to Inventory</button>
                </a>
              </div>";
    } else {
        echo "<p style='text-align:center; margin-top:20px;'>Your cart is empty.</p>";
    }
    $conn->close();
    ?>

    

    <!-- Link to main JavaScript functions -->
<script src="js/main.js"></script>




<!-- Theme Modal -->
<div id="modalBackdrop" onclick="closeThemeModal()" style="display:none; position:fixed; left:0; top:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:5;"></div>

<div id="themeModal" class="modal" style="display:none; position:fixed; z-index:10; left:50%; top:50%; transform:translate(-50%, -50%); background:#fff; padding:20px; border-radius:5px;">
  <h3>Select Theme</h3>
  <button onclick="changeTheme('purple')">Purple</button>
  <button onclick="changeTheme('blue')">Blue</button>
  <button onclick="changeTheme('green')">Green</button>
  <br><br>
  <button onclick="closeThemeModal()">Close</button>
</div>



</body>
</html>
