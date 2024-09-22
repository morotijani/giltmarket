<?php 


// get company data
function company_data() {
	global $conn;

	$sql = "
		SELECT * FROM jspence
	";
	$statement = $conn->prepare($sql);
	$statement->execute();
	$row = $statement->fetchAll();

	return $row;
}

// get admin position
function _admin_position($admin) {
	global $conn;

	$sql = "
		SELECT admin_permissions FROM jspence_admin 
		WHERE admin_id = ?
	";
	$statement = $conn->prepare($sql);
	$statement->execute([$admin]);
	$rows = $statement->fetchAll();
	$permission = $rows[0]['admin_permissions'];

	$output = 'admin';
	if (admin_has_permission()) {
		$output = 'admin';
	} else if ($permission == 'supervisor') {
		$output = 'supervisor';
	} else if ($permission == 'salesperson') {
		$output = 'salespersonnel';
	}

	return ucwords($output);
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
	";
	$statement = $conn->prepare($sql);
	$statement->execute([$today, $admin_data['admin_id']]);
	$count_row = $statement->rowCount();

	if ($count_row > 0) {
		return true;
	}
	return false;
}

//
function find_capital_given_to($to, $today) {
	global $conn;

	$sql = "
		SELECT *
		FROM jspence_daily 
		WHERE daily_date = ? 
		AND daily_to = ?
	";
	$statement = $conn->prepare($sql);
	$statement->execute([$today, $to]);
	$count_row = $statement->rowCount();
	$row = $statement->fetchAll();

	if ($count_row > 0) {
		return $row[0]['daily_id'];
	}
	return false;
}

// Amount given to trade
function _capital($admin) {
	global $conn;
	global $admin_data;

	$today = date('Y-m-d');

	$sql = "
		SELECT daily_id, daily_capital, daily_balance, jspence_admin.admin_permissions
		FROM jspence_daily 
		INNER JOIN jspence_admin 
		ON (jspence_admin.admin_id = jspence_daily.daily_by OR jspence_admin.admin_id = jspence_daily.daily_to)
		WHERE daily_date = ? 
		AND daily_to = ? 
		AND admin_id = ?
		LIMIT 1
	";
	$statement = $conn->prepare($sql);
	$statement->execute([$today, $admin, $admin]);
	$rows = $statement->fetchAll();

	$balance = null;
	$output = [
		'today_capital' => 0,
		'today_balance' => $balance,
		'today_capital_id' => 0
	];

	if ($statement->rowCount() > 0): 
		$row = $rows[0];
		$balance = $row['daily_balance'];

		if ($row["admin_permissions"] == 'supervisor' && $row['daily_balance'] == null) {
			$balance = $row['daily_balance'];
		} else if ($row["admin_permissions"] == 'salesperson') {
			$balance = (($row['daily_balance'] == null) ? $row['daily_capital'] : $row['daily_balance']);
		}
		
		$output = [
			'today_capital' => $row['daily_capital'],
			'today_balance' => $balance,
			'today_capital_id' => $row['daily_id']
		];
	endif;

	return $output;
}

// check if balance is exhausted or not
function is_capital_exhausted($conn, $admin) {
	$today = date("Y-m-d");
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
		$today_total_balance = (float)(_capital()['today_capital'] - $r[0]['ttsa']);
		if (admin_has_permission('supervisor')) {
			$today_total_balance = $r[0]['ttsa'];
			// return ($today_total_balance >= _capital()['today_capital']) ? true : false;
			$return = true;
		}
		
		if ($today_total_balance == 0) {
			$return = false;
		} else if (($today_total_balance > 0) && $today_total_balance < _capital()['today_capital']) {
			$return = true;
		} 
	}
	
	return $return;	
}
 
// get today capital given balance
function update_today_capital_given_balance($type, $today_total_balance, $today, $log_admin) {
	global $conn;

	$updateQ = "
		UPDATE jspence_daily 
		SET daily_balance = ? 
		WHERE daily_date = ? 
		AND daily_by = ?
	";
	$statement = $conn->prepare($updateQ);
	$statement->execute([$today_total_balance, $today, $log_admin]);
	
	$message = $type . " made, balance remaining is: " . money($today_total_balance) . " and " . $today . " capital was:  " . money(_capital()['today_capital']);
	add_to_log($message, $log_admin);
}

function _gained_calculation($balance, $capital) {
	$output = (float)($balance - $capital);
	if ($balance == null) {
		$output = $balance;
	}
	return money($output);
}

