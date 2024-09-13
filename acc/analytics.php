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

	    $where = '';
		if ($admin_data[0]['admin_permissions'] != 'admin,salesperson') {
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
    //dnd($b['balance']);

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
                            <a href="#" class="px-3 py-1 text-muted bg-white-hover bg-opacity-70-hover rounded">1H </a>
                            <a href="#" class="px-3 py-1 text-muted bg-white rounded shadow-1">1D </a>
                            <a href="#" class="px-3 py-1 text-muted bg-white-hover bg-opacity-50-hover rounded">1W </a>
                            <a href="#" class="px-3 py-1 text-muted bg-white-hover bg-opacity-50-hover rounded">1M </a>
                            <a href="#" class="d-none d-sm-inline-block px-3 py-1 text-muted bg-white-hover bg-opacity-50-hover rounded">1Y</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="vstack gap-3 gap-xl-6">
            <div class="row row-cols-xl-4 g-3 g-xl-6">
                <div class="col-xxl-8">
                    <div class="card">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h5>Profit<!-- Balance --></h5>
                            </div>
                            <div>
                                <span class="text-heading fw-bold"><i class="bi bi-arrow-up me-2"></i>7.8%</span></div>
                            </div>
                            <div class="text-2xl fw-bolder text-heading ls-tight"><?= money($al['gained_or_loss']); ?> GHS</div>
                            <div class="d-flex align-items-center justify-content-between mt-8">
                            <div class="">
                                <div class="d-flex gap-3 align-items-center">
                                <div class="icon icon-sm icon-shape text-sm rounded-circle bg-dark text-success"><i class="bi bi-arrow-down"></i></div><span class="h6 fw-semibold text-muted">Incoming</span>
                                </div>
                                <div class="fw-bold text-heading mt-3"><?= money($al['in']); ?>  GHS</div>
                            </div><span class="vr bg-dark bg-opacity-10"></span>
                            <div class="">
                                <div class="d-flex gap-3 align-items-center">
                                <div class="icon icon-sm icon-shape text-sm rounded-circle bg-dark text-danger"><i class="bi bi-arrow-up"></i></div><span class="h6 fw-semibold text-muted">Outgoing</span>
                                </div>
                                <div class="fw-bold text-heading mt-3"><?= money($al['out']); ?> GHS</div>
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
                    
                </div>
                <div class="col-xxl-4">
                    <div class="row g-3">
                        <div class="col">
                            <div class="card">
                                <div class="p-4">
                                    <h6 class="text-limit text-muted mb-3">Total Capital</h6>
                                    <span class="text-sm text-muted text-opacity-90 fw-semibold">GHS</span> <span class="d-block h3 ls-tight fw-bold"><?= money($al['capital']); ?></span>
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
                                    <h6 class="text-limit text-muted mb-3">Total Balance</h6>
                                    <span class="text-sm text-muted text-opacity-90 fw-semibold">GHS</span> <span class="d-block h3 ls-tight fw-bold"><?= money($al['balance']); ?></span>
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
                                    <h6 class="text-limit text-muted mb-3">Expenses</h6>
                                    <span class="text-sm text-muted text-opacity-90 fw-semibold">GHS</span> <span class="d-block h3 ls-tight fw-bold"><?= money($al['expenses']); ?></span>
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
                                    <span class="text-sm text-muted text-opacity-90 fw-semibold">#</span> <span class="d-block h3 ls-tight fw-bold"><?= $al['trades']; ?></span>
                                    <p class="mt-1">
                                        <span class="text-success text-xs"><i class="fas fa-arrow-up me-1"></i>20% </span>
                                        <span class="text-muted text-xs text-opacity-75">vs last week</span>
                                    </p>
                                </div>
                            </div>
                        </div>
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
    });
    
    // SEARCH AND PAGINATION FOR LIST
    function load_data(page, query = '') {
        $.ajax({
            url : "<?= PROOT; ?>auth/expenditure.list.php",
            method : "POST",
            data : {
                page : page, 
                query : query
            },
            success : function(data) {
                $("#load-content").html(data);
            }
        });
    }

    load_data(1);
    $('#search').keyup(function() {
        var query = $('#search').val();
        load_data(1, query);
    });

    $(document).on('click', '.page-link-go', function() {
        var page = $(this).data('page_number');
        var query = $('#search').val();
        load_data(page, query);
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

