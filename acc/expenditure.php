<?php 

    // view admin profile details
    require_once ("../db_connection/conn.php");

    if (!admin_is_logged_in()) {
        admn_login_redirect();
    }

    include ("../includes/header.inc.php");
    include ("../includes/nav.inc.php");

    $where = '';
    if ($admin_data[0]['admin_permissions'] != 'admin,salesperson,supervisor') {
        $where = ' WHERE jspence_admin.admin_id = "'.$admin_data[0]['admin_id'].'" ';
    }

    $sql = "
        SELECT * FROM jspence_expenditures 
        INNER JOIN jspence_admin 
        ON jspence_admin.admin_id = jspence_expenditures.expenditure_by
        $where 
        ORDER BY jspence_expenditures.createdAt DESC
    ";
    $statement = $conn->prepare($sql);
    $statement->execute();
    $count_row = $statement->rowCount();
    $rows = $statement->fetchAll();

?>
    
    <div class="mb-6 mb-xl-10">
        <div class="row g-3 align-items-center">
            <div class="col">
                <h1 class="ls-tight">Expenditure</h1>
            </div>
            <div class="col">
                <div class="hstack gap-2 justify-content-end">
                    <a href="<?= goBack(); ?>" class="btn btn-sm btn-neutral d-sm-inline-flex"><span class="pe-2"><i class="bi bi-arrow-90deg-left"></i> </span><span>Go back</span></a>
                </div>
            </div>
        </div>
    </div>

    <div class="row row-cols-md-1 g-6">
        <div class="col">
            <div class="card">
                <div class="card-body py-4">
                    <div class="d-flex flex-wrap align-items-center justify-content-between mb-5">
                        <div class="flex-1">
                            <h6 class="h5 text-limit fw-semibold mb-1">Creat an Expenditure</h6>
                            <p class="text-sm text-muted d-none d-sm-block">Fill in the below fields to make an expenditure</p>
                        </div>
                        <div class="ms-sm-auto">
                            <div class="d-flex align-items-center mt-5 mb-3 lh-none text-heading d-block display-5 ls-tight mb-0">
                                <span class="fw-semibold text-2xl align-self-start mt-1 me-1"></span> <span><?= (is_capital_given() ? money(_capital()['today_capital']) : '' ); ?></span> <span class="d-inline-block fw-normal text-muted text-lg mt-sm-3 ms-1">/ <?= date('Y-m-d'); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="border rounded">
                        <div>
                            <div class="textarea-autosize">
                                <textarea class="form-control border-0 shadow-none p-4" rows="3" placeholder="Enter description" oninput="this.parentNode.dataset.replicatedValue = this.value"></textarea>
                            </div>
                            <div class="d-flex align-items-center px-6 py-3 border-top">
                                <div class="flex-fill align-items-center">
                                    <input class="form-control form-control-flush text-lg fw-bold" name="today_given" id="today_given" type="number" min="0.00" step="0.01" value="" placeholder="0.00">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="my-4"></div>
                    <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between">
                        <a href="<?= PROOT; ?>" class="text-muted text-danger-hover text-sm fw-semibold">Go dashboard</a> 
                        <a href="#" class="btn btn-sm btn-neutral">Add expenditure</a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="d-flex align-items-end justify-content-between mt-10 mb-4">
        <div>
            <h4 class="fw-semibold mb-1">List (<?= $count_row; ?>)</h4>
            <p class="text-sm text-muted">By filling your data you get a much better experience using our website.</p>
        </div>
        <div class="d-none d-md-flex gap-2">
            <button type="button" class="btn btn-sm btn-dark"><i class="bi bi-download me-2"></i>Export all</button>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-nowrap table-flush">
            <thead>
                <tr>
                    <th scope="col">Invoice</span></th>
                    <th scope="col">What for</th>
                    <th scope="col">Date</th>
                    <th scope="col">Time</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="p-1">
                        <div class="d-flex align-items-center gap-3 ps-1">
                            <div class="icon icon-shape w-rem-10 h-rem-10 rounded-circle text-sm bg-primary bg-opacity-25 text-tertiary">
                                <i class="bi bi-file-fill"></i>
                            </div>
                            <div>
                                <span class="d-block text-heading fw-bold">Invoice ABC 00021</span> 
                                <span class="text-xs text-muted">by 10/01/2021</span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <a class="text-current" href="#">Dribbble</a>
                    </td>
                    <td>$1.274,89</td>
                    <td>$323,00</td>
                    <td class="text-end">
                        <a href="<?= PROOT; ?>acc/expenditure?edit=<?= '1'; ?>" class="badge bg-body-secondary text-xs text-success">Edit </a>
                        <a href="<?= PROOT; ?>acc/expenditure?delete=<?= '1'; ?>" class="badge bg-body-secondary text-xs text-danger">Delete </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

<?php include ("../includes/footer.inc.php"); ?>
