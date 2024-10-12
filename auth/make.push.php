<?php 
    // make a daily push

    require_once ("../db_connection/conn.php");

	if (isset($_POST['today_given'])) {
		if (!empty($_POST['today_given']) || $_POST['today_given'] != '') {
			if (!empty($_POST['push_to']) || $_POST['push_to'] != '') {

				$given = sanitize($_POST['today_given']);
				$today_date = sanitize($_POST['today_date']);				
				$push_to = ((isset($_POST['push_to']) && !empty($_POST['push_to'])) ? sanitize($_POST['push_to']) : '');

				$today = date("Y-m-d");
				$daily_id = guidv4();
				$push_id = guidv4();
				$push_from = $admin_id;

				if ($today_date == $today) {
					$findCapital = find_capital_given_to($push_to, $today);

					// get today capital from whom we are pushing to
					$c = _capital($push_to)['today_capital'];

					// check if capital has been given
					if ($findCapital) {
						$c = (float)($given + $c);

						$bal = _capital($push_to)['today_balance'];
						// check if we are sending to salepersonnel from supervisor
						if (!admin_has_permission()) {
							$bal = ((_capital($push_to)['today_balance'] == null) ? null : (float)($given + _capital($push_to)['today_balance']));
						}

						// update daily capital and balance
						$dailyQ = "
							UPDATE `jspence_daily` 
							SET `daily_capital` = ?, `daily_balance` = ? 
							WHERE `daily_date` = ? AND `daily_to` = ?
						";
						$daily_data = [$c, $bal, $today, $push_to];
						$message = "on this day " . $today . ", capital updated of an amount " . money($c) . ', added amount ' . money($given) .  'for a ' .((admin_has_permission()) ? ' supervisor' : 'saleperson') . ' id: ' . $push_to;
					} else {
						$daily_data = [$daily_id, $given, $today, $push_to];
						
						// insert into daily
						$dailyQ = "
							INSERT INTO jspence_daily (daily_id, daily_capital, daily_date, daily_to) 
							VALUES (?, ?, ?, ?)
						";
						$message = "on this day " . $today . ", capital entered of an amount of " . money($c) . ' to a ' . ((admin_has_permission()) ? ' supervisor' : 'saleperson') . ' id: ' . $push_to;
					}

					$statement = $conn->prepare($dailyQ);
					$daily_result = $statement->execute($daily_data);

					// find the just enetered capital id
					if (!$findCapital) {
						$LID = $conn->lastInsertId();
						$q = $conn->query("SELECT * FROM jspence_daily WHERE id = '" . $LID . "' LIMIT 1")->fetchAll();
						$findCapital = $q[0]['daily_id'];
					}

					if (isset($daily_result)) {
						// insert into push table
						$push_data = [$push_id, $findCapital, $given, $push_from, $push_to, $today];
						$sql = "
							INSERT INTO jspence_pushes (push_id, push_daily, push_amount, push_from, push_to, push_date) 
							VALUES (?, ?, ?, ?, ?, ?)
						";
						$statement = $conn->prepare($sql);
						$push_result = $statement->execute($push_data);

						if (isset($push_result)) {
							$push_message = "push made on " . $today . ", of an amount of " . money($given) . ' to a ' . ((admin_has_permission()) ? ' supervisor' : 'saleperson') . ' id: ' . $push_to;
							add_to_log($push_message, $admin_id);
						}
						add_to_log($message, $admin_id);
		
						$_SESSION['flash_success'] = money($given) . ((admin_has_permission('saleperson')) ? ' Gold push to supervisor' : 'Money pushed to saleperson'). ' successfully!';
					} else {	
						echo js_alert('Something went wrong, please refresh and try agin!');
					}
					redirect(goBack());
				}
			}
		}
	}
    