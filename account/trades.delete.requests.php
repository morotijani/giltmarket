<?php 

    // view admin profile details
    require_once ("../db_connection/conn.php");
    if (!admin_is_logged_in()) {
        admn_login_redirect();
    }
    include ("../includes/header.inc.php");
    include ("../includes/aside.inc.php");
    include ("../includes/left.nav.inc.php");
    include ("../includes/top.nav.inc.php");

    // request viewed
    if (admin_has_permission()) {
        $viewedQ = $conn->query("UPDATE jspence_sales SET sale_delete_request_status = 2 WHERE sale_status = 1")->execute();
        if ($viewedQ) {
            // code...    
            $message = "viewed all new delete request";
            add_to_log($message, $admin_data['admin_id']);
        }
    }

    // delete sale
    if (isset($_GET['pd']) && !empty($_GET['pd'])) {
        $id = sanitize($_GET['pd']);

        $check = $conn->query("SELECT * FROM jspence_sales WHERE sale_id = '" . $id . "' AND sale_status = 1")->rowCount();
        if ($check > 0) {
            // code...
            $query = "
                UPDATE jspence_sales 
                SET sale_status = ?, sale_delete_request_status = ?
                WHERE sale_id = ?
            ";
            $statement = $conn->prepare($query);
            $result = $statement->execute([2, 0, $id]);
            if (isset($result)) {
                // update sale capital balance
                $r = $conn->query("SELECT * FROM jspence_sales WHERE sale_id = '" . $id . "' AND sale_status = 2")->fetchAll();
                $saleAmt = $r[0]['sale_total_amount'];

                $updateQuery = "
                    UPDATE jspence_daily 
                    SET daily_balance = daily_balance + ? 
                    WHERE daily_id = ?
                ";
                if ($r[0]['sale_type'] == 'in') {
                    $updateQuery = "
                        UPDATE jspence_daily 
                        SET daily_balance = daily_balance - ? 
                        WHERE daily_id = ?
                    ";
                }
                
                $statement = $conn->prepare($updateQuery);
                $statement->execute([$saleAmt, $r[0]['sale_daily']]);

                $message = "deleted sale from sale requests";
                add_to_log($message, $admin_data['admin_id']);

                $_SESSION['flash_success'] = "Sale deleted successfully!";
                redirect(PROOT . 'account/trades.delete.requests');
            } else {
                $message = "tried to delete a sale from sale requests but 'Something went wrong.'";
                add_to_log($message, $admin_data['admin_id']);
                echo js_alert("Something went wrong, please try again!");
            }
        } else {
            $message = "tried to delete a sale from sale requests but 'Could not find trade to delete.'";
            add_to_log($message, $admin_data['admin_id']);

            $_SESSION['flash_error'] = "Could not find trade to delete!";
            redirect(PROOT . 'acc/trades.delete.requests');
        }
    }



?>

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
                        <li class="breadcrumb-item active" aria-current="page">Trades</li>
                    </ol>
                </nav>

                <!-- Heading -->
                <h1 class="fs-4 mb-0">Delete trade request(s)</h1>
            </div>
            <div class="col-12 col-sm-auto mt-4 mt-sm-0">
                <!-- Action -->
                <a class="btn btn-secondary d-block" href="<?= goBack(); ?>" data-bs-target="#buyModal" data-bs-toggle="modal"> <span class="material-symbols-outlined me-1">arrow_back</span> Go back </a>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <!-- Filters -->
                <div class="card card-line bg-body-tertiary border-transparent mb-7">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-12 col-lg-auto mb-3 mb-lg-0">
                                <ul class="nav nav-pills">
                                    <li class="nav-item">
                                        <a class="nav-link" href="<?= PROOT; ?>account/trades">All trades</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link bg-dark active" aria-current="page" href="<?= PROOT; ?>account/trades.delete.requests">Delete request <?= count_new_delete_requests($conn); ?></a>
                                    </li>
                                    <?php if (admin_has_permission()) { ?>
                                    <li class="nav-item">
                                        <a class="nav-link" href="<?= PROOT; ?>account/trades.archive">Archive</a>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <div class="col-12 col-lg">
                            </div>
                            <div class="col-auto ms-n2">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive mb-7">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <?php if (admin_has_permission()): ?>
                            <th scope="col">Handler</th>
                        <?php endif; ?>
                        <th>Customer</th>
                        <th>Gram</th>
                        <th>Volume</th>
                        <th>Price</th>
                        <th>Amount</th>
                        <th></th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?= fetch_all_sales(1, $admin_data['admin_id'], 'no_exp'); ?>
                </tbody>
            </table>
        </div>
    </div>

<?php include ("../includes/footer.inc.php"); ?>
