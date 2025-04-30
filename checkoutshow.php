
<?php
session_start();

// Must be logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect_to=checkoutshow.php");
    exit;
}
$theme = isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'purple';

$conn = new mysqli("maliqproject", "root", "", "book_inventory_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Cart check
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty. <a href='bookInventory.php'>Go Shopping</a></p>";
    exit;
}

// Verify stock
foreach ($_SESSION['cart'] as $id => $it) {
    $st = $conn->prepare("SELECT stock_quantity FROM Merchandise WHERE book_id = ?");
    $st->bind_param("i", $id);
    $st->execute();
    $r = $st->get_result();
    if ($r && $r->num_rows > 0) {
        $rw = $r->fetch_assoc();
        if ($rw['stock_quantity'] < $it['quantity']) {
            // Instead of "Insufficient stock for '{$it['title']}'..."
            // string concatenation to avoid parse errors
            echo "<p>Insufficient stock for '" . htmlspecialchars($it['title']) . 
                 "'. Please update cart.</p>";
            echo "<a href='cartshow.php'>Return to Cart</a>";
            exit;
        }
    } else {
        // Similarly here:
        echo "<p>Book not found: '" . htmlspecialchars($it['title']) . "'</p>";
        echo "<a href='cartshow.php'>Return to Cart</a>";
        exit;
    }
    $st->close();
}

// Sum total
$total = 0;
foreach ($_SESSION['cart'] as $c) {
    $total += $c['price'] * $c['quantity'];
}

$orderPlaced = false;

