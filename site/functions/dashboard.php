<?php
require_once('./include/sql.php');
$user = current_user();
$cartNumber = 0;

if ($user && isset($user['id'])) {
  foreach (countCart($user['id']) as $data1) {
    $cartNumber += 1;
  }
  $cartData = countCart($user['id']);

} else {
  $cartNumber = 0; // Default to 0 if no user is logged in
}


function getCategory()
{
  global $db;
  $sql = "SELECT * FROM categories";
  $result = find_by_sql($sql);
  return ($result);
}
function getDistinctProduct()
{
  global $db;
  $sql = "SELECT DISTINCT name FROM products";
  $result = find_by_sql($sql);
  return ($result);
}
function getAllProducts()
{
  global $db;
  $sql = " SELECT p.id,p.name,p.quantity,p.buy_price,p.sale_price,p.category_name,p.size,p.color,p.media_id,p.date,c.name";
  $sql .= " AS categorie,m.file_name AS image";
  $sql .= " FROM products p";
  $sql .= " LEFT JOIN categories c ON c.id = p.categorie_id";
  $sql .= " LEFT JOIN media m ON m.id = p.media_id";
  return find_by_sql($sql);
}

function getSizes($size)
{
  global $db;
  $sql = "SELECT * FROM products WHERE name = '$size'";
  $result = find_by_sql($sql);
  return ($result);
}

function getColor($size)
{
  global $db;
  $sql = "SELECT * FROM products WHERE name = '$size'";
  $result = find_by_sql($sql);
  return ($result);
}

function getImage($imgs)
{
  global $db;
  $sql = " SELECT p.id,p.name,p.quantity,p.buy_price,p.sale_price,p.category_name,p.size,p.media_id,p.date,c.name";
  $sql .= " AS categorie,m.file_name AS image";
  $sql .= " FROM products p";
  $sql .= " LEFT JOIN categories c ON c.id = p.categorie_id";
  $sql .= " LEFT JOIN media m ON m.id = p.media_id WHERE id = {$imgs}";
  return find_by_sql($sql);
}
function countCart($id)
{
  global $db;

  $sql = "SELECT * from cart WHERE user_id = {$id}";
  return find_by_sql($sql);
}

function getImages($id)
{
  global $db;
  $sql = "SELECT file_name FROM media WHERE id = {$id}";
  return find_by_id('media', $id);
}
?>