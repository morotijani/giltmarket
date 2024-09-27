<?php 

// Analytics info 

require_once ("../db_connection/conn.php");

include ("../includes/header.inc.php");

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

    $denomination_id = guidv4();
    $by = $admin_data['admin_id'];
    $capital_id = 11; //_capital($by)['today_capital_id'];

    $data = [$denomination_id, $capital_id, $by, $denomination_200c, $denomination_200c_amt, $denomination_100c, $denomination_100c_amt, $denomination_50c, $denomination_50c_amt, $denomination_20c, $denomination_20c_amt, $denomination_10c, $denomination_10c_amt, $denomination_5c, $denomination_5c_amt, $denomination_2c, $denomination_2c_amt, $denomination_1c, $denomination_1c_amt, $denomination_50p, $denomination_50p_amt, $denomination_20p, $denomination_20p_amt, $denomination_10p, $denomination_10p_amt, $denomination_5p, $denomination_5p_amt, $denomination_1p, $denomination_1p_amt];

    $sql = "
        INSERT INTO `jspence_denomination`(`denominations_id`, `denomination_capital`, `denomination_by`, `denomination_200c`, `denomination_200c_amt`, `denomination_100c`, `denomination_100c_amt`, `denomination_50c`, `denomination_50c_amt`, `denomination_20c`, `denomination_20c_amt`, `denomination_10c`, `denomination_10c_amt`, `denomination_5c`, `denomination_5c_amt`, `denomination_2c`, `denomination_2c_amt`, `denomination_1c`, `denomination_1c_amt`, `denomination_50p`, `denomination_50p_amt`, `denomination_20p`, `denomination_20p_amt`, `denomination_10p`, `denomination_10p_amt`, `denomination_5p`, `denomination_5p_amt`, `denomination_1p`, `denomination_1p_amt`) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ";
    $statement = $conn->prepare($sql);
    $result = $statement->execute($data);
    if (isset($result)) {

        $query = "
            UPDATE jspence_daily SET daily_capital_status = ? 
            WHERE daily_id = ?
        ";
        $statement = $conn->prepare($query);
        $statement->execute([1, $capital_id]);

        echo 'done';
    }
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
                    <h1 class="fs-4 mb-0">ID #123</h1>
                </div>
                <div class="col-12 col-sm-auto mt-4 mt-sm-0">
                    <!-- Action -->
                    <a class="btn btn-secondary d-block" href="#!"> <span class="material-symbols-outlined me-1">download</span> Download </a>
                </div>
            </div>

            <!-- Page content -->
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center justify-content-between mb-7">
                        <div class="col-auto">
                            <!-- Icon -->
                            <div class="avatar avatar-xl rounded text-primary">
                                <i class="fs-2" data-duoicon="box-2"></i>
                            </div>
                        </div>
                        <div class="col-auto">
                            <p class="text-end text-body-secondary mb-0">
                            <span class="text-body">Michael Johnson</span> <br />
                            ID No: #123 <br />
                            Date: 12/12/2021
                            </p>
                        </div>
                    </div>
                    <div class="row justify-content-between mb-7">
                        <div class="col-auto">
                            <p class="text-body-secondary mb-0">
                                <span class="fw-bold text-body">From:</span> <br />
                                <span class="text-body">Quantum Dynamics</span>
                            </p>
                        </div>
                        <div class="col-auto">
                            <p class="text-end text-body-secondary mb-0">
                                <span class="fw-bold text-body">To:</span> <br />
                                <span class="text-body">Michael Johnson</span> <br />
                                1234 Main St. <br />
                                Springfield, IL 62701
                            </p>
                        </div>
                    </div>
                    <div class="list-group mb-7">
                        <div class="list-group-item bg-body">
                            <div class="row">
                                <div class="col">Machine Learning Course</div>
                                <div class="col-auto">$99.99</div>
                            </div>
                        </div>
                        <div class="list-group-item bg-body">
                            <div class="row">
                                <div class="col">Web Development Course</div>
                                <div class="col-auto">$159.99</div>
                            </div>
                        </div>
                        <div class="list-group-item bg-body">
                            <div class="row">
                                <div class="col">
                                    <strong>Total</strong>
                                </div>
                                    <div class="col-auto">
                                    <strong>$259.98</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h3 class="fs-base">Notes:</h3>
                    <p class="text-body-secondary mb-0">
                    Thank you for your purchase! <br />
                    Please let us know if you have any questions.
                    </p>
                </div>
            </div>
        </div>
    </main>

<?php include ("../includes/footer.inc.php"); ?>
