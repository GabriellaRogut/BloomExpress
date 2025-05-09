
<div id="past-orders" class="orders-content-2">
    <div class="order">

        <button class="dropdown-btn" type="button" data-bs-toggle="collapse" data-bs-target="#OrderP<?= $ordP['orderID'] ?>" aria-expanded="false">
            <p class="order-btn-txt"><?= $ordP['delivery_date'] ?></p>
            <p>No<span class="order-btn-txt no-txt"><?= $ordP['orderNum'] ?></span></p> 
        </button>


        <div class="collapse" id="OrderP<?= $ordP['orderID'] ?>">
            <div>

                <div class="order-dates">
                    <p><small>Placed on <?= date('l', strtotime( $ordP['placed_on'] )) ?>, <?= date("d F, Y", strtotime( $ordP['placed_on'] ) ) ?></small></p>
                    <p><small>Delivered on <?= date('l', strtotime( $ordP['delivery_date'] )) ?>, <?= date("d F, Y", strtotime( $ordP['delivery_date'] ) ) ?></small></p>
                </div>

            </div>

            <?php include("elements/past-order-card.php") ?>
        </div>

    </div>
</div>


