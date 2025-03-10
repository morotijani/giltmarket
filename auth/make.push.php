<?php 
    // make a push

    require_once ("../db_connection/conn.php");

	if (isset($_POST['today_given'])) {
		if (!empty($_POST['pin']) || $_POST['pin'] != '') {
			if (!empty($_POST['push_to']) || $_POST['push_to'] != '') {

				if (admin_has_permission('salesperson')) {
					$push_gram = ((isset($_POST['push_gram'])) ? sanitize($_POST["push_gram"]) : null);
					$push_volume = ((isset($_POST['push_volume'])) ? sanitize($_POST["push_volume"]) : null);

					if ($push_gram != null || !empty($push_gram)) {
						if ($push_volume != null || !empty($push_volume)) {

							$push_density = calculateDensity($push_gram, $push_volume);
							$push_pounds = calculatePounds($push_gram);
							$push_carat = calculateCarat($push_gram, $push_volume);
							
							$pushData = array('gram' => $push_gram, 'volume' => $push_volume, 'density' => $push_density, 'pounds' => $push_pounds, 'carat' => $push_carat);
							$pushData = json_encode($pushData);
						} else {
							echo js_alert('Something went wrong, please refresh and try agin!');
							redirect(goBack());
						}
					} else {
						echo js_alert('Something went wrong, please refresh and try agin!');
						redirect(goBack());
					}
				}

				$given = sanitize($_POST['today_given']);
				$today_date = sanitize($_POST['today_date']);				
				$push_to = ((isset($_POST['push_to']) && !empty($_POST['push_to'])) ? sanitize($_POST['push_to']) : '');
				$pin = ((isset($_POST['pin']) && !empty($_POST['pin'])) ? sanitize($_POST['pin']) : '');
				$push_note = ((isset($_POST['push_note']) && !empty($_POST['push_note'])) ? sanitize($_POST['push_note']) : '');

				if ($pin == $admin_data['admin_pin']) {

					$today = date("Y-m-d");
					$daily_id = guidv4();
					$push_id = guidv4();
					$push_from = $admin_id;

					if ($today_date == $today) {
						$findCapital = find_capital_given_to($push_to);

						// get today capital from whom we are pushing to
						$c = _capital($push_to)['today_capital'];

						// check if capital has been given
						if (is_array($findCapital)) {
							$c = (float)($given + $c);

							// update daily capital and balance
							$dailyQ = "
								UPDATE `giltmarket_daily` 
								SET `daily_capital` = ?, `daily_balance` = daily_balance + '" . $given . "' 
								WHERE `daily_id` = ? AND `daily_to` = ?
							";
							$daily_data = [$c, $findCapital['daily_id'], $push_to];
							$message = "on this day " . $today . ", capital updated of an amount " . money($c) . ', added amount ' . money($given) .  'for a ' .((admin_has_permission()) ? ' supervisor' : 'saleperson') . ' id: ' . $push_to;
						} else {
							$daily_data = [$daily_id, $given, $given, $push_to];
							
							// insert into daily
							$dailyQ = "
								INSERT INTO giltmarket_daily (daily_id, daily_capital, daily_balance, daily_to) 
								VALUES (?, ?, ?, ?)
							";
							$message = "on this day " . $today . ", capital entered of an amount of " . money($c) . ' to a ' . ((admin_has_permission()) ? ' supervisor' : 'saleperson') . ' id: ' . $push_to;
						}

						$statement = $conn->prepare($dailyQ);
						$daily_result = $statement->execute($daily_data);

						if (isset($daily_result)) {

							// find the just enetered capital id
							if (!is_array($findCapital)) {
								$LID = $conn->lastInsertId();
								$q = $conn->query("SELECT * FROM giltmarket_daily WHERE id = '" . $LID . "' LIMIT 1")->fetchAll();
								$findCapital = $q[0]['daily_id'];
							} else {
								$findCapital = $findCapital['daily_id'];
							}

							// update coffers
							if (admin_has_permission('supervisor')) {
								$coffers_id = guidv4();
							
								$coffersSQL = "
									INSERT INTO giltmarket_coffers (coffers_id, coffers_amount, coffers_status) 
									VALUES (?, ?, ?)
								";
								$statement = $conn->prepare($coffersSQL);
								$statement->execute([$coffers_id, $given, 'send']);

								$LID = $conn->lastInsertId();
								$q = $conn->query("SELECT * FROM giltmarket_coffers WHERE id = '" . $LID . "' LIMIT 1")->fetchAll();
								$findCapital = $q[0]['coffers_id'];

								$pushData = null;
							}

							// insert into push table
							$push_data = [
								$push_id, 
								$findCapital, 
								$given, 
								((admin_has_permission('supervisor')) ? 'money' : 'gold'), 
								$push_from, 
								$push_to, 
								((admin_has_permission('supervisor')) ? 'coffers' : 'daily'), 
								$pushData, 
								$push_note
							];
							$sql = "
								INSERT INTO giltmarket_pushes (push_id, push_daily, push_amount, push_type, push_from, push_to, push_from_where, push_data, push_note) 
								VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
							";
							$statement = $conn->prepare($sql);
							$push_result = $statement->execute($push_data);

							if (isset($push_result)) {

								$push_message = "push made on " . $today . ", of an amount of " . money($given) . ' to a ' . ((admin_has_permission()) ? ' supervisor' : 'saleperson') . ' id: ' . $push_to;
								add_to_log($push_message, $admin_id);
							}
							add_to_log($message, $admin_id);

							$_SESSION['flash_success'] = money($given) . ((admin_has_permission('salesperson')) ? ' Gold push to supervisor' : ' Money pushed to saleperson'). ' successfully!';
						} else {
							echo js_alert('Something went wrong, please refresh and try agin!');
						}
						redirect(PROOT);
					} else {
						// device date wrong of choosed date not matching
						$_SESSION['flash_error'] = 'Check your device date!';
						redirect(PROOT);
					}
				} else {
					$_SESSION['flash_error'] = 'Invalid admin PIN provided!';
					redirect(PROOT);
				}
			}
		}
	}
    