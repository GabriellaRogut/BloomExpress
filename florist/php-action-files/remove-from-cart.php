<?php
    include("../DB/connection.php");

    $cartItemID = $_POST['cart_item_removeID'];


    $remove_item = $connection->prepare("
        DELETE FROM Cart_Items 
        WHERE cartItemID = ?
    ");                         
    $remove_item->execute([ $cartItemID ]); 


    // back to the previous page
    echo "<script>document.location.href='../cart.php';</script>";

?>