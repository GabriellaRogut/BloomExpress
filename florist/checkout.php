<?php 
    include("DB/connection.php");
    include("config/config.php");
    include("php-action-files/guestCart.php");


    if (isset($_SESSION['userIn'])){
        $userID = $_SESSION['user']['userID'];

        $userData = $connection->prepare("
            SELECT * 
            FROM User 
            WHERE userID = ?;
        "); 
        $userData->execute([$userID]); 
        $data = $userData->fetch();

        unset($data['promo_code']);



        // check for antything in the cart (if there's nothing, don't show the form /for redirecting back/)
        $cart = $connection->prepare("
            SELECT ci.cartItemID 
            FROM Cart c
            JOIN Cart_Items ci
            ON c.cartID = ci.cartID
            WHERE userID = ?;
        "); 
        $cart->execute([$userID]); 
        $cart = $cart->fetch();
    } 


    if (isset($_SESSION['post_info'])){
        $data = $_SESSION['post_info'];
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BloomExpress - Checkout</title>
    <link rel="icon" type="image/x-icon" href="images/logo-ico.ico">

    <?php include("elements/links.php")?>

    <link rel="stylesheet" href="styles/style.css?v=<?= time() ?>">
</head>


<body class="co-body">
<?php 
    // printrfunc( $_SESSION['cart']);
    if (@$cart || @$_SESSION['cart']){
?>

    <div class="col-12 checkout-container">

        <img src="images/logo.png" alt="logo" class="logo co-logo">


        <!-- Left - Form -->
        <form action="php-action-files/checkout-action.php" method="POST" class="checkout-form">
 
            <h4 class="co-title">Contact Information</h4>
            <div class="flex-inp">
                <input required type="email" name="email" placeholder="Email" class="inp" value="<?= isset( $data['email'] ) ? $data['email'] : "" ?>">
                <input required type="text" name="phone" placeholder="Phone number" class="inp phone-inp" value="<?= isset( $data['phone'] ) ? $data['phone'] : "" ?>">
            </div>


            <h4 class="co-title">Shipping Address</h4>
            <div class="flex-inp">
                <input required type="text" name="first_name" placeholder="First name" class="inp half" value="<?= isset( $data['first_name'] ) ? $data['first_name'] : "" ?>">
                <input required type="text" name="last_name" placeholder="Last name" class="inp half" value="<?= isset( $data['last_name'] ) ? $data['last_name'] : "" ?>">
            </div>

            <input type="text" name="address" placeholder="Address" class="inp" value="<?= isset( $data['address'] ) ? $data['address'] : "" ?>">

            <div class="flex-inp">
                <input required type="text" name="city" placeholder="City" class="inp half" value="<?= isset( $data['city'] ) ? $data['city'] : "" ?>">
                <input required type="text" name="ZIPCode" placeholder="ZIP code" class="inp half" value="<?= isset( $data['ZIPCode'] ) ? $data['ZIPCode'] : "" ?>">
            </div>


            <h4 class="co-title"> Delivery/Fetch Date</h4>
            <input required type="date" name="delivery_date" class="inp" value="<?= isset( $data['delivery_date'] ) ? $data['delivery_date'] : "" ?>">


            <?php 
                if (isset($_GET['delivery_date']) && $_GET['delivery_date'] == 'invalid'){ 
            ?>
                    <p class="error">&#10005; Delivery must be scheduled at least 1 day in advance. Otherwise, please select "Fetch at Shop".</p>
            <?php 
                } else if (isset($_GET['delivery_date_fetch']) && $_GET['delivery_date_fetch'] == 'invalid'){
            ?>
                    <p class="error">&#10005; Invalid Date Input. Date Can Not be in the Past.</p>
            <?php 
                } else {
            ?>
                    <p class="delivery-warning"> 
                        * Delivery must be scheduled at least 1 day in advance. Otherwise, please select "Fetch at Shop".
                    </p>
            <?php
                }
            ?>


            
            <div class="promo-code-section">
                <h4 class="co-title">Promo code</h4>
                
                <?php 
                    if (isset($_SESSION['promo_code_success'])){
                ?>
                        <input type="text" name="promo_code" placeholder="Promo Code" class="inp promo_inp" value="<?= isset( $data['promo_code'] ) ? $data['promo_code'] : "" ?>" readonly>
                        <button class="ss-btn promo-btn" value="apply_promo" name="apply_promocode" type="submit" disabled>Apply Promocode</button>
                        
                <?php 
                    } else {
                ?>
                        <input type="text" name="promo_code" placeholder="Promo Code" class="inp promo_inp" value="<?= isset( $data['promo_code'] ) ? $data['promo_code'] : "" ?>">
                        <button class="ss-btn promo-btn" value="apply_promo" name="apply_promocode" type="submit">Apply Promocode</button>
                <?php
                    }
                ?>
                
                <div style="clear: both;"></div> 


                <?php 
                    if (isset($_GET['promo']) && $_GET['promo'] == 'invalid'){ 
                ?>
                        <p class="error">&#10005; Invalid promo code.</p>
                <?php 
                    } 
                ?>
               
            </div>



            <h4 class="co-title">Payment Method</h4>
            <div class="payment-options">
                <label class="pay-opt">
                    <input type="radio" name="payment" class="pay-opt-radio" id="payOnDelivery" onclick="togglePayment()" value="Pay on Delivery"> Pay on Delivery
                </label>
                <label class="pay-opt">
                    <input type="radio" name="payment" class="pay-opt-radio" id="payOnline" onclick="togglePayment()" value="Pay Online" <?php if ((isset($_GET['expire_date']) && $_GET['expire_date'] == 'invalid') || (isset($_GET['cvv']) && $_GET['cvv'] == 'invalid')) { ?>checked<?php } ?>> Pay Online
                </label>
                <label class="pay-opt">
                    <input type="radio" name="payment" class="pay-opt-radio" id="fetchShop" onclick="togglePayment()" value="Fetch at Shop"> Fetch at Shop
                </label>
            </div>


            <div id="shopSelect">
                <p class="co-title-sm">Select Pickup Location</p>
                
                <select id="citySelect" name="shopLocationSelect" class="inp" onchange="updateShops()">
                    <option>-- Select Shop --</option>
                    <option value="loc-1" class="sel-opt">Pravets, bul. 3 mart</option>
                    <option value="loc-2" class="sel-opt">Sofia, zk Studentski grad</option>
                </select>

            </div>



            <div id="cardSelect">
                <input type="text" name="cardNum" placeholder="Card number" class="inp" value="<?= isset( $data['cardNum'] ) ? $data['cardNum'] : "" ?>">

                <div class="flex-inp">
                    <input type="text" name="expDate" placeholder="MM/YY" class="inp half" value="<?= isset( $data['expDate'] ) ? $data['expDate'] : "" ?>">
                    <input type="text" name="cvv" placeholder="CVV" class="inp half" value="<?= isset( $data['cvv'] ) ? $data['cvv'] : "" ?>">
                </div>

                <?php 
                    if (isset($_GET['expire_date']) && $_GET['expire_date'] == 'invalid'){ 
                ?>
                        <p class="error expire_date_err">&#10005; Invalid Expiration Date Format or Values.</p>
                <?php 
                    } 
                ?>


                <?php 
                    if (isset($_GET['cvv']) && $_GET['cvv'] == 'invalid'){ 
                ?>
                        <p class="error">&#10005; Invalid CVV.</p>
                <?php 
                    } 
                ?>
            </div>

            <?php 
                if ( ( isset($_GET['expire_date']) && $_GET['expire_date'] == 'invalid' ) || ( isset($_GET['cvv']) && $_GET['cvv'] == 'invalid' )){ 
            ?>
                    <script>
                        window.onload = function(e){ 
                            togglePayment();
                        }
                    </script>
            <?php 
                }
            ?>


            <div class="checkout-btns">
                <button class="ss-btn checkout-btn" type="Submit" value="SubmitOrder" name="submit_order">Order</button>
                <?php 
                    $orderCode = '';
                    function generateOrderNumber($length = 6) {
                        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                        $orderNum = '#';
                            
                        for ($i = 0; $i < $length; $i++) {
                            $orderNum .= $characters[rand(0,strlen($characters)-1)];
                        }
                        return $orderNum;
                    }
                    $orderCode = generateOrderNumber();
                ?>

                <input type="hidden" name="orderCodeInput" value='<?= $orderCode ?>'>

                <a class="ss-btn checkout-btn back-to-cart-btn" href="cart.php">Back to Cart<i class="fa-solid fa-bag-shopping bag-icon"></i></a>
                
            </div>

        </form>





        <!-- Right - Total -->
        <div class="order-summary">
            <h4 class="co-title summary-title">Order Summary</h4>

            <?php 
                $delivery = 2.20;

                if (isset($userID)) {
                    $totalArr = $connection->prepare("
                        SELECT total_price_cart 
                        FROM Cart 
                        WHERE userID = ?
                    ");
                    $totalArr->execute([$userID]);
                    $total = $totalArr->fetch();

                } else {
                    $totalS = Cart::getTotalSession(); // call static

                    
                }
            ?>

            <?php
                if (isset($userID)) { 
            ?> 
                    <p>Subtotal: <span class="total-price"><?= number_format($total['total_price_cart'], 2, ".") ?></span></p>
                    <p>Transport: <span class="total-price"><?= number_format($delivery, 2, ".") ?></span></p>
                    <hr>

                    <p><strong>Total: <span class="total-price"><?= number_format($total['total_price_cart'] + $delivery, 2, ".") ?></span></strong></p>
            <?php 
                } else {
            ?>
                    <p>Subtotal: <span class="total-price"><?= number_format($totalS, 2, ".") ?></span></p>
                    <p>Transport: <span class="total-price"><?= number_format($delivery, 2, ".") ?></span></p>
                    <hr>

                    <p><strong>Total: <span class="total-price"><?= number_format($totalS + $delivery, 2, ".") ?></span></strong></p>
            <?php 
                }
            ?>

            
        </div>

    </div>

    <?php include("elements/footer.php")?>
         
    <?php 
        } 
    ?>


    <!-- prevent going back to form and resubmitting -->
     <script>
        function preventBack() {
            window.history.forward(); 
        }
        
        setTimeout("preventBack()", 0);
        
        window.onunload = function () { null };
    </script>

</body>
</html>
