<?php 

// supervisor should enter the money gotten into the system...so system details will be form what has come inside

// Denomination
require_once ("../db_connection/conn.php");
if (!admin_is_logged_in()) {
    admin_login_redirect();
}

if (admin_has_permission()) {
    redirect(PROOT . 'accounts/trades');
}
include ("../includes/header.inc.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['postdata'] = $_POST;
    unset($_POST);
    redirect($_SERVER['PHP_SELF']);
    exit;
}
    
// issetElse
if (array_key_exists('postdata', $_SESSION)) {
    $_POST = $_SESSION['postdata'];

    $capital_mover = capital_mover($admin_id);
	$denomination_checker = ((is_array($capital_mover) && $capital_mover["msg"] == "touched") ? 'forgot' : null);

    $noCash = ((isset($_POST['no-cash']) && $_POST['no-cash'] == 'no-cash') ? 'no' : 'yes');
    $denomination_have_cash = $noCash;

    $denomination_200c = ((isset($_POST['denomination_200c']) && $noCash == 'yes' && !empty($_POST['denomination_200c'])) ? sanitize($_POST['denomination_200c']) : NULL);
    $denomination_200c_amt = ((isset($_POST['denomination_200c_amt']) && $noCash == 'yes' && !empty($_POST['denomination_200c_amt'])) ? sanitize($_POST['denomination_200c_amt']) : NULL);
    $denomination_100c = ((isset($_POST['denomination_100c']) && $noCash == 'yes' && !empty($_POST['denomination_100c'])) ? sanitize($_POST['denomination_100c']) : NULL);
    $denomination_100c_amt = ((isset($_POST['denomination_100c_amt']) && $noCash == 'yes' && !empty($_POST['denomination_100c_amt'])) ? sanitize($_POST['denomination_100c_amt']) : NULL);
    $denomination_50c = ((isset($_POST['denomination_50c']) && $noCash == 'yes' && !empty($_POST['denomination_50c'])) ? sanitize($_POST['denomination_50c']) : NULL);
    $denomination_50c_amt = ((isset($_POST['denomination_50c_amt']) && $noCash == 'yes' && !empty($_POST['denomination_50c_amt'])) ? sanitize($_POST['denomination_50c_amt']) : NULL);
    $denomination_20c = ((isset($_POST['denomination_20c']) && $noCash == 'yes' && !empty($_POST['denomination_20c'])) ? sanitize($_POST['denomination_20c']) : NULL);
    $denomination_20c_amt = ((isset($_POST['denomination_20c_amt']) && $noCash == 'yes' && !empty($_POST['denomination_20c_amt'])) ? sanitize($_POST['denomination_20c_amt']) : NULL);
    $denomination_10c = ((isset($_POST['denomination_10c']) && $noCash == 'yes' && !empty($_POST['denomination_10c'])) ? sanitize($_POST['denomination_10c']) : NULL);
    $denomination_10c_amt = ((isset($_POST['denomination_10c_amt']) && $noCash == 'yes' && !empty($_POST['denomination_10c_amt'])) ? sanitize($_POST['denomination_10c_amt']) : NULL);
    $denomination_5c = ((isset($_POST['denomination_5c']) && $noCash == 'yes' && !empty($_POST['denomination_5c'])) ? sanitize($_POST['denomination_5c']) : NULL);
    $denomination_5c_amt = ((isset($_POST['denomination_5c_amt']) && $noCash == 'yes' && !empty($_POST['denomination_5c_amt'])) ? sanitize($_POST['denomination_5c_amt']) : NULL);
    $denomination_2c = ((isset($_POST['denomination_2c']) && $noCash == 'yes' && !empty($_POST['denomination_2c'])) ? sanitize($_POST['denomination_2c']) : NULL);
    $denomination_2c_amt = ((isset($_POST['denomination_2c_amt']) && $noCash == 'yes' && !empty($_POST['denomination_2c_amt'])) ? sanitize($_POST['denomination_2c_amt']) : NULL);
    $denomination_1c = ((isset($_POST['denomination_1c']) && $noCash == 'yes' && !empty($_POST['denomination_1c'])) ? sanitize($_POST['denomination_1c']) : NULL);
    $denomination_1c_amt = ((isset($_POST['denomination_1c_amt']) && $noCash == 'yes' && !empty($_POST['denomination_1c_amt'])) ? sanitize($_POST['denomination_1c_amt']) : NULL);
    $denomination_50p = ((isset($_POST['denomination_50p']) && $noCash == 'yes' && !empty($_POST['denomination_50p'])) ? sanitize($_POST['denomination_50p']) : NULL);
    $denomination_50p_amt = ((isset($_POST['denomination_50p_amt']) && $noCash == 'yes' && !empty($_POST['denomination_50p_amt'])) ? sanitize($_POST['denomination_50p_amt']) : NULL);
    $denomination_20p = ((isset($_POST['denomination_20p']) && $noCash == 'yes' && !empty($_POST['denomination_20p'])) ? sanitize($_POST['denomination_20p']) : NULL);
    $denomination_20p_amt = ((isset($_POST['denomination_20p_amt']) && $noCash == 'yes' && !empty($_POST['denomination_20p_amt'])) ? sanitize($_POST['denomination_20p_amt']) : NULL);
    $denomination_10p = ((isset($_POST['denomination_10p']) && $noCash == 'yes' && !empty($_POST['denomination_10p'])) ? sanitize($_POST['denomination_10p']) : NULL);
    $denomination_10p_amt = ((isset($_POST['denomination_10p_amt']) && $noCash == 'yes' && !empty($_POST['denomination_10p_amt'])) ? sanitize($_POST['denomination_10p_amt']) : NULL);
    $denomination_5p = ((isset($_POST['denomination_5p']) && $noCash == 'yes' && !empty($_POST['denomination_5p'])) ? sanitize($_POST['denomination_5p']) : NULL);
    $denomination_5p_amt = ((isset($_POST['denomination_5p_amt']) && $noCash == 'yes' && !empty($_POST['denomination_5p_amt'])) ? sanitize($_POST['denomination_5p_amt']) : NULL);
    $denomination_1p = ((isset($_POST['denomination_1p']) && $noCash == 'yes' && !empty($_POST['denomination_1p'])) ? sanitize($_POST['denomination_1p']) : NULL);
    $denomination_1p_amt = ((isset($_POST['denomination_1p_amt']) && $noCash == 'yes' && !empty($_POST['denomination_1p_amt'])) ? sanitize($_POST['denomination_1p_amt']) : NULL);
    $denomination_total = ((isset($_POST['denomination_total']) && $noCash == 'yes' && !empty($_POST['denomination_total'])) ? sanitize($_POST['denomination_total']) : NULL);

    $denomination_id = guidv4();
    $capital_id = _capital($admin_id)['today_capital_id'];
    $capital_amt = money(_capital($admin_id)['today_capital']);
    $capital_bal = _capital($admin_id)['today_balance'];

    // include gain amount to supervor details
    $gained = '';
    $g = '';
    if (admin_has_permission('supervisor')) {
        $g = _gained_calculation(_capital($admin_id)['today_balance'], _capital($admin_id)['today_capital'], $admin_id);
        $gained = 'Earned: ' . money($g) . '<br />';
        $capital_bal = remaining_gold_balance($admin_id);
    }

    $exp_amt = ((admin_has_permission('supervisor')) ? '' : total_expenditure_today($admin_id));
    $expenditure = ((admin_has_permission('salesperson')) ? 'Expenditure: ' . money($exp_amt["sum"]) . '<br />' : '');

    $tst = total_sale_amount_today($admin_id); // total sale today
    $brought_in_amount = ((admin_has_permission('supervisor')) ? 'Cash' : 'Gold') . ' accumulated: ' . ((admin_has_permission('supervisor')) ? money($tst["sum"]) : money((float)($tst["sum"] - $exp_amt["sum"])));

    //
    $p = get_total_send_push($conn, $admin_id);

    $data = [$denomination_id, $capital_id, $admin_id, $denomination_200c, $denomination_200c_amt, $denomination_100c, $denomination_100c_amt, $denomination_50c, $denomination_50c_amt, $denomination_20c, $denomination_20c_amt, $denomination_10c, $denomination_10c_amt, $denomination_5c, $denomination_5c_amt, $denomination_2c, $denomination_2c_amt, $denomination_1c, $denomination_1c_amt, $denomination_50p, $denomination_50p_amt, $denomination_20p, $denomination_20p_amt, $denomination_10p, $denomination_10p_amt, $denomination_5p, $denomination_5p_amt, $denomination_1p, $denomination_1p_amt, $denomination_have_cash, $denomination_checker];
    // save end trade records into denomination table
    $sql = "
        INSERT INTO `jspence_denomination`(`denominations_id`, `denomination_capital`, `denomination_by`, `denomination_200c`, `denomination_200c_amt`, `denomination_100c`, `denomination_100c_amt`, `denomination_50c`, `denomination_50c_amt`, `denomination_20c`, `denomination_20c_amt`, `denomination_10c`, `denomination_10c_amt`, `denomination_5c`, `denomination_5c_amt`, `denomination_2c`, `denomination_2c_amt`, `denomination_1c`, `denomination_1c_amt`, `denomination_50p`, `denomination_50p_amt`, `denomination_20p`, `denomination_20p_amt`, `denomination_10p`, `denomination_10p_amt`, `denomination_5p`, `denomination_5p_amt`, `denomination_1p`, `denomination_1p_amt`, `denomination_have_cash`, `denomination_checker`) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ";
    $statement = $conn->prepare($sql);
    $result = $statement->execute($data);
    if (isset($result)) {

        $message = "ended trade, denomination id: " . $denomination_id . ", and total amount of " . money($denomination_total);
        add_to_log($message, $admin_id);
    
        $push_to = '986785d8-7b98-4747-a0b2-8b4f4b239e06'; // get supervisors id
        $push_id = guidv4();
        $today = date('Y-m-d');
        
        // send balance back to the supervisor for his next day trade
        $tomorrow = new DateTime('tomorrow');
        $tomorrow = $tomorrow->format('Y-m-d');

        // get remaining gold balance //
        
        $daily_id = guidv4(); // generate new daily id
        $supervisor_tomorrow_capital = _capital($push_to, $tomorrow)['today_capital']; // get supervisors tomorrow capital
        
        $gold_balance = 0;
        if (admin_has_permission('salesperson')) {
            $gold_balance = total_amount_today($admin_id); // salepersonnel accumulated gold
        } else {
            $gold_balance = remaining_gold_balance($admin_id); // remaining supervisor gold balance
        }
        $new_capital = $gold_balance;

        // check if supervisor has already recieved tomorrow capital from other salepersonels
        $findActiveCapital = find_capital_given_to($push_to);
        if (is_array($findActiveCapital)) {
            $new_capital = (float)($supervisor_tomorrow_capital + $gold_balance);
            $daily_id = $findActiveCapital["daily_id"];
        }

        // prevent adding negative balance
        if ($gold_balance > 0) {
            $data = [$new_capital, $push_to, $daily_id];

            // insert into supervosr's capital for tomorrow
            $sql = "
                INSERT INTO jspence_daily (daily_capital, daily_to, daily_id) 
                VALUES (?, ?, ?)
            ";
            if (is_array($findActiveCapital)) {
                // update supervosr's capital for tomorrow
                $sql = "
                    UPDATE `jspence_daily` 
                    SET `daily_capital` = ? 
                    WHERE `daily_to` = ? AND `daily_id` = ?
                ";
            }
            $statement = $conn->prepare($sql);
            $daily_result = $statement->execute($data);
        }

        // send cash balance or cash accumulated to the coffers
        $coffers_id = guidv4();
        $pushData = array();
        if (admin_has_permission('salesperson')) {
            $cash = _capital($admin_id)['today_balance']; // cash remaining from saleperson

            $pushData = array(
                'expenditure' => $exp_amt["sum"], 
                'total_pushes' => $p["sum"], 
                'accumulated_gold' => (float)($tst["sum"] - $exp_amt["sum"]), 
                'total_sales' => $tst["sum"]
            );
        } else {
            $cash = total_amount_today($admin_id); // cash gained from supervisor

            $pushData = array(
                'balance' => $capital_bal,
                'total_pushes' => $p["sum"], 
                'accumulated_money' => $tst["sum"], 
                'total_sales' => $tst["sum"], 
                'earned' => $g,
                'sold' => _capital($admin_id)['today_balance']
            );
        }
        $pushData = json_encode($pushData);

        $insertSql = "
            INSERT INTO jspence_coffers (coffers_amount, coffers_status, coffers_receive_through, coffers_id) 
            VALUES (?, ?, ?, ?)
        ";
        $statement = $conn->prepare($insertSql);
        $coffers_result = $statement->execute([$cash, 'receive', 'end_trade_balance', $coffers_id]);

        // insert all money into pushes and link with coffers id
        if ($coffers_result) {
            $LID = $conn->lastInsertId();
            $q = $conn->query("SELECT * FROM jspence_coffers WHERE id = '" . $LID . "' LIMIT 1")->fetchAll();
            $coffers_id = $q[0]['coffers_id'];

            

            $push_data = [$push_id, $coffers_id, $cash, 'money', $admin_id, 'coffers', $today, 'coffers', $pushData];
            $sql = "
                INSERT INTO jspence_pushes (push_id, push_daily, push_amount, push_type, push_from, push_to, push_date, push_on, push_data) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ";
            $statement = $conn->prepare($sql);
            $statement->execute($push_data);
        }

        // update today trade table so it does not accepts any trades anymore
        $query = "
            UPDATE jspence_daily SET daily_capital_status = ? 
            WHERE daily_id = ?
        ";
        $statement = $conn->prepare($query);
        $r = $statement->execute([1, $capital_id]);
        if ($r) {
            $message = "market capital ended, capital id: " . $capital_id;
            add_to_log($message, $admin_id);
        }

?>

    <main class="main px-lg-6">
        <!-- Content -->
        <div class="container-lg">
            <!-- Page header -->
            <div class="row align-items-center mb-7">
                <div class="col-auto">
                    <!-- Avatar -->
                    <div class="avatar avatar-xl rounded text-warning">
                        <i class="fs-2" data-duoicon="credit-card"></i>
                    </div>
                </div>
                <div class="col">
                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-2">
                            <li class="breadcrumb-item"><a class="text-body-secondary" href="javascript:;">Market</a></li>
                            <li class="breadcrumb-item active" aria-current="page">End trade</li>
                        </ol>
                    </nav>

                    <!-- Heading -->
                    <h1 class="fs-4 mb-0">ID: <?= $denomination_id; ?></h1>
                </div>
                <div class="col-12 col-sm-auto mt-4 mt-sm-0">
                    <!-- Action -->
                    <div class="row gx-2">
                        <div class="col-6 col-sm-auto">
                            <a class="btn btn-secondary d-block" href="javascript:;" onclick="printPageArea('printableArea')"> <span class="material-symbols-outlined me-1">download</span> Download </a>
                        </div>
                        <div class="col-6 col-sm-auto">
                            <a class="btn btn-light d-block" href="<?= PROOT; ?>"> Go home </a>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Page content -->
            <div class="card" id="printableArea">
                <div class="card-body">
                    <div class="row align-items-center justify-content-between mb-7">
                        <div class="col-auto">
                            <!-- Icon -->
                            <div class="avatar avatar-xl rounded text-primary">
                                <img src="<?= PROOT; ?>assets/media/logo-no-bg.png" width="40" height="40" class="img-fluid" />
                            </div>
                        </div>
                        <div class="col-auto">
                            <p class="text-end text-body-secondary mb-0">
                                <span class="text-body"><?= company_data()['company_name']; ?></span> <br />
                                <?= company_data()['company_address']; ?> <br />
                                <?= company_data()['company_phone1']; ?>
                            </p>
                            
                        </div>
                    </div>
                    <div class="row justify-content-between mb-7">
                        <div class="col-auto">
                            <p class="text-body-secondary mb-0">
                                <span class="fw-bold text-body">Capital:</span> <br />
                                <span class="text-body">System summary</span> <br />
                                Capital ID: <?= $capital_id; ?><br />
                                Amount Given: <?= $capital_amt; ?><br />
                                Balance: <?= money($capital_bal); ?><br />
                                <?= $brought_in_amount; ?><br />
                                <?= $gained; ?>
                                <?= $expenditure; ?>
                                Total Push made: <?= money($p["sum"]); ?>
                            </p>
                        </div>
                        <div class="col-auto">
                            <p class="text-end text-body-secondary mb-0">
                            <span class="fw-bold text-body">From:</span> <br />
                                <span class="text-body"><?= ucwords($admin_data['admin_fullname']); ?></span> <br />
                                Title: <?= strtoupper(_admin_position($admin_data['admin_permissions'])); ?> <br />
                                Admin ID: <?= $admin_id; ?> <br />
                                Last Login: <?= pretty_date($admin_data['admin_last_login']); ?> <br />
                                Denomination ID: <?= $denomination_id; ?>
                            </p>
                        </div>
                    </div>
                    <?php if ($noCash == 'yes'): ?>
                    <div class="list-group mb-7">
                        <div class="list-group-item bg-body">
                            <div class="row">
                                <div class="col">200 X </div>
                                <div class="col"><?= $denomination_200c; ?></div>
                                <div class="col-auto"><?= money($denomination_200c_amt); ?></div>
                            </div>
                        </div>
                        <div class="list-group-item bg-body">
                            <div class="row">
                                <div class="col">100 X </div>
                                <div class="col"><?= $denomination_100c; ?></div>
                                <div class="col-auto"><?= money($denomination_100c_amt); ?></div>
                            </div>
                        </div>
                        <div class="list-group-item bg-body">
                            <div class="row">
                                <div class="col">50 X </div>
                                <div class="col"><?= $denomination_50c; ?></div>
                                <div class="col-auto"><?= money($denomination_50c_amt); ?></div>
                            </div>
                        </div>
                        <div class="list-group-item bg-body">
                            <div class="row">
                                <div class="col">20 X </div>
                                <div class="col"><?= $denomination_20c; ?></div>
                                <div class="col-auto"><?= money($denomination_20c_amt); ?></div>
                            </div>
                        </div>
                        <div class="list-group-item bg-body">
                            <div class="row">
                                <div class="col">10 X </div>
                                <div class="col"><?= $denomination_10c; ?></div>
                                <div class="col-auto"><?= money($denomination_10c_amt); ?></div>
                            </div>
                        </div>
                        <div class="list-group-item bg-body">
                            <div class="row">
                                <div class="col">5 X </div>
                                <div class="col"><?= $denomination_5c; ?></div>
                                <div class="col-auto"><?= money($denomination_5c_amt); ?></div>
                            </div>
                        </div>
                        <div class="list-group-item bg-body">
                            <div class="row">
                                <div class="col">2 X </div>
                                <div class="col"><?= $denomination_2c; ?></div>
                                <div class="col-auto"><?= money($denomination_2c_amt); ?></div>
                            </div>
                        </div>
                        <div class="list-group-item bg-body">
                            <div class="row">
                                <div class="col">1 X </div>
                                <div class="col"><?= $denomination_1c; ?></div>
                                <div class="col-auto"><?= money($denomination_1c_amt); ?></div>
                            </div>
                        </div>
                        <div class="list-group-item bg-body">
                            <div class="row">
                                <div class="col">0.50 X </div>
                                <div class="col"><?= $denomination_50p; ?></div>
                                <div class="col-auto"><?= money($denomination_50p_amt); ?></div>
                            </div>
                        </div>
                        <div class="list-group-item bg-body">
                            <div class="row">
                                <div class="col">0.20 X </div>
                                <div class="col"><?= $denomination_20p; ?></div>
                                <div class="col-auto"><?= money($denomination_20p_amt); ?></div>
                            </div>
                        </div>
                        <div class="list-group-item bg-body">
                            <div class="row">
                                <div class="col">0.10 X </div>
                                <div class="col"><?= $denomination_10p; ?></div>
                                <div class="col-auto"><?= money($denomination_10p_amt); ?></div>
                            </div>
                        </div>
                        <div class="list-group-item bg-body">
                            <div class="row">
                                <div class="col">0.05 X </div>
                                <div class="col"><?= $denomination_5p; ?></div>
                                <div class="col-auto"><?= money($denomination_5p_amt); ?></div>
                            </div>
                        </div>
                        <div class="list-group-item bg-body">
                            <div class="row">
                                <div class="col">0.01 X </div>
                                <div class="col"><?= $denomination_1p; ?></div>
                                <div class="col-auto"><?= money($denomination_1p_amt); ?></div>
                            </div>
                        </div>
                        <div class="list-group-item bg-body">
                            <div class="row">
                                <div class="col">
                                    <strong>Total</strong>
                                </div>
                                    <div class="col-auto">
                                    <strong><?= money($denomination_total); ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                    <p>No cash.</p>
                    <?php endif; ?>
                    <h3 class="fs-base">Notes:</h3>
                    <p class="text-body-secondary mb-0">
                    Thank you for todays sales! <br />
                    Please let us know if you have any questions.
                    </p>
                </div>
            </div>
        </div>
    </main>

<?php

    unset($_SESSION['postdata']);

    }
} else {
    redirect(PROOT);
}

include ("../includes/footer.inc.php"); 

?>

<script>
    function printPageArea(areaID) {
        var printContent = document.getElementById(areaID).innerHTML;
        var originalContent = document.body.innerHTML;
        document.body.innerHTML = printContent;
        window.print();
        document.body.innerHTML = originalContent;
    }
</script>
