<?php 

// get company data
function company_data() {
	global $conn;

	$sql = "
		SELECT * FROM jspence
	";
	$statement = $conn->prepare($sql);
	$statement->execute();
	$rows = $statement->fetchAll();
	$row = $rows[0];

	return $row;
}

// get admin position
function _admin_position($permission) {

	$output = 'admin';
	if (admin_has_permission()) {
		$output = 'admin';
	} else if ($permission == 'supervisor') {
		$output = 'supervisor';
	} else if ($permission == 'salesperson') {
		$output = 'salespersonnel';
	}

	return $output;
}

// check if capital is given today
function is_capital_given() {
	global $conn;
	global $admin_data;

	$today = date('Y-m-d');
	$sql = "
		SELECT *
		FROM jspence_daily 
		WHERE daily_date = ? 
		AND daily_to = ? 
		AND daily_capital_status = ? 
		ORDER BY daily_date DESC 
		LIMIT 1
	";
	$statement = $conn->prepare($sql);
	$statement->execute([$today, $admin_data['admin_id'], 0]);
	$count_row = $statement->rowCount();
	$rows = $statement->fetchAll();

	return ((is_array($rows) && $count_row > 0) ? true : false);
}

//
function find_capital_given_to($to) {
	global $conn;

	$sql = "
		SELECT *
		FROM jspence_daily 
		WHERE daily_to = ? 
		AND daily_capital_status = ? 
		ORDER BY daily_date DESC 
		LIMIT 1
	";
	$statement = $conn->prepare($sql);
	$statement->execute([$to, 0]);
	$count_row = $statement->rowCount();
	$row = $statement->fetchAll();

	if ($count_row > 0) {
		return $row[0];
	}
	return false;
}

// Amount given to trade
function _capital($admin, $d = null, $for = null) {
	global $conn;
	$today = date('Y-m-d');
	$today = (($d != null) ? $d : $today);

	$sql = "
		SELECT daily_id, daily_capital, daily_balance, daily_capital_status, push_status, push_daily , daily_to 
		FROM jspence_daily 
		INNER JOIN jspence_pushes 
		-- ON jspence_pushes.push_daily = jspence_daily.daily_id 
		INNER JOIN jspence_coffers 
		ON (
			jspence_coffers.coffers_id = jspence_pushes.push_daily 
			OR jspence_pushes.push_daily = jspence_daily.daily_id
		) 
		WHERE jspence_daily.daily_to = ? 
		AND jspence_daily.daily_capital_status = ? 
		AND jspence_pushes.push_status = ? 
		AND jspence_coffers.coffers_status = ? 
		ORDER BY jspence_daily.daily_date DESC
		LIMIT 1
	";
	$statement = $conn->prepare($sql);
	$statement->execute([$admin, 0, 0, 'send']);
	$rows = $statement->fetchAll();

	$balance = null;
	$output = [
		'today_capital' => null,
		'today_balance' => $balance,
		'today_capital_id' => null
	];

	if ($statement->rowCount() > 0): 
		$row = $rows[0];
		$balance = $row['daily_balance'];

		if (admin_has_permission('supervisor') && ($row['daily_balance'] == null || $row['daily_balance'] == '0.00')) {
			$balance = $row['daily_balance'];
		} else if (admin_has_permission('salesperson')) {
			$balance = (($row['daily_balance'] == null || $row['daily_balance'] == '0.00' || $row['daily_balance'] == 0) ? $row['daily_capital'] : $row['daily_balance']);
			// if ($row["daily_capital_status"] == 1) {
			// 	$balance = null;
			// }
		}

		// get balance on make push
		if ($for == 'push') {
			$balance = (($row['daily_balance'] == null || $row['daily_balance'] == '0.00' || $row['daily_balance'] == 0) ? null : $row['daily_balance']);
		}

		// get balance on push reversal
		if ($for == 'reversal') {
			$balance = (($row['daily_balance'] == null || $row['daily_balance'] == '0.00' || $row['daily_balance'] == 0) ? $row['daily_capital'] : $row['daily_balance']);
		}
		
		$output = [
			'today_capital' => $row['daily_capital'],
			'today_balance' => $balance,
			'today_capital_id' => $row['daily_id']
		];
	endif;

	return $output;
}

// fetch supervisor remaining gold
function remaining_gold_balance($admin) {
	return _capital($admin)['today_balance']; // $output;
}

// check if balance is exhausted or not
function is_capital_exhausted($conn, $admin) {
	$runningCapital = find_capital_given_to($admin);

	if (is_array($runningCapital)) {
		$today = $runningCapital['daily_date'];
		$t = (admin_has_permission('supervisor') ? 'in' : 'out');

		$q = "
			SELECT 
				SUM(jspence_sales.sale_total_amount) AS ttsa, 
				CAST(jspence_sales.createdAt AS date) AS sd 
			FROM `jspence_sales` 
			WHERE CAST(jspence_sales.createdAt AS date) = ? 
			AND jspence_sales.sale_type = ? 
			AND jspence_sales.sale_by = ? 
			AND jspence_sales.sale_status = ?
		";
		$statement = $conn->prepare($q);
		$statement->execute([$today, $t, $admin, 0]);
		$r = $statement->fetchAll();
		
		$return = true;
		if ($r[0]['ttsa'] > 0) {
			$today_total_balance = (float)(_capital($admin)['today_capital'] - $r[0]['ttsa']);
			if (admin_has_permission('supervisor')) {
				$today_total_balance = $r[0]['ttsa'];
				$return = true;
			}
			
			if ($today_total_balance == 0) {
				$return = false;
			} else if (($today_total_balance > 0) && $today_total_balance < _capital($admin)['today_capital']) {
				$return = true;
			} 
		}
	}
	
	return $return;	
}

// sum all capital of today for super admin
function sum_capital_given_for_day() {
	global $conn;

	$sql = "
		SELECT SUM(daily_capital) AS sum
		FROM jspence_daily 
		WHERE daily_capital_status = ? 
		AND status = ? 
		AND daily_date = ? 
	";
	$statement = $conn->prepare($sql);
	$statement->execute([0, 0, date('Y-m-d')]);
	$count_row = $statement->rowCount();
	$row = $statement->fetchAll();

	if ($count_row > 0) {
		return $row[0]['sum'];
	}

	return false;
}

