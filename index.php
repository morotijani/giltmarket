<?php 
    require_once ("db_connection/conn.php");

    include ("includes/header.inc.php");
    include ("includes/aside.inc.php");
    include ("includes/left.nav.inc.php");
    include ("includes/top.nav.inc.php");

    if (!admin_is_logged_in()) {
		admn_login_redirect();
	}
	$today = date("F j, Y, g:i a"); 

	// insert daily capital given
	if (isset($_POST['today_given'])) {
		if (!empty($_POST['today_given']) || $_POST['today_given'] != '') {

			$given = sanitize($_POST['today_given']);
			$today_date = sanitize($_POST['today_date']);

			$today = date("Y-m-d");
			$daily_id = guidv4();
			$daily_by = $admin_data[0]['admin_id'];

			if ($today_date == $today) {
				$data = [$daily_id, $given, $today, $daily_by];
				$sql = "
					INSERT INTO jspence_daily (daily_id, daily_capital, daily_date, daily_by) 
					VALUES (?, ?, ?, ?)
				";
				$message = "today " . $today . " capital entered of an amount of " . money($given);

				if (is_capital_given()) {
					$g = (float)($given - _capital()['today_capital']);
					$b = ((admin_has_permission('salesperson') && _capital()['today_balance'] == '0.00') ? '0.00' : (float)($g + _capital()['today_balance']));

					if (admin_has_permission('supervisor')) {
						$b = _capital()['today_balance'];
					}

					$sql = "
						UPDATE jspence_daily 
						SET daily_capital = ?, 
						daily_balance = " . $b . "
						WHERE daily_date = ? 
						AND daily_by = ?
					";
					// remove the first element and only remove one element
					$data = array_splice($data, 1, 3);
					$message = "today " . $today . " capital updated of an amount of " . money($given) . ', added amount ' . money($g);
				}
				$statement = $conn->prepare($sql);
				$result = $statement->execute($data);
				if ($result) {
					
					add_to_log($message, $admin_id);
	
					$_SESSION['flash_success'] = 'Today capital saved successfully!';
					redirect(PROOT);
				} else {
					echo js_alert('Something went wrong, please refresh and try agin!');
					redirect(PROOT);
				}
			}
		}
	}

	// statisticall calculations
	$thisYr = date("Y");
	$lastYr = $thisYr - 1;

	$where = '';
	if (!admin_has_permission()) {
		$where = ' AND sale_by = "'.$admin_data['admin_id'].'"';
	}

	$thisYrQ = "
		SELECT sale_total_amount, createdAt 
		FROM jspence_sales 
		WHERE YEAR(createdAt) = '{$thisYr}' 
		AND sale_status = 0 
		$where
	";
	$statement = $conn->prepare($thisYrQ);
	$statement->execute();
	$thisYr_result = $statement->fetchAll();
	

	$lastYrQ = "
		SELECT sale_total_amount, createdAt 
		FROM jspence_sales 
		WHERE YEAR(createdAt) = '{$lastYr}' 
		AND sale_status = 0 
		$where
	";
	$statement = $conn->prepare($lastYrQ);
	$statement->execute();
	$lastYr_result = $statement->fetchAll();

	$current = array();
	$last = array();

	$currentTotal = 0;
	$lastTotal = 0;

	foreach ($thisYr_result as $thisYr_row) {
		$month = date("m", strtotime($thisYr_row['createdAt']));
		if (!array_key_exists((int)$month, $current)) {
			$current[(int)$month] = $thisYr_row['sale_total_amount'];
		} else {
			$current[(int)$month] += $thisYr_row['sale_total_amount'];
		}
		$currentTotal += $thisYr_row['sale_total_amount'];
	}

	foreach ($lastYr_result as $lastYr_row) {
		$month = date("m", strtotime($lastYr_row['createdAt']));
		if (!array_key_exists((int)$month, $last)) {
			$last[(int)$month] = $lastYr_row['sale_total_amount'];
		} else {
			$last[(int)$month] += $lastYr_row['sale_total_amount'];
		}
		$currentTotal += $lastYr_row['sale_total_amount'];
	}

