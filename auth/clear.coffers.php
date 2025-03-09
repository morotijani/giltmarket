<?php

// clearing of coffers

require_once ("../db_connection/conn.php");

if (isset($_GET['clear']) && !empty($_GET['clear'])) {
    if ((int)$_GET['clear']) {
        $sql = "
            UPDATE giltmarket_coffers 
            SET giltmarket_coffers.status = ?
        ";
        $statement = $conn->prepare($sql);
        $result = $statement->execute([2]);

        if (isset($result)) {

            // add to log message
            $message = "coffers cleared.";
            add_to_log($message, $admin_id);

            $_SESSION['flash_success'] = "Coffers successfully cleard!";
            redirect(PROOT . 'account/pushes');
        } else {
            $_SESSION['flash_error'] = "Something went wrong, please try again!";
            redirect(PROOT . 'account/pushes');
        }
    }
}
