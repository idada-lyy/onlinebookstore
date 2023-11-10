<?php

@include '../config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

//if (!issetet($admin_id)) {
//    header('location:./admin/login.php');
//};

if (isset($_POST['update_order'])) {
    $order_id = $_POST['order_id'];
    $update_payment = $_POST['update_payment'];
    mysqli_query($conn, "UPDATE `orders` SET payment_status = '$update_payment' WHERE id = '$order_id'") or die('query failed');
    $message[] = 'payment status has been updated!';
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM `orders` WHERE id = '$delete_id'") or die('query failed');
    header('location:../admin/order.php');
}

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="format-detection" content="telephone=no">
    <title>Stroyka</title>
    <link rel="icon" type="/image/png" href="../images/favicon.png"><!-- fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,400i,500,500i,700,700i"><!-- css -->
    <link rel="stylesheet" href="../vendor/bootstrap-4.2.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="../vendor/owl-carousel-2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="../css/style.css"><!-- js -->
    <script src="../vendor/jquery-3.3.1/jquery.min.js"></script>
    <script src="../vendor/bootstrap-4.2.1/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/owl-carousel-2.3.4/owl.carousel.min.js"></script>
    <script src="../vendor/nouislider-12.1.0/nouislider.min.js"></script>
    <script src="../js/number.js"></script>
    <script src="../js/main.js"></script>
    <script src="../vendor/svg4everybody-2.1.9/svg4everybody.min.js"></script>
    <script>svg4everybody();</script><!-- font - fontawesome -->
    <link rel="stylesheet" href="../vendor/fontawesome-5.6.1/css/all.min.css"><!-- font - stroyka -->
    <link rel="stylesheet" href="../fonts/stroyka/stroyka.css">
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
    <!---->
        <?php @include '../admin/header.php'; ?>

    <div class="site__body">
        <div class="page-header">
            <div class="page-header__container container">
                <div class="page-header__title"><h1>Order List</h1></div>
            </div>
        </div>
        <div class="cart block">
            <div class="container">
                <table class="table">
                    <thead>
                    <tr>
                        <th class="">Place on</th>
                        <th class="">Name</th>
                        <th class="">Number</th>
                        <th class="">Email</th>
                        <th class="">Address</th>
                        <th class="">Method</th>
                        <th class="">Total Product</th>
                        <th class="">Total Price</th>
                        <th class="">Payment Status</th>
                        <th class="">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $select_orders = mysqli_query($conn, "SELECT * FROM `orders`") or die('query failed');
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
                                <form action="" method="post">
                                    <td class="cart-table__column">
                                        <input type="hidden" name="order_id" value="<?php echo $fetch_orders['id']; ?>">
                                        <select name="update_payment" required>
                                            <option disabled
                                                    selected><?php echo $fetch_orders['payment_status']; ?></option>
                                            <option value="pending">pending</option>
                                            <option value="completed">completed</option>
                                        </select>
                                    </td>
                                    <td class="cart-table__column">
                                        <input type="submit" name="update_order" value="update" class="btn btn-primary">
                                        <a href="./order.php?delete=<?php echo $fetch_orders['id']; ?>"
                                           class="btn btn-danger" onclick="return confirm('delete this order?');">delete</a>
                                    </td>
                                </form>
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
</div>
</div><!-- site / end -->
</body>

</html>