// If user submitted the form
if ($_SERVER["REQUEST_METHOD"] === "POST" 
    && isset($_POST['billing_address'], $_POST['shipping_address'], $_POST['payment_method'])) {

    $uid  = $_SESSION['user_id'];
    $bill = htmlspecialchars(trim($_POST['billing_address']));
    $ship = htmlspecialchars(trim($_POST['shipping_address']));
    $pm   = htmlspecialchars(trim($_POST['payment_method']));

    // Insert into Orders
    $o = $conn->prepare("INSERT INTO Orders (user_id, total_amount, order_status) VALUES (?, ?, 'pending')");
    $o->bind_param("id", $uid, $total);
    if ($o->execute()) {
        $order_id = $o->insert_id;
        $o->close();

        // If bank transfer, store bank details
        if ($pm === 'bank_transfer' 
            && isset($_POST['bank_name'], $_POST['account_number'], $_POST['routing_number'])) {
            $bn  = htmlspecialchars(trim($_POST['bank_name']));
            $acc = htmlspecialchars(trim($_POST['account_number']));
            $rno = htmlspecialchars(trim($_POST['routing_number']));

            $bq = $conn->prepare("INSERT INTO Bank_Details (order_id, bank_name, account_number, routing_number)
                                  VALUES (?, ?, ?, ?)");
            $bq->bind_param("isss", $order_id, $bn, $acc, $rno);
            $bq->execute();
            $bq->close();
        }

        // Insert each cart item
        foreach ($_SESSION['cart'] as $bid => $itm) {
            $qnty = $itm['quantity'];
            $subt = $itm['price'] * $qnty;
            $oi = $conn->prepare("INSERT INTO Order_Items (order_id, book_id, quantity, subtotal)
                                  VALUES (?, ?, ?, ?)");
            $oi->bind_param("iiid", $order_id, $bid, $qnty, $subt);
            $oi->execute();
            $oi->close();

            // reduce stock
            $ustk = $conn->prepare("UPDATE Merchandise SET stock_quantity = stock_quantity - ? 
                                    WHERE book_id = ?");
            $ustk->bind_param("ii", $qnty, $bid);
            $ustk->execute();
            $ustk->close();
        }

        // Clear cart
        $_SESSION['cart'] = [];
        $orderPlaced = true;

        // Show summary
        echo "<h3>Order Placed Successfully!</h3>";
        echo "<p>Order ID: #$order_id</p>";
        echo "<p>Total: $" . number_format($total,2) . "</p>";
        echo "<p>Order Status: pending</p>";

        // Show items
        $oi2 = $conn->prepare("SELECT oi.*, b.book_title, b.book_author, m.price
                               FROM Order_Items oi
                               JOIN Books b ON oi.book_id = b.book_id
                               JOIN Merchandise m ON m.book_id = b.book_id
                               WHERE oi.order_id = ?");
        $oi2->bind_param("i", $order_id);
        $oi2->execute();
        $ri = $oi2->get_result();
        if ($ri->num_rows > 0) {
            echo "<table>";
            echo "<tr><th>Title</th><th>Author</th><th>Quantity</th><th>Price</th><th>Subtotal</th></tr>";
            while ($oit = $ri->fetch_assoc()) {
                echo "<tr>
                        <td>" . htmlspecialchars($oit['book_title']) . "</td>
                        <td>" . htmlspecialchars($oit['book_author']) . "</td>
                        <td>" . (int)$oit['quantity'] . "</td>
                        <td>$" . number_format($oit['price'],2) . "</td>
                        <td>$" . number_format($oit['subtotal'],2) . "</td>
                      </tr>";
            }
            echo "</table>";
        }
        $oi2->close();

        echo '<a href="bookInventory.php"><button>Back to Inventory</button></a>';
    } else {
        echo "<p>Error placing order. Try again.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Checkout</title>
  <link rel="icon" type="image/png" href="images/favicon.png">
  <link rel="stylesheet" href="css/styles.css">
  <link rel="stylesheet" href="css/styles_<?php echo htmlspecialchars($theme); ?>.css">
</head>
<body onload="updateCartCount();">

<?php if (!$orderPlaced): ?>
 <!-- HEADER -->
 <header style="display: flex; justify-content: space-between; align-items: center; padding: 20px 40px; background-color: #333; color: white;">
    <h1 style="margin: 0; font-size: 24px;">Checkout</h1>

    <div style="background: #f4f4f4; padding: 12px 20px; border-radius: 8px; color: black; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
        <div style="display: flex; align-items: center; gap: 15px;">
            <?php if (isset($_SESSION['username'])): ?>
                <span style="font-weight: 500;">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
                <a href="logout.php" style="padding: 6px 12px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px; border: none; font-weight: 500;">Logout</a>
            <?php else: ?>
                <a href="login.php" style="padding: 6px 12px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px; border: none; font-weight: 500;">Login</a>
            <?php endif; ?>

            <button onclick="openThemeModal()" style="padding: 6px 12px; border: 1px solid #ccc; background: white; border-radius: 4px; cursor: pointer;">
                Change Theme
            </button>

            <a href="cartshow.php" style="padding: 6px 12px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px; border: none; font-weight: 500;">
                Cart (<span id="cartCount">0</span>)
            </a>
        </div>
    </div>
</header>





  <h2>Review Your Order</h2>
  <p>Total: $<?php echo number_format($total, 2); ?></p>
  <form action="checkoutshow.php" method="POST" style="max-width: 600px;">
  <fieldset>
    <legend>Billing Address</legend>
    <input type="text" name="billing_address" placeholder="Enter billing address" required style="width:100%;">
  </fieldset>

  <fieldset>
    <legend>Shipping Address</legend>
    <input type="text" name="shipping_address" placeholder="Enter shipping address" required style="width:100%;">
    <label><input type="checkbox" id="same_as_billing" onclick="copyBillingAddress()"> Same as Billing</label>
  </fieldset>

  <fieldset>
    <legend>Payment Method</legend>
    <select id="payment_method" name="payment_method" onchange="toggleBankDetailsMain()" required>
      <option value="">Select Payment Method</option>
      <option value="credit_card">Credit Card</option>
      <option value="bank_transfer">Bank Transfer</option>
    </select>

    <!-- Credit Card Section -->
    <div id="card_section" style="display:none; margin-top:10px;">
      <label>Card Number:</label>
      <input type="text" name="card_number" id="card_number" placeholder="e.g. 4319 9833 0000 0022"
             pattern="\d{13,19}" maxlength="19" style="width:100%;" required>
      <label>Expiry (MM/YY):</label>
      <input type="text" name="card_expiry" id="card_expiry" placeholder="MM/YY"
             pattern="^(0[1-9]|1[0-2])\/\d{2}$" maxlength="5" style="width:100%;" required>
      <label>CVC:</label>
      <input type="text" name="card_cvc" id="card_cvc" placeholder="3-digit code"
             pattern="\d{3,4}" maxlength="4" style="width:100%;" required>
    </div>

    <!-- Bank Transfer Section -->
    <div id="bank_section" style="display:none; margin-top:10px;">
      <label>Bank Name:</label>
      <input type="text" name="bank_name" id="bank_name" style="width:100%;"><br>
      <label>Account Number:</label>
      <input type="text" name="account_number" id="account_number" style="width:100%;"><br>
      <label>Routing Number:</label>
      <input type="text" name="routing_number" id="routing_number" style="width:100%;"><br>
    </div>
  </fieldset>

  <br>
  <button type="submit">Place Order</button>
</form>

  <br>
  <a href="cartshow.php"><button>Back to Cart</button></a>
<?php endif; ?>

<!-- THEME & AVATAR MODALS -->
<div id="modalBackdrop"
     onclick="closeThemeModal(); closeAvatarModal();"
     style="display:none; position:fixed; left:0; top:0; width:100%; height:100%;
            background:rgba(0,0,0,0.5); z-index:5;">
</div>
<div id="themeModal" class="modal"
     style="display:none; position:fixed; z-index:10; left:50%; top:50%;
            transform:translate(-50%, -50%); background:#fff; padding:20px; border-radius:5px;">
  <h3>Select Theme</h3>
  <button onclick="changeTheme('purple')">Purple</button>
  <button onclick="changeTheme('blue')">Blue</button>
  <button onclick="changeTheme('green')">Green</button>
  <br><br>
  <button onclick="closeThemeModal()">Close</button>
</div>
<div id="avatarModal" class="modal"
     style="display:none; position:fixed; z-index:10; left:50%; top:50%;
            transform:translate(-50%, -50%); background:#fff; padding:20px; border-radius:5px;">
  <h3>Select Avatar</h3>
  <?php for ($i=1; $i<=5; $i++): ?>
    <img src="images/avatars/avatar<?php echo $i; ?>.jpg"
         style="width:60px; height:60px; margin:5px; cursor:pointer;"
         onclick="selectAvatar(<?php echo $i; ?>)">
  <?php endfor; ?>
  <br><br>
  <button onclick="closeAvatarModal()">Close</button>
</div>



<script src="js/cart.js"></script>
<script src="js/utils.js"></script>


<!-- Link to main JavaScript functions -->
<script src="js/main.js"></script>

</body>
</html>
