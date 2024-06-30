<?php 
    require_once ("db_connection/conn.php");

    include ("includes/header.inc.php");
    include ("includes/nav.inc.php");

    $thisYr = date("Y");
    $lastYr = $thisYr - 1;

    $thisYrQ = "
        SELECT sale_total_amount, createdAt 
        FROM jspence_sales 
        WHERE YEAR(createdAt) = ? 
        AND sale_by = ?
    ";
    $statement = $conn->prepare($thisYrQ);
    $statement->execute([$thisYr, $admin_data[0]['admin_id']]);
    $thisYr_result = $statement->fetchAll();
    

    $lastYrQ = "
        SELECT sale_total_amount, createdAt 
        FROM jspence_sales 
        WHERE YEAR(createdAt) = ? 
        AND sale_by = ?
    ";
    $statement = $conn->prepare($lastYrQ);
    $statement->execute([$lastYr, $admin_data[0]['admin_id']]);
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
					<!-- <div class="row align-items-center g-10">
						<div class="col-lg-8">
							<h1 class="ls-tight fw-bolder display-3 text-white mb-5">Build Professional Dashboards, Faster than Ever.</h1>
							<p class="w-xl-75 lead text-white">With our intuitive tools and expertly designed components, you'll have the power to create professional dashboards quicker than ever.</p>
						</div>
						<div class="col-lg-4 align-self-end">
							<div class="hstack gap-3 justify-content-lg-end"><a href="https://themes.getbootstrap.com/product/satoshi-defi-and-crypto-exchange-theme/" class="btn btn-lg btn-white rounded-pill bg-dark-hover border-0 shadow-none px-lg-8" target="_blank">Purchase now </a><a href="/pages/dashboard.html" class="btn btn-lg btn-dark rounded-pill border-0 shadow-none px-lg-8">Explore more</a>
							</div>
						</div>
					</div> -->




					<div class="mb-6 mb-xl-10">
						<div class="row g-3 align-items-center">
							<div class="col">
								<h1 class="ls-tight">Dashboard</h1>
							</div>
							<div class="col">
								<div class="hstack gap-2 justify-content-end">
									<button type="button" class="btn btn-sm btn-square btn-neutral rounded-circle d-xxl-none" data-bs-toggle="offcanvas" data-bs-target="#responsiveOffcanvas" aria-controls="responsiveOffcanvas"><i class="bi bi-three-dots"></i></button> <button type="button" class="btn btn-sm btn-neutral d-none d-sm-inline-flex" data-bs-target="#buyModal" data-bs-toggle="modal"><span class="pe-2"><i class="bi bi-plus-circle"></i> </span><span>Trade</span></button> 
									<a href="<?= PROOT; ?>" class="btn d-inline-flex btn-sm btn-dark"><span>Refresh</span></a>
								</div>
							</div>
						</div>
					</div>

					<div class="row g-3 g-xxl-6">
						<div class="col-xxl-8">
							<div class="vstack gap-3 gap-md-6">
								<div class="row g-3">
									<div class="col-md col-sm-6">
										<div class="card border-primary-hover">
											<div class="card-body p-4">
												<div class="d-flex align-items-center gap-2">
													<img src="<?= PROOT; ?>dist/media/today.png" class="w-rem-5 flex-none" alt="..."> <a href="javascript:;" class="h6 stretched-link">Today</a>
												</div>
												<?php $t = total_amount_today($admin_data[0]['admin_id'], $admin_data[0]['admin_permissions']); ?>
												<div class="text-sm fw-semibold mt-3"><?= $t['amount']; ?></div>
												<div class="d-flex align-items-center gap-2 mt-1 text-xs">
													<span class="badge badge-xs bg-<?= $t['percentage_color']; ?>"><i class="bi bi-arrow-<?= $t['percentage_icon']; ?>"></i> </span><span><?= $t['percentage']; ?>%</span>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md col-sm-6">
										<div class="card border-primary-hover">
											<div class="card-body p-4">
												<div class="d-flex align-items-center gap-2">
													<img src="<?= PROOT; ?>dist/media/thismonth.png" class="w-rem-5 flex-none" alt="..."> 
													<a href="javascript:;" class="h6 stretched-link">This Month</a>
												</div>
												<?php $m = total_amount_thismonth($admin_data[0]['admin_id'], $admin_data[0]['admin_permissions']); ?>
												<div class="text-sm fw-semibold mt-3"><?= $t['amount']; ?></div>
												<div class="d-flex align-items-center gap-2 mt-1 text-xs"><span class="badge badge-xs bg-<?= $t['percentage_color']; ?>"><i class="bi bi-arrow-<?= $t['percentage_icon']; ?>"></i> </span><span><?= $t['percentage']; ?>%</span></div>
											</div>
										</div>
									</div>
									<div class="col-md col-sm-6">
										<div class="card border-primary-hover">
											<div class="card-body p-4">
												<div class="d-flex align-items-center gap-2">
													<img src="<?= PROOT; ?>dist/media/orders.jpg" class="w-rem-5 flex-none" alt="..."> 
													<a href="<?= PROOT; ?>acc/trades" class="h6 stretched-link">Orders</a></div>
													<div class="text-sm fw-semibold mt-3"><?= count_total_orders($admin_data[0]['admin_id'], $admin_data[0]['admin_permissions']); ?></div>
													<div class="d-flex align-items-center gap-2 mt-1 text-xs">
														<span class="badge badge-xs bg-info"><i class="bi bi-123"></i> </span><span><?= date("l jS \of F " . ' . ' . " A"); ?></span>
													</div>
												</div>
											</div>
										</div>
									</div>

									<div class="card">
										<div class="card-body pb-0">
											<div class="d-flex justify-content-between align-items-center"><div>
												<h5>Earnings</h5>
											</div>
											<div class="hstack align-items-center">
												<a href="#" class="text-muted"><i class="bi bi-arrow-repeat"></i></a>
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
												<h5>Recent transactions</h5>
											</div>
											<div class="hstack align-items-center">
												<a href="<?= PROOT; ?>acc/trades" class="text-muted">
													<i class="bi bi-arrow-repeat"></i>
												</a>
											</div>
										</div>
										<div class="vstack gap-6">
											<?= get_recent_trades($admin_data[0]['admin_id'], $admin_data[0]['admin_permissions']); ?>
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
											<div class="card-body d-flex flex-column p-0 p-xxl-6">
												<div class="d-flex justify-content-between align-items-center mb-3">

													<?php $g = grand_total_amount($admin_data[0]['admin_id'], $admin_data[0]['admin_permissions']); ?>
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
													  	<?= get_logs($admin_data[0]['admin_id'], $admin_data[0]['admin_permissions']); ?>
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
						<div class="mt-10 d-flex justify-content-center">
							<!-- <img src="/img/marketing/hero-img-1.png"> -->
							<script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs" type="module"></script> 
							<dotlottie-player src="<?= PROOT; ?>dist/media/bg.json" background="transparent" speed="1" style="width: 300px; height: 300px;" loop autoplay></dotlottie-player>
						</div>
					<?php endif; ?>


				



	<!-- LOGIN -->
	<div class="modal fade" id="connectWalletModal" tabindex="-1" aria-labelledby="connectWalletModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content overflow-hidden">
				<div class="modal-header pb-0 border-0">
					<h1 class="modal-title h4" id="connectWalletModalLabel">Connect your account</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body undefined">
					<div class="list-group list-group-flush gap-2">
						<form method="POST" action="<?= PROOT; ?>auth/login">
							<div class="list-group-item border rounded p-4 bg-body-secondary-hover">
								<div class="">
									<input type="email" name="admin_email" class="form-control" placeholder="Email">
								</div>
							</div>
							<div class="list-group-item border rounded p-4 bg-body-secondary-hover">
								<div class="">
									<input type="password" name="admin_password" class="form-control" placeholder="******">
								</div>
							</div>
							<div class="list-group-item border rounded p-4 bg-body-secondary-hover">
								<div class="">
									<input type="submit" name="submit_form" class="form-control" value="Connect">
								</div>
							</div>
						</form>
					</div>
					<div class="text-xs text-muted mt-6">Missing password? <a href="auth/recover-password" class="fw-bold">Recover here.</a></div>
					<div class="text-xs text-muted mt-6">By connecting, know that we save all actions into logs for future references. You agree to J-Spence <a href="#" class="fw-bold">Terms of Service</a></div>
				</div>
			</div>
		</div>
	</div>

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