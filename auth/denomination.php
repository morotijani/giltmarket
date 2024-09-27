<?php 

// Analytics info 

require_once ("../db_connection/conn.php");

if (isset($_POST['denomination_200c'])) {
    $denomination_200c = ((isset($_POST['denomination_200c']) && !empty($_POST['denomination_200c'])) ? sanitize($_POST['denomination_200c']) : NULL);
    $denomination_200c_amt = ((isset($_POST['denomination_200c_amt']) && !empty($_POST['denomination_200c_amt'])) ? sanitize($_POST['denomination_200c_amt']) : NULL);
    $denomination_100c = ((isset($_POST['denomination_100c']) && !empty($_POST['denomination_100c'])) ? sanitize($_POST['denomination_100c']) : NULL);
    $denomination_100c_amt = ((isset($_POST['denomination_100c_amt']) && !empty($_POST['denomination_100c_amt'])) ? sanitize($_POST['denomination_100c_amt']) : NULL);
    $denomination_50c = ((isset($_POST['denomination_50c']) && !empty($_POST['denomination_50c'])) ? sanitize($_POST['denomination_50c']) : NULL);
    $denomination_50c_amt = ((isset($_POST['denomination_50c_amt']) && !empty($_POST['denomination_50c_amt'])) ? sanitize($_POST['denomination_50c_amt']) : NULL);
    $denomination_20c = ((isset($_POST['denomination_20c']) && !empty($_POST['denomination_20c'])) ? sanitize($_POST['denomination_20c']) : NULL);
    $denomination_20c_amt = ((isset($_POST['denomination_20c_amt']) && !empty($_POST['denomination_20c_amt'])) ? sanitize($_POST['denomination_20c_amt']) : NULL);
    $denomination_10c = ((isset($_POST['denomination_10c']) && !empty($_POST['denomination_10c'])) ? sanitize($_POST['denomination_10c']) : NULL);
    $denomination_10c_amt = ((isset($_POST['denomination_10c_amt']) && !empty($_POST['denomination_10c_amt'])) ? sanitize($_POST['denomination_10c_amt']) : NULL);
    $denomination_5c = ((isset($_POST['denomination_5c']) && !empty($_POST['denomination_5c'])) ? sanitize($_POST['denomination_5c']) : NULL);
    $denomination_5c_amt = ((isset($_POST['denomination_5c_amt']) && !empty($_POST['denomination_5c_amt'])) ? sanitize($_POST['denomination_5c_amt']) : NULL);
    $denomination_2c = ((isset($_POST['denomination_2c']) && !empty($_POST['denomination_2c'])) ? sanitize($_POST['denomination_2c']) : NULL);
    $denomination_2c_amt = ((isset($_POST['denomination_2c_amt']) && !empty($_POST['denomination_2c_amt'])) ? sanitize($_POST['denomination_2c_amt']) : NULL);
    $denomination_1c = ((isset($_POST['denomination_1c']) && !empty($_POST['denomination_1c'])) ? sanitize($_POST['denomination_1c']) : NULL);
    $denomination_1c_amt = ((isset($_POST['denomination_1c_amt']) && !empty($_POST['denomination_1c_amt'])) ? sanitize($_POST['denomination_1c_amt']) : NULL);
    $denomination_50p = ((isset($_POST['denomination_50p']) && !empty($_POST['denomination_50p'])) ? sanitize($_POST['denomination_50p']) : NULL);
    $denomination_50p_amt = ((isset($_POST['denomination_50p_amt']) && !empty($_POST['denomination_50p_amt'])) ? sanitize($_POST['denomination_50p_amt']) : NULL);
    $denomination_20p = ((isset($_POST['denomination_20p']) && !empty($_POST['denomination_20p'])) ? sanitize($_POST['denomination_20p']) : NULL);
    $denomination_20p_amt = ((isset($_POST['denomination_20p_amt']) && !empty($_POST['denomination_20p_amt'])) ? sanitize($_POST['denomination_20p_amt']) : NULL);
    $denomination_10p = ((isset($_POST['denomination_10p']) && !empty($_POST['denomination_10p'])) ? sanitize($_POST['denomination_10p']) : NULL);
    $denomination_10p_amt = ((isset($_POST['denomination_10p_amt']) && !empty($_POST['denomination_10p_amt'])) ? sanitize($_POST['denomination_10p_amt']) : NULL);
    $denomination_5p = ((isset($_POST['denomination_5p']) && !empty($_POST['denomination_5p'])) ? sanitize($_POST['denomination_5p']) : NULL);
    $denomination_5p_amt = ((isset($_POST['denomination_5p_amt']) && !empty($_POST['denomination_5p_amt'])) ? sanitize($_POST['denomination_5p_amt']) : NULL);
    $denomination_1p = ((isset($_POST['denomination_1p']) && !empty($_POST['denomination_1p'])) ? sanitize($_POST['denomination_1p']) : NULL);
    $denomination_1p_amt = ((isset($_POST['denomination_1p_amt']) && !empty($_POST['denomination_1p_amt'])) ? sanitize($_POST['denomination_1p_amt']) : NULL);

    $denomination_id = guidv4();
    $by = $admin_id['admin_id'];
    $capital_id = _capital($by)['today_capital_id'];

    $data = [$denomination_id, $capital_id, $by, $denomination_200c, $denomination_200c_amt, $denomination_100c, $denomination_100c_amt, $denomination_50c, $denomination_50c_amt, $denomination_20c, $denomination_20c_amt, $denomination_10c, $denomination_10c_amt, $denomination_5c, $denomination_5c_amt, $denomination_2c, $denomination_2c_amt, $denomination_1c, $denomination_1c_amt, $denomination_50p, $denomination_50p_amt, $denomination_20p, $denomination_20p_amt, $denomination_10p, $denomination_10p_amt, $denomination_5p, $denomination_5p_amt, $denomination_1p, $denomination_1p_amt];

    $sql = "
        INSERT INTO `jspence_denomination`(`denominations_id`, `denomination_capital`, `denomination_by`, `denomination_200c`, `denomination_200c_amt`, `denomination_100c`, `denomination_100c_amt`, `denomination_50c`, `denomination_50c_amt`, `denomination_20c`, `denomination_20c_amt`, `denomination_10c`, `denomination_10c_amt`, `denomination_5c`, `denomination_5c_amt`, `denomination_2c`, `denomination_2c_amt`, `denomination_1c`, `denomination_1c_amt`, `denomination_50p`, `denomination_50p_amt`, `denomination_20p`, `denomination_20p_amt`, `denomination_10p`, `denomination_10p_amt`, `denomination_5p`, `denomination_5p_amt`, `denomination_1p`, `denomination_1p_amt`) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ";
    $statement = $conn->prepare($sql);
    $result = $statement->execute($data);
    if (isset($result)) {

        $query = "
            UPDATE jspence_daily SET daily_capital_status = ? 
            WHERE daily_id = ?
        ";
        $statement = $conn->prepare($query);
        $statement->execute([1, $capital_id]);

        echo 'done';
    }
}
