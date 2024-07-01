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
        $fileName = "J-Spence" . $data . "-sheet";

        if ($data == 'all') {
            $query = "SELECT * FROM jspence_sales WHERE trash = 0";
        } else if ($data == 'gained') {
            $query = "SELECT * FROM jspence_sales WHERE percentage > 0 AND trash = 0";
        } else if ($data == 'rejected') {
            $query = "SELECT * FROM jspence_sales WHERE status != 0 OR status != 1";
        }  else if ($data == 'trash') {
            $query = "SELECT * FROM jspence_sales WHERE trash = 1";
        }
        $statement = $conn->prepare($query);
        $statement->execute();
        $rows = $statement->fetchAll();
        $count_row = $statement->fetchAll();

        if ($count_row > 0) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Header
            $sheet->setCellValue('A1', 'NAME');
            $sheet->setCellValue('B1', 'DATE OF BIRTH');
            $sheet->setCellValue('C1', 'AGE');
            $sheet->setCellValue('D1', 'PLACE OF BIRTH');
            $sheet->setCellValue('E1', 'PLACE OF RESIDENCE');
            $sheet->setCellValue('F1', 'LIVING WITH PARENT');
            $sheet->setCellValue('G1', 'FAMILY SIZE');
            $sheet->setCellValue('H1', 'FATHER\'S NAME');
            $sheet->setCellValue('I1', 'FATHER\'S AGE');
            $sheet->setCellValue('J1', 'FATHER\'S OCCUPATION');
            $sheet->setCellValue('K1', 'MOTHER\'S NAME');
            $sheet->setCellValue('L1', 'MOTHER\'S AGE');
            $sheet->setCellValue('M1', 'MOTHER\'S OCCUPATION');
            $sheet->setCellValue('N1', 'ARE BOTH PARENT ALIVE');
            $sheet->setCellValue('O1', 'IF NO, WHICH PARENT IS DESEASED');
            $sheet->setCellValue('P1', 'SCHOOL NAME');
            $sheet->setCellValue('Q1', 'WHO PAID YOUR LAST SCHOOL FEES');
            $sheet->setCellValue('R1', 'PROGRAM');
            $sheet->setCellValue('S1', 'YEAR OF STUDY');
            $sheet->setCellValue('T1', 'STUDENT ID');
            $sheet->setCellValue('U1', 'SELF DESCRIPTION');
            $sheet->setCellValue('V1', 'PERSONAL DREAM');
            $sheet->setCellValue('W1', 'LIMITATION IN YOUR LIFE AS A STUDENT');
            $sheet->setCellValue('X1', 'NAME OF REFEREE');
            $sheet->setCellValue('Y1', 'REFEREE RELATION');
            $sheet->setCellValue('Z1', 'REFEREE OCCUPATION');
            $sheet->setCellValue('AA1', 'CONTACT OF REFEREE');
            $sheet->setCellValue('AB1', 'REFEREE ADDRESS');
            $sheet->setCellValue('AC1', 'REFEREE EMAIL');
            $sheet->setCellValue('AD1', 'PERCENTAGE GAINED');

            $rowCount = 2;
            foreach ($rows as $row) {
                $sheet->setCellValue('A' . $rowCount, ucwords($row['student_name']));
                $sheet->setCellValue('B' . $rowCount, $row['student_dob']);
                $sheet->setCellValue('C' . $rowCount, $row['student_age']);
                $sheet->setCellValue('D' . $rowCount, $row['student_place_of_birth']);
                $sheet->setCellValue('E' . $rowCount, $row['student_place_of_residence']);
                $sheet->setCellValue('F' . $rowCount, $row['student_with_parent']);
                $sheet->setCellValue('G' . $rowCount, $row['student_family_size']);
                $sheet->setCellValue('H' . $rowCount, ucwords($row['father_name']));
                $sheet->setCellValue('I' . $rowCount, $row['father_age']);
                $sheet->setCellValue('J' . $rowCount, ucwords($row['father_occupation']));
                $sheet->setCellValue('K' . $rowCount, ucwords($row['mother_name']);
                $sheet->setCellValue('L' . $rowCount, $row['mother_age']);
                $sheet->setCellValue('M' . $rowCount, ucwords($row['mother_occupation']));
                $sheet->setCellValue('N' . $rowCount, $row['parent_alive']);
                $sheet->setCellValue('O' . $rowCount, $row['parent_deceased']);
                $sheet->setCellValue('P' . $rowCount, ucwords($row['school_name']));
                $sheet->setCellValue('Q' . $rowCount, $row['wpys_fees']);
                $sheet->setCellValue('R' . $rowCount, ucwords($row['program_name']));
                $sheet->setCellValue('S' . $rowCount, $row['year_of_study']);
                $sheet->setCellValue('T' . $rowCount, $row['index_number']);
                $sheet->setCellValue('U' . $rowCount, $row['self_description']);
                $sheet->setCellValue('V' . $rowCount, $row['professional_dream']);
                $sheet->setCellValue('W' . $rowCount, $row['limitation']);
                $sheet->setCellValue('X' . $rowCount, ucwords($row['referee_name']));
                $sheet->setCellValue('Y' . $rowCount, $row['relation_nature']);
                $sheet->setCellValue('Z' . $rowCount, $row['referee_occupation']);
                $sheet->setCellValue('AA' . $rowCount, $row['referee_contact']);
                $sheet->setCellValue('AB' . $rowCount, $row['referee_address']);
                $sheet->setCellValue('AC' . $rowCount, $row['referee_email']);
                $sheet->setCellValue('AD' . $rowCount, $row['percentage']);
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