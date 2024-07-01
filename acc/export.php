<?php 
    require_once ("../db_connection/conn.php");

    if (!admin_is_logged_in()) {
        admn_login_redirect();
    }

    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use PhpOffice\PhpSpreadsheet\Writer\Xls;
    use PhpOffice\PhpSpreadsheet\Writer\Csv;

    if (isset($_GET['data']) && !empty($_GET['type'])) {
        $data = sanitize($_GET['data']);
        $FileExtType = sanitize($_GET['type']);
        $fileName = "J-Spence-Trades-" . $data . "-sheet";

        if ($data == 'all') {
            $query = "SELECT * FROM jspence_sales INNER JOIN jspence_admin ON jspence_admin.admin_id = jspence_sales.sale_by WHERE jspence_sales.sale_status = 0";
        } else if ($data == 'archive') {
            $query = "SELECT * FROM jspence_sales INNER JOIN jspence_admin ON jspence_admin.admin_id = jspence_sales.sale_by WHERE jspence_sales.sale_status = 1";
        }
        $statement = $conn->prepare($query);
        $statement->execute();
        $rows = $statement->fetchAll();
        $count_row = $statement->fetchAll();

        if ($count_row > 0) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Header
            $sheet->setCellValue('A1', 'SALE ID');
            $sheet->setCellValue('B1', 'GRAM');
            $sheet->setCellValue('C1', 'AGE');
            $sheet->setCellValue('D1', 'VOLUME');
            $sheet->setCellValue('E1', 'DENSITY');
            $sheet->setCellValue('F1', 'POUNDS');
            $sheet->setCellValue('G1', 'KARAT');
            $sheet->setCellValue('H1', 'PRICE');
            $sheet->setCellValue('I1', 'TOTAL AMOUNT');
            $sheet->setCellValue('J1', 'CUSTOMER NAME');
            $sheet->setCellValue('K1', 'CUSTOMER CONTACT');
            $sheet->setCellValue('L1', 'COMMENT');
            $sheet->setCellValue('M1', 'SALE BY');
            $sheet->setCellValue('N1', 'STATUS');

            $rowCount = 2;
            foreach ($rows as $row) {
                $sheet->setCellValue('A' . $rowCount, ucwords($row['sale_id']));
                $sheet->setCellValue('B' . $rowCount, $row['sale_gram']);
                $sheet->setCellValue('C' . $rowCount, $row['sale_volume']);
                $sheet->setCellValue('D' . $rowCount, $row['sale_density']);
                $sheet->setCellValue('E' . $rowCount, $row['sale_pounds']);
                $sheet->setCellValue('F' . $rowCount, $row['sale_carat']);
                $sheet->setCellValue('G' . $rowCount, money($row['sale_price']);
                $sheet->setCellValue('H' . $rowCount, money($row['sale_total_amount']));
                $sheet->setCellValue('I' . $rowCount, ucwords($row['sale_customer_name']));
                $sheet->setCellValue('J' . $rowCount, $row['sale_customer_contact']);
                $sheet->setCellValue('K' . $rowCount, $row['sale_comment'];
                $sheet->setCellValue('L' . $rowCount, $row['sale_by']);
                $sheet->setCellValue('M' . $rowCount, $row['createdAt']);
                $sheet->setCellValue('N' . $rowCount, $row['sale_status']);
                $rowCount++;
            }

            if ($FileExtType == 'xlsx') {
                $writer = new Xlsx($spreadsheet);
                $NewFileName = $fileName . '.xlsx';
            } elseif($FileExtType == 'xls') {
                $writer = new Xls($spreadsheet);
                $NewFileName = $fileName . '.xls';
            } elseif($FileExtType == 'csv') {
                $writer = new Csv($spreadsheet);
                $NewFileName = $fileName . '.csv';
            }

            // $writer->save($NewFileName);
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attactment; filename="' . urlencode($NewFileName) . '"');
            $writer->save('php://output');
            // redirect(PROOT . 'admin/Scholarship/index');

        } else {
            $_SESSION['flash_error'] = "No Record Found";
            redirect(PROOT . 'admin/Scholarship/index');
        }
    }