?>

 	<!-- Content -->
 	<div class="container-lg">
        <!-- Page content -->
        <div class="row align-items-center">
          	<div class="col-12 col-md-auto order-md-1 d-flex align-items-center justify-content-center mb-4 mb-md-0">
            	<div class="avatar text-warning me-2">
              		<i class="fs-4" data-duoicon="world"></i>
            	</div>
				Ghana, GH –&nbsp;<time datetime="20:00" id="time_span"></time>
			</div>
			<div class="col-12 col-md order-md-0 text-center text-md-start">
				<h1>Hello, <?= $admin_data['first']; ?></h1>
				<p class="fs-lg text-body-secondary mb-0">Here's a summary of your account activity for this day.</p>
			</div>
		</div>
		<!-- <span class="d-flex align-items-center mt-4" style="cursor: pointer">
			<div class="avatar text-success me-2">
				<i class="fs-4" data-duoicon="bank"></i>
			</div>
			Trade –&nbsp;<time datetime="20:00">0.00</time>
		</span> -->

        <!-- Divider -->
        <hr class="my-8" />

		<!-- Stats -->
		<div class="row mb-8">
        	<div class="col-12 col-md-6 col-xxl-3 mb-4 mb-xxl-0">
            	<div class="card bg-body-tertiary border-transparent">
              		<div class="card-body">
                		<div class="row align-items-center">
							<div class="col">
								<!-- Heading -->
								<h4 class="fs-base fw-normal text-body-secondary mb-1"><?= ((admin_has_permission()) ? 'Today' : 'Capital'); ?></h4>

								<!-- Text -->
								<div class="fs-5 fw-semibold"><?= ((admin_has_permission()) ? total_amount_today($admin_data['admin_id']) : money(_capital()['today_capital'])); ?></div>
							</div>
							<div class="col-auto">
								<!-- Avatar -->
								<div class="avatar avatar-lg bg-body text-warning" data-bs-target="<?= ((admin_has_permission()) ? '' : '#buyModal'); ?>" data-bs-toggle="modal" style="cursor: pointer;">
									<i class="fs-4" data-duoicon="credit-card"></i>
								</div>
							</div>
							</div>
						</div>
            		</div>
          		</div>
          		<div class="col-12 col-md-6 col-xxl-3 mb-4 mb-xxl-0">
					<div class="card bg-body-tertiary border-transparent">
						<div class="card-body">
							<div class="row align-items-center">
							<div class="col">
								<!-- Heading -->
								<?php if (admin_has_permission()) : ?>
									<h4 class="fs-base fw-normal text-body-secondary mb-1"> <?= date("F"); ?> </h4>
								<?php else: ?>
									<h4 class="fs-base fw-normal text-body-secondary mb-1"><?= ((admin_has_permission('supervisor')) ? 'Sold' : 'Balance'); ?></h4>
								<?php endif; ?>

								<!-- Text -->
								<div class="fs-5 fw-semibold"><?= ((admin_has_permission()) ? total_amount_thismonth($admin_data['admin_id']) : money(_capital()['today_balance'])); ?></div>
							</div>
							<div class="col-auto">
								<!-- Avatar -->
								<div class="avatar avatar-lg bg-body text-warning">
								<i class="fs-4" data-duoicon="clock"></i>
								</div>
							</div>
							</div>
						</div>
					</div>
          		</div>
				<div class="col-12 col-md-6 col-xxl-3 mb-4 mb-md-0">
					<div class="card bg-body-tertiary border-transparent">
						<div class="card-body">
							<div class="row align-items-center">
								<?php if (admin_has_permission('salesperson')) : ?>
								<div class="col">
									<!-- Heading -->
									<h4 class="fs-base fw-normal text-body-secondary mb-1">Number of requests</h4>

									<!-- Text -->
									<div class="fs-5 fw-semibold">19</div>
								</div>
								<div class="col-auto">
									<!-- Avatar -->
									<div class="avatar avatar-lg bg-body text-warning">
									<i class="fs-4" data-duoicon="bell"></i>
									</div>
								</div>
								<?php else : ?>
									<div class="col">
										<!-- Heading -->
										<h4 class="fs-base fw-normal text-body-secondary mb-1">Earned</h4>

										<!-- Text -->
										<div class="fs-5 fw-semibold"><?= _gained_calculation(_capital()['today_balance'], _capital()['today_capital']); ?></div>
									</div>
									<div class="col-auto">
										<!-- Avatar -->
										<div class="avatar avatar-lg bg-body text-warning">
										<i class="fs-4" data-duoicon="bell"></i>
										</div>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
				<div class="col-12 col-md-6 col-xxl-3">
					<div class="card bg-body-tertiary border-transparent">
						<div class="card-body">
							<div class="row align-items-center">
								<div class="col">
									<!-- Heading -->
									<h4 class="fs-base fw-normal text-body-secondary mb-1">Trades</h4>

									<!-- Text -->
									<div class="fs-5 fw-semibold"><?= count_today_orders($admin_data['admin_id']); ?></div>
								</div>
								<div class="col-auto">
									<!-- Avatar -->
									<div class="avatar avatar-lg bg-body text-warning">
										<i class="fs-4" data-duoicon="clipboard"></i>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

        <div class="row">
			
          	<div class="col-12 col-xxl-8">
			  <?php if (admin_has_permission()) : ?>
				<!-- Performance -->
				<div class="card mb-6">
					<div class="card-header">
						<div class="row align-items-center">
							<div class="col">
								<h3 class="fs-6 mb-0">Performance</h3>
							</div>
							<div class="col-auto my-n3 me-n3">
								<select
								class="form-select"
								id="performanceChartSelect"
								data-choices='{ "searchEnabled": false, "choices": [{ "value": "week", "label": "Week" }, { "value": "month", "label": "Month" }]}'
								></select>
							</div>
						</div>
					</div>
					<div class="card-body">
						<div class="chart">
							<!-- <canvas class="chart-canvas" id="performanceChart"></canvas> -->
							<canvas class="chart-canvas" id="performanceChart"></canvas>
							<!-- <canvas class="my-4 w-100" id="myChart" width="900" height="400"></canvas> -->
						</div>
					</div>
				</div>

				<div class="card mb-6">
					<div class="card-header">
						<div class="row align-items-center">
							<div class="col">
								<h3 class="fs-6 mb-0">Accumulated trades by months and years</h3>
							</div>
							<div class="col-auto my-n3 me-n3">
								<a class="btn btn-link" href="<?= PROOT; ?>account/analytics">
								Analytics
								<span class="material-symbols-outlined">arrow_right_alt</span>
								</a>
							</div>
						</div>
					</div>
					<div class="card-body py-3">
						<div class="table-responsive">
							<table class="table table-flush table-hover align-middle mb-0">
								<tbody>
									<?php for ($i = 1; $i <= 12; $i++):
										$dt = dateTime::createFromFormat('!m',$i);
									?>
										<tr>
											<td <?= (date('m') == $i) ? ' class="bg-danger-subtle"' : ''; ?>><?= $dt->format("F"); ?></td>
											<td <?= (date('m') == $i) ? ' class="bg-danger-subtle"' : ''; ?>><?= ((array_key_exists($i, $last)) ? money($last[$i]) : money(0)); ?></td>
											<td <?= (date('m') == $i) ? ' class="bg-danger-subtle"' : ''; ?>><?=  ((array_key_exists($i, $current)) ? money($current[$i]) : money(0)); ?></td>
										</tr>
									<?php endfor; ?>
									<tr>
										<td class="bg-success-subtle">Total</td>
										<td class="bg-success-subtle"><?= money($lastTotal); ?></td>
										<td class="bg-success-subtle"><?= money($currentTotal); ?></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>

			<?php endif; ?>

            <!-- Trades -->
			<div class="card mb-6 mb-xxl-0">
				<div class="card-header">
					<div class="row align-items-center">
						<div class="col">
							<h3 class="fs-6 mb-0">Trades</h3>
						</div>
						<div class="col-auto my-n3 me-n3">
							<a class="btn btn-link" href="#!">
							Browse all
							<span class="material-symbols-outlined">arrow_right_alt</span>
							</a>
						</div>
					</div>
				</div>
				<div class="card-body py-3">
					<div class="table-responsive">
						<table class="table table-flush align-middle mb-0">
							<tbody>
								<?= get_recent_trades($admin_data['admin_id']); ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
        <div class="col-12 col-xxl-4">

            <!-- Goals -->
            <div class="card mb-6">
              	<div class="card-header">
					<div class="row align-items-center">
						<div class="col">
							<h3 class="fs-6 mb-0">Pushes</h3>
						</div>
						<div class="col-auto my-n3 me-n3">
							<a class="btn btn-link" href="javascript:;" data-bs-target="<?= ((!admin_has_permission()) ? '#modalCapital' : ''); ?>" data-bs-toggle="modal">
							Make a push
							<span class="material-symbols-outlined">send_money</span>
							</a>
						</div>
					</div>
              	</div>
              	<div class="card-body py-3">
                	<div class="list-group list-group-flush">
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
									<h6 class="fs-base fw-normal mb-1">A push amount recieved</h6>
									<span class="fs-sm text-body-secondary">0.00</span>
								</div>
								<div class="col-auto">
									<time class="text-body-secondary" datetime="01/01/2025">Jan 01</time>
								</div>
							</div>
						</div>
                	</div>
              	</div>
            </div>

            <!-- Activity -->
            <div class="card">
				<div class="card-header">
					<h3 class="fs-6 mb-0">Recent activity</h3>
				</div>
				<div class="card-body">
					<ul class="activity">
						<?= get_logs($admin_data['admin_id']); ?>
					</ul>
				</div>
			</div>

		</div>
	</div>
