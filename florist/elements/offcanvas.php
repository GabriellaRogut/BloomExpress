<!-- Offcanvas for orders -->
<div class="offcanvas offcanvas-start" id="offcanvasOrders" aria-labelledby="offcanvasOrdersLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasOrdersLabel">My Orders</h5>
            
        <button class="remove-button close-ofc-btn" data-bs-dismiss="offcanvas">&#10005;</button>
    </div>


    <div class="offcanvas-body">

        <div class="row orders-section-row">
            <div class="col-6">
                <button id="ongoing-btn" class="orders-btn active-tab" onclick="showOrders('ongoing')">Ongoing Orders</button>
            </div>
            <div class="col-6">
                <button id="past-btn" class="orders-btn" onclick="showOrders('past')">Past Orders</button>
            </div>
        </div>


        <?php if (isset($_SESSION['userIn']) && $_SESSION['userIn'] == true) { ?>

            <?php
                if ( isset( $_SESSION['userIn'] )) {
                    $userID = $_SESSION['user']['userID'];

                    $order = $connection->prepare("
                        SELECT *
                        FROM Orders o
                        WHERE o.userID = ? AND order_status = ?
                        ORDER BY o.placed_on DESC;
                        ");
                    $order->execute([$userID, 'Ongoing']);
                    $order = $order->fetchAll();
                }
            ?>


                <!-- Ongoing Orders -->
                <div id="ongoing-orders-container">
                    <?php 
                        if ( $order ) {
                            foreach( $order as $ord ) {
                                $ord['orderID']; include("elements/ongoing-order.php");
                            }
                        }else{
                    ?>

                        <div id="ongoing-orders-container">
                            <div class="card no-acc-div no-acc-orders">
                                <div class="card-body no-acc-card no-acc-ord-card">
                                    <p class="card-text no-acc-txt no-acc-ord-txt">
                                        Ongoing Orders will appear Here.
                                    </p>
                                </div>
                            </div>
                        </div>

                    <?php
                        }
                    ?>    
                </div>
                    
                <!-- Past Orders -->
                <?php
                    if ( isset( $_SESSION['userIn'] )) {
                        $userID = $_SESSION['user']['userID'];
                        $orderP = $connection->prepare("
                            SELECT *
                            FROM Orders o
                            WHERE o.userID = ? AND order_status = ?
                            ORDER BY o.placed_on DESC;
                        ");
                        $orderP->execute([$userID, 'Past']);
                        $orderP = $orderP->fetchAll();
                    }
                ?>

                <div id="past-orders-container">
                    <?php 
                        if ( $orderP ) {
                            foreach( $orderP as $ordP ) {
                                $ordP['orderID']; include("elements/past-order.php");
                            }
                        } else {
                    ?>

                    
                            <div id="past-orders-container" style="display:block;">
                                <div class="card no-acc-div no-acc-orders">
                                    <div class="card-body no-acc-card no-acc-ord-card">
                                        <p class="card-text no-acc-txt no-acc-ord-txt">
                                            Past Orders will appear Here.
                                        </p>
                                    </div>
                                </div>
                            </div>

                        <?php
                            }
                        ?>     
                </div>


        <?php 
            } else { 
        ?>  

            <div id="ongoing-orders-container">
                <div class="card no-acc-div no-acc-orders">
                    <div class="card-body no-acc-card no-acc-ord-card">
                        <h5 class="card-title no-acc-title no-acc-ord-title">No user logged in..</h5>
                        <p class="card-text no-acc-txt no-acc-ord-txt">
                            Log In to see your Orders.
                        </p>
                    </div>
                </div>
            </div>

            <div id="past-orders-container">
                <div class="card no-acc-div no-acc-orders">
                    <div class="card-body no-acc-card no-acc-ord-card">
                        <h5 class="card-title no-acc-title no-acc-ord-title">No user logged in..</h5>
                        <p class="card-text no-acc-txt no-acc-ord-txt">
                            Log In to see your Orders.
                        </p>
                    </div>
                </div>
            </div>

        <?php 
            } 
        ?>


    </div>

</div>

