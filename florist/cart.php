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

                        <p class="cart-txt"><input type="checkbox" class="tc-checkbox"> I agree to <span class="tc-span">Terms and Conditions</span></p>


                        <!-- only allow checkout if there are any items in the cart -->
                        <?php 
                            if (isset($items_cart) && !empty($items_cart)) { ?>
                                <a href="checkout.php">
                                    <button class="ss-btn checkout-btn">Checkout<i class="fa-solid fa-bag-shopping bag-icon"></i></button>
                                </a>
                        <?php 
                            } else { 
                        ?>
                                <button disabled style="cursor: auto;" class="ss-btn checkout-btn">Checkout<i class="fa-solid fa-bag-shopping bag-icon"></i></button>
                        <?php 
                            } 
                        ?>




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