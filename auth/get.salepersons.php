<?php 

require_once ("../db_connection/conn.php");

$output = '';
if (isset($_POST['select_for'])) {
    if ($_POST['select_for'] == 'saleperson') {
        $output = get_salepersons_for_push_capital($conn);
    }
}

echo $output;
