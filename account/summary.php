<?php 

    // expenditure
    require_once ("../db_connection/conn.php");

    if (!admin_is_logged_in()) {
        admn_login_redirect();
    }

    if (admin_has_permission()) {
        redirect(PROOT . 'index');
    }
    include ("../includes/header.inc.php");
    include ("../includes/aside.inc.php");
    include ("../includes/left.nav.inc.php");
    include ("../includes/top.nav.inc.php");

    $thisYr = date("Y");
	$lastYr = $thisYr - 1;

    $thisYrQ = "
        SELECT SUM(daily_capital) AS dc, SUM(daily_balance) AS db, jspence_daily.daily_date
        FROM `jspence_daily` 
        INNER JOIN jspence_admin
        ON admin_id = jspence_daily.daily_to
        WHERE jspence_admin.admin_permissions = ?
        AND YEAR(jspence_daily.daily_date) = ? 
        AND jspence_daily.status = ? 
    ";
    $statement = $conn->prepare($thisYrQ);
    $statement->execute(['supervisor', $thisYr, 0]);
    $thisYr_result = $statement->fetchAll();
    

    $lastYrQ = "
        SELECT SUM(daily_capital) AS dc, SUM(daily_balance) AS db, jspence_daily.daily_date
        FROM `jspence_daily` 
        INNER JOIN jspence_admin
        ON admin_id = jspence_daily.daily_to
        WHERE jspence_admin.admin_permissions = ?
        AND YEAR(jspence_daily.daily_date) = ? 
        AND jspence_daily.status = ? 
    ";
    $statement = $conn->prepare($lastYrQ);
    $statement->execute(['supervisor', $lastYr, 0]);
    $lastYr_result = $statement->fetchAll();

    $current = array();
    $last = array();

    $currentTotal = 0;
    $lastTotal = 0;

    foreach ($thisYr_result as $thisYr_row) {
        $this_year_profit_amount = (float)($thisYr_row['db'] - $thisYr_row['dc']);
        if ($thisYr_row['db'] == null) {
            $this_year_profit_amount = 0;
        }
        $month = date("m", strtotime($thisYr_row['daily_date'] ?? ""));
        if (!array_key_exists((int)$month, $current)) {
            $current[(int)$month] = $this_year_profit_amount;
        } else {
            $current[(int)$month] += $this_year_profit_amount;
        }
        $currentTotal += $this_year_profit_amount;
    }

    foreach ($lastYr_result as $lastYr_row) {
        $last_year_profit_amount = (float)($lastYr_row['db'] - $lastYr_row['dc']);
        if ($lastYr_row['db'] == null) {
            $last_year_profit_amount = 0;
        }
        $month = date("m", strtotime($lastYr_row['daily_date'] ?? ""));
        if (!array_key_exists((int)$month, $last)) {
            $last[(int)$month] = $last_year_profit_amount;
        } else {
            $last[(int)$month] += $last_year_profit_amount;
        }
        $currentTotal += $last_year_profit_amount;
    }

?>


