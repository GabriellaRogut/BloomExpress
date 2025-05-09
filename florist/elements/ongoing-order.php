<div id="ongoing-orders" class="orders-content-1">
    
    <div class="order">

        <button class="dropdown-btn" type="button" data-bs-toggle="collapse" data-bs-target="#Order<?= $ord['orderID'] ?>" aria-expanded="false">
            <p class="order-btn-txt"><?= $ord['placed_on'] ?></p>
            <p>No<span class="order-btn-txt no-txt"><?= $ord['orderNum'] ?></span></p> 
        </button>


        <div class="collapse" id="Order<?= $ord['orderID'] ?>">
            <div>
                
                <div class="order-dates">
                    <p><small>Placed on <?= date('l', strtotime( $ord['placed_on'] )) ?>, <?= date("d F, Y", strtotime( $ord['placed_on'] ) ) ?></small></p>
                    <p><small>Expected on <?= date('l', strtotime( $ord['delivery_date'] )) ?>, <?= date("d F, Y", strtotime( $ord['delivery_date'] ) ) ?></small></p>
                </div>

            </div>

            <?php include("elements/ongoing-order-card.php") ?>

        </div>

    </div>

</div>

