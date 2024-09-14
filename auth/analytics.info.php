<?php 

// Analytics info 

require_once ("../db_connection/conn.php");

if (isset($_POST['dater'])) {
    $dater = sanitize($_POST['dater']);
    $action = (isset($_POST['action']) ? $_POST['action'] : '');

    $andDaily = '';
    $andExpenditure = '';
    $and = '';
    if ($action == 'with_date' && $dater != null) {
        $and = ' AND CAST(jspence_sales.createdAt AS date) = "'.$dater.'"';
        $andDaily = ' AND daily_date = "'.$dater.'"';
        $andExpenditure = ' AND CAST(jspence_expenditures.createdAt AS date) = "'.$dater.'"';
    } else if ($action == 'with_month') {
        $and = ' AND MONTH(jspence_sales.createdAt) = "'.$dater.'"';
        $andDaily = ' AND MONTH(daily_date) = "'.$dater.'"';
        $andExpenditure = ' AND MONTH(jspence_expenditures.createdAt) = "'.$dater.'"';
    }

    $supervisorQuery = "
        SELECT SUM(daily_capital) AS capital, SUM(daily_balance) AS balance 
        FROM jspence_daily 
        INNER JOIN jspence_admin 
        ON jspence_admin.admin_id = jspence_daily.daily_by
        WHERE jspence_daily.status = ? 
        AND jspence_admin.admin_permissions = ?
        $andDaily
    ";
    $statement = $conn->prepare($supervisorQuery);
    $result = $statement->execute([0, 'supervisor']);
    $sup_rows = $statement->fetchAll();
    $sup_row = $sup_rows[0];

    $sales = $conn->query(
        "SELECT SUM(daily_capital) AS capital, SUM(daily_balance) AS balance 
        FROM jspence_daily 
        INNER JOIN jspence_admin 
        ON jspence_admin.admin_id = jspence_daily.daily_by
        WHERE jspence_daily.status = 0 
        AND jspence_admin.admin_permissions = 'salesperson'
        $andDaily
        "
    )->fetchAll();


    $ins = $conn->query("SELECT SUM(sale_total_amount) AS ins_amt, CAST(jspence_sales.createdAt AS date) AS in_d FROM jspence_sales WHERE sale_type = 'in' AND sale_status = 0 $and")->fetchAll();
    $outs = $conn->query("SELECT SUM(sale_total_amount) AS outs_amt, CAST(jspence_sales.createdAt AS date) AS out_d FROM jspence_sales WHERE sale_type = 'out' AND sale_status = 0 $and")->fetchAll();
    $expense = $conn->query("SELECT SUM(expenditure_amount) AS exp_amt, CAST(jspence_expenditures.createdAt AS date) AS exp_d FROM jspence_expenditures WHERE status = 0 $andExpenditure")->fetchAll();
    $count_trades = $conn->query("SELECT *, CAST(jspence_sales.createdAt AS date) AS c_d FROM jspence_sales WHERE sale_status = 0 $and")->rowCount();

    $gained_or_loss = 0;
    $in = (($ins[0]['ins_amt']) ? $ins[0]['ins_amt'] : 0);
    $out = (($outs[0]['outs_amt']) ? $outs[0]['outs_amt'] : 0);
    $expenses = (($expense[0]['exp_amt']) ? $expense[0]['exp_amt'] : 0);

    $total_sales_capital = $sales[0]['capital'] ?? 0;
    $total_sales_balance = $sales[0]['balance'] ?? 0;

    $total_supervisor_capital = $sup_row['capital'] ?? 0;
    $total_supervisor_balance = $sup_row['balance'] ?? 0;

    $out = (float)($out + $expenses);

    $arrayOutput = [
        'supervisor_capital' => $total_supervisor_capital,
        'supervisor_balance' => $total_supervisor_balance,
        'sales_capital' => $total_sales_capital,
        'sales_balance' => $total_sales_balance,
        'gained_or_loss' => 0,
        'in' => $in,
        'out' => $out,
        'trades' => $count_trades,
        'expenses' => $expenses
    ];

    if ($statement->rowCount() > 0) {
        $gained_or_loss = (float)($sup_row['capital'] - $sup_row['balance']);
    }

    $arrayOutput = [
        'supervisor_capital' => money($total_supervisor_capital),
        'supervisor_balance' => money($total_supervisor_balance),
        'sales_capital' => money($total_sales_capital),
        'sales_balance' => money($total_sales_balance),
        'gained_or_loss' => money($gained_or_loss),
        'in' => money($in),
        'out' => money($out),
        'trades' => $count_trades,
        'expenses' => money($expenses)
    ];

    $ouput = json_encode($arrayOutput);
    echo $ouput;
}
