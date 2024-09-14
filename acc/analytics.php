<?php 

    // expenditure
    require_once ("../db_connection/conn.php");

    if (!admin_is_logged_in()) {
        admn_login_redirect();
    }

    if ($admin_data[0]['admin_permissions'] == 'supervisor') {
        redirect(PROOT . 'index');
    }

    include ("../includes/header.inc.php");
    include ("../includes/nav.inc.php");

    $al = analytics_left();

    $thisYr = date("Y");
	$lastYr = $thisYr - 1;

    $thisYrQ = "
        SELECT SUM(daily_capital) AS dc, SUM(daily_balance) AS db, jspence_daily.daily_date
        FROM `jspence_daily` 
        INNER JOIN jspence_admin
        ON admin_id = jspence_daily.daily_by
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
        ON admin_id = jspence_daily.daily_by
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
        $this_year_profit_amount = (float)($thisYr_row['dc'] - $thisYr_row['db']); 
        $month = date("m", strtotime($thisYr_row['daily_date'] ?? ""));
        if (!array_key_exists((int)$month, $current)) {
            $current[(int)$month] = $this_year_profit_amount;
        } else {
            $current[(int)$month] += $this_year_profit_amount;
        }
        $currentTotal += $this_year_profit_amount;
    }

    foreach ($lastYr_result as $lastYr_row) {
        $last_year_profit_amount = (float)($lastYr_row['dc'] - $lastYr_row['db']); 
        $month = date("m", strtotime($lastYr_row['daily_date'] ?? ""));
        if (!array_key_exists((int)$month, $last)) {
            $last[(int)$month] = $last_year_profit_amount;
        } else {
            $last[(int)$month] += $last_year_profit_amount;
        }
        $currentTotal += $last_year_profit_amount;
    }

?>

<div class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
    <main class="container-fluid px-3 py-5 p-lg-6 p-xxl-8 ">
        <div class="mb-6 mb-xl-10">
            <div class="row g-3 align-items-center">
                <div class="col">
                    <h1 class="ls-tight">Analytics</h1>
                </div>
                <div class="col">
                    <div class="hstack gap-2 justify-content-end">
                        <div class="d-flex scrollable-x justify-content-between gap-1 p-1 align-items-center bg-body-secondary rounded text-xs fw-semibold">
                            <input type="date" id="dater" class="px-3 py-1 text-muted">
                            <select id='use-month' class="px-3 py-1 text-muted">
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
                        <a href="<?= PROOT . 'acc/analytics' ?>" class="btn btn-sm btn-neutral d-sm-inline-flex"><span class="pe-2"><i class="bi bi-arrow-clockwise"></i> </span><span>Reset</span></a>
                            <!-- <div class="input-group input-group-inline datepicker">
                                <span class="input-group-text pe-2"><i class="bi bi-calendar"></i> </span>
                                <input type="text" class="form-control" placeholder="Select date" data-input="data-input">
                            </div> -->
                    </div>
                </div>
            </div>
        </div>

        <div class="vstack gap-3 gap-xl-6">
            <div class="row g-3">
                <div class="col">
                    <div class="card">
                        <div class="p-4">
                            <h6 class="text-limit text-muted mb-3">Supervisor</h6>
                            <span class="text-muted text-opacity-60 text-opacity-100-hover" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-placement="right" data-bs-html="true" data-bs-content="The total capital given out to supervisors and Balance remaining, which can be determined as profit or loss." role="button"><i class="bi bi-info-circle"></i></span>
                            <span class="text-sm text-muted text-opacity-90 fw-semibold">GHS</span> <span class="d-block h3 ls-tight fw-bold" id="sup-capital">0.00</span>
                            <p class="mt-1">
                                <span class="text-success text-xs"><i class="fas fa-arrow-up me-1"></i>Balance </span>
                                <span class="text-muted text-xs text-opacity-75" id="sup-balance">0.00</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card">
                        <div class="p-4">
                            <h6 class="text-limit text-muted mb-3">Salespersonnel</h6>
                            <span class="text-muted text-opacity-60 text-opacity-100-hover" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-placement="right" data-bs-html="true" data-bs-content="The total capital given out to salespersonnels and Balance remaining." role="button"><i class="bi bi-info-circle"></i></span>
                            <span class="text-sm text-muted text-opacity-90 fw-semibold">GHS</span> <span class="d-block h3 ls-tight fw-bold" id="sal-capital">0.00</span>
                            <p class="mt-1">
                                <span class="text-success text-xs"><i class="fas fa-arrow-up me-1"></i>Balance </span>
                                <span class="text-muted text-xs text-opacity-75" id="sal-balance">0.00</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card">
                        <div class="p-4">
                            <h6 class="text-limit text-muted mb-3">Expenses</h6>
                            <span class="text-muted text-opacity-60 text-opacity-100-hover" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-placement="right" data-bs-html="true" data-bs-content="The total expenses made." role="button"><i class="bi bi-info-circle"></i></span>
                            <span class="text-sm text-muted text-opacity-90 fw-semibold">GHS</span> <span class="d-block h3 ls-tight fw-bold" id="expenses">0.00</span>
                            <p class="mt-1">
                                <span class="text-success text-xs"><i class="fas fa-arrow-up me-1"></i>20% </span>
                                <span class="text-muted text-xs text-opacity-75">vs last week</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card">
                        <div class="p-4">
                            <h6 class="text-limit text-muted mb-3">Total trades</h6>
                            <span class="text-muted text-opacity-60 text-opacity-100-hover" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-placement="right" data-bs-html="true" data-bs-content="The total number of trade(s) made." role="button"><i class="bi bi-info-circle"></i></span>
                            <span class="text-sm text-muted text-opacity-90 fw-semibold">#</span> <span class="d-block h3 ls-tight fw-bold" id="total-trades">0.00</span>
                            <p class="mt-1">
                                <span class="text-success text-xs"><i class="fas fa-arrow-up me-1"></i>20% </span>
                                <span class="text-muted text-xs text-opacity-75">vs last week</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5>Earnings<!-- Balance --></h5>
                    </div>
                    <div>
                        <span class="text-heading fw-bold"><i class="bi bi-arrow-up me-2"></i>7.8%</span></div>
                    </div>
                    <div class="text-2xl fw-bolder text-heading ls-tight" id="profit-loss">0.00 GHS</div>
                    <div class="d-flex align-items-center justify-content-between mt-8">
                    <div class="">
                        <div class="d-flex gap-3 align-items-center">
                        <div class="icon icon-sm icon-shape text-sm rounded-circle bg-dark text-success"><i class="bi bi-arrow-down"></i></div><span class="h6 fw-semibold text-muted">Incoming</span> <span class="text-muted text-opacity-60 text-opacity-100-hover" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-placement="right" data-bs-html="true" data-bs-content="The total amount of trades comming in done by supervisors" role="button"><i class="bi bi-info-circle"></i></span>
                        </div>
                        <div class="fw-bold text-heading mt-3" id="incoming">0.00 GHS</div>
                    </div><span class="vr bg-dark bg-opacity-10"></span>
                    <div class="">
                        <div class="d-flex gap-3 align-items-center">
                        <div class="icon icon-sm icon-shape text-sm rounded-circle bg-dark text-danger"><i class="bi bi-arrow-up"></i></div><span class="h6 fw-semibold text-muted">Outgoing</span> <span class="text-muted text-opacity-60 text-opacity-100-hover" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-placement="right" data-bs-html="true" data-bs-content="The total amount of trades going out done by salespersonnels" role="button"><i class="bi bi-info-circle"></i></span>
                        </div>
                        <div class="fw-bold text-heading mt-3" id="outgoing">0.00 GHS</div>
                    </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5>Earnings</h5>
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
                            <h5>Accumulated profits by months and years</h5>
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
        </div>
    </main>
