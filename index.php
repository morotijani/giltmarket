<?php 
    require_once ("db_connection/conn.php");

	if (!admin_is_logged_in()) {
		admn_login_redirect();
	}

    include ("includes/header.inc.php");
    include ("includes/aside.inc.php");
    include ("includes/left.nav.inc.php");
    include ("includes/top.nav.inc.php");

	// statisticall calculations
	$thisYr = date("Y");
	$lastYr = $thisYr - 1;

	$where = '';
	if (!admin_has_permission()) {
		$where = ' AND sale_by = "' . $admin_id . '"';
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
				<p class="fs-lg text-body-secondary mb-0">Here's a summary of your account activity for this day: <?= date('l, F jS, Y', strtotime(date("Y-m-d"))); ?>.</p>
			</div>
		</div>

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
								<div class="fs-5 fw-semibold"><?= ((admin_has_permission()) ? total_amount_today($admin_id) : money(_capital($admin_id)['today_capital'])); ?></div>
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
								<div class="fs-5 fw-semibold"><?= ((admin_has_permission()) ? total_amount_thismonth($admin_data['admin_id']) : money(_capital($admin_data["admin_id"])['today_balance'])); ?></div>
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
									<?php
										$where = '';
										if (!admin_has_permission()) {
											$where = ' AND push_to = "' . $admin_data["admin_id"] . '" ';
										}
										$p = $conn->query("SELECT * FROM jspence_pushes WHERE push_date = '" . date('Y-m-d') . "' $where AND push_status = 0")->rowCount()
									?>
									<div class="fs-5 fw-semibold"><?= $p; ?></div>
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
										<div class="fs-5 fw-semibold"><?= _gained_calculation(_capital($admin_data["admin_id"])['today_balance'], _capital($admin_data["admin_id"])['today_capital']); ?></div>
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
							<!-- <div class="col-auto my-n3 me-n3">
								<select
								class="form-select"
								id="performanceChartSelect"
								data-choices='{ "searchEnabled": false, "choices": [{ "value": "week", "label": "Week" }, { "value": "month", "label": "Month" }]}'
								></select>
							</div> -->
						</div>
					</div>
					<div class="card-body">
						<div class="chart">
							<!-- <canvas class="chart-canvas" id="performanceChart"></canvas> -->
							<!-- <canvas class="chart-canvas" id="performanceChart"></canvas> -->
							<canvas class="my-4 w-100" id="myChart" width="900" height="400"></canvas>
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

			<div class="card mb-6">
				<div class="card-body py-3">
					<div class="d-flex gap-8 justify-content-center mb-5">
						<a href="javascript:;" class="text-lg fw-bold text-heading">Push</a> <span class="opacity-10">~></span> <a href="#" class="text-lg fw-bold text-muted">Gold</a>
					</div>
					<form class="vstack gap-6" id="buyForm">
                        <div id="step-1">
                            <div class="vstack gap-2">
                                <div class="bg-body-secondary rounded-3 p-4">
                                    <div class="d-flex justify-content-between text-xs text-muted">
                                        <span class="fw-semibold">Gold</span>
                                    </div>
                                    <div class="d-flex justify-content-between gap-2 mt-4">
                                        <input type="number" inputmode="numeric" class="form-control form-control-flush fw-bold text-xl flex-fill w-rem-50" placeholder="0.00" id="gram-amount" name="gram-amount" required autocomplete="off" min="0.00" step="0.01"> <button type="button" class="btn btn-outline-light shadow-none rounded-pill flex-none d-flex align-items-center gap-2 py-2 ps-2 pe-4"><img src="<?= PROOT; ?>assets/media/gold.png" class="w-rem-6 h-rem-6" alt="..."> <span class="text-xs text-heading ms-1">GRM</span>&nbsp;</button>
                                    </div>
                                </div>
								<div class="text-center text-sm text-muted text-underline">Cash at Hand ≈ 23.000 EUR</div>
								<div>
									<label class="form-label">Pick a supervisor</label>
									<div>
										<select class="form-control text-reset border border-dashed d-flex align-items-center"></select>
									</div>
								</div>
								<button type="button" data-bs-target="#sendMGModal" data-bs-toggle="modal" class="btn btn-lg btn-dark w-100">Proceed</button>
                            </div>
						</div>

						<div class="modal fade" id="sendMGModal" tabindex="-1" aria-labelledby="sendMGModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true" style="backdrop-filter: blur(5px);">
							<div class="modal-dialog modal-dialog-centered">
								<div class="modal-content overflow-hidden">
									<div class="modal-header pb-0 border-0">
										<h1 class="modal-title h4" id="sendMGModalLabel">Verify push!</h1>
										<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
									</div>
									<div class="modal-body">
										<div class="inputpin mb-3">
											<div>
												<?php if (is_capital_given()): ?>
													<?php if (is_capital_exhausted($conn, $admin_data['admin_id'])): ?>
													<label class="form-label">Enter pin</label>
													<div class="d-flex justify-content-between p-4 bg-body-tertiary rounded">
														<input type="number" class="form-control form-control-flush text-xl fw-bold w-rem-40 bg-transparent" placeholder="0000" name="pin" id="pin" autocomplete="off" inputmode="numeric" data-maxlength="4" oninput="this.value=this.value.slice(0,this.dataset.maxlength)" required>
														<button type="button" class="btn btn-sm btn-light rounded-pill shadow-none flex-none d-flex align-items-center gap-2 p-2" style="border: 1px solid #cbd5e1;">
															<img src="<?= PROOT; ?>assets/media/pin.jpg" class="w-rem-6 h-rem-6 rounded-circle" alt="..."> <span>PIN</span>
														</button>
													</div>
													<?php else: ?>
														<p class="h4">
															Trade ended: the capital given for today's trade has been exhausted!
														</p>
													<?php endif; ?>
												<?php else: ?>
													<p class="h4">
														Please you are to provide today's capital given before you can complete a trade!
													</p>
												<?php endif; ?>
											</div>
										</div>
										<?php if (is_capital_given()): ?>
											<?php if (is_capital_exhausted($conn, $admin_data['admin_id'])): ?>
												<button type="button" id="submitExpenditure" class="btn btn-warning mt-4">Send gold</button>
											<?php endif; ?>
										<?php endif; ?>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>

            <!-- Trades -->
			<div class="card mb-6 mb-xxl-0">
				<div class="card-header">
					<div class="row align-items-center">
						<div class="col">
							<h3 class="fs-6 mb-0">Trades</h3>
							<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
								99+
								<span class="visually-hidden">unread messages</span>
							</span>
						</div>
						<div class="col-auto my-n3 me-n3">
							<a class="btn btn-link" href="<?= PROOT; ?>account/trades">
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
						<?php if (admin_has_permission('supervisor')) : ?>
						<div class="col-auto my-n3 me-n3">
							<a class="btn btn-link" href="javascript:;" data-bs-target="#modalCapital" data-bs-toggle="modal">
							Make a push
							<span class="material-symbols-outlined">send_money</span>
							</a>
						</div>
						<?php endif; ?>
					</div>
              	</div>
              	<div class="card-body py-3">
                	<div class="list-group list-group-flush">
						<?= get_pushes_made($admin_id, date("Y-m-d")); ?>
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
		
<?php include ("includes/footer.inc.php"); ?>

<script type="text/javascript" src="<?= PROOT; ?>assets/js/Chart.min.js"></script>
<script>
	$(document).ready(function() {

		// fetch current time.
		function updateTime() {
			var currentTime = new Date()
			var hours = currentTime.getHours()
			var seconds = currentTime.getSeconds();
			var minutes = currentTime.getMinutes()
			if (minutes < 10){
				minutes = "0" + minutes
			}
			if (seconds < 10){
				seconds = "0" + seconds
			}
			var t_str = hours + ":" + minutes + " " + seconds + " ";
			if(hours > 11){
				t_str += "PM";
			} else {
				t_str += "AM";
			}
			document.getElementById('time_span').innerHTML = t_str;
		}
		setInterval(updateTime, 1000);
	});

</script>
<?php if (admin_has_permission()) : ?>
<script type="text/javascript">
    /* globals Chart:false, feather:false */

	(function () {
	    'use strict'

	      // Graphs
	    var ctx = document.getElementById('myChart')
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
<?php endif; ?>