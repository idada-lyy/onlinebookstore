<?php

@include '../config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
};

if (isset($_POST['update_product'])) {

    $update_p_id = $_POST['update_p_id'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $details = mysqli_real_escape_string($conn, $_POST['details']);
    $genre = mysqli_real_escape_string($conn, $_POST['genre']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $isbn = mysqli_real_escape_string($conn, $_POST['isbn']);

    $s = mysqli_query($conn, "UPDATE `products` SET name = '$name', details = '$details', genre = '$genre', author = '$author', isbn = '$isbn', price = '$price' WHERE id = '$update_p_id'") or die('query failed');

    $image = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folter = './uploaded_img/' . $image;
    $old_image = $_POST['update_p_image'];

    if (!empty($image)) {
        if ($image_size > 2000000) {
            $message[] = 'image file size is too large!';
        } else {
            $image_data = file_get_contents($image_tmp_name);
            $base64_image = "data:image/jpeg;base64," . base64_encode($image_data);
            mysqli_query($conn, "UPDATE `products` SET image = '$base64_image' WHERE id = '$update_p_id'") or die('query failed');
            move_uploaded_file($image_tmp_name, $image_folter);
            unlink('./uploaded_img/' . $old_image);
            $message[] = 'image updated successfully!';
        }
    }

    $message[] = 'product updated successfully!';

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
                <div class="page-header__title"><h1>Book Edit</h1></div>
            </div>
        </div>
        <?php

        $update_id = $_GET['update'];
        $select_products = mysqli_query($conn, "SELECT * FROM `products` WHERE id = '$update_id'") or die('query failed');
        if (mysqli_num_rows($select_products) > 0) {
            while ($fetch_products = mysqli_fetch_assoc($select_products)) {
                ?>
                <div class="cart block">
                    <div class="container">
                        <form action="" method="post" enctype="multipart/form-data">
                            <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" class="image" alt="">
                            <input type="hidden" value="<?php echo $fetch_products['id']; ?>" name="update_p_id">
                            <input type="hidden" value="<?php echo $fetch_products['image']; ?>" name="update_p_image">
                            <div class="form-group">
                                <label for="productName">Book Name:</label>
                                <input type="text" class="form-control" value="<?php echo $fetch_products['name']; ?>"
                                       id="productName" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="productDescription">Book Details:</label>
                                <textarea cols="30" rows="10" class="form-control" id="productDescription"
                                          name="details"
                                          required><?php echo $fetch_products['details']; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="productName">Book Genre:</label>
                                <select class="form-control" id="productGenre" name="genre" required>
                                    <option value="Fantasy" <?php if ($fetch_products['genre'] == 'Fantasy') echo 'selected'; ?>>
                                        Fantasy
                                    </option>
                                    <option value="History" <?php if ($fetch_products['genre'] == 'History') echo 'selected'; ?>>
                                        History
                                    </option>
                                    <option value="Horror" <?php if ($fetch_products['genre'] == 'Horror') echo 'selected'; ?>>
                                        Horror
                                    </option>
                                    <option value="Mystery" <?php if ($fetch_products['genre'] == 'Mystery') echo 'selected'; ?>>
                                        Mystery
                                    </option>
                                    <option value="Non-Fiction" <?php if ($fetch_products['genre'] == 'Non-Fiction') echo 'selected'; ?>>
                                        Non-Fiction
                                    </option>
                                    <option value="Romance" <?php if ($fetch_products['genre'] == 'Romance') echo 'selected'; ?>>
                                        Romance
                                    </option>
                                    <option value="Sci-Fi" <?php if ($fetch_products['genre'] == 'Sci-Fi') echo 'selected'; ?>>
                                        Sci-Fi
                                    </option>
                                    <option value="Thriller" <?php if ($fetch_products['genre'] == 'Thriller') echo 'selected'; ?>>
                                        Thriller
                                    </option>
                                    <option value="Young Adult" <?php if ($fetch_products['genre'] == 'Young Adult') echo 'selected'; ?>>
                                        Young Adult
                                    </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Book Author:</label>
                                <input type="text" class="form-control" name="author" value="<?php echo $fetch_products['author']; ?>" required>
                            </div>

                            <div class="form-group">
                                <label>Book ISBN:</label>
                                <input type="text" class="form-control" name="isbn" value="<?php echo $fetch_products['isbn']; ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="productPrice">Book Price:</label>
                                <input type="number" class="form-control"
                                       value="<?php echo $fetch_products['price']; ?>" id="productPrice" name="price"
                                       required>
                            </div>

                            <div class="form-group">
                                <label for="productPrice">Book Image:</label>
                                <input type="file" class="form-control" accept="image/jpg, image/jpeg, image/png"
                                       class="box" name="image">
                            </div>

                            <div class="form-group">
                                <label for="productPrice">Book Quantity:</label>
                                <input type="number" class="form-control" name="quantity" value="<?php echo $fetch_products['quantity']; ?>">
                            </div>

                            <input type="submit" value="Update" name="update_product" class="btn btn-primary">
                        </form>
                    </div>
                </div>
                <?php
            }
        } else {
            echo '<p class="empty">no update product select</p>';
        }
        ?>
    </div>
</div>
</div><!-- site / end -->
</body>

</html>

