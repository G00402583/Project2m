<?php
session_start();
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
$total_items = 0;
$total_price = 0;
foreach ($_SESSION['cart'] as $it) {
    $total_items += $it['quantity'];
    $total_price += $it['price'] * $it['quantity'];
}
?>
<div class="mini-cart-box">
  <span id="cartCountData" style="display:none;"><?php echo $total_items; ?></span>
  <p><strong>Mini Cart Summary:</strong></p>
  <p>Items: <?php echo $total_items; ?></p>
  <p>Total Price: $<?php echo number_format($total_price, 2); ?></p>

  <button type="button" onclick="window.location.href='cartshow.php'" class="mini-cart-btn">
    View Full Cart
  </button>
</div>
