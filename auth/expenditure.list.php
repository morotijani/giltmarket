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
	$where = ' AND expenditure_by = "'.$admin_data[0]["admin_id"].'" ';
}
$query = "
	SELECT *, jspence_expenditures.id AS eid, jspence_expenditures.createdAt AS eca, jspence_expenditures.updatedAt AS sua, jspence_admin.id AS aid 
    FROM jspence_expenditures 
	INNER JOIN jspence_admin 
	ON jspence_admin.admin_id = jspence_expenditures.expenditure_by 
	WHERE jspence_expenditures.status = 0 
	$where 
";
$search_query = ((isset($_POST['query'])) ? sanitize($_POST['query']) : '');
$find_query = str_replace(' ', '%', $search_query);
if ($search_query != '') {
	$query .= '
		AND (expenditure_id LIKE "%'.$find_query.'%" 
		OR expenditure_amount LIKE "%'.$find_query.'%" 
		OR expenditure_what_for LIKE "%'.$find_query.'%" 
		OR jspence_expenditures.createdAt = "%'.$find_query.'%" 
		OR admin_fullname LIKE "%'.$find_query.'%") 
	';
} else {
	$query .= 'ORDER BY jspence_expenditures.createdAt DESC ';
}

$filter_query = $query . 'LIMIT ' . $start . ', ' . $limit . '';

$total_data = $conn->query("SELECT * FROM jspence_expenditures INNER JOIN jspence_admin ON jspence_admin.admin_id = jspence_expenditures.expenditure_by WHERE jspence_expenditures.status = 0 $where")->rowCount();

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
                    <th scope="col">Reference</span></th>
                    <th scope="col">What for</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Date</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
';

if ($total_data > 0) {
	$i = 1;
	foreach ($result as $row) {
		$output .= '
            <tr>
                <td>' . $i . '</td>
                <td class="p-1">
                    <div class="d-flex align-items-center gap-3 ps-1">
                        <div class="icon icon-shape w-rem-10 h-rem-10 rounded-circle text-sm bg-primary bg-opacity-25 text-tertiary">
                            <i class="bi bi-file-fill"></i>
                        </div>
                        <div>
                            <span class="d-block text-heading fw-bold">'. $row["expenditure_id"] .'</span>
                                ' . (admin_has_permission() ? ' <span class="text-xs text-muted">by <a href="javascript:;" data-bs-target="#adminModal_' . $row["aid"] . '" data-bs-toggle="modal">' . ucwords($row["admin_fullname"]) . '</a></span> ' : '') . '
                        </div>
                    </div>
                </td>
                <td>'. $row["expenditure_what_for"] .'</a></td>
                <td>'. money($row["expenditure_amount"]) .'</td>
                <td>'. pretty_date($row["eca"]) .'</td>
                <td class="text-end">
                    <a href="'. PROOT .'acc/expenditure?edit=' . $row["expenditure_id"] .'" class="badge bg-body-secondary text-xs text-success">Edit </a>
                    <a href="javascript:;" data-bs-target="#deleteModal_' . $row["eid"] . '" data-bs-toggle="modal" class="badge bg-body-secondary text-xs text-danger">Delete </a>
                </td>
            </tr>

            <!-- DELETE TRADE -->
            <div class="modal fade" id="deleteModal_' . $row["eid"] . '" tabindex="-1" aria-labelledby="deleteModalLabel_' . $row["eid"] . '" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content overflow-hidden">
                        <div class="modal-header pb-0 border-0">
                            <h1 class="modal-title h4" id="deleteModalLabel_' . $row["eid"] . '">Delete expenditure!</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-0">
                            <div class="px-6 py-5 border-bottom">
                                <p>
                                    <i>'.$row["expenditure_what_for"].', with an amount of '.money($row["expenditure_amount"]).'</i> 
                                    <br><br>
                                    Are you sure you want to proceed to this action.
                                </p>
                            </div>
                            <div class="px-6 py-5 bg-body-secondary d-flex justify-content-center">
                                <a href="' . PROOT . 'acc/expenditure?delete=' . $row["expenditure_id"] . '" class="btn btn-sm btn-danger"><i class="bi bi-trash me-2"></i>Yes, Confirm delete</a>&nbsp;&nbsp;
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
	$output .= '
		<tr class="text-warning">
			<td colspan="6">No data found!</td>
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