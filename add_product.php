<?php
  $page_title = 'Add Product';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
  $all_categories = find_all('categories');
  $all_photo = find_all('media');
?>
<?php
 if(isset($_POST['add_product'])){
   $req_fields = array('product-title','product-categorie','product-quantity','buying-price', 'saleing-price' );
   validate_fields($req_fields);
   if(empty($errors)){
     $p_name  = remove_junk($db->escape($_POST['product-title']));
     $p_cat   = remove_junk($db->escape($_POST['product-categorie']));
     $p_qty   = remove_junk($db->escape($_POST['product-quantity']));
     $p_sex   = remove_junk($db->escape($_POST['product-sex']));
     $p_buy   = remove_junk($db->escape($_POST['buying-price']));
     $p_sale  = remove_junk($db->escape($_POST['saleing-price']));
     $color  = remove_junk($db->escape($_POST['color']));
     $size  = "";
     $cat_name = explode(",",$p_cat);

     if(explode(",",$p_cat)[0]==="SHOES"){
      $size  = remove_junk($db->escape($_POST['sizes']));
     }else if(explode(",",$p_cat)[0]==="CLOTHES"){
      $size  = remove_junk($db->escape($_POST['size']));
     }else{
      $size  = NULL;
     }
     
     if (is_null($_POST['product-photo']) || $_POST['product-photo'] === "") {
       $media_id = '0';
     } else {
       $media_id = remove_junk($db->escape($_POST['product-photo']));
     }
     $date    = make_date();
     $query  = "INSERT INTO products ("; 
     $query .=" name,quantity,buy_price,sale_price,category,category_name,media_id,date,size,sex,color";
     $query .=") VALUES (";
     $query .=" '{$p_name}', '{$p_qty}', '{$p_buy}', '{$p_sale}', '{$cat_name[0]}', '{$cat_name[1]}', '{$media_id}', '{$date}', '{$size}', '{$p_sex}', '{$color}'";
     $query .=")";
     if($db->query($query)){
       $session->msg('s',"Product added ");
       redirect('add_product.php', false);
     } else {
       $session->msg('d',' Sorry failed to added!');
       redirect('product.php', false);
     }

   } else{
     $session->msg("d", $errors);
     redirect('add_product.php',false);
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
            <span class="glyphicon glyphicon-th"></span>
            <span>Add New Product</span>
         </strong>
        </div>
        <div class="panel-body">
         <div class="col-md-12">
          <form method="post" action="add_product.php" class="clearfix">
              <div class="form-group">
                <label for="product-title">Product Title</label>
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="product-title" id="product-title" placeholder="Product Title">
               </div>
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-md-3">
                    <label for="product-categorie">Product Category</label>
                    <select class="form-control" name="product-categorie" id="product-categorie">
                      <option value="">Select Product Category</option>
                    <?php  foreach ($all_categories as $cat): ?>
                      <option value="<?php echo $cat['type'].",".$cat['name'] ?>">
                        <?php echo $cat['name'] ?></option>
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
                      <option>XXS</option>
                      <option>XS</option>
                      <option>S</option>
                      <option>M</option>
                      <option>L</option>
                      <option>XL</option>
                      <option>XXL</option>
                      <option>XXXL</option>
                    </select>
                  </div>
                  <div class="col-md-3">
                    <label for="product-photo">Product Photo</label>
                    <select class="form-control" name="product-photo" id="product-photo">
                      <option value="">Select Product Photo</option>
                    <?php  foreach ($all_photo as $photo): ?>
                      <option value="<?php echo (int)$photo['id'] ?>">
                        <?php echo $photo['file_name'] ?></option>
                    <?php endforeach; ?>
                    </select>
                  </div>
                  <div style="display: flex; gap: 10px;">
                    <label for="product-sex">Sex</label>
                    <select class="form-control" name="product-sex" id="product-sex" style="width: 140px;">
                      <option value="">Select Sex</option>
                      <option value="Male">Male</option>
                      <option value="Female">Female</option>
                      <option value="Unisex">Unisex</option>
                    </select>
                    <label for="color">Color</label>
                    <input type="text" placeholder="Color" name="color" id="color" class="form-control" style="width:100px">
                  </div>
                </div>
              </div>

              <div class="form-group">
               <div class="row">
                 <div class="col-md-4">
                   <label for="product-quantity">Product Quantity</label>
                   <div class="input-group">
                     <span class="input-group-addon">
                      <i class="glyphicon glyphicon-shopping-cart"></i>
                     </span>
                     <input type="number" class="form-control" name="product-quantity" id="product-quantity" placeholder="Product Quantity">
                  </div>
                 </div>
                 <div class="col-md-4">
                   <label for="buying-price">Buying Price</label>
                   <div class="input-group">
                     <span class="input-group-addon">
                       <i class="glyphicon glyphicon-peso">₱</i>
                     </span>
                     <input type="number" class="form-control" name="buying-price" id="buying-price" placeholder="Buying Price">
                     <span class="input-group-addon">.00</span>
                  </div>
                 </div>
                  <div class="col-md-4">
                    <label for="saleing-price">Selling Price</label>
                    <div class="input-group">
                      <span class="input-group-addon">
                        <i class="glyphicon glyphicon-peso">₱</i>
                      </span>
                      <input type="number" class="form-control" name="saleing-price" id="saleing-price" placeholder="Selling Price">
                      <span class="input-group-addon">.00</span>
                   </div>
                  </div>
               </div>
              </div>
              <button type="submit" name="add_product" class="btn btn-danger">Add product</button>
          </form>
         </div>
        </div>
      </div>
    </div>
  </div>
<script>
   $(document).ready(function(){
    
    $("#any-size").hide();
          $("#clothe-size").hide();
    $("#product-categorie").change(function(){
        let type = $("#product-categorie").val()

        if(type.split(",")[0]==="SHOES"){
          $("#any-size").show();
          $("#clothe-size").hide();
        }else if(type.split(",")[0]==="CLOTHES"){
          $("#any-size").hide();
          $("#clothe-size").show();
        }else{
          $("#any-size").hide();
          $("#clothe-size").hide();
          
        }
    })
})
</script>
<?php include_once('layouts/footer.php'); ?>