// get today capital given balance
function update_today_capital_given_balance($type, $today_capital, $today_total_balance, $pf, $last_sale, $today, $admin) {
	global $conn;

	$data = [$today_total_balance, $today, $admin];
	$sql = "
		UPDATE jspence_daily 
		SET daily_balance = ?, daily_profit = daily_profit + '".$pf."'  
		WHERE daily_date = ? 
		AND daily_to = ?
	";
	$statement = $conn->prepare($sql);
	$statement->execute($data);
	
	$message = $type . " made, balance remaining is: " . money($today_total_balance) . " and " . $today . " capital was:  " . money(_capital($admin)['today_capital']);
	add_to_log($message, $admin);
}

function _gained_calculation($balance, $capital, $last_sale, $admin) {
	$output = 0;
	$gb = remaining_gold_balance($admin); // gold balance


	// incase gold balance is 0 then calculate the actual earnings/gained amount
	if ($gb <= 0) {
		// $output = (float)($balance - $capital);
		$tst = total_sale_amount_today($admin);
		$accumulated = $tst["sum"];

		$output = (float)($last_sale - $balance);

		$runningCapital = find_capital_given_to($admin);
		if (is_array($runningCapital)) {
			$output = (float)($output + $runningCapital['daily_profit']);
		}
	}

	return $output;
}

// get pushes
function get_pushes_made($admin, $permission, $today = null) {
	global $conn;

	$sql = "
		SELECT * FROM jspence_pushes 
		INNER JOIN jspence_admin 
		ON jspence_admin.admin_id = jspence_pushes.push_to
		WHERE jspence_pushes.push_status = ? 
	";

	if (!admin_has_permission()) {
        $sql .= ' AND (push_to = "' . $admin . '" OR push_from IN (SELECT push_from FROM jspence_pushes WHERE push_from = "' . $admin . '")) AND push_date = "' . $today . '" ';
    }
	if ($permission == 'salesperson') {
		$sql .= " AND jspence_pushes.push_from_where != 'end-trade'";
	}

	if ($today != null) {
		$sql .= 'AND jspence_pushes.push_date = "' . $today . '"';
	}

	$sql .= ' ORDER BY jspence_pushes.id DESC';

	$statement = $conn->prepare($sql);
	$result = $statement->execute([0]);
	$output = '';
	
	if ($statement->rowCount() > 0): 
		foreach ($statement->fetchAll() as $row) {
			$message = '';
			if (admin_has_permission()) {
				$message = 'ed to ' . ucwords($row["admin_fullname"]);
			} else {
				if ($row["push_from"] == $admin) {
					$message = 'ed to ' . ucwords($row["admin_fullname"]);
				} else {
					$message = ' received';
				}
			}
			$output .= '
				<div class="list-group-item px-0">
					<div class="row align-items-center">
						<div class="col-auto">
							<div class="avatar">
								<div
									class="progress progress-circle text-success"
									role="progressbar"
									aria-label="Reduce response time"
									aria-valuenow="100"
									aria-valuemin="0"
									aria-valuemax="100"
									data-bs-toggle="tooltip"
									data-bs-title="100%"
									style="--bs-progress-circle-value: 100"
								></div>
							</div>
						</div>
						<div class="col ms-n2">
							<h6 class="fs-base fw-normal mb-1">' . money($row["push_amount"]) . ' push' . $message . '</h6>
							<span class="fs-sm text-body-secondary">' . time_from_date($row["createdAt"]) . '</span>
						</div>
						<div class="col-auto">
							<time class="text-body-secondary" datetime="01/01/2025">' . pretty_date_only($row["createdAt"]) .'</time>
						</div>
					</div>
				</div>
			';
		}
	else: 
		$output = "
			<div class='alert alert-info'>No data found!</div>
		";
	endif;

	return $output;
}

// fetch total send push made
function get_total_send_push($conn, $admin, $p = null) {
	$permission = (($p != null) ?? $p);
	$runningCapital = find_capital_given_to($admin);
	$type = (admin_has_permission('supervisor') ? "money" : "gold");
	$output = [
		"sum" => 0, 
		"count" => 0
	];

	if (is_array($runningCapital) || admin_has_permission()) {
		$date = ((admin_has_permission()) ? date("Y-m-d") : $runningCapital['daily_date']);

		$query = "
			SELECT 
				SUM(jspence_pushes.push_amount) AS pamt, 
				COUNT(jspence_pushes.id) AS c 
			FROM jspence_pushes 
			WHERE jspence_pushes.push_date = ?
		";
		if ($p == 'supervisor') {
			$query .= " AND push_from_where != 'physical-cash'";
		}
		if  (!admin_has_permission()) {
			$query .= " AND jspence_pushes.push_from = '" . $admin . "' AND push_type = '" . $type . "'";
		}
		$statement = $conn->prepare($query);
		$result = $statement->execute([$date]);
		$row = $statement->fetchAll();

		if ($result) {
			$a = (($row[0]['pamt'] == null || $row[0]['pamt'] == '0.00' || $row[0]['pamt'] == 0) ? 0 : $row[0]['pamt']);
			$output = [
				"sum" => $a, 
				"count" => $row[0]["c"]
			];
		}
	}
	return $output;
}

// fetch total receive push
function get_total_receive_push($conn, $admin) {
	$type = (admin_has_permission('supervisor') ? "gold" : "money");
	$runningCapital = find_capital_given_to($admin);
	$output = [
		"sum" => 0, 
		"count" => 0
	];

	if (is_array($runningCapital) || admin_has_permission()) {
		$date = ((admin_has_permission()) ? date("Y-m-d") : $runningCapital['daily_date']);

		$query = "
			SELECT 
				SUM(jspence_pushes.push_amount) AS pamt, 
				COUNT(jspence_pushes.id) AS c 
			FROM jspence_pushes 
			WHERE jspence_pushes.push_date = ?
		";
		if  (!admin_has_permission()) {
			$query .= " AND jspence_pushes.push_to = '" . $admin . "' AND push_type = '" . $type . "'";
		}

		$statement = $conn->prepare($query);
		$result = $statement->execute([$date]);
		$row = $statement->fetchAll();

		if ($result) {
			$a = (($row[0]['pamt'] == null || $row[0]['pamt'] == '0.00' || $row[0]['pamt'] == 0) ? 0 : $row[0]['pamt']);
			$output = [
				"sum" => $a, 
				"count" => $row[0]["c"]
			];
		}
	}
	return $output;
}

// 
function get_total_pushes($conn, $admin, $p = null) {
	$permission = (($p != null) ?? $p);
	$s = get_total_send_push($conn, $admin, $permission);
	$r = get_total_receive_push($conn, $admin);
	
	if (count($s) > 0 && count($r) > 0) {

		$sum = ((float)$s["sum"] + $r["sum"]);
		$count = ((int)$s["count"] + $r["count"]);

		$array = [
			"sum" => $sum, 
			"count" => $count
		];

		return $array;
	}
	return false;
}


