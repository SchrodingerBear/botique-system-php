<?php
require_once('includes/load.php');
// Check what level user has permission to view this page
page_require_level(2);

// Validate product ID
$product_id = (int)$_GET['id'];
$product = find_by_id('products', $product_id);
if (!$product) {
    $session->msg("d", "Missing Product ID.");
    redirect('product.php');
}

// Update the product status to 'sold out'
if (update_product_status($product_id, 'sold out')) {
    $session->msg("s", "Product marked as sold out.");
} else {
    $session->msg("d", "Failed to mark product as sold out.");
}
redirect('product.php');

// Function to update product status
function update_product_status($id, $status) {
    global $db; // Ensure you have access to the database connection
    // Use prepared statements to avoid SQL injection
    $query = "UPDATE products SET status = ? WHERE id = ?";
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, 'si', $status, $id);
    return mysqli_stmt_execute($stmt);
}
?>
