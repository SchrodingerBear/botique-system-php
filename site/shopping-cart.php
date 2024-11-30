<?php

ob_start();
require_once('include/load.php');
require_once("functions/dashboard.php");
$data = getCategory();
$getallProduct = getAllProducts();
$distProduct = getAllProducts();
$getImage = "";
$user = current_user();
$uniqueData = array();

if (empty($cartData)) {
	echo "No items in cart.<br>";
} else {
	// echo '<pre>';
	// var_dump($cartData);
	// echo '</pre>';
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$coupon = $_POST['coupon'] ?? ''; // coupon code, if any
	$address = $_POST['address'] ?? ''; // Customer address
	$delivery_type = $_POST['delivery_type'] ?? ''; // Delivery type (0 for COD, 1 for Online Payment)
	$receipt_path = ''; // Default value if no receipt is uploaded

	// Handle receipt image upload only if delivery_type is 1 (Online Payment)
	if ($delivery_type == '1') {
		if (isset($_FILES['receipt'])) {
			$receipt = $_FILES['receipt'];
			if ($receipt['error'] === UPLOAD_ERR_OK) {
				$upload_dir = '../uploads/receipt/';
				$upload_file = $upload_dir . basename($receipt['name']);

				// Move the uploaded file to the target directory
				if (move_uploaded_file($receipt['tmp_name'], $upload_file)) {
					$receipt_path = $upload_file; // Store the file path
				} else {
					echo 'Error uploading receipt image.<br>';
				}
			} else {
				echo 'Error uploading receipt image.<br>';
			}
		}
	}

	// Insert cart items into the sales table
	foreach ($cartData as $prod) {
		$quantity = $prod['quantity'];
		$price = $prod['price'];
		$color = $prod['color'];
		$size = $prod['size'];
		$variation = $color . ',' . $size; // Combining color and size for variation
		$date = date('Y-m-d H:i:s'); // Current timestamp

		// Debugging: Log each cart item details
		echo "Processing product: " . $prod['name'] . " with quantity: {$quantity}, price: {$price}, color: {$color}, size: {$size}<br>";

		// Find the product ID from the products table based on name, size, and color
		$productQuery = "
            SELECT id FROM products 
            WHERE name = '{$prod['name']}' 
            AND size = '{$size}' 
            AND color = '{$color}'
            LIMIT 1
        ";

		$result = $db->query($productQuery);

		if ($result && $result->num_rows > 0) {
			$product = $result->fetch_assoc();
			$product_id = $product['id'];

			// Insert into sales table with address, delivery_type, and receipt path
			$sql = "
                INSERT INTO sales (userid, product_id, qty, price, variation, date, receipt, address, type)
                VALUES ({$_SESSION['user_id']}, {$product_id}, {$quantity}, {$price}, '{$variation}', '{$date}', '{$receipt_path}', '{$address}', '{$delivery_type}')
            ";

			if ($db->query($sql)) {
				echo "Successfully inserted into sales for " . $prod['name'] . "<br>";

				// Update the product quantity in the products table
				$sql_update_product = "
                    UPDATE products 
                    SET quantity = quantity - {$quantity}
                    WHERE name = '{$prod['name']}' 
                    AND color = '{$color}' 
                    AND size = '{$size}'
                ";

				if ($db->query($sql_update_product)) {
					echo "Product quantity updated successfully for " . $prod['name'] . "<br>";
				} else {
					echo "Error updating product quantity for " . $prod['name'] . "<br>";
				}
			} else {
				echo "Error inserting into sales table for product " . $prod['name'] . ": " . $db->error . "<br>";
			}
		} else {
			echo "Error: Product not found for " . $prod['name'] . "<br>";
		}
	}

	$sql_clear_cart = "
	    DELETE FROM cart 
	    WHERE user_id = {$_SESSION['user_id']}
	";

	if ($db->query($sql_clear_cart)) {
		header('Location: profile.php');
		exit();
	} else {
		echo "Error clearing the cart.<br>";
	}
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
	<title>Shoping Cart</title>
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
						Free shipping for standard order over $100
					</div>

					<div class="right-top-bar flex-w h-full">
						<a href="#" class="flex-c-m trans-04 p-lr-25">
							Help & FAQs
						</a>

						<a href="#" class="flex-c-m trans-04 p-lr-25">
							My Account
						</a>

						<a href="#" class="flex-c-m trans-04 p-lr-25">
							EN
						</a>

						<a href="#" class="flex-c-m trans-04 p-lr-25">
							USD
						</a>
					</div>
				</div>
			</div>

			<div class="wrap-menu-desktop how-shadow1">
				<nav class="limiter-menu-desktop container">

					<!-- Logo desktop -->
					<a href="index.php" class="logo">
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

							<li>
								<a href="contact.php">Contact</a>
							</li>
						</ul>
					</div>

					<!-- Icon header -->
					<div class="wrap-icon-header flex-w flex-r-m">
						<div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 js-show-modal-search">
							<i class="zmdi zmdi-search"></i>
						</div>
						<div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 icon-header-noti js-show-carts"
							data-notify="<?php echo $cartNumber ?>">
							<i class="zmdi zmdi-shopping-cart"></i>
						</div>


						<a href="#" class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 icon-header-noti"
							data-notify="0">
							<i class="zmdi zmdi-favorite-outline"></i>
						</a>
					</div>
				</nav>
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

				<div class="icon-header-item cl2 hov-cl1 trans-04 p-r-11 p-l-10 icon-header-noti js-show-carts"
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
	<div class="wrap-header-cart js-panel-cart">
		<div class="s-full js-hide-carts"></div>

		<div class="header-cart flex-col-l p-l-65 p-r-25">
			<div class="header-cart-title flex-w flex-sb-m p-b-8">
				<span class="mtext-103 cl2">
					Your Cart
				</span>

				<div class="fs-35 lh-10 cl2 p-lr-5 pointer hov-cl1 trans-04 js-hide-carts">
					<i class="zmdi zmdi-close"></i>
				</div>
			</div>

			<div class="header-cart-content flex-w js-pscroll">
				<ul class="header-cart-wrapitem w-full">
					<?php
					$totalPrice1 = 0;
					foreach ($cartData as $prod):
						if ($prod['user_id'] === $user['id']) {
							$totalPrice1 += ($prod['price'] * $prod['quantity']);
						}

						?>
						<li class="header-cart-item flex-w flex-t m-b-12">
							<div class="header-cart-item-img">
								<?php 								// return print_r($dat);
									if ($prod['image_id'] === '0'): ?>
									<img src="../uploads/products/no_image.jpg" alt="">
								<?php else:
										?>
									<img src="../uploads/products/<?php echo getImages($prod['image_id'])['file_name']; ?>"
										alt="">
								<?php endif; ?>
							</div>

							<div class="header-cart-item-txt p-t-8">
								<a href="#" class="header-cart-item-name m-b-18 hov-cl1 trans-04">
									<?php echo $prod['name']; ?>
								</a>

								<span class="header-cart-item-info">
									<?php echo $prod['quantity']; ?> x ₱<?php echo $prod['price']; ?>.00
								</span>
							</div>
						</li>
					<?php endforeach; ?>

				</ul>

				<div class="w-full">
					<div class="header-cart-total w-full p-tb-40">
						Total: ₱<?php echo $totalPrice1; ?>.00
					</div>

					<div class="header-cart-buttons flex-w w-full">
						<a href="shopping-cart.php"
							class="flex-c-m stext-101 cl0 size-107 bg3 bor2 hov-btn3 p-lr-15 trans-04 m-r-8 m-b-10">
							View Cart
						</a>

						<a href="shopping-cart.php"
							class="flex-c-m stext-101 cl0 size-107 bg3 bor2 hov-btn3 p-lr-15 trans-04 m-b-10">
							Check Out
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>


	<!-- breadcrumb -->
	<div class="container">
		<div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
			<a href="index.php" class="stext-109 cl8 hov-cl1 trans-04">
				Home
				<i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
			</a>

			<span class="stext-109 cl4">
				Shoping Cart
			</span>
		</div>
	</div>

	<input type="hidden" class="user_ids" value="<?php echo $user['id'] ?>" />

	<!-- Shoping Cart -->

	<form class="bg0 p-t-75 p-b-85" action="shopping-cart.php" method="POST" enctype="multipart/form-data">
		<input type="hidden" name="cart_data" value="<?php echo htmlentities(json_encode($cartData)); ?>" />
		<input type="hidden" name="total" value="<?php echo htmlentities(json_encode($totalPrice1)); ?>" />

		<div class="container">
			<div class="row">
				<div class="col-lg-10 col-xl-7 m-lr-auto m-b-50">
					<div class="m-l-25 m-r--38 m-lr-0-xl">
						<div class="wrap-table-shopping-cart">

							<table class="table-shopping-cart">
								<tr class="table_head">
									<th>Product</th>
									<th></th>
									<th>Price</th>
									<th>Quantity</th>
									<th>Size</th>
									<th>Color</th>
									<th>Total</th>
								</tr>

								<?php foreach ($cartData as $prod): ?>
									<tr class="table_row">
										<td class="column-1">
											<div class="how-itemcart1">
												<?php if ($prod['image_id'] === '0'): ?>
													<img src="../uploads/products/no_image.jpg" alt="">
												<?php else: ?>
													<img src="../uploads/products/<?php echo getImages($prod['image_id'])['file_name']; ?>"
														alt="">
												<?php endif; ?>
											</div>
										</td>
										<td class="column-2"><?php echo $prod['name']; ?></td>
										<td class="column-3"> ₱ <?php echo $prod['price']; ?></td>
										<td class="column-4">
											<input type="hidden" class="cart<?php echo $prod['id']; ?>"
												value="<?php echo $prod['id']; ?>" />
											<div class="wrap-num-product flex-w m-l-auto m-r-0">
												<div
													class="btn-num-product-down btn-num-product-down<?php echo $prod['id']; ?> cl8 hov-btn3 trans-04 flex-c-m">
													<i class="fs-16 zmdi zmdi-minus"></i>
												</div>

												<input
													class="mtext-104 cl3 txt-center num-product quantity_<?php echo $prod['id']; ?>"
													type="number" name="num-product1"
													value="<?php echo $prod['quantity']; ?>">

												<div
													class="btn-num-product-up btn-num-product-up<?php echo $prod['id']; ?> cl8 hov-btn3 trans-04 flex-c-m">
													<i class="fs-16 zmdi zmdi-plus"></i>
												</div>
											</div>
										</td>
										<input type="hidden" class="price<?php echo $prod['id']; ?>"
											value="<?php echo $prod['price'] ?>">
										<td class="column-5 "><span class="total"><?php echo $prod['size'] ?></span></td>
										<td class="column-5 "><span class="total"><?php echo $prod['color'] ?></span></td>
										<td class="column-5 ">₱ <span
												class="total<?php echo $prod['id'] ?>"><?php echo (int) $prod['price'] * (int) $prod['quantity']; ?></span>.00
										</td>
									</tr>
								<?php endforeach; ?>

							</table>
						</div>

						<div class="flex-w flex-sb-m bor15 p-t-18 p-b-15 p-lr-40 p-lr-15-sm">
							<div class="flex-w flex-m m-r-20 m-tb-5">
								<input class="stext-104 cl2 plh4 size-117 bor13 p-lr-20 m-r-10 m-tb-5" type="text"
									name="coupon" placeholder="Coupon Code">
								<div
									class="flex-c-m stext-101 cl2 size-118 bg8 bor13 hov-btn3 p-lr-15 trans-04 pointer m-tb-5">
									Apply coupon
								</div>
							</div>

							<div class="flex-c-m stext-101 cl2 size-119 bg8 bor13 hov-btn3 p-lr-15 trans-04 pointer m-tb-10"
								id="carts">
								Update Cart
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-10 col-lg-7 col-xl-5 m-lr-auto m-b-50">
					<div class="bor10 p-lr-40 p-t-30 p-b-40 m-l-63 m-r-40 m-lr-0-xl p-lr-15-sm">
						<h4 class="mtext-109 cl2 p-b-30">Cart Totals</h4>
						<div class="flex-w flex-t bor12 p-b-13">
							<div class="size-208">
								<span class="stext-110 cl2">Subtotal:</span>
							</div>
							<div class="size-209">
								<span class="mtext-110 cl2">₱<span class="maintotal">0</span>.00</span>
							</div>
						</div>

						<div class="flex-w flex-t p-t-27 p-b-33">
							<div class="size-208">
								<span class="mtext-101 cl2">Total:</span>
							</div>
							<div class="size-209 p-t-1">
								₱<span class="mtext-110 cl2 checkouttext"><?php echo $totalPrice1 ?></span>.00
							</div>
						</div>

						<!-- Address Input -->
						<div class="m-b-15">
							<label for="address" class="stext-110 cl2">Delivery Address:</label>
							<textarea name="address" id="address" class="size-110 cl2 bor13 p-lr-20 m-tb-5"
								required></textarea>
						</div>

						<div class="m-b-15">
							<label class="stext-110 cl2">Delivery Type:</label>
							<select name="delivery_type" id="delivery_type" class="size-117 cl2 bor13 p-lr-20 m-tb-5"
								required>
								<option value="1">Online Payment</option>
								<option value="0">Cash on Delivery</option>
							</select>
						</div>

						<!-- Upload Receipt -->
						<div id="receipt-section" class="flex-w flex-c m-b-15">
							<label for="receipt" class="stext-110 cl2">Upload Receipt:</label>
							<input type="file" name="receipt" id="receipt" class="stext-104 cl2 bor13 p-lr-20 m-tb-5">
						</div>



						<button type="submit"
							class="flex-c-m stext-101 cl0 size-116 bg3 bor14 hov-btn3 p-lr-15 trans-04 pointer">
							Proceed to Checkout
						</button>
					</div>
				</div>
			</div>
		</div>
	</form>

	<script>
		const deliveryTypeSelect = document.getElementById('delivery_type');
		const receiptSection = document.getElementById('receipt-section');

		deliveryTypeSelect.addEventListener('change', function () {
			if (this.value === '0') { // COD selected
				receiptSection.style.display = 'none';
			} else { // Online payment selected
				receiptSection.style.display = 'flex';
			}
		});
	</script>



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
					<script>
						document.write(new Date().getFullYear());
					</script> All rights reserved | Made with <i class="fa fa-heart-o" aria-hidden="true"></i> by <a
						href="https://colorlib.com" target="_blank">Colorlib</a> &amp; distributed by <a
						href="https://themewagon.com" target="_blank">ThemeWagon</a>
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
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
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
	<script src="js/main.js"></script>
	<?php
	$totalPrice = 0;
	foreach ($cartData as $prod): ?>
		<?php

		if ($prod['user_id'] === $user['id']) {
			$totalPrice += ($prod['price'] * $prod['quantity']);
		}

		?>
		<script>
			$('.btn-num-product-down<?php echo $prod['id']; ?>').on('click', function () {
				let numProduct = $('.quantity_<?php echo $prod['id']; ?>').val();
				if (numProduct > 0) {
					$('.quantity_<?php echo $prod['id']; ?>').val(numProduct -= 1);
					let price = $('.price<?php echo $prod['id'] ?>').val()
					if (numProduct > 0) {
						let formData = {
							id: $('.user_ids').val(),
							number: numProduct,
							cartId: $('.cart<?php echo $prod['id']; ?>').val()
						}
						console.log(formData)
						fetch('../add_quantity.php', {
							method: 'POST', // Set the method to POST
							headers: {
								'Content-Type': 'application/json' // Send as JSON
							},
							body: JSON.stringify(formData)
						}).then(res => res.json())
							.then(data => {

								$('.total<?php echo $prod['id'] ?>').text(price * $('.quantity_<?php echo $prod['id']; ?>').val())
								console.log(data)
							}).catch(err => console.log("Erros", err))

					}
				}
			});

			$('.btn-num-product-up<?php echo $prod['id']; ?>').on('click', function () {
				let numProduct = $('.quantity_<?php echo $prod['id']; ?>').val();
				let output = eval(numProduct) + 1;
				let price = $('.price<?php echo $prod['id'] ?>').val()
				$('.quantity_<?php echo $prod['id']; ?>').val(output)

				if (output > 0) {
					let formData = {
						id: $('.user_ids').val(),
						number: output,
						cartId: $('.cart<?php echo $prod['id']; ?>').val()
					}
					fetch('../add_quantity.php', {
						method: 'POST', // Set the method to POST
						headers: {
							'Content-Type': 'application/json' // Send as JSON
						},
						body: JSON.stringify(formData) // Convert data to JSON string
					}).then(res => res.json())

						.then(data => {
							$('.total<?php echo $prod['id'] ?>').text(price * $('.quantity_<?php echo $prod['id']; ?>').val())

							console.log(data)
						}).catch(err => console.log("Erros", err))

				}
			});

			$('#carts').click(() => {
				$('.maintotal').text(<?php echo $totalPrice; ?>);
			})
		</script>
	<?php endforeach; ?>
	<script>
		$('.updatebtm').click(() => {
			$(".checkouttext").text(<?php echo $totalPrice; ?> + 65)
		})
	</script>
</body>

</html>