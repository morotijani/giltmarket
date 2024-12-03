<?php

// clearing of coffers

require_once ("../db_connection/conn.php");

if (isset($_GET['clear']) && !empty($_GET['clear'])) {
    if ((int)$_GET['clear']) {
        $sql = "
            UPDATE jspence_coffers 
            SET jspence_coffers.status = ?
        ";
        $statement = $conn->prepare($sql);
        $result = $statement->execute([1]);

        if (isset($result)) {
            $_SESSION['flash_success'] = "Coffers successfully cleard!";
            redirect(PROOT . 'account/pushes');
        } else {
            $_SESSION['flash_error'] = "Something went wrong, please try again!";
            redirect(PROOT . 'account/pushes');
        }
    }
}