////////////////////////////////////////////////////////////////////////////////////

function truncate($val, $f = "0") {
    if (($p = strpos($val, '.')) !== false) {
        $val = floatval(substr($val, 0, $p + 1 + $f));
    }
    return $val;
}

// Density calculation
function calculateDensity($gram, $volume) {
	if (($gram != NULL || $gram != '') && $gram > 0) {
		if (($volume != NULL || $volume != '') && $volume > 0) {
			$density = ($gram / $volume);

			return truncate($density, 2);
		}
	}
	
	return false;
}

// Density calculation
function calculatePounds($gram) {
	if ($gram != NULL || $gram != '') {
		$pounds = ($gram / FIXED_POUNDS_FIGURE);
		
		return truncate($pounds, 2);
		
	}

	return false;
}

// Carat calculation
function calculateCarat($gram, $volume) {
	$density = calculateDensity($gram, $volume);
	if ($density) {
		$a = $density - 10.51;
		$b = $a * 52.838;
		$c = $b / $density;
		
		return truncate($c, 2);
	}
	
	return false;
}

// Total amount calculation
function calculateTotalAmount($gram, $volume, $current_price) {
	$carat = calculateCarat($gram, $volume);
	$pounds = calculatePounds($gram);

	$total_amount = ($carat * $current_price / FIXED_TOTAL_FIGURE * $pounds);
	return (int)$total_amount;
}


// add to logs
function add_to_log($message, $log_admin) {
	global $conn;

	$log_id = guidv4();
	$createdAt = date("Y-m-d H:i:s");
	$sql = "
		INSERT INTO `jspence_logs`(`log_id`, `log_message`, `log_admin`, `createdAt`) 
		VALUES (?, ?, ?, ?)
	";
	$statement = $conn->prepare($sql);
	$result = $statement->execute([$log_id, $message, $log_admin, $createdAt]);

	return false;
	if ($result) {
		return true;
	}
}

