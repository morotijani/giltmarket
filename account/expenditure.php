<?php 

    // expenditure
    require_once ("../db_connection/conn.php");

    if (!admin_is_logged_in()) {
        admn_login_redirect();
    }

    if ($admin_data['admin_permissions'] == 'supervisor') {
        redirect(PROOT . 'index');
    }

    include ("../includes/header.inc.php");
    include ("../includes/aside.inc.php");
    include ("../includes/left.nav.inc.php");
    include ("../includes/top.nav.inc.php");

    $by = $admin_data['admin_id'];
    $for_amount = ((isset($_POST['for_amount']) && !empty($_POST['for_amount'])) ? sanitize($_POST['for_amount']) : '');
    $what_for = ((isset($_POST['what_for']) && !empty($_POST['what_for'])) ? sanitize($_POST['what_for']) : '');

    if (isset($_GET['edit']) && !empty($_GET['edit'])) {
        $id = sanitize($_GET['edit']);

        $sql = "
            SELECT * FROM jspence_expenditures 
            WHERE expenditure_id = ? 
            AND expenditure_by = ?
            LIMIT 1 
        ";
        $statement = $conn->prepare($sql);
        $statement->execute([$id, $admin_data[0]['admin_id']]);
        $_row = $statement->fetchAll();

        if ($statement->rowCount()) {
            $for_amount = ((isset($_POST['for_amount']) && !empty($_POST['for_amount'])) ? sanitize($_POST['for_amount']) : $_row[0]['expenditure_amount']);
            $what_for = ((isset($_POST['what_for']) && !empty($_POST['what_for'])) ? sanitize($_POST['what_for']) : $_row[0]['expenditure_what_for']);
        } else {
            $_SESSION['flash_error'] = 'Cannot find expenditure!';
            redirect(PROOT . "acc/expenditure");
        }
    }

    if ($_POST) {
        $e_id = guidv4();
        $createdAt = date("Y-m-d H:i:s");

        if ((!empty($for_amount) || $for_amount != '') && (!empty($what_for) || $what_for != '')) {

            if (is_capital_given()) {
                if ($for_amount > 0) {
                    if ($admin_data['admin_pin'] == $_POST['pin']) {

                        $today_balance = _capital($by)['today_balance'];
                        if ($for_amount <= $today_balance) {
                            $data = [$e_id, _capital($by)['today_capital_id'], $what_for, $for_amount, $by, $createdAt];

                            $sql = "
                                INSERT INTO jspence_expenditures (expenditure_id, expenditure_capital_id, expenditure_what_for, expenditure_amount, expenditure_by, createdAt) 
                                VALUES (?, ?, ?, ?, ?, ?)
                            ";
                            if (isset($_GET['edit']) && !empty($_GET['edit'])) {
                                $data = [$what_for, $for_amount, $id];
                                $sql = "
                                    UPDATE jspence_expenditures 
                                    SET expenditure_what_for = ?, expenditure_amount = ?
                                    WHERE expenditure_id = ?
                                ";
                            }

                            $statement = $conn->prepare($sql);
                            $result = $statement->execute($data);
                            if (isset($result)) {
                                
                                $today = date("Y-m-d");
                                $balance = (float)(_capital($by)['today_balance'] - $for_amount);
                                if (isset($_GET['edit']) && !empty($_GET['edit'])) {
                                    if ($for_amount < $_row[0]['expenditure_amount']) {
                                        $balance = (float)($_row[0]['expenditure_amount'] - $for_amount);
                                        $balance = (float)(_capital($by)['today_balance'] + $balance);
                                    } elseif ($for_amount > $_row[0]['expenditure_amount']) {
                                        $balance = (float)($for_amount - $_row[0]['expenditure_amount']);
                                        $balance = (float)(_capital($by)['today_balance'] - $balance);
                                    } else {
                                        $balance = _capital($by)['today_balance'];
                                    }
                                }

                                $query = "
                                    UPDATE jspence_daily 
                                    SET daily_balance = ?
                                    WHERE daily_date = ? 
                                    AND daily_by = ?
                                ";
                                $statement = $conn->prepare($query);
                                $statement->execute([$balance, $today, $by]);

                                $message = "added new expenditure: " . $what_for . " and amount of: " . money($for_amount);
                                add_to_log($message, $by);
                
                                $_SESSION['flash_success'] = 'Expenditure has been saved!';
                                redirect(PROOT . "acc/expenditure");
                            } else {
                                echo js_alert("Something went wrong!");
                                redirect(PROOT . "acc/expenditure");
                            }
                        } else {
                            $_SESSION['flash_error'] = 'Today\'s remaining balance cannot complete this expenditure!';
                            redirect(PROOT . "acc/expenditure");
                        }
                    } else {
                        $_SESSION['flash_error'] = 'Invalid pin code provided!';
                        redirect(PROOT . "acc/expenditure");
                    }
                }
            } else {
                $_SESSION['flash_error'] = 'Today\'s capital has not been given so, you can not create an expenditure!';
                redirect(PROOT . "acc/expenditure");
            }
        } else {
            $_SESSION['flash_error'] = 'Empty fields are required!';
            redirect(PROOT . "acc/expenditure");
        }
    }

    if (isset($_GET['delete']) && !empty($_GET['delete'])) {
        $id = sanitize($_GET['delete']);

        $sql = "
            SELECT * FROM jspence_expenditures 
            WHERE expenditure_id = ? 
            AND expenditure_by = ?
            LIMIT 1 
        ";
        $statement = $conn->prepare($sql);
        $statement->execute([$id, $admin_data['admin_id']]);
        $_row = $statement->fetchAll();

        if ($statement->rowCount()) {
            $updateQuery = "
                UPDATE jspence_expenditures 
                SET status = ? 
                WHERE expenditure_id = ?
            ";
            $statement = $conn->prepare($updateQuery);
            $result = $statement->execute([1, $id]);

            if (isset($result)) {
                $for_amount = $_row[0]['expenditure_amount'];
                $today = date("Y-m-d");
                $balance = (float)(_capital($by)['today_balance'] + $for_amount);

                $query = "
                    UPDATE jspence_daily 
                    SET daily_balance = ?
                    WHERE daily_date = ? 
                    AND daily_by = ?
                ";
                $statement = $conn->prepare($query);
                $statement->execute([$balance, $today, $by]);

                $message = "added new expenditure: " . $what_for . " and amount of: " . money($for_amount);
                add_to_log($message, $by);

                $_SESSION['flash_success'] = 'Expenditure has been deleted!';
                redirect(PROOT . "acc/expenditure");
            }
        } else {
            $_SESSION['flash_error'] = 'Cannot find expenditure!';
            redirect(PROOT . "acc/expenditure");
        }

    }

