<?php include("DB/connection.php") ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BloomExpress - Account</title>
    <link rel="icon" type="image/x-icon" href="images/logo-ico.ico">

    <?php include("elements/links.php")?>

    <link rel="stylesheet" href="styles/style.css?v=<?= time() ?>">
</head>


<body>
    <?php
        if (isset($_SESSION['signUpSuccess']) && $_SESSION['signUpSuccess'] == true){
            unset( $_SESSION['signUpSuccess'] ); // only appear after a new acc is created
    ?>

            <div class="popup-overlay" id="AccOverlay" onclick="closeAccPopup()"></div>

            <div class="popup" id="AccPopup">
                <span class="close-btn" onclick="closeAccPopup()">✖</span>
                <div class="finish-container">
                    <h5 class="finish-txt">Finish your profile for Easier Order!</h5>
                </div>
            </div>

            <script>
                window.addEventListener("load", (event) => { // after everything is finished loading /footer js/
                    openAccPopup();
                });
            </script>

    <?php 
        } 
    ?>

    
    <?php 
        include("elements/offcanvas.php") ;
        include("login.php");
        include("signup.php");
    ?>

    <div class="row">
        <div class="col-2 col-xl-1 sidebar-col">
            <div class="sidebar">

                <div class="logo-row">
                    <a href="index.php"><img class="sb-logo" src="images/logo.png" alt="logo"></a>
                </div>
                    
                <a href="index.php">Home</a>
                <a href="shop.php" >Shop</a>
                <a href="cart.php">Cart</a>
                <a href="#" data-bs-toggle="offcanvas" data-bs-target="#offcanvasOrders">Orders</a>
                <a href="account.php" class="active">Account</a>
            </div>
        </div>


        <div class="col-10 col-xl-11">

            <div class="profile-content">
                <?php if (isset($_SESSION['userIn']) && $_SESSION['userIn'] == true){ ?>
                    
                    <?php
                        $userID = $_SESSION['user']['userID']; // get user's ID
                        $user = $connection->prepare("
                            SELECT * 
                            FROM User 
                            WHERE userID = ?"
                        );
                        $user->execute([$userID]);
                        $user = $user->fetch();


                        $userPromoArr = $connection->prepare("
                            SELECT promo_code
                            FROM User 
                            WHERE userID = ?"
                        );
                        $userPromoArr->execute([$userID]);
                        $userPromoArr = $userPromoArr->fetchAll();
                    ?>
                    


                    <?php 
                        if( $user['promo_code']) {
                    ?>
                            <div class="card promo-card">
                                <div class="card-header">
                                    <p>PROMO CODE</p>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">Thank you for Signing Up!</h5>
                                    <p class="card-text">Your <span class="promo-txt">10% OFF</span> Promo Code is</p>
                                    <h5 class="promo-code" onclick="copyToClipboard()" id="textToCopy"><?= $user['promo_code'] ?></h5>
                                </div>
                                <div class="card-footer text-body-secondary">
                                    <p class="promo-exp-date">No Expiration Date</p>
                                </div>
                            </div>
                    <?php
                        }
                    ?>



                    <div class="profile-title">Personal Information</div>
                
                    <table class="profile-table">
                        <tr>
                            <th> </th>
                            <th class="th-title">Details</th>
                            <th> </th>
                        </tr>
                        <tr>
                            <td id="first_name">First Name</td>
                            <td><?= $user['first_name'] ?></td> <!-- get DB column from user -->
                            <td class="edit-td">
                                <button class="edit-btn edit-link">Edit</button>
                                <button class="save-btn edit-link">Save</button>
                                <button class="cancel-btn edit-link">Cancel</button>
                            </td>
                        </tr>
                        <tr>
                            <td id="last_name">Last Name</td>
                            <td><?= $user['last_name'] ?></td>
                            <td class="edit-td">
                                <button class="edit-btn edit-link">Edit</button>
                                <button class="save-btn edit-link">Save</button>
                                <button class="cancel-btn edit-link">Cancel</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="required-input" id="email"><p class="starred-content"></p>Email</td>
                            <td><?= $user['email'] ?></td>
                            <td class="edit-td">
                                <button class="edit-btn edit-link">Edit</button>
                                <button class="save-btn edit-link">Save</button>
                                <button class="cancel-btn edit-link">Cancel</button>
                            </td>
                        </tr>
                        <tr>
                            <td id="phone">Phone</td>
                            <td><?= $user['phone'] ?></td>
                            <td class="edit-td">
                                <button class="edit-btn edit-link">Edit</button>
                                <button class="save-btn edit-link">Save</button>
                                <button class="cancel-btn edit-link">Cancel</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="required-input" id="password"><p class="starred-content"></p>Password</td>
                            <td>***</td>
                            <td class="edit-td">
                                <button class="edit-btn edit-link">Edit</button>
                                <button class="save-btn edit-link">Save</button>
                                <button class="cancel-btn edit-link">Cancel</button>
                            </td>
                        </tr>
                        <tr>
                            <td id="address">Address</td>
                            <td><?= $user['address'] ?></td>
                            <td class="edit-td">
                                <button class="edit-btn edit-link">Edit</button>
                                <button class="save-btn edit-link">Save</button>
                                <button class="cancel-btn edit-link">Cancel</button>
                            </td>
                        </tr>
                        <tr>
                            <td id="city">City</td>
                            <td><?= $user['city'] ?></td>
                            <td class="edit-td">
                                <button class="edit-btn edit-link">Edit</button>
                                <button class="save-btn edit-link">Save</button>
                                <button class="cancel-btn edit-link">Cancel</button>
                            </td>
                        </tr>
                        <tr>
                            <td id="ZIPCode">ZIP Code</td>
                            <td><?= $user['ZIPCode'] ?></td>
                            <td class="edit-td">
                                <button class="edit-btn edit-link">Edit</button>
                                <button class="save-btn edit-link">Save</button>
                                <button class="cancel-btn edit-link">Cancel</button>
                            </td>
                        </tr>
                        <tr>
                            <td id="birthday">Birthday</td>
                            <td><?= $user['birthday'] ?></td>
                            <td class="edit-td">
                                <button class="edit-btn edit-link">Edit</button>
                                <button class="save-btn edit-link">Save</button>
                                <button class="cancel-btn edit-link">Cancel</button>
                            </td>
                        </tr>

                        <?php
                            if ( isset( $errorPrUpdate ) ) {
                                foreach( $errorPrUpdate as $err ) {
                                    echo "<div class='error'>". $err . "</div>";
                                }
                            }
                        ?>
                    </table>
            </div>

            <?php 
                } else { 
            ?>
            
                    <div class="card no-acc-div">
                        <div class="card-body no-acc-card">
                            <h5 class="card-title no-acc-title">No user logged in..</h5>
                            <p class="card-text no-acc-txt">
                                Don't have an account yet? Make ordering Faster and Easier and get 
                                <span class="promo-txt">10% OFF</span> by Signing Up NOW!
                            </p>
                            
                            <button class="ss-btn" onclick="openSPopup()">Log In</button>
                            <button onclick="openPopup()" class="ss-btn">Sign Up</button>
                        </div>
                    </div>

            <?php 
                }
            ?>
        </div>

    </div>

    <?php include("elements/footer.php")?> 
    
</body>
</html>
