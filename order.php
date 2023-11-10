<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
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
    <script>svg4everybody();</script><!-- font - fontawesome -->
    <link rel="stylesheet" href="vendor/fontawesome-5.6.1/css/all.min.css"><!-- font - stroyka -->
    <link rel="stylesheet" href="fonts/stroyka/stroyka.css">
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-97489509-6"></script>
    <script>window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());
        gtag('config', 'UA-97489509-6');</script>
</head>
<body>
<div class="site">

    <?php @include 'header.php'; ?>

    <div class="site__body">
        <div class="page-header">
            <div class="page-header__container container">
                <div class="page-header__breadcrumb">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="book.php">Home</a>
                                <svg class="breadcrumb-arrow" width="6px" height="9px">
                                    <use xlink:href="images/sprite.svg#arrow-rounded-right-6x9"></use>
                                </svg>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Order</li>
                        </ol>
                    </nav>
                </div>
                <div class="page-header__title"><h1>Place Order</h1></div>
            </div>
        </div>
        <div class="cart block">
            <div class="container">
                <table class="cart__table cart-table">
                    <thead class="cart-table__head">
                    <tr class="cart-table__row">
                        <th class="cart-table__column ">Place on</th>
                        <th class="cart-table__column ">Name</th>
                        <th class="cart-table__column ">Number</th>
                        <th class="cart-table__column ">Email</th>
                        <th class="cart-table__column ">Address</th>
                        <th class="cart-table__column ">Method</th>
                        <th class="cart-table__column ">Total Product</th>
                        <th class="cart-table__column ">Total Price</th>
                        <th class="cart-table__column ">Payment Status</th>
                    </tr>
                    </thead>
                    <tbody class="cart-table__body">
                    <?php
                    $select_orders = mysqli_query($conn, "SELECT * FROM `orders` WHERE user_id = '$user_id'") or die('query failed');
                    if (mysqli_num_rows($select_orders) > 0) {
                        while ($fetch_orders = mysqli_fetch_assoc($select_orders)) {
                            $json = json_decode($fetch_orders['total_products'], true);
                            ?>
                            <tr class="cart-table__row">
                                <td class="cart-table__column"><?php echo $fetch_orders['placed_on']; ?></td>
                                <td class="cart-table__column"><?php echo $fetch_orders['name']; ?></td>
                                <td class="cart-table__column"><?php echo $fetch_orders['number']; ?></td>
                                <td class="cart-table__column"><?php echo $fetch_orders['email']; ?></td>
                                <td class="cart-table__column"><?php echo $fetch_orders['address']; ?></td>
                                <td class="cart-table__column"><?php echo $fetch_orders['method']; ?></td>
                                <td class="cart-table__column">
                                    <?php
                                    foreach ($json as $product) {
                                        echo $product['name'] . ' (' . $product['quantity'] . ')<br>';
                                    }
                                    ?>
                                </td>
                                <td class="cart-table__column"><?php echo $fetch_orders['total_price']; ?></td>
                                <td class="cart-table__column"><?php echo $fetch_orders['payment_status']; ?></td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo '<p class="empty">no orders placed yet!</p>';
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php @include 'footer.php'; ?>
</div><!-- site / end -->
</body>

</html>

