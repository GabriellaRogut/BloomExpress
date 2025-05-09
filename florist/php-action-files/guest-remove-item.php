<?php 
    include('../DB/connection.php');
    include("guestCart.php");

    $guestCart = new Cart();


    if (isset($_POST['guest_item_removeID'], $_POST['remove_item'])) {
        $cartItemID = $_POST['guest_item_removeID'];

        if (isset($_SESSION['cart'][$cartItemID])) {
            $guestCart->removeItem($cartItemID);
        }
    }


    echo "<script>document.location.href='../cart.php';</script>";
?>
