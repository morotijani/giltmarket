<?php 

// Analytics info 

require_once ("../db_connection/conn.php");

if (isset($_POST['dater'])) {
    $dater = sanitize($_POST['dater']);

    $andDaily = '';
    $andExpenditure = '';
    $and = '';
    if ($dater != null) {
        $and = ' AND CAST(jspence_sales.createdAt AS date) = "'.$dater.'"';
        $andDaily = ' AND daily_date = "'.$dater.'"';
        $andExpenditure = ' AND CAST(jspence_expenditures.createdAt AS date) = "'.$dater.'"';
    }

    $sql = "
        SELECT SUM(daily_capital) AS capital, SUM(daily_balance) AS balance 
        FROM jspence_daily 
        WHERE status = ? 
        $andDaily
    ";
    $statement = $conn->prepare($sql);
    $result = $statement->execute([0]);
    $rows = $statement->fetchAll();
    $row = $rows[0];

    $ins = $conn->query("SELECT SUM(sale_total_amount) AS ins_amt, CAST(jspence_sales.createdAt AS date) AS in_d FROM jspence_sales WHERE sale_type = 'in' AND sale_status = 0 $and")->fetchAll();
    $outs = $conn->query("SELECT SUM(sale_total_amount) AS outs_amt, CAST(jspence_sales.createdAt AS date) AS out_d FROM jspence_sales WHERE sale_type = 'out' AND sale_status = 0 $and")->fetchAll();
    $expense = $conn->query("SELECT SUM(expenditure_amount) AS exp_amt, CAST(jspence_expenditures.createdAt AS date) AS exp_d FROM jspence_expenditures WHERE status = 0 $andExpenditure")->fetchAll();
    $count_trades = $conn->query("SELECT *, CAST(jspence_sales.createdAt AS date) AS c_d FROM jspence_sales WHERE sale_status = 0 $and")->rowCount();

    $gained_or_loss = 0;
    $in = (($ins[0]['ins_amt']) ? $ins[0]['ins_amt'] : 0);
    $out = (($outs[0]['outs_amt']) ? $outs[0]['outs_amt'] : 0);
    $expenses = (($expense[0]['exp_amt']) ? $expense[0]['exp_amt'] : 0);

    $arrayOutput = [
        'capital' => 0,
        'balance' => 0,
        'gained_or_loss' => 0,
        'in' => $in,
        'out' => $out,
        'trades' => $count_trades,
        'expenses' => $expenses
    ];
    if ($statement->rowCount() > 0) {
        $a = (float)($row['balance'] + $expenses);
        $gained_or_loss = (float)($row['capital'] - $a);
    }

    $arrayOutput = [
        'capital' => money($row['capital']),
        'balance' => money($row['balance']),
        'gained_or_loss' => money($gained_or_loss),
        'in' => money($in),
        'out' => money($out),
        'trades' => $count_trades,
        'expenses' => money($expenses)
    ];

    $ouput = json_encode($arrayOutput);
    echo $ouput;
}
