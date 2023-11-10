<?php
if (isset($message)) {
    foreach ($message as $message) {
        echo '
      <div class="message">
         <span>' . $message . '</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
    }
}
?>

<header class="site__header d-lg-block d-none">
    <div class="site-header"><!-- .topbar -->
        <div class="site-header__nav-panel">
            <div class="nav-panel">
                <div class="nav-panel__container container">
                    <div class="nav-panel__row">
                        <?php
                        $select_cart_count = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
                        $cart_num_rows = mysqli_num_rows($select_cart_count);
                        ?>

                        <?php
                        if (empty($_SESSION['admin_name']) && empty($_SESSION['user_name'])) {
                            ?>
                            <div class="nav-panel__nav-links nav-links">
                                <ul class="nav-links__list">
                                    <li class="nav-links__item"><a href="register.php"><span>Register</span></a>
                                    </li>
                                    <li class="nav-links__item"><a href="login.php"><span>Login</span></a>
                                    </li>
                                    <li class="nav-links__item"><a href="book.php"><span>Home</span></a>
                                    </li>
                                    <li class="nav-links__item nav-links__item--with-submenu">
                                        <a href="#">
                                            <span>Genre
                                                <svg class="nav-links__arrow" width="9px" height="6px">
                                                    <use xlink:href="images/sprite.svg#arrow-rounded-down-9x6">
                                                    </use>
                                                </svg>
                                            </span>
                                        </a>
                                        <div class="nav-links__menu"><!-- .menu -->
                                            <ul class="menu menu--layout--classic">
                                                <li><a href="book.php?genre=Fantasy">Fantasy</a></li>
                                                <li><a href="book.php?genre=History">History</a></li>
                                                <li><a href="book.php?genre=Horror">Horror</a></li>
                                                <li><a href="book.php?genre=Mystery">Mystery</a></li>
                                                <li><a href="book.php?genre=Non-Fiction">Non-Fiction</a></li>
                                                <li><a href="book.php?genre=Romance">Romance</a></li>
                                                <li><a href="book.php?genre=Sci-Fi">Sci-Fi</a></li>
                                                <li><a href="book.php?genre=Thriller">Thriller</a></li>
                                                <li><a href="book.php?genre=Young Adult">Young Adult</a></li>
                                            </ul><!-- .menu / end -->
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <?php
                        }
                        ?>

                        <?php
                        if (!empty($_SESSION['admin_name']) || !empty($_SESSION['user_name'])) {
                            ?>
                            <div class="nav-panel__nav-links nav-links">
                                <ul class="nav-links__list">
                                    <li class="nav-links__item"><a href="book.php"><span>Home</span></a>
                                    </li>
                                    <!--                                    <li class="nav-links__item"><a href="book.php"><span>Book</span></a>-->
                                    <!--                                    </li>-->
                                    <li class="nav-links__item"><a href="order.php"><span>Orders</span></a>
                                    </li>
                                    <li class="nav-links__item nav-links__item--with-submenu">
                                        <a href="#">
                                            <span>Genre
                                                <svg class="nav-links__arrow" width="9px" height="6px">
                                                    <use xlink:href="images/sprite.svg#arrow-rounded-down-9x6">
                                                    </use>
                                                </svg>
                                            </span>
                                        </a>
                                        <div class="nav-links__menu"><!-- .menu -->
                                            <ul class="menu menu--layout--classic">
                                                <li><a href="book.php?genre=Fantasy">Fantasy</a></li>
                                                <li><a href="book.php?genre=History">History</a></li>
                                                <li><a href="book.php?genre=Horror">Horror</a></li>
                                                <li><a href="book.php?genre=Mystery">Mystery</a></li>
                                                <li><a href="book.php?genre=Non-Fiction">Non-Fiction</a></li>
                                                <li><a href="book.php?genre=Romance">Romance</a></li>
                                                <li><a href="book.php?genre=Sci-Fi">Sci-Fi</a></li>
                                                <li><a href="book.php?genre=Thriller">Thriller</a></li>
                                                <li><a href="book.php?genre=Young Adult">Young Adult</a></li>
                                            </ul><!-- .menu / end -->
                                        </div>
                                    </li>
                                </ul>
                            </div><!-- .nav-links / end -->
                            <div class="nav-panel__nav-links nav-links">
                                <ul class="nav-links__list">
                                    <li class="nav-links__item">
                                        <div class="topbar__item">
                                            <div class="topbar-dropdown">
                                                <button class="topbar-dropdown__btn"
                                                        type="button"><?php echo $_SESSION['user_name']; ?>
                                                    <svg width="7px" height="5px">
                                                        <use xlink:href="images/sprite.svg#arrow-rounded-down-7x5"></use>
                                                    </svg>
                                                </button>
                                                <div class="topbar-dropdown__body"><!-- .menu -->
                                                    <ul class="menu menu--layout--topbar">
                                                        <li><a href="logout.php" class="delete-btn">logout</a></li>
                                                    </ul><!-- .menu / end -->
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="nav-panel__indicators">
                                <div class="indicator">
                                    <a href="cart.php" class="indicator__button">
                                    <span class="indicator__area">
                                        <svg width="20px" height="20px">
                                            <use xlink:href="images/sprite.svg#cart-20"></use>
                                        </svg>
                                        <span class="indicator__value"><?php echo $cart_num_rows; ?></span>
                                    </span>
                                    </a>
                                </div>
                            </div>
                            <?php
                        }
                        ?>


                    </div>
                </div>
            </div>
        </div>
    </div>
</header>