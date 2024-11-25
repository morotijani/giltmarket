<?php 

// Analytics info 

require_once ("../db_connection/conn.php");

if (isset($_POST['dater'])) {
    $dater = sanitize($_POST['dater']);
    $action = (isset($_POST['action']) ? $_POST['action'] : '');

    $andDaily = '';
    $andExpenditure = '';
    $andPush = '';
    $and = '';
    if ($action == 'with_date' && $dater != null) {
        $and = ' AND CAST(jspence_sales.createdAt AS date) = "' . $dater . '"';
        $andDaily = ' AND daily_date = "'.$dater.'"';
        $andExpenditure = ' AND CAST(jspence_sales.createdAt AS date) = "' . $dater . '"';
        $andPush = ' AND CAST(jspence_pushes.createdAt AS date) = "' . $dater . '"';
    } else if ($action == 'with_month') {
        $and = ' AND MONTH(jspence_sales.createdAt) = "' . $dater . '"';
        $andDaily = ' AND MONTH(daily_date) = "'.$dater.'"';
        $andExpenditure = ' AND MONTH(jspence_sales.createdAt) = "' . $dater . '"';
        $andPush = ' AND MONTH(jspence_pushes.createdAt) = "' . $dater . '"';
    }

    $supervisorQuery = "
        SELECT 
            daily_capital AS capital, 
            daily_balance AS balance, 
            SUM(daily_profit) AS profit 
        FROM jspence_daily 
        INNER JOIN jspence_admin 
        ON jspence_admin.admin_id = jspence_daily.daily_to
        WHERE jspence_daily.status = ? 
        AND jspence_admin.admin_permissions = ?
        $andDaily 
        ORDER BY daily_date DESC 
        LIMIT 1
    ";
    $statement = $conn->prepare($supervisorQuery);
    $result = $statement->execute([0, 'supervisor']);
    $sup_rows = $statement->fetchAll();
    $sup_row = $sup_rows[0] ?? $sup_rows;

    $sales = $conn->query(
        "SELECT SUM(daily_capital) AS capital, SUM(daily_balance) AS balance 
        FROM jspence_daily 
        INNER JOIN jspence_admin 
        ON jspence_admin.admin_id = jspence_daily.daily_to
        WHERE jspence_daily.status = 0 
        AND jspence_admin.admin_permissions = 'salesperson'
        $andDaily
        "
    )->fetchAll();


    $ins = $conn->query("SELECT SUM(sale_total_amount) AS ins_amt FROM jspence_sales WHERE sale_type = 'in' AND sale_status = 0 $and")->fetchAll();
    $outs = $conn->query("SELECT SUM(sale_total_amount) AS outs_amt FROM jspence_sales WHERE sale_type = 'out' AND sale_status = 0 $and")->fetchAll();
    $expense = $conn->query("SELECT SUM(sale_total_amount) AS exp_amt FROM jspence_sales WHERE sale_type = 'exp' AND sale_status = 0 $andExpenditure")->fetchAll();
    $push = $conn->query("SELECT SUM(push_amount) AS push_amt FROM jspence_pushes WHERE push_status = 0 $andPush")->fetchAll();
    $push_moneys = $conn->query("SELECT SUM(push_amount) AS money_amt FROM jspence_pushes WHERE push_status = 0 AND push_type = 'money' $andPush")->fetchAll();
    $push_golds = $conn->query("SELECT SUM(push_amount) AS gold_amt FROM jspence_pushes WHERE push_status = 0 AND push_type = 'gold' $andPush")->fetchAll();
    $count_trades = $conn->query("SELECT * FROM jspence_sales WHERE sale_status = 0 $and")->rowCount();
    
    $grams = $conn->query("SELECT SUM(sale_gram) AS gram FROM jspence_sales WHERE sale_status = 0 $and")->fetchAll();
    $volumes = $conn->query("SELECT SUM(sale_volume) AS volume FROM jspence_sales WHERE sale_status = 0 $and")->fetchAll();
    $densitys = $conn->query("SELECT SUM(sale_density) AS density FROM jspence_sales WHERE sale_status = 0 $and")->fetchAll();
    $pounds = $conn->query("SELECT SUM(sale_pounds) AS pds FROM jspence_sales WHERE sale_status = 0 $and")->fetchAll();
    $carats = $conn->query("SELECT SUM(sale_carat) AS carat FROM jspence_sales WHERE sale_status = 0 $and")->fetchAll();

    $gained_or_loss = 0;
    $in = (($ins[0]['ins_amt']) ? $ins[0]['ins_amt'] : 0);
    $out = (($outs[0]['outs_amt']) ? $outs[0]['outs_amt'] : 0);
    $expenses = (($expense[0]['exp_amt']) ? $expense[0]['exp_amt'] : 0);
    $pushes = (($push[0]['push_amt']) ? $push[0]['push_amt'] : 0);
    $push_money = (($push_moneys[0]['money_amt']) ? $push_moneys[0]['money_amt'] : 0);
    $push_gold = (($push_golds[0]['gold_amt']) ? $push_golds[0]['gold_amt'] : 0);
    
    $gram = (($grams[0]['gram']) ? $grams[0]['gram'] : 0);
    $volume = (($volumes[0]['volume']) ? $volumes[0]['volume'] : 0);
    $density = (($densitys[0]['density']) ? $densitys[0]['density'] : 0);
    $pound = (($pounds[0]['pds']) ? $pounds[0]['pds'] : 0);
    $carat = (($carats[0]['carat']) ? $carats[0]['carat'] : 0);

    $total_sales_capital = $sales[0]['capital'] ?? 0;
    $total_sales_balance = $sales[0]['balance'] ?? 0;

    $total_supervisor_capital = $sup_row['capital'] ?? 0;
    $total_supervisor_balance = $sup_row['balance'] ?? 0;

    // $out = (float)($out + $expenses);

    $arrayOutput = [
        'supervisor_capital' => $total_supervisor_capital,
        'supervisor_balance' => $total_supervisor_balance,
        'sales_capital' => $total_sales_capital,
        'sales_balance' => $total_sales_balance,
        'gained_or_loss' => 0,
        'in' => $in,
        'out' => $out,
        'pushes' => $pushes,
        'push_money' => $push_money,
        'push_gold' => $push_gold,
        'trades' => $count_trades,
        'expenses' => $expenses,
        'gram' => $gram,
        'volume' => $volume,
        'density' => $density,
        'pounds' => $pound,
        'carat' => $carat
    ];

    if ($statement->rowCount() > 0) {
        $gained_or_loss = $sup_row['profit'];
        // $gained_or_loss = (float)($sup_row['balance'] - $sup_row['capital']);
        // if ($sup_row['balance'] == null) {
        //     $gained_or_loss = 0;
        // }
    }

    $arrayOutput = [
        'supervisor_capital' => money($total_supervisor_capital),
        'supervisor_balance' => money($total_supervisor_balance),
        'sales_capital' => money($total_sales_capital),
        'sales_balance' => money($total_sales_balance),
        'gained_or_loss' => money($gained_or_loss),
        'in' => money($in),
        'out' => money($out),
        'pushes' => money($pushes),
        'push_money' => money($push_money),
        'push_gold' => money($push_gold),
        'trades' => $count_trades,
        'expenses' => money($expenses),
        'gram' => $gram,
        'volume' => $volume,
        'density' => $density,
        'pounds' => $pound,
        'carat' => $carat
    ];

    $ouput = json_encode($arrayOutput);
    echo $ouput;
}
