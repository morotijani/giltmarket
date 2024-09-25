<?php 
    require_once ("../db_connection/conn.php");

    if (!admin_is_logged_in()) {
        admn_login_redirect();
    }

    dnd($_GET);

    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use PhpOffice\PhpSpreadsheet\Writer\Xls;
    use PhpOffice\PhpSpreadsheet\Writer\Csv;

    // Dompdf, Mpdf or Tcpdf (as appropriate)
    // $className = \PhpOffice\PhpSpreadsheet\Writer\Pdf\Dompdf::class;
    // IOFactory::registerWriter('Pdf', $className);

    $class = \PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf::class;
    \PhpOffice\PhpSpreadsheet\IOFactory::registerWriter('Pdf', $class);


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
            $sheet->setCellValue('C1', 'VOLUME');
            $sheet->setCellValue('D1', 'DENSITY');
            $sheet->setCellValue('E1', 'POUNDS');
            $sheet->setCellValue('F1', 'KARAT');
            $sheet->setCellValue('G1', 'PRICE');
            $sheet->setCellValue('H1', 'TOTAL AMOUNT');
            $sheet->setCellValue('I1', 'CUSTOMER NAME');
            $sheet->setCellValue('J1', 'CUSTOMER CONTACT');
            $sheet->setCellValue('K1', 'COMMENT');
            $sheet->setCellValue('L1', 'SALE BY');
            $sheet->setCellValue('M1', 'DATE');

            $rowCount = 2;
            foreach ($rows as $row) {
                $sheet->setCellValue('A' . $rowCount, ucwords($row['sale_id']));
                $sheet->setCellValue('B' . $rowCount, $row['sale_gram']);
                $sheet->setCellValue('C' . $rowCount, $row['sale_volume']);
                $sheet->setCellValue('D' . $rowCount, $row['sale_density']);
                $sheet->setCellValue('E' . $rowCount, $row['sale_pounds']);
                $sheet->setCellValue('F' . $rowCount, $row['sale_carat']);
                $sheet->setCellValue('G' . $rowCount, money($row['sale_price']));
                $sheet->setCellValue('H' . $rowCount, money($row['sale_total_amount']));
                $sheet->setCellValue('I' . $rowCount, ucwords($row['sale_customer_name']));
                $sheet->setCellValue('J' . $rowCount, $row['sale_customer_contact']);
                $sheet->setCellValue('K' . $rowCount, $row['sale_comment']);
                $sheet->setCellValue('L' . $rowCount, ucwords($row['admin_fullname']));
                $sheet->setCellValue('M' . $rowCount, $row['createdAt']);
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
            } elseif($FileExtType == 'pdf') {
                //$writer = new Csv($spreadsheet);


                $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Pdf');
                $writer = new \PhpOffice\PhpSpreadsheet\Writer\Pdf\Dompdf($spreadsheet);

                $NewFileName = $fileName . '.pdf';
            }

            $message = "exported " . strtoupper($FileExtType) . " trades data";
            add_to_log($message, $_SESSION['JSAdmin']);

            // $writer->save($NewFileName);
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attactment; filename="' . urlencode($NewFileName) . '"');
            $writer->save('php://output');

        } else {
            $_SESSION['flash_error'] = "No Record Found";
            redirect(PROOT . 'acc/trades');
        }
    }