<?php
$page_title = 'Sales Report';
$results = '';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);
?>
<?php
  if(isset($_POST['submit'])){
    $req_dates = array('start-date','end-date');
    validate_fields($req_dates);

    if(empty($errors)):
      $start_date   = remove_junk($db->escape($_POST['start-date']));
      $end_date     = remove_junk($db->escape($_POST['end-date']));
      $results      = find_sale_by_dates($start_date,$end_date);
    else:
      $session->msg("d", $errors);
      redirect('sales_report.php', false);
    endif;

  } else {
    $session->msg("d", "Select dates");
    redirect('sales_report.php', false);
  }
?>
<!doctype html>
<html lang="en-US">
 <head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <title>Default Page Title</title>
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"/>
     <style>
    @media print {
        html, body {
            font-size: 10pt;
            margin: 0;
            padding: 0;
        }
        .page-break {
            page-break-before: always;
            width: auto;
            margin: auto;
        }
    }
    
    .page-break {
        max-width: 1000px;
        margin: 20px auto;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        background-color: #ffffff;
    }

    .sale-head {
        margin: 20px 0;
        text-align: center;
    }

    .sale-head h1 {
        font-size: 24px;
        margin: 0;
        color: #333;
        border-bottom: 2px solid #007bff;
        padding-bottom: 10px;
    }

    .sale-head strong {
        font-size: 18px;
        display: block;
        margin-top: 5px;
        color: #555;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    table thead tr th {
        background-color: #007bff;
        color: #ffffff;
        padding: 10px;
        text-align: center;
        border: 1px solid #ededed;
    }

    table tbody tr {
        transition: background-color 0.3s;
    }

    table tbody tr:hover {
        background-color: #f1f1f1;
    }

    table tbody tr td {
        padding: 10px;
        text-align: right;
        border: 1px solid #ededed;
    }

    tfoot {
        font-weight: 600;
        background-color: #f8f8f8;
    }

    tfoot tr td {
        border-top: 2px solid #007bff;
    }
</style>

</head>
<body>
  <?php if($results): ?>
    <div class="page-break">
       <div class="sale-head pull-right">
           <h1>Sales Report</h1>
           <strong><?php if(isset($start_date)){ echo $start_date;}?> To <?php if(isset($end_date)){echo $end_date;}?> </strong>
       </div>
      <table class="table table-border">
        <thead>
          <tr>
              <th>Date</th>
              <th>Product Title</th>
              <th>Buying Price</th>
              <th>Selling Price</th>
              <th>Total Qty</th>
              <th>TOTAL</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($results as $result): ?>
           <tr>
              <td class=""><?php echo remove_junk($result['date']);?></td>
              <td class="desc">
                <h6><?php echo remove_junk(ucfirst($result['name']));?></h6>
              </td>
              <td class="text-right"><?php echo remove_junk($result['buy_price']);?></td>
              <td class="text-right"><?php echo remove_junk($result['sale_price']);?></td>
              <td class="text-right"><?php echo remove_junk($result['total_sales']);?></td>
              <td class="text-right"><?php echo remove_junk($result['total_saleing_price']);?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
         <tr class="text-right">
           <td colspan="4"></td>
           <td colspan="1">Grand Total</td>
           <td> ₱
           <?php echo number_format(total_price($results)[0], 2);?>
          </td>
         </tr>
         <tr class="text-right">
           <td colspan="4"></td>
           <td colspan="1">Profit</td>
           <td> ₱<?php echo number_format(total_price($results)[1], 2);?></td>
         </tr>
        </tfoot>
      </table>
    </div>
  <?php
    else:
        $session->msg("d", "Sorry no sales has been found. ");
        redirect('sales_report.php', false);
     endif;
  ?>
</body>
</html>
<?php if(isset($db)) { $db->db_disconnect(); } ?>
