<?php

@include '../config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
};


if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM `products` WHERE id = '$delete_id'") or die('query failed');
    mysqli_query($conn, "DELETE FROM `cart` WHERE pid = '$delete_id'") or die('query failed');
    header('location:../admin/book.php');
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
                    <h1>Book List</h1>
                </div>
                <a href="book_create.php" class="btn btn-primary">Create Book</a>
            </div>
        </div>
        <div class="cart block">
            <div class="container">
                <table class="table">
                    <thead>
                    <tr>
                        <th class="">Image</th>
                        <th class="">Name</th>
                        <th class="">Details</th>
                        <th class="">Genre</th>
                        <th class="">Author</th>
                        <th class="">ISBN</th>
                        <th class="">Price</th>
                        <th class="">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
                    if (mysqli_num_rows($select_products) > 0) {
                        while ($fetch_products = mysqli_fetch_assoc($select_products)) {
                            ?>
                            <!-- Replace the following rows with server-generated product data -->
                            <tr class="cart-table__row">
                                <td class="cart-table__column"><img src="<?php echo $fetch_products['image']; ?>"
                                                                    width="300" height="200"></td>
                                <td class="cart-table__column"><?php echo $fetch_products['name']; ?></td>
                                <td class="cart-table__column"><?php echo $fetch_products['details']; ?></td>
                                <td class="cart-table__column"><?php echo $fetch_products['genre']; ?></td>
                                <td class="cart-table__column"><?php echo $fetch_products['author']; ?></td>
                                <td class="cart-table__column"><?php echo $fetch_products['isbn']; ?></td>
                                <td class="cart-table__column"><?php echo $fetch_products['price']; ?></td>
                                <form action="" method="post">
                                    <td class="cart-table__column">
                                        <a class="btn btn-primary"
                                           href="../admin/book_edit.php?update=<?php echo $fetch_products['id']; ?>">Edit</a>
                                        <a class="btn btn-danger"
                                           href="./book.php?delete=<?php echo $fetch_products['id']; ?>"
                                           class="delete-btn" onclick="return confirm('delete this book?');">delete</a>
                                    </td>
                                </form>
                            </tr>
                            <?php
                        }
                    } else {
                        echo '<p class="empty">no books placed yet!</p>';
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
