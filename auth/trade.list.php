<?php 

// LIST AND SEARCH FOR TRADES

require_once ("../db_connection/conn.php");


$limit = 10;
$page = 1;

if ($_POST['page'] > 1) {
	$start = (($_POST['page'] - 1) * $limit);
	$page = $_POST['page'];
} else {
	$start = 0;
}

$where = '';
if (!admin_has_permission()) {
	$where = ' AND sale_by = "'.$admin_data[0]["admin_id"].'" ';
}
$query = "
	SELECT *, jspence_sales.id AS sid, jspence_sales.createdAt AS sca, jspence_sales.updatedAt AS sua, jspence_admin.id AS aid FROM jspence_sales 
	INNER JOIN jspence_admin 
	ON jspence_admin.admin_id = jspence_sales.sale_by 
	WHERE sale_status = 0 
	$where 
";
$search_query = ((isset($_POST['query'])) ? sanitize($_POST['query']) : '');
$find_query = str_replace(' ', '%', $search_query);
if ($search_query != '') {
	$query .= '
		AND (sale_id LIKE "%'.$find_query.'%" 
		OR sale_customer_name LIKE "%'.$find_query.'%" 
		OR sale_customer_contact LIKE "%'.$find_query.'%" 
		OR admin_fullname LIKE "%'.$find_query.'%") 
	';
} else {
	$query .= 'ORDER BY createdAt DESC ';
}

$filter_query = $query . 'LIMIT ' . $start . ', ' . $limit . '';

$total_data = $conn->query("SELECT * FROM jspence_sales INNER JOIN jspence_admin ON jspence_admin.admin_id = jspence_sales.sale_by WHERE sale_status = 0 $where")->rowCount();

$statement = $conn->prepare($filter_query);
$statement->execute();
$result = $statement->fetchAll();
$count_filter = $statement->rowCount();

$archive = '';
if (admin_has_permission()) {
	$archive = '
		<li class="nav-item">
    		<a href="<?= PROOT; ?>acc/trades.archive" class="nav-link">Archive</a>
		</li>
	';
}

$output = ' 
	<ul class="nav nav-tabs nav-tabs-flush gap-8 overflow-x border-0 mt-1">
            <li class="nav-item">
                <a href="<?= PROOT; ?>acc/trades" class="nav-link active">All data (' . $total_data . ')</a>
            </li>
            <li class="nav-item">
                <a href="'.PROOT.'acc/trades.delete.requests" class="nav-link">Delete request ' . count_new_delete_requests($conn) . '</a>
            </li>
           ' . $archive . '
        </ul>

    <div class="table-responsive">
        <table class="table table-hover table-striped table-nowrap">
            <thead class="table-light">
                <tr>
                   <th>#</th>
				   <td></th>
                    ' .  ((admin_has_permission()) ? '<th scope="col">Handler</th>' : '') . '
                    <th scope="col">Customer</th>
                    <th scope="col">Gram</th>
                    <th scope="col">Volume</th>
                    <th scope="col">Price</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Date</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
';

