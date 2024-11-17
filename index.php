<?php 
    require_once ("db_connection/conn.php");

	if (!admin_is_logged_in()) {
		admin_login_redirect();
	}
	idle_user();
	
	//
	if (is_array(capital_mover($admin_id)) && capital_mover($admin_id)["msg"] == "touched") {
		redirect(PROOT . 'auth/end-trade-checker');
	}

    include ("includes/header.inc.php");
    include ("includes/aside.inc.php");
    include ("includes/left.nav.inc.php");
    include ("includes/top.nav.inc.php");

	// statistical calculations
	$thisYr = date("Y");
	$lastYr = $thisYr - 1;

	$thisYrQ = "
		SELECT sale_total_amount, createdAt 
		FROM jspence_sales 
		WHERE YEAR(createdAt) = '{$thisYr}' 
		AND sale_status = 0 
	";
	$statement = $conn->prepare($thisYrQ);
	$statement->execute();
	$thisYr_result = $statement->fetchAll();
	

	$lastYrQ = "
		SELECT sale_total_amount, createdAt 
		FROM jspence_sales 
		WHERE YEAR(createdAt) = '{$lastYr}' 
		AND sale_status = 0 
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
		<?php if ($admin_permission == 'supervisor'): ?>
			<div class="text-info">&nbsp;&nbsp;Gold given: <?= money(_capital($admin_id)['today_capital']); ?></div>
		<?php endif; ?>
		<div class="row mb-8">
        	<div class="col-12 col-md-6 col-xxl-3 mb-4 mb-xxl-0">
            	<div class="card bg-success-subtle border-transparent">
              		<div class="card-body">
                		<div class="row align-items-center">
							<div class="col">
								<!-- Heading -->
								<h4 class="des fs-base fw-normal text-body-secondary mb-1">
									<?php 
										if (admin_has_permission()) {
											echo 'Capital';
										} else if (admin_has_permission('supervisor')) {
											echo 'Gold balance';
										} else {
											echo 'Money given';
										}
									?>
								</h4>
								<div class="hide">
								<?php 
										if (admin_has_permission()) {
											echo 'Sum of all capital given to admins to trade (both gold and cash).';
										} else if (admin_has_permission('supervisor')) {
											echo 'The remaining amount of gold you\'ve sold so far.';
										} else {
											echo 'The total amount of cash given to make trades for today.';
										}
									?>
								</div>


								<!-- Text -->
								<div class="fs-5 fw-semibold">
									<?php 
										if (admin_has_permission()) {
											echo money(sum_capital_given_for_day());
										} else if (admin_has_permission('supervisor')) {
											echo money(remaining_gold_balance($admin_id));
										} else {
											echo money(_capital($admin_id)['today_capital']);
										}
									?>
								</div>
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
					<div class="card bg-warning-subtle border-transparent">
						<div class="card-body">
							<div class="row align-items-center">
							<div class="col">
								<!-- Heading -->
								<?php if (admin_has_permission()) : ?>
									<h4 class="fs-base fw-normal text-body-secondary mb-1 des"> Trades </h4>
								<?php else: ?>
									<h4 class="fs-base fw-normal text-body-secondary mb-1 des"><?= ((admin_has_permission('supervisor')) ? 'Sold' : 'Balance'); ?></h4>
								<?php endif; ?>

								<div class="hide">
								<?php 
										if (admin_has_permission()) {
											echo 'Total amount of trades made today.';
										} else if (admin_has_permission('supervisor')) {
											echo 'Sum of gold that has been sold to customers.';
										} else {
											echo 'The remaining amount of money left to trade.';
										}
									?>
								</div>

								<!-- Text -->
								<div class="fs-5 fw-semibold"><?= ((admin_has_permission()) ? total_trades_amount_today() : money(_capital($admin_id)['today_balance'])); ?></div>
							</div>
							<div class="col-auto">
								<!-- Avatar -->
								<div class="avatar avatar-lg bg-body text-warning">
								<i class="fs-4" data-duoicon="align-bottom"></i>
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
										<div class="fs-5 fw-semibold"><?= money(_gained_calculation(_capital($admin_data["admin_id"])['today_balance'], _capital($admin_data["admin_id"])['today_capital'], $admin_id)); ?></div>
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
									<?php if (admin_has_permission()) : ?>
										<h4 class="fs-base fw-normal text-body-secondary mb-1"> Expenditure </h4>
									<?php else: ?>
										<h4 class="fs-base fw-normal text-body-secondary mb-1"> <?= (($admin_data['admin_permissions'] == 'salesperson') ? 'Gold' : 'Money'); ?> accumulated </h4>
									<?php endif; ?>

									<!-- Text -->
									<div class="fs-5 fw-semibold">
										<?php 
											if (admin_has_permission()) {
												$e = total_expenditure_today($admin_id);
												echo money($e["sum"]);
											} else {
												echo money(total_amount_today($admin_id));
											}
										?>
									</div>
								</div>
								<div class="col-auto">
									<!-- Avatar -->
									<div class="avatar avatar-lg bg-body text-warning">
										<i class="fs-4" data-duoicon="briefcase"></i>
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
						</div>
					</div>
					<div class="card-body">
						<div class="chart">
							<!-- <canvas class="chart-canvas" id="performanceChart"></canvas> -->
							<canvas class="my-4 w-100" id="myChart" width="900" height="400"></canvas>
						</div>
					</div>
				</div>

				<div class="card mb-6">
					<div class="card-header">
						<div class="row align-items-center">
							<div class="col">
								<h3 class="fs-6 mb-0">Trades by months and years</h3>
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
			<?php if (!admin_has_permission()) : ?>
			<div class="card mb-6">
				<div class="card-body py-3">
					<div class="d-flex gap-8 justify-content-center mb-5">
						<a href="javascript:;" class="text-lg fw-bold text-heading">Push</a> <span class="opacity-10">~></span> <a href="javascript:;" class="text-lg fw-bold text-muted"><?= ((admin_has_permission('supervisor')) ? 'Money' : 'Gold'); ?></a>
					</div>
					<form class="vstack gap-6" method="POST" id="sendMGForm" action="<?= PROOT; ?>auth/make.push.php" autocomplete="off">
                        <div id="">
                            <div class="vstack gap-2">
								<div class="form-check">
									<input class="form-check-input" name="push-all" type="checkbox" id="push-all" value="push-all">
									<label class="form-check-label" for="push-all">
										Push all <?= ((admin_has_permission('supervisor')) ? 'money' : 'gold'); ?>
									</label>
								</div>
								<div class="bg-body-secondary rounded-3 p-4">
									<div class="d-flex justify-content-between text-xs text-muted">
										<span class="fw-semibold"><?= ((admin_has_permission('supervisor')) ? 'Money' : 'Gold'); ?></span>
									</div>
									<div class="d-flex justify-content-between gap-2 mt-4">
										<input type="number" inputmode="numeric" class="form-control form-control-flush fw-bold text-xl flex-fill w-rem-50" placeholder="0.00" id="today_given" name="today_given" required autocomplete="off" min="0.00" step="0.01" <?= ((admin_has_permission('salesperson')) ? 'readonly' : ''); ?>> <button type="button" class="btn btn-outline-light shadow-none rounded-pill flex-none d-flex align-items-center gap-2 py-2 ps-2 pe-4"><img src="<?= PROOT; ?>assets/media/<?= ((admin_has_permission('supervisor')) ? 'money' : 'gold'); ?>.png" class="w-rem-6 h-rem-6" alt="..."> <span class="text-xs text-heading ms-1"><?= ((admin_has_permission('supervisor')) ? 'GHS' : 'GRM'); ?></span>&nbsp;</button>
									</div>
								</div>
								<div class="text-center text-sm text-muted text-underline"><?= ((admin_has_permission('supervisor')) ? 'Cash in coffers' : 'Gold at Hand'); ?> ≈ <?= ((admin_has_permission('supervisor')) ? money(get_admin_coffers($conn, $admin_id, 'balance')) : money(total_amount_today($admin_id))); ?> GHS</div>
								<input type="hidden" id="in-hand" value="<?= ((admin_has_permission('supervisor')) ? get_admin_coffers($conn, $admin_id, 'balance') : total_amount_today($admin_id)); ?>">
								<?php if (admin_has_permission('salesperson')) : ?>
								<!-- <div class="row">
									<div class="col">
										<input type="number" inputmode="numeric" class="form-control" name="push_price" id="push_price" autocomplete="off" min="0.00" step="0.01" placeholder="Current price" required>
									</div>
									<div class="col">
										<input type="number" inputmode="numeric" class="form-control" name="push_gram" id="push_gram" autocomplete="off" min="0.00" step="0.01" placeholder="Gram" required>
									</div>
									<div class="col">
										<input type="number" inputmode="numeric" class="form-control" name="push_volume" id="push_volume" autocomplete="off" min="0.00" step="0.01" placeholder="Volume" required>
									</div>
								</div>
								<div class="row d-none" id="push-result">
									<div class="col">
										<input type="text" class="form-control" readonly name="push_density" id="push_density" placeholder="Density">
									</div>
									<div class="col">
										<input type="text" class="form-control" readonly name="push_pounds" id="push_pounds" placeholder="Pounds">
									</div>
									<div class="col">
										<input type="text" class="form-control" readonly name="push_carat" id="push_carat" placeholder="Carat">
									</div>
								</div> -->
								<?php endif; ?>
								<div class="row">
									<div class="col">
										<input class="form-control" name="today_date" id="today_date" readonly type="date" value="<?php echo date('Y-m-d'); ?>" required>
									</div>
									<div class="col">
										<select class="form-control" name="push_to" id="push_to" required>
											<option value="">Pick a <?= ((admin_has_permission('supervisor')) ? 'sales person' : 'supervisor'); ?></option>
											<?php 
												if (admin_has_permission('supervisor')) {
													echo get_salepersons_for_push_capital($conn);
												} else {
													echo get_supervisors_for_push_capital($conn);
												}
											?>
										</select>
									</div>
								</div>
								<div class="">
									<input class="form-control" name="push_note" id="push_note" placeholder="Note ..." type="text">
								</div>
								<div><small class="text-muted" id="push_msg"></small></div>
								<button type="button" class="btn btn-lg w-100" id="push-next-1">Proceed</button>
                            </div>
						</div>

						<!-- Push summary -->
						<div class="modal fade" id="pushSummaryModal" tabindex="-1" aria-labelledby="pushSummaryModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" style="backdrop-filter: blur(5px);">
							<div class="modal-dialog modal-dialog-centered">
								<div class="modal-content overflow-hidden">
									<div class="modal-header pb-0 border-0">
										<h1 class="modal-title h4" id="pushSummaryModalLabel">Push summary</h1>
										<button type="button" class="btn-close btn-close-push-modal" data-bs-dismiss="modal" aria-label="Close"></button>
									</div>
									<div class="modal-body">
										<ul class="list-group list-group-flush">
											<div class="list-group-item px-0">
												<div class="row align-items-center">
													<div class="col ms-n2">
														<h6 class="fs-base fw-normal mb-1">Total amount,</h6>
													</div>
													<div class="col-auto">
														<time class="text-body-secondary" id="push-amount"></time>
													</div>
												</div>
											</div>
											<?php if (admin_has_permission('salesperson')) : ?>
											<div class="list-group-item px-0">
												<div class="row align-items-center">
													<div class="col ms-n2">
														<h6 class="fs-base fw-normal mb-1">Gram,</h6>
													</div>
													<div class="col-auto">
														<time class="text-body-secondary"><?= sum_up_grams($conn, $admin_id); ?></time>
													</div>
												</div>
											</div>
											<div class="list-group-item px-0">
												<div class="row align-items-center">
													<div class="col ms-n2">
														<h6 class="fs-base fw-normal mb-1">Volume,</h6>
													</div>
													<div class="col-auto">
														<time class="text-body-secondary"><?= sum_up_volume($conn, $admin_id); ?></time>
													</div>
												</div>
											</div>
											<div class="list-group-item px-0">
												<div class="row align-items-center">
													<div class="col ms-n2">
														<h6 class="fs-base fw-normal mb-1">Density,</h6>
													</div>
													<div class="col-auto">
														<time class="text-body-secondary"><?= sum_up_density($conn, $admin_id); ?></time>
													</div>
												</div>
											</div>
											<div class="list-group-item px-0">
												<div class="row align-items-center">
													<div class="col ms-n2">
														<h6 class="fs-base fw-normal mb-1">Pounds,</h6>
													</div>
													<div class="col-auto">
														<time class="text-body-secondary"><?= sum_up_pounds($conn, $admin_id); ?></time>
													</div>
												</div>
											</div>
											<div class="list-group-item px-0">
												<div class="row align-items-center">
													<div class="col ms-n2">
														<h6 class="fs-base fw-normal mb-1">Carat,</h6>
													</div>
													<div class="col-auto">
														<time class="text-body-secondary"><?= sum_up_carat($conn, $admin_id); ?></time>
													</div>
												</div>
											</div>
											<?php endif; ?>
											<div class="list-group-item px-0">
												<div class="row align-items-center">
													<div class="col ms-n2">
														<h6 class="fs-base fw-normal mb-1">To,</h6>
													</div>
													<div class="col-auto">
														<time class="text-body-secondary" id="push-to"></time>
													</div>
												</div>
											</div>
											<div class="list-group-item px-0">
												<div class="row align-items-center">
													<div class="col ms-n2">
														<h6 class="fs-base fw-normal mb-1">Note,</h6>
													</div>
													<div class="col-auto">
														<time class="text-body-secondary" id="push-note"></time>
													</div>
												</div>
											</div>
											<div class="list-group-item px-0">
												<div class="row align-items-center">
													<div class="col ms-n2">
														<h6 class="fs-base fw-normal mb-1">Date,</h6>
													</div>
													<div class="col-auto">
														<time class="text-body-secondary"><?= date("Y-m-d"); ?></time>
													</div>
												</div>
											</div>
										</ul>
										<div class="d-flex justify-content-between">
											<button type="button" class="btn btn-sm btn-warning" id="push-next-2"> Next </button>
											<button type="button" class="btn btn-sm" id="push-back-1"><< Go back</button>
										</div>
									</div>
								</div>
							</div>
						</div>
						
						<!-- Enter pin on push -->
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
												<?php if ((admin_has_permission('supervisor') && get_admin_coffers($conn, $admin_id, 'balance') > 0) || (admin_has_permission('salesperson') && total_amount_today($admin_id) > 0)): ?>
													<label class="form-label">Enter pin</label>
													<div class="d-flex justify-content-between p-4 bg-body-tertiary rounded">
														<input type="number" class="form-control form-control-flush text-xl fw-bold w-rem-40 bg-transparent" placeholder="0000" name="pin" id="push_pin" autocomplete="off" inputmode="numeric" data-maxlength="4" oninput="this.value=this.value.slice(0,this.dataset.maxlength)" required>
														<button type="button" class="btn btn-sm btn-light rounded-pill shadow-none flex-none d-flex align-items-center gap-2 p-2" style="border: 1px solid #cbd5e1;">
															<img src="<?= PROOT; ?>assets/media/pin.jpg" class="w-rem-6 h-rem-6 rounded-circle" alt="..."> <span>PIN</span>
														</button>
													</div>
												<?php else: ?>
													<p class="h5 text-muted">
														There is no <?= ((admin_has_permission('supervisor')) ? 'cash' : 'gold'); ?> at hand to make this push!
													</p>
												<?php endif; ?>
											</div>
										</div>
										<?php 
											if ((admin_has_permission('supervisor') && get_admin_coffers($conn, $admin_id, 'balance') > 0) || (admin_has_permission('salesperson') && total_amount_today($admin_id) > 0)): ?>
											<div class="d-flex justify-content-between">
												<button type="button" id="submitSendMG" class="btn btn-warning">Send <?= ((admin_has_permission('supervisor')) ? 'money' : 'gold'); ?></button>
												<button type="button" class="btn btn-sm" id="push-back-2"><< Go back</button>
											</div>
										<?php endif; ?>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
			<?php endif; ?>

            <!-- Trades -->
			<div class="card mb-6 mb-xxl-0">
				<div class="card-header">
					<div class="row align-items-center">
						<div class="col">
							<h3 class="fs-6 mb-0">Trades (<?php $tst = total_sale_amount_today($admin_id); echo money($tst["sum"]); ?>)</h3>
							<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
								<?= count_today_orders($admin_id); ?>
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
								<?= get_recent_trades($admin_id); ?>
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
						<?php if ($admin_permission == 'supervisor') : ?>
						<div class="col-auto my-n3 me-n3">
							<a class="btn btn-link" href="javascript:;" data-bs-target="#modalCapital" data-bs-toggle="modal">
							Fund coffers
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

