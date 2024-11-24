<?php

$page_title = 'Monthly Sales';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);
?>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
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
        margin: 0;
        border-collapse: collapse;
        width: 100%;
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

    .text-center {
        text-align: center;
    }

    .glyphicon {
        margin-right: 5px;
    }
</style>

<?php
 $year = date('Y');
 $sales = monthlySales($year);
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
            <span>Monthly Sales</span>
          </strong>
        </div>
        <div class="panel-body">
          <table class="table table-bordered table-striped">
            <thead>
              <tr>
                <th class="text-center" style="width: 50px;">#</th>
                <th> Product name </th>
                <th class="text-center" style="width: 15%;"> Quantity sold</th>
                <th class="text-center" style="width: 15%;"> Total </th>
                <th class="text-center" style="width: 15%;"> Date </th>
             </tr>
            </thead>
           <tbody>
             <?php foreach ($sales as $sale):?>
             <tr>
               <td class="text-center"><?php echo count_id();?></td>
               <td><?php echo remove_junk($sale['name']); ?></td>
               <td class="text-center"><?php echo (int)$sale['qty']; ?></td>
               <td class="text-center"><?php echo remove_junk($sale['total_saleing_price']); ?></td>
               <td class="text-center"><?php echo $sale['date']; ?></td>
             </tr>
             <?php endforeach;?>
           </tbody>
         </table>
        </div>
      </div>
    </div>
  </div>

<?php include_once('layouts/footer.php'); ?>