function fetch_all_sales($status, $admin, $type = null) {
	global $conn;
	$output = '';

	$sql = "
		SELECT *, jspence_sales.id AS sid, jspence_sales.createdAt AS sca, jspence_sales.updatedAt AS sua, jspence_admin.id AS aid 
		FROM jspence_sales 
		INNER JOIN jspence_admin 
		ON jspence_admin.admin_id = jspence_sales.sale_by 
		WHERE sale_status = ? 
	";
	if (!admin_has_permission()) {
		$sql .= ' AND sale_by = "' . $admin . '" ';
	}
	if ($type == 'no_exp') {
		$sql .= " AND sale_type != 'exp' ";
	}
	$sql .= " ORDER BY createdAt DESC";
	
	$statement = $conn->prepare($sql);
	$statement->execute([$status]);
	$rows = $statement->fetchAll();

	if ($statement->rowCount() > 0) {
		$i = 1;
		foreach ($rows as $row) {

			$type = "";
			if ($row["sale_type"] == 'out') {
				$type = '
					<span class="badge bg-danger-subtle text-danger">out-trade</span>
				';
			} else if ($row["sale_type"] == 'in') {
				$type = '
					<span class="badge bg-success-subtle text-success">in-trade</span>
				';
			} else if ($row["sale_type"] == 'exp') {
				$type = '
					<span class="badge bg-secondary-subtle text-secondary">expenditure</span>
				';
			}
			
			$option1 = '';
	        $option2 =  '
				<div class="p-2"></div>
				<div class="px-6 py-5 d-flex justify-content-center">
					<button class="btn btn-dark"><i class="bi bi-receipt me-2"></i>Print receipt</button>&nbsp<a href="#deleteModal_'. $row["sid"] . '" data-bs-toggle="modal" class="btn btn-danger"><span class="material-symbols-outlined me-2"> delete </span> Delete</a>
				</div>
	        ';
			if ($row['sale_status'] == 1) {
				$option1 = '';
				$option2 = '';
			}
			
			$output .= '
				<tr>
	                <td>' . $i . '</td>
	                ' . (admin_has_permission() ? ' <td><a href="javascript:;" data-bs-target="#adminModal_' . $row["aid"] . '" data-bs-toggle="modal"><span class="d-block text-heading fw-bold">' . ucwords($row["admin_fullname"]) . '</span></a></td> ' : '') . '
	                <td class="text-xs">' . strtoupper($row["sale_customer_name"]) . ' <span class="material-symbols-outlined mx-2"> trending_flat </span> ' . $row["sale_customer_contact"] . '</td>
	                <td>' . $row["sale_gram"] . '</td>
	                <td>' . $row["sale_volume"] . '</td>
	                <td>' . money($row["sale_price"]) . '</td>
	                <td>' . money($row["sale_total_amount"]) . '</td>
	                <td>' . $type . '</td>
	                <td>' . pretty_date($row["sca"]) . '</td>
	                <td class="text-end">
	                    <button type="button" class="btn btn-dark btn-sm" title="More" data-bs-target="#saleModal_' . $row["sid"] . '" data-bs-toggle="modal">
	                        <span class="material-symbols-outlined"> table_eye </span> 
	                    </button> '.$option1.'
	                </td>
	            </tr>

	            <!-- Trade details -->
	            <div class="modal fade" id="saleModal_' . $row["sid"] . '" tabindex="-1" aria-labelledby="saleModalLabel_' . $row["sid"] . '" aria-hidden="true" style="backdrop-filter: blur(5px);">
					<div class="modal-dialog modal-sm modal-dialog-centered">
						<div class="modal-content overflow-hidden">
							<div class="modal-header pb-0 border-0">
								<h1 class="modal-title h4" id="saleModalLabel_' . $row["sid"] . '">' . $row["sale_id"] . (admin_has_permission() ? '<br>by ' .ucwords($row["admin_fullname"]) : '' )  . '</h1>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div class="modal-body">
								<ul class="list-group list-group-flush">
									<div class="list-group-item px-0">
										<div class="row align-items-center">
											<div class="col ms-n2">
												<h6 class="fs-base fw-normal mb-1">Total amount,</h6>
											</div>
											<div class="col-auto">
												<time class="text-body-secondary" datetime="01/01/2025">' . money($row["sale_total_amount"]) . '</time>
											</div>
										</div>
									</div>
									<div class="list-group-item px-0">
										<div class="row align-items-center">
											<div class="col ms-n2">
												<h6 class="fs-base fw-normal mb-1">Price,</h6>
											</div>
											<div class="col-auto">
												<time class="text-body-secondary" datetime="01/01/2025">' . money($row["sale_price"]) . '</time>
											</div>
										</div>
									</div>
									<div class="list-group-item px-0">
										<div class="row align-items-center">
											<div class="col ms-n2">
												<h6 class="fs-base fw-normal mb-1">Gram,</h6>
											</div>
											<div class="col-auto">
												<time class="text-body-secondary" datetime="01/01/2025">' . $row["sale_gram"] . '</time>
											</div>
										</div>
									</div>
									<div class="list-group-item px-0">
										<div class="row align-items-center">
											<div class="col ms-n2">
												<h6 class="fs-base fw-normal mb-1">Volume,</h6>
											</div>
											<div class="col-auto">
												<time class="text-body-secondary" datetime="01/01/2025">' . $row["sale_volume"] . '</time>
											</div>
										</div>
									</div>
									<div class="list-group-item px-0">
										<div class="row align-items-center">
											<div class="col ms-n2">
												<h6 class="fs-base fw-normal mb-1">Density,</h6>
											</div>
											<div class="col-auto">
												<time class="text-body-secondary" datetime="01/01/2025">' . $row["sale_density"] . '</time>
											</div>
										</div>
									</div>
									<div class="list-group-item px-0">
										<div class="row align-items-center">
											<div class="col ms-n2">
												<h6 class="fs-base fw-normal mb-1">Pounds,</h6>
											</div>
											<div class="col-auto">
												<time class="text-body-secondary" datetime="01/01/2025">' . $row["sale_pounds"] . '</time>
											</div>
										</div>
									</div>
									<div class="list-group-item px-0">
										<div class="row align-items-center">
											<div class="col ms-n2">
												<h6 class="fs-base fw-normal mb-1">Carat,</h6>
											</div>
											<div class="col-auto">
												<time class="text-body-secondary" datetime="01/01/2025">' . $row["sale_carat"] . '</time>
											</div>
										</div>
									</div>
									<div class="list-group-item px-0">
										<div class="row align-items-center">
											<div class="col ms-n2">
												<h6 class="fs-base fw-normal mb-1">Customer,</h6>
											</div>
											<div class="col-auto">
												<time class="text-body-secondary" datetime="01/01/2025">' . ucwords($row["sale_customer_name"]) . ' | Contact: ' . $row["sale_customer_contact"] . '</time>
											</div>
										</div>
									</div>
									<div class="list-group-item px-0">
										<div class="row align-items-center">
											<div class="col ms-n2">
												<h6 class="fs-base fw-normal mb-1">Note,</h6>
											</div>
											<div class="col-auto">
												<time class="text-body-secondary" datetime="01/01/2025">' . $row["sale_comment"] . '</time>
											</div>
										</div>
									</div>
									<div class="list-group-item px-0">
										<div class="row align-items-center">
											<div class="col ms-n2">
												<h6 class="fs-base fw-normal mb-1">Date,</h6>
											</div>
											<div class="col-auto">
												<time class="text-body-secondary" datetime="01/01/2025">' . pretty_date($row["sca"]) . '</time>
											</div>
										</div>
									</div>
								</ul>
								' . $option2 . '
							</div>
						</div>
					</div>
				</div>

				<!-- DELETE TRADE -->
				<div class="modal fade" id="deleteModal_' . $row["sid"] . '" tabindex="-1" aria-labelledby="deleteModalLabel_' . $row["sid"] . '" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true" style="backdrop-filter: blur(5px);">
				    <div class="modal-dialog modal-sm modal-dialog-centered">
				        <div class="modal-content overflow-hidden">
				            <div class="modal-header pb-0 border-0">
				                <h1 class="modal-title h4" id="deleteModalLabel_' . $row["sid"] . '">Delete trade!</h1>
				                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				            </div>
				            <div class="modal-body p-0">
			                    <div class="px-6 py-5 border-bottom">
			                       <p>
			                       Trade of Volume '.$row["sale_volume"].', Gram ' . $row["sale_gram"] . ', Price ' . money($row["sale_price"]) . ' and Amount ' . money($row["sale_total_amount"]) . ' from customer ' . ucwords($row["sale_customer_name"]) . ' will be notified to the main admin to complete the deletion!
			                       </p>
			                       <br>
			                       Trade ID: ' . $row["sale_id"] . '
			                       <br>
			                       <p>
			                       		Are you sure you want to proceed to this action.
			                       </p>
			                    </div>
			                    <div class="px-6 py-5 bg-body-secondary d-flex justify-content-center">
			                        <a href="'.PROOT.'account/trades?delete_request='.$row["sale_id"].'" class="btn btn-sm btn-danger"><i class="bi bi-trash me-2"></i>Yes, Confirm delete</a>&nbsp;&nbsp;
			                        <button type="button" class="btn btn-sm btn-dark"data-bs-dismiss="modal">No, cancel</button>
			                    </div>
				            </div>
				        </div>
				    </div>
				</div>

				<!-- HANDLER DETAILS -->
				<div class="modal fade" id="adminModal_' . $row["aid"] . '" tabindex="-1" aria-labelledby="adminModalLabel_' . $row["aid"] . '" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true" style="backdrop-filter: blur(5px);">
					<div class="modal-dialog modal-sm modal-dialog-centered">
						<div class="modal-content overflow-hidden">
							<div class="modal-header pb-0 border-0">
								<h1 class="modal-title h4" id="adminModalLabel_' . $row["aid"] . '">Handler details</h1>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div class="modal-body">
								<ul class="list-group">
									<li class="list-group-item" style="padding: 0.1rem 1rem;">
				                        <small class="text-muted">Profile,</small>
				                        <p>' . (($row["admin_profile"] != '') ? '<div class="avatar"><img src="' . PROOT . $row["admin_profile"] . '" class="img-fluid" /></div>' : 'No Profile') . '</p>
				                    </li>
				                    <li class="list-group-item" style="padding: 0.1rem 1rem;">
				                        <small class="text-muted">Full  name,</small>
				                        <p>' . ucwords($row["admin_fullname"]) . '</p>
				                    </li>
				                    <li class="list-group-item" style="padding: 0.1rem 1rem;">
				                        <small class="text-muted">Email,</small>
				                        <p>' . $row["admin_email"] . '</p>
				                    </li>
				                    <li class="list-group-item" style="padding: 0.1rem 1rem;">
				                        <small class="text-muted">Date Joined</small>
				                        <p>' . pretty_date($row["admin_joined_date"]) . '</p>
				                    </li>
				                    <li class="list-group-item" style="padding: 0.1rem 1rem;">
				                        <small class="text-muted">Last Login</small>
				                        <p>' . (($row["admin_last_login"] == NULL) ? 'NEVER' : pretty_date($row["admin_last_login"])) . '</p>
				                    </li>
								</ul><div class="p-2"></div>
								<div class="d-flex justify-content-center">
									<a class="btn btn-light" href="' . PROOT . 'account/admins"><i class="bi bi-people me-2"></i>All admins</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			';

			$i++;
		}
	} else {
		$output = '
			<tr>
				<td colspan="9">
					<div class="alert alert-info"">No data found.</div>
				</td>
			</tr>
		';
	}

	return $output;
}


