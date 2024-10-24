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
                                        <input type="date" id="dater" class="form-control bg-body">
                                    </div>
                                    <div class="col-auto">
                                        <select id='use-month' class="form-control bg-body">
                                            <option value=''>Select Month</option>
                                            <option value="1">January</option>
                                            <option value='2'>February</option>
                                            <option value='3'>March</option>
                                            <option value='4'>April</option>
                                            <option value='5'>May</option>
                                            <option value='6'>June</option>
                                            <option value='7'>July</option>
                                            <option value='8'>August</option>
                                            <option value='9'>September</option>
                                            <option value='10'>October</option>
                                            <option value='11'>November</option>
                                            <option value='12'>December</option>
                                        </select>
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
                        <div class="col-12 col-md-6 col-xxl-3 mb-4 mb-xxl-0">
                            <div class="card bg-body-tertiary border-transparent">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <!-- Heading -->
                                            <h4 class="fs-base fw-normal text-body-secondary mb-1">Capital</h4>

                                            <!-- Text -->
                                            <div class="fs-5 fw-semibold" id="sup-capital"></div>
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

                        <div class="col-12 col-md-6 col-xxl-3 mb-4 mb-xxl-0">
                            <div class="card bg-body-tertiary border-transparent">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <!-- Heading -->
                                            <h4 class="fs-base fw-normal text-body-secondary mb-1">Balance</h4>

                                            <!-- Text -->
                                            <div class="fs-5 fw-semibold" id="sal-capital"></div>
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

                        <div class="col-12 col-md-6 col-xxl-3 mb-4 mb-xxl-0">
                            <div class="card bg-body-tertiary border-transparent">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <!-- Heading -->
                                            <h4 class="fs-base fw-normal text-body-secondary mb-1">Expenses</h4>

                                            <!-- Text -->
                                            <div class="fs-5 fw-semibold" id="expenses"></div>
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

                        <div class="col-12 col-md-6 col-xxl-3 mb-4 mb-xxl-0">
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

                        <div class="col-12 col-md-6 col-xxl-3 mb-4 mb-xxl-0">
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

                        <div class="col-12 col-md-6 col-xxl-3 mb-4 mb-xxl-0">
                            <div class="card bg-body-tertiary border-transparent">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <!-- Heading -->
                                            <h4 class="fs-base fw-normal text-body-secondary mb-1">Receive Pushes (Gold/Money)</h4>

                                            <!-- Text -->
                                            <div class="fs-5 fw-semibold" id="total-trades"></div>
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
                        <div class="col-6 col-md-8 col-xxl-10 mb-4 mb-xxl-0">
                            <div class="card bg-body-tertiary border-transparent">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <!-- Heading -->
                                            <h4 class="fs-base fw-normal text-body-secondary mb-1">Earnings</h4>

                                            <!-- Text -->
                                            <div class="fs-5 fw-semibold" id="profit-loss">0.00</div>
                                        </div>
                                        <div class="col-auto">
                                            <!-- Avatar -->
                                            <div class="avatar avatar-lg bg-body text-warning">
                                                <i class="fs-4" data-duoicon="credit-card"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between mt-8">
                                        <div class="">
                                            <div class="d-flex gap-3 align-items-center">
                                                <span class="text-success text-xs">Incoming (supervisor)</span>
                                            </div>
                                            <div class="text-muted text-xs text-opacity-75 mt-3" id="incoming">0.00 GHS</div>
                                        </div>
                                        <div class="">
                                            <div class="d-flex gap-3 align-items-center">
                                                <span class="text-danger text-xs">Outgoing (salesperson)</span>
                                            </div>
                                            <div class="text-muted text-xs text-opacity-75 mt-3" id="outgoing">0.00 GHS</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-4 col-xxl-2 mb-4 mb-xxl-0">
                            <div class="card bg-body-tertiary border-transparent">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <!-- Heading -->
                                            <h4 class="fs-base fw-normal text-body-secondary mb-1">Total pushes</h4>

                                            <!-- Text -->
                                            <div class="fs-5 fw-semibold" id="total-pushes"></div>
                                            <p class="mt-1">
                                                <span class="text-success text-xs"><i class="fas fa-arrow-up me-1"></i> </span>
                                                <span class="text-muted text-xs text-opacity-75" id=""></span>
                                            </p>
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
                                            <div class="fs-4 fw-semibold" id="total-carat"></div>
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
	                text: 'Earnings By Month - J-Spence LTD.'
	            }
	        }
	    })
	})()
</script>

