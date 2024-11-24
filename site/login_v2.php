<?php
ob_start();
require_once('include/load.php');
require_once('auth_v2.php');
// if($session->isUserLoggedIn(true)) { redirect('index.php', false);}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" />
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" />
    <link rel="stylesheet" href="libs/css/main.css" />
    <style>
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            opacity: .75;
            display: flex;
            justify-content: center;
            align-items: center;
            background: url('../uploads/bg.jpg');
            background-repeat: no-repeat;
            background-size: cover;
            background-position-y: -300px;
            z-index: -1;

        }

        body {
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
            padding-top: 10%;

        }
    </style>
</head>

<body>
    <div
        style="background: rgba(255, 255, 255, 0.9); border-radius: 10px; padding: 30px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2); max-width: 400px; width: 100%;">
        <div class="text-center">
            <h1 style="margin-bottom: 20px; color: #333;">Welcome To Dea Belezza</h1>
        </div>
        <?php echo display_msg($msg); ?>
        <form method="post" action="auth_v2.php" class="clearfix">
            <div class="form-group" style="margin-bottom: 20px;">
                <label for="username" class="control-label" style="font-weight: bold;">Username</label>
                <input type="text" class="form-control" name="username" placeholder="Username" required
                    style="padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
            </div>
            <div class="form-group" style="margin-bottom: 20px;">
                <label for="Password" class="control-label" style="font-weight: bold;">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Password" required
                    style="padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-info pull-right"
                    style="background-color: #3498db; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">Login</button>
            </div>
            <a href="register.php">Register</a>
        </form>
    </div>
</body>

</html>