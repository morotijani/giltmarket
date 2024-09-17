<?php 

    // view admin profile details
    require_once ("../db_connection/conn.php");

    if (!admin_is_logged_in()) {
        admn_login_redirect();
    }

    include ("../includes/header.inc.php");
    include ("../includes/nav.inc.php");

    //
    if (isset($_GET['delete_request']) && !empty($_GET['delete_request'])) {
        // code...
        $id = sanitize($_GET['delete_request']);

        $findSale = $conn->query("SELECT * FROM jspence_sales WHERE sale_id = '".$id."'")->rowCount();
        if ($findSale > 0) {
            // code...
            $sql = "
                UPDATE jspence_sales 
                SET sale_status = ?, sale_delete_request_status = ?
                WHERE sale_id = ?
            ";
            $statement = $conn->prepare($sql);
            $result = $statement->execute([1, 1, $id]);
            if (isset($result)) {
                // code...
                
                $message = "delete request for trade id: '".$id."'";
                add_to_log($message, $admin_data[0]['admin_id']);

                $_SESSION['flash_success'] = ' Sale delete request successfully sent!';
                redirect(PROOT . 'acc/trades');
            } else {
                echo js_alert("Something went wrong, please try again!");
            }
        } else {
            $_SESSION['flash_error'] = ' Could not find sale to send a delete request!';
            redirect(PROOT . 'acc/trades');
        }

    }



?>
<!-- <a href="#" onClick="MyWindow=window.open('http://www.google.com','MyWindow','width=600,height=300'); return false;">Click Here</a>
<a href="#" onClick="MyWindow=window.open('http://www.google.com','MyWindow','width=600,height=300'); return false;">Click Here</a> -->

    <div class="px-6 px-lg-7 pt-8 border-bottom">
        <div class="d-flex align-items-center">
            <h1>Trades</h1>
            <div class="hstack gap-2 ms-auto">
                <?php if (admin_has_permission()): ?>
                <div class="dropdown">
                    <button class="btn btn-sm btn-neutral flex-none d-flex align-items-center gap-2 py-1 px-2" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="<?= PROOT; ?>dist/media/export.png" class="w-rem-5 h-rem-5 rounded-circle" alt="..."> <span>Export</span> <i class="bi bi-chevron-down text-xs me-1"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-sm">
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2" href="<?= PROOT; ?>acc/export/all/xlsx">
                                <img src="<?= PROOT; ?>dist/media/XLSX.png" class="w-rem-6 h-rem-6 rounded-circle" alt="..."> <span>XLSX</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2" href="<?= PROOT; ?>acc/export/all/xls">
                                <img src="<?= PROOT; ?>dist/media/XLS.png" class="w-rem-6 h-rem-6 rounded-circle" alt="..."> <span>XLS</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2" href="<?= PROOT; ?>acc/export/all/csv">
                                <img src="<?= PROOT; ?>dist/media/CSV.png" class="w-rem-6 h-rem-6 rounded-circle" alt="..."> <span>CSV</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2" href="<?= PROOT; ?>acc/export/all/pdf">
                                <img src="<?= PROOT; ?>dist/media/CSV.png" class="w-rem-6 h-rem-6 rounded-circle" alt="..."> <span>PDF</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <?php endif ?>
                <button type="button" class="btn btn-sm btn-primary d-none d-sm-inline-flex" data-bs-target="#buyModal" data-bs-toggle="modal"><span class="pe-2"><i class="bi bi-plus-circle"></i> </span><span>Trade</span></button>
            </div>
        </div>
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

<?php include ("../includes/footer.inc.php"); ?>


<script>
    
    // SEARCH AND PAGINATION FOR LIST
    function load_data(page, query = '') {
        $.ajax({
            url : "<?= PROOT; ?>auth/trade.lists.php",
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
