<?php
require_once('includes/load.php');

// Check what level user has permission to view this page
page_require_level(2);

// Validate product ID
$product_id = ($_GET['id']);
// Update the product status to 'sold out'
if (update_product_status($product_id, 0)) {
    $session->msg("s", "Product marked as sold out.");
} else {
    $session->msg("d", "Failed to mark product as sold out.");
}
redirect('product.php');

// Function to update product status
function update_product_status($id, $status) {
    global $db; // Ensure you have access to the database connection

    // Gamitin ang getConnection method para makuha ang mysqli connection

    // Prepare the SQL statement
    $query = "UPDATE products SET quantity = '{$status}' WHERE id = '{$id}'";
    return $db->query($query); 
    // // Gumamit ng prepared statements para maiwasan ang SQL injection
    // $stmt = mysqli_prepare($mysqli_connection, $query);
    // mysqli_stmt_bind_param($stmt, 'si', $status, $id);
    // return mysqli_stmt_execute($stmt);
}

?>
