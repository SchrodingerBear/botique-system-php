<?php
$page_title = 'All sale';
require_once('includes/load.php');
// Checkin What level user has permission to view this page
page_require_level(3);
?>
<style>
  body {
    font-family: Arial, sans-serif;
    background-color: #f7f7f7;
    margin: 0;
    padding: 20px;
  }

  .panel {
    margin: 20px auto;
    background-color: #ffffff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  }

  .panel-heading {
    background-color: #007bff;
    color: #ffffff;
    padding: 15px;
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
    font-size: 20px;
  }

  .table {
    width: 100%;
    border-collapse: collapse;
  }

  .table th,
  .table td {
    padding: 12px;
    text-align: center;
    border: 1px solid #ddd;
  }

  .table th {
    background-color: #007bff;
    color: white;
  }

  .table tbody tr:nth-child(even) {
    background-color: #f9f9f9;
  }

  .table tbody tr:hover {
    background-color: #f1f1f1;
  }

  .btn-group .btn {
    margin: 0 2px;
  }

  .text-center {
    text-align: center;
  }

  .glyphicon {
    margin-right: 5px;
  }

  .pull-right {
    margin-top: -5px;
  }
</style>

<?php
$sales = find_all_sale();
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>All Sales</span>
        </strong>

      </div>
      <div class="panel-body">

        <table class="table table-bordered table-striped" id="table">
          <thead>
            <tr>
              <th class="text-center" style="width: 50px;">#</th>
              <th>Product Name</th>
              <th class="text-center" style="width: 15%;">Quantity</th>
              <th class="text-center" style="width: 15%;">Total</th>
              <th class="text-center" style="width: 15%;">Date</th>
              <th class="text-center" style="width: 15%;">Type</th>
              <th class="text-center" style="width: 15%;">Address</th>
              <th class="text-center" style="width: 15%;">Receipt</th>
              <th class="text-center" style="width: 100px;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($sales as $sale): ?>
              <tr>
                <td class="text-center"><?php echo count_id(); ?></td>
                <td><?php echo remove_junk($sale['name']); ?></td>
                <td class="text-center"><?php echo (int) $sale['qty']; ?></td>
                <td class="text-center">₱<?php echo remove_junk($sale['price']); ?></td>
                <td class="text-center"><?php echo $sale['date']; ?></td>
                <td class="text-center"><?php echo $sale['type'] == 1 ? 'Online Payment' : 'COD'; ?></td>
                <td class="text-center"><?php echo remove_junk($sale['address']); ?></td>
                <td class="text-center">
                  <?php if ($sale['type'] == 1): ?>
                    <img style="width: 250px; height: 250px;"
                      src="uploads/receipt/<?php echo basename($sale['receipt']); ?>" alt="Receipt">
                  <?php else: ?>
                    Receipt not available
                  <?php endif; ?>
                </td>
                <td class="text-center">
                  <div class="btn-group">
                    <a href="edit_sale.php?id=<?php echo (int) $sale['id']; ?>" class="btn btn-warning btn-xs"
                      title="Edit" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-edit"></span>
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