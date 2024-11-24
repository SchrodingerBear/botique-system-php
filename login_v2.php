<?php
ob_start();
require_once('includes/load.php');
if ($session->isUserLoggedIn(true)) {
  redirect('index.php', false);
}
?>
<style>
  /* styles.css */
  body {
    background-image: url('libs/images/db.jpg');
    /* Path to your background image */
    background-size: cover;
    /* Cover the entire viewport */
    background-position: center;
    /* Center the image */
    background-attachment: fixed;
    /* Fix the background image */
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
  }

  .login-page {
    width: 100%;
    max-width: 400px;
    margin: 100px auto;
    padding: 20px;
    background: rgba(255, 255, 255, 0.8);
    /* Semi-transparent white background */
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    /* Subtle shadow */
  }

  .text-center {
    text-align: center;
    margin-bottom: 20px;
  }

  .text-center h1 {
    font-size: 2em;
    margin: 0;
    color: #333;
  }

  .text-center p {
    color: #666;
  }

  .form-group {
    margin-bottom: 15px;
  }

  .form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
  }

  .form-group input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
  }

  .btn-info {
    background-color: #5bc0de;
    border-color: #46b8da;
    color: white;
  }

  .btn-info:hover {
    background-color: #31b0d5;
    border-color: #269abc;
  }
</style>
<div class="login-page">
  <div class="text-center">
    <h1>Welcome</h1>
    <p>Sign in to start your session</p>
  </div>
  <?php echo display_msg($msg); ?>
  <form method="post" action="auth_v2.php" class="clearfix">
    <div class="form-group">
      <label for="username" class="control-label">Username</label>
      <input type="name" class="form-control" name="username" placeholder="Username">
    </div>
    <div class="form-group">
      <label for="Password" class="control-label">Password</label>
      <input type="password" name="password" class="form-control" placeholder="password">
    </div>
    <div class="form-group">
      <button type="submit" class="btn btn-info  pull-right">Login</button>
    </div>
  </form>
</div>
<?php include_once('layouts/header.php'); ?>