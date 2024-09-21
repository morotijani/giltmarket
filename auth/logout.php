<?php 

    require_once ("../db_connection/conn.php");

    $message = "logged out from system";
    add_to_log($message, $_SESSION['JSAdmin']);

    unset($_SESSION['JSAdmin']);

    redirect(PROOT . 'auth/login');
