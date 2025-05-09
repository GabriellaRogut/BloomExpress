<?php
    include("../DB/connection.php");
    include("guestCart.php");


    $readyMadeID = $_POST['readyMadeID'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $size = $_POST['size'];
    $image_path = $_POST['image_path'];


    if (isset($_SESSION['user']['userID'])){

        // get product details
        $userID = $_SESSION['user']['userID'];

        $cart = $connection->query("
            SELECT *
            FROM Cart
            WHERE Cart.userID = $userID;
        ")->fetch();


        if (!$cart){
            $cart = $connection->query("
                INSERT INTO Cart (userID)
                VALUES ($userID)
            ");

            $cart = $connection->query("
                SELECT *
                FROM Cart
                WHERE Cart.userID = $userID;
            ")->fetch();
        }

        $cartIDvar = $cart["cartID"];


        $items_cart_to_upd = $connection->query("
            SELECT *
            FROM Cart_Items
            WHERE Cart_Items.cartID = $cartIDvar;
        ")->fetchAll();


        $found = false;

        foreach( $items_cart_to_upd as $c ){
            if ($c['readyMadeID'] == $readyMadeID) {

                $found = true;

                $update_item = $connection->prepare("
                    UPDATE Cart_Items 
                    SET quantity = quantity + 1 
                    WHERE cartID = ? AND readyMadeID = ?
                ");
                $update_item->execute([$cartIDvar, $readyMadeID]); 
            }

        }


        // add to cart
        if (!$found) {
            $add_item = $connection->prepare("
                INSERT INTO Cart_Items (readyMadeID, cartID, quantity)
                VALUES (?, ?, 1)
            ");

            $add_item->execute([ $readyMadeID, $cartIDvar ]); 
        }
        
    } else {
        $guestCart = new Cart();
        $guestCart->addItem($readyMadeID, $price);
    }



    echo "<script>document.location.href='../shop.php';</script>";
?>
