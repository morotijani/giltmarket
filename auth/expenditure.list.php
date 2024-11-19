<?php 

// LIST AND SEARCH FOR TRADES

require_once ("../db_connection/conn.php");

$today = date("Y-m-d");
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
	$where = ' AND sale_by = "'.$admin_data["admin_id"].'" AND CAST(jspence_sales.createdAt AS date) = "' . $today . '" ';
}
$query = "
	SELECT *, jspence_sales.id AS eid, jspence_sales.createdAt AS eca, jspence_sales.updatedAt AS sua, jspence_admin.id AS aid, CAST(jspence_sales.createdAt AS date) AS edate 
    FROM jspence_sales 
	INNER JOIN jspence_admin 
	ON jspence_admin.admin_id = jspence_sales.sale_by 
	WHERE jspence_sales.sale_status = 0 
    AND jspence_sales.sale_type = 'exp'
	$where 
";
$search_query = ((isset($_POST['query'])) ? sanitize($_POST['query']) : '');
$find_query = str_replace(' ', '%', $search_query);
if ($search_query != '') {
	$query .= '
		AND (sale_id LIKE "%'.$find_query.'%" 
		OR sale_total_amount LIKE "%'.$find_query.'%" 
		OR sale_comment LIKE "%'.$find_query.'%" 
		OR jspence_sales.createdAt = "%'.$find_query.'%" 
		OR admin_fullname LIKE "%'.$find_query.'%") 
	';
} else {
	$query .= 'ORDER BY jspence_sales.createdAt DESC ';
}

$filter_query = $query . 'LIMIT ' . $start . ', ' . $limit . '';

$total_data = $conn->query("SELECT * FROM jspence_sales INNER JOIN jspence_admin ON jspence_admin.admin_id = jspence_sales.sale_by WHERE jspence_sales.sale_status = 0 AND jspence_sales.sale_type = 'exp' $where")->rowCount();

$statement = $conn->prepare($filter_query);
$statement->execute();
$result = $statement->fetchAll();
$count_filter = $statement->rowCount();

$output = '
    <div class="card mb-6">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Reference</span></th>
                        <th>What for</th>
                        <th>Amount</th>
                        <th>Date</th>
                        ' . (!admin_has_permission() ? '<th></th>' : '') . '
                    </tr>
                </thead>
                <tbody>
';

if ($total_data > 0) {
	$i = 1;
	foreach ($result as $row) {
        $option = '';
        if (!admin_has_permission() && $row["edate"] == date("Y-m-d")) {
           $option = '
                <td class="text-end">
                    <a href="'. PROOT .'account/expenditure?edit=' . $row["sale_id"] . '" class="btn btn-sm btn-light">Edit </a>
                    <a href="javascript:;" data-bs-target="#deleteModal_' . $row["eid"] . '" data-bs-toggle="modal" class="btn btn-sm btn-dark">Delete </a>
                </td>
           '; 
        }

		$output .= '
            <tr>
                <td>' . $i . '</td>
                <td class="p-1">
                    <div class="d-flex align-items-center gap-3 ps-1">
                        <div class="icon icon-shape w-rem-10 h-rem-10 rounded-circle text-sm bg-primary bg-opacity-25 text-tertiary">
                            <i class="bi bi-send-fill"></i>
                        </div>
                        <div>
                            <span class="d-block text-heading fw-bold">'. $row["sale_id"] .'</span>
                                ' . (admin_has_permission() ? ' <span class="text-xs text-muted">by <a href="javascript:;" data-bs-target="#adminModal_' . $row["aid"] . '" data-bs-toggle="modal">' . ucwords($row["admin_fullname"]) . '</a></span> ' : '') . '
                        </div>
                    </div>
                </td>
                <td>'. $row["sale_comment"] .'</a></td>
                <td>'. money($row["sale_total_amount"]) .'</td>
                <td>'. pretty_date($row["eca"]) .'</td>
                ' . $option . '
            </tr>

            <!-- DELETE Expenditure -->
            <div class="modal fade" id="deleteModal_' . $row["eid"] . '" tabindex="-1" aria-labelledby="deleteModalLabel_' . $row["eid"] . '" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
                <div class="modal-dialog modal-sm modal-dialog-centered">
                    <div class="modal-content overflow-hidden">
                        <div class="modal-header pb-0 border-0">
                            <h1 class="modal-title h4" id="deleteModalLabel_' . $row["eid"] . '">Delete expenditure!</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-0">
                            <div class="px-6 py-5 border-bottom">
                                <p>
                                    <i>'.$row["sale_comment"].', with an amount of <span style="font-family: Roboto Mono, monospace;">'.money($row["sale_total_amount"]).'</span></i> 
                                    <br><br>
                                    Are you sure you want to proceed to this action.
                                </p>
                            </div>
                            <div class="px-6 py-5 bg-body-secondary d-flex justify-content-center">
                                <a href="' . PROOT . 'account/expenditure?delete=' . $row["sale_id"] . '" class="btn btn-sm btn-danger"><i class="bi bi-trash me-2"></i>Yes, Confirm delete</a>&nbsp;&nbsp;
                                <button type="button" class="btn btn-sm btn-dark"data-bs-dismiss="modal">No, cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- HANDLER DETAILS -->
            <div class="modal fade" id="adminModal_' . $row["aid"] . '" tabindex="-1" aria-labelledby="adminModalLabel_' . $row["aid"] . '" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true" style="backdrop-filter: blur(5px);">>
                <div class="modal-dialog modal-dialog-centered">
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
                            </ul>
                            <div class="px-6 py-5 d-flex justify-content-center">
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
	$output .= '
		<tr>
            <td colspan="6">
               <div class="alert alert-info"> No data found!</div>
            </td>
		</tr>
	';
}

$output .= '
                </tbody>
            </table>
        </div>
    </div>
	<div class="row align-items-center">
        <div class="col">
            <!-- Text -->
            <p class="text-body-secondary mb-0">Showing ' . $count_filter . ' items out of ' . $total_data . ' results found</p>
        </div>
        <div class="col-auto">
';

if ($total_data > 0) {
	$output .= '
		<nav aria-label="Page navigation example">
            <ul class="pagination mb-0">
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
	                    <a class="page-link" href="javascript:;" data-page_number="'.$previous_id.'" aria-label="Previous">
	                        <span aria-hidden="true">&laquo;</span>
	                    </a>
	                </li>
				';
			} else {
				$previous_link = '
					<li class="page-item disabled">
	                    <a class="page-link" href="javascript:;" aria-label="Previous">
	                        <span aria-hidden="true">&laquo;</span>
	                    </a>
	                </li>
				';
			}

			$next_id = $page_array[$count] + 1;
			if ($next_id >= $total_links) {
				$next_link = '
					<li class="page-item disabled">
                        <a class="page-link" href="javascript:;" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
				';
			} else {
				$next_link = '
					<li class="page-item">
                        <a class="page-link" href="javascript:;" data-page_number="'.$next_id.' aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
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

echo $output . '	
                    </ul>
				</nav>
			</div>
		</div>
    ';
