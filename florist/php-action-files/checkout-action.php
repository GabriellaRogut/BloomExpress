<?php
    include("../DB/connection.php");
    include("../config/config.php");
    include("../php-action-files/guestCart.php");


    if (isset($_SESSION['userIn'])){
        $userID = $_SESSION['user']['userID'];

        $promoCodeExec = $connection->prepare("
            SELECT p.promo_code, p.promoCodeID, p.promotion_value
            FROM PromoCode p
            JOIN User_PromoCode up ON p.promoCodeID = up.promoCodeID
            WHERE up.userID = ? AND up.status = 'Available'
        ");
        $promoCodeExec->execute([$userID]);
        $promoCodeArr = $promoCodeExec->fetch();
        $userPromoCode = $promoCodeArr['promo_code'];
        $usedPromoID = $promoCodeArr['promoCodeID'];
        $promotionValue = $promoCodeArr['promotion_value'];



        $cartInfo = $connection->prepare("
            SELECT cartID
            FROM Cart
            WHERE userID = ?
        ");
        $cartInfo->execute([$userID]);
        $cartInfoArr = $cartInfo->fetch();

        $cartID = $cartInfoArr['cartID'];
    }

    $_SESSION['post_info'] = $_POST;

    if ( !isset(  $_SESSION['promo_code_used']  ) ){
        $_SESSION['promo_code_used'] = null;
    }


// DISCOUNT
    if (isset($_POST['apply_promocode'])){
        $promoCode = $_POST['promo_code'];

        if ($userPromoCode == $promoCode && !isset($_SESSION['promo_code_success'])){
            $totalArr = $connection->prepare("
                SELECT total_price_cart 
                FROM Cart 
                WHERE userID = ?
            ");
            $totalArr->execute([$userID]);
            $totalA = $totalArr->fetch();
            $total = $totalA['total_price_cart'];

            $discount = $total * $promotionValue; //10% = 0.1; etc.
            $promoPrice = $total - $discount;
            
            $SetPromoPrice = $connection->prepare("
                UPDATE Cart
                SET total_price_cart = ?
                WHERE userID = ?
            "); 
            $SetPromoPrice->execute([$promoPrice, $userID]); 


            $_SESSION['promo_code_success'] = true;
            $_SESSION['promo_code_used'] = $promoCode;

        } else {
            // Promo code is invalid
            $_SESSION['post_info']['promo_code'] = ''; // clear the promocode input
            echo "<script>document.location.href='../checkout.php?promo=invalid';</script>";
            exit;
        }

        echo "<script>document.location.href='../checkout.php';</script>";
        exit;
    }

    $placed_on = date("Y-m-d");

    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $city = $_POST['city'];
    $address = $_POST['address'];
    $ZIPCode = $_POST['ZIPCode'];

    $payment_method = @$_POST['payment'];


// DELIVERY DATE CHECK 
    $delivery_date = $_POST['delivery_date'];

    $placed_timestamp = strtotime($placed_on);
    $delivery_timestamp = strtotime($delivery_date);

    if ($delivery_timestamp < strtotime("+1 day") && ($payment_method != 'Fetch at Shop')) {
        // Del date is invalid
        $_SESSION['post_info']['delivery_date'] = '';
        echo "<script>document.location.href='../checkout.php?delivery_date=invalid';</script>";
        exit;
    } else if (($payment_method == 'Fetch at Shop') && ($delivery_timestamp < strtotime('today'))) {
        // Del date is invalid
        $_SESSION['post_info']['delivery_date'] = '';
        echo "<script>document.location.href='../checkout.php?delivery_date_fetch=invalid';</script>";
        exit;
    } else {
        $delivery_date_valid = $delivery_date;
    }
 
    
// PAYMENT
    if ($payment_method == 'Pay Online'){
        $cardNum = $_POST['cardNum'];
        $expDate = $_POST['expDate'];
        $cvv = $_POST['cvv'];

        $expDateRegex = '/^[0-9]{2}\/[0-9]{2}$/';

        if(preg_match($expDateRegex, $expDate)){
            $expDateAsArr = explode("/", $expDate);

            if($expDateAsArr[0] < 1 || $expDateAsArr[0] > 12 || $expDateAsArr[1] < date('y')){
                $_SESSION['post_info']['expDate'] = '';
                echo "<script>document.location.href='../checkout.php?expire_date=invalid';</script>";
                exit;
            } else {
                $expDateChecked = $expDate;
            }

            if(preg_match('/^[0-9]{3}$/', $cvv)){
                $cvvChecked = $cvv;
            } else {
                $_SESSION['post_info']['cvv'] = '';
                echo "<script>document.location.href='../checkout.php?cvv=invalid';</script>";
                exit;
            }
        } else {
            $_SESSION['post_info']['expDate'] = '';
            echo "<script>document.location.href='../checkout.php?expire_date=invalid';</script>";
            exit;
        }


        $shopLocation = null;
    } 
    else if ($payment_method == 'Fetch at Shop'){
        $shopLocation = $_POST['shopLocationSelect'];
        $cardNum = null;
        $expDateChecked = null;
        $cvvChecked = null;
    } 
    else if ($payment_method == 'Pay on Delivery'){
        $shopLocation = null;
        $cardNum = null;
        $expDateChecked = null;
        $cvvChecked = null;
    }

    $orderCode = $_POST['orderCodeInput'];

// SET ORDER
    if (isset($_SESSION['userIn'])){
        $setOrder = $connection->prepare("
            INSERT INTO Orders
            SET
                email = ?,
                first_name = ?,
                last_name = ?,
                phone = ?,
                city = ?,
                address = ?,
                ZIPCode = ?,
                orderNum = ?,
                placed_on = ?,
                payment_method = ?,
                order_status = ?,
                userID = ?,
                delivery_date = ?,
                pickupShopLocation = ?,
                cardNum = ?,
                expDate = ?,
                cvv = ?,
                promo_code_order = ?
        ");
        $setOrder->execute([$email, $first_name, $last_name, $phone, $city, $address, $ZIPCode, $orderCode, $placed_on, $payment_method, 'Ongoing', $userID, $delivery_date_valid, $shopLocation, $cardNum, $expDateChecked, $cvvChecked, $_SESSION['promo_code_used']]);

        $orderID = $connection->lastInsertId(); // do not move


        $cart_items = $connection->prepare("
            SELECT *
            FROM Cart_Items
            WHERE cartID = ?
        ");
        $cart_items->execute([$cartID]);
        $c_items = $cart_items->fetchAll();


        foreach ($c_items as $item){
            $insertToOrdItems = $connection->prepare("
                INSERT INTO Order_Items(orderID, readyMadeID, quantity)
                VALUES(?, ?, ?)
            ");
            $insertToOrdItems->execute([$orderID, $item['readyMadeID'], $item['quantity']]);
        }

    } else {
        $setOrder = $connection->prepare("
            INSERT INTO GuestUserOrder
            SET
                email = ?,
                first_name = ?,
                last_name = ?,
                phone = ?,
                city = ?,
                address = ?,
                ZIPCode = ?,
                orderNum = ?,
                placed_on = ?,
                payment_method = ?,
                order_status = ?,
                delivery_date = ?,
                pickupShopLocation = ?,
                cardNum = ?,
                expDate = ?,
                cvv = ?
        "); 
        $setOrder->execute([$email, $first_name, $last_name, $phone, $city, $address, $ZIPCode, (string)$orderCode, $placed_on, $payment_method, 'Ongoing', $delivery_date_valid, $shopLocation, $cardNum, $expDateChecked, $cvvChecked]);


        $guestOrderID = $connection->lastInsertId(); // do not move

        $c_items = Cart::getItems();
        foreach ($c_items as $item){
            $insertToOrdItems = $connection->prepare("
                INSERT INTO Guest_Order_Items(guestOrderID, readyMadeID, quantity)
                VALUES(?, ?, ?)
            ");
            $insertToOrdItems->execute([$guestOrderID, $item['readyMadeID'], $item['quantity']]);
        }
    }

    

    if (isset($_SESSION['userIn']) && isset($_POST['submit_order']) && isset($_SESSION['promo_code_success'])){
        $deactivatePromoCode = $connection->prepare("
            UPDATE PromoCode p
            JOIN User_PromoCode up
            ON p.promoCodeID = up.promoCodeID
            SET status = ?
            WHERE up.userID = ? and up.promoCodeID = ?;
        ");
        $deactivatePromoCode->execute(["Used", $userID, $usedPromoID]);
    }


    if (isset($_SESSION['userIn']) && isset($_POST['submit_order'])){

        $getCartInfo = $connection->prepare("
            SELECT *
            FROM Cart
            WHERE userID = ?
        "); 
        $getCartInfo->execute([$userID]);
        $getCartInfo = $getCartInfo->fetch();

        $cartID = $getCartInfo['cartID'];


        $clearCart = $connection->prepare("
            DELETE
            FROM Cart_Items
            WHERE cartID = ?;
        ");
        $clearCart->execute([$cartID]);


        $deleteCart = $connection->prepare("
            DELETE
            FROM Cart
            WHERE cartID = ?;
        ");
        $deleteCart->execute([$cartID]);

    } else {
        // guest user cart clear
        $_SESSION['cart'] = [];
    }

    unset ($_SESSION['post_info']);
    unset($_SESSION['promo_code_used']);


    $_SESSION['order_complete'] = true;
    header("Location: ../order-success-page.php");
    exit; // redirect to "order successfull" page
?>