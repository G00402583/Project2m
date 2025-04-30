<link rel="icon" type="image/png" href="images/favicon.png">
<?php
session_start();

// This can do advanced server-side validation for your checkout form
// Integrated simpler validation directly in checkoutshow.php

$response = [
  "success" => "success",
  "errors"  => [],
  "formFields" => []
];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
   // For example: "billing_address", "shipping_address", "payment_method", etc.

   // If any required fields are empty, set errors:
   // e.g. $response['errors']['billing']['address'] = "*required"

   // If no errors
   if (!empty($response["errors"])) {
       $response["success"] = "failure";
   } else {
       // Save data in $_SESSION
       $_SESSION['checkout_form'] = $_POST; 
   }

   header("Content-Type: application/json");
   echo json_encode($response);
   exit;
}
?>
