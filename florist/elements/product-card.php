<?php
    $ddData = $connection->query(" 
        SELECT *
        FROM R_M_Bouquets_Flowers bf 
        LEFT JOIN flowers f 
        ON bf.flowerID = f.flowerID 
    ")->fetchAll();
?>


<div class="col-xl-4 col-sm-6 col-12 product-col">
    <div class="card product-card">

        <img src="<?= $product['image_path'] ?>" class="card-img-top" alt="<?= $product['name'] ?>">

        <div class="card-body">
            <h5 class="card-title"><?= $product['name'] ?></h5>
            <p class="card-text prod-size">
                <small>Size: <span class="cart-size"><?= $product['size'] ?></span></small>
            </p>
            <p class="card-text"><span>$ </span><?= $product['price'] ?></p>

            <div class="bq-dropdown-row">
                <button class="bq-dropdown-btn" onclick="toggleDropdown(this)">
                    <span class="rotating-arrow">▼</span> Flowers used
                </button>

                <div class="bq-dropdown-content">
                    <?php 
                    foreach ($ddData as $dd) { ?>
                        <?php if ($product['readyMadeID'] == $dd['readyMadeID']) { ?>
                            <?= $dd['flowerName'] ?>
                        <?php }
                    } 
                    ?>
                </div>
            </div>

            <!-- Form to add item to cart -->
            <form action="php-action-files/add-to-cart.php" method="POST">
                <input type="hidden" name="readyMadeID" value="<?= $product['readyMadeID'] ?>">
                <input type="hidden" name="name" value="<?= $product['name'] ?>">
                <input type="hidden" name="price" value="<?= $product['price'] ?>">
                <input type="hidden" name="size" value="<?= $product['size'] ?>">
                <input type="hidden" name="image_path" value="<?= $product['image_path'] ?>">
                        
                <button type="submit" class="ss-btn add-btn">Add to Cart</button>
            </form>


        </div>
    </div>
</div>

