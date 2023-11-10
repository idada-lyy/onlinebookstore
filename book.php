<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (isset($_POST['add_to_cart'])) {
    if (!isset($user_id)) {
        header('location:login.php');
        return;
    };

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
<body>

<div class="site">

    <?php @include 'header.php'; ?>

    <div class="site__body">
        <div class="container pt-4">
            <form class="" method="POST" action="">
                <div class="row p-1 flex-row align-items-center" style="border: 1px solid black">
                    <div class="col-11">
                        <input class="border-0" style="width: 100%" name="search_box" placeholder="Search Book Name"
                               type="text">
                    </div>
                    <div class="col-1">
                        <input class="btn " type="submit" value="search" name="search_btn">
                    </div>
                </div>
            </form>
        </div>
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
                            <li class="breadcrumb-item"><a href="#">Book</a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="block">
                        <div class="products-view">
                            <div class="products-view__list products-list" data-layout="grid-4-full"
                                 data-with-features="true">

                                <div class="products-list__body">
                                    <?php
                                    $a = $_GET['genre'];

                                    if (isset($_POST['search_btn'])) {
                                        $search_box = mysqli_real_escape_string($conn, $_POST['search_box']);
                                        $select_products = mysqli_query($conn, "SELECT * FROM `products` WHERE name LIKE '%{$search_box}%' OR details LIKE '%{$search_box}%'OR author LIKE '%{$search_box}%'OR isbn LIKE '%{$search_box}%'") or die('query failed');
                                    } else {
                                        if (empty($a)) {
                                            $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
                                        } else {
                                            $select_products = mysqli_query($conn, "SELECT * FROM `products` WHERE genre='$a'") or die('query failed');
                                        }
                                    }

                                    if (mysqli_num_rows($select_products) > 0) {
                                        while ($fetch_products = mysqli_fetch_assoc($select_products)) {
                                            ?>
                                            <div class="products-list__item">
                                                <div class="product-card">
                                                    <div class="product-card__badges-list">
                                                        <div class="product-card__badge product-card__badge--new">
                                                            New
                                                        </div>
                                                    </div>
                                                    <div class="product-card__image">
                                                        <a href="book_detail.php?pid=<?php echo $fetch_products['id']; ?>">
                                                            <img src="<?php echo $fetch_products['image']; ?>"
                                                                 width="200px" height="250px" alt="">
                                                        </a>
                                                    </div>
                                                    <div class="product-card__info">
                                                        <div class="product-card__name"><a
                                                                    href="product.html"><?php echo $fetch_products['name']; ?></a>
                                                        </div>
                                                        <ul class="product-card__features-list">
                                                            <?php echo $fetch_products['details']; ?>
                                                        </ul>
                                                    </div>
                                                    <div class="product-card__actions">
                                                        <div class="product-card__prices">
                                                            RM<?php echo $fetch_products['price']; ?></div>
                                                        <form action="" method="POST" class="box">
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
                                                            <div class="product-card__buttons">
                                                                <input class="btn btn-primary product-card__addtocart"
                                                                       type="submit" name="add_to_cart"
                                                                       value="Add To Cart">
                                                                <button class="btn btn-secondary product-card__addtocart product-card__addtocart--list"
                                                                        type="button">Add To Cart
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                    } else {
                                        echo '<p class="empty">no products added yet!</p>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php @include 'footer.php'; ?>
</div>
</body>

</html>
