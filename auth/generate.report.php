<?php 

    require_once ("../db_connection/conn.php");

    if (!admin_is_logged_in()) {
        admin_login_redirect();
    }
    if (isset($_POST['role'])) {
        
        $role = ((isset($_POST['role']) && !empty($_POST['role'])) ? sanitize($_POST['role']) : '');
        $from = ((isset($_POST['from']) && !empty($_POST['from'])) ? sanitize($_POST['from']) : '');
        $to = ((isset($_POST['to']) && !empty($_POST['to'])) ? sanitize($_POST['to']) : '');

        $sup_admin = ((isset($_POST['sup_admin']) && !empty($_POST['sup_admin'])) ? sanitize($_POST['sup_admin']) : '');
        $sal_admin = ((isset($_POST['sal_admin']) && !empty($_POST['sal_admin'])) ? sanitize($_POST['sal_admin']) : '');

        $th = '
            <th>Earned</th>
            <th>Expenditure</th>
        ';
        $admin = '';
        $permission = '';
        if ($role == "supervisor") {
            $admin = $sup_admin;
            $th = '<th>Earned</th>';
            $permission = 'supervisor';
        } else if ($role == "salesperson") {
            $admin = $sal_admin;
            $th = '<th>Expenditure</th>';
            $permission = 'salesperson';
        }

        $sql = "
            SELECT 
                giltmarket_daily.daily_capital AS capital, 
                giltmarket_daily.daily_balance AS balance_sold, 
                giltmarket_daily.daily_date, 
                giltmarket_daily.daily_id, 
                giltmarket_admin.admin_permissions, 
                giltmarket_daily.daily_date 
            FROM giltmarket_daily 
            INNER JOIN giltmarket_admin 
            ON giltmarket_admin.admin_id = giltmarket_daily.daily_to
            WHERE giltmarket_daily.status = ? 
        ";

        // checking on admin selected
        if ($admin != '') {
            $sql .= " AND giltmarket_daily.daily_to = '" . $admin . "' ";
        }
        
        // checking on dates from and to 
        if ((!empty($from) || $from != '') && (!empty($to) || $to != '')) {
            $sql .= " AND CAST(giltmarket_daily.createdAt AS date) BETWEEN '" . $from . "' AND '" . $to . "' ";
        } else if ((!empty($from) || $from != '') && (empty($to) || $to == '')) {
            $sql .= " AND CAST(giltmarket_daily.createdAt AS date) = '" . $from . "' ";
        } else if ((!empty($to) || $to != '') && (empty($from) || $from == '')) {
            $sql .= " AND CAST(giltmarket_daily.createdAt AS date) = '" . $to . "' ";
        }

        // checking of role permission
        if ($permission != '') {
            $sql .= " AND giltmarket_admin.admin_permissions = '" . $permission . "'";
        }

        $statement = $conn->prepare($sql);
        $statement->execute([0]);
        $rows = $statement->fetchAll();
        $row_count = $statement->rowCount();

        $output = '
            <p class="text-body-secondary">From: ' . $from . ' and To: ' . $to . '</p>
            <hr>
            <div class="table-responsive">
                <table class="table table-flush align-middle mb-0">
                    <thead>
                        <tr>
                            <th></td>
                            <th>Amount given</th>
                            <th>Gram</th>
                            <th>Volume</th>
                            <th>Density</th>
                            <th>Pounds</th>
                            <th>Carat</th>
                            <th>Total trades</th>
                            ' . $th . '
                        </tr>
                    </thead>
                    <tbody>
        ';

        if ($row_count > 0) {

            $total_capital = 0;
            $total_amount = 0;
            $total_gram = 0;
            $total_volume = 0;
            $total_density = 0;
            $total_pounds = 0;
            $total_carat = 0;
            $total_expenditure = 0;
            $total_earned = 0;

            foreach ($rows as $row) {
                $total_capital += $row["capital"];
                $sql2 = "
                    SELECT 
                        SUM(giltmarket_sales.sale_total_amount) AS amount, 
                        SUM(giltmarket_sales.sale_gram) AS gram, 
                        SUM(giltmarket_sales.sale_volume) AS volume 
                    FROM giltmarket_sales 
                    WHERE giltmarket_sales.sale_daily = ? 
                    AND giltmarket_sales.sale_status = ? 
                ";
                $statement = $conn->prepare($sql2);
                $statement->execute([$row["daily_id"], 0]);
                $sub_rows = $statement->fetchAll();
                $sub_count = $statement->rowCount();

                foreach ($sub_rows as $sub_row) {

                    $density = calculateDensity($sub_row["gram"], $sub_row["volume"]);
                    $pounds = calculatePounds($sub_row["gram"]);
                    $carat = calculateCarat($sub_row["gram"], $sub_row["volume"]);

                    $total_amount += $sub_row["amount"];
                    $total_gram += $sub_row["gram"];
                    $total_volume += $sub_row["volume"];
                    $total_density += $density;
                    $total_pounds += $pounds;
                    $total_carat += $carat;

                    // accumulated
                    $td = '';
                    $ttd = '';

                    // get earned
                    $earned = 0;
                    if ($row['admin_permissions'] == 'supervisor') {
                        $earned = (float)($row['balance_sold'] - $row['capital']);
                        $total_earned += $earned;
                        if ($row['balance_sold'] == null) {
                            $earned = 0;
                        }
                    }

                    // get expenditure
                    $expenditure = 0;
                    if ($row['admin_permissions'] == 'salesperson') {
                        $exp = $conn->query("SELECT SUM(giltmarket_sales.sale_total_amount) AS exp_amount FROM giltmarket_sales WHERE giltmarket_sales.sale_type = 'exp' AND giltmarket_sales.sale_daily = '" .$row["daily_id"] . "' AND giltmarket_sales.sale_status = 0")->fetchAll();
                        if (is_array($exp)) {
                            $expenditure = $exp[0]['exp_amount'];
                            $total_expenditure += $expenditure;
                        }
                    }

                    //
                    $td .= '
                        <td>' . money($earned) . '</td>
                        <td>' . money($expenditure) . '</td>
                    ';

                    //
                    $ttd .= '
                        <td class="bg-warning-subtle">' . money($total_earned) . '</td>
                        <td class="bg-warning-subtle">' . money($total_expenditure) . '</td>
                    ';

                    //
                    if ($role == "supervisor") {
                        $td = '<td>' . money($earned) . '</td>';
                        $ttd = '<td class="bg-warning-subtle">' . money($total_earned) . '</td>';
                    } else if ($role == "salesperson") {
                        // $td = ((float)$sub_row["amount"] - $expenditure); //expenditure made by salepersonnel
                        $td = '<td>' . money($expenditure) . '</td>';
                        $ttd = '<td class="bg-warning-subtle">' . money($total_expenditure) . '</td>';
                    }

                    $output .= '
                        <tr>
                            <td>' . pretty_date_only($row["daily_date"]) . '</td>
                            <td>' . money($row["capital"]) . '</td>
                            <td>' . $sub_row["gram"] . '</td>
                            <td>' . $sub_row["volume"] . '</td>
                            <td>' . $density . '</td>
                            <td>' . $pounds . '</td>
                            <td>' . $carat . '</td>
                            <td>' . money($sub_row["amount"]) . '</td>
                            ' . $td . '
                        </tr>
                    ';
                }
            }
        } else {
            $output .= '
                    <tr>
                        <td colspan="8">No data found!</td>
                    </tr>
                ';
        }
        $output .= '
                        <tr>
                            <td  class="bg-warning-subtle">Grand Total</td>
                            <td  class="bg-warning-subtle">' . money($total_capital) . '</td>
                            <td  class="bg-warning-subtle">' . $total_gram . '</td>
                            <td  class="bg-warning-subtle">' . $total_volume . '</td>
                            <td  class="bg-warning-subtle">' . $total_density . '</td>
                            <td  class="bg-warning-subtle">' . $total_pounds . '</td>
                            <td  class="bg-warning-subtle">' . $total_carat . '</td>
                            <td  class="bg-warning-subtle">' . money($total_amount) . '</td>
                            ' . $ttd . '
                        </tr>
                    </tbody>
                </table>
            </div>
        ';
        
        echo $output;
    }
