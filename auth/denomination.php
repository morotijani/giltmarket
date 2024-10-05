<?php 

// header( 'Cache-Control: no-store, no-cache, must-revalidate' ); 
// header( 'Cache-Control: post-check=0, pre-check=0', false ); 
// header( 'Pragma: no-cache' );

// supervisor should enter the money gotten into the system...so system details will be form what has come inside

// Denomination
require_once ("../db_connection/conn.php");
if (!admin_is_logged_in()) {
    admn_login_redirect();
}

if (admin_has_permission()) {
    redirect(PROOT . 'accounts/trades');
}
include ("../includes/header.inc.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['postdata'] = $_POST;
    

    
    if (isset($_POST['denomination_200c'])) {
        $denomination_200c = ((isset($_POST['denomination_200c']) && !empty($_POST['denomination_200c'])) ? sanitize($_POST['denomination_200c']) : NULL);
        $denomination_200c_amt = ((isset($_POST['denomination_200c_amt']) && !empty($_POST['denomination_200c_amt'])) ? sanitize($_POST['denomination_200c_amt']) : NULL);
        $denomination_100c = ((isset($_POST['denomination_100c']) && !empty($_POST['denomination_100c'])) ? sanitize($_POST['denomination_100c']) : NULL);
        $denomination_100c_amt = ((isset($_POST['denomination_100c_amt']) && !empty($_POST['denomination_100c_amt'])) ? sanitize($_POST['denomination_100c_amt']) : NULL);
        $denomination_50c = ((isset($_POST['denomination_50c']) && !empty($_POST['denomination_50c'])) ? sanitize($_POST['denomination_50c']) : NULL);
        $denomination_50c_amt = ((isset($_POST['denomination_50c_amt']) && !empty($_POST['denomination_50c_amt'])) ? sanitize($_POST['denomination_50c_amt']) : NULL);
        $denomination_20c = ((isset($_POST['denomination_20c']) && !empty($_POST['denomination_20c'])) ? sanitize($_POST['denomination_20c']) : NULL);
        $denomination_20c_amt = ((isset($_POST['denomination_20c_amt']) && !empty($_POST['denomination_20c_amt'])) ? sanitize($_POST['denomination_20c_amt']) : NULL);
        $denomination_10c = ((isset($_POST['denomination_10c']) && !empty($_POST['denomination_10c'])) ? sanitize($_POST['denomination_10c']) : NULL);
        $denomination_10c_amt = ((isset($_POST['denomination_10c_amt']) && !empty($_POST['denomination_10c_amt'])) ? sanitize($_POST['denomination_10c_amt']) : NULL);
        $denomination_5c = ((isset($_POST['denomination_5c']) && !empty($_POST['denomination_5c'])) ? sanitize($_POST['denomination_5c']) : NULL);
        $denomination_5c_amt = ((isset($_POST['denomination_5c_amt']) && !empty($_POST['denomination_5c_amt'])) ? sanitize($_POST['denomination_5c_amt']) : NULL);
        $denomination_2c = ((isset($_POST['denomination_2c']) && !empty($_POST['denomination_2c'])) ? sanitize($_POST['denomination_2c']) : NULL);
        $denomination_2c_amt = ((isset($_POST['denomination_2c_amt']) && !empty($_POST['denomination_2c_amt'])) ? sanitize($_POST['denomination_2c_amt']) : NULL);
        $denomination_1c = ((isset($_POST['denomination_1c']) && !empty($_POST['denomination_1c'])) ? sanitize($_POST['denomination_1c']) : NULL);
        $denomination_1c_amt = ((isset($_POST['denomination_1c_amt']) && !empty($_POST['denomination_1c_amt'])) ? sanitize($_POST['denomination_1c_amt']) : NULL);
        $denomination_50p = ((isset($_POST['denomination_50p']) && !empty($_POST['denomination_50p'])) ? sanitize($_POST['denomination_50p']) : NULL);
        $denomination_50p_amt = ((isset($_POST['denomination_50p_amt']) && !empty($_POST['denomination_50p_amt'])) ? sanitize($_POST['denomination_50p_amt']) : NULL);
        $denomination_20p = ((isset($_POST['denomination_20p']) && !empty($_POST['denomination_20p'])) ? sanitize($_POST['denomination_20p']) : NULL);
        $denomination_20p_amt = ((isset($_POST['denomination_20p_amt']) && !empty($_POST['denomination_20p_amt'])) ? sanitize($_POST['denomination_20p_amt']) : NULL);
        $denomination_10p = ((isset($_POST['denomination_10p']) && !empty($_POST['denomination_10p'])) ? sanitize($_POST['denomination_10p']) : NULL);
        $denomination_10p_amt = ((isset($_POST['denomination_10p_amt']) && !empty($_POST['denomination_10p_amt'])) ? sanitize($_POST['denomination_10p_amt']) : NULL);
        $denomination_5p = ((isset($_POST['denomination_5p']) && !empty($_POST['denomination_5p'])) ? sanitize($_POST['denomination_5p']) : NULL);
        $denomination_5p_amt = ((isset($_POST['denomination_5p_amt']) && !empty($_POST['denomination_5p_amt'])) ? sanitize($_POST['denomination_5p_amt']) : NULL);
        $denomination_1p = ((isset($_POST['denomination_1p']) && !empty($_POST['denomination_1p'])) ? sanitize($_POST['denomination_1p']) : NULL);
        $denomination_1p_amt = ((isset($_POST['denomination_1p_amt']) && !empty($_POST['denomination_1p_amt'])) ? sanitize($_POST['denomination_1p_amt']) : NULL);
        $denomination_total = ((isset($_POST['denomination_total']) && !empty($_POST['denomination_total'])) ? sanitize($_POST['denomination_total']) : NULL);


        unset($_POST);
        // unset($_REQUEST);

        $denomination_id = guidv4();
        $capital_id = _capital($admin_id)['today_capital_id'];
        $capital_amt = money(_capital($admin_id)['today_capital']);
        $capital_bal = money(_capital($admin_id)['today_balance']);

        $data = [$denomination_id, $capital_id, $admin_id, $denomination_200c, $denomination_200c_amt, $denomination_100c, $denomination_100c_amt, $denomination_50c, $denomination_50c_amt, $denomination_20c, $denomination_20c_amt, $denomination_10c, $denomination_10c_amt, $denomination_5c, $denomination_5c_amt, $denomination_2c, $denomination_2c_amt, $denomination_1c, $denomination_1c_amt, $denomination_50p, $denomination_50p_amt, $denomination_20p, $denomination_20p_amt, $denomination_10p, $denomination_10p_amt, $denomination_5p, $denomination_5p_amt, $denomination_1p, $denomination_1p_amt];
        
        // save end trade records into denomination table
        $sql = "
            INSERT INTO `jspence_denomination`(`denominations_id`, `denomination_capital`, `denomination_by`, `denomination_200c`, `denomination_200c_amt`, `denomination_100c`, `denomination_100c_amt`, `denomination_50c`, `denomination_50c_amt`, `denomination_20c`, `denomination_20c_amt`, `denomination_10c`, `denomination_10c_amt`, `denomination_5c`, `denomination_5c_amt`, `denomination_2c`, `denomination_2c_amt`, `denomination_1c`, `denomination_1c_amt`, `denomination_50p`, `denomination_50p_amt`, `denomination_20p`, `denomination_20p_amt`, `denomination_10p`, `denomination_10p_amt`, `denomination_5p`, `denomination_5p_amt`, `denomination_1p`, `denomination_1p_amt`) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ";
        $statement = $conn->prepare($sql);
        $result = $statement->execute($data);
        if (isset($result)) {

            $message = "ended trade, denomination id: " . $denomination_id . ", and total amount of " . money($denomination_total);
            add_to_log($message, $admin_id);
        
            // allow only salepersonnels to perform only this action
            if (!admin_has_permission('supervisor')) {
                // send balance back to the supervisor for his next day trade
                $tomorrow = new DateTime('tomorrow');
                $tomorrow = $tomorrow->format('Y-m-d');

                $push_to = '$2y$10$lwzmqYK9BHTWrHL0FNxoju1FCQQfOY78T8nb9kEeH0dTzvRCannvW'; // get supervisors id
                $supervisor_capital = _capital($push_to)['today_capital']; // get supervisors capital
            
                $daily_id = guidv4();
                $push_id = guidv4();
                $new_capital = _capital($admin_id)['today_balance']; // current ending trade sale personnel balance

                // check if supervisor has already recieved tomorrow capital from other salepersonels
                $findTomorrowCapital = find_capital_given_to($push_to, $tomorrow);
                if ($findTomorrowCapital) {
                    $new_capital = (float)($new_capital + $supervisor_capital);
                    $daily_id = $findTomorrowCapital;
                }
                $data = [$new_capital, $tomorrow, $push_to, $daily_id];

                // insert into supervosr's capital for tomorrow
                $sql = "
                    INSERT INTO jspence_daily (daily_capital, daily_date, daily_to, daily_id) 
                    VALUES (?, ?, ?, ?)
                ";
                if ($findTomorrowCapital) {
                    // update supervosr's capital for tomorrow
                    $sql = "
                        UPDATE `jspence_daily` 
                        SET `daily_capital` = ? 
                        WHERE `daily_date` = ? AND `daily_to` = ? AND `daily_id` = ?
                    ";
                }
                $message = "end-trade, remaining balance " . $capital_bal . ' sent to supervisor id: ' . $push_to;
                $statement = $conn->prepare($sql);
                $daily_result = $statement->execute($data);

                // find the just enetered capital id
                if (!$findTomorrowCapital) {
                    $LID = $conn->lastInsertId();
                    $q = $conn->query("SELECT * FROM jspence_daily WHERE id = '" . $LID . "' LIMIT 1")->fetchAll();
                    $findTomorrowCapital = $q[0]['daily_id'];
                }

                if (isset($daily_result)) {
                    // insert into push table
                    $push_data = [$push_id, $findTomorrowCapital, _capital($admin_id)['today_balance'], $admin_id, $push_to, $tomorrow];
                    $sql = "
                        INSERT INTO jspence_pushes (push_id, push_daily, push_amount, push_from, push_to, push_date) 
                        VALUES (?, ?, ?, ?, ?, ?)
                    ";
                    $statement = $conn->prepare($sql);
                    $push_result = $statement->execute($push_data);

                    if (isset($push_result)) {
                        $push_message = "end-trade push made on " . $tomorrow . ", of an amount of " . $capital_bal . ' to supervisor id: ' . $push_to;
                        add_to_log($push_message, $admin_id);
                    }
                    add_to_log($message, $admin_id);
                }

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
                            <li class="breadcrumb-item"><a class="text-body-secondary" href="#">Market</a></li>
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
                                <span class="text-body">From Sytem</span> <br />
                                Capital ID: <?= $capital_id; ?><br />
                                Amount Given: <?= $capital_amt; ?><br />
                                Balance: <?= $capital_bal; ?><br />
                            </p>
                        </div>
                        <div class="col-auto">
                            <p class="text-end text-body-secondary mb-0">
                            <span class="fw-bold text-body">From:</span> <br />
                                <span class="text-body"><?= ucwords($admin_data['admin_fullname']); ?></span> <br />
                                Admin ID: <?= $admin_data['admin_id']; ?> <br />
                                Last Login: <?= pretty_date($admin_data['admin_last_login']); ?> <br />
                                Denomination ID: <?= $denomination_id; ?>
                            </p>
                        </div>
                    </div>
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
        }
    } else {
        redirect(PROOT);
    }

    unset($_SESSION['postdata']);
    
}

    include ("../includes/footer.inc.php"); 
?>

<script>
    function printPageArea(areaID){
        var printContent = document.getElementById(areaID).innerHTML;
        var originalContent = document.body.innerHTML;
        document.body.innerHTML = printContent;
        window.print();
        document.body.innerHTML = originalContent;
    }
</script>
