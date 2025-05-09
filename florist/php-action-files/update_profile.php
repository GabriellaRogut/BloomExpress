<?php
    include("../DB/connection.php");

    $userID = $_SESSION['user']['userID'];

    $field = $_POST['field'];
    $value = $_POST['value'];


    $errorPrUpdate = array();

    if ($field == "password") {
        $oldPass = $_POST['oldPass'];
        $newPass = $_POST['newPass'];
        $confirmPass = $_POST['confirmPass'];

        $userPassDB = $connection->prepare("
            SELECT password
            FROM User
            WHERE userID = ?
        ");
        $userPassDB->execute([$userID]);
        $userPassDB->fetch();


        if (password_verify($oldPass, $userPassDB)) {
            $newPassHash = password_hash($newPass, PASSWORD_BCRYPT);
            $value = $newPassHash;
        } else {
            $errorPrUpdate[] = "Existing Password Does Not Match your Input.";
        }  
    }




    if ( !$errorPrUpdate ){
        $userUpdate = $connection->prepare("
            UPDATE User 
            SET $field = ? 
            WHERE userID = ?
        "); 
        $success = $userUpdate->execute([ $value, $userID ]); 

        
        $result = array('success' => $success );

        echo json_encode($result);
    }
?>