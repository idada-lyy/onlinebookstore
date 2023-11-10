<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
};

if (isset($_POST['order'])) {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $number = mysqli_real_escape_string($conn, $_POST['number']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $method = mysqli_real_escape_string($conn, $_POST['method']);
    $address = mysqli_real_escape_string($conn, $_POST['address_line1'] . ', ' . $_POST['address_line2'] . ', ' . $_POST['address_line3'] . ', ' . $_POST['state'] . ', ' . $_POST['city'] . ', ' . $_POST['country'] . ' - ' . $_POST['postcode']);
    $placed_on = date('d-M-Y');

    $cart_total = 10;
    $cart_products = '';

    $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
    if (mysqli_num_rows($cart_query) > 0) {
        while ($cart_item = mysqli_fetch_assoc($cart_query)) {
            $cart_products .= '{ "name":"' . $cart_item['name'] . '", "quantity":' . $cart_item['quantity'] . ', "genre": "' . $cart_item['genre'] . '"}, ';
            $sub_total = ($cart_item['price'] * $cart_item['quantity']);
            $cart_total += $sub_total;
        }
    }

    $total_products = '[' . rtrim($cart_products, ', ') . ']';

    $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE name = '$name' AND number = '$number' AND email = '$email' AND method = '$method' AND address = '$address' AND total_products = '$total_products' AND total_price = '$cart_total'") or die('query failed');

    if ($cart_total == 0) {
        $message[] = 'your cart is empty!';
    } elseif (mysqli_num_rows($order_query) > 0) {
        $message[] = 'order placed already!';
    } else {
        mysqli_query($conn, "INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price, placed_on) VALUES('$user_id', '$name', '$number', '$email', '$method', '$address', '$total_products', '$cart_total', '$placed_on')") or die('query failed');
        mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
        $message[] = 'order placed successfully!';
    }
}

?>


<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="format-detection" content="telephone=no">
    <title>Stroyka</title>
    <link rel="icon" type="image/png" href="images/favicon.png"><!-- fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,400i,500,500i,700,700i"><!-- css -->
    <link rel="stylesheet" href="vendor/bootstrap-4.2.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/owl-carousel-2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="css/style.css"><!-- js -->
    <script src="vendor/jquery-3.3.1/jquery.min.js"></script>
    <script src="vendor/bootstrap-4.2.1/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/owl-carousel-2.3.4/owl.carousel.min.js"></script>
    <script src="vendor/nouislider-12.1.0/nouislider.min.js"></script>
    <script src="js/number.js"></script>
    <script src="js/main.js"></script>
    <script src="vendor/svg4everybody-2.1.9/svg4everybody.min.js"></script>
    <script>
        svg4everybody();
    </script><!-- font - fontawesome -->
    <link rel="stylesheet" href="vendor/fontawesome-5.6.1/css/all.min.css"><!-- font - stroyka -->
    <link rel="stylesheet" href="fonts/stroyka/stroyka.css">
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-97489509-6"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());
        gtag('config', 'UA-97489509-6');
    </script>
</head>

<body>

<div class="site">

    <?php @include 'header.php'; ?>
    <form action="" method="POST">
        <div class="site__body">
            <div class="page-header">
                <div class="page-header__container container">
                    <div class="page-header__breadcrumb">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="book.php">Home</a>
                                    <svg class="breadcrumb-arrow"
                                         width="6px" height="9px">
                                        <use xlink:href="images/sprite.svg#arrow-rounded-right-6x9"></use>
                                    </svg>
                                </li>
                                <li class="breadcrumb-item"><a href="#">Breadcrumb</a>
                                    <svg class="breadcrumb-arrow"
                                         width="6px" height="9px">
                                        <use xlink:href="images/sprite.svg#arrow-rounded-right-6x9"></use>
                                    </svg>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Checkout</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="page-header__title">
                        <h1>Checkout</h1>
                    </div>
                </div>
            </div>

            <div class="checkout block">
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-lg-6 col-xl-7">
                            <div class="card mb-lg-0">
                                <div class="card-body">
                                    <h3 class="card-title">Billing details</h3>
                                    <div class="form-row">
                                        <div class="form-group col-md-12"><label for="checkout-first-name">Full Name
                                                Name</label> <input type="text" class="form-control" name="name"
                                                                    id="checkout-first-name" placeholder="Full Name">
                                        </div>
                                    </div>
                                    <div class="form-group"><label for="checkout-country">Country</label>
                                        <select name="country"
                                                id="checkout-country" class="form-control">
                                            <option>Select a country...</option>
                                            <option>United States</option>
                                            <option>Russia</option>
                                            <option>Italy</option>
                                            <option>France</option>
                                            <option>Ukraine</option>
                                            <option>Germany</option>
                                            <option>Australia</option>
                                        </select></div>
                                    <div class="form-group"><label for="checkout-city">City</label> <input
                                                type="text" class="form-control" name="city" id="checkout-city"
                                                placeholder="City"></div>

                                    <div class="form-group"><label for="checkout-street-address">State</label>
                                        <input type="text" class="form-control" id="checkout-street-address"
                                               name="state"
                                               placeholder="State"></div>
                                    <div class="form-group"><label for="checkout-street-address">Postcode</label>
                                        <input type="text" class="form-control" id="checkout-street-address"
                                               name="postcode"
                                               placeholder="Postcode"></div>

                                    <div class="form-group"><label for="checkout-state">Address</label>
                                        <input type="text" class="form-control" id="checkout-state"
                                               name="address_line1">
                                        <input type="text" class="form-control" id="checkout-state"
                                               name="address_line2">
                                        <input type="text" class="form-control" id="checkout-state"
                                               name="address_line3">
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6"><label for="checkout-email">Email
                                                address</label>
                                            <input type="email" class="form-control" name="email"
                                                   id="checkout-email" placeholder="Email address">
                                        </div>
                                        <div class="form-group col-md-6"><label for="checkout-phone">Phone</label>
                                            <input type="text" class="form-control" id="checkout-phone" name="number"
                                                   placeholder="Phone"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6 col-xl-5 mt-4 mt-lg-0">
                            <div class="card mb-0">
                                <div class="card-body">
                                    <h3 class="card-title">Your Order</h3>
                                    <table class="checkout__totals">
                                        <thead class="checkout__totals-header">
                                        <tr>
                                            <th>Product</th>
                                            <th>Total</th>
                                        </tr>
                                        </thead>
                                        <tbody class="checkout__totals-products">
                                        <?php
                                        $grand_total = 0;
                                        $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
                                        if (mysqli_num_rows($select_cart) > 0) {
                                            while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
                                                $total_price = ($fetch_cart['price'] * $fetch_cart['quantity']);
                                                $grand_total += $total_price;
                                                ?>
                                                <tr>
                                                    <td><?php echo $fetch_cart['name'] ?> ×
                                                        <?php echo $fetch_cart['quantity'] ?></td>
                                                    <td>RM<?php echo $fetch_cart['price'] ?></td>
                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            echo '<p class="empty">your cart is empty</p>';
                                        }
                                        ?>
                                        </tbody>

                                        <?php
                                        $grand_total = 0;
                                        $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
                                        if (mysqli_num_rows($select_cart) > 0) {
                                            while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
                                                $total_price = ($fetch_cart['price'] * $fetch_cart['quantity']);
                                                $grand_total += $total_price;
                                            }
                                            ?>
                                            <tbody class="checkout__totals-subtotals">
                                            <tr>
                                                <th>Subtotal</th>
                                                <td>RM<?php echo $grand_total ?></td>
                                            </tr>
                                            <!--                                        <tr>-->
                                            <!--                                            <th>Store Credit</th>-->
                                            <!--                                            <td>$-20.00</td>-->
                                            <!--                                        </tr>-->
                                            <tr>
                                                <th>Shipping Fee</th>
                                                <td>RM10</td>
                                            </tr>
                                            </tbody>
                                            <tfoot class="checkout__totals-footer">
                                            <tr>
                                                <th>Total</th>
                                                <td>RM<?php echo $grand_total + 10 ?></td>
                                            </tr>
                                            </tfoot>

                                            <?php
                                        }
                                        ?>
                                    </table>
                                    <div class="payment-methods">
                                        <div class="form-group"><label for="checkout-city">Payment Method</label>
                                            <select name="method" id="checkout-country" class="form-control" required>
                                                <option value="">Select a Payment Method...</option>
                                                <option value="Direct bank transfer">Direct bank transfer</option>
                                                <option value="Check payments">Check payments</option>
                                                <option value="Cash on delivery">Cash on delivery</option>
                                                <option value="PayPal">PayPal</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="checkout__agree form-group">
                                        <div class="form-check"><span class="form-check-input input-check"><span
                                                        class="input-check__body"><input class="input-check__input"
                                                                                         type="checkbox"
                                                                                         id="checkout-terms"> <span
                                                            class="input-check__box"></span> <svg
                                                            class="input-check__icon"
                                                            width="9px" height="7px">
                                                        <use xlink:href="images/sprite.svg#check-9x7"></use>
                                                    </svg> </span></span><label class="form-check-label"
                                                                                for="checkout-terms">I have read and
                                                agree to the website <a
                                                        target="_blank" href="terms-and-conditions.html">terms and
                                                    conditions</a>*</label></div>
                                    </div>
                                    <input type="submit" name="order" value="order now"
                                           class="btn btn-primary btn-xl btn-block">Place
                                    Order
                                    </input>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <?php @include 'footer.php'; ?>

</div><!-- site / end -->
</body>

</html>

