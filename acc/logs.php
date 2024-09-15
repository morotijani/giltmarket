<?php 

    // view admin profile details
    require_once ("../db_connection/conn.php");

    if (!admin_is_logged_in()) {
        admn_login_redirect();
    }

    include ("../includes/header.inc.php");
    include ("../includes/nav.inc.php");

    $where = '';
    if (!admin_has_permission()) {
        $where = ' WHERE jspence_admin.admin_id = "'.$admin_data[0]['admin_id'].'" ';
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
                <h1 class="ls-tight">Full system logs</h1>
            </div>
            <div class="col">
                <div class="hstack gap-2 justify-content-end">
                    <a href="<?= goBack(); ?>" class="btn btn-sm btn-neutral d-sm-inline-flex"><span class="pe-2"><i class="bi bi-arrow-90deg-left"></i> </span><span>Go back</span></a> 
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
