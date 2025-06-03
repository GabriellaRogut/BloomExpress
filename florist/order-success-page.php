<?php 
    include("DB/connection.php");
    include("config/config.php");
    include("php-action-files/guestCart.php");
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BloomExpress - Order Success</title>
    <?php include("elements/links.php") ?>
    <link rel="stylesheet" href="styles/style.css?v=<?= time() ?>">
</head>


<body class="orders-success-background">
    <div class="card ord-success-card">
        <div class="card-header">Your Order was Successfull</div>
        <div class="card-body text-secondary">
            
            
            <?php 
            if (isset($_SESSION['userIn'])){
            ?>
                <p class="card-title">Thank You for your Order! </p>
                <p class="card-text">You can now find it in your Ongoing Orders.</p>
            <?php
                } else {
            ?>
                <p class="card-title">Thank You  </p>
                <p class="card-text"> for your Order!</p>
            <?php
                }
            ?>
        </div>

        <form method="POST" action="php-action-files/unset-order-success.php">
            <button type="submit" name="unset-complete" value="unset" class="ss-btn success-out-btn">Back to Homepage</button>
        </form>
    </div>

</body>

</html>