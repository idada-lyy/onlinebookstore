<?php

@include '../config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
};

if (isset($_POST['add_product'])) {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $details = mysqli_real_escape_string($conn, $_POST['details']);
    $genre = mysqli_real_escape_string($conn, $_POST['genre']);
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $isbn = mysqli_real_escape_string($conn, $_POST['isbn']);

    $image = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folter = 'uploaded_img/' . $image;

    $select_product_name = mysqli_query($conn, "SELECT name FROM `products` WHERE name = '$name'") or die('query failed');

    if (mysqli_num_rows($select_product_name) > 0) {
        $message[] = 'product name already exist!';
    } else {
        $image_data = file_get_contents($image_tmp_name);
        $base64_image = "data:image/jpeg;base64," . base64_encode($image_data);
        $insert_product = mysqli_query($conn, "INSERT INTO `products`(name, details, genre, author, isbn, price, image, quantity) VALUES('$name', '$details', '$genre', '$author', '$isbn', '$price', '$base64_image', '$quantity')") or die('query failed');

        if ($insert_product) {
            if ($image_size > 2000000) {
                $message[] = 'image size is too large!';
            } else {
                move_uploaded_file($image_tmp_name, $image_folter);
                $message[] = 'product added successfully!';
                header('location:../admin/book.php');
            }
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
                <div class="page-header__title"><h1>Create New Book</h1></div>
            </div>
        </div>
        <div class="cart block">
            <div class="container">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="productName">Book Name:</label>
                        <input type="text" class="form-control" id="productName" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="productDescription">Book Details:</label>
                        <textarea cols="30" rows="10" class="form-control" id="productDescription" name="details"
                                  required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="productName">Book Genre:</label>
                        <select class="form-control" id="productGenre" name="genre" required>
                            <option value="">Select a genre</option>
                            <option value="Fantasy">Fantasy</option>
                            <option value="History">History</option>
                            <option value="Horror">Horror</option>
                            <option value="Mystery">Mystery</option>
                            <option value="Non-Fiction">Non-Fiction</option>
                            <option value="Romance">Romance</option>
                            <option value="Sci-Fi">Sci-Fi</option>
                            <option value="Thriller">Thriller</option>
                            <option value="Young Adult">Young Adult</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Book Author:</label>
                        <input type="text" class="form-control" name="author" required>
                    </div>

                    <div class="form-group">
                        <label>Book ISBN:</label>
                        <input type="text" class="form-control" name="isbn" required>
                    </div>

                    <div class="form-group">
                        <label for="productPrice">Book Price:</label>
                        <input type="number" class="form-control" id="productPrice" name="price"
                               required>
                    </div>

                    <div class="form-group">
                        <label for="productPrice">Book Image:</label>
                        <input type="file" class="form-control" accept="image/jpg, image/jpeg, image/png" class="box"
                               name="image">
                    </div>

                    <div class="form-group">
                        <label for="productPrice">Book Quantity:</label>
                        <input type="number" class="form-control" name="quantity">
                    </div>

                    <input type="submit" value="add product" name="add_product" class="btn btn-primary">
                </form>
            </div>
        </div>
    </div>
</div>
</div><!-- site / end -->
</body>

</html>