// get total accumulated amount for today
function total_amount_today($admin) {
	global $conn;
	$today = date('Y-m-d');

	$runningCapital = find_capital_given_to($admin);

	// fetch today total amount

	$total = 0;
	if (is_array($runningCapital)) {
		$sql = "
			SELECT SUM(sale_total_amount) AS total
			FROM `jspence_sales` 
			WHERE jspence_sales.sale_status = ? 
			AND sale_type != ? 
			AND jspence_sales.sale_daily = ?
		";
		if (!admin_has_permission()) {
			$sql .= ' AND sale_by = "' . $admin . '" ';
			if (admin_has_permission('salesperson')) {
				$sql .= ' AND sale_pushed = 0 ';
			}
		}

		$statement = $conn->prepare($sql);
		$statement->execute([0, 'exp', $runningCapital['daily_id']]);
		$thisDayrow = $statement->fetchAll();

		$total_amount_traded = $thisDayrow[0]['total'] ?? 0;

		// get all pushed amout 
		$get_pushed = $conn->query("SELECT SUM(push_amount) AS pamt FROM jspence_pushes WHERE push_from = '" . $admin . "' AND push_date = '" . $runningCapital['daily_date'] . "' AND push_daily = '" . $runningCapital['daily_id'] . "' AND jspence_pushes.push_from_where = 'daily'")->fetchAll();
		$total_amount_pushed = $get_pushed[0]['pamt'] ?? 0;
		// if (admin_has_permission('salesperson')) {
		// 	$total_amount_pushed = 0;
		// }

		// fetch all revese pushes
		$reverse_pushes = $conn->query("SELECT SUM(push_amount) AS pamt FROM jspence_pushes WHERE push_from = '" . $admin . "' AND push_date = '" . $runningCapital['daily_date'] . "' AND push_daily = '" . $runningCapital['daily_id'] . "' AND jspence_pushes.push_from_where = 'dialy' AND push_status = 1")->fetchAll();
		$r_total_amount_pushed = $reverse_pushes[0]['pamt'] ?? 0;

		if (admin_has_permission('salesperson') && $total_amount_traded <= 0) {
			$total_amount_pushed = $total_amount_traded;
		}

		if (admin_has_permission('supervisor') && $total_amount_traded < 0) {
			$total_amount_traded = $total_amount_pushed;
		}

		// sum total amount traded and subtract pushes and add back reverse pushes
		$total = (float)($total_amount_traded - $total_amount_pushed + $r_total_amount_pushed);
	}
	
	return $total;
}

// get total expenditure 
function total_expenditure_today($admin, $option = null) {
	global $conn;
	$runningCapital = find_capital_given_to($admin);
	$array = [
		"sum" => 0,
		"count" => 0
	];

	if (is_array($runningCapital) || admin_has_permission()) {
		$d = ((admin_has_permission())? null : $runningCapital['daily_id']); 
		$date = ((admin_has_permission())? date("Y-m-d") : $runningCapital['daily_date']); 

		$sql = "
			SELECT 
				SUM(sale_total_amount) AS total,
				COUNT(id) AS c
			FROM `jspence_sales` 
			WHERE jspence_sales.sale_type = ? 
			AND jspence_sales.sale_status = ? 
			AND CAST(CreatedAt AS date) = ? 
		";

		if (!admin_has_permission()) {
			$sql .= " AND sale_daily = '" . $d . "' AND sale_by = '" . $admin . "'";
		}

		$statement = $conn->prepare($sql);
		$statement->execute([
			'exp', 
			(($option == 'delete') ? 1 : 0), 
			$date
		]);
		$row = $statement->fetchAll();

		$array = [
			"sum" => $row[0]['total'] ?? 0,
			"count" => $row[0]['c']
		];
	}
	return $array;
}

// get total amount of sales with "expenditure" today
function total_sale_amount_today($admin, $del = null, $option = null, $DT = null) {
	global $conn;
	$today = (($DT == null) ? date('Y-m-d') : $DT);

	$sql = "
		SELECT 
			SUM(sale_total_amount) AS total, 
			COUNT(jspence_sales.id) AS c 
		FROM `jspence_sales` 
		INNER JOIN jspence_daily
		ON jspence_daily.daily_id = jspence_sales.sale_daily
		WHERE jspence_daily.daily_capital_status = ? 
		AND CAST(jspence_sales.createdAt AS date) = jspence_daily.daily_date 
	";

	if ($del == 'delete') {
		$sql .= " AND jspence_sales.sale_status = 2 ";
	} else {
		$sql .= " AND (jspence_sales.sale_status = 0 OR  jspence_sales.sale_status = 1) ";
	}

	if (!admin_has_permission()) {
		// $sql .= " AND sale_by = '" . $admin . "' AND CAST(jspence_sales.createdAt AS date) = '" . $today . "' ";
		$sql .= " AND sale_by = '" . $admin . "'";
	}

	if ($option == 'exp') {
		$sql .= " AND sale_type != 'exp' ";
	}

	$statement = $conn->prepare($sql);
	$statement->execute([0]);
	$thisDayrow = $statement->fetchAll();

	$array = [
		"sum" => $thisDayrow[0]['total'] ?? 0,
		"count" => $thisDayrow[0]['c']
	];

	return $array;
}

// get total amount of trades today
function total_trades_amount_today() {
	global $conn;

	$thisSql = "
		SELECT SUM(sale_total_amount) AS total 
		FROM `jspence_sales` 
		WHERE CAST(createdAt as date) = ? 
	    AND sale_status = ? 
	";
	$statement = $conn->prepare($thisSql);
	$statement->execute([date("Y-m-d"), 0]);
	$thisRow = $statement->fetchAll();

	return money($thisRow[0]['total']);
}