<script>
	$(document).ready(function() {

		function delay(callback, ms) {
			var timer = 0;
			return function() {
				var context = this, args = arguments;
				clearTimeout(timer);
				timer = setTimeout(function () {
				callback.apply(context, args);
				}, ms || 0);
			};
		}

		//
		$("#push-all").change(function() {
			if (this.checked) {
				// Do stuff
				var inHand = $('#in-hand').val()
				$('#today_given').val(inHand);

				// re-check checkbox
				$( this ).prop( "checked", true );
			} else {
				$('#today_given').val('');
			}
		});

		// Calculation made with current price input
		$('#push_price').on('keyup', delay(function(e) {
            e.preventDefault();

			var current_price = $('#push_price').val();
			var gram = $('#push_gram').val();
			var volume = $('#push_volume').val();

			if (current_price != '' && current_price > 0) {
				if (gram != '' && gram > 0) {
					if (volume != '' && volume > 0) {
						$('#push_msg').text('');
						$('#show-sendMGModal').attr('disabled', false);

						$.ajax({
							url : '<?= PROOT; ?>auth/gold.calculation.php',
							method : 'POST',
							data : {
								gram : gram,
								volume : volume, 
								current_price : current_price
							},
							beforeSend : function () {
								// body...
								$('#push_msg').text('typing ...');
								$('#show-sendMGModal').attr('disabled', true);
								
							},
							success: function(data) {
								console.log(data)
								const response = JSON.parse(data);

								$("#push-result").removeClass('d-none');
								
								$('#push_density').val(response["density"] + ' Density');
								$('#push_pounds').val(response["pounds"] + ' Pounds');
								$('#push_carat').val(response["carat"] + ' Carat');
								$('#today_given').val(response["total_amount"]);

								$('#push_msg').text('');
								$('#show-sendMGModal').attr('disabled', false);
							},
							error: function() {
								return false;
							}
						})
					} else {
						$('#push_msg').text('');
					}
				}
			}
		}, 500))
		
		// Calculation made with gram input
		$('#push_gram').on('keyup', delay(function(e) {
            e.preventDefault();

			var current_price = $('#push_price').val();
			var gram = $('#push_gram').val();
			var volume = $('#push_volume').val();

			if (current_price != '' && current_price > 0) {
				if (gram != '' && gram > 0) {
					if (volume != '' && volume > 0) {
						$('#push_msg').text('');
						$('#show-sendMGModal').attr('disabled', false);

						$.ajax({
							url : '<?= PROOT; ?>auth/gold.calculation.php',
							method : 'POST',
							data : {
								gram : gram,
								volume : volume, 
								current_price : current_price
							},
							beforeSend : function () {
								// body...
								$('#push_msg').text('typing ...');
								$('#show-sendMGModal').attr('disabled', true);
							},
							success: function(data) {
								console.log(data)
								const response = JSON.parse(data);

								$("#push-result").removeClass('d-none');
								
								$('#push_density').val(response["density"] + ' Density');
								$('#push_pounds').val(response["pounds"] + ' Pounds');
								$('#push_carat').val(response["carat"] + ' Carat');
								$('#today_given').val(response["total_amount"]);

								$('#push_msg').text('');
								$('#show-sendMGModal').attr('disabled', false);
							},
							error: function() {
								return false;
							}
						})
					}
				}
			}
		}, 500))
		
		// Calculation made with volume input
		$('#push_volume').on('keyup', delay(function(e) {
            e.preventDefault();

			var current_price = $('#push_price').val();
			var gram = $('#push_gram').val();
			var volume = $('#push_volume').val();

			if (current_price != '' && current_price > 0) {
				if (volume != '' && volume > 0) {
					if (gram != '' && gram > 0) {
						$('#push_msg').text('');
						$('#show-sendMGModal').attr('disabled', false);

						$.ajax ({
							url : '<?= PROOT; ?>auth/gold.calculation.php',
							method : 'POST',
							data : {
								gram : gram,
								volume : volume, 
								current_price : current_price
							},
							beforeSend : function () {
								// body...
								$('#push_msg').text('typing ...');
								$('#show-sendMGModal').attr('disabled', true);
							},
							success: function(data) {
								console.log(data)
								const response = JSON.parse(data);
								
								$("#push-result").removeClass('d-none');

								$('#push_density').val(response["density"] + ' Density');
								$('#push_pounds').val(response["pounds"] + ' Pounds');
								$('#push_carat').val(response["carat"] + ' Carat');
								$('#today_given').val(response["total_amount"]);
								
								$('#push_msg').text('');
								$('#show-sendMGModal').attr('disabled', false);
							},
							error: function() {
								return false;
							}
						})
					}
				}
			}
		}, 500));

		// make a push
		$('#push-next-1').on('click', function() {
		
			var push_to = $("#push_to").val();
			var amount_given = $("#today_given").val();
			var balance = '<?= ((admin_has_permission('supervisor')) ? get_admin_coffers($conn, $admin_id, 'balance') : total_amount_today($admin_id)); ?>';
			var push_note = $("#push_note").val();

			if (push_to == '') {
				alert("You will have to select a <?= ((admin_has_permission('supervisor') ? 'sale person' : 'supervisor')) ;?> to proceed!");
				$("#push_to").focus()
				return false;
			}

			if (amount_given == '' || amount_given == 0) {
				alert("Invalid push amount provided");
				$("#today_given").focus()
				return false;
			}

			if (amount_given <= +balance) {

				$('#push-amount').text(amount_given);
				$('#push-to').text(push_to);
				$('#push-note').text(push_note);
				
				$('#pushSummaryModal').modal('show');

			} else {
				alert("The <?= ((admin_has_permission('supervisor')) ? 'cash in coffers' : 'gold at hand'); ?> is not enough to make this push!");
				return false;
			}
		});
		
		$('#push-next-2').on('click', function() {
			$('#pushSummaryModal').modal('hide')
			$('#sendMGModal').modal('show');
		})
		
		$('#push-back-1').on('click', function() {
			$('#pushSummaryModal').modal('hide')
			$('#sendMGModal').modal('hide');
		})
		
		$('#push-back-2').on('click', function() {
			$('#pushSummaryModal').modal('show')
			$('#push_pin').val('');

			$('#sendMGModal').modal('hide');
		})
		
		// submit send push form
		$('#submitSendMG').on('click', function() {
			if ($('#push_pin').val() == '') {
				alert("PIN is required!");
				$('#push_pin').focus()
				return false;
			} else {
				$('#submitSendMG').attr('disabled', true);
				$('#submitSendMG').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span> Pushing ...</span>');

				setTimeout(function () {
					$('#sendMGForm').submit();
				}, 2000)
			}
		})
	})
</script>
