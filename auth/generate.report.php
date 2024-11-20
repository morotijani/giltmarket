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
                jspence_daily.daily_capital AS capital, 
                jspence_daily.daily_balance AS balance_sold, 
                jspence_daily.daily_date, 
                jspence_daily.daily_id, 
                jspence_admin.admin_permissions 
            FROM jspence_daily 
            INNER JOIN jspence_admin 
            ON jspence_admin.admin_id = jspence_daily.daily_to
            WHERE jspence_daily.status = ? 
        ";

        // checking on admin selected
        if ($admin != '') {
            $sql .= " AND jspence_daily.daily_to = '" . $admin . "' ";
        }
        
        // checking on dates from and to 
        if ((!empty($from) || $from != '') && (!empty($to) || $to != '')) {
            $sql .= " AND CAST(jspence_daily.createdAt AS date) BETWEEN '" . $from . "' AND '" . $to . "' ";
        } else if ((!empty($from) || $from != '') && (empty($to) || $to == '')) {
            $sql .= " AND CAST(jspence_daily.createdAt AS date) = '" . $from . "' ";
        } else if ((!empty($to) || $to != '') && (empty($from) || $from == '')) {
            $sql .= " AND CAST(jspence_daily.createdAt AS date) = '" . $to . "' ";
        }

        // checking of role permission
        if ($permission != '') {
            $sql .= " AND jspence_admin.admin_permissions = '" . $permission . "'";
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
            foreach ($rows as $row) {

                $sql2 = "
                    SELECT 
                        SUM(jspence_sales.sale_total_amount) AS amount, 
                        SUM(jspence_sales.sale_gram) AS gram, 
                        SUM(jspence_sales.sale_volume) AS volume 
                    FROM jspence_sales 
                    WHERE jspence_sales.sale_daily = ? 
                    AND jspence_sales.sale_status = ? 
                ";
                $statement = $conn->prepare($sql2);
                $statement->execute([$row["daily_id"], 0]);
                $sub_rows = $statement->fetchAll();

                foreach ($sub_rows as $sub_row) {
                    $density = calculateDensity($sub_row["gram"], $sub_row["volume"]);
                    $pounds = calculatePounds($sub_row["gram"]);
                    $carat = calculateCarat($sub_row["gram"], $sub_row["volume"]);

                    // accumulated
                    $td = '';

                    // get earned
                    $earned = 0;
                    if ($row['admin_permissions'] == 'supervisor') {
                        $earned = (float)($row['balance_sold'] - $row['capital']);
                        if ($row['balance_sold'] == null) {
                            $earned = 0;
                        }

                        
                    }

                    // get expenditure
                    $expenditure = 0;

                    if ($row['admin_permissions'] == 'salesperson') {
                        $exp = $conn->query("SELECT SUM(jspence_sales.sale_total_amount) AS exp_amount FROM jspence_sales WHERE jspence_sales.sale_type = 'exp' AND jspence_sales.sale_status = 0")->fetchAll();
                        if (is_array($exp)) {
                            $expenditure = $exp[0]['exp_amount'];
                        }
                       
                    } 
                    $td .= '
                        <td>' . $earned . '</td>
                        <td>' . $expenditure . '</td>
                    ';

                    //
                    if ($role == "supervisor") {
                        $td = '<td>' . $earned . '</td>';
                    } else if ($role == "salesperson") {
                        
                        $td = ((float)$sub_row["amount"] - $expenditure); //expenditure made by salepersonnel
                        $td = '<td>' . $td . '</td>';
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
                    </tbody>
                </table>
            </div>
        ';
        
        echo $output;
    }
