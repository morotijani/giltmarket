<?php 

    require_once ("../db_connection/conn.php");

    unset($_SESSION['JSAdmin']);

    redirect(PROOT . 'index');
