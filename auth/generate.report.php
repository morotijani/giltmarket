<?php 

    require_once ("../db_connection/conn.php");

    if (!admin_is_logged_in()) {
        admin_login_redirect();
    }
    if (isset($_POST['role'])) {
        
        $role = ((isset($_POST['role']) && !empty($_POST['role'])) ? sanitize($_POST['role']) : '');
        $from = ((isset($_POST['from']) && !empty($_POST['from'])) ? sanitize($_POST['from']) : '');
        $to = ((isset($_POST['to']) && !empty($_POST['to'])) ? sanitize($_POST['to']) : '');

        $th = '';
        if ($role == "supervisor") {
            $admin = ((isset($_POST['sup_admin']) && !empty($_POST['sup_admin'])) ? sanitize($_POST['sup_admin']) : '');
            $th = '<th>Earned</th>';
        } else if ($role == "salesperson") {
            $admin = ((isset($_POST['sal_admin']) && !empty($_POST['sal_admin'])) ? sanitize($_POST['sal_admin']) : '');
            $th = '<th>Expenditure</th>';
        }

        $sql = "
            SELECT *,
                SUM(sale_total_amount) AS ta, 
                SUM(daily_balance) AS cb, 
                SUM(daily_capital) AS c
                SUM(sale_gram) AS tg, 
                SUM(sale_volume) AS tv, 
            FROM jspence_daily 
            INNER JOIN jspence_sales 
            ON jspence_sales.sale_by = jspence_daily.daily_to 
            WHERE jspence_daily.status = ? 
            AND jspence_sales.sale_status = ? 
        ";

        if ($admin != '') {
            $sql .= " AND jspence_daily.daily_to = '" . $admin . "' AND jspence_sales.sale_by = '" . $admin . "' ";
        }

        if ((!empty($from) || $from != '') && (!empty($to) || $to != '')) {
            $sql .= " AND CAST(jspence_sales.createdAt AS date) BETWEEN '" . $from . "' AND '" . $to . "' ";
        } else if ((!empty($from) || $from != '') && (empty($to) || $to == '')) {
            $sql .= " AND CAST(jspence_sales.createdAt AS date) = '" . $from . "' ";
        } else if ((!empty($to) || $to != '') && (empty($from) || $from == '')) {
            $sql .= " AND CAST(jspence_sales.createdAt AS date) = '" . $to . "' ";
        }

        $statement = $conn->prepare($sql);
        $statement->execute([0, 0]);
        $rows = $statement->fetchAll();
        $row_count = $statement->rowCount();


        $output = '
            <p class="text-body-secondary">From: ' . $from . ' and To: ' . $to . '</p>
            <hr>
            <table class="table">
                <thead>
                    <tr>
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

                $density = calculateDensity($row["sg"], $row["sv"]);
                $pounds = calculatePounds($row["sg"]);
                $carat = calculateCarat($row["sg"], $row["sv"]);

                // accumulated
                $earned = 0;
                if ($role == "supervisor") {
                    $earned = (float)($row['cb'] - $row['c']);
                    if ($row['cb'] == null) {
                        $earned = 0;
                    }
                } else if ($role == "salesperson") {
                    $earned = $row["ta"] - $ex; //expenditure made by salepersonnel
                }

                $output .= '
                    <tr>
                        <td>' . money($row["c"]) . '</td>
                        <td>' . $row["sg"] . '</td>
                        <td>' . $row["sv"] . '</td>
                        <td>' . $density . '</td>
                        <td>' . $pounds . '</td>
                        <td>' . $carat . '</td>
                        <td>' . money($row["ta"]) . '</td>
                        <td>' . money($earned) . '</td>
                    </tr>
                ';
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
        ';
        
        echo $output;
    }
