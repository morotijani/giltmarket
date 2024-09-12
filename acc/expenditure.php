<?php 

    // view admin profile details
    require_once ("../db_connection/conn.php");

    if (!admin_is_logged_in()) {
        admn_login_redirect();
    }

    include ("../includes/header.inc.php");
    include ("../includes/nav.inc.php");

    $where = '';
    if ($admin_data[0]['admin_permissions'] != 'admin,salesperson') {
        $where = ' WHERE jspence_admin.admin_id = "'.$admin_data[0]['admin_id'].'" ';
        // code...
    }

    $sql = "
        SELECT * FROM jspence_logs 
        INNER JOIN jspence_admin 
        ON jspence_admin.admin_id = jspence_logs.log_admin
        $where 
        ORDER BY jspence_logs.createdAt DESC
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
                            <h6 class="h5 text-limit fw-semibold mb-1">Standard Plan</h6>
                            <p class="text-sm text-muted d-none d-sm-block">The perfect way to get started.</p>
                        </div>
                        <div class="ms-sm-auto">
                            <div class="d-flex align-items-center mt-5 mb-3 lh-none text-heading d-block display-5 ls-tight mb-0">
                                <span class="fw-semibold text-2xl align-self-start mt-1 me-1">$</span> <span>59</span> <span class="d-inline-block fw-normal text-muted text-lg mt-sm-3 ms-1">/ month</span>
                            </div>
                        </div>
                    </div>

                    <div class="border rounded">
                        <div><div class="textarea-autosize" data-replicated-value=""><textarea class="form-control border-0 shadow-none p-4" rows="3" placeholder="Enter description" oninput="this.parentNode.dataset.replicatedValue = this.value"></textarea></div><div class="d-flex align-items-center px-6 py-3 border-top"><div class="flex-fill d-flex align-items-center"><h6 class="fw-semibold text-xs text-muted text-opacity-60">Markdown formatting</h6></div><div class="text-end"><div class="hstack gap-5 align-items-center"><a href="#!" class="text-lg text-muted text-primary-hover"><i class="bi bi-images"></i> </a><a href="#!" class="text-lg text-muted text-primary-hover"><i class="bi bi-emoji-smile"></i> </a><a href="#!" class="text-lg text-muted text-primary-hover"><i class="bi bi-paperclip"></i></a></div></div></div></div></div>
                    
                    <div class="d-flex align-items-center justify-content-between mb-1">
                        <span class="text-sm text-muted fw-semibold text-heading d-block">30 days</span> <span class="text-sm text-muted fw-semibold d-block">12 days left</span>
                    </div>


                    <div class="progress progress-sm shadow-none mb-6">
                        <div class="progress-bar bg-primary" role="progressbar" style="width:70%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <hr class="my-4">
                    <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between">
                        <a href="#" class="text-muted text-danger-hover text-sm fw-semibold">Cancel plan</a> 
                        <a href="#" class="btn btn-sm btn-neutral">Change plan</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-body pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5>Logs (<?= $count_row; ?>)</h5>
                        </div>
                        <div class="hstack align-items-center">
                            <a href="<?= PROOT; ?>acc/logs" class="text-muted">
                                <i class="bi bi-arrow-repeat"></i>
                            </a>
                        </div>
                    </div>
                    <div class="list-group list-group-flush">
                        <?php foreach ($rows as $row): ?>
                            
                        <div class="list-group-item d-flex align-items-center justify-content-between gap-6">
                            <div class="d-flex align-items-center gap-3">
                                <div class="">
                                    <span class="text-heading text-xs fw-semibold"><em><?= ucwords($row['admin_fullname']); ?> </em></span>
                                    <span class="text-muted text-xs"><em><?= $row['log_message']; ?></em></span>
                                </div>
                            </div>
                            <div class="text-xs"><em><?= pretty_date($row['createdAt']); ?></em></div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php 

    include ("../includes/footer.inc.php");

?>