// get pushes
function get_pushes_made($admin, $today = null) {
	global $conn;

	$where = '';
	if (!admin_has_permission()) {
		$where .= ' AND jspence_pushes.push_to = "' . $admin . '" ';
	}

	if ($today != null) {
		$where .= 'AND jspence_pushes.push_date = "' . $today . '"';
	}

	$sql = "
		SELECT * FROM jspence_pushes 
		WHERE jspence_pushes.push_status = ?
		$where
	";
	$statement = $conn->prepare($sql);
	$result = $statement->execute([0]);
	$output = '';
	
	if ($statement->rowCount() > 0)
		foreach ($statement->fetchAll() as $row) {
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
							<h6 class="fs-base fw-normal mb-1">' . money($row["push_amount"]) . '</h6>
							<span class="fs-sm text-body-secondary">' . time_from_date($row["createdAt"]) . '</span>
						</div>
						<div class="col-auto">
							<time class="text-body-secondary" datetime="01/01/2025">' . pretty_date_only($row["createdAt"]) .'</time>
						</div>
					</div>
				</div>
			';
		}

	return $output;
}




function truncate($val, $f = "0") {
    if(($p = strpos($val, '.')) !== false) {
        $val = floatval(substr($val, 0, $p + 1 + $f));
    }
    return $val;
}

// Density calculation
function calculateDensity($gram, $volume) {
	$density = ($gram / $volume);
	return truncate($density, 2);
}

// Density calculation
function calculatePounds($gram) {
	$pounds = ($gram / FIXED_POUNDS_FIGURE);
	return truncate($pounds, 2);
}