</div>


<?php include ("../includes/footer.inc.php"); ?>
<script type="text/javascript" src="<?= PROOT; ?>dist/js/Chart.min.js"></script>
<script>
    $(document).ready(function() {
        $('#submitExpenditure').on('click', function() {
            if (confirm("By clicking on ok, this expenditure will be recorded!")) {
                expenditureForm.submit()
            }
            return false
        });

        var dater = null;
        var action = '';
        
        $('#dater').on('change', function() {
            dater = this.value;
            action = 'with_date'
            $('#use-month').val("");
            analytics(dater, action)
        });

        $('#use-month').change(function(){
            dater = $(this).val();
            action = "with_month"
            $('#dater').val("");
            analytics(dater, action)
        });

        function analytics(dater, action) {
            $.ajax({
                method: "POST",
                url: "<?= PROOT; ?>auth/analytics.info.php",
                data: {
                    dater : dater,
                    action : action
                },
                beforeSend: function() {
                    $('#sup-capital').text('loading ...');
                    $('#sup-balance').text('loading ...');
                    $('#sal-capital').text('loading ...');
                    $('#sal-balance').text('loading ...');
                    $('#expenses').text('loading ...');
                    $('#total-trades').text('loading ...');
                    $('#profit-loss').text('loading ...');
                    $('#incoming').text('loading ...');
                    $('#outgoing').text('loading ...');
                },
                success: function(data) {
                    const response = JSON.parse(data);

                    $('#sup-capital').text(response["supervisor_capital"]);
                    $('#sup-balance').text(response["supervisor_balance"]);
                    $('#sal-capital').text(response["sales_capital"]);
                    $('#sal-balance').text(response["sales_balance"]);
                    $('#expenses').text(response["expenses"]);
                    $('#total-trades').text(response["trades"]);
                    $('#profit-loss').text(response["gained_or_loss"]);
                    $('#incoming').text(response["in"]);
                    $('#outgoing').text(response["out"]);

                    console.log(data);
                },
                error: function() {

                }
            })
        }
        //
        analytics(dater);

    });
</script>

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