</div>

<!-- Push todays capital -->
<?php if ($admin_data['admin_permissions'] == 'supervisor'): ?>
	<div class="modal fade" id="modalCapital" tabindex="-1" aria-labelledby="modalCapital" aria-hidden="true" style="backdrop-filter: blur(5px);">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content shadow-3">
				<div class="modal-header justify-content-start">
					<div class="icon icon-shape rounded-3 bg-primary-subtle text-primary text-lg me-4">
						<i class="bi bi-currency-exchange"></i>
					</div>
					<div>
						<h5 class="mb-1">Push Capital</h5>
						<small class="d-block text-xs text-muted">You are to give todays capital before you can start trade.</small>
					</div>
				</div>
				<form action="" method="POST" id="capitalForm">
					<div class="modal-body">
						<div class="mb-4">
							<label class="form-label">Today's Date</label> 
							<input class="form-control" name="today_date" id="today_date" type="date" value="<?php echo date('Y-m-d'); ?>">
						</div>
						<div class="mb-4">
							<div class="form-check mb-2">
								<input class="form-check-input for_class" type="radio" name="push_for" id="flexRadioDefault1" value="self">
								<label class="form-check-label" for="flexRadioDefault1">
									For Self
								</label>
							</div>
							<div class="form-check mb-2">
								<input class="form-check-input for_class" type="radio" name="push_for" id="flexRadioDefault2" value="saleperson">
								<label class="form-check-label" for="flexRadioDefault2">
									For Saleperson
								</label>
							</div>
						</div>
						<div class="mb-3 d-none" id="sf">
							<select class="form-select bg-body" name="push_to" id="teamMembers"
							data-choices='{"searchEnabled": false, "choices": [
								{
									"value": "",
									"label": "Select a sales person",
									"customProperties": {
										"avatarSrc": "<?= PROOT; ?>assets/media/avatar.png"
									}
								},
								<?= get_salepersons_for_push_capital($conn); ?>
							]}'
						></select>
				  		</div>
						<div class="">
							<label class="form-label">Amount given</label> 
							<input class="form-control" placeholder="0.00" name="today_given" id="today_given" type="number" min="0.00" step="0.01" value="<?= (is_capital_given() ? _capital()['today_capital'] : '' ); ?>">
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Close</button>&nbsp;&nbsp;
						<button type="button" id="submitCapital" class="btn btn-sm btn-warning">Save</button>
					</div>
				</form>
			</div>
		</div>
	</div>
