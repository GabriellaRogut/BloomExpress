<?php

    function printrfunc($data){
        echo "<pre>";
        print_r( $data );
        exit;
    }


// Change PromoCode Status
    $removeExpiredCode = $connection->query("
            UPDATE User_PromoCode up
            JOIN PromoCode p
            ON up.promoCodeID = p.promoCodeID
            SET up.status = 'Expired'
            WHERE p.expirationDate < CURDATE()
    ");


// Chnange Order Status
    $changeOrderStatus = $connection->query("
            UPDATE Orders
            SET order_status = 'Past'
            WHERE delivery_date < CURDATE()
    ");
    // same for guest orders



    function generatePromoCode($length = 6) {
            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            $promoCode = '';
                
            for ($i = 0; $i < $length; $i++) {
                $promoCode .= $characters[rand(0, strlen($characters)-1)];
            }
            return $promoCode;
    }



// Birthday PromoCode Creation
    if(isset( $_SESSION['userIn'])){
            $userID = $_SESSION['user']['userID'];


            $getUserBday = $connection->prepare("
                    SELECT birthday
                    FROM User
                    WHERE userID = ?
            ");
            $getUserBday->execute([$userID]);
            $bday = $getUserBday->fetch();


            if ($bday) {
            $userBday = strtotime($bday['birthday']);

            $checkExistingPromo = $connection->prepare("
                    SELECT p.promo_code
                    FROM PromoCode p
                    JOIN User_PromoCode up ON p.promoCodeID = up.promoCodeID
                    WHERE up.userID = ? AND p.type = 'Birthday'
                    ");
            $checkExistingPromo->execute([$userID]);
            $checkExistingPromo = $checkExistingPromo->fetch();


            if (!$checkExistingPromo && (date('m-d', $userBday) == date('m-d'))) {
                    $promo_code = generatePromoCode();
                    $expirationDate = date('Y-m-d', strtotime("+1 month"));


                    $createPromoCode = $connection->prepare("
                    INSERT INTO PromoCode (promo_code, type, expirationDate)
                    VALUES (?, ?, ?)
                    ");
                    $createPromoCode->execute([$promo_code, "Birthday", $expirationDate]);

                    $promoID = $connection->lastInsertId();

                    $linkPromoCode = $connection->prepare("
                            INSERT INTO User_PromoCode (userID, promoCodeID, status)
                            VALUES (?, ?, ?)
                    ");
                    $linkPromoCode->execute(array( $userID, $promoID, 'Available' ) );

            }
        }
    }

?>