if ($total_data > 0) {
	$i = 1;
	foreach ($result as $row) {

		$arrayOutput = array('reference' => $row['sale_id'], 'customername' => $row['sale_customer_name'], 'date' => $row['sca'], 'gram' => $row['sale_gram'], 'volume' => $row['sale_volume'], 'density' => $row['sale_density'], 'pounds' => $row['sale_pounds'], 'carat' => $row['sale_carat'], 'total_amount' => $row['sale_total_amount'], 'current_price' => $row['sale_price'], 'by' => $row['sale_by'], 'message' => '',);
		
		$outputData = json_encode($arrayOutput);
		$option1 = '';
		// $option1 = '&nbsp;<a href="javascript:;" onClick="MyWindow=window.open('.$outputData.',\'MyWindow\',\'width=600,height=300\'); return false;" title="Print receipt" class="btn btn-sm btn-square btn-neutral w-rem-6 h-rem-6">
        //                 <i class="bi bi-receipt"></i>';
        $option2 =  '
			<div class="p-2"></div>
			<div class="px-6 py-5 bg-body-secondary d-flex justify-content-center">
				<!-- <button class="btn btn-sm btn-dark"><i class="bi bi-receipt me-2"></i>Print receipt</button>&nbsp --><a href="#deleteModal_'. $row["sid"] . '" data-bs-toggle="modal" class="btn btn-sm btn-neutral"><i class="bi bi-trash3 me-2"></i>Delete</a>
			</div>
        ';
        $option3 = '';
		if ($row['sale_status'] == 1) {
			// code...
			$option1 = '';
			$option2 = '';
			if ($permission == 'admin,salesperson') {
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
					<td>
						<div class="icon icon-shape rounded-circle icon-sm flex-none w-rem-10 h-rem-10 text-sm bg-'.(($row["sale_type"] == 'out') ? 'danger' : 'success').' bg-opacity-25 text-'.(($row["sale_type"] == 'out') ? 'danger' : 'success').'"><i class="bi bi-'.(($row["sale_type"] == 'out') ? 'arrow-up-right-circle-fill' : 'arrow-down-left-circle-fill').'"></i></div>
					</td>
	                ' . (admin_has_permission() ? ' <td><a href="javascript:;" data-bs-target="#adminModal_' . $row["aid"] . '" data-bs-toggle="modal"><span class="d-block text-heading fw-bold">' . ucwords($row["admin_fullname"]) . '</span></a></td> ' : '') . '
	                <td class="text-xs">
						' . strtoupper($row["sale_customer_name"]) . ' <i class="bi bi-arrow-right mx-2"></i> ' . $row["sale_customer_contact"] . '
					</td>
	                <td>' . $row["sale_gram"] . '</td>
	                <td>' . $row["sale_volume"] . '</td>
	                <td>' . money($row["sale_price"]) . '</td>
	                <td>' . money($row["sale_total_amount"]) . '</td>
	                <td>' . pretty_date($row["sca"]) . '</td>
	                <td class="text-end">
	                    <button type="button" class="btn btn-sm btn-square btn-neutral w-rem-6 h-rem-6" title="More" data-bs-target="#saleModal_' . $row["sid"] . '" data-bs-toggle="modal">
	                        <i class="bi bi-three-dots"></i>
	                    </button> '.$option1.'
	                    </a>
	                </td>
	            </tr>

	            <!-- Trade details -->
	            <div class="modal fade" id="saleModal_' . $row["sid"] . '" tabindex="-1" aria-labelledby="saleModalLabel_' . $row["sid"] . '" aria-hidden="true" style="backdrop-filter: blur(5px);">
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
				                        <p>' . money($row["sale_total_amount"]) . '</p>
				                    </li>
				                    <li class="list-group-item" style="padding: 0.1rem 1rem;">
				                        <small class="text-muted">Price,</small>
				                        <p>' . money($row["sale_price"]) . '</p>
				                    </li>
				                    <li class="list-group-item" style="padding: 0.1rem 1rem;">
				                        <small class="text-muted">Gram</small>
				                        <p>' . $row["sale_gram"] . '</p>
				                    </li>
				                    <li class="list-group-item" style="padding: 0.1rem 1rem;">
				                        <small class="text-muted">Volume</small>
				                        <p>' . $row["sale_volume"] . '</p>
				                    </li>
				                    <li class="list-group-item" style="padding: 0.1rem 1rem;">
				                        <small class="text-muted">Density</small>
				                        <p>' . $row["sale_density"] . '</p>
				                    </li>
				                    <li class="list-group-item" style="padding: 0.1rem 1rem;">
				                        <small class="text-muted">Pounds</small>
				                        <p>' . $row["sale_pounds"] . '</p>
				                    </li>
				                    <li class="list-group-item" style="padding: 0.1rem 1rem;">
				                        <small class="text-muted">Carat</small>
				                        <p id="send-amount">' . $row["sale_carat"] . '</p>
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
				<div class="modal fade" id="deleteModal_' . $row["sid"] . '" tabindex="-1" aria-labelledby="deleteModalLabel_' . $row["sid"] . '" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true" style="backdrop-filter: blur(5px);">
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
				<div class="modal fade" id="adminModal_' . $row["aid"] . '" tabindex="-1" aria-labelledby="adminModalLabel_' . $row["aid"] . '" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true" style="backdrop-filter: blur(5px);">
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
	$output .= '
		<tr class="text-warning">
			<td colspan="5">No data found!</td>
		</tr>
	';
}

$output .= '
			</tbody>
        </table>
    </div>
    <div class="py-4 px-6">
        <div class="row align-items-center justify-content-between">
            <div class="col-md-6 d-none d-md-block">
                <span class="text-muted text-sm">Showing ' . $count_filter . ' items out of ' . $total_data . ' results found</span>
            </div>
';

if ($total_data > 0) {
	$output .= '
		<div class="col-md-auto">
            <nav aria-label="Page navigation example">
                <ul class="pagination pagination-spaced gap-1">
	';

	$total_links = ceil($total_data / $limit);

	$previous_link = '';
	$next_link = '';
	$page_link = '';

	if ($total_links > 4) {
		if ($page < 5) {
			for ($count = 1; $count <= 5; $count++) {
				$page_array[] = $count;
			}
			$page_array[] = '...';
			$page_array[] = $total_links;
		} else {
			$end_limit = $total_links - 5;
			if ($page > $end_limit) {
				$page_array[] = 1;
				$page_array[] = '...';

				for ($count = $end_limit; $count <= $total_links; $count++) {
					$page_array[] = $count;
				}
			} else {
				$page_array[] = 1;
				$page_array[] = '...';
				for ($count = $page - 1; $count <= $page + 1; $count++) {
					$page_array[] = $count;
				}
				$page_array[] = '...';
				$page_array[] = $total_links;
			}
		}
	} else {
		for ($count = 1; $count <= $total_links; $count++) {
			$page_array[] = $count;
		}
	}

	for ($count = 0; $count < count($page_array); $count++) {
		if ($page == $page_array[$count]) {
			$page_link .= '
				<li class="page-item active">
                    <a class="page-link" href="javascript:;">'.$page_array[$count].'</a>
                </li>
			';

			$previous_id = $page_array[$count] - 1;
			if ($previous_id > 0) {
				$previous_link = '
					<li class="page-item">
	                    <a class="page-link" href="javascript:;" data-page_number="'.$previous_id.'">
	                        <i class="bi bi-chevron-left"></i>
	                    </a>
	                </li>
				';
			} else {
				$previous_link = '
					<li class="page-item disabled">
	                    <a class="page-link" href="javascript:;">
	                        <i class="bi bi-chevron-left"></i>
	                    </a>
	                </li>
				';
			}

			$next_id = $page_array[$count] + 1;
			if ($next_id >= $total_links) {
				$next_link = '
					<li class="page-item disabled">
                        <a class="page-link" href="javascript:;">
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </li>
				';
			} else {
				$next_link = '
					<li class="page-item">
                        <a class="page-link" href="javascript:;" data-page_number="'.$next_id.'">
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </li>
				';
			}

		} else {
			
			if ($page_array[$count] == '...') {
				$page_link .= '
					<li class="page-item disabled">
						<a class="page-link" href="javascript:;">...</a>
					</li>
				';
			} else {
				$page_link .= '
					<li class="page-item">
						<a class="page-link page-link-go" href="javascript:;" data-page_number="'.$page_array[$count].'">'.$page_array[$count].'</a>
					</li>
				';
			}
		}

	}

	$output .= $previous_link. $page_link . $next_link;
}

echo $output . '</ul>
                    </nav>
                </div>';