// count total orders
function count_total_orders($admin) {
	global $conn;

	$where = '';
	if (!admin_has_permission()) {
		$where = ' AND sale_by = "'.$admin.'"';
	}

	$sql = "
		SELECT COUNT(sale_id) AS total_number 
		FROM `jspence_sales` 
		WHERE sale_status = 0 
		$where 
	";
	$statement = $conn->prepare($sql);
	$statement->execute();
	$row = $statement->fetchAll();
	
	return $row[0]['total_number'];
}

// count total orders
function count_today_orders($admin) {
	global $conn;
	$runningCapital = find_capital_given_to($admin);
	$a = 0;

	if (is_array($runningCapital)) {	

		$sql = "
			SELECT COUNT(sale_id) AS total_number 
			FROM `jspence_sales` 
			WHERE sale_status = ? 
			AND sale_daily = ? 
		";
		if (!admin_has_permission()) {
			$sql .= ' AND sale_by = "'.$admin.'"';
		}
		$statement = $conn->prepare($sql);
		$statement->execute([0, $runningCapital['daily_id']]);
		$row = $statement->fetchAll();
		$a = $row[0]['total_number'] ?? 0;
	}
	
	return $a;
}

// get grand amount of orders
function grand_total_amount($admin) {
	global $conn;
	$thisYear = date("Y");
	$lastYear = $thisYear - 1;

	$output = [];

	$where = '';
	if (!admin_has_permission()) {
		$where = ' sale_by = "'.$admin.'" AND ';
	}

	$thisSql = "
		SELECT SUM(sale_total_amount) AS total 
		FROM `jspence_sales` 
		WHERE $where 
		YEAR(createdAt) = '{$thisYear}' 
	    AND sale_status = 0 
	";
	$statement = $conn->prepare($thisSql);
	$statement->execute();
	$thisRow = $statement->fetchAll();

	$thisPercentage = ($thisRow[0]['total'] / 100);

	$lastSql = "
		SELECT SUM(sale_total_amount) AS total 
		FROM `jspence_sales` 
		WHERE $where 
		YEAR(createdAt) = '{$lastYear}' 
	    AND sale_status = 0 
	";
	$statement = $conn->prepare($lastSql);
	$statement->execute([]);
	$lastRrow = $statement->fetchAll();

	$lastPercentage = ($lastRrow[0]['total'] / 100);

	if ($thisPercentage > $lastPercentage) {
		// going top
		$percentage = (($thisPercentage + $lastPercentage) / 100);
		$percentage_icon = 'up';
	} else {
		// going down
		$percentage = (($thisPercentage - $lastPercentage) / 100);
		$percentage_icon = 'down';
	}

	$where = '';
	if (!admin_has_permission()) {
		$where = ' AND sale_by = "'.$admin.'"';
	}
	$grandTotalSql = "
		SELECT SUM(sale_total_amount) AS total 
		FROM `jspence_sales` 
		WHERE sale_status = 0 
		$where
	";
	$statement = $conn->prepare($grandTotalSql);
	$statement->execute();
	$grandTotalRow = $statement->fetchAll();

	$output = [
		'grand_total' 			=> money($grandTotalRow[0]['total']),
		'this_year' 			=> money($thisRow[0]['total']),
		'last_year' 			=> money($lastRrow[0]['total']),
		'percentage' 			=> $percentage,
		'percentage_icon' 		=> $percentage_icon,
	];

	return $output;
}

// get logs for admins
function get_logs($admin) {
	global $conn;
	$today = date("Y-m-d");
	$output = '';

	$where = '';
	if (!admin_has_permission()) {
		$where = ' WHERE jspence_logs.log_admin = "'.$admin.'" AND CAST(jspence_logs.createdAt AS date) = "' . $today . '"';
	}

	$sql = "
		SELECT * FROM jspence_logs 
		INNER JOIN jspence_admin 
		ON jspence_admin.admin_id = jspence_logs.log_admin
		$where 
		ORDER BY jspence_logs.createdAt DESC
		LIMIT 10
	";
	$statement = $conn->prepare($sql);
	$statement->execute();
	$rows = $statement->fetchAll();

	if ($statement->rowCount() > 0): 
		foreach ($rows as $row) {
			$admin_name = explode(' ', $row['admin_fullname']);
			$admin_name = ucwords($admin_name[0]);

			$output .= '
				<li data-icon="account_circle">
					<div>
						<h6 class="fs-base mb-1">' . (($row["log_admin"] == $admin) ? 'You': $admin_name) . ' <span class="fs-sm fw-normal text-body-secondary ms-1">' . pretty_date($row["createdAt"]) .'</span></h6>
						<p class="mb-0">' . $row["log_message"] . '</p>
					</div>
				</li>
			';
		}
	else:
		$output .= '
				<div class="alert alert-info">
					No data found!
				</div>
			';
	endif;

	return $output;
}

// count logs
function count_logs($admin) {
	global $conn;
	$today = date("Y-m-d");

    $where = '';
    if (!admin_has_permission()) {
        $where = ' WHERE jspence_admin.admin_id = "' . $admin . '" AND CAST(jspence_logs.createdAt AS date) = "' . $today . '" ';
    }

    $sql = "
        SELECT * FROM jspence_logs 
        INNER JOIN jspence_admin 
        ON jspence_admin.admin_id = jspence_logs.log_admin
        $where 
        ORDER BY jspence_logs.createdAt DESC
    ";
    $statement = $conn->prepare($sql);
    $statement->execute();

    return $statement->rowCount();
}

