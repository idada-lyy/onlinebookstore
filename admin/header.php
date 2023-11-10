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
                    <div class="nav-panel__row justify-content-between">
                        <div class="nav-panel__nav-links nav-links">
                            <ul class="nav-links__list">
                                <li class="nav-links__item"><a href="dashboard.php"><span>Dashboard</span></a>
                                </li>
                                <li class="nav-links__item"><a href="book.php"><span>Book</span></a>
                                </li>
                                <li class="nav-links__item"><a href="cart.php"><span>Cart</span></a>
                                </li>
                                <li class="nav-links__item"><a href="order.php"><span>Orders</span></a>
                                </li>
                            </ul>
                        </div><!-- .nav-links / end -->


                        <div class="nav-panel__nav-links nav-links">
                            <ul class="nav-links__list">
                                <li class="nav-links__item">
                                    <div class="topbar__item">
                                        <div class="topbar-dropdown">
                                            <button class="topbar-dropdown__btn"
                                                    type="button"><?php echo $_SESSION['admin_name']; ?>
                                                <svg width="7px" height="5px">
                                                    <use xlink:href="images/sprite.svg#arrow-rounded-down-7x5"></use>
                                                </svg>
                                            </button>
                                            <div class="topbar-dropdown__body"><!-- .menu -->
                                                <ul class="menu menu--layout--topbar">
                                                    <li><a href="../logout.php" class="delete-btn">logout</a></li>
                                                </ul><!-- .menu / end -->
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div><!-- .nav-links / end -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>