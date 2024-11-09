<?php 
    require_once ("../db_connection/conn.php");

    if (!admin_is_logged_in()) {
        admin_login_redirect();
    }

    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use PhpOffice\PhpSpreadsheet\Writer\Xls;
    use PhpOffice\PhpSpreadsheet\Writer\Csv;

    // Dompdf, Mpdf or Tcpdf (as appropriate)
    // $className = \PhpOffice\PhpSpreadsheet\Writer\Pdf\Dompdf::class;
    // IOFactory::registerWriter('Pdf', $className);

    $class = \PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf::class;
    \PhpOffice\PhpSpreadsheet\IOFactory::registerWriter('Pdf', $class);

    if (isset($_GET['exp_with'])) {
        $exp_with = (isset($_GET['exp_with']) && !empty($_GET['exp_with']) ? sanitize($_GET['exp_with']) : '');
        $exp_status = (isset($_GET['export-status']) && !empty($_GET['export-status']) ? sanitize($_GET['export-status']) : '');
        $exp_type = (isset($_GET['export-type']) && !empty($_GET['export-type']) ? sanitize($_GET['export-type']) : '');
        $get_out_from_date = null;
        
        $query = "SELECT * FROM jspence_sales INNER JOIN jspence_admin ON jspence_admin.admin_id = jspence_sales.sale_by WHERE ";
        if ($exp_status == 'zero') {
            $query .= "jspence_sales.sale_status =  0 ";
        } else if ($exp_status == 'one') {
            $query .= "jspence_sales.sale_status = 1 ";
        }
        if ($exp_with == 'month') {
            $get_out_from_date = (isset($_GET['export-month']) && !empty($_GET['export-month']) ? sanitize($_GET['export-month']) : '');
            $query .= "AND MONTH(jspence_sales.createdAt) = '" . $get_out_from_date . "'";
        } else  if ($exp_with == 'year') {
            $get_out_from_date = (isset($_GET['export-year']) && !empty($_GET['export-year']) ? sanitize($_GET['export-year']) : '');
            $query .= "AND YEAR(jspence_sales.createdAt) = '" . $get_out_from_date . "'";
        } else  if ($exp_with == 'date') {
            $get_out_from_date = (isset($_GET['export-date']) && !empty($_GET['export-date']) ? sanitize($_GET['export-date']) : '');
            $query .= "AND CAST(jspence_sales.createdAt AS date) = '" . $get_out_from_date . "'";
        }

        if (!admin_has_permission()) {
            $query .= ' AND jspence_sales.sale_by = "'.$admin_data['admin_id'].'" ';
        }
        $query .= " AND jspence_sales.sale_type = 'exp'";
        

        $statement = $conn->prepare($query);
        $statement->execute();
        $rows = $statement->fetchAll();
        $count_row = $statement->rowCount();

        if ($count_row > 0) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Header
            $sheet->setCellValue('A1', 'EXPENDITURE ID');
            $sheet->setCellValue('B1', 'WHAT FOR');
            $sheet->setCellValue('C1', 'AMOUNT');
            $sheet->setCellValue('D1', 'BY');
            $sheet->setCellValue('E1', 'DATE');
            $sheet->setCellValue('F1', 'STATUS');

            $rowCount = 2;
            foreach ($rows as $row) {
                $sheet->setCellValue('A' . $rowCount, $row['sale_id']);
                $sheet->setCellValue('B' . $rowCount, $row['sale_comment']);
                $sheet->setCellValue('C' . $rowCount, money($row['sale_total_amount']));
                $sheet->setCellValue('D' . $rowCount, ucwords($row['admin_fullname']));
                $sheet->setCellValue('E' . $rowCount, $row['createdAt']);
                $sheet->setCellValue('F' . $rowCount, $row['sale_status']);
                $rowCount++;
            }

            $FileExtType = $exp_type;
            $fileName = "J-Spence-Expenditure-" . $exp_status . "-sheet";

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

           $_SESSION['flash_success'] = "Downloaded!";
        } else {
            $_SESSION['flash_error'] = "No Record Found!";
        }
        redirect(PROOT . "account/expenditure");
    }
