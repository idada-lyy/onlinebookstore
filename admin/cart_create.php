<?php

@include '../config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
};

if (isset($_POST['add_cart'])) {

    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    $book_id = mysqli_real_escape_string($conn, $_POST['book_id']);
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);

    $select_product_name = mysqli_query($conn, "SELECT * FROM `products` WHERE id = '$book_id'") or die('query failed');
    $fetch_books = mysqli_fetch_assoc($select_product_name);
    $product_name = $fetch_books['name'];
    $product_price = $fetch_books['price'];
    $product_image = $fetch_books['image'];

    mysqli_query($conn, "INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES('$user_id', '$book_id', '$product_name', '$product_price', '$quantity', '$product_image')") or die('query failed');
    $message[] = 'cart added successfully!';
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
                <div class="page-header__title"><h1>Create New Cart</h1></div>
            </div>
        </div>
        <div class="cart block">
            <div class="container">
                <form action="" method="post">
                    <div class="form-group">
                        <label for="productName">User</label>
                        <select name="user_id" class="form-control">
                            <?php
                            $select_users = mysqli_query($conn, "SELECT * FROM `users`") or die('query failed');
                            if (mysqli_num_rows($select_users) > 0) {
                                while ($fetch_users = mysqli_fetch_assoc($select_users)) {
                                    ?>
                                    <option value="<?php echo $fetch_users['id']; ?>"><?php echo $fetch_users['name']; ?></option>
                                    <?php
                                }
                            } else {
                                echo '<option value="">No user select</option>';
                            }
                            ?>
                        </select>
                        <!--                        <input type="text" class="form-control" id="productName" name="name" required>-->
                    </div>
                    <div class="form-group">
                        <label for="productDescription">Book Name</label>
                        <select name="book_id" class="form-control">
                            <?php
                            $select_books = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
                            if (mysqli_num_rows($select_books) > 0) {
                                while ($fetch_books = mysqli_fetch_assoc($select_books)) {
                                    ?>
                                    <option value="<?php echo $fetch_books['id']; ?>"><?php echo $fetch_books['name']; ?></option>
                                    <?php
                                }
                            } else {
                                echo '<option value="">No books select</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="productPrice">quantity</label>
                        <input type="number" class="form-control" id="productPrice" name="quantity"
                               required>
                    </div>

                    <input type="submit" value="add cart" name="add_cart" class="btn btn-primary">
                </form>
            </div>
        </div>
    </div>
</div>
</div><!-- site / end -->
</body>

</html>

