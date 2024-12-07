<?php
$page_title = 'Edit Product';
require_once('includes/load.php');
// Check user permissions
page_require_level(2);

$product_id = (int) $_GET['id'];
$product = find_by_id('products', $product_id);
var_dump($product);
if (!$product) {
  $session->msg('d', 'Product not found.');
  redirect('products.php');
}

$all_categories = find_all('categories');
$all_photo = find_all('media');
$variation = fetch_variations();

if (isset($_POST['edit_product'])) {
  $req_fields = array(
    'product-title',
    'product-categorie',
    'product-quantity',
    'buying-price',
    'saleing-price'
  );
  validate_fields($req_fields);

  if (empty($errors)) {
    $p_name = remove_junk($db->escape($_POST['product-title']));
    $p_cat = remove_junk($db->escape($_POST['product-categorie']));
    $p_qty = remove_junk($db->escape($_POST['product-quantity']));
    $p_sex = remove_junk($db->escape($_POST['product-sex']));
    $p_buy = remove_junk($db->escape($_POST['buying-price']));
    $p_sale = remove_junk($db->escape($_POST['saleing-price']));
    $color = remove_junk($db->escape($_POST['color']));
    $size = "";

    $cat_name = explode(",", $p_cat);

    if ($cat_name[0] === "SHOES") {
      $size = remove_junk($db->escape($_POST['sizes']));
    } else if ($cat_name[0] === "CLOTHES") {
      $size = remove_junk($db->escape($_POST['size']));
    } else {
      $size = NULL;
    }

    $media_ids = $product['media_id'] ? explode(",", $product['media_id']) : [];
    if (!empty($_FILES['product-photos']['name'][0])) {
      $upload_dir = 'uploads/products/';
      foreach ($_FILES['product-photos']['name'] as $key => $file_name) {
        $tmp_name = $_FILES['product-photos']['tmp_name'][$key];
        $file_type = $_FILES['product-photos']['type'][$key];
        $target_file = $upload_dir . basename($file_name);

        // Ensure the upload directory exists
        if (!is_dir($upload_dir)) {
          mkdir($upload_dir, 0777, true);
        }

        // Move the uploaded file to the designated directory
        if (move_uploaded_file($tmp_name, $target_file)) {
          // Insert into media table
          $query_media = "INSERT INTO media (file_name, file_type) VALUES ('{$file_name}', '{$file_type}')";
          if ($db->query($query_media)) {
            $media_ids[] = $db->insert_id();
          }
        }
      }
    }

    $media_ids_string = empty($media_ids) ? 'NULL' : implode(",", $media_ids);

    $query = "UPDATE products SET name = '{$p_name}', quantity = '{$p_qty}', buy_price = '{$p_buy}', 
              sale_price = '{$p_sale}', category = '{$cat_name[0]}', category_name = '{$cat_name[1]}', 
              media_id = '{$media_ids_string}', size = " . ($size ? "'{$size}'" : "NULL") . ", 
              sex = '{$p_sex}', color = '{$color}' WHERE id = {$product_id}";

    if ($db->query($query)) {
      $session->msg('s', "Product updated.");
    } else {
      $session->msg('d', 'Sorry, failed to update product.');
    }
  } else {
    $session->msg("d", $errors);
    redirect("edit_product.php?id={$product_id}", false);
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
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-edit"></span>
          <span>Edit Product</span>
        </strong>
      </div>
      <div class="panel-body">
        <form method="post" action="edit_product.php?id=<?php echo (int) $product_id; ?>" class="clearfix"
          enctype="multipart/form-data">
          <!-- Product Title -->
          <div class="form-group">
            <label for="product-title">Product Title</label>
            <div class="input-group">
              <span class="input-group-addon">
                <i class="glyphicon glyphicon-th-large"></i>
              </span>
              <input type="text" class="form-control" name="product-title" id="product-title"
                value="<?php echo $product['name']; ?>" placeholder="Product Title">
            </div>
          </div>

          <!-- Category and Options -->
          <div class="form-group">
            <div class="row">
              <div class="col-md-3">
                <label for="product-categorie">Product Category</label>
                <select class="form-control" name="product-categorie" id="product-categorie">
                  <option value="">Select Product Category</option>

                  <?php foreach ($all_categories as $cat): ?>
                    <?php
                    // Create the value for the option
                    $option_value = $cat['type'] . "," . $cat['name'];

                    // Check if the product's category name is contained in the option value
                    $is_selected = (strpos($option_value, $product['category_name']) !== false) ? 'selected' : '';
                    ?>
                    <option value="<?php echo $option_value; ?>" <?php echo $is_selected; ?>>
                      <?php echo $cat['name']; ?>
                    </option>
                  <?php endforeach; ?>
                </select>

              </div>

              <div class="col-md-3" id="any-size">
                <label for="sizes">Size</label>
                <input type="number" name="sizes" id="sizes" placeholder="Size" class="form-control size"
                  value="<?php echo $product['size']; ?>">
              </div>

              <div class="col-md-3" id="clothe-size">
                <label for="size">Size</label>
                <select name="size" class="form-control size">
                  <option value="">Select Size</option>
                  <?php
                  foreach ($variation as $item) {
                    if ($item['variation'] === 'Sizes') {
                      $sizes = json_decode($item['sub'], true);
                      foreach ($sizes as $size) {
                        $selected = $size === $product['size'] ? 'selected' : '';
                        echo "<option value=\"{$size}\" {$selected}>{$size}</option>";
                      }
                    }
                  }
                  ?>
                </select>
              </div>

              <div class="col-md-3">
                <label for="product-photo">Product Photos</label>
                <input type="file" class="form-control" name="product-photos[]" id="product-photo" accept="image/*"
                  multiple>
                <small class="form-text text-muted">Upload at least 3 images.</small>
              </div>
            </div>
          </div>

          <!-- Additional Options -->
          <div class="form-group">
            <div class="row">
              <div class="col-md-6">
                <label for="product-sex">Sex</label>
                <select class="form-control" name="product-sex" id="product-sex">
                  <option value="">Select Sex</option>
                  <option value="Male" <?php echo $product['sex'] === 'Male' ? 'selected' : ''; ?>>Male</option>
                  <option value="Female" <?php echo $product['sex'] === 'Female' ? 'selected' : ''; ?>>Female</option>
                  <option value="Unisex" <?php echo $product['sex'] === 'Unisex' ? 'selected' : ''; ?>>Unisex</option>
                </select>
              </div>
              <div class="col-md-6">
                <?php
                foreach ($variation as $item) {
                  if ($item['variation'] === 'Color') {
                    $colors = json_decode($item['sub'], true);
                    echo '<label for="color">Color</label>';
                    echo '<select name="color" id="color" class="form-control">';
                    foreach ($colors as $color) {
                      $selected = $color === $product['color'] ? 'selected' : '';
                      echo "<option value=\"{$color}\" {$selected}>{$color}</option>";
                    }
                    echo '</select>';
                  }
                }
                ?>
              </div>
            </div>
          </div>

          <!-- Pricing and Quantity -->
          <div class="form-group">
            <div class="row">
              <div class="col-md-4">
                <label for="product-quantity">Product Quantity</label>
                <div class="input-group">
                  <span class="input-group-addon">
                    <i class="glyphicon glyphicon-shopping-cart"></i>
                  </span>
                  <input type="number" class="form-control" name="product-quantity" id="product-quantity"
                    value="<?php echo $product['quantity']; ?>" placeholder="Product Quantity">
                </div>
              </div>

              <div class="col-md-4">
                <label for="buying-price">Buying Price</label>
                <div class="input-group">
                  <span class="input-group-addon">
                    <i class="glyphicon glyphicon-peso">₱</i>
                  </span>
                  <input type="number" class="form-control" name="buying-price" id="buying-price"
                    value="<?php echo $product['buy_price']; ?>" placeholder="Buying Price">
                  <span class="input-group-addon">.00</span>
                </div>
              </div>

              <div class="col-md-4">
                <label for="saleing-price">Selling Price</label>
                <div class="input-group">
                  <span class="input-group-addon">
                    <i class="glyphicon glyphicon-peso">₱</i>
                  </span>
                  <input type="number" class="form-control" name="saleing-price" id="saleing-price"
                    value="<?php echo $product['sale_price']; ?>" placeholder="Selling Price">
                  <span class="input-group-addon">.00</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Submit Button -->
          <button type="submit" name="edit_product" class="btn btn-danger">Update Product</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function () {
    $("#any-size").hide();
    $("#clothe-size").hide();
    $("#product-categorie").change(function () {
      let type = $(this).val();
      if (type.split(",")[0] === "SHOES") {
        $("#any-size").show();
        $("#clothe-size").hide();
      } else if (type.split(",")[0] === "CLOTHES") {
        $("#any-size").hide();
        $("#clothe-size").show();
      } else {
        $("#any-size").hide();
        $("#clothe-size").hide();
      }
    });
  });
</script>

<?php include_once('layouts/footer.php'); ?>