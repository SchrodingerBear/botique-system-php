<?php
require_once('includes/load.php');
// Get the raw POST data
$data = file_get_contents('php://input');

// Decode the JSON data
$decodedData = json_decode($data, true);

if (
  $decodedData && isset($decodedData['image_id']) && isset($decodedData['name'])
  && isset($decodedData['price']) && isset($decodedData['color']) && isset($decodedData['size'])
  && isset($decodedData['quantity'])
) {
  // Check if the product exists with the specified name, size, and color
  $productQuery = "
        SELECT id FROM products 
        WHERE name = '{$decodedData['name']}' 
        AND size = '{$decodedData['size']}' 
        AND color = '{$decodedData['color']}'
        LIMIT 1
    ";
  $productResult = $db->query($productQuery);

  if ($productResult->num_rows === 0) {
    // No product found with the specified variation
    echo json_encode(["msg" => "error", "error" => "No item with that variation available. Please select different variation."]);
  } else {
    // Product exists, proceed to add to the cart
    $query = "INSERT INTO cart (";
    $query .= " image_id, name, price, color, size, quantity, userName, user_id";
    $query .= ") VALUES (";
    $query .= " '{$decodedData['image_id']}', '{$decodedData['name']}', '{$decodedData['price']}', '{$decodedData['color']}', '{$decodedData['size']}', '{$decodedData['quantity']}', '{$decodedData['userName']}', '{$decodedData['user_id']}'";
    $query .= ")";

    if ($db->query($query)) {
      $session->msg('s', "Item added to Cart");
      echo json_encode(["msg" => "success"]);
    } else {
      $session->msg('d', 'Sorry, failed to add to Cart!');
      echo json_encode(["msg" => "error"]);
    }
  }
}
?>