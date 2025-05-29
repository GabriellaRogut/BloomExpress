<?php
    include("../DB/connection.php");
    include("../config/config.php");
    include("guestCart.php");

    $guestCart = new Cart();

    $guestCart->clearCart();


    echo "<script>document.location.href='../cart.php';</script>";
?>