// Carat calculation
function calculateCarat($gram, $volume) {
	$density = calculateDensity($gram, $volume);
	$a = $density - 10.51;
	$b = $a * 52.838;
	$c = $b / $density;
	return truncate($c, 2);
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

function fetch_all_sales($status, $admin) {
	global $conn;
	$output = '';

	$where = '';
	if (!admin_has_permission()) {
		$where = ' AND sale_by = "'.$admin.'" ';
	}

	$sql = "
		SELECT *, jspence_sales.id AS sid, jspence_sales.createdAt AS sca, jspence_sales.updatedAt AS sua, jspence_admin.id AS aid 
		FROM jspence_sales 
		INNER JOIN jspence_admin 
		ON jspence_admin.admin_id = jspence_sales.sale_by 
		WHERE sale_status = ? 
		$where 
		ORDER BY createdAt DESC
	";
	$statement = $conn->prepare($sql);
	$statement->execute([$status]);
	$rows = $statement->fetchAll();

	if ($statement->rowCount() > 0) {
		// code...
		$i = 1;
		foreach ($rows as $row) {

			$arrayOutput = array('reference' => $row['sale_id'], 'customername' => $row['sale_customer_name'], 'date' => $row['sca'], 'gram' => $row['sale_gram'], 'volume' => $row['sale_volume'], 'density' => $row['sale_density'], 'pounds' => $row['sale_pounds'], 'carat' => $row['sale_carat'], 'total_amount' => $row['sale_total_amount'], 'current_price' => $row['sale_price'], 'by' => $row['sale_by'], 'message' => '',);
			
			$outputData = json_encode($arrayOutput);
			
			$option1 = '&nbsp;<a href="javascript:;" onClick="MyWindow=window.open('.$outputData.',\'MyWindow\',\'width=600,height=300\'); return false;" title="Print receipt" class="btn btn-sm btn-square btn-neutral w-rem-6 h-rem-6">
	                        <i class="bi bi-receipt"></i>';
	        $option2 =  '
				<div class="p-2"></div>
				<div class="px-6 py-5 bg-body-secondary d-flex justify-content-center">
					<button class="btn btn-sm btn-dark"><i class="bi bi-receipt me-2"></i>Print receipt</button>&nbsp<a href="#deleteModal_'. $row["sid"] . '" data-bs-toggle="modal" class="btn btn-sm btn-neutral"><i class="bi bi-trash3 me-2"></i>Delete</a>
				</div>
	        ';
	        $option3 = '';
			if ($row['sale_status'] == 1) {
				// code...
				$option1 = '';
				$option2 = '';
				if (admin_has_permission()) {
					// code...
					$option3 = '
						<a href="' . PROOT . 'acc/trades.delete.requests?pd=' . $row["sale_id"] . '" class="btn btn-sm btn-danger mt-2 mb-2"><i class="bi bi-trash3 me-2"></i>Delete</a>
					';
				}
			} else if ($row['sale_status'] == 2) {
				$option1 = '';
				$option2 = '';
				$option3 = '';
			}
			
			$output .= '
				<tr>
	                <td>' . $i . '</td>
	                ' . (admin_has_permission() ? ' <td><a href="javascript:;" data-bs-target="#adminModal_' . $row["aid"] . '" data-bs-toggle="modal"><span class="d-block text-heading fw-bold">' . ucwords($row["admin_fullname"]) . '</span></a></td> ' : '') . '
	                <td class="text-xs">' . strtoupper($row["sale_customer_name"]) . ' <i class="bi bi-arrow-right mx-2"></i> ' . $row["sale_customer_contact"] . '</td>
	                <td style="font-family: Roboto Mono, monospace;">' . $row["sale_gram"] . '</td>
	                <td style="font-family: Roboto Mono, monospace;">' . $row["sale_volume"] . '</td>
	                <td style="font-family: Roboto Mono, monospace;">' . money($row["sale_price"]) . '</td>
	                <td style="font-family: Roboto Mono, monospace;">' . money($row["sale_total_amount"]) . '</td>
	                <td>' . pretty_date($row["sca"]) . '</td>
	                <td class="text-end">
	                    <button type="button" class="btn btn-sm btn-square btn-neutral w-rem-6 h-rem-6" title="More" data-bs-target="#saleModal_' . $row["sid"] . '" data-bs-toggle="modal">
	                        <i class="bi bi-three-dots"></i>
	                    </button> '.$option1.'
	                    </a>
	                </td>
	            </tr>

	            <!-- Trade details -->
	            <div class="modal fade" id="saleModal_' . $row["sid"] . '" tabindex="-1" aria-labelledby="saleModalLabel_' . $row["sid"] . '" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered">
						<div class="modal-content overflow-hidden">
							<div class="modal-header pb-0 border-0">
								<h1 class="modal-title h4" id="saleModalLabel_' . $row["sid"] . '">' . $row["sale_id"] . ' <br>by ' . (admin_has_permission() ? ucwords($row["admin_fullname"]) : 'you' )  . '</h1>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div class="modal-body p-0 text-center">
								<ul class="list-group">
									<li class="list-group-item" style="padding: 0.1rem 1rem;">
				                        <small class="text-muted">Total amount,</small>
				                        <p style="font-family: Roboto Mono, monospace;">' . money($row["sale_total_amount"]) . '</p>
				                    </li>
				                    <li class="list-group-item" style="padding: 0.1rem 1rem;">
				                        <small class="text-muted">Price,</small>
				                        <p style="font-family: Roboto Mono, monospace;">' . money($row["sale_price"]) . '</p>
				                    </li>
				                    <li class="list-group-item" style="padding: 0.1rem 1rem;">
				                        <small class="text-muted">Gram</small>
				                        <p style="font-family: Roboto Mono, monospace;">' . $row["sale_gram"] . '</p>
				                    </li>
				                    <li class="list-group-item" style="padding: 0.1rem 1rem;">
				                        <small class="text-muted">Volume</small>
				                        <p style="font-family: Roboto Mono, monospace;">' . $row["sale_volume"] . '</p>
				                    </li>
				                    <li class="list-group-item" style="padding: 0.1rem 1rem;">
				                        <small class="text-muted">Density</small>
				                        <p style="font-family: Roboto Mono, monospace;">' . $row["sale_density"] . '</p>
				                    </li>
				                    <li class="list-group-item" style="padding: 0.1rem 1rem;">
				                        <small class="text-muted">Pounds</small>
				                        <p style="font-family: Roboto Mono, monospace;">' . $row["sale_pounds"] . '</p>
				                    </li>
				                    <li class="list-group-item" style="padding: 0.1rem 1rem;">
				                        <small class="text-muted">Carat</small>
				                        <p id="send-amount" style="font-family: Roboto Mono, monospace;">' . $row["sale_carat"] . '</p>
				                    </li>
				                    <li class="list-group-item" style="padding: 0.1rem 1rem;">
				                        <small class="text-muted">Customer</small>
				                        <p id="send-amount">Name: ' . ucwords($row["sale_customer_name"]) . ' | Contact: ' . $row["sale_customer_contact"] . '</p>
				                    </li>
				                    <li class="list-group-item" style="padding: 0.1rem 1rem;">
				                        <small class="text-muted">Note</small>
				                        <p>' . $row["sale_comment"] . '</p>
				                    </li>
				                    <li class="list-group-item" style="padding: 0.1rem 1rem;">
				                        <small class="text-muted">Date</small>
				                        <p>' . pretty_date($row["sca"]) . '</p>
				                    </li>
								</ul>
								' . $option2 . '
								' . $option3 . '
							</div>
						</div>
					</div>
				</div>

				<!-- DELETE TRADE -->
				<div class="modal fade" id="deleteModal_' . $row["sid"] . '" tabindex="-1" aria-labelledby="deleteModalLabel_' . $row["sid"] . '" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
				    <div class="modal-dialog modal-dialog-centered">
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
			                        <a href="'.PROOT.'acc/trades?delete_request='.$row["sale_id"].'" class="btn btn-sm btn-danger"><i class="bi bi-trash me-2"></i>Yes, Confirm delete</a>&nbsp;&nbsp;
			                        <button type="button" class="btn btn-sm btn-dark"data-bs-dismiss="modal">No, cancel</button>
			                    </div>
				            </div>
				        </div>
				    </div>
				</div>

				<!-- HANDLER DETAILS -->
				<div class="modal fade" id="adminModal_' . $row["aid"] . '" tabindex="-1" aria-labelledby="adminModalLabel_' . $row["aid"] . '" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered">
						<div class="modal-content overflow-hidden">
							<div class="modal-header pb-0 border-0">
								<h1 class="modal-title h4" id="adminModalLabel_' . $row["aid"] . '">Handler details</h1>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div class="modal-body p-0 text-center">
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
								<div class="px-6 py-5 bg-body-secondary d-flex justify-content-center">
								<a class="btn btn-sm btn-neutral" href="' . PROOT . 'acc/admins"><i class="bi bi-people me-2"></i>All admins</a>
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
				<td colspan="9">No data found.</td>
			</tr>
		';
	}

	return $output;
}


// get total amount of orders today
function total_amount_today($admin) {
	global $conn;
	$thisDay = date("d");

	$where = '';
	if (!admin_has_permission()) {
		$where = ' sale_by = "'.$admin.'" AND ';
	}

	$thisDaySql = "
		SELECT SUM(sale_total_amount) AS total
		FROM `jspence_sales` 
		WHERE $where
		DAY(createdAt) = '{$thisDay}' 
	    AND sale_status = 0 
	";
	$statement = $conn->prepare($thisDaySql);
	$statement->execute();
	$thisDayrow = $statement->fetchAll();

	return money($thisDayrow[0]['total']);
}

// get total amount of orders in current month
function total_amount_thismonth($admin) {
	global $conn;
	$thisMonth = date("m");

	$where = '';
	if (!admin_has_permission()) {
		$where = ' sale_by = "'.$admin.'" AND ';
	}

	$thisSql = "
		SELECT SUM(sale_total_amount) AS total 
		FROM `jspence_sales` 
		WHERE $where 
		MONTH(createdAt) = '{$thisMonth}' 
	    AND sale_status = 0 
	";
	$statement = $conn->prepare($thisSql);
	$statement->execute();
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
	$today = date("Y-m-d");

	$where = '';
	if (!admin_has_permission()) {
		$where = ' AND sale_by = "'.$admin.'"';
	}

	$sql = "
		SELECT COUNT(sale_id) AS total_number 
		FROM `jspence_sales` 
		WHERE sale_status = ? 
		AND CAST(jspence_sales.createdAt AS date) = ?
		$where 
	";
	$statement = $conn->prepare($sql);
	$statement->execute([0, $today]);
	$row = $statement->fetchAll();
	
	return $row[0]['total_number'];
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
	$output = '';

	$where = '';
	if (!admin_has_permission()) {
		$where = ' WHERE jspence_logs.log_admin = "'.$admin.'" ';
	}

	$sql = "
		SELECT * FROM jspence_logs 
		INNER JOIN jspence_admin 
		ON jspence_admin.admin_id = jspence_logs.log_admin
		$where 
		ORDER BY jspence_logs.createdAt DESC
		LIMIT 8
	";
	$statement = $conn->prepare($sql);
	$statement->execute();
	$rows = $statement->fetchAll();

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

	return $output;
}


// get recent trades
function get_recent_trades($admin) {
	global $conn;
	$output = '';

	$today = date('d');

	$where = '';
	if (!admin_has_permission()) {
		$where = ' sale_by = "'.$admin.'" AND ';
	}

	$sql = "
		SELECT * FROM jspence_sales 
		WHERE $where 
		DAY(createdAt) = ? 
	    AND sale_status = 0 
	";
	$statement = $conn->prepare($sql);
	$statement->execute([$today]);
	$rows = $statement->fetchAll();
	$counts = $statement->rowCount();

	if ($counts > 0) {
		// code...
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
			$output .= '
				<tr>
					<td>
						<div class="d-flex align-items-center">
							<div class="avatar text-primary">
							<i class="fs-4" data-duoicon="book-3"></i>
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
						' . ucwords($row["sale_customer_name"]) . '
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

	return $output;
}


// 
function count_new_delete_requests($conn) {
	 // Get new delete requests
    $requestNumber = $conn->query("SELECT * FROM jspence_sales WHERE sale_delete_request_status = 1")->rowCount();

    if (admin_has_permission()) {
	    return '
	    	<span class="badge badge-sm rounded-pill me-n2 bg-danger-subtle text-danger ms-auto">' . $requestNumber . '</span>
	    ';
    }
	return '';
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