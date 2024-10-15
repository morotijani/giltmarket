<?php 

// LIST AND SEARCH FOR PUSHES

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
	$where = ' AND (push_to = "' . $admin_id . '" OR push_from IN (SELECT push_from FROM jspence_pushes WHERE push_from = "' . $admin_id . '")) AND push_date = "' . $today . '" ';
}

$query = "
	SELECT *, jspence_pushes.id AS pid, jspence_pushes.createdAt AS pca, jspence_pushes.updatedAt AS sua, CAST(jspence_pushes.createdAt AS date) AS pdate 
    FROM jspence_pushes 
	INNER JOIN jspence_admin 
	ON (jspence_admin.admin_id = jspence_pushes.push_to OR jspence_admin.admin_id = jspence_pushes.push_from) 
	WHERE jspence_pushes.push_status = 0 
	$where 
";

$total_push = $conn->query("SELECT * FROM jspence_pushes INNER JOIN jspence_admin ON (jspence_admin.admin_id = jspence_pushes.push_from OR jspence_admin.admin_id = jspence_pushes.push_to) WHERE jspence_pushes.push_status = 0 $where")->rowCount();

$search_query = ((isset($_POST['query'])) ? sanitize($_POST['query']) : '');
$find_query = str_replace(' ', '%', $search_query);
if ($search_query != '') {
	$query .= '
		AND (push_id LIKE "%'.$find_query.'%" 
		OR push_amount LIKE "%'.$find_query.'%" 
		OR jspence_pushes.createdAt = "%'.$find_query.'%" 
		OR admin_fullname LIKE "%'.$find_query.'%") 
		GROUP BY push_id 
	';
} else {
	$query .= 'GROUP BY push_id ORDER BY jspence_pushes.createdAt DESC ';
}

$filter_query = $query . 'LIMIT ' . $start . ', ' . $limit . '';

$total_data = $conn->query("SELECT * FROM jspence_pushes INNER JOIN jspence_admin ON (jspence_admin.admin_id = jspence_pushes.push_to OR jspence_admin.admin_id = jspence_pushes.push_from) WHERE jspence_pushes.push_status = 0 $where GROUP BY push_id")->rowCount();

$statement = $conn->prepare($filter_query);
$statement->execute();
$result = $statement->fetchAll();
$count_filter = $statement->rowCount();

$output = ' 
        <div class="table-responsive mb-7">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Push ID</span></th>
                        <th>Capital ID</th>
                        <th>Amount</th>
                        <th>From</th>
                        <th>To</th>
                        <th></th>
                        <th>Date</th>
						<th></th>
                    </tr>
                </thead>
                <tbody>
';

if ($total_data > 0) {
	$i = 1;
	foreach ($result as $row) {

        $_from = find_admin_with_id($row["push_from"]);
		if ($row['push_to'] == 'coffers') {
			$__to = 'coffers';
		} else {
			$_to = find_admin_with_id($row["push_to"]);
			$__to = $_to['admin_fullname'];
		}
        
        $option = '';
    	if ($row["push_date"] == date("Y-m-d") && $row["push_from"] == $admin_id && ($row['push_on'] == 'dialy' || $row['push_on'] == 'coffers')) {
           $option = '<a href="javascript:;" data-bs-target="#reverseModal_' . $row["pid"] . '" data-bs-toggle="modal" class="badge bg-dark"> Reverse push </a>'; 
        }
		
		$s = '';
		if ($row["push_to"] == $admin_id) {
			$s = '<span class="badge bg-success-subtle text-success">received</span>';
		} else {
			$s = '<span class="badge bg-warning-subtle text-warning">sent</span>';
		}

		$output .= '
            <tr class="' . ((admin_has_permission() && $row["push_date"] == date("Y-m-d")) ? 'table-info' : '') . '">
                <td>' . $i . '</td>
                <td>' . $row["push_id"] . '</td>
                <td>' . $row["push_daily"] . '</td>
                <td>' . money($row["push_amount"]) .'</td>
                <td>' . ucwords($_from['admin_fullname']) . '</a></td>
                <td>' . ucwords($__to) . '</td>
                <td>' . $s . '</td>
                <td>'. pretty_date($row["pca"]) .'</td>
                <td class="text-end">' . $option . '</td>
            </tr>

			<!-- Reverse push -->
			<div class="modal fade" id="reverseModal_' . $row["pid"] . '" tabindex="-1" aria-labelledby="reverseModalLabel_' . $row["pid"] . '" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true" style="backdrop-filter: blur(5px);">
				    <div class="modal-dialog modal-dialog-centered">
				        <div class="modal-content overflow-hidden">
				            <div class="modal-header pb-0 border-0">
				                <h1 class="modal-title h4" id="reverseModalLabel_' . $row["pid"] . '">Reverse push!</h1>
				                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				            </div>
							<div class="modal-body p-0">
								<div class="px-6 py-5 border-bottom">
								<p>
									You are to reverse a push you made of an amount of ' . money($row["push_amount"]) . ' to ' . ucwords($__to)  . '
								</p>
								<br>
								Push ID: 
								<input class="form-control" id="push_id" name="push_id" readonly value="' . $row["push_id"] . '" />
								</div>
								<div class="px-6 py-5 d-flex justify-content-center">
									<a href="' . PROOT . 'auth/push.reverse.php?id=' . $row["push_amount"] . '" class="btn btn-sm btn-warning"><i class="bi bi-trash me-2"></i>Reverse</a>&nbsp;&nbsp;
									<button type="button" class="btn btn-sm btn-dark"data-bs-dismiss="modal">Cancel</button>
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
            <td colspan="9">
               <div class="alert alert-info"> No data found!</div>
            </td>
		</tr>
	';
}

$output .= '
			</tbody>
        </table>
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
