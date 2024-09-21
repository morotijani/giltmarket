<?php 
    require_once ("db_connection/conn.php");

    include ("includes/header.inc.php");
    include ("includes/nav.inc.php");

    if (!admin_is_logged_in()) {
		admn_login_redirect();
	}

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
		$where = ' AND sale_by = "'.$admin_data[0]['admin_id'].'"';
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


		<?php if (admin_is_logged_in()): ?>

			<?php if (!admin_has_permission()): ?>
				<div class="mb-6 mb-xl-10">
					<div class="row g-3 align-items-center">
						<div class="col">
							<h1 class="ls-tight">
								<?= ((admin_has_permission('supervisor')) ? 'Sold' : 'Balance'); ?>: 
								<span style="font-family: Roboto Mono, monospace;"><?= money(_capital()['today_balance']); ?></span>
							</h1>
							<p class="text-sm text-muted">
								<?php if (admin_has_permission('supervisor')) :?>
								Gained: <span class="text-success" style="font-family: Roboto Mono, monospace;"><?= _gained_calculation(_capital()['today_balance'], _capital()['today_capital']); ?></span>
								<br>
								<?php endif; ?>
								Amount given today to trade: <span style="font-family: Roboto Mono, monospace;"><?= money(_capital()['today_capital']); ?></span> 
								<br>Today date: <?= date("Y-m-d"); ?>
							</p>
						</div>
						<div class="col">
							<div class="hstack gap-2 justify-content-end">
								<button type="button" class="btn btn-sm btn-square btn-neutral rounded-circle d-xxl-none" data-bs-toggle="offcanvas" data-bs-target="#responsiveOffcanvas" aria-controls="responsiveOffcanvas"><i class="bi bi-three-dots"></i></button> <button type="button" class="btn btn-sm btn-neutral d-none d-sm-inline-flex" data-bs-target="#buyModal" data-bs-toggle="modal"><span class="pe-2"><i class="bi bi-plus-circle"></i> </span><span>Trade</span></button> 
								<button data-bs-toggle="modal" data-bs-target="#modalCapital" type="button" class="btn d-inline-flex btn-sm btn-dark"><span>Today Capital</span></button>
							</div>
						</div>
					</div>
				</div>
			<?php endif; ?>

			<div class="row g-3 g-xxl-6">
				<div class="col-xxl-8">
					<div class="vstack gap-3 gap-md-6">
						<div class="row g-3">
							<div class="col-md col-sm-6">
								<div class="card border-primary-hover">
									<div class="card-body p-4" style="font-family: Roboto Mono, monospace;">
										<div class="d-flex align-items-center gap-2">
											<img src="<?= PROOT; ?>dist/media/today.png" class="w-rem-5 flex-none" alt="..."> <a href="javascript:;" class="h6 stretched-link">Today</a>
										</div>
										<?php $t = total_amount_today($admin_data[0]['admin_id']); ?>
										<div class="text-sm fw-semibold mt-3"><?= $t['amount']; ?></div>
										<div class="d-flex align-items-center gap-2 mt-1 text-xs">
											<span class="badge badge-xs bg-<?= $t['percentage_color']; ?>"><i class="bi bi-arrow-<?= $t['percentage_icon']; ?>"></i> </span><span><?= $t['percentage']; ?>%</span>
										</div>
									</div>
								</div>
							</div>
									<div class="col-md col-sm-6">
										<div class="card border-primary-hover">
											<div class="card-body p-4" style="font-family: Roboto Mono, monospace;">
												<div class="d-flex align-items-center gap-2">
													<img src="<?= PROOT; ?>dist/media/thismonth.png" class="w-rem-5 flex-none" alt="..."> 
													<a href="javascript:;" class="h6 stretched-link">This Month</a>
												</div>
												<?php $m = total_amount_thismonth($admin_data[0]['admin_id']); ?>
												<div class="text-sm fw-semibold mt-3"><?= $m['amount']; ?></div>
												<div class="d-flex align-items-center gap-2 mt-1 text-xs"><span class="badge badge-xs bg-<?= $t['percentage_color']; ?>"><i class="bi bi-arrow-<?= $t['percentage_icon']; ?>"></i> </span><span><?= $t['percentage']; ?>%</span></div>
											</div>
										</div>
									</div>
									<div class="col-md col-sm-6">
										<div class="card border-primary-hover">
											<div class="card-body p-4" style="font-family: Roboto Mono, monospace;">
												<div class="d-flex align-items-center gap-2">
													<img src="<?= PROOT; ?>dist/media/orders.jpg" class="w-rem-5 flex-none" alt="..."> 
													<a href="<?= PROOT; ?>acc/trades" class="h6 stretched-link">Orders</a></div>
													<div class="text-sm fw-semibold mt-3"><?= count_total_orders($admin_data[0]['admin_id']); ?></div>
													<div class="d-flex align-items-center gap-2 mt-1 text-xs">
														<span class="badge badge-xs bg-info"><i class="bi bi-123"></i> </span><span><?= date("l jS \of F " . ' . ' . " A"); ?></span>
													</div>
												</div>
											</div>
										</div>
									</div>
									<?php if (admin_has_permission()): ?>
									<div class="card">
										<div class="card-body pb-0">
											<div class="d-flex justify-content-between align-items-center"><div>
												<h5>Trades</h5>
											</div>
											<div class="hstack align-items-center">
												<a href="<?= PROOT; ?>acc/analytics" class="text-muted"><i class="bi bi-arrow-repeat"></i></a>
											</div>
										</div>
										<div class="mx-n4">
											<canvas class="my-4 w-100" id="myChart" width="900" height="380"></canvas>
										</div>
									</div>
								</div>
								
								<div class="card">
									<div class="card-body">
										<div class="d-flex justify-content-between align-items-center mb-5">
											<div>
												<h5>Accumulated trades by months and years</h5>
											</div>
											<div class="hstack align-items-center">
												<a href="<?= PROOT; ?>acc/trades" class="text-muted">
													<i class="bi bi-arrow-repeat"></i>
												</a>
											</div>
										</div>
										<div class="vstack gap-6">
											<table class="table table-bordered table-lg">
												<thead>
													<tr>
														<th scope="col"></th>
						                                <th scope="col" style="font-family: Roboto Mono, monospace;"><?= $lastYr; ?></th>
						                                <th scope="col" style="font-family: Roboto Mono, monospace;"><?= $thisYr; ?></th>
													</tr>
												</thead>
												 <tbody>
						                            <?php for ($i = 1; $i <= 12; $i++):
						                                $dt = dateTime::createFromFormat('!m',$i);
						                            ?>
						                                <tr>
						                                    <td <?= (date('m') == $i) ? ' class="bg-danger"' : ''; ?>><?= $dt->format("F"); ?></td>
						                                    <td <?= (date('m') == $i) ? ' class="bg-danger"' : ''; ?> style="font-family: Roboto Mono, monospace;"><?= ((array_key_exists($i, $last)) ? money($last[$i]) : money(0)); ?></td>
						                                    <td <?= (date('m') == $i) ? ' class="bg-danger"' : ''; ?> style="font-family: Roboto Mono, monospace;"><?=  ((array_key_exists($i, $current)) ? money($current[$i]) : money(0)); ?></td>
						                                </tr>
						                            <?php endfor; ?>
						                            <tr>
						                                <td>Total</td>
						                                <td style="font-family: Roboto Mono, monospace;"><?= money($lastTotal); ?></td>
						                                <td style="font-family: Roboto Mono, monospace;"><?= money($currentTotal); ?></td>
						                            </tr>
						                        </tbody>
											</table>
										</div>
									</div>
								</div>
								<?php endif; ?>

								<div class="card">
									<div class="card-body">
										<div class="d-flex justify-content-between align-items-center mb-5">
											<div>
												<h5>Recent trades</h5>
											</div>
											<div class="hstack align-items-center">
												<a href="<?= PROOT; ?>acc/trades" class="text-muted">
													<i class="bi bi-arrow-repeat"></i>
												</a>
											</div>
										</div>
										<div class="vstack gap-6">
											<?= get_recent_trades($admin_data[0]['admin_id']); ?>
										</div>
									</div>
								</div>

							</div>
						</div>
						<div class="col-xxl-4">
							<div class="offcanvas-xxl m-xxl-0 rounded-sm-4 rounded-xxl-0 offcanvas-end overflow-hidden m-sm-4" tabindex="-1" id="responsiveOffcanvas" aria-labelledby="responsiveOffcanvasLabel">
								<div class="offcanvas-header rounded-top-4 bg-light">
									<h5 class="offcanvas-title" id="responsiveOffcanvasLabel">Quick Stats</h5>
									<button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#responsiveOffcanvas" aria-label="Close"></button>
								</div>
								<div class="offcanvas-body d-flex flex-column p-3 p-sm-6 p-xxl-0 gap-3 gap-xxl-6">
									<div class="vstack gap-6 gap-xxl-6">
										<div class="card border-0 border-xxl">
											<div class="card-body d-flex flex-column p-0 p-xxl-6" style="font-family: Roboto Mono, monospace;">
												<div class="d-flex justify-content-between align-items-center mb-3">

													<?php $g = grand_total_amount($admin_data[0]['admin_id']); ?>
													<div>
														<h5>Grand total</h5>
													</div>
													<div>
														<span class="text-heading fw-bold">
															<i class="bi bi-arrow-<?= $g['percentage_icon'] ?> me-2"></i><?= $g['percentage'] ?>%</span>
														</div>
													</div>
													<div class="text-2xl fw-bolder text-heading ls-tight"><?= $g['grand_total']; ?></div>
													<div class="d-flex align-items-center justify-content-between mt-8">
														<div class="">
															<div class="d-flex gap-3 align-items-center">
																<div class="icon icon-sm icon-shape text-sm rounded-circle bg-dark text-info">
																	<i class="bi bi-currency-exchange"></i>
																</div>
																<span class="h6 fw-semibold text-muted">Last year</span>
															</div>
															<div class="fw-bold text-heading mt-3"><?= $g['last_year']; ?></div>
														</div>
														<span class="vr bg-dark bg-opacity-10"></span>
														<div class="">
															<div class="d-flex gap-3 align-items-center">
																<div class="icon icon-sm icon-shape text-sm rounded-circle bg-dark text-success">
																	<i class="bi bi-currency-exchange"></i>
																</div>
																<span class="h6 fw-semibold text-muted">This year</span>
															</div>
															<div class="fw-bold text-heading mt-3"><?= $g['this_year']; ?></div>
														</div>
													</div>
												</div>
											</div>
											<hr class="my-0 d-xxl-none">
											<div class="card border-0 border-xxl">
												<div class="card-body p-0 p-xxl-6">
													<div class="d-flex justify-content-between align-items-center mb-5"><div>
														<h5>Logs</h5>
													</div>
													<div class="hstack align-items-center">
														<a href="<?= PROOT; ?>acc/logs" title="view more" class="text-muted">
															<i class="bi bi-three-dots-vertical"></i>
														</a>
													</div>
												</div>
												<div class="vstack gap-1">
													<ul class="list-group">
													  	<?= get_logs($admin_data[0]['admin_id']); ?>
													</ul>
												</div>
											</div>
										</div>
										<hr class="my-0 d-xxl-none">
									</div>
								</div>
							</div>
						</div>
					</div>
					
					


					<?php else: ?>

					<div class="d-flex justify-content-center">
						<div class="col-md-6 p-12 p-xl-7">
							<div class="d-lg-flex flex-column w-full h-full p-16 bg-surface-secondary rounded-5">
								<!-- <a class="d-block" href="<?= PROOT; ?>">
									<div class="w-md-auto  text-dark">
										<img class="img-fluid rounded" style="widht: 64px; height: 64px;" src="<?= PROOT; ?>dist/media/logo.jpeg">
									</div>
								</a> -->

								<!-- Title -->
								<div class="mt-10 mt-xl-16">
									<h1 class="lh-tight ls-tighter font-bolder display-5">
										admin portal, login to start making trades.
									</h1>
								</div>

								<div class="svg-fluid mt-auto mb-xl-20 mx-auto transform scale-150">
									<img class="img-fluid" width="400" height="400" src="<?= PROOT; ?>dist/media/auth.svg" />
								</div>
							</div>
						</div>
					</div>
					<?php endif; ?>


<?php include ("includes/footer.inc.php"); ?>

<script type="text/javascript" src="<?= PROOT; ?>dist/js/Chart.min.js"></script>
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
	                pointBackgroundColor: 'red'
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
	                display: true,
	                text: 'Sales By Month - J-Spence LTD.'
	            }
	        }
	    })
	})()
</script>
