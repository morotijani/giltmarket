<?php 

    require_once ("../db_connection/conn.php");

    if (!admin_is_logged_in()) {
        admin_login_redirect();
    }
    if (isset($_POST['role'])) {
        
        $role = ((isset($_POST['role']) && !empty($_POST['role'])) ? sanitize($_POST['role']) : '');
        $from = ((isset($_POST['from']) && !empty($_POST['from'])) ? sanitize($_POST['from']) : '');
        $to = ((isset($_POST['to']) && !empty($_POST['to'])) ? sanitize($_POST['to']) : '');

        if ($role == "supervisor") {
            $admin = ((isset($_POST['sup_admin']) && !empty($_POST['sup_admin'])) ? sanitize($_POST['sup_admin']) : '');
            $th = '<th></th>'
        } else if ($role == "salesperson") {
            $admin = ((isset($_POST['sal_admin']) && !empty($_POST['sal_admin'])) ? sanitize($_POST['sal_admin']) : '');
        }

        $sql = "
            SELECT *,
                SUM(sale_total_amount) AS ta, 
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
                        <th>Expenditure</th>
                        <th>Accumulated Money/Gold</th>
                        <th>Earned</th>
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
                $accumulated = 0;
                if ($role == "supervisor") {
                    $accumulated = money($row["ta"]);
                } else if ($role == "salesperson") {
                    $accumulated = $row["ta"] - $ex;
                }

                // accumulated
                $earned = 0;
                if ($role == "supervisor") {
                    $earned = $row["daily_capital"] - $row["ta"];
                    $earned = money($row["ta"]);
                }

                $output .= '
                    <tr>
                        <td>' . money($row["daily_capital"]) . '</td>
                        <td>' . $row["sg"] . '</td>
                        <td>' . $row["sv"] . '</td>
                        <td>' . $density . '</td>
                        <td>' . $pounds . '</td>
                        <td>' . $carat . '</td>
                        <td>' . money($row["ta"]) . '</td>
                        <td>' . $accumulated . '</td>
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
