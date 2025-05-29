<?php 
    include("DB/connection.php");
    include("config/config.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BloomExpress - Home</title>
    <link rel="icon" type="image/x-icon" href="images/logo-ico.ico">

    <?php include("elements/links.php")?>

    <link rel="stylesheet" href="styles/style.css?v=<?= time() ?>">
</head>


<body>
    <?php include("elements/offcanvas.php") ?>
    <?php include("login.php") ?>
    <?php include("signup.php") ?>
    

    <div class="row">

        <div class="col-2 col-xl-1 sidebar-col">
            <div class="sidebar">

                <div class="logo-row">
                    <a class="logo-a" href="index.php"><img class="sb-logo" src="images/logo.png" alt="logo"></a>
                </div>
                
                <a href="index.php" class="active">Home</a>
                <a href="shop.php" >Shop</a>
                <a href="cart.php">Cart</a>
                <a href="#" data-bs-toggle="offcanvas" data-bs-target="#offcanvasOrders">Orders</a>
                <a href="account.php">Account</a>
            </div>
        </div>


        <div class="col-10 col-xl-11">

            <div class="row">
                <div class="index-nav">
                    <?php include("elements/navbar.php") ?>
                </div>
            </div>


            <div class="row">
                <div class="carousel index-carousel">
                    <div class="carousel-inner index-photo-inner">
                        <img src="images/index-photo.jpg" class="index-photo">
                        <div class="carousel-caption">
                            <h5 class="caption-text">Bloom Express</h5>
                            <button class="ss-btn" onclick="openSPopup()">Log In</button>
                            <button onclick="openPopup()" class="ss-btn">Sign Up</button>
                        </div>
                    </div>
                </div>
            </div>
                    

            <div class="row about-us">
                <div class="col-12">
                    <h2 class="index-title">About Us</h2>
                    <p class="index-text">
                        At BloomExpress, we believe that flowers have the power to brighten every moment. 
                        Our expert florists carefully craft each bouquet with love, ensuring freshness and elegance in every arrangement. 
                        Whether you're celebrating a special occasion or just want to make someone smile, we’re here to deliver beauty to your doorstep.
                    </p>
                </div>
            </div>

            <div class="row how-to-order">
                <div class="col-12">
                    <h2 class="index-title">How to Order</h2>
                    <p class="index-text order-text">
                        Ordering flowers with BloomExpress is simple and convenient! Choose from our beautifully curated bouquets 
                        or customize your own arrangement to make it truly special. Just add your selection to the cart, 
                        select your delivery date, and let us handle the rest. Fresh, elegant flowers delivered straight to your door!
                    </p>
                </div>
            </div>

            <div class="row gallery">
                <div class="col-12">
                    <h2 class="index-title">Our Happy Clients</h2>
                         
                    <div class="container">
                        <div class="row">
                            <div class="gallery horizontal-gallery">

                            <?php
                                $data = $connection->query("SELECT * FROM Gallery")->fetchAll();
                            ?>

                            <?php foreach( $data as $image ) { ?>
                                <img class="gallery-slide" src="<?= $image['image_path'] ?>">
                            <?php } ?>
 
                            </div>                    
                        </div>
                    </div>

                </div>
            </div>



        </div>

    </div>


    <?php include("elements/footer.php")?> 
    
</body>
</html>
