<?php 
if (isset( $_POST['submitLI'] ) ) {

	$email = $_POST['email'];
	$password = $_POST['password'];

    $errorsLI = array();

    if ( !$errorsLI ) {

        $stmt = $connection->prepare("SELECT * FROM User WHERE email = ?"); 
        $stmt->execute([ $email ]); 
        $user = $stmt->fetch();
        
        if ( $user && (password_verify($password, $user['password']))) {
            $_SESSION['user'] = $user;
            $_SESSION['userIn'] = true;
            
            echo "<script>document.location.href='account.php';</script>"; // redirect to account.php
            exit;
            
        } else {
            $errorsLI[] = "Invalid entry";
        }
    }
}
?>	

<div class="popup-overlay" id="Soverlay" onclick="closeSPopup()"></div>

<div class="popup s-popup" id="Spopup">
    <span class="close-btn" onclick="closeSPopup()">✖</span>
    <div class="login-container">
        <div class="left">
            <h2 class="welcome-txt">Welcome Back!</h2>

            <?php
                if ( isset( $errorsLI ) ) {

                    foreach( $errorsLI as $error ) {
                        echo "<div class='error'>". $error . "</div>";
                    }
                }
            ?>
            
            <form method="post">
                <div class="left-centered-txt">
                    <div class="input-div">
                        <label for="email">Email</label>
                        <input type="email" name="email" placeholder="Enter your email" class="inp">
                    </div>

                    <div class="input-div">
                        <label for="pass">Password</label>
                        <input type="password" name="password" placeholder="Enter your password" class="inp">
                    </div>
                </div>    

                <button type="submit" name="submitLI" value="1" class="ss-btn login-btn">Log In</button>

                <a onclick="openPopup(); closeSPopup()">
                    <p class="profile-opt">Don't have a profile yet?</p>
                    <p class="profile-opt-promo">Sign Up for <span class="promo-txt promo-txt-lin">10% OFF</span> on First Order!</p>
                </a>
            </form>
        </div>
    </div>
</div>