// get recent trades
function get_recent_trades($admin) {
	global $conn;
	$output = '';
	$runningCapital = find_capital_given_to($admin);

	if (is_array($runningCapital)) { 
		$sql = "
			SELECT * FROM jspence_sales 
			WHERE sale_daily = ? 
			AND sale_status = ? 
		";
		if (!admin_has_permission()) {
			$sql .= ' AND sale_by = "' . $admin . '" ';
		}
		$sql .= " ORDER BY createdAt DESC";

		$statement = $conn->prepare($sql);
		$statement->execute([$runningCapital["daily_id"], 0]);
		$rows = $statement->fetchAll();
		$counts = $statement->rowCount();

		if ($counts > 0) {
			// code...
			foreach ($rows as $row) {
				$type = "";
				if ($row["sale_type"] == 'out') {
					// out-trade
					$type = '
						<span class="badge bg-danger-subtle text-danger">buy-gold</span>
					';
				} else if ($row["sale_type"] == 'in') { 
					// in-trade
					$type = '
						<span class="badge bg-success-subtle text-success">sell-gold</span>
					';
				} else if ($row["sale_type"] == 'exp') {
					$type = '
						<span class="badge bg-secondary-subtle text-secondary">expenditure</span>
					';
				}
				$output .= '
					<tr>
						<td>
							<div class="d-flex align-items-center">
								<div class="avatar text-info">
									<i class="fs-4" data-duoicon="align-bottom"></i>
								</div>
								<div class="ms-4">
									<div>' . $row["sale_id"] . '</div>
									<div class="fs-sm text-body-secondary">Created on ' . pretty_date($row["createdAt"]) . '</div>
								</div>
							</div>
						</td>
						<td>
							' . $type . '
						</td>
						<td>
							' . money($row["sale_total_amount"]) . '
						</td>
						<td>
							' . (($row["sale_customer_name"] != null) ? ucwords($row["sale_customer_name"]) : '') . '
						</td>
					</tr>
				';
			}
		} else {
			$output = '
				<tr>
					<td colspan="4">
						<div class="alert alert-warning">
							<h6 class="progress-text mb-1 d-block">No trades found today!</h6>
							<p class="text-muted text-xs">Current date time: ' . date("l jS \of F Y h:i:s A") . '</p>
						</div>
					</td>
				</tr>
			';
		}
	}

	return $output;
}

//
function get_salepersons_for_push_capital($conn) {
	$rows = $conn->query("SELECT * FROM jspence_admin WHERE admin_permissions = 'salesperson' AND admin_status = 0")->fetchAll();
	$output = '';
	foreach ($rows as $row) {
		$output .= '<option value="' . $row['admin_id'] . '">' . ucwords($row["admin_fullname"]) . '</option>';
	}

	return $output;
}

//
function get_supervisors_for_push_capital($conn) {
	$rows = $conn->query("SELECT * FROM jspence_admin WHERE admin_permissions = 'supervisor' AND admin_status = 0")->fetchAll();
	$output = '';
	foreach ($rows as $row) {
		$output .= '<option value="' . $row['admin_id'] . '">' . ucwords($row["admin_fullname"]) . '</option>';
	}

	return $output;
}

// find daily to push
function find_dialy_for_push($t, $id) {
	global $conn;
	//
	$sql = "
		SELECT * FROM jspence_daily 
		WHERE daily_date = ? 
		AND daily_id = ? 
		LIMIT 1
	";
	$statement = $conn->prepare($sql);
	$result = $statement->execute([$t, $id]);

	if ($result) {
		return true;
	}
	return false;
}

// find admin
function find_admin_with_id($id) {
	global $conn;

	$sql = "
		SELECT * FROM jspence_admin 
		WHERE admin_id = ? 
		LIMIT 1
	";
	$statement = $conn->prepare($sql);
	$statement->execute([$id]);
	$rows = $statement->fetchAll();
	$row = $rows[0];

	if ($statement->rowCount() > 0) {
		return $row;
	}
	return false;
}


// sum of
function sum_up_given_units($conn, $admin) {
	$output = '';
	$today = date("Y-m-d");

	$where = '';
	if (!admin_has_permission()) {
		$where = " AND jspence_sales.sale_by = '" . $admin . "' AND CAST(createdAt AS date) = '" . $today . "'";
	}
	
	$sql = "
		SELECT SUM(sale_gram) AS g, SUM(sale_volume) AS v, SUM(sale_density) AS d, SUM(sale_pounds) AS p, SUM(sale_carat) AS c 
		FROM jspence_sales 
		INNER JOIN jspence_admin 
		ON jspence_admin.admin_id = jspence_sales.sale_by
		WHERE sale_status = ? 
		$where 
		GROUP BY jspence_sales.sale_by
	";
	$statement = $conn->prepare($sql);
	$statement->execute([0]);
	$rows = $statement->fetchAll();

	if ($statement->rowCount() > 0) {
		foreach ($rows as $row) {
			$output .= '
				<tr>
					<td>' . $row["g"] . '</td>
					<td>' . $row["v"] . ' </td>
					<td>' . $row["d"] . ' </td>
					<td>' . $row["p"]. ' </td>
					<td>' . $row["c"]. ' </td>
				</tr>
			';
		}
	} else {
		$output = '
			<tr>
				<td colspan="5">
					<div class="alert alert-warning">
						<h6 class="progress-text mb-1 d-block">No data found today!</h6>
						<p class="text-muted text-xs">Current date time: ' . date("l jS \of F Y h:i:s A") . '</p>
					</div>
				</td>
			</tr>
		';
	}

	return $output;
}

// 
function push_unit_calculations($admin) {
	global $conn;
	$gram = 0;
	$volume = 0;
	$density = 0;
	$carat = 0;
	$pounds = 0;
	$output = [
		'p_gram' => $gram,
		'p_volume' => $volume,
		'p_density' => $density,
		'p_carat' => $carat,
		'p_pounds' => $pounds
	];

	$runningCapital = find_capital_given_to($admin);
	if (is_array($runningCapital)) {

		$query = "
			SELECT push_data FROM jspence_pushes 
			WHERE push_type = ? 
			AND push_from_where = ? 
			AND push_from = ? 
			AND push_date = ?
		";
		$statement = $conn->prepare($query);
		$statement->execute(['gold', 'daily', $admin, $runningCapital["daily_date"]]);
		$sub_rows = $statement->fetchAll();
		foreach	($sub_rows as $sub_row) {
			$jsonObject = $sub_row['push_data'];

			// Decode JSON data
			$data = json_decode($jsonObject, true);

			if (json_last_error() === JSON_ERROR_NONE) {
				$gram += $data['gram'];
				$volume += $data['volume'];

				$density = calculateDensity($gram, $volume);
				$carat = calculateCarat($gram, $volume);
				$pounds = calculatePounds($gram);
			}
		}

		$output = [
			'p_gram' => $gram,
			'p_volume' => $volume,
			'p_density' => $density,
			'p_carat' => $carat,
			'p_pounds' => $pounds
		];
	}

	return $output;
}

// summ all gram per admin for today
function sum_up_grams($conn, $admin) {
	$output = 0;
	$runningCapital = find_capital_given_to($admin);

	if (is_array($runningCapital)) {
		$sql = "
			SELECT SUM(sale_gram) AS g 
			FROM jspence_sales 
			WHERE sale_status = ? 
			-- AND sale_pushed = ? 
			AND sale_daily = ?
		";

		if (!admin_has_permission()) {
			// $sql .= " AND jspence_sales.sale_by = '" . $admin . "' AND CAST(createdAt AS date) = '" . $today . "'";
			$sql .= " AND jspence_sales.sale_by = '" . $admin . "'";
		}

		$statement = $conn->prepare($sql);
		$statement->execute([0, $runningCapital["daily_id"]]);
		$row = $statement->fetchAll();
		$g = $row[0]['g'];

		if ($g != null || $g != '') {
			$output = $g;
			if (admin_has_permission('salesperson')) {
				$arr = push_unit_calculations($admin);
				$output = ((float)$g - $arr['p_gram']);
			}
		}
	}

	return $output;
}

