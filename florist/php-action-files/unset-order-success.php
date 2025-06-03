<?php
    include("../DB/connection.php");

    if (isset($_POST['unset-complete'])) {
        unset($_SESSION['order_complete']);
    }

    header("Location: ../index.php");
    exit;
?>