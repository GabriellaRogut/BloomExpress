<?php
    include("../DB/connection.php");
    include("guestCart.php");

    $guestCart = new Cart();


    if (isset($_POST['cartItemID'], $_POST['action'])) {
        $cartItemID = $_POST['cartItemID'];
        $action = $_POST['action'];

        if (isset($_SESSION['cart'][$cartItemID])) {
            if ($action === 'gIncrease') {
                $guestCart->increaseQty($cartItemID);
            } elseif ($action === 'gDecrease') {
                $guestCart->decreaseQty($cartItemID);
            }
        }
    }


    echo "<script>document.location.href='../cart.php';</script>";
?>