<!-- Content -->
<div class="container-lg">
        <!-- Page header -->
        <div class="row align-items-center mb-7">
            <div class="col-auto">
                <!-- Avatar -->
                <div class="avatar avatar-xl rounded text-warning">
                    <i class="fs-2" data-duoicon="slideshow"></i>
                </div>
            </div>
            <div class="col">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a class="text-body-secondary" href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Market</li>
                    </ol>
                </nav>

                <!-- Heading -->
                <h1 class="fs-4 mb-0">Summary</h1>
            </div>
            <div class="col-12 col-sm-auto mt-4 mt-sm-0">
                <!-- Action -->
                <a class="btn btn-light d-block" href="<?= PROOT; ?>"> <span class="material-symbols-outlined me-1">dashboard</span> Go to dashboard </a>
            </div>
        </div>

        <!-- Page content -->
        <div class="row">
            <div class="col-12">
                <!-- Filters -->
                <div class="card card-line bg-body-tertiary border-transparent mb-7">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-12 col-lg-auto mb-3 mb-lg-0">
                                <ul class="nav nav-pills">
                                    <li class="nav-item">
                                        <a class="nav-link bg-dark active" aria-current="page" href="<?= PROOT; ?>account/analytics">Data</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-12 col-lg">
                                <div class="row gx-3">
                                    <div class="col col-lg-auto ms-auto">
                                        <?= date('l, F jS, Y', strtotime(date("Y-m-d"))); ?>
                                    </div>
                                    <div class="col-auto">
                                        Ghana, GH –&nbsp;<time datetime="20:00" id="time_span"></time>
                                    </div>
                                    <div class="col-auto ms-n2">
                                        <a href="<?= PROOT . 'account/analytics' ?>" class="btn btn-dark px-3">
                                            <span class="material-symbols-outlined">reset_wrench</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            
            <div class="row">
                <div class="col">
                <div class="vstack gap-3 gap-xl-6">
                    <div class="row g-3">
                        <div class="col-12 col-md-6 mb-4 mb-xxl-0">
                            <div class="card bg-body-tertiary border-transparent">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <!-- Heading -->
                                            <h4 class="fs-base fw-normal text-body-secondary mb-1">Capital</h4>

                                            <!-- Text -->
                                            <div class="fs-5 fw-semibold" id="sup-capital">0.00</div>
                                            <p class="mt-1">
                                                <span class="text-success text-xs"><i class="fas fa-arrow-up me-1"></i>Balance: </span>
                                                <span class="text-muted text-xs text-opacity-75" id="sup-balance">0.00</span>
                                            </p>
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

                        <div class="col-12 col-md-6 mb-4 mb-xxl-0">
                            <div class="card bg-body-tertiary border-transparent">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <!-- Heading -->
                                            <h4 class="fs-base fw-normal text-body-secondary mb-1">Balance</h4>

                                            <!-- Text -->
                                            <div class="fs-5 fw-semibold" id="sal-capital">0.00</div>
                                        </div>
                                        <div class="col-auto">
                                            <!-- Avatar -->
                                            <div class="avatar avatar-lg bg-body text-warning">
                                                <i class="fs-4" data-duoicon="credit-card"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-4 mb-xxl-0">
                            <div class="card bg-body-tertiary border-transparent">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <!-- Heading -->
                                            <h4 class="fs-base fw-normal text-body-secondary mb-1">Trades</h4>

                                            <!-- Text -->
                                            <div class="fs-5 fw-semibold" id="expenses">0.00</div>
                                            <p class="mt-1">
                                                <span class="text-success text-xs"><i class="fas fa-arrow-up me-1"></i>Count: </span>
                                                <span class="text-muted text-xs text-opacity-75" id="sup-balance">0</span>
                                            </p>
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

                        <div class="col-12 col-md-6 mb-4 mb-xxl-0">
                            <div class="card bg-body-tertiary border-transparent">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <!-- Heading -->
                                            <h4 class="fs-base fw-normal text-body-secondary mb-1">Deleted Trades</h4>

                                            <!-- Text -->
                                            <div class="fs-5 fw-semibold" id="expenses">0.00</div>
                                            <p class="mt-1">
                                                <span class="text-success text-xs"><i class="fas fa-arrow-up me-1"></i>Count: </span>
                                                <span class="text-muted text-xs text-opacity-75" id="sup-balance">0</span>
                                            </p>
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

                        <div class="col-12 col-md-6 mb-4 mb-xxl-0">
                            <div class="card bg-body-tertiary border-transparent">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <!-- Heading -->
                                            <h4 class="fs-base fw-normal text-body-secondary mb-1">Expenses</h4>

                                            <!-- Text -->
                                            <div class="fs-5 fw-semibold" id="expenses">0.00</div>
                                            <p class="mt-1">
                                                <span class="text-success text-xs"><i class="fas fa-arrow-up me-1"></i>Count: </span>
                                                <span class="text-muted text-xs text-opacity-75" id="sup-balance">0</span>
                                            </p>
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

                        <div class="col-12 col-md-6 mb-4 mb-xxl-0">
                            <div class="card bg-body-tertiary border-transparent">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <!-- Heading -->
                                            <h4 class="fs-base fw-normal text-body-secondary mb-1">Deleted Expenses</h4>

                                            <!-- Text -->
                                            <div class="fs-5 fw-semibold" id="expenses">0.00</div>
                                            <p class="mt-1">
                                                <span class="text-success text-xs"><i class="fas fa-arrow-up me-1"></i>Count: </span>
                                                <span class="text-muted text-xs text-opacity-75" id="sup-balance">0</span>
                                            </p>
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

                        <div class="col-12 col-md-6 col-xxl-4 mb-4 mb-xxl-0">
                            <div class="card bg-body-tertiary border-transparent">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <!-- Heading -->
                                            <h4 class="fs-base fw-normal text-body-secondary mb-1">Total Pushes (Gold/Money)</h4>

                                            <!-- Text -->
                                            <div class="fs-5 fw-semibold" id="total-trades">0.00</div>
                                            <p class="mt-1">
                                                <span class="text-success text-xs"><i class="fas fa-arrow-up me-1"></i>Count: </span>
                                                <span class="text-muted text-xs text-opacity-75" id="">0</span>
                                            </p>
                                        </div>
                                        <div class="col-auto">
                                            <!-- Avatar -->
                                            <div class="avatar avatar-lg bg-body text-warning">
                                                <i class="fs-4" data-duoicon="bell-badge"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-xxl-4 mb-4 mb-xxl-0">
                            <div class="card bg-body-tertiary border-transparent">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <!-- Heading -->
                                            <h4 class="fs-base fw-normal text-body-secondary mb-1">Send Pushes (Gold/Money)</h4>

                                            <!-- Text -->
                                            <div class="fs-5 fw-semibold" id="total-trades">0.00</div>
                                            <p class="mt-1">
                                                <span class="text-success text-xs"><i class="fas fa-arrow-up me-1"></i>Count: </span>
                                                <span class="text-muted text-xs text-opacity-75" id="">0</span>
                                            </p>
                                        </div>
                                        <div class="col-auto">
                                            <!-- Avatar -->
                                            <div class="avatar avatar-lg bg-body text-warning">
                                                <i class="fs-4" data-duoicon="bell-badge"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-xxl-4 mb-4 mb-xxl-0">
                            <div class="card bg-body-tertiary border-transparent">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <!-- Heading -->
                                            <h4 class="fs-base fw-normal text-body-secondary mb-1">Receive Pushes (Gold/Money)</h4>

                                            <!-- Text -->
                                            <div class="fs-5 fw-semibold" id="total-trades">0.00</div>
                                            <p class="mt-1">
                                                <span class="text-success text-xs"><i class="fas fa-arrow-up me-1"></i>Count: </span>
                                                <span class="text-muted text-xs text-opacity-75" id="">0</span>
                                            </p>
                                        </div>
                                        <div class="col-auto">
                                            <!-- Avatar -->
                                            <div class="avatar avatar-lg bg-body text-warning">
                                                <i class="fs-4" data-duoicon="bell-badge"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="row g-3">
                        <div class="col-6 col-md-6 mb-4 mb-xxl-0">
                            <div class="card bg-body-tertiary border-transparent">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <!-- Heading -->
                                            <h4 class="fs-base fw-normal text-body-secondary mb-1">Logs</h4>

                                            <!-- Text -->
                                            <div class="fs-5 fw-semibold" id="profit-loss">0</div>
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
                        <div class="col-6 col-md-6 mb-4 mb-xxl-0">
                            <div class="card bg-body-tertiary border-transparent">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <!-- Heading -->
                                            <h4 class="fs-base fw-normal text-body-secondary mb-1">Logged In datetime</h4>

                                            <!-- Text -->
                                            <div class="fs-5 fw-semibold" id="total-pushes">12:11 9 PM</div>
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
                    </div>
                    
                    <div class="row mb-8">
                        <div class="col-12 col-md-6 col-xxl mb-4 mb-xxl-0">
                            <div class="card bg-body-tertiary border-transparent">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <!-- Heading -->
                                            <h4 class="fs-sm fw-normal text-body-secondary mb-1">Gram</h4>

                                            <!-- Text -->
                                            <div class="fs-4 fw-semibold" id="total-gram">0</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-xxl mb-4 mb-xxl-0">
                            <div class="card bg-body-tertiary border-transparent">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <!-- Heading -->
                                            <h4 class="fs-sm fw-normal text-body-secondary mb-1">Volume</h4>

                                            <!-- Text -->
                                            <div class="fs-4 fw-semibold" id="total-volume">0</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-xxl mb-4 mb-xxl-0">
                            <div class="card bg-body-tertiary border-transparent">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <!-- Heading -->
                                            <h4 class="fs-sm fw-normal text-body-secondary mb-1">Density</h4>

                                            <!-- Text -->
                                            <div class="fs-4 fw-semibold" id="total-density">0</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-xxl mb-4 mb-xxl-0">
                            <div class="card bg-body-tertiary border-transparent">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <!-- Heading -->
                                            <h4 class="fs-sm fw-normal text-body-secondary mb-1">Pounds</h4>

                                            <!-- Text -->
                                            <div class="fs-4 fw-semibold" id="total-pounds">0</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-xxl mb-md-0">
                            <div class="card bg-body-tertiary border-transparent">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <!-- Heading -->
                                            <h4 class="fs-sm fw-normal text-body-secondary mb-1">Carat</h4>

                                            <!-- Text -->
                                            <div class="fs-4 fw-semibold" id="total-carat">0</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body pb-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5>Earnings chart</h5>
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

                </div>
            </div>
        </div>
    </div>


<?php include ("../includes/footer.inc.php"); ?>
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
