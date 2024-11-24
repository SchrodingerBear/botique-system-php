<?php
$page_title = 'All categories';
require_once('includes/load.php');
// Checkin What level user has permission to view this page
page_require_level(1);

$all_categories = find_all('categories')
  ?>
<?php
require_once('includes/load.php');
// Checkin What level user has permission to view this page
page_require_level(1);

$type_category = find_all('category_type')
  ?>
<?php
if (isset($_POST['add_cat'])) {
  $req_field = array('categorie-name');
  validate_fields($req_field);
  $cat_name = remove_junk($db->escape($_POST['categorie-name']));

  $req_fields = array('categorie-type');
  validate_fields($req_fields);
  $cat_type = remove_junk($db->escape($_POST['categorie-type']));
  if (empty($errors)) {
    $sql = "INSERT INTO categories (name,type)";
    $sql .= " VALUES ('{$cat_name}','{$cat_type}')";
    if ($db->query($sql)) {
      $session->msg("s", "Successfully Added Categorie");
      redirect('categorie.php', false);
    } else {
      $session->msg("d", "Sorry Failed to insert.");
      redirect('categorie.php', false);
    }
  } else {
    $session->msg("d", $errors);
    redirect('categorie.php', false);
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
  <div class="col-md-5">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Add New Category</span>
        </strong>
      </div>
      <div class="panel-body">
        <form method="post" action="categorie.php">
          <div class="form-group d-flex" style="display: flex; gap:10px;">
            <input type="text" class="form-control col-4" name="categorie-name" placeholder="Categorie Name">
            <select class="form-control col-4" name="categorie-type">
              <option value="">Select</option>
              <?php foreach ($type_category as $cat): ?>
                <option value="<?php echo strtoupper($cat['name']); ?>"><?php echo $cat['name']; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <button type="submit" name="add_cat" class="btn btn-primary">Add category</button>
        </form>
      </div>
    </div>
  </div>
  <div class="col-md-7">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>All Categories</span>
        </strong>
      </div>
      <div class="panel-body">
        <table class="table table-bordered table-striped table-hover" id="table">
          <thead>
            <tr>
              <th class="text-center" style="width: 50px;">#</th>
              <th>Category</th>
              <th>Type</th>
              <th class="text-center" style="width: 100px;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($all_categories as $cat): ?>
              <tr>
                <td class="text-center"><?php echo count_id(); ?></td>
                <td><?php echo remove_junk(ucfirst($cat['name'])); ?></td>
                <td><?php echo remove_junk(ucfirst($cat['type'])); ?></td>
                <td class="text-center">
                  <div class="btn-group">
                    <a href="edit_categorie.php?id=<?php echo (int) $cat['id']; ?>" class="btn btn-xs btn-warning"
                      data-toggle="tooltip" title="Edit">
                      <span class="glyphicon glyphicon-edit"></span>
                    </a>
                    <a href="delete_categorie.php?id=<?php echo (int) $cat['id']; ?>" class="btn btn-xs btn-danger"
                      data-toggle="tooltip" title="Remove">
                      <span class="glyphicon glyphicon-trash"></span>
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