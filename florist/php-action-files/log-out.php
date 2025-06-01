<?php
    include("../DB/connection.php");
    include("../config/config.php");

    if (isset($_POST['log-out'])){
        $_SESSION = [];

        session_unset();
        session_destroy();

        // Clear session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
    }


    echo "<script>document.location.href='../index.php';</script>";
?>