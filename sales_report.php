<?php
$page_title = 'Sale Report';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);
?>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f7f7f7;
    }

    .panel {
        margin: 20px auto;
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
    }

    .panel-heading {
        text-align: center;
        padding: 10px 0;
        font-size: 20px;
        color: #333;
        border-bottom: 2px solid #007bff;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-label {
        font-weight: bold;
        margin-bottom: 5px;
        display: block;
        color: #555;
    }

    .input-group {
        display: flex;
        justify-content: space-between;
    }

    .input-group input {
        margin-right: 5px;
        border-radius: 5px;
        border: 1px solid #ccc;
        padding: 10px;
    }

    .input-group-addon {
        background-color: #007bff;
        color: #fff;
        border-radius: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 10px;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        padding: 10px 20px;
        border-radius: 5px;
        color: #ffffff;
        transition: background-color 0.3s;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
    }
</style>

<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-6">
    <div class="panel">
      <div class="panel-heading">

      </div>
      <div class="panel-body">
          <form class="clearfix" method="post" action="sale_report_process.php">
            <div class="form-group">
              <label class="form-label">Date Range</label>
                <div class="input-group">
                  <input type="text" class="datepicker form-control" name="start-date" placeholder="From">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-menu-right"></i></span>
                  <input type="text" class="datepicker form-control" name="end-date" placeholder="To">
                </div>
            </div>
            <div class="form-group">
                 <button type="submit" name="submit" class="btn btn-primary">Generate Report</button>
            </div>
          </form>
      </div>

    </div>
  </div>

</div>
<?php include_once('layouts/footer.php'); ?>