?>

    <div class="container-lg">
        <!-- Page header -->
        <div class="row align-items-center mb-7">
            <div class="col-auto">
                <!-- Avatar -->
                <div class="avatar avatar-xl rounded text-warning">
                <i class="fs-2" data-duoicon="clipboard"></i>
                </div>
            </div>
            <div class="col">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a class="text-body-secondary" href="javascript:;">Expenditure</a></li>
                        <li class="breadcrumb-item active" aria-current="page">New project</li>
                    </ol>
                </nav>

                <!-- Heading -->
                <h1 class="fs-4 mb-0">New project</h1>
            </div>
            <div class="col-12 col-sm-auto mt-4 mt-sm-0">
                <!-- Action -->
                <a class="btn btn-light d-block" href="<?= goBack(); ?>"> Go back </a>
            </div>
        </div>

    <?php if (isset($_GET['add'])) : ?>
    <?php if (!admin_has_permission()): ?>
    <?php if (is_capital_given()): ?>
       

            <form method="POST" id="expenditureForm">
                <section class="card card-line bg-body-tertiary border-transparent mb-5">
                    <div class="card-body">
                        <h3 class="fs-5 mb-1">Create an expenditure</h3>
                        <p class="text-body-secondary mb-5">Fill in the below fields to make an expenditure</p>
                        <hr />
                        <div class="mb-4">
                            <label class="form-label" for="projectTitle">Reason</label>
                            <input class="form-control bg-body" type="text" name="what_for" id="what_for" placeholder="Enter description" value="<?= $what_for; ?>" required />
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="projectTitle">Amount</label>
                            <input class="form-control bg-body" name="for_amount" id="for_amount" type="number" min="0.00" step="0.01" value="<?= $for_amount; ?>" placeholder="0.00" required />
                        </div>
                        <button type="button" data-bs-target="#expenditureModal" data-bs-toggle="modal" class="btn btn-dark">Add expenditure</button>
                    </div>
                </section>
                <div class="modal fade" id="expenditureModal" tabindex="-1" aria-labelledby="expenditureModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true" style="backdrop-filter: blur(5px);">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content overflow-hidden">
                            <div class="modal-header pb-0 border-0">
                                <h1 class="modal-title h4" id="expenditureModalLabel">Verify add expenditure!</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="inputpin mb-3">
                                    <div>
                                        <?php if (is_capital_given()): ?>
                                            <?php if (is_capital_exhausted($conn, $admin_data['admin_id'])): ?>
                                            <label class="form-label">Enter pin</label>
                                            <div class="d-flex justify-content-between p-4 bg-body-tertiary rounded">
                                                <input type="tel" class="form-control form-control-flush text-xl fw-bold w-rem-40" placeholder="0000" name="pin" id="pin" autocomplete="off" inputmode="numeric" data-maxlength="4" oninput="this.value=this.value.slice(0,this.dataset.maxlength)" required>
                                                <button type="button" class="btn btn-sm btn-light rounded-pill shadow-none flex-none d-flex align-items-center gap-2 p-2" style="border: 1px solid #cbd5e1">
                                                    <img src="<?= PROOT; ?>assets/media/pin.jpg" class="w-rem-6 h-rem-6 rounded-circle" alt="..."> <span>PIN</span>
                                                </button>
                                            </div>
                                            <?php else: ?>
                                                <p class="h4">
                                                    Trade ended: the capital given for today's trade has been exhausted!
                                                </p>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <p class="h4">
                                                Please you are to provide today's capital given before you can complete a trade!
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php if (is_capital_given()): ?>
                                    <?php if (is_capital_exhausted($conn, $admin_data['admin_id'])): ?>
                                        <button type="button" id="submitExpenditure" class="btn btn-warning mt-4">Submit</button>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>



 
    <?php endif; ?>
    <?php endif; ?>

    <?php else: ?>

    <div class="row align-items-center g-6 mt-0 mb-6">
        <div class="col-sm-6">
            <div class="d-flex gap-2">
                <div class="input-group input-group-sm input-group-inline w-100 w-md-50">
                    <span class="input-group-text"><i class="bi bi-search me-2"></i> </span>
                    <input type="search" class="form-control ps-0" placeholder="Search all trades" aria-label="Search" id="search">
                </div>
            </div>
        </div>
    </div>
    <div id="load-content"></div>

    <?php endif; ?>

<?php include ("../includes/footer.inc.php"); ?>

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