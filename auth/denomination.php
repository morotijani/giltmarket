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

    //
    $denomination_id = guidv4();
    $capital_id = _capital($admin_id)['today_capital_id'];
    $capital_amt = money(_capital($admin_id)['today_capital']);
    $capital_bal = _capital($admin_id)['today_balance'];

    // include gain amount to supervor details
    $gained = '';
    $g = 0;
    if (admin_has_permission('supervisor')) {
        $runningCapital = find_capital_given_to($admin_id);
        if (is_array($runningCapital)) {
            $g = $runningCapital['daily_profit'];
        }

        $gained = 'Earned: ' . money($g) . '<br />';
    }

    // expenditure details
    $exp_amt = ((admin_has_permission('supervisor')) ? '' : total_expenditure_today($admin_id));
    $expenditure = ((admin_has_permission('salesperson')) ? 'Expenditure: ' . money($exp_amt["sum"]) . '<br />' : '');

    $tst = total_sale_amount_today($admin_id); // total sale 
    $brought_in_amount = ((admin_has_permission('supervisor')) ? 'Cash' : 'Gold') . ' accumulated: ' . money(total_amount_today($admin_id));

    // get all send push
    $p = get_total_send_push($conn, $admin_id, $admin_data["admin_permissions"]);

    // 
    $push_gram = 0;
    $push_volume = 0;
    $push_density = 0;
    $push_pounds = 0;
    $push_carat = 0;

    // 
    $denomination_data = array(
        'amount_given' => _capital($admin_id)['today_capital'], 
        'balance' => $capital_bal, 
        'brought_in' => total_amount_today($admin_id), 
        'send_push' => $p["sum"]
    );
    $new_Array = [];
    if (admin_has_permission('salesperson')) {
        $new_Array = array('expenditure' => $exp_amt["sum"]);
    } else if (admin_has_permission('supervisor')) {
        $new_Array = array('gained' => $g);
    }
    $denomination_data = array_merge($denomination_data, $new_Array);
	$denomination_data = json_encode($denomination_data);

    $data = [$denomination_id, $capital_id, $admin_id, $denomination_200c, $denomination_200c_amt, $denomination_100c, $denomination_100c_amt, $denomination_50c, $denomination_50c_amt, $denomination_20c, $denomination_20c_amt, $denomination_10c, $denomination_10c_amt, $denomination_5c, $denomination_5c_amt, $denomination_2c, $denomination_2c_amt, $denomination_1c, $denomination_1c_amt, $denomination_50p, $denomination_50p_amt, $denomination_20p, $denomination_20p_amt, $denomination_10p, $denomination_10p_amt, $denomination_5p, $denomination_5p_amt, $denomination_1p, $denomination_1p_amt, $denomination_have_cash, $denomination_checker, $denomination_data];
    // save end trade records into denomination table
    $sql = "
        INSERT INTO `giltmarket_denomination`(`denominations_id`, `denomination_capital`, `denomination_by`, `denomination_200c`, `denomination_200c_amt`, `denomination_100c`, `denomination_100c_amt`, `denomination_50c`, `denomination_50c_amt`, `denomination_20c`, `denomination_20c_amt`, `denomination_10c`, `denomination_10c_amt`, `denomination_5c`, `denomination_5c_amt`, `denomination_2c`, `denomination_2c_amt`, `denomination_1c`, `denomination_1c_amt`, `denomination_50p`, `denomination_50p_amt`, `denomination_20p`, `denomination_20p_amt`, `denomination_10p`, `denomination_10p_amt`, `denomination_5p`, `denomination_5p_amt`, `denomination_1p`, `denomination_1p_amt`, `denomination_have_cash`, `denomination_checker`, `denomination_data`) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ";
    $statement = $conn->prepare($sql);
    $result = $statement->execute($data);
    if (isset($result)) {

        $message = "ended trade, denomination id: " . $denomination_id . ", and total amount of " . money($denomination_total);
        add_to_log($message, $admin_id);
    
        $push_to = '986785d8-7b98-4747-a0b2-8b4f4b239e06'; // get supervisors id
        $push_id = guidv4();
        $daily_id = guidv4(); // generate new daily id
        $today = date('Y-m-d');
        $gold_balance = 0;
        $new_capital = 0;
        $gold_to_push = 0;
        
        // grab tomorrows date
        $tomorrow = new DateTime('tomorrow');
        $tomorrow = $tomorrow->format('Y-m-d');

        // get remaining gold balance //
        $findActiveCapital = find_capital_given_to($push_to); // supervisor today or active capital
        if (admin_has_permission('salesperson')) {
            $gold_balance = total_amount_today($admin_id); // salepersonnel accumulated gold
            $new_capital = $gold_balance;
            $gold_to_push = $gold_balance;
            
            if (is_array($findActiveCapital)) {
                // if there was an active capital found, add the salesperson accumulated gold balance to the supervisor gold balance
                $gold_balance = (float)($findActiveCapital["daily_balance"] + $gold_balance);
                $new_capital = (float)($findActiveCapital["daily_capital"] + total_amount_today($admin_id));
                $daily_id = $findActiveCapital["daily_id"];
            }
            
        } else {
            if (is_array($findActiveCapital)) {
                $gold_balance = $findActiveCapital["daily_balance"]; // remaining_gold_balance($admin_id); // remaining supervisor gold balance
                $new_capital = $gold_balance;
                $daily_id = $findActiveCapital["daily_id"];
            }
        }

        // check if supervisor has already recieved tomorrow capital from other salepersonels
        // $findTomorrowCapital = find_capital_given_to($push_to, $tomorrow);
        // if (is_array($findTomorrowCapital)) {
        //     $new_capital = (float)($supervisor_tomorrow_capital + $gold_balance);
        //     $gold_balance = (float)($findActiveCapital["daily_balance"] + $gold_balance);
            
        //     $daily_id = $findTomorrowCapital["daily_id"];
        // }

        // prevent adding negative balance
        if ($gold_balance > 0) {            
            if (admin_has_permission('supervisor')) {
                // insert into supervosr's capital for tomorrow
                $QUERY = "
                    INSERT INTO giltmarket_daily (daily_capital, daily_balance, daily_to, daily_id, daily_date) 
                    VALUES (?, ?, ?, ?, ?)
                ";
                $statement = $conn->prepare($QUERY);
                $daily_result = $statement->execute([$new_capital, $gold_balance, $push_to, guidv4(), $tomorrow]);
            } else {
                $data = [$new_capital, $gold_balance, $push_to, $daily_id];

                // insert into supervosr's capital for tomorrow
                $sql = "
                    INSERT INTO giltmarket_daily (daily_capital, daily_balance, daily_to, daily_id, daily_date) 
                    VALUES (?, ?, ?, ?, '" . $tomorrow . "')
                ";
                if (is_array($findActiveCapital)) {
                    // update supervosr's capital for tomorrow
                    $sql = "
                        UPDATE `giltmarket_daily` 
                        SET `daily_capital` = ?, daily_balance = ? 
                        WHERE `daily_to` = ? AND `daily_id` = ?
                    ";
                }
                $statement = $conn->prepare($sql);
                $daily_result = $statement->execute($data);
            }

            if ($daily_result) {
                $push_gram = sum_up_grams($conn, $admin_id);
                $push_volume = sum_up_volume($conn, $admin_id);
                $push_density = sum_up_density($conn, $admin_id);
                $push_pounds = sum_up_pounds($conn, $admin_id);
                $push_carat = sum_up_carat($conn, $admin_id);

                if ($push_gram > 0) {
                    $pushGoldData = array('gram' => $push_gram, 'volume' => $push_volume, 'density' => $push_density, 'pounds' => $push_pounds, 'carat' => $push_carat);
                    $pushGoldData = json_encode($pushGoldData);

                    // insert gold to pushes
                    $push_Data = [$push_id, $daily_id, $gold_to_push, 'gold', $admin_id, $push_to, 'end-trade', $pushGoldData];
                    $SQL = "
                        INSERT INTO giltmarket_pushes (push_id, push_daily, push_amount, push_type, push_from, push_to, push_from_where, push_data) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                    ";
                    $statement = $conn->prepare($SQL);
                    $SQLRESULT = $statement->execute($push_Data);

                    if ($SQLRESULT) {
                        $updateQ = "
                            UPDATE giltmarket_sales 
                            SET sale_pushed = ?
                            WHERE sale_daily = ? 
                            AND sale_pushed = ? 
                        ";
                        $statement = $conn->prepare($updateQ);
                        $statement->execute([1, $daily_id, 0]);
                    }
                }
            }
        }

        // send cash balance or cash accumulated to the coffers
        $coffers_id = guidv4();
        $pushData = array();
        $cash = 0;
        if (admin_has_permission('salesperson')) {
            $cash = _capital($admin_id)['today_balance']; // cash remaining from saleperson

            $pushData = array(
                'expenditure' => $exp_amt["sum"], 
                'send_pushes' => $p["sum"], 
                'accumulated_gold' => total_amount_today($admin_id), 
                'total_sales' => $tst["sum"]
            );
        } else {
            $cash = total_amount_today($admin_id); // cash gained from supervisor

            $pushData = array(
                'balance' => $capital_bal,
                'send_pushes' => $p["sum"], 
                'accumulated_money' => total_amount_today($admin_id), 
                'total_sales' => $tst["sum"], 
                'earned' => $g,
                'sold' => _capital($admin_id)['today_balance']
            );
        }
        $pushData = json_encode($pushData);

        if ($cash > 0) {
            $insertSql = "
                INSERT INTO giltmarket_coffers (coffers_amount, coffers_status, coffers_receive_through, status, coffers_id) 
                VALUES (?, ?, ?, ?, ?)
            ";
            $statement = $conn->prepare($insertSql);
            $coffers_result = $statement->execute([$cash, 'receive', 'end_trade_balance', '1', $coffers_id]);

            // insert all money into pushes and link with coffers id
            if ($coffers_result) {
                $LID = $conn->lastInsertId();
                $q = $conn->query("SELECT * FROM giltmarket_coffers WHERE id = '" . $LID . "' LIMIT 1")->fetchAll();
                $coffers_id = $q[0]['coffers_id'];

                $push_data = [$push_id, $coffers_id, $cash, 'money', $admin_id, 'coffers', 'end-trade', $pushData];
                $sql = "
                    INSERT INTO giltmarket_pushes (push_id, push_daily, push_amount, push_type, push_from, push_to, push_from_where, push_data) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                ";
                $statement = $conn->prepare($sql);
                $statement->execute($push_data);
            }
        }

        // update today trade table so it does not accepts any trades anymore
        $query = "
            UPDATE giltmarket_daily SET daily_capital_status = ? 
            WHERE daily_id = ?
        ";
        $statement = $conn->prepare($query);
        $r = $statement->execute([1, $capital_id]);
        if ($r) {
            $message = "market capital ended, capital id: " . $capital_id;
            add_to_log($message, $admin_id);
        }

        // set coffers status to 1 to prevent the usage of the amount available (only admin will have access)
        if (admin_has_permission('supervisor')) {
            $Sql = "UPDATE giltmarket_coffers SET status = ?";
            $statement = $conn->prepare($Sql);
            $statement->execute([1]);
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
                            <a class="btn btn-light d-block" href="<?= PROOT; ?>auth/logout"> Logout </a>
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
                                <img src="<?= PROOT; ?>assets/media/giltmarket-logo.png" width="40" height="40" class="img-fluid" />
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
                                Send Push made: <?= money($p["sum"]); ?>
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
