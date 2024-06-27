<?php 

    require_once ("../db_connection/conn.php");

    unset($_SESSION['ATAdmin']);

    header('Location: login');

?>