<?php endif; ?>


		
<?php include ("includes/footer.inc.php"); ?>

<script type="text/javascript" src="<?= PROOT; ?>assets/js/Chart.min.js"></script>
<script>
	$(document).ready(function() {

		// list salepersonnel on push capital
		$(".for_class").change(function(e) {
			event.preventDefault()
			var select_for = $(".for_class:checked").val();

			if (select_for == 'saleperson') {
				$('#sf').removeClass('d-none');
			} else {
				$('#sf').addClass('d-none');
			}
		});


		$('#submitCapital').on('click', function() {
			
			// $('#capitalForm').submit();
		})
	});

</script>
<script type="text/javascript">
    /* globals Chart:false, feather:false */

	(function () {
	    'use strict'

	      // Graphs
	    var ctx = document.getElementById('performanceChart')
	      // eslint-disable-next-line no-unused-vars
	    var myChart = new Chart(ctx, {
	        type: 'line',
	        data: {
	            labels: [
	                <?php 
	                    for ($i = 1; $i <= 12; $i++) {
	                        $dt = dateTime::createFromFormat('!m',$i);
	                        $m = $dt->format("F");
	                        echo json_encode($m).',';
	                    }
	                ?>
	            ],
	            datasets: [{
	                label: '<?= $thisYr; ?>, Amount ₵',
	                data: [
	                    <?php 
	                        for ($i = 1; $i <= 12; $i++) {
	                            $mn = (array_key_exists($i, $current)) ? $current[$i] : 0;
	                            echo json_encode($mn).',';
	                        }
	                    ?>
	                ],
	                lineTension: 0,
	                backgroundColor: 'rgba(225, 0.1, 0.3, 0.1)',
	                borderColor: 'tomato',
	                borderWidth: 3,
	                pointBackgroundColor: 'red',
	            },{
	                label: '<?= $lastYr; ?>, Amount ₵',
	                data : [
	                    <?php 
	                        for ($i = 1; $i <= 12; $i++) {
	                            $mn = (array_key_exists($i, $last)) ? $last[$i] : 0;
	                            echo json_encode($mn).',';
	                        }
	                    ?>
	                ],
	                backgroundColor: 'rgba(0, 225, 0, 0.1)',
	                borderColor: 'gold',
	                pointBackgroundColor: 'brown',
	                borderWidth: 3
	            }]
	        },
	        options: {
	            responsive: true,
	            scales: {
	                yAxes: [{
	                    ticks: {
	                        beginAtZero: false
	                    }
	                }]
	            },
	            legend: {
	                display: true,
	                position: 'top',
	            },
	            title: {
	                display: false,
	                text: 'Sales By Month - J-Spence LTD.'
	            }
	        }
	    })
	})()
	
</script>
