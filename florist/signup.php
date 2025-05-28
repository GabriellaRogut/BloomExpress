<?php
    // function generatePromoCode($length = 6) {
    //     $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    //     $promoCode = '';
            
    //     for ($i = 0; $i < $length; $i++) {
    //         $promoCode .= $characters[rand(0, strlen($characters)-1)];
    //     }
    //     return $promoCode;
    // }
 


    if ( isset( $_POST['submitSU'] ) ) {

        $email = $_POST['email'];
        $password = $_POST['password'];
        $password_confirm = $_POST['password_confirm'];

        $errors = array();
        
        $stmt = $connection->prepare("SELECT * FROM User WHERE email = ?"); 
        $stmt->execute([ $email ]); 
        $user = $stmt->fetch();

        if ( $user ) {
            $errors[] = "Account already exists";
        }

        if( $password != $password_confirm ) {
            $errors[] = "Passwords do not match";
        }


        if (!$errors) {
            $promo_code = generatePromoCode();
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            $promoType = "New User";
            $expirationDate = null;


            $insertPromo = $connection->prepare("
                INSERT INTO PromoCode (promo_code, type, expirationDate)
                VALUES (?, ?, ?)
            ");
            $insertPromo->execute([$promo_code, $promoType, $expirationDate]);
            $promoCodeID = $connection->lastInsertId();

        
            $sql = "INSERT INTO User (email, password) VALUES (?, ?)";
            $connection->prepare($sql)->execute([$email, $hashedPassword]);

            
            $stmt = $connection->prepare("
                SELECT * 
                FROM User 
                WHERE email = ?
            ");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

        
            $linkPromo = $connection->prepare("
                INSERT INTO User_PromoCode (userID, promoCodeID, status)
                VALUES (?, ?, ?)
            ");
            $linkPromo->execute([$user['userID'], $promoCodeID, "Available"]);


            $_SESSION['user'] = $user;
            $_SESSION['signUpSuccess'] = true;
            $_SESSION['userIn'] = true;


            echo "<script>document.location.href='account.php';</script>";
            exit;
        }


    }
?>

<div class="popup-overlay" id="overlay" onclick="closePopup()"></div>

<div class="popup" id="popup">
    <span class="close-btn" onclick="closePopup()">✖</span>
    <div class="signup-container">
        <div class="left">
            <h2 class="welcome-txt">Welcome!</h2>
            <p>Sign Up for <span class="promo-txt">10% OFF</span> on First Order!</p>
            
            <?php
                if ( isset( $errors ) ) {

                    foreach( $errors as $error ) {
                        echo "<div class='error'>". $error . "</div>";
                    }
                }
            ?>

            <form method="post">
                <div class="left-centered-txt">
                        <div class="input-div">
                            <label for="email">Email</label>
                            <input type="email" name="email" placeholder="Enter your email" class="inp" value="<?= @$email ?>"> 
                        </div>

                        <div class="input-div">
                            <label for="pass">Password</label>
                            <input type="password" name="password" placeholder="Enter your password" class="inp">
                        </div>

                        <div class="input-div">
                            <label for="pass2">Confirm Password</label>
                            <input type="password" name="password_confirm" placeholder="Confirm your password" class="inp">
                        </div>
                    </div>

                <button type="submit" class="ss-btn signup-btn" name="submitSU" value="enter">Sign Up</button>

            </form>

            <a onclick="openSPopup(); closePopup()"><p class="profile-opt">Already have a profile?</p></a>
        </div>
    </div>
</div>

