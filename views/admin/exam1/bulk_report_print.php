<!DOCTYPE html>
<html>

<head>
    <title>Bulk Examination Report</title>
    <style>
        /* ================================
   EXAM REPORT STYLES
================================ */

        .institute-header {
            margin-bottom: 12px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .institute-logo {
            max-height: 80px;
            margin-bottom: 8px;
        }

        .institute-name {
            font-size: 21px;
            font-weight: bold;
            margin: 0;
            color: #1a237e;
            line-height: 1.15;
        }

        .institute-subtitle {
            font-size: 12px;
            font-weight: 600;
            margin: 1px 0;
            line-height: 1.2;
        }

        .report-title {
            font-size: 15px;
            font-weight: 700;
            text-align: center;
            margin: 7px 0 8px;
            letter-spacing: .4px;
        }

        .institute-address {
            font-size: 10px;
            margin-top: 3px;
        }

        /* Exam information */

        .exam-info {
            margin: 8px 0 12px;
            font-size: 16px;
        }

        .exam-info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px dotted #555;
            padding: 5px 0;
        }

        .exam-info-item {
            min-width: 30%;
        }

        .exam-info-label {
            font-weight: 700;
            margin-right: 5px;
        }

        .exam-info-value {
            font-weight: 500;
        }


        /* Marks table */

        .exam-report-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            table-layout: fixed;
        }

        .exam-report-table th,
        .exam-report-table td {
            border: 1px solid #555;
            padding: 7px 5px;
            text-align: center;
            vertical-align: middle;
            font-size: 13px;
            padding: 6px 4px;
        }

        .exam-report-table thead th {
            background-color: #e8e8e8;
            font-weight: 700;
        }

        .exam-report-table tbody tr:nth-child(even) {
            background-color: #f7f7f7;
        }

        .exam-report-table .student-name {
            text-align: left;
            padding-left: 10px;
            font-weight: 500;
        }

        /* Subject heading */

        .subject-heading {
            display: block;
            font-weight: 700;
            font-size: 13px;
            line-height: 1.2;
        }

        .subject-topic {
            display: block;
            margin-top: 3px;
            font-size: 8px;
            font-weight: normal;
            color: #666;
            line-height: 1.2;
        }

        /* Summary rows */

        .exam-summary-row td {
            font-weight: 600;
            background-color: #fafafa;
        }

        .exam-summary-label {
            text-align: center !important;
            font-weight: 700 !important;
        }

        .teacher-sign-row {
            height: 40px;
        }

        .teacher-sign-cell {
            height: 40px;
            vertical-align: bottom !important;
        }


        /* Footer signatures */

        .report-signatures {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 35px;
            padding: 0 30px;
        }

        .signature-box {
            width: 25%;
            text-align: center;
        }

        .signature-line {
            border-top: 1px solid #000;
            margin-bottom: 8px;
            height: 1px;
            width: 80%;
            margin: 0 auto 7px;
        }

        .signature-title {
            font-weight: 700;
            font-size: 15px;
        }


        /* Print */

        @media print {

            @page {
                size: A4 portrait;
                margin: 12mm 10mm 12mm 10mm;
            }

            .no-print {
                display: none !important;
            }

            body {
                margin: 0;
                padding: 0;
            }

            .header-box {
                width: 100%;
            }

            .exam-report-table {
                page-break-inside: auto;
            }

            .exam-report-table tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            /*   
    .exam-report-table th,
    .exam-report-table td {
        font-size: 11px;
        padding: 5px 3px;
    } */

            .exam-report-table thead {
                display: table-header-group;
            }

            .exam-report-table tfoot {
                display: table-footer-group;
            }

            .report-signatures {
                page-break-inside: avoid;
            }
        }
    </style>
</head>

