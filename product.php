<?php
$page_title = 'All Product';
require_once('includes/load.php');
// Check user permission to view this page
page_require_level(2);
$products = join_product_table();
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <div class="pull-right">
                    <a href="add_product.php" class="btn btn-primary">Add New</a>
                </div>
            </div>
            <div class="panel-body">
                <table class="table table-bordered" id="table">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 50px;">#</th>
                            <th> Photo</th>
                            <th> Product Title </th>
                            <th class="text-center" style="width: 10%;"> Categorie </th>
                            <th class="text-center" style="width: 10%;"> Size </th>
                            <th class="text-center" style="width: 10%;"> Color </th>
                            <th class="text-center" style="width: 10%;"> Stock Product </th>
                            <th class="text-center" style="width: 10%;"> Buying Price </th>
                            <th class="text-center" style="width: 10%;"> Selling Price </th>
                            <th class="text-center" style="width: 10%;"> Product Added </th>
                            <th class="text-center" style="width: 100px;"> Actions </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td class="text-center"><?php echo count_id(); ?></td>
                                <td>
                                    <?php
                                    $media = fetch_media(); // This will return the array of media data
                                    $mediaIdString = $product['media_id'] ?? ''; // Default to empty string if null
                                
                                    // Only call explode if the media_id is not empty
                                    $mediaIds = $mediaIdString !== '' ? explode(',', $mediaIdString) : [];

                                    $imageFound = false; // Flag to track if at least one image is found
                                    foreach ($mediaIds as $mediaId) {
                                        foreach ($media as $item) {
                                            if ((string) $item['id'] === (string) $mediaId) { // Ensure type matching
                                                $filePath = "uploads/products/" . $item['file_name'];
                                                ?>
                                                <img class="img-avatar img-rectangel" src="<?php echo $filePath; ?>" alt="">
                                                <?php
                                                $imageFound = true;
                                                // No break here since we want to display all matching images
                                                break; // Break the inner loop to move to the next mediaId
                                            }
                                        }
                                    }
                                    if (!$imageFound): ?>
                                        <img class="img-avatar img-rectangel" src="uploads/products/no_image.jpg" alt="">
                                    <?php endif; ?>

                                </td>
                                <td><?php echo remove_junk($product['name']); ?></td>
                                <td class="text-center"><?php echo remove_junk($product['category_name']); ?></td>
                                <td class="text-center"><?php echo remove_junk($product['size']); ?></td>
                                <td class="text-center"><?php echo remove_junk($product['color']); ?></td>
                                <td class="text-center"><?php if ((int) remove_junk($product['quantity']) === 0): ?>
                                        <span class="badge badge-danger bg-danger" style="background-color: #DC3545;">Out of
                                            Stock</span>
                                    <?php else: ?>
                                        <?php echo remove_junk($product['quantity']); ?>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center"><?php echo remove_junk($product['buy_price']); ?></td>
                                <td class="text-center"><?php echo remove_junk($product['sale_price']); ?></td>
                                <td class="text-center"><?php echo read_date($product['date']); ?></td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="edit_product.php?id=<?php echo $product['id']; ?>"
                                            class="btn btn-info btn-xs" title="Edit" data-toggle="tooltip">
                                            <span class="glyphicon glyphicon-edit"></span>
                                        </a>
                                        <a href="mark_sold_out.php?id=<?php echo $product['id']; ?>"
                                            class="btn btn-warning btn-xs" title="Mark as Sold Out" data-toggle="tooltip">
                                            <span class="glyphicon glyphicon-ban-circle"></span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" />
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function () {
        $("#table").DataTable();
    });
</script>