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
            $push_id = guidv4();
            $createdAt = date("Y-m-d H:i:s");

            if ($today_date == $today) {

                // subtract add amount from cash in from supervisor gained cash
                $coffers_receive_through = null;
                if ($add_from == 'trades') {
                    
                    $coffers_receive_through = 'trades';
                    $a = total_amount_today($admin_id);
                    if ($add_amount > $a) {
                        $_SESSION['flash_error'] = 'Invalid fund amount';
                    }
                } else {
                    $coffers_receive_through = 'cash';
                }

                $coffersSQL = "
                    INSERT INTO jspence_coffers (coffers_id, coffers_amount, coffers_status, coffers_receive_through, createdAt) 
                    VALUES (?, ?, ?, ?, ?)
                ";
                $statement = $conn->prepare($coffersSQL);
                $result = $statement->execute([$coffers_id, $add_amount, 'receive', $coffers_receive_through, $createdAt]);
                if ($result) {
                    
                    $push_from_where = (($add_from == 'trades') ? 'dialy' : 'physical-cash');

                    $LID = $conn->lastInsertId();
                    $q = $conn->query("SELECT * FROM jspence_coffers WHERE id = '" . $LID . "' LIMIT 1")->fetchAll();
                    $coffers_id = $q[0]['coffers_id'];

                    $push_data = [$push_id, $coffers_id, $add_amount, 'money', $admin_id, 'coffers', $today, $push_from_where];
                    $sql = "
                        INSERT INTO jspence_pushes (push_id, push_daily, push_amount, push_type, push_from, push_to, push_date, push_from_where) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                    ";
                    $statement = $conn->prepare($sql);
                    $statement->execute($push_data);
                    

                    // add to log message
                    $message = money($add_amount) . " from " . strtoupper($add_from) . " has been add to coffers";
                    add_to_log($message, $admin_id);

                    $_SESSION['flash_success'] = 'Coffers funded with an amount of ' . money($add_amount);
                } else {	
                    echo js_alert('Something went wrong, please refresh and try agin!');
                }
                redirect(goBack());
            }
		}
	}
