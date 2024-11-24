<?php
$page_title = 'Edit product';
require_once('includes/load.php');
// Checkin What level user has permission to view this page
page_require_level(2);
?>
<?php
$product = find_by_id('products', (int) $_GET['id']);
$all_categories = find_all('categories');
$all_photo = find_all('media');
if (!$product) {
  $session->msg("d", "Missing product id.");
  redirect('product.php');
}
?>
<?php
if (isset($_POST['product'])) {
  $req_fields = array('product-title', 'product-categorie', 'product-quantity', 'buying-price', 'saleing-price', 'product-size', 'product-color', 'product-sex');
  validate_fields($req_fields);

  if (empty($errors)) {
    $p_name = remove_junk($db->escape($_POST['product-title']));
    $p_cat = (int) $_POST['product-categorie'];
    $p_qty = remove_junk($db->escape($_POST['product-quantity']));
    $p_buy = remove_junk($db->escape($_POST['buying-price']));
    $p_sale = remove_junk($db->escape($_POST['saleing-price']));
    $p_size = remove_junk($db->escape($_POST['product-size']));
    $p_color = remove_junk($db->escape($_POST['product-color']));
    $p_sex = remove_junk($db->escape($_POST['product-sex']));

    if (is_null($_POST['product-photo']) || $_POST['product-photo'] === "") {
      $media_id = '0';
    } else {
      $media_id = remove_junk($db->escape($_POST['product-photo']));
    }

    $query = "UPDATE products SET";
    $query .= " name ='{$p_name}', quantity ='{$p_qty}',";
    $query .= " buy_price ='{$p_buy}', sale_price ='{$p_sale}', categorie_id ='{$p_cat}', media_id='{$media_id}',";
    $query .= " size ='{$p_size}', color ='{$p_color}', sex ='{$p_sex}'";
    $query .= " WHERE id ='{$product['id']}'";
    $result = $db->query($query);
    if ($result && $db->affected_rows() === 1) {
      $session->msg('s', "Product updated ");
      redirect('product.php', false);
    } else {
      $session->msg('d', ' Nothing updated!');
      redirect('edit_product.php?id=' . $product['id'], false);
    }

  } else {
    $session->msg("d", $errors);
    redirect('edit_product.php?id=' . $product['id'], false);
  }

}
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>
<div class="row">
  <div class="panel panel-default">
    <div class="panel-heading">
      <strong>
        <span class="glyphicon glyphicon-th"></span>
        <span>Edit Product</span>
      </strong>
    </div>
    <div class="panel-body">
      <div class="col-md-7">
        <form method="post" action="edit_product.php?id=<?php echo (int) $product['id'] ?>">
          <div class="form-group">
            <div class="input-group">
              <span class="input-group-addon">
                <i class="glyphicon glyphicon-th-large"></i>
              </span>
              <input type="text" class="form-control" name="product-title"
                value="<?php echo remove_junk($product['name']); ?>">
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-md-6">
                <select class="form-control" name="product-categorie">
                  <option value=""> Select a category</option>
                  <?php foreach ($all_categories as $cat): ?>
                    <option value="<?php echo (int) $cat['id']; ?>" <?php if ($product['categorie_id'] === $cat['id']):
                          echo "selected";
                        endif; ?>>
                      <?php echo remove_junk($cat['name']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-6">
                <select class="form-control" name="product-photo">
                  <option value=""> No image</option>
                  <?php foreach ($all_photo as $photo): ?>
                    <option value="<?php echo (int) $photo['id']; ?>" <?php if ($product['media_id'] === $photo['id']):
                          echo "selected";
                        endif; ?>>
                      <?php echo $photo['file_name'] ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label for="qty">Quantity</label>
                  <div class="input-group">
                    <span class="input-group-addon">
                      <i class="glyphicon glyphicon-shopping-cart"></i>
                    </span>
                    <input type="number" class="form-control" name="product-quantity"
                      value="<?php echo remove_junk($product['quantity']); ?>">
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="qty">Buying price</label>
                  <div class="input-group">
                    <span class="input-group-addon">
                      <i class="glyphicon glyphicon-peso">₱</i>
                    </span>
                    <input type="number" class="form-control" name="buying-price"
                      value="<?php echo remove_junk($product['buy_price']); ?>">
                    <span class="input-group-addon">.00</span>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="qty">Selling price</label>
                  <div class="input-group">
                    <span class="input-group-addon">
                      <i class="glyphicon glyphicon-peso">₱</i>
                    </span>
                    <input type="number" class="form-control" name="saleing-price"
                      value="<?php echo remove_junk($product['sale_price']); ?>">
                    <span class="input-group-addon">.00</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label for="size">Size</label>
                  <div class="input-group">
                    <input type="text" class="form-control" name="product-size"
                      value="<?php echo remove_junk($product['size']); ?>">
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="color">Color</label>
                  <div class="input-group">
                    <input type="text" class="form-control" name="product-color"
                      value="<?php echo remove_junk($product['color']); ?>">
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="sex">Sex</label>
                  <div class="input-group">
                    <select class="form-control" name="product-sex" id="product-sex" style="width: 140px;">
                      <option value="Male">Male</option>
                      <option value="Female">Female</option>
                      <option value="Unisex">Unisex</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <button type="submit" name="product" class="btn btn-danger">Update</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>