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
    <title>Book Inventory</title>
    <!-- Main and theme-specific CSS -->
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/styles_<?php echo $theme; ?>.css">
    <link rel="stylesheet" href="css/bookinventory.css">
    <link rel="icon" type="image/png" href="images/favicon.png">
    <!-- JS files -->
    <script src="js/formExtra.js"></script>
    <script src="js/cart.js"></script>
    
</head>
<body>
    <!-- Backdrop -->
    <div id="modalBackdrop" onclick="closeThemeModal(); closeAvatarModal(); closeUserFormModal(); closeCheckoutModal();"></div>

    <!-- Theme Modal -->
    <div id="themeModal" class="modal">
        <h3>Select Theme</h3>
        <button onclick="changeTheme('purple')">Purple</button>
        <button onclick="changeTheme('blue')">Blue</button>
        <button onclick="changeTheme('green')">Green</button>
        <br><br>
        <button onclick="closeThemeModal()">Close</button>
    </div>

    <!-- Avatar Modal -->
    <div id="avatarModal" class="modal">
  <h3>Select Avatar</h3>
  <div id="avatarGrid" class="image-grid"></div>
  <br><br>
  <button onclick="closeAvatarModal()">Close</button>
</div>


    <!-- Extra Form Modal for Creating User (userForm) -->
    <div id="userFormModal" class="modal">
        <h3>Create a New Account</h3>
        <!-- user form  -->
        <form id="userFormModalForm">
            <label>Username:</label>
            <input type="text" id="modal_username" name="username" required><br>
            <label>Email:</label>
            <input type="email" id="modal_email" name="email" required><br>
            <label>Password:</label>
            <input type="password" id="modal_password" name="password" required><br>
            <label>Phone Number:</label>
            <input type="text" id="modal_phone" name="phone_number" required><br>
            <label>Address:</label>
            <textarea id="modal_address" name="address" required></textarea><br>
            <button type="button" onclick="submitUserFormModal()">Create User</button>
            <button type="button" onclick="closeUserFormModal()">Cancel</button>
        </form>
        <div id="modalUserResponse" style="color:green; margin-top:10px;"></div>
    </div>

   <!-- Extra Form Modal for Checkout -->
<div id="checkoutModal" class="modal">
  <h3>Checkout</h3>
  <form id="checkoutModalForm" onsubmit="submitCheckoutModal(); return false;">
    <label>Billing Address:</label>
    <input type="text" id="modal_billing_address" name="billing_address" required><br>

    <label>Shipping Address:</label>
    <input type="text" id="modal_shipping_address" name="shipping_address" required><br>

    <input type="checkbox" id="modal_same_as_billing" onclick="copyBillingModal()"> Same as Billing<br>

    <label>Payment Method:</label>
    <select id="payment_method" name="payment_method" onchange="toggleBankDetailsMain()" required>
      <option value="">Select Payment Method</option>
      <option value="credit_card">Credit Card</option>
      <option value="bank_transfer">Bank Transfer</option>
    </select><br>

    <!-- Credit Card Section -->
<div id="card_section" style="display: none;">
  <label for="card_number">Card Number:</label>
  <input type="text" id="card_number" name="card_number" maxlength="16" pattern="\d{16}"><br>

  <label for="card_expiry">Expiry Date (MM/YY):</label>
  <input type="text" id="card_expiry" name="card_expiry" pattern="^(0[1-9]|1[0-2])\/\d{2}$"><br>

  <label for="card_cvc">CVC:</label>
  <input type="text" id="card_cvc" name="card_cvc" maxlength="3" pattern="\d{3}"><br>
</div>


    <!-- Bank Transfer Section -->
    <div id="bank_section" style="display:none;">
      <label>Bank Name:</label>
      <input type="text" name="bank_name"><br>

      <label>Account Number:</label>
      <input type="text" name="account_number"><br>

      <label>Routing Number:</label>
      <input type="text" name="routing_number"><br>
    </div>

    <button type="submit">Complete Order</button>
    <button type="button" onclick="closeCheckoutModal()">Cancel</button>
  </form>
  <div id="modalCheckoutResponse" style="margin-top: 10px;"></div>
</div>





    <!-- Header -->
    <header style="display:flex; justify-content:space-between; align-items:center; padding:20px; background:#333;">
    <a href="bookinventory.php" style="color:black; font-size:24px; font-weight:bold; padding:10px 20px; background:#007bff; border-radius:5px; text-decoration:none;">My Book Store</a></div>
        <nav>
            <ul style="list-style:none; display:flex; gap:20px; align-items:center;">
                <!-- Cart icon with count & mini-cart hover -->
                <li style="position:relative;">
  <div id="cartIcon" style="background:transparent; padding:0; margin:0; border:none; box-shadow:none;">
  <?php
$conn = new mysqli("maliqproject", "root", "", "book_inventory_db");
$result = $conn->query("SELECT file_path FROM Static_Images WHERE name = 'cart_icon.png' LIMIT 1");
$row = $result->fetch_assoc();
$bookImage = $row ? $row['file_path'] : 'images/cart_icon.png'; // fallback
?>

<img src="<?php echo $bookImage; ?>" alt="Cart Icon" style="width: 60px; height: auto;">




    <span id="cartCount" style="color:white; position:absolute; top:0; right:0; font-weight:bold;">0</span>
    <div id="miniCartHover"></div>
  </div>
</li>
 
    <a href="#" onclick="openCheckoutModal(); return false;" 
       style="color:white; text-decoration:none; padding:6px 10px; border:2px solid white; border-radius:4px; transition:0.3s;">
       Checkout
    </a>
</li>
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
            <button onclick="openThemeModal()" style="margin-left:10px; padding:5px;">Change Theme</button>
        </div>
    </header>

    <div class="main-container">
        <center>
        <?php
$conn = new mysqli("maliqproject", "root", "", "book_inventory_db");
$result = $conn->query("SELECT file_path FROM Static_Images WHERE name = 'bookinv.jpg' LIMIT 1");
$row = $result->fetch_assoc();
$bookImage = $row ? $row['file_path'] : 'images/bookinv.jpg'; // fallback
?>

<img src="<?php echo $bookImage; ?>" width="500px" alt="Book Inventory">

            <h2>Book Inventory</h2>
        </center>
        <div id="mini_cart"></div>

        <!-- Form for filtering books -->
        <form id="book_form">
            <div>
                <label for="book_genres">Select a Genre:</label>
                <select name="book_genres" id="book_genres" onchange="updateBookList()">
                    <option value="" disabled selected>Select a Genre</option>
                    <option value="">All</option>
                    <?php
                    $conn = new mysqli("maliqproject", "root", "", "book_inventory_db");
                    if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }
                    $sql_genres = "SELECT DISTINCT genre FROM Books ORDER BY genre";
                    $result_genres = $conn->query($sql_genres);
                    if ($result_genres->num_rows > 0) {
                        while ($row = $result_genres->fetch_assoc()) {
                            echo "<option value=\"" . $row["genre"] . "\">" . $row["genre"] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div>
                <label for="book_author">Enter Author Name:</label>
                <input type="text" id="book_author" name="book_author" placeholder="Enter Author Name" onkeyup="updateBookList()">
            </div>
        </form>

        <div id="book_response"></div>
        <a href="logout.php" 
   style="color:#ff4d4d; background-color:#1a1a1a; padding:6px 12px; border-radius:4px; text-decoration:none; font-weight:bold;">
   Logout
</a>

    </div>

   

<script src="js/main.js"></script>

</body>
</html>