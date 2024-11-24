<?php

ob_start();
require_once('include/load.php');
require_once("functions/dashboard.php");
$data = getCategory();
$getallProduct = getAllProducts();
$distProduct = getAllProducts();
$getImage = "";
$uniqueData = array();

$user = current_user();

global $db;

$sql = "
    SELECT 
        s.*, 
        p.name AS product_name 
    FROM 
        sales AS s
    JOIN 
        products AS p 
    ON 
        s.product_id = p.id
    WHERE 
        s.userid = {$user['id']}
";

$orders = find_by_sql($sql);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = htmlspecialchars(trim($_POST['id']));
    $name = htmlspecialchars(trim($_POST['name']));
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));

    // Validate inputs
    if (!empty($id) && !empty($name) && !empty($username) && !empty($password)) {
        $hashedPassword = sha1($password);

        try {
            global $db;

            $sql = "
                UPDATE users 
                SET 
                    name = '{$name}', 
                    username = '{$username}', 
                    password = '{$hashedPassword}' 
                WHERE 
                    id = {$id}
            ";

            if ($db->query($sql)) {
                echo "<script>alert('Profile updated successfully.'); window.location.href='profile.php';</script>";
                exit;
            } else {
                echo "<script>alert('Failed to update profile.'); window.location.href='profile.php';</script>";
                exit;
            }
        } catch (Exception $e) {
            echo "<script>alert('Database error: {$e->getMessage()}'); window.location.href='profile.php';</script>";
            exit;
        }
    } else {
        echo "<script>alert('All fields are required.'); window.location.href='profile.php';</script>";
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Contact</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--===============================================================================================-->
    <link rel="icon" type="image/png" href="images/icons/favicon.png" />
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="fonts/iconic/css/material-design-iconic-font.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="fonts/linearicons-v1.0.0/icon-font.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/perfect-scrollbar/perfect-scrollbar.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="css/util.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <!--===============================================================================================-->
</head>

<body class="animsition">

    <!-- Header -->
    <header class="header-v4">
        <!-- Header desktop -->
        <div class="container-menu-desktop">
            <!-- Topbar -->
            <div class="top-bar">
                <div class="content-topbar flex-sb-m h-full container">
                    <div class="left-top-bar">

                    </div>
                </div>

                <div class="wrap-menu-desktop how-shadow1">
                    <nav class="limiter-menu-desktop container">

                        <!-- Logo desktop -->
                        <a href="#" class="logo">
                            <img src="images/icons/logo-01.png" alt="IMG-LOGO">
                        </a>

                        <!-- Menu desktop -->
                        <div class="menu-desktop">
                            <ul class="main-menu">
                                <li>
                                    <a href="index.php">Home</a>
                                    <ul class="sub-menu">
                                        <li><a href="index.php">Homepage 1</a></li>
                                        <li><a href="home-02.php">Homepage 2</a></li>
                                        <li><a href="home-03.php">Homepage 3</a></li>
                                    </ul>
                                </li>

                                <li>
                                    <a href="product.php">Shop</a>
                                </li>
                                <li>
                                    <a href="blog.php">Blog</a>
                                </li>

                                <li>
                                    <a href="about.php">About</a>
                                </li>

                                <li class="active-menu">
                                    <a href="contact.php">Contact</a>
                                </li>
                                <li>
                                    <?php if (!$user): ?>
                                        <a href="login_v2.php">Login</a>
                                    <?php else: ?>
                                        <span style="font-weight: bolder; color:#fa4251;">Hello,
                                            <?php echo $user['name']; ?>!</span>
                                    <?php endif; ?>
                                </li>
                                <li>
                                    <?php if ($user): ?>
                                        <a href="logout.php" style="color:#fa4251;">Logout</a>
                                    <?php endif; ?>
                                </li>
                                <li>
                                    <?php if ($user): ?>
                                        <a href="shopping-cart.php">
                                            <div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 icon-header-noti js-show-cart"
                                                data-notify="<?php echo $cartNumber ?>">
                                                <i class="zmdi zmdi-shopping-cart"></i>
                                            </div>
                                        </a>
                                    <?php else: ?>
                                        <div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 icon-header-noti js-show-cart"
                                            data-notify="0">
                                            <i class="zmdi zmdi-shopping-cart"></i>
                                        </div>
                                    <?php endif; ?>
                                </li>
                                <li>
                                    <?php if (!$user): ?>
                                        <a href="#">

                                            <div
                                                class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 js-show-modal-search">
                                                <i class="zmdi zmdi-search"></i>
                                            </div>
                                        </a>
                                    <?php else: ?>
                                        <div
                                            class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 js-show-modal-search">
                                            <i class="zmdi zmdi-search"></i>
                                        </div> <?php endif; ?>
                                </li>
                            </ul>
                        </div>


                </div>
            </div>

            <!-- Header Mobile -->
            <div class="wrap-header-mobile">
                <!-- Logo moblie -->
                <div class="logo-mobile">
                    <a href="index.php"><img src="images/icons/logo-01.png" alt="IMG-LOGO"></a>
                </div>

                <!-- Icon header -->
                <div class="wrap-icon-header flex-w flex-r-m m-r-15">
                    <div class="icon-header-item cl2 hov-cl1 trans-04 p-r-11 js-show-modal-search">
                        <i class="zmdi zmdi-search"></i>
                    </div>

                    <div class="icon-header-item cl2 hov-cl1 trans-04 p-r-11 p-l-10 icon-header-noti js-show-cart"
                        data-notify="2">
                        <i class="zmdi zmdi-shopping-cart"></i>
                    </div>

                    <a href="#" class="dis-block icon-header-item cl2 hov-cl1 trans-04 p-r-11 p-l-10 icon-header-noti"
                        data-notify="0">
                        <i class="zmdi zmdi-favorite-outline"></i>
                    </a>
                </div>

                <!-- Button show menu -->
                <div class="btn-show-menu-mobile hamburger hamburger--squeeze">
                    <span class="hamburger-box">
                        <span class="hamburger-inner"></span>
                    </span>
                </div>
            </div>


            <!-- Menu Mobile -->
            <div class="menu-mobile">
                <ul class="topbar-mobile">
                    <li>
                        <div class="left-top-bar">
                            Free shipping for standard order over $100
                        </div>
                    </li>

                    <li>
                        <div class="right-top-bar flex-w h-full">
                            <a href="#" class="flex-c-m p-lr-10 trans-04">
                                Help & FAQs
                            </a>

                            <a href="#" class="flex-c-m p-lr-10 trans-04">
                                My Account
                            </a>

                            <a href="#" class="flex-c-m p-lr-10 trans-04">
                                EN
                            </a>

                            <a href="#" class="flex-c-m p-lr-10 trans-04">
                                USD
                            </a>
                        </div>
                    </li>
                </ul>

                <ul class="main-menu-m">
                    <li>
                        <a href="index.php">Home</a>
                        <ul class="sub-menu-m">
                            <li><a href="index.php">Homepage 1</a></li>
                            <li><a href="home-02.php">Homepage 2</a></li>
                            <li><a href="home-03.php">Homepage 3</a></li>
                        </ul>
                        <span class="arrow-main-menu-m">
                            <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </span>
                    </li>

                    <li>
                        <a href="product.php">Shop</a>
                    </li>

                    <li>
                        <a href="shoping-cart.php" class="label1 rs1" data-label1="hot">Features</a>
                    </li>

                    <li>
                        <a href="blog.php">Blog</a>
                    </li>

                    <li>
                        <a href="about.php">About</a>
                    </li>

                    <li>
                        <a href="contact.php">Contact</a>
                    </li>
                </ul>
            </div>

            <!-- Modal Search -->
            <div class="modal-search-header flex-c-m trans-04 js-hide-modal-search">
                <div class="container-search-header">
                    <button class="flex-c-m btn-hide-modal-search trans-04 js-hide-modal-search">
                        <img src="images/icons/icon-close2.png" alt="CLOSE">
                    </button>

                    <form class="wrap-search-header flex-w p-l-15">
                        <button class="flex-c-m trans-04">
                            <i class="zmdi zmdi-search"></i>
                        </button>
                        <input class="plh3" type="text" name="search" placeholder="Search...">
                    </form>
                </div>
            </div>
    </header>

    <!-- Cart -->


    <!-- Title page -->
    <section class="bg-img1 txt-center p-lr-15 p-tb-92" style="background-image: url('images/bg-01.jpg');">
        <h2 class="ltext-105 cl0 txt-center">
            Profile
        </h2>
    </section>


    <!-- Content page -->


    <section class="bg0 p-t-104 p-b-116">
        <div style="margin: 0 15px;">
            <div class="flex-w flex-tr">
                <div class="size-210  p-lr-70 p-t-55 p-b-70 p-lr-15-lg w-full">
                    <form method="post" enctype="multipart/form-data" action="profile.php" class="clearfix">
                        <input hidden name="id" value="<?= htmlspecialchars(trim(string: $user['id'])) ?>">
                        <div class="bor8 m-b-20">
                            <input class="stext-111 cl2 plh3 size-116 p-l-28 p-r-30" type="text" name="name"
                                placeholder="Name" value="<?= htmlspecialchars(trim($user['name'])) ?>" required>
                        </div>

                        <!-- Username Field -->
                        <div class="bor8 m-b-20">
                            <input class="stext-111 cl2 plh3 size-116 p-l-28 p-r-30" type="text" name="username"
                                placeholder="Username" value="<?= htmlspecialchars($user['username']) ?>" required>
                        </div>

                        <!-- Password Field (Empty) -->
                        <div class="bor8 m-b-20">
                            <input class="stext-111 cl2 plh3 size-116 p-l-28 p-r-30" type="password" name="password"
                                placeholder="Password" required>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit"
                            class="flex-c-m stext-101 cl0 size-121 bg3 bor1 hov-btn3 p-lr-15 trans-04 pointer">
                            Update
                        </button>
                    </form>
                </div>


                <div style="margin-left: 2%;">
                    <table class="table table-bordered  table-responsive" id="table">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;">#</th>
                                <th>Product Name</th>
                                <th class="text-center" style="width: 15%;">Variation</th>
                                <th class="text-center" style="width: 15%;">Quantity</th>
                                <th class="text-center" style="width: 15%;">Total</th>
                                <th class="text-center" style="width: 15%;">Date</th>
                                <th class="text-center" style="width: 15%;">Status</th>
                                <th class="text-center" style="width: 15%;">Receipt</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($orders as $index => $order) {
                                $variation = $order['variation']; // Map variation
                                $total = number_format($order['qty'] * $order['price'], 2); // Calculate total
                                echo "<tr>
        <td class='text-center'>" . ($index + 1) . "</td>
        <td>{$order['product_name']}</td>
        <td class='text-center'>{$variation}</td>
        <td class='text-center'>{$order['qty']}</td>
        <td class='text-center'>₱{$total}</td>
        <td class='text-center'>{$order['date']}</td>
        <td class='text-center'>" . ($order["status"] == 1 ? 'Approve' : 'Pending') . "</td>
        <td class='text-center'><img style='width: 250px; height: 250px' src='" . $order['receipt'] . "'></td>
    </tr>";
                            }
                            ?>

                        </tbody>

                </div>
                </table>
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
                <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
                <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
                <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
                <script>
                    $(document).ready(function () {
                        $("#table").DataTable();
                    });
                </script>
            </div>
        </div>
    </section>





    <!-- Footer -->
    <footer class="bg3 p-t-75 p-b-32">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-lg-3 p-b-50">
                    <h4 class="stext-301 cl0 p-b-30">
                        Categories
                    </h4>

                    <ul>
                        <li class="p-b-10">
                            <a href="#" class="stext-107 cl7 hov-cl1 trans-04">
                                Women
                            </a>
                        </li>

                        <li class="p-b-10">
                            <a href="#" class="stext-107 cl7 hov-cl1 trans-04">
                                Men
                            </a>
                        </li>

                        <li class="p-b-10">
                            <a href="#" class="stext-107 cl7 hov-cl1 trans-04">
                                Shoes
                            </a>
                        </li>

                        <li class="p-b-10">
                            <a href="#" class="stext-107 cl7 hov-cl1 trans-04">
                                Watches
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="col-sm-6 col-lg-3 p-b-50">
                    <h4 class="stext-301 cl0 p-b-30">
                        Help
                    </h4>

                    <ul>
                        <li class="p-b-10">
                            <a href="#" class="stext-107 cl7 hov-cl1 trans-04">
                                Track Order
                            </a>
                        </li>

                        <li class="p-b-10">
                            <a href="#" class="stext-107 cl7 hov-cl1 trans-04">
                                Returns
                            </a>
                        </li>

                        <li class="p-b-10">
                            <a href="#" class="stext-107 cl7 hov-cl1 trans-04">
                                Shipping
                            </a>
                        </li>

                        <li class="p-b-10">
                            <a href="#" class="stext-107 cl7 hov-cl1 trans-04">
                                FAQs
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="col-sm-6 col-lg-3 p-b-50">
                    <h4 class="stext-301 cl0 p-b-30">
                        GET IN TOUCH
                    </h4>

                    <p class="stext-107 cl7 size-201">
                        Any questions? Let us know in store at 8th floor, 379 Hudson St, New York, NY 10018 or call us
                        on (+1) 96 716 6879
                    </p>

                    <div class="p-t-27">
                        <a href="#" class="fs-18 cl7 hov-cl1 trans-04 m-r-16">
                            <i class="fa fa-facebook"></i>
                        </a>

                        <a href="#" class="fs-18 cl7 hov-cl1 trans-04 m-r-16">
                            <i class="fa fa-instagram"></i>
                        </a>

                        <a href="#" class="fs-18 cl7 hov-cl1 trans-04 m-r-16">
                            <i class="fa fa-pinterest-p"></i>
                        </a>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3 p-b-50">
                    <h4 class="stext-301 cl0 p-b-30">
                        Newsletter
                    </h4>

                    <form>
                        <div class="wrap-input1 w-full p-b-4">
                            <input class="input1 bg-none plh1 stext-107 cl7" type="text" name="email"
                                placeholder="email@example.com">
                            <div class="focus-input1 trans-04"></div>
                        </div>

                        <div class="p-t-18">
                            <button class="flex-c-m stext-101 cl0 size-103 bg1 bor1 hov-btn2 p-lr-15 trans-04">
                                Subscribe
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="p-t-40">
                <div class="flex-c-m flex-w p-b-18">
                    <a href="#" class="m-all-1">
                        <img src="images/icons/icon-pay-01.png" alt="ICON-PAY">
                    </a>

                    <a href="#" class="m-all-1">
                        <img src="images/icons/icon-pay-02.png" alt="ICON-PAY">
                    </a>

                    <a href="#" class="m-all-1">
                        <img src="images/icons/icon-pay-03.png" alt="ICON-PAY">
                    </a>

                    <a href="#" class="m-all-1">
                        <img src="images/icons/icon-pay-04.png" alt="ICON-PAY">
                    </a>

                    <a href="#" class="m-all-1">
                        <img src="images/icons/icon-pay-05.png" alt="ICON-PAY">
                    </a>
                </div>

                <p class="stext-107 cl6 txt-center">
                    <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                    Copyright &copy;
                    <script>document.write(new Date().getFullYear());</script> All rights reserved | Made with <i
                        class="fa fa-heart-o" aria-hidden="true"></i> by <a href="https://colorlib.com"
                        target="_blank">Colorlib</a> &amp; distributed by <a href="https://themewagon.com"
                        target="_blank">ThemeWagon</a>
                    <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->

                </p>
            </div>
        </div>
    </footer>


    <!-- Back to top -->
    <div class="btn-back-to-top" id="myBtn">
        <span class="symbol-btn-back-to-top">
            <i class="zmdi zmdi-chevron-up"></i>
        </span>
    </div>

    <!--===============================================================================================-->
    <!--===============================================================================================-->
    <script src="vendor/animsition/js/animsition.min.js"></script>
    <!--===============================================================================================-->
    <script src="vendor/bootstrap/js/popper.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <!--===============================================================================================-->
    <script src="vendor/select2/select2.min.js"></script>
    <script>
        $(".js-select2").each(function () {
            $(this).select2({
                minimumResultsForSearch: 20,
                dropdownParent: $(this).next('.dropDownSelect2')
            });
        })
    </script>
    <!--===============================================================================================-->
    <script src="vendor/MagnificPopup/jquery.magnific-popup.min.js"></script>
    <!--===============================================================================================-->
    <script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script>
        $('.js-pscroll').each(function () {
            $(this).css('position', 'relative');
            $(this).css('overflow', 'hidden');
            var ps = new PerfectScrollbar(this, {
                wheelSpeed: 1,
                scrollingThreshold: 1000,
                wheelPropagation: false,
            });

            $(window).on('resize', function () {
                ps.update();
            })
        });
    </script>
    <!--===============================================================================================-->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAKFWBqlKAGCeS1rMVoaNlwyayu0e0YRes"></script>
    <script src="js/map-custom.js"></script>
    <!--===============================================================================================-->
    <script src="js/main.js"></script>

</body>

</html>