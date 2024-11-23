<?php
  $page_title = 'Home Page';
  require_once('includes/load.php');
  if (!$session->isUserLoggedIn(true)) { redirect('index.php', false);}
?>
<?php include_once('layouts/header.php'); ?>

<style>
 
 body {
  background-image: url('libs/images/db.jpg');
  background-size: contain; /* Resize the image to fit within the viewport while maintaining aspect ratio */
  background-position: right center; /* Align the image to the top left corner */
  background-repeat: no-repeat; /* Prevent image repetition */
  background-attachment: fixed; /* Fix the background image */
  margin: 0; /* Remove default margin */
  padding: 0; /* Remove default padding */
  height: 100vh; /* Ensure body takes full viewport height */
  overflow: hidden; /* Hide overflow to prevent scrolling */
}
  
</style>

<div class="content-wrapper">
  <div class="row">
    <div class="col-md-12">
   
    </div>
    
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>
