<?php 
    require_once ("db_connection/conn.php");

    include ("includes/header.inc.php");
    include ("includes/aside.inc.php");
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
            	<div class="avatar text-info me-2">
              		<i class="fs-4" data-duoicon="world"></i>
            	</div>
				Ghana, GH –&nbsp;<time datetime="20:00">8:00 PM</time>
			</div>
			<div class="col-12 col-md order-md-0 text-center text-md-start">
				<?= $flash; ?>
				<h1>Hello, <?= $admin_data['first']; ?></h1>
				<p class="fs-lg text-body-secondary mb-0">Here's a summary of your account activity for this week.</p>
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
								<h4 class="fs-base fw-normal text-body-secondary mb-1">Earned</h4>

								<!-- Text -->
								<div class="fs-5 fw-semibold">$1,250</div>
							</div>
							<div class="col-auto">
								<!-- Avatar -->
								<div class="avatar avatar-lg bg-body text-primary">
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
								<h4 class="fs-base fw-normal text-body-secondary mb-1">Hours logged</h4>

								<!-- Text -->
								<div class="fs-5 fw-semibold">35.5 hrs</div>
							</div>
							<div class="col-auto">
								<!-- Avatar -->
								<div class="avatar avatar-lg bg-body text-primary">
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
							<div class="col">
								<!-- Heading -->
								<h4 class="fs-base fw-normal text-body-secondary mb-1">Tasks pending</h4>

								<!-- Text -->
								<div class="fs-5 fw-semibold">19</div>
							</div>
							<div class="col-auto">
								<!-- Avatar -->
								<div class="avatar avatar-lg bg-body text-primary">
								<i class="fs-4" data-duoicon="bell"></i>
								</div>
							</div>
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
									<h4 class="fs-base fw-normal text-body-secondary mb-1">Projects</h4>

									<!-- Text -->
									<div class="fs-5 fw-semibold">12</div>
								</div>
								<div class="col-auto">
									<!-- Avatar -->
									<div class="avatar avatar-lg bg-body text-primary">
										<i class="fs-4" data-duoicon="clipboard"></i>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>


			
        <div class="row">
          <div class="col-12 col-xxl-8">
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
                  <canvas class="chart-canvas" id="performanceChart"></canvas>
                </div>
              </div>
            </div>

            <!-- Projects -->
            <div class="card mb-6 mb-xxl-0">
              <div class="card-header">
                <div class="row align-items-center">
                  <div class="col">
                    <h3 class="fs-6 mb-0">Active projects</h3>
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
                      <tr>
                        <td>
                          <div class="d-flex align-items-center">
                            <div class="avatar">
                              <img class="avatar-img" src="./assets/img/projects/project-1.png" alt="..." />
                            </div>
                            <div class="ms-4">
                              <div>Filters AI</div>
                              <div class="fs-sm text-body-secondary">Updated on Apr 10, 2024</div>
                            </div>
                          </div>
                        </td>
                        <td>
                          <span class="badge bg-success-subtle text-success">Ready to Ship</span>
                        </td>
                        <td>
                          <div class="d-flex align-items-center text-nowrap">
                            <div class="avatar avatar-xs me-2">
                              <img class="avatar-img" src="./assets/img/photos/photo-2.jpg" alt="..." />
                            </div>
                            Michael Johnson
                          </div>
                        </td>
                        <td>
                          <div class="avatar-group">
                            <div class="avatar avatar-xs">
                              <img class="avatar-img" src="./assets/img/photos/photo-2.jpg" alt="..." />
                            </div>
                            <div class="avatar avatar-xs">
                              <img class="avatar-img" src="./assets/img/photos/photo-3.jpg" alt="..." />
                            </div>
                            <div class="avatar avatar-xs">
                              <img class="avatar-img" src="./assets/img/photos/photo-4.jpg" alt="..." />
                            </div>
                            <div class="avatar avatar-xs">
                              <img class="avatar-img" src="./assets/img/photos/photo-5.jpg" alt="..." />
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <div class="d-flex align-items-center">
                            <div class="avatar">
                              <img class="avatar-img" src="./assets/img/projects/project-2.png" alt="..." />
                            </div>
                            <div class="ms-4">
                              <div>Design landing page</div>
                              <div class="fs-sm text-body-secondary">Created on Mar 05, 2024</div>
                            </div>
                          </div>
                        </td>
                        <td>
                          <span class="badge bg-danger-subtle text-danger">Cancelled</span>
                        </td>
                        <td>
                          <div class="d-flex align-items-center text-nowrap">
                            <div class="avatar avatar-xs me-2">
                              <img class="avatar-img" src="./assets/img/photos/photo-1.jpg" alt="..." />
                            </div>
                            Emily Thompson
                          </div>
                        </td>
                        <td>
                          <div class="avatar-group">
                            <div class="avatar avatar-xs">
                              <img class="avatar-img" src="./assets/img/photos/photo-4.jpg" alt="..." />
                            </div>
                            <div class="avatar avatar-xs">
                              <img class="avatar-img" src="./assets/img/photos/photo-5.jpg" alt="..." />
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <div class="d-flex align-items-center">
                            <div class="avatar text-primary">
                              <i class="fs-4" data-duoicon="book-3"></i>
                            </div>
                            <div class="ms-4">
                              <div>Update documentation</div>
                              <div class="fs-sm text-body-secondary">Created on Jan 22, 2024</div>
                            </div>
                          </div>
                        </td>
                        <td>
                          <span class="badge bg-secondary-subtle text-secondary">In Testing</span>
                        </td>
                        <td>
                          <div class="d-flex align-items-center text-nowrap">
                            <div class="avatar avatar-xs me-2">
                              <img class="avatar-img" src="./assets/img/photos/photo-2.jpg" alt="..." />
                            </div>
                            Michael Johnson
                          </div>
                        </td>
                        <td>
                          <div class="avatar-group">
                            <div class="avatar avatar-xs">
                              <img class="avatar-img" src="./assets/img/photos/photo-1.jpg" alt="..." />
                            </div>
                            <div class="avatar avatar-xs">
                              <img class="avatar-img" src="./assets/img/photos/photo-3.jpg" alt="..." />
                            </div>
                            <div class="avatar avatar-xs">
                              <img class="avatar-img" src="./assets/img/photos/photo-6.jpg" alt="..." />
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <div class="d-flex align-items-center">
                            <div class="avatar">
                              <img class="avatar-img" src="./assets/img/projects/project-3.png" alt="..." />
                            </div>
                            <div class="ms-4">
                              <div>Update Touche</div>
                              <div class="fs-sm text-body-secondary">Updated on Apr 14, 2024</div>
                            </div>
                          </div>
                        </td>
                        <td>
                          <span class="badge bg-success-subtle text-success">Ready to Ship</span>
                        </td>
                        <td>
                          <div class="d-flex align-items-center text-nowrap">
                            <div class="avatar avatar-xs me-2">
                              <img class="avatar-img" src="./assets/img/photos/photo-5.jpg" alt="..." />
                            </div>
                            Jessica Miller
                          </div>
                        </td>
                        <td>
                          <div class="avatar-group">
                            <div class="avatar avatar-xs">
                              <img class="avatar-img" src="./assets/img/photos/photo-3.jpg" alt="..." />
                            </div>
                            <div class="avatar avatar-xs">
                              <img class="avatar-img" src="./assets/img/photos/photo-4.jpg" alt="..." />
                            </div>
                            <div class="avatar avatar-xs">
                              <img class="avatar-img" src="./assets/img/photos/photo-5.jpg" alt="..." />
                            </div>
                            <div class="avatar avatar-xs">
                              <img class="avatar-img" src="./assets/img/photos/photo-6.jpg" alt="..." />
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <div class="d-flex align-items-center">
                            <div class="avatar text-primary">
                              <i class="fs-4" data-duoicon="box"></i>
                            </div>
                            <div class="ms-4">
                              <div>Add Transactions</div>
                              <div class="fs-sm text-body-secondary">Created on Apr 25, 2024</div>
                            </div>
                          </div>
                        </td>
                        <td>
                          <span class="badge bg-light text-body-secondary">Backlog</span>
                        </td>
                        <td>
                          <div class="d-flex align-items-center text-nowrap">
                            <div class="avatar avatar-xs me-2">
                              <img class="avatar-img" src="./assets/img/photos/photo-4.jpg" alt="..." />
                            </div>
                            Olivia Davis
                          </div>
                        </td>
                        <td>
                          <div class="avatar-group">
                            <div class="avatar avatar-xs">
                              <img class="avatar-img" src="./assets/img/photos/photo-3.jpg" alt="..." />
                            </div>
                            <div class="avatar avatar-xs">
                              <img class="avatar-img" src="./assets/img/photos/photo-6.jpg" alt="..." />
                            </div>
                            <div class="avatar avatar-xs">
                              <img class="avatar-img" src="./assets/img/photos/photo-1.jpg" alt="..." />
                            </div>
                          </div>
                        </td>
                      </tr>
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
                <h3 class="fs-6 mb-0">Goals</h3>
              </div>
              <div class="card-body py-3">
                <div class="list-group list-group-flush">
                  <div class="list-group-item px-0">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <div class="avatar">
                          <div
                            class="progress progress-circle text-primary"
                            role="progressbar"
                            aria-label="Increase monthly revenue"
                            aria-valuenow="75"
                            aria-valuemin="0"
                            aria-valuemax="100"
                            data-bs-toggle="tooltip"
                            data-bs-title="75%"
                            style="--bs-progress-circle-value: 75"
                          ></div>
                        </div>
                      </div>
                      <div class="col ms-n2">
                        <h6 class="fs-base fw-normal mb-1">Increase monthly revenue</h6>
                        <span class="fs-sm text-body-secondary">$10,000</span>
                      </div>
                      <div class="col-auto">
                        <time class="text-body-secondary" datetime="03/15/2024">Mar 15</time>
                      </div>
                    </div>
                  </div>
                  <div class="list-group-item px-0">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <div class="avatar">
                          <div
                            class="progress progress-circle text-secondary"
                            role="progressbar"
                            aria-label="Launch new feature"
                            aria-valuenow="50"
                            aria-valuemin="0"
                            aria-valuemax="100"
                            data-bs-toggle="tooltip"
                            data-bs-title="50%"
                            style="--bs-progress-circle-value: 50"
                          ></div>
                        </div>
                      </div>
                      <div class="col ms-n2">
                        <h6 class="fs-base fw-normal mb-1">Launch new feature</h6>
                        <span class="fs-sm text-body-secondary">Dark mode</span>
                      </div>
                      <div class="col-auto">
                        <time class="text-body-secondary" datetime="10/01/2024">Oct 01</time>
                      </div>
                    </div>
                  </div>
                  <div class="list-group-item px-0">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <div class="avatar">
                          <div
                            class="progress progress-circle text-danger"
                            role="progressbar"
                            aria-label="Grow user base"
                            aria-valuenow="45"
                            aria-valuemin="0"
                            aria-valuemax="100"
                            data-bs-toggle="tooltip"
                            data-bs-title="45%"
                            style="--bs-progress-circle-value: 45"
                          ></div>
                        </div>
                      </div>
                      <div class="col ms-n2">
                        <h6 class="fs-base fw-normal mb-1">Grow user base</h6>
                        <span class="fs-sm text-body-secondary">75%</span>
                      </div>
                      <div class="col-auto">
                        <time class="text-body-secondary" datetime="12/12/2024">Dec 12</time>
                      </div>
                    </div>
                  </div>
                  <div class="list-group-item px-0">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <div class="avatar">
                          <div
                            class="progress progress-circle text-primary"
                            role="progressbar"
                            aria-label="Improve customer satisfaction"
                            aria-valuenow="60"
                            aria-valuemin="0"
                            aria-valuemax="100"
                            data-bs-toggle="tooltip"
                            data-bs-title="60%"
                            style="--bs-progress-circle-value: 60"
                          ></div>
                        </div>
                      </div>
                      <div class="col ms-n2">
                        <h6 class="fs-base fw-normal mb-1">Improve customer satisfaction</h6>
                        <span class="fs-sm text-body-secondary">85%</span>
                      </div>
                      <div class="col-auto">
                        <time class="text-body-secondary" datetime="12/15/2024">Dec 15</time>
                      </div>
                    </div>
                  </div>
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
                        <h6 class="fs-base fw-normal mb-1">Reduce response time</h6>
                        <span class="fs-sm text-body-secondary">1hr</span>
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
                  <li data-icon="thumb_up">
                    <div>
                      <h6 class="fs-base mb-1">You <span class="fs-sm fw-normal text-body-secondary ms-1">1hr ago</span></h6>
                      <p class="mb-0">Liked a post by @john_doe</p>
                    </div>
                  </li>
                  <li data-icon="chat_bubble">
                    <div>
                      <h6 class="fs-base mb-1">Jessica Miller <span class="fs-sm fw-normal text-body-secondary ms-1">3hr ago</span></h6>
                      <p class="mb-0">Commented on a photo</p>
                    </div>
                  </li>
                  <li data-icon="share">
                    <div>
                      <h6 class="fs-base mb-1">Emily Thompson <span class="fs-sm fw-normal text-body-secondary ms-1">3hr ago</span></h6>
                      <p class="mb-0">Shared an article: "Top 10 Travel Destinations"</p>
                    </div>
                  </li>
                  <li data-icon="person_add">
                    <div>
                      <h6 class="fs-base mb-1">You <span class="fs-sm fw-normal text-body-secondary ms-1">1 day ago</span></h6>
                      <p class="mb-0">Started following @jane_smith</p>
                    </div>
                  </li>
                  <li data-icon="account_circle">
                    <div>
                      <h6 class="fs-base mb-1">Olivia Davis <span class="fs-sm fw-normal text-body-secondary ms-1">2 days ago</span></h6>
                      <p class="mb-0">Updated profile picture</p>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>


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
					
					<!-- Seeting for todays capital -->
					 <?php if (!admin_has_permission()): ?>
					<div class="modal fade" id="modalCapital" tabindex="-1" aria-labelledby="modalCapital" aria-hidden="true" style="backdrop-filter: blur(5px);">
						<div class="modal-dialog modal-dialog-centered">
							<div class="modal-content shadow-3">
								<div class="modal-header justify-content-start">
									<div class="icon icon-shape rounded-3 bg-primary-subtle text-primary text-lg me-4">
										<i class="bi bi-currency-exchange"></i>
									</div>
									<div>
										<h5 class="mb-1">Today's Capital</h5>
										<small class="d-block text-xs text-muted">You are to give todays capital before you can start trade.</small>
									</div>
								</div>
								<form action="" method="POST" id="capitalForm">
									<div class="modal-body">
										<div class="mb-3">
											<label class="form-label">Today's Date</label> 
											<input class="form-control" name="today_date" id="today_date" type="date" value="<?php echo date('Y-m-d'); ?>">
										</div>
										<div class="">
											<label class="form-label">Amount given</label> 
											<input class="form-control" placeholder="0.00" name="today_given" id="today_given" type="number" min="0.00" step="0.01" value="<?= (is_capital_given() ? _capital()['today_capital'] : '' ); ?>">
										</div>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-sm btn-neutral" data-bs-dismiss="modal">Close</button> 
										<button type="submit" id="submitCapital" class="btn btn-sm btn-primary">Save</button>
									</div>
								</form>
							</div>
						</div>
					</div>
					<?php endif; ?>


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
