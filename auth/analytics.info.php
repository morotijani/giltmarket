<?php 

	// DELETE admin profile picture

	require_once ("../db_connection/conn.php");

	if (isset($_POST['dater'])) {
        $date = sanitize($_POST['dater']);

        if ($date == null) {
            echo null;
        } else {
            echo $date;
        }
    }