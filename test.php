1. by exporting make sure you include year to the month export
2. add reverse delete requests


// $sql = "
        //     SELECT
        //         SUM(jspence_sales.sale_total_amount) AS ta, 
        //         SUM(jspence_daily.daily_balance) AS cb, 
        //         SUM(jspence_daily.daily_capital) AS c, 
        //         SUM(jspence_sales.sale_gram) AS tg, 
        //         SUM(jspence_sales.sale_volume) AS tv, 
        //         daily_date
        //     FROM jspence_daily 
        //     INNER JOIN jspence_sales 
        //     ON jspence_sales.sale_daily = jspence_daily.daily_id 
        //     INNER JOIN jspence_admin 
        //     ON jspence_admin.admin_id = jspence_daily.daily_to
        //     WHERE jspence_daily.status = ? 
        //     AND jspence_sales.sale_status = ? 
        // ";
        
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