<?php
    include("../DB/connection.php");
    include("../config/config.php");

    $cart_del_ID = $_POST['cart_delete_ID'];


    $delete_cart = $connection->prepare("
        DELETE FROM Cart_Items
        WHERE cartID = ?
    ");                         
    $delete_cart->execute([ $cart_del_ID ]); 



    // back to the previous page
    echo "<script>document.location.href='../cart.php';</script>";
?>