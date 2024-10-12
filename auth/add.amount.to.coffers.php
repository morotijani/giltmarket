<?php 
    // add an amount to coffers

    require_once ("../db_connection/conn.php");

	if (isset($_POST['add_amount'])) {
		if (!empty($_POST['add_from']) || $_POST['add_from'] != '') {

            $add_from = $_POST['add_from'];
            $add_amount = sanitize($_POST['add_amount']);
            $today_date = sanitize($_POST['today_date']);

            $today = date("Y-m-d");
            $coffers_id = guidv4();
            $createdAt = date("Y-m-d H:i:s");

            if ($today_date == $today) {

                if ($add_from == 'trades') {

                }

                $coffersSQL = "
                    INSERT INTO jspence_coffers (coffers_id, coffers_amount, coffers_for, coffers_status, createdAt) 
                    VALUES (?, ?, ?, ?, ?)
                ";
                $statement = $conn->prepare($coffersSQL);
                $result = $statement->execute([$coffers_id, $add_amount, $admin_id, 'receive', $createdAt]);
                if ($result) {
                    $_SESSION['flash_success'] = money($given) . ((admin_has_permission('saleperson')) ? ' Gold push to supervisor' : 'Money pushed to saleperson'). ' successfully!';
                } else {	
                    echo js_alert('Something went wrong, please refresh and try agin!');
                }
                redirect(goBack());
            }
		}
	}