// summ all volume per admin for today
function sum_up_volume($conn, $admin) {
	$output = 0;
	$runningCapital = find_capital_given_to($admin);

	if (is_array($runningCapital)) {
		$sql = "
			SELECT SUM(sale_volume) AS v 
			FROM jspence_sales 
			WHERE sale_status = ? 
			-- AND sale_pushed = ? 
			AND sale_daily = ?
		";

		if (!admin_has_permission()) {
			// $sql .= " AND jspence_sales.sale_by = '" . $admin . "' AND CAST(createdAt AS date) = '" . $today . "'";
			$sql .= " AND jspence_sales.sale_by = '" . $admin . "'";
		}

		$statement = $conn->prepare($sql);
		$statement->execute([0, $runningCapital["daily_id"]]);
		$row = $statement->fetchAll();
		$v = $row[0]['v'];

		if ($v != null || $v != '') {
			$output = $v;
			if (admin_has_permission('salesperson')) {
				$arr = push_unit_calculations($admin);
				$output = ((float)$v - $arr['p_volume']);
			}
		}

	}
	return $output;
}

// summ all density per admin for today
function sum_up_density($conn, $admin) {
	$density = 0;
	if (sum_up_grams($conn, $admin) > 0) {
		if (sum_up_volume($conn, $admin) > 0) {
			$density = calculateDensity(sum_up_grams($conn, $admin), sum_up_volume($conn, $admin));
		}
	}
	
	return $density;
}

// summ all pounds per admin for today
function sum_up_pounds($conn, $admin) {
	return calculatePounds(sum_up_grams($conn, $admin));
}

// summ all carat per admin for today
function sum_up_carat($conn, $admin) {
	$carat = 0;
	if (sum_up_grams($conn, $admin) > 0) {
		if (sum_up_volume($conn, $admin) > 0) {
			$carat = calculateCarat(sum_up_grams($conn, $admin), sum_up_volume($conn, $admin));
		}
	}
	
	return $carat;
}

// get admin coffers balance (balance, receive or send)
function get_admin_coffers($conn, $admin, $action = null) {
	$output = 0;
	if ($action == 'receive') {
		$output = get_admin_coffers_received($conn, $admin);
	} else if ($action == 'send') {
		$output = get_admin_coffers_send($conn, $admin);
	} else {
		$output = (float)(get_admin_coffers_received($conn, $admin) - get_admin_coffers_send($conn, $admin));
	}

	return $output;
}

function get_admin_coffers_received($conn, $admin) {	
	$query = "
		SELECT SUM(coffers_amount) AS sum_received 
		FROM jspence_coffers 
		INNER JOIN jspence_pushes 
		ON jspence_pushes.push_daily = jspence_coffers.coffers_id 
		WHERE (coffers_status = ? OR coffers_status = ?) 
		-- AND jspence_pushes.push_status = ?
	";
	$statement = $conn->prepare($query);
	$statement->execute(['receive', 'reverse']);
	$rows = $statement->fetchAll();
	$row = $rows[0];

	if ($statement->rowCount() > 0) {
		return (($row['sum_received'] == null || $row['sum_received'] == '0.00') ? 0 : $row['sum_received']);
	}
	return 0;
}

function get_admin_coffers_send($conn, $admin) {
	$query = "
		SELECT 
			SUM(coffers_amount) AS sum_send 
		FROM jspence_coffers 
		INNER JOIN jspence_pushes 
		ON jspence_pushes.push_daily = jspence_coffers.coffers_id 
		WHERE coffers_status = ? 
		AND jspence_pushes.push_status = ?
	";
	$statement = $conn->prepare($query);
	$statement->execute(['send', 0]);
	$rows = $statement->fetchAll();
	$row = $rows[0];

	if ($statement->rowCount() > 0) {
		return (($row['sum_send'] == null || $row['sum_send'] == '0.00') ? 0 : $row['sum_send']);
	}
	return 0;
}

// check if capital was never touch and change it date to the next day
function capital_mover($admin) {
	global $conn;
	$today = date("Y-m-d");

	$a = "
		SELECT * FROM jspence_daily 
		WHERE jspence_daily.daily_to = ? 
		AND jspence_daily.daily_capital_status = ? 
		AND jspence_daily.status = ? 
		-- AND jspence_daily.daily_date = ?
		ORDER BY daily_date DESC 
		LIMIT 1
	";
	$statement = $conn->prepare($a);
	$statement->execute([$admin, 0, 0]);
	$row = $statement->fetchAll();

	if ($statement->rowCount() > 0) {
		
		if ($today != $row[0]["daily_date"]) { // check to see if that day is equal to our current date

			$b = $row[0]["daily_balance"];
			$capital = $row[0]["daily_capital"];

			// check if admin entered denomination
			$c = $conn->query("SELECT * FROM jspence_denomination WHERE denomination_capital = '" . $row[0]['daily_id'] . "' AND denomination_by = '" . $admin . "' AND status = 0 ORDER BY id DESC LIMIT 1")->rowCount();

			$result = [
				"msg" => ""
			];
			// if ($b == NULL && $c == 0) { // capital not touch, denomination not entered
			if ($b == $capital && $c == 0) { // capital not touch, denomination not entered

				// update capital date to the following day 
				$sql = "
					UPDATE jspence_daily 
					SET daily_date = ? 
					WHERE daily_id = ?
				";
				$statement = $conn->prepare($sql);
				$result = $statement->execute(
					[
						$today, 
						$row[0]["daily_id"]
					]
				);
				$result = "not-touched-updated";
			} else if ($b != NULL && $c == 0) { // capital touched, denomination not entered
				// auto enter denomination

				$tst = total_sale_amount_today($admin, null, null, $row[0]["daily_date"]); 
				$result = [
					"msg" => "touched", 
					"capital" => $row[0]["daily_capital"], 
					"balance" => $row[0]["daily_balance"], 
					"date" => $row[0]["daily_date"],
					"tt" =>  $tst["sum"]
				];
			}
			return $result;
		}
		return "same-date";
	}
	return false;	
}
