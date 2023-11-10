<?php

@include '../config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
};

if (isset($_POST['update_cart'])) {
    $cart_id = $_POST['cart_id'];
    $quantity = $_POST['quantity'];
    mysqli_query($conn, "UPDATE `cart` SET quantity = '$quantity' WHERE id = '$cart_id'") or die('query failed');
    $message[] = 'cart has been updated!';
}


if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$delete_id'") or die('query failed');
    header('location:../admin/cart.php');

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
                <div class="page-header__title">
                    <h1>Cart List</h1>
                </div>
                <a href="cart_create.php" class="btn btn-primary">Create Cart</a>
            </div>
        </div>
        <div class="cart block">
            <div class="container">
                <table class="table">
                    <thead>
                    <tr>
                        <th class="">Created By</th>
                        <th class="">Book Name</th>
                        <th class="">Price</th>
                        <th class="">Quantity</th>
                        <th class="">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $select_carts = mysqli_query($conn, "SELECT * FROM `cart`") or die('query failed');
                    if (mysqli_num_rows($select_carts) > 0) {
                        while ($fetch_carts = mysqli_fetch_assoc($select_carts)) {
                            $userId = $fetch_carts['user_id'];
                            $users = mysqli_query($conn, "SELECT * FROM `users` WHERE id = '$userId'") or die('query failed');
                            $fetch_user = mysqli_fetch_assoc($users);

                            ?>
                            <!-- Replace the following rows with server-generated product data -->
                            <tr class="cart-table__row">
                                <td class="cart-table__column"><?php echo $fetch_user['name']; ?></td>
                                <td class="cart-table__column"><?php echo $fetch_carts['name']; ?></td>
                                <td class="cart-table__column"><?php echo $fetch_carts['price']; ?></td>
                                <form action="" method="post">
                                    <input type="hidden" name="cart_id" value="<?php echo $fetch_carts['id']; ?>">
                                    <td class="cart-table__column"><input name="quantity" required type="number"
                                                                          value="<?php echo $fetch_carts['quantity']; ?>">
                                    </td>
                                    <td class="cart-table__column">
                                        <input type="submit" name="update_cart" value="update" class="btn btn-primary">
                                        <a class="btn btn-danger"
                                           href="./cart.php?delete=<?php echo $fetch_carts['id']; ?>"
                                           class="delete-btn" onclick="return confirm('delete this cart?');">delete</a>
                                    </td>
                                </form>
                            </tr>
                            <?php
                        }
                    } else {
                        echo '<p class="empty">no cart placed yet!</p>';
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
