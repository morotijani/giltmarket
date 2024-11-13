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
        } else if ($role == "salesperson") {
            $admin = ((isset($_POST['sal_admin']) && !empty($_POST['sal_admin'])) ? sanitize($_POST['sal_admin']) : '');
        }

        $sql = "
            SELECT * FROM jspence_sales 
            INNER JOIN jspence_daily 
            ON jspence_daily.daily_to = jspence_sales.sale_by
            WHERE daily_to = ? 
            AND sale_by = ?

        ";
        if ((!empty($from) || $from != '') && (!empty($to) || $to != '')) {
            $sql .= " AND CAST(jspence_sales.createdAt AS date) BETWEEN '" . $from . "' AND '" . $to . "' ";
        } else if ((!empty($from) || $from != '') && (empty($to) || $to == '')) {
            $sql .= " AND CAST(jspence_sales.createdAt AS date) = '" . $from . "' ";
        } else if ((!empty($to) || $to != '') && (empty($from) || $from == '')) {
            $sql .= " AND CAST(jspence_sales.createdAt AS date) = '" . $to . "' ";
        }

        $statement = $conn->prepare($sql);
        $statement->execute([$admin, $admin]);
        $rows = $statement->fetchAll();

        $output = '
            <p class="text-body-secondary">From: ' . $from . ' and To: ' . $to . '</p>
            <hr>
            <table class="table">
                <thead>
                    <tr>
                        <th>Price</th>
                        <th>Gram</th>
                        <th>Volume</th>
                        <th>Density</th>
                        <th>Pounds</th>
                        <th>Carat</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody>
        ';

        foreach ($rows as $row) {
            $output .= '
                <tr>
                    <td>' . $row["sale_price"] . '</td>
                    <td>' . $row["sale_gram"] . '</td>
                    <td>' . $row["sale_volume"] . '</td>
                    <td>' . $row["sale_density"] . '</td>
                    <td>' . $row["sale_pounds"] . '</td>
                    <td>' . $row["sale_carat"] . '</td>
                    <td>' . money($row["sale_total_amount"]) . '</td>
                </tr>
            ';
        }
        $output .= '
                </tbody>
            </table>
        ';
    }

    echo $output;
