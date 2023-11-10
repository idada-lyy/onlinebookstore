<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
};

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$delete_id'") or die('query failed');
    header('location:cart.php');
}

if (isset($_GET['delete_all'])) {
    mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
    header('location:cart.php');
};

if (isset($_POST['update_quantity'])) {
    $cart_id = $_POST['cart_id'];
    $cart_quantity = $_POST['cart_quantity'];
    mysqli_query($conn, "UPDATE `cart` SET quantity = '$cart_quantity' WHERE id = '$cart_id'") or die('query failed');
    $message[] = 'cart quantity updated!';
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
                            <li class="breadcrumb-item active" aria-current="page">Shopping Cart</li>
                        </ol>
                    </nav>
                </div>
                <div class="page-header__title"><h1>Shopping Cart</h1></div>
            </div>
        </div>
        <div class="cart block">
            <div class="container">
                <table class="cart__table cart-table">
                    <thead class="cart-table__head">
                    <tr class="cart-table__row">
                        <th class="cart-table__column cart-table__column--image">Image</th>
                        <th class="cart-table__column cart-table__column--product">Product</th>
                        <th class="cart-table__column cart-table__column--price">Price</th>
                        <th class="cart-table__column cart-table__column--quantity">Quantity</th>
                        <th class="cart-table__column cart-table__column--total">Total</th>
                        <th class="cart-table__column "></th>
                        <th class="cart-table__column cart-table__column--remove"></th>
                    </tr>
                    </thead>
                    <tbody class="cart-table__body">
                    <?php
                    $grand_total = 0;
                    $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
                    if (mysqli_num_rows($select_cart) > 0) {
                        while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
                            ?>
                            <tr class="cart-table__row">
                                <td class="cart-table__column cart-table__column--image"><a href="#"><img
                                                src="<?php echo $fetch_cart['image']; ?>" alt=""></a></td>
                                <td class="cart-table__column cart-table__column--product">
                                    <a href="#" class="cart-table__product-name"><?php echo $fetch_cart['name']; ?></a>
                                    <!--                                    <ul class="cart-table__options">-->
                                    <!--                                        <li>Color: Yellow</li>-->
                                    <!--                                        <li>Material: Aluminium</li>-->
                                    <!--                                    </ul>-->
                                </td>
                                <td class="cart-table__column cart-table__column--price" data-title="Price">
                                    RM<?php echo $fetch_cart['price']; ?></td>
                                <form action="" method="post">
                                    <td class="cart-table__column cart-table__column--quantity" data-title="Quantity">
                                        <div class="input-number">
                                            <input class="form-control input-number__input" name="cart_quantity"
                                                   type="number"
                                                   min="1"
                                                   value="<?php echo $fetch_cart['quantity']; ?>">
                                            <div class="input-number__add"></div>
                                            <div class="input-number__sub"></div>
                                        </div>
                                    </td>
                                    <td class="cart-table__column cart-table__column--total" data-title="Total">
                                        RM<?php echo $fetch_cart['price'] * $fetch_cart['quantity']; ?>
                                    </td>
                                    <input type="hidden" value="<?php echo $fetch_cart['id']; ?>" name="cart_id">
                                    <td class="cart-table__column">
                                        <input type="submit" name="update_quantity"
                                               class="btn btn-primary cart__update-button" value="Update Cart">
                                    </td>
                                    <td class="cart-table__column cart-table__column--remove">
                                        <a href="cart.php?delete=<?php echo $fetch_cart['id']; ?>"
                                           type="button" class="btn btn-light btn-sm btn-svg-icon"
                                           onclick="return confirm('delete this from cart?');">
                                            <svg width="12px" height="12px">
                                                <use xlink:href="images/sprite.svg#cross-12"></use>
                                            </svg>
                                        </a>
                                    </td>
                                </form>
                            </tr>
                            <?php
                        }
                    } else {
                        echo '<p class="empty">your cart is empty</p>';
                    }
                    ?>
                    </tbody>
                </table>
                <div class="cart__actions justify-content-end">
                    <div class="cart__buttons">
                        <a href="book.php" class="btn btn-light">Continue Shopping</a>
                    </div>
                </div>
                <?php
                $grand_total = 0;
                $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
                if (mysqli_num_rows($select_cart) > 0) {
                    while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
                        $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']);
                        $grand_total += $sub_total;
                    }

                    ?>
                    <div class="row justify-content-end pt-5">
                        <div class="col-12 col-md-7 col-lg-6 col-xl-5">
                            <div class="card">
                                <div class="card-body"><h3 class="card-title">Cart Totals</h3>
                                    <table class="cart__totals">
                                        <thead class="cart__totals-header">
                                        <tr>
                                            <th>Subtotal</th>
                                            <td>RM<?php echo $grand_total; ?></td>
                                        </tr>
                                        </thead>
                                        <tbody class="cart__totals-body">
                                        <tr>
                                            <th>Shipping</th>
                                            <td>RM10
<!--                                                <div class="cart__calc-shipping"><a href="#">Calculate Shipping</a>-->
<!--                                                </div>-->
                                            </td>
                                        </tr>
<!--                                        <tr>-->
<!--                                            <th>Tax</th>-->
<!--                                            <td>$0.00</td>-->
<!--                                        </tr>-->
                                        </tbody>
                                        <tfoot class="cart__totals-footer">
                                        <tr>
                                            <th>Total</th>
                                            <td>RM<?php echo $grand_total+10; ?></td>
                                        </tr>
                                        </tfoot>
                                    </table>
                                    <a class="btn btn-primary btn-xl btn-block cart__checkout-button"
                                       href="checkout.php">Proceed
                                        to checkout</a></div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>

    <?php @include 'footer.php'; ?>
</div><!-- site / end -->
</body>

</html>
