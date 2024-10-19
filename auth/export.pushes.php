<?php 
    require_once ("../db_connection/conn.php");

    if (!admin_is_logged_in()) {
        admn_login_redirect();
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
        
        $query = "SELECT * FROM jspence_pushes INNER JOIN jspence_admin ON (jspence_admin.admin_id = jspence_pushes.push_to OR jspence_admin.admin_id = jspence_pushes.push_from) ";
        if ($exp_with == 'month') {
            $get_out_from_date = (isset($_GET['export-month']) && !empty($_GET['export-month']) ? sanitize($_GET['export-month']) : '');
            $query .= "WHERE MONTH(jspence_pushes.createdAt) = '" . $get_out_from_date . "'";
        } else if ($exp_with == 'year') {
            $get_out_from_date = (isset($_GET['export-year']) && !empty($_GET['export-year']) ? sanitize($_GET['export-year']) : '');
            $query .= "WHERE YEAR(jspence_pushes.createdAt) = '" . $get_out_from_date . "'";
        } else if ($exp_with == 'date') {
            $get_out_from_date = (isset($_GET['export-date']) && !empty($_GET['export-date']) ? sanitize($_GET['export-date']) : '');
            $query .= "WHERE push_date = '" . $get_out_from_date . "'";
        }

        if (!admin_has_permission()) {
            $query .= ' AND (push_to = "' . $admin_id . '" OR push_from IN (SELECT push_from FROM jspence_pushes WHERE push_from = "' . $admin_id . '")) ';
        }
        $query .= " AND jspence_pushes.push_status = 0 GROUP BY push_id ORDER BY jspence_pushes.createdAt DESC";

        $statement = $conn->prepare($query);
        $statement->execute();
        $rows = $statement->fetchAll();
        $count_row = $statement->rowCount();

        if ($count_row > 0) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Header
            $sheet->setCellValue('A1', 'PUSH ID');
            $sheet->setCellValue('B1', 'DAILY ID');
            $sheet->setCellValue('C1', 'PUSH AMOUNT');
            $sheet->setCellValue('D1', 'PUSH FROM');
            $sheet->setCellValue('E1', 'PUSH TO');
            $sheet->setCellValue('F1', 'PUSH ON');
            $sheet->setCellValue('G1', 'DATE');

            $rowCount = 2;
            foreach ($rows as $row) {

                $__from = find_admin_with_id($row["push_from"]);
                if ($row['push_to'] == 'coffers') {
                    $__to = 'coffers';
                } else {
                    $_to = find_admin_with_id($row["push_to"]);
                    $__to = $_to['admin_fullname'];
                }

                $sheet->setCellValue('A' . $rowCount, $row['push_id']);
                $sheet->setCellValue('B' . $rowCount, $row['push_daily']);
                $sheet->setCellValue('C' . $rowCount, money($row['push_amount']));
                $sheet->setCellValue('D' . $rowCount, ucwords($__from['admin_fullname']));
                $sheet->setCellValue('E' . $rowCount, ucwords($__to));
                $sheet->setCellValue('F' . $rowCount, strtoupper($row["push_on"]));
                $sheet->setCellValue('G' . $rowCount, $row['createdAt']);
                $rowCount++;
            }

            $FileExtType = $exp_type;
            $fileName = "J-Spence-Pushes-" . $exp_status . "-sheet";

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
            add_to_log($message, $admin_id);

            // $writer->save($NewFileName);
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attactment; filename="' . urlencode($NewFileName) . '"');
            $writer->save('php://output');

           $_SESSION['flash_success'] = "Downloaded!";
        } else {
            $_SESSION['flash_error'] = "No Record Found!";
        }
        redirect(PROOT . "account/pushes");
    }
