<?php

// clearing of coffers

require_once ("../db_connection/conn.php");

if (isset($_GET['clear']) && !empty($_GET['clear'])) {
    if ((int)$_GET['clear']) {
        $sql = "
            UPDATE jspece_coffers 
            SET jspece_coffers.status = ?
        ";
        $statement = $conn->prepare($sql);
        $result = $statement->execute([1]);

        if (isset($result)) {
            redirect(PROOT . 'account/pushes');
        }
    }
}
