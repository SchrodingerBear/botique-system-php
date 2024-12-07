<?php
$page_title = 'Add Product';
require_once('includes/load.php');
// Checkin What level user has permission to view this page
page_require_level(2);
$all_categories = find_all('categories');
$all_photo = find_all('media');
if (isset($_POST['add_product'])) {
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

    $media_ids = [];
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

    $query = "INSERT INTO products (name, quantity, buy_price, sale_price, category, category_name, media_id, size, sex, color) 
              VALUES ('{$p_name}', '{$p_qty}', '{$p_buy}', '{$p_sale}', '{$cat_name[0]}', '{$cat_name[1]}', 
              '{$media_ids_string}', " . ($size ? "'{$size}'" : "NULL") . ", '{$p_sex}', '{$color}')";
    echo $query;  // Debugging line to show the query

    if ($db->query($query)) {
      $session->msg('s', "Product added ");
    } else {
      $session->msg('d', ' Sorry, failed to add product!');
    }


  } else {
    $session->msg("d", $errors);
    redirect('add_product.php', false);
  }
}
$variation = fetch_variations();
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
          <span class="glyphicon glyphicon-th"></span>
          <span>Add New Product</span>
        </strong>
      </div>
      <div class="panel-body">
        <form method="post" action="add_product.php" class="clearfix" enctype="multipart/form-data">

          <!-- Product Title -->
          <div class="form-group">
            <label for="product-title">Product Title</label>
            <div class="input-group">
              <span class="input-group-addon">
                <i class="glyphicon glyphicon-th-large"></i>
              </span>
              <input type="text" class="form-control" name="product-title" id="product-title"
                placeholder="Product Title">
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
                    <option value="<?php echo $cat['type'] . "," . $cat['name']; ?>">
                      <?php echo $cat['name']; ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="col-md-3" id="any-size">
                <label for="sizes">Size</label>
                <input type="number" name="sizes" id="sizes" placeholder="Size" class="form-control size">
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
                        echo "<option value=\"{$size}\">{$size}</option>";
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
                  <option value="Male">Male</option>
                  <option value="Female">Female</option>
                  <option value="Unisex">Unisex</option>
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
                      echo "<option value=\"{$color}\">{$color}</option>";
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
                    placeholder="Product Quantity">
                </div>
              </div>

              <div class="col-md-4">
                <label for="buying-price">Buying Price</label>
                <div class="input-group">
                  <span class="input-group-addon">
                    <i class="glyphicon glyphicon-peso">₱</i>
                  </span>
                  <input type="number" class="form-control" name="buying-price" id="buying-price"
                    placeholder="Buying Price">
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
                    placeholder="Selling Price">
                  <span class="input-group-addon">.00</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Submit Button -->
          <button type="submit" name="add_product" class="btn btn-danger">Add Product</button>

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