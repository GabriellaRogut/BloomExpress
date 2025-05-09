<?php 
    $userID = $_SESSION['user']['userID'];

    $items = $connection->query(
        "SELECT *
        FROM Orders o
        JOIN Order_Items oi ON o.orderID = oi.orderID
        JOIN Ready_Made_Bouquets rmb ON oi.readyMadeID = rmb.readyMadeID
        WHERE o.userID = $userID AND o.orderID = $ord[orderID];"
    )->fetchAll();
    

    foreach( $items as $item ) { 
        
        $ddData = $connection->query(" 
            SELECT f.flowerName 
            FROM R_M_Bouquets_Flowers bf 
            LEFT JOIN flowers f ON bf.flowerID = f.flowerID WHERE bf.readyMadeID = ". $item['readyMadeID']
        )->fetchAll();
?>


        <div class="row order-item-row">

            <div class="col-3 img-cart-col">
                <img src="<?= $item['image_path'] ?>" class="img-fluid" alt="product">
                <h5 class="card-title order-prod-title"> <?= $item['name'] ?> </h5>
                <p class="card-text"><small>Size: <span class="cart-size"> <?= $item['size'] ?> </span></small></p>
            </div>

            <div class="col-3 cart-col">
                <p><?= $item['price'] ?></p>
            </div>

            <div class="col-3 cart-col">
                <div class="quantity-selector">
                    <span class="quantity qty-ordered"> <?= $item['quantity'] ?> </span>
                </div>
            </div>

            <div class="col-3 cart-col">
                <p><?= number_format($item['price'] * $item['quantity'], 2, ".") ?></p>
            </div>

            <div class="order-dropdown-row">
                <button class="bq-dropdown-btn order-ddwn-btn" onclick="toggleDropdown(this)">
                    <span class="rotating-arrow">▼</span> Flowers used
                </button>
                                
                <div class="bq-dropdown-content order-ddwn-content">

                    <?php
                        $i = 0; 
                        foreach($ddData as $data) {
                            if ($i > 0) {  
                                echo ",";  
                            }  
                            echo $data['flowerName'];  
                            $i++;
                        }
                    ?> 
                </div>
            </div>

            <hr class="order-hr">
        </div>
<?php 
    } 
?>

