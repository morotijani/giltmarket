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

        $th = '';
        $admin = '1';
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
                SUM(jspence_sales.sale_total_amount) AS ta, 
                SUM(jspence_daily.daily_balance) AS cb, 
                SUM(jspence_daily.daily_capital) AS c, 
                SUM(jspence_sales.sale_gram) AS tg, 
                SUM(jspence_sales.sale_volume) AS tv, 
                daily_date
            FROM jspence_daily 
            INNER JOIN jspence_sales 
            ON jspence_sales.sale_daily = jspence_daily.daily_id 
            INNER JOIN jspence_admin 
            ON jspence_admin.admin_id = jspence_daily.daily_to
            WHERE jspence_daily.status = ? 
            AND jspence_sales.sale_status = ? 
        ";

        if ($admin != '') {
            $sql .= " AND jspence_daily.daily_to = '" . $admin . "' AND jspence_sales.sale_by = '" . $admin . "' AND jspence_admin.admin_id = '" . $admin . "' ";
        }

        if ((!empty($from) || $from != '') && (!empty($to) || $to != '')) {
            $sql .= " AND CAST(jspence_sales.createdAt AS date) BETWEEN '" . $from . "' AND '" . $to . "' ";
        } else if ((!empty($from) || $from != '') && (empty($to) || $to == '')) {
            $sql .= " AND CAST(jspence_sales.createdAt AS date) = '" . $from . "' ";
        } else if ((!empty($to) || $to != '') && (empty($from) || $from == '')) {
            $sql .= " AND CAST(jspence_sales.createdAt AS date) = '" . $to . "' ";
        }
        $sql .= " AND admin_permissions = '" . $permission . "'";

        $statement = $conn->prepare($sql);
        $statement->execute([0, 0]);
        $rows = $statement->fetchAll();
        $row_count = $statement->rowCount();

        dnd($rows);


        $output = '
            <p class="text-body-secondary">From: ' . $from . ' and To: ' . $to . '</p>
            <hr>
            <div class="table-responsive">
                <table class="table">
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

                $density = calculateDensity($row["tg"], $row["tv"]);
                $pounds = calculatePounds($row["tg"]);
                $carat = calculateCarat($row["tg"], $row["tv"]);

                // accumulated
                $earned = 0;
                if ($role == "supervisor") {
                    $earned = (float)($row['cb'] - $row['c']);
                    if ($row['cb'] == null) {
                        $earned = 0;
                    }
                } else if ($role == "salesperson") {
                    $earned = $row["ta"] - 0; //expenditure made by salepersonnel
                }

                $output .= '
                    <tr>
                        <td>' . pretty_date_only($row["daily_date"]) . '</td>
                        <td>' . money($row["c"]) . '</td>
                        <td>' . $row["tg"] . '</td>
                        <td>' . $row["tv"] . '</td>
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
            </div>
        ';
        
        echo $output;
    }
