<?php
    include("../DB/connection.php");
    include("../config/config.php");
    
    $buttonClicked = $_POST['checkout'];
    $_SESSION['errorCheckbox'] = array();


    if(isset($buttonClicked)){
        if (!isset($_POST['terms_and_conditions']) || $_POST['terms_and_conditions'] != 'on'){
            $_SESSION['errorCheckbox'][] = "Please, check our Terms And Conditions!";
            header("Location: ../cart.php");
            exit;
        } else {
            header("Location: ../checkout.php");
            exit;
        }
    }


?>