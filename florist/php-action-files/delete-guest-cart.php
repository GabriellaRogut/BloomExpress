<?php
    include("../DB/connection.php");
    include("guestCart.php");

    $guestCart = new Cart();

    $guestCart->clearCart();


    echo "<script>document.location.href='../cart.php';</script>";
?>