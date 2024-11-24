<?php
ob_start();
require_once('include/load.php');



// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form inputs
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate inputs
    if ($password !== $confirm_password) {
        $msg = "Passwords do not match!";
    } else {
        // Generate a salt (optional but recommended for SHA-1)
        $salt = bin2hex(random_bytes(16)); // Generates a 16-byte random salt

        // Hash the password with SHA-1 and the salt
        $hashed_password = sha1(
            $password
        );

        // Prepare the SQL query to insert data into the users table
        $query = "
            INSERT INTO users (name, username, password, user_level, status)
            VALUES ('{$name}','{$username}', '{$hashed_password}', 3, 1)
        ";

        // Execute the query
        if ($db->query($query)) {
            $msg = "Account created successfully!";
            // Optionally, redirect to login page or another page
            redirect('login_v2.php', false);
        } else {
            $msg = "Error creating account. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
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
            <h1 style="margin-bottom: 20px; color: #333;">Create Your Account</h1>
        </div>
        <?php echo display_msg($msg); ?>
        <form method="post" action="register.php" class="clearfix">
            <div class="form-group" style="margin-bottom: 20px;">
                <label for="username" class="control-label" style="font-weight: bold;">Username</label>
                <input type="text" class="form-control" name="username" placeholder="Username" required
                    style="padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
            </div>
            <div class="form-group" style="margin-bottom: 20px;">
                <label for="name" class="control-label" style="font-weight: bold;">Name</label>
                <input type="text" class="form-control" name="name" placeholder="Name" required
                    style="padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label for="password" class="control-label" style="font-weight: bold;">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Password" required
                    style="padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
            </div>
            <div class="form-group" style="margin-bottom: 20px;">
                <label for="confirm_password" class="control-label" style="font-weight: bold;">Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password"
                    required style="padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-info pull-right"
                    style="background-color: #3498db; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">Register</button>
            </div>
        </form>
        <p class="text-center" style="margin-top: 15px;">
            Already have an account? <a href="login_v2.php">Login here</a>
        </p>
    </div>
</body>

</html>