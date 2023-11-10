<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
};

if (isset($_POST['add_to_cart'])) {

    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_genre = $_POST['product_genre'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = $_POST['product_quantity'];

    $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

    $select_product = mysqli_query($conn, "SELECT quantity FROM products WHERE id = '$product_id'") or die('query failed');
    $fetch_products = mysqli_fetch_assoc($select_product);

    if ($fetch_products['quantity'] == 0) {
        $message[] = 'Product out of stock!';
    } else {
        if (mysqli_num_rows($check_cart_numbers) > 0) {
            // Product is already in the cart, so increase the quantity in the cart and decrease the quantity in the product table
            $cart_row = mysqli_fetch_assoc($check_cart_numbers);
            $cart_id = $cart_row['id'];
            $new_quantity = $cart_row['quantity'] + 1;

            mysqli_query($conn, "UPDATE `cart` SET quantity = '$new_quantity' WHERE id = '$cart_id'") or die('query failed');

            // Decrease the quantity in the product table
            $new_product_quantity = $fetch_products['quantity'] - 1;
            mysqli_query($conn, "UPDATE `products` SET quantity = '$new_product_quantity' WHERE id = '$product_id'") or die('query failed');

            $message[] = 'Product quantity updated in the cart';
        } else {
            // Product is not in the cart, so insert it into the cart table
            mysqli_query($conn, "INSERT INTO `cart`(user_id, pid, name, genre, price, quantity, image) VALUES('$user_id', '$product_id', '$product_name', '$product_genre', '$product_price', 1, '$product_image')") or die('query failed');

            // Decrease the quantity in the product table
            $new_product_quantity = $fetch_products['quantity'] - 1;
            mysqli_query($conn, "UPDATE `products` SET quantity = '$new_product_quantity' WHERE id = '$product_id'") or die('query failed');

            $message[] = 'Product added to cart';
        }
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
<body><!-- quickview-modal -->
<div id="quickview-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content"></div>
    </div>
</div>

<div class="site"><!-- mobile site__header -->

    <?php @include 'header.php'; ?>

    <div class="site__body">

        <?php
        if (isset($_GET['pid'])) {
            $pid = $_GET['pid'];
            $select_products = mysqli_query($conn, "SELECT * FROM `products` WHERE id = '$pid'") or die('query failed');
            if (mysqli_num_rows($select_products) > 0) {
                while ($fetch_products = mysqli_fetch_assoc($select_products)) {
                    ?>

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
                                        <li class="breadcrumb-item active" aria-current="page">
                                            <?php echo $fetch_products['name']; ?>
                                        </li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <div class="block">
                        <div class="container">
                            <div class="product product--layout--standard" data-layout="standard">
                                <div class="product__content"><!-- .product__gallery -->
                                    <div class="product__gallery">
                                        <div class="product-gallery">
                                            <div class="product-gallery__featured">
                                                <div class="owl-carousel" id="product-image"><a
                                                            href="#"><img
                                                                src="<?php echo $fetch_products['image']; ?>" alt="">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="product__info">
                                        <h1 class="product__name"><?php echo $fetch_products['name']; ?></h1>
                                        <!--                                        <div class="product__rating">-->
                                        <!--                                            <div class="product__rating-stars">-->
                                        <!--                                                <div class="rating">-->
                                        <!--                                                    <div class="rating__body">-->
                                        <!--                                                        <svg class="rating__star rating__star--active" width="13px"-->
                                        <!--                                                             height="12px">-->
                                        <!--                                                            <g class="rating__fill">-->
                                        <!--                                                                <use xlink:href="images/sprite.svg#star-normal"></use>-->
                                        <!--                                                            </g>-->
                                        <!--                                                            <g class="rating__stroke">-->
                                        <!--                                                                <use xlink:href="images/sprite.svg#star-normal-stroke"></use>-->
                                        <!--                                                            </g>-->
                                        <!--                                                        </svg>-->
                                        <!--                                                        <div class="rating__star rating__star--only-edge rating__star--active">-->
                                        <!--                                                            <div class="rating__fill">-->
                                        <!--                                                                <div class="fake-svg-icon"></div>-->
                                        <!--                                                            </div>-->
                                        <!--                                                            <div class="rating__stroke">-->
                                        <!--                                                                <div class="fake-svg-icon"></div>-->
                                        <!--                                                            </div>-->
                                        <!--                                                        </div>-->
                                        <!--                                                        <svg class="rating__star rating__star--active" width="13px"-->
                                        <!--                                                             height="12px">-->
                                        <!--                                                            <g class="rating__fill">-->
                                        <!--                                                                <use xlink:href="images/sprite.svg#star-normal"></use>-->
                                        <!--                                                            </g>-->
                                        <!--                                                            <g class="rating__stroke">-->
                                        <!--                                                                <use xlink:href="images/sprite.svg#star-normal-stroke"></use>-->
                                        <!--                                                            </g>-->
                                        <!--                                                        </svg>-->
                                        <!--                                                        <div class="rating__star rating__star--only-edge rating__star--active">-->
                                        <!--                                                            <div class="rating__fill">-->
                                        <!--                                                                <div class="fake-svg-icon"></div>-->
                                        <!--                                                            </div>-->
                                        <!--                                                            <div class="rating__stroke">-->
                                        <!--                                                                <div class="fake-svg-icon"></div>-->
                                        <!--                                                            </div>-->
                                        <!--                                                        </div>-->
                                        <!--                                                        <svg class="rating__star rating__star--active" width="13px"-->
                                        <!--                                                             height="12px">-->
                                        <!--                                                            <g class="rating__fill">-->
                                        <!--                                                                <use xlink:href="images/sprite.svg#star-normal"></use>-->
                                        <!--                                                            </g>-->
                                        <!--                                                            <g class="rating__stroke">-->
                                        <!--                                                                <use xlink:href="images/sprite.svg#star-normal-stroke"></use>-->
                                        <!--                                                            </g>-->
                                        <!--                                                        </svg>-->
                                        <!--                                                        <div class="rating__star rating__star--only-edge rating__star--active">-->
                                        <!--                                                            <div class="rating__fill">-->
                                        <!--                                                                <div class="fake-svg-icon"></div>-->
                                        <!--                                                            </div>-->
                                        <!--                                                            <div class="rating__stroke">-->
                                        <!--                                                                <div class="fake-svg-icon"></div>-->
                                        <!--                                                            </div>-->
                                        <!--                                                        </div>-->
                                        <!--                                                        <svg class="rating__star rating__star--active" width="13px"-->
                                        <!--                                                             height="12px">-->
                                        <!--                                                            <g class="rating__fill">-->
                                        <!--                                                                <use xlink:href="images/sprite.svg#star-normal"></use>-->
                                        <!--                                                            </g>-->
                                        <!--                                                            <g class="rating__stroke">-->
                                        <!--                                                                <use xlink:href="images/sprite.svg#star-normal-stroke"></use>-->
                                        <!--                                                            </g>-->
                                        <!--                                                        </svg>-->
                                        <!--                                                        <div class="rating__star rating__star--only-edge rating__star--active">-->
                                        <!--                                                            <div class="rating__fill">-->
                                        <!--                                                                <div class="fake-svg-icon"></div>-->
                                        <!--                                                            </div>-->
                                        <!--                                                            <div class="rating__stroke">-->
                                        <!--                                                                <div class="fake-svg-icon"></div>-->
                                        <!--                                                            </div>-->
                                        <!--                                                        </div>-->
                                        <!--                                                        <svg class="rating__star rating__star--active" width="13px"-->
                                        <!--                                                             height="12px">-->
                                        <!--                                                            <g class="rating__fill">-->
                                        <!--                                                                <use xlink:href="images/sprite.svg#star-normal"></use>-->
                                        <!--                                                            </g>-->
                                        <!--                                                            <g class="rating__stroke">-->
                                        <!--                                                                <use xlink:href="images/sprite.svg#star-normal-stroke"></use>-->
                                        <!--                                                            </g>-->
                                        <!--                                                        </svg>-->
                                        <!--                                                        <div class="rating__star rating__star--only-edge rating__star--active">-->
                                        <!--                                                            <div class="rating__fill">-->
                                        <!--                                                                <div class="fake-svg-icon"></div>-->
                                        <!--                                                            </div>-->
                                        <!--                                                            <div class="rating__stroke">-->
                                        <!--                                                                <div class="fake-svg-icon"></div>-->
                                        <!--                                                            </div>-->
                                        <!--                                                        </div>-->
                                        <!--                                                    </div>-->
                                        <!--                                                </div>-->
                                        <!--                                            </div>-->
                                        <!--                                            <div class="product__rating-legend"><a href="#">7-->
                                        <!--                                                    Reviews</a><span>/</span><a href="#">Write-->
                                        <!--                                                    A Review</a></div>-->
                                        <!--                                        </div>-->

                                        <div class="product__description">
                                            Genre: <?php echo $fetch_products['genre']; ?>
                                        </div>
                                        <div class="product__description">
                                            Author: <?php echo $fetch_products['author']; ?>
                                        </div>
                                        <div class="product__description">
                                            ISBN: <?php echo $fetch_products['isbn']; ?>
                                        </div>
                                        <div class="product__description">
                                            Description: <?php echo $fetch_products['details']; ?>
                                        </div>
                                        <!--                                        <ul class="product__features">-->
                                        <!--                                            <li>Speed: 750 RPM</li>-->
                                        <!--                                            <li>Power Source: Cordless-Electric</li>-->
                                        <!--                                            <li>Battery Cell Type: Lithium</li>-->
                                        <!--                                            <li>Voltage: 20 Volts</li>-->
                                        <!--                                            <li>Battery Capacity: 2 Ah</li>-->
                                        <!--                                        </ul>-->
                                        <ul class="product__meta">
                                            <li class="product__meta-availability">Availability: <span
                                                        class="text-success">In Stock</span></li>
                                            <!--                                            <li>Brand: <a href="#">Wakita</a></li>-->
                                            <!--                                            <li>SKU: 83690/32</li>-->
                                        </ul>
                                    </div><!-- .product__info / end --><!-- .product__sidebar -->
                                    <div class="product__sidebar">
                                        <div class="product__availability">Availability: <span class="text-success">In Stock</span>
                                        </div>
                                        <div class="product__prices">RM<?php echo $fetch_products['price']; ?></div>
                                        <!-- .product__options -->
                                        <form class="product__options" action="" method="POST">
                                            <input type="hidden" name="product_id"
                                                   value="<?php echo $fetch_products['id']; ?>">
                                            <input type="hidden" name="product_name"
                                                   value="<?php echo $fetch_products['name']; ?>">
                                            <input type="hidden" name="product_genre"
                                                   value="<?php echo $fetch_products['genre']; ?>">
                                            <input type="hidden" name="product_price"
                                                   value="<?php echo $fetch_products['price']; ?>">
                                            <input type="hidden" name="product_image"
                                                   value="<?php echo $fetch_products['image']; ?>">
                                            <div class="form-group product__option"><label class="product__option-label"
                                                                                           for="product-quantity">Quantity</label>
                                                <div class="product__actions">
                                                    <div class="product__actions-item">
                                                        <div class="input-number product__quantity">
                                                            <input name="product_quantity"
                                                                   id="product-quantity"
                                                                   class="input-number__input form-control form-control-lg"
                                                                   type="number" min="1"
                                                                   value="1">
                                                            <div class="input-number__add"></div>
                                                            <div class="input-number__sub"></div>
                                                        </div>
                                                    </div>
                                                    <div class="product__actions-item product__actions-item--addtocart">
                                                        <input value="Add to cart" type="submit" name="add_to_cart"
                                                               class="btn btn-primary btn-lg">
                                                    </div>
                                                </div>
                                            </div>
                                        </form><!-- .product__options / end -->
                                    </div><!-- .product__end -->
                                </div>
                            </div>
                        </div>
                    </div><!-- .block-products-carousel -->
                    <?php
                }
            } else {
                echo '<p class="empty">no products details available!</p>';
            }
        }
        ?>
    </div>

    <?php @include 'footer.php'; ?>
</div>
</html>