<body>

    <div style="text-align: right; margin-bottom: 10px;">
        <button class="no-print" onclick="window.print()" style="padding: 8px 16px; border: 1px solid #999; background: #fff; border-radius: 4px; font-size: 14px; cursor: pointer;">
            Print Report
        </button>
    </div>

    <div class="header-box">

        <div class="institute-header">
            <!-- <img src="<?php echo base_url('uploads/school_content/admin_logo/1.png'); ?>" alt="Niyati Logo" class="institute-logo"> -->
            <h1 class="institute-name">
                <!-- THE CALCUTTA ANGLO GUJARATI SCHOOL FOR BOYS <br>
                &amp; GIRLS (H.SEC.) -->
                The C A G SCHOOL
            </h1>

            <!-- <div class="institute-subtitle">
                SHREE SHREE LAKSHMI NARAIN TRUST GIRLS SECONDARY
            </div>

            <div class="institute-subtitle">
                VALLABHRAM MEHTA BOYS SECONDARY SCHOOL
            </div>

            <div class="institute-subtitle">
                BOYS / GIRLS SECONDARY SECTION
            </div>

            <div class="institute-address">
                6 & 29, Pollock Street, Kolkata - 700 001
            </div> -->
        </div>
        <!-- <div class="title-block">
            Class: <?php echo $class_details['class'] . ' (' . $section_details['section'] . ')'; ?>
        </div>
        <div class="subtitle-block">
            Exam Name: <?php echo $exam_group_details->name; ?>
        </div> -->
    </div>

    <div class="report-title">
        EXAMINATION MARKS REPORT
    </div>

    <div class="exam-info">

        <div class="exam-info-row">

            <div class="exam-info-item">
                <span class="exam-info-label">Class:</span>
                <span class="exam-info-value">
                    <?php echo !empty($class_details['class']) ? htmlspecialchars($class_details['class']) : '-'; ?>
                </span>
            </div>

            <div class="exam-info-item">
                <span class="exam-info-label">Section:</span>
                <span class="exam-info-value">
                    <?php echo !empty($section_details['section']) ? htmlspecialchars($section_details['section']) : '-'; ?>
                </span>
            </div>

            <div class="exam-info-item">
                <span class="exam-info-label">Exam:</span>
                <span class="exam-info-value">
                    <?php echo !empty($exam_group_details->name) ? htmlspecialchars($exam_group_details->name) : '-'; ?>
                </span>
            </div>

            <!-- <div class="exam-info-item">
            <span class="exam-info-label">Session:</span>
            <span class="exam-info-value">
                <?php echo "2026-27"; ?>
            </span>
        </div> -->

        </div>


        <!-- <div class="exam-info-row">

        <div class="exam-info-item">
            <span class="exam-info-label">Full Marks:</span>
            <span class="exam-info-value">
                <?php echo !empty($full_marks) ? $full_marks : ''; ?>
            </span>
        </div>

        <div class="exam-info-item">
            <span class="exam-info-label">Pass Marks:</span>
            <span class="exam-info-value">
                <?php echo !empty($pass_marks) ? $pass_marks : ''; ?>
            </span>
        </div>

        <div class="exam-info-item">
            <span class="exam-info-label">Exam Date:</span>
            <span class="exam-info-value">
                <?php echo !empty($exam_date) ? date('d/m/Y', strtotime($exam_date)) : '-'; ?>
            </span>
        </div>

    </div> -->

    </div>

    <table class="exam-report-table">

        <thead>

            <tr>

                <th rowspan="2" style="width: 8%;">
                    Roll No.
                </th>

                <th rowspan="2" style="width: 30%;">
                    Full Name
                </th>

                <?php if (empty($subjects)) { ?>

                    <th rowspan="2">
                        No Subjects Found
                    </th>

                <?php } else { ?>

                    <th colspan="<?php echo count($subjects); ?>">
                        Subjects
                    </th>

                <?php } ?>

            </tr>

            <tr>

                <?php foreach ($subjects as $sub_id => $sub_name) { ?>

                    <th>

                        <span class="subject-heading">
                            <?php echo htmlspecialchars($sub_name); ?>
                        </span>

                        <!-- <?php if (!empty($topics[$sub_id])) { ?>

                        <span class="subject-topic">
                            <?php //echo htmlspecialchars($topics[$sub_id]); 
                            ?>
                        </span>

                    <?php } ?> -->

                        <?php
                        $out_of = '';

                        /*
                    * Get Out Of marks from the first student
                    * having marks for this subject.
                    */
                        foreach ($students as $student) {

                            if (
                                isset($student['marks'][$sub_id]) &&
                                $student['marks'][$sub_id]['total'] !== null &&
                                $student['marks'][$sub_id]['total'] !== ''
                            ) {
                                $out_of = $student['marks'][$sub_id]['total'];
                                break;
                            }
                        }
                        ?>

                        <?php if ($out_of !== '') { ?>

                            <span style="
                            display:block;
                            margin-top:4px;
                            font-size:10px;
                            font-weight:normal;
                            color:#555;
                        ">
                                Out Of: <?php echo (int) $out_of; ?>
                            </span>

                        <?php } ?>

                    </th>

                <?php } ?>

            </tr>

        </thead>


        <tbody>

            <?php if (empty($students)) { ?>

                <tr>
                    <td colspan="<?php echo 2 + count($subjects); ?>">
                        No students found in this batch.
                    </td>
                </tr>

            <?php } else { ?>

                <?php foreach ($students as $stu_id => $student) { ?>

                    <tr>

                        <td>
                            <?php echo $student['roll_no']; ?>
                        </td>

                        <td class="student-name">
                            <?php echo htmlspecialchars($student['name']); ?>
                        </td>

                        <?php foreach ($subjects as $sub_id => $sub_name) { ?>

                            <td>

                                <?php

                                if (isset($student['marks'][$sub_id])) {

                                    $mark = $student['marks'][$sub_id];

                                    if ($mark['status'] == 'pending') {

                                        echo "<span style='color:#666;'>Test Pending</span>";
                                    } elseif ($mark['status'] == 'absent') {

                                        echo "<span style='color:red;'>Absent</span>";
                                    } else {

                                        $intobtained = (float) $mark['obtained'];
                                        //$inttotal = (int) $mark['total'];

                                        $obtained = str_pad(
                                            $intobtained,
                                            2,
                                            "0",
                                            STR_PAD_LEFT
                                        );

                                        //echo $obtained . "/" . $inttotal;

                                        echo $obtained;
                                    }
                                } else {

                                    echo "-";
                                }

                                ?>

                            </td>

                        <?php } ?>

                    </tr>

                <?php } ?>

                <!-- ==========================================
             EXAM SUMMARY
        =========================================== -->

                <tr class="exam-summary-row">

                    <td colspan="2" class="exam-summary-label">
                        Pass
                    </td>

                    <?php foreach ($subjects as $sub_id => $sub_name) { ?>

                        <td>
                            <?php
                            echo isset($subject_summary[$sub_id]['pass'])
                                ? $subject_summary[$sub_id]['pass']
                                : '-';
                            ?>
                        </td>

                    <?php } ?>

                </tr>


                <tr class="exam-summary-row">

                    <td colspan="2" class="exam-summary-label">
                        Fail
                    </td>

                    <?php foreach ($subjects as $sub_id => $sub_name) { ?>

                        <td>
                            <?php
                            echo isset($subject_summary[$sub_id]['fail'])
                                ? $subject_summary[$sub_id]['fail']
                                : '-';
                            ?>
                        </td>

                    <?php } ?>

                </tr>


                <tr class="exam-summary-row">

                    <td colspan="2" class="exam-summary-label">
                        Total
                    </td>

                    <?php foreach ($subjects as $sub_id => $sub_name) { ?>

                        <td>
                            <?php
                            echo isset($subject_summary[$sub_id]['total'])
                                ? $subject_summary[$sub_id]['total']
                                : '-';
                            ?>
                        </td>

                    <?php } ?>

                </tr>


                <tr class="teacher-sign-row">

                    <td colspan="2" class="exam-summary-label">
                        Teacher's Sign
                    </td>

                    <?php foreach ($subjects as $sub_id => $sub_name) { ?>

                        <td class="teacher-sign-cell">
                            <?php
                            if (!empty($subject_summary[$sub_id]['teacher_sign'])) {
                                echo htmlspecialchars(
                                    $subject_summary[$sub_id]['teacher_sign']
                                );
                            }
                            ?>
                        </td>

                    <?php } ?>

                </tr>

        </tbody>

    </table>

<?php } ?>

<div class="report-signatures">

    <div class="signature-box">

        <div class="signature-line"></div>

        <div class="signature-title">
            Principal
        </div>

    </div>


    <div class="signature-box">

        <div class="signature-line"></div>

        <div class="signature-title">
            HOD
        </div>

    </div>


    <div class="signature-box">

        <div class="signature-line"></div>

        <div class="signature-title">
            Class Teacher
        </div>

    </div>

</div>


</body>

</html>
