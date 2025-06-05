<?php 
    include("DB/connection.php");
    include("config/config.php");
    include("php-action-files/guestCart.php"); 
?>

<?php
    if (isset( $_SESSION['userIn'] )) {
        $userID = $_SESSION['user']['userID'];


        // +/- bttns
        if ( isset($_POST['submitPlus']) || isset($_POST['submitMin']) ){

            $stmt = $connection->prepare("
                UPDATE Cart_Items 
                JOIN Cart ON Cart_Items.cartID = Cart.cartID
                SET quantity = ? 
                WHERE cartItemID = ? AND userID = ?
            "); 
            $stmt->execute([ $_POST['quantity'], $_POST['cartItemID'], $userID]); 

        }

        // get cart items for the user
        $items_cart = $connection->prepare("
            SELECT *
            FROM Cart_Items
            JOIN Cart ON Cart_Items.cartID = Cart.cartID
            JOIN Ready_Made_Bouquets ON Ready_Made_Bouquets.readyMadeID = Cart_Items.readyMadeID
            WHERE Cart.userID = ?;
        ");
        $items_cart->execute([$userID]);
        $items_cart = $items_cart->fetchAll();
    


    } else {
        // session cart for guests (obj)
        $guestCart = new Cart();
        
        // get items for the session if there are any
        $guestItems = $guestCart->getItems();

        // get items ID
        $guestItemID = [];
        foreach($guestItems as $guestItem){
            $guestItemID[] = $guestItem['readyMadeID'];
        }


        if (!empty($guestItemID)) {
            $guestItemIDStr = implode(", ", $guestItemID);
            $items_cart = $connection->query("
                SELECT * 
                FROM Ready_Made_Bouquets
                WHERE readyMadeID IN ($guestItemIDStr);
            ")->fetchAll();

            foreach ($items_cart as &$i) {
                $i['quantity'] = $_SESSION['cart'][$i['readyMadeID']]['quantity'];
            }
        } else {
            $items_cart = [];
        }

        
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BloomExpress - My Cart</title>
    <link rel="icon" type="image/x-icon" href="images/logo-ico.ico">
    <?php include("elements/links.php") ?>
    <link rel="stylesheet" href="styles/style.css?v=<?= time() ?>">
</head>

<body>
    <?php include("elements/offcanvas.php") ?>

    <div class="row">
        <div class="col-2 col-xl-1 sidebar-col">
            <div class="sidebar">
                <div class="logo-row">
                    <a href="index.php"><img class="sb-logo" src="images/logo.png" alt="logo"></a>
                </div>
                <a href="index.php">Home</a>
                <a href="shop.php">Shop</a>
                <a href="cart.php" class="active">Cart</a>
                <a href="#" data-bs-toggle="offcanvas" data-bs-target="#offcanvasOrders">Orders</a>
                <a href="account.php">Account</a>
            </div>
        </div>


        <!-- Cart Side  -->
        <div class="col-10 col-xl-11">
            <div class="row">
                <div class="col-12 col-xl-9">
                    <div class="row titles-row">
                        <div class="col-3 title-col">Product</div>
                        <div class="col-3 title-col">Price</div>
                        <div class="col-3 title-col">Quantity</div>
                        <div class="col-3 title-col">Total</div>
                    </div>

                    <div class="row cart-items-container">
                        

                        <?php if ($items_cart) { //if(isset(var)) -> exists but is empty; if(var) -> exists or not ?>

                            
                            <?php 
                                foreach ($items_cart as $item_cart){ 
                            ?>

                                <?php

                                    $isGuest = !isset($item_cart['cartItemID']);
                                    
                                    $ddData = $connection->query(" 
                                        SELECT f.flowerName 
                                        FROM R_M_Bouquets_Flowers bf 
                                        LEFT JOIN flowers f ON bf.flowerID = f.flowerID 
                                        WHERE bf.readyMadeID = " . $item_cart['readyMadeID']
                                    )->fetchAll();
                                ?>

                                <div class="card cart-card cart-selector">
                                    <hr class="cart-c-hr hr-top">

                                    <div class="row">
                                        <div class="col-3 img-cart-col">
                                            <img src="<?= $item_cart['image_path'] ?>" class="img-fluid" alt="product">
                                            <h5 class="card-title"><?= $item_cart['name'] ?></h5>
                                            <p class="card-text"><small>Size: <span class="cart-size"><?= $item_cart['size'] ?></span></small></p>
                                        </div>

                                        <div class="col-3 cart-col">
                                            <h5><?= $item_cart['price'] ?></h5>
                                        </div>

                                        <div class="col-3 cart-col">
                                            <?php 
                                                if (!$isGuest) {
                                            ?> 
                                                    <form method="POST">
                                                        <div class="quantity-selector">
                                                            <button type="submit" name="submitMin" value="submitLess" class="decrease">−</button>
                                                                <input type="hidden" name="cartItemID" value="<?= $item_cart["cartItemID"] ?>">
                                                                <input type="hidden" class="quantity-field" name="quantity" value="<?= $item_cart['quantity'] ?>"><span class="quantity quantity-cart"><?= $item_cart['quantity'] ?></span>
                                                            <button type="submit" name="submitPlus" value="submitMore" class="increase">+</button>
                                                        </div>
                                                    </form>
                                            <?php 
                                                } else { 
                                            ?>
                                                    <form method="POST" action="php-action-files/guest-quantity-upd.php">
                                                        <div class="quantity-selector">
                                                            <button type="submit" name="action" value="gDecrease" class="decrease">−</button>
                                                                <input type="hidden" name="cartItemID" value="<?= $item_cart["readyMadeID"] ?>">
                                                                <span class="quantity quantity-cart"><?= $_SESSION['cart'][$item_cart['readyMadeID']]['quantity'] ?></span>
                                                            <button type="submit" name="action" value="gIncrease" class="increase">+</button>
                                                        </div>
                                                    </form>             
                                            <?php } ?>
                                        </div>

                                    
                                            <div class="col-3 cart-col">
                                                    <h5><?= number_format(($item_cart['price']) * ($item_cart['quantity']), 2, ".") ?></h5>
                                            </div>
                                        


                                        <?php 
                                            if (!$isGuest) {
                                        ?> 
                                                <form  class="remove-button" action="php-action-files/remove-from-cart.php" method="POST">
                                                    <input type="hidden" name="cart_item_removeID" value="<?= $item_cart["cartItemID"] ?>">
                                                    <button class="remove-button" type="submit" name="remove_item">&#10005;</button>
                                                </form>
                                        <?php 
                                            } else {
                                        ?>

                                                <form class="remove-button" action="php-action-files/guest-remove-item.php" method="POST">
                                                    <input type="hidden" name="guest_item_removeID" value="<?= $item_cart["readyMadeID"] ?>">
                                                    <button class="remove-button" type="submit" name="remove_item">&#10005;</button>
                                                </form>

                                        <?php 
                                            }
                                        ?>    


                                        <div class="cart-dropdown-row">
                                            <button class="bq-dropdown-btn" onclick="toggleDropdown(this)">
                                                <span class="rotating-arrow">▼</span> Flowers used
                                            </button>
                                            

                                            <div class="bq-dropdown-content">
                                                <?php
                                                     $j = 0; 
                                                    foreach($ddData as $data) {
                                                        if ($j > 0) {  
                                                            echo " ";  
                                                        }  
                                                        echo $data['flowerName'];  
                                                        $j++;
                                                    }
                                                ?> 
                                            </div>
                                        </div>

                                    </div>

                                    <hr class="cart-c-hr hr-btm">
                                </div>
                            <?php 
                                } 
                            ?>

                        <?php 
                            } else { 
                        ?>
                            <div class="card no-acc-div empty-cart-div">
                                <div class="card-body no-acc-card">
                                    <h5 class="card-title empty-cart-txt">Your Cart is Empty</h5>
                                </div>
                            </div>

                        <?php 
                            } 
                        ?>

                    </div>
                </div>



                <!-- Checkout Side -->
                <div class="col-12 col-xl-3 checkout-col">
                    <hr class="checkout-hr">
                    <div class="checkout-cont">

                    
                        <?php 
                            if (isset($userID)){   
                                $total = $connection->prepare("
                                    SELECT total_price_cart
                                    FROM Cart
                                    WHERE userID = ?;
                                "); 
                                $total->execute([$userID]);
                                $total = $total->fetch();
                            

                                $total_c_price = 0.00;

                                if (isset($items_cart)){
                                    foreach($items_cart as $item_cart){
                                        $total_c_price = $total_c_price + ($item_cart["quantity"] * $item_cart["price"]);
                                    } 
                                } 

                                $cart = $connection->query("
                                    UPDATE Cart
                                    SET total_price_cart = $total_c_price;
                                ");

                                unset($_SESSION['promo_code_success']);
                                unset($_SESSION['post_info']['promo_code']);

                            } else {
                                $total_c_price = $guestCart->getTotal();
                            }
                        ?>

                        
                        <p class="cart-total-title cart-txt">cart total: <span class="total-price cart-txt"><?= number_format($total_c_price, 2, ".") ?></span></p>
                        <p>Transport and taxes calculated at checkout</p><br><br>

                        <form method="POST" action="php-action-files/termsAndConditions.php">
                            <p class="cart-txt">
                                <input type="checkbox" class="tc-checkbox" value="on" name="terms_and_conditions"> I agree to <span class="tc-span termsAndConditionsLink"><a onclick="openTermsAndConditions()">Terms and Conditions</a></span>  
                            </p>

                            <?php
                                if (isset($_SESSION['errorCheckbox']) && count($_SESSION['errorCheckbox'])){
                                    foreach( $_SESSION['errorCheckbox'] as $error ) {
                                        echo "<div class='error tcErr'>". $error . "</div>";
                                    }
                                }
                            ?>

                            <!-- only allow checkout if there are any items in the cart and terms_and_conditions is checked -->
                            <?php 
                                if ((isset($items_cart) && !empty($items_cart))) { ?>
                                    
                                        <button class="ss-btn checkout-btn" name="checkout" value="checkout">Checkout<i class="fa-solid fa-bag-shopping bag-icon"></i></button>
                                    
                            <?php 
                                } else { 
                            ?>
                                    <button disabled style="cursor: auto;" class="ss-btn checkout-btn" name="checkout" value="checkout">Checkout<i class="fa-solid fa-bag-shopping bag-icon"></i></button>
                            <?php 
                                } 
                            ?>

                        </form>


                        <!-- Terms and Conditions Popup -->
                        <div class="popup-overlay" id="tcOverlay" onclick="closeTermsAndConditions()"></div>
                        <div class="popup s-popup" id="tcPopup">
                            <span class="close-btn" onclick="closeTermsAndConditions()">✖</span>
                            <div class="login-container">
                                <div class="left termsAndConditionsPopup">
                                    <h4><span>❁</span> BloomExpress <span>❁</span><br> Terms and Conditions</h4>
                                    <p><strong><i>Last Updated: 2025-05-06</i></strong></p>

                                    <p>Welcome to BloomExpress ("Company", "we", "our", "us"). These Terms and Conditions ("Terms") govern your use of our website [www.bloomexpress.com] (the "Site") and the sale and delivery of flower bouquets and related products and services (collectively, the "Services").</p>

                                    <p>By accessing or using our Services, you agree to be bound by these Terms. If you do not agree, please do not use our Services.</p>

                                    <h5>1. Eligibility</h5>
                                    <p>You must be at least 18 years of age or have legal parental or guardian consent to use our Services. By placing an order, you confirm that all information provided is true and accurate.</p>

                                    <h5>2. Products and Orders</h5>

                                    <h6>2.1 Product Availability</h6>
                                    <p>All floral products are subject to availability. If certain flowers or items are unavailable, we reserve the right to substitute with similar products of equal or greater value.</p>

                                    <h6>2.2 Order Acceptance</h6>
                                    <p>We reserve the right to refuse or cancel any order for reasons including but not limited to product availability, pricing errors, or suspected fraud.</p>

                                    <h5>3. Pricing and Payment</h5>
                                    <p>All prices are in [Insert Currency] and include applicable taxes unless otherwise noted. Payment is due at the time of order. We accept major credit/debit cards and any other methods displayed on our Site.</p>

                                    <h5>4. Delivery</h5>

                                    <h6>4.1 Delivery Areas</h6>
                                    <p>We deliver to designated regions listed on our website. Orders outside our service areas may not be fulfilled.</p>

                                    <h6>4.2 Delivery Times</h6>
                                    <p>We strive to deliver on the requested date but cannot guarantee specific delivery times due to external factors.</p>

                                    <h6>4.3 Incorrect Information</h6>
                                    <p>We are not responsible for missed deliveries due to incorrect or incomplete delivery information provided by the customer.</p>

                                    <h5>5. Cancellations & Refunds</h5>

                                    <h6>5.1 Changes & Cancellations</h6>
                                    <p>Orders can be modified or canceled up to 24 hours before the scheduled delivery. Contact us at [Insert Contact Email].</p>

                                    <h6>5.2 Refunds</h6>
                                    <p>Refunds or replacements will be considered for damaged, missing, or incorrect orders reported within 24 hours of delivery, with photo proof.</p>

                                    <h5>6. Intellectual Property</h5>
                                    <p>All content on the Site, including logos, images, and product designs, is owned by BloomExpress or licensed to us. Unauthorized use or reproduction is prohibited.</p>

                                    <h5>7. Prohibited Conduct</h5>
                                    <p>You agree not to:</p>
                                    <ul>
                                    <li>Use our Services for unlawful purposes.</li>
                                    <li>Disrupt or interfere with the Site or servers.</li>
                                    <li>Attempt to gain unauthorized access to any part of the Site.</li>
                                    </ul>

                                    <h5>8. Limitation of Liability</h5>
                                    <p>BloomExpress shall not be liable for any indirect, incidental, or consequential damages arising from the use of our Services. Our liability is limited to the amount paid for your purchase.</p>

                                    <h5>9. Indemnification</h5>
                                    <p>You agree to indemnify and hold harmless BloomExpress and its employees, officers, and affiliates from any claims, damages, or losses arising from your use of the Services or breach of these Terms.</p>

                                    <h5>10. Governing Law</h5>
                                    <p>These Terms are governed by the laws of Bulgaria, without regard to its conflict of law rules.</p>

                                    <h5>11. Changes to Terms</h5>
                                    <p>We reserve the right to update these Terms at any time. Updates will be posted on this page. Continued use of our Services after changes means you accept the new Terms.</p>

                                    <h5>12. Contact Us</h5>
                                    <p>If you have any questions, please contact us:</p>
                                    <p>
                                    Email: <i> contact@bloomexpress.com </i><br>
                                    Phone: <i> +359 00 000 0000 </i><br>
                                    Address: <i> Pravets, Bulgaria </i><br>
                                    Website: <i> www.bloomexpress.com </i>
                                    </p>

                                    <h4><span>❁</span> BloomExpress <span>❁</span> <br> Privacy Policy</h4>
                                    <p><strong><i>Last Updated: 2025-05-06</i></strong></p>

                                    <p>BloomExpress ("we", "us", or "our") respects your privacy and is committed to protecting your personal information. This Privacy Policy explains how we collect, use, share, and protect your information when you use our website [www.bloomexpress.com] or any of our Services.</p>

                                    <h5>1. Information We Collect</h5>
                                    <p>We collect the following types of information:</p>
                                    <ul>
                                    <li><strong>Personal Information:</strong> Name, email address, phone number, billing and shipping addresses, payment information.</li>
                                    <li><strong>Order Details:</strong> Product selection, delivery preferences.</li>
                                    <li><strong>Technical Data:</strong> IP address, browser type, device information, cookies, and analytics data.</li>
                                    </ul>

                                    <h5>2. How We Use Your Information</h5>
                                    <p>We use your information to:</p>
                                    <ul>
                                    <li>Process and deliver your orders;</li>
                                    <li>Communicate with you about your order and customer support;</li>
                                    <li>Improve and personalize your experience;</li>
                                    <li>Send promotional offers or updates (only with your consent);</li>
                                    <li>Prevent fraud or misuse of our Services.</li>
                                    </ul>

                                    <h5>3. Sharing Your Information</h5>
                                    <p>We do not sell your personal data. We only share your information with:</p>
                                    <ul>
                                    <li>Payment processors to handle transactions;</li>
                                    <li>Delivery partners for fulfilling your order;</li>
                                    <li>Service providers that assist with website hosting, analytics, or marketing (under strict confidentiality agreements);</li>
                                    <li>Authorities if required by law.</li>
                                    </ul>

                                    <h5>4. Cookies and Tracking Technologies</h5>
                                    <p>We use cookies and similar tools to enhance user experience, track website usage, and deliver relevant advertisements. You may adjust your browser settings to disable cookies, but some features may not function properly.</p>

                                    <h5>5. Your Rights</h5>
                                    <p>Depending on your location, you may have the right to:</p>
                                    <ul>
                                    <li>Access, correct, or delete your personal data;</li>
                                    <li>Withdraw consent for marketing communications;</li>
                                    <li>Lodge a complaint with a data protection authority.</li>
                                    </ul>
                                    <p>To exercise your rights, contact us at contact@bloomexpress.com.</p>

                                    <h5>6. Third-Party Links</h5>
                                    <p>Our Site may contain links to external websites. We are not responsible for the privacy practices of those third parties.</p>

                                    <h5>7. Children's Privacy</h5>
                                    <p>Our Services are not intended for children under 13. We do not knowingly collect personal information from children.</p>

                                    <h5>8. Changes to This Policy</h5>
                                    <p>We may update this Privacy Policy from time to time. Updates will be posted on this page with a new effective date.</p>

                                    <h5>9. Contact Us</h5>
                                    <p>If you have any questions or concerns about this Privacy Policy, please contact us:</p>
                                    <p>
                                    Email: <i> contact@bloomexpress.com </i><br>
                                    Phone: <i> +359 00 000 0000 </i><br>
                                    Address: <i> Pravets, Bulgaria </i><br>
                                    Website: <i> www.bloomexpress.com </i>
                                    </p>

                                </div>
                            </div>
                        </div>




                        <?php 
                            if (isset($userID)){
                                $cart_info = $connection->query("
                                    SELECT *
                                    FROM Cart
                                    WHERE Cart.userID = $userID;
                                ")->fetch();
                            }
                        ?>


                        <!-- only allow clearing the cart if there are any items in it -->
                        <?php 
                            if (isset($items_cart) && !empty($items_cart)) { 

                                foreach ($items_cart as $item_cart){
                                    $isGuest = !isset($item_cart['cartItemID']);
                                }
                                  
                                
                                if (!$isGuest) {
                            ?>
                                    <form action="php-action-files/delete-cart.php" method="POST">
                                        <input type="hidden" name="cart_delete_ID" value="<?= $cart_info["cartID"] ?>">
                                        <button class="ss-btn checkout-btn empty-btn" type="submit" name="delete_cart">Empty Cart &#9447;</button>
                                    </form>
                            <?php 
                                } else { 
                            ?>
                                    <form action="php-action-files/delete-guest-cart.php" method="POST">
                                        <button class="ss-btn checkout-btn empty-btn" type="submit" name="delete_g_cart">Empty Cart &#9447;</button>
                                    </form>
                            <?php  
                                }
                            } else { 
                        ?>
                                <button disabled style="cursor: auto;" class="ss-btn checkout-btn empty-btn">Empty Cart &#9447;</button>
                        <?php 
                            } 
                        ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include("elements/footer.php") ?>

</body>
</html>