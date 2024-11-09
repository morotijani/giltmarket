<?php 

    // REPORT

    require_once ("../db_connection/conn.php");

    if (!admin_is_logged_in()) {
        admin_login_redirect();
    }

    include ("includes/header.inc.php");
    include ("includes/nav.inc.php");
    include ("includes/left-side-bar.inc.php");


?>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Reports</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <a href="<?= PROOT; ?>gpmin/index" class="btn btn-sm btn-outline-secondary">Home</a>
                    <a href="<?= PROOT; ?>gpmin/reports" class="btn btn-sm btn-outline-secondary">Refresh</a>
                </div>
                <a href="<?= PROOT; ?>gpmin/orders" class="btn btn-sm btn-outline-secondary">
                    <span data-feather="activity"></span>
                    Orders
                </a>
            </div>
        </div>

        <?php 
            $thisYr = date("Y");
            $lastYr = $thisYr - 1;

            $thisYrQ = "
                SELECT transaction_grand_total, transaction_txn_date 
                FROM garypie_transaction 
                WHERE YEAR(transaction_txn_date) = '{$thisYr}'
                AND transaction_intent = 'Paid'
            ";
            $statement = $conn->prepare($thisYrQ);
            $statement->execute();
            $thisYr_result = $statement->fetchAll();

           // dnd($thisYr_result);
            

            $lastYrQ = "
                SELECT transaction_grand_total, transaction_txn_date 
                FROM garypie_transaction 
                WHERE YEAR(transaction_txn_date) = '{$lastYr}'
                AND transaction_intent = 'Paid'
            ";
            $statement = $conn->prepare($lastYrQ);
            $statement->execute();
            $lastYr_result = $statement->fetchAll();

            $current = array();
            $last = array();

            $currentTotal = 0;
            $lastTotal = 0;

            foreach ($thisYr_result as $thisYr_row) {
                $month = date("m", strtotime($thisYr_row['transaction_txn_date']));
                if (!array_key_exists((int)$month, $current)) {
                    $current[(int)$month] = $thisYr_row['transaction_grand_total'];
                } else {
                    $current[(int)$month] += $thisYr_row['transaction_grand_total'];
                }
                $currentTotal += $thisYr_row['transaction_grand_total'];
            }


            foreach ($lastYr_result as $lastYr_row) {
                $month = date("m", strtotime($lastYr_row['transaction_txn_date']));
                if (!array_key_exists((int)$month, $last)) {
                    $last[(int)$month] = $lastYr_row['transaction_grand_total'];
                } else {
                    $last[(int)$month] += $lastYr_row['transaction_grand_total'];
                }
                $lastTotal += $lastYr_row['transaction_grand_total'];
            }

        ?>
        
        <div class="row">
            <div class="col-md-4">
                <h4>Sales by month (report on table)</h4>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-sm">
                        <thead>
                            <tr>
                                <th scope="col"></th>
                                <th scope="col"><?= $lastYr; ?></th>
                                <th scope="col"><?= $thisYr; ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php for ($i = 1; $i <= 12; $i++):
                                $dt = dateTime::createFromFormat('!m',$i);
                            ?>
                                <tr <?= (date('m') == $i) ? ' class="bg-info"' : ''; ?>>
                                    <td><?= $dt->format("F"); ?></td>
                                    <td><?= ((array_key_exists($i, $last)) ? money($last[$i]) : money(0)); ?></td>
                                    <td><?=  ((array_key_exists($i, $current)) ? money($current[$i]) : money(0)); ?></td>
                                </tr>
                            <?php endfor; ?>
                            <tr>
                                <td>Total</td>
                                <td><?= money($lastTotal); ?></td>
                                <td><?= money($currentTotal); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-8">
                <h4>Low Inventory</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th scope="col">Product</th>
                                <th scope="col">Category</th>
                                <th scope="col">Size</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Threshold</th>
                            </tr>
                        </thead>
                <tbody>
                    <?php $lowItems = low_inventory_access(); foreach ($lowItems as $item): ?>
                        <tr <?= ($item['quantity'] == 0)?'class="table-danger"':''; ?>>
                            <td><?= ucwords($item['title']); ?></td>
                            <td><?= ucwords($item['category']); ?></td>
                            <td><?= $item['size']; ?></td>
                            <td><?= $item['quantity']; ?></td>
                            <td><?= $item['threshold']; ?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
                    </table>
                </div>
            </div>
        </div>

    </main>
<?php 

    include ("includes/footer.inc.php");

?>
