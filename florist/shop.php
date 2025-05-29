<?php 
    include("DB/connection.php");
    include("config/config.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BloomExpress - Shop</title>
    <link rel="icon" type="image/x-icon" href="images/logo-ico.ico">
    
    <?php include("elements/links.php")?>

    <link rel="stylesheet" href="styles/style.css?v=<?= time() ?>">
</head>

<body>
    <?php include("elements/offcanvas.php") ?>

    <!-- Sidebar -->
    <div class="row">
        <div class="col-2 col-xl-1 sidebar-col">
            <div class="sidebar">

                <div class="logo-row">
                    <a href="index.php"><img class="sb-logo" src="images/logo.png" alt="logo"></a>
                </div>
                
                <a href="index.php">Home</a>
                <a href="shop.php" class="active">Shop</a>
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

            <!-- Custom Bouquets Section -->
            <div class="row custom-bouquet-section">
                <div class="col-12">

                        <div class="card customize-card">
                            <div class="card-body">
                                <h5 class="card-title customize-title">Customize Your Bouquet</h5>
                                <p class="card-text customize-text">Make your own design, by choosing size, flowers, ribbons..!</p>
                                <button class="ss-btn here-btn"><a href="customize-order.php">
                                    HERE
                                </a></button>
                            </div>
                        </div>
                </div>
            </div>

            <!-- Ready-Made Bouquets Section -->
            <div class="row ready-made-section">

                <div class="col-12 ready-made-title">
                    <p class="index-title shop-title"> Ready-Made Bouquets
                        <div class="shop-gold-line"></div>
                    </p>
                </div>



                <div class="row bq-section-row">
                    <div class="col-12 col-lg-2">
                        <button id="wedding-btn" class="bq-section active-section" onclick="showBqSection('wedding')">Weddings & Engagements</button>
                    </div>
                    <div class="col-12 col-lg-2">
                        <button id="bday-btn" class="bq-section" onclick="showBqSection('bday')">Birthdays</button>
                    </div>
                    <div class="col-12 col-lg-2">
                        <button id="sayily-btn" class="bq-section" onclick="showBqSection('sayily')">Say "I Love You"</button>
                    </div>
                    <div class="col-12 col-lg-2">
                        <button id="congrats-btn" class="bq-section" onclick="showBqSection('congrats')">Congratulations</button>
                    </div>
                    <div class="col-12 col-lg-2">
                        <button id="justbcz-btn" class="bq-section" onclick="showBqSection('justbcz')">Just Because</button>
                    </div>
                </div>





                <!-- Bouquets Content -->
                <div class="col-12" id="wedding-cont">
                    <div class="row bq-container">
                        <?php 
                            $bouquetW = $connection->query("
                                SELECT *
                                FROM Ready_Made_Bouquets
                                WHERE category = 'Weddings & Engagements'
                            ")->fetchAll();

                            if ( isset($bouquetW) ) {
                                foreach ($bouquetW as $bq) {
                                    $product = $bq;
                                    include("elements/product-card.php");
                                }
                            }
                        ?>
                    </div>
                </div>

                <div class="col-12" id="bday-cont">
                    <div class="row bq-container">
                    <?php 
                        $bouquetB = $connection->query("
                            SELECT *
                            FROM Ready_Made_Bouquets
                            WHERE category = 'Birthdays'
                        ")->fetchAll();

                        if ( isset($bouquetB) ) {
                            foreach ($bouquetB as $bq) {
                                $product = $bq;
                                include("elements/product-card.php");
                            }
                        }
                    ?>
                    </div>
                </div>

                <div class="col-12" id="sayily-cont">
                    <div class="row bq-container">
                        <?php 
                            $bouquetILY = $connection->query("
                                SELECT *
                                FROM Ready_Made_Bouquets
                                WHERE category = 'Say ILY'
                            ")->fetchAll();

                            if ( isset($bouquetILY) ) {
                                foreach ($bouquetILY as $bq) {
                                    $product = $bq;
                                    include("elements/product-card.php");
                                }
                            }
                        ?>
                    </div>
                </div>

                <div class="col-12" id="congrats-cont">
                    <div class="row bq-container">
                        <?php 
                            $bouquetC = $connection->query("
                                SELECT *
                                FROM Ready_Made_Bouquets
                                WHERE category = 'Congratulations'
                            ")->fetchAll();

                            if ( isset($bouquetC) ) {
                                foreach ($bouquetC as $bq) {
                                    $product = $bq;
                                    include("elements/product-card.php");
                                }
                            }
                        ?>
                    </div>
                </div>

                <div class="col-12" id="justbcz-cont">
                    <div class="row bq-container">
                        <?php 
                            $bouquetJ = $connection->query("
                                SELECT *
                                FROM Ready_Made_Bouquets
                                WHERE category = 'Just Because'
                            ")->fetchAll();

                            if ( isset($bouquetJ) ) {
                                foreach ($bouquetJ as $bq) {
                                    $product = $bq;
                                    include("elements/product-card.php");
                                }
                            }
                        ?>
                    </div>
                </div>


            </div>

        </div>
    </div>


    <?php include("elements/footer.php")?>
</body>
</html>
