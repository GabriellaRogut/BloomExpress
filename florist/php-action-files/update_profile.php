<?php
    include("../DB/connection.php");

    $userID = $_SESSION['user']['userID'];

    $field = $_POST['field'];
    $value = isset( $_POST['value'] ) ? $_POST['value'] : "";

    $errorPrUpdate = array();


    if ($field == "email") {
        $emailRegex = '/^[_A-Za-z0-9-\\+]+(\\.[_A-Za-z0-9-]+)*@[A-Za-z0-9-]+(\\.[A-Za-z0-9]+)*(\\.[A-Za-z]{2,})$/';

        if (!preg_match($emailRegex, $value)){
            $errorPrUpdate[] = "Please enter a Valid Email Address.";
        } else if ($value == ''){
            $errorPrUpdate[] = "You can Not delete your Email Address.";
        }
    


    } else if ($field == "password") {
        $oldPass = $_POST['oldPass'];
        $newPass = $_POST['newPass'];
        $confirmPass = $_POST['confirmPass'];

        $userPassDB = $connection->prepare("
            SELECT password
            FROM User
            WHERE userID = ?
        ");
        $userPassDB->execute([$userID]);
        $userPassArr = $userPassDB->fetch();
        $userPass = $userPassArr['password'];



        $newPassHash = password_hash($newPass, PASSWORD_BCRYPT);

        if (password_verify($oldPass, $userPass)) {
            $value = $newPassHash;
        } else {
            $errorPrUpdate[] = "Existing Password Does Not Match your Input.";
        }  

        if ($newPass == ''){
            $errorPrUpdate[] = "You can Not delete your Password.";
        } else if ($newPass != $confirmPass && $newPass != ''){
            $errorPrUpdate[] = "Your Confirmation Password Does Not Match.";
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
        
    } else {
        echo json_encode([
            'success' => false,
            'message' => implode('<br>', $errorPrUpdate)
        ]);
    }

?>