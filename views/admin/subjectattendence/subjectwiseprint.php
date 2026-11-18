
<style>

body{
    font-family: Arial, Helvetica, sans-serif;
    font-size:12px;
}

.table{
    width:100%;
    border-collapse: collapse;
}

.table th,
.table td{
    border:1px solid #000;
    padding:6px;
    text-align:center;
    vertical-align:middle !important;
}

.table thead tr th{
    vertical-align: middle !important;
    text-align:center;
}

.table th{
    background:#f2f2f2;
}

.student_name{
    text-align:left !important;
}

.no-print{
    margin-bottom:15px;
}

.print-header{
    width:100%;
    display:flex;
    justify-content:flex-end;
    margin-bottom:15px;
}

.print-btn{

    background:#2d8fdd;
    color:#fff;
    border:none;
    padding:8px 18px;
    border-radius:4px;
    font-size:13px;
    cursor:pointer;
    font-weight:600;
}

.print-btn:hover{
    background:#1f78be;
}

@media print {

    .no-print{
        display:none !important;
    }

    body{
        margin:0;
        padding:0;
    }

    .table th{
        background:#f2f2f2 !important;
        -webkit-print-color-adjust: exact;
    }
}

table{
    page-break-inside:auto;
}

tr{
    page-break-inside:avoid;
}

thead{
    display:table-header-group;
}

</style>

<div class="no-print print-header">

    <button onclick="window.print();"
            class="print-btn">

        <i class="fa fa-print"></i> Print Report

    </button>

</div>

<h3 style="text-align:center;margin-bottom:15px;">

    List of Students

    <?php echo $class_name['class']; ?>

    <?php echo $report_year; ?>

    Batch

</h3>

<table class="table table-bordered">

    <thead>

    <tr>
        <th rowspan="2">Sl No</th>
        <th rowspan="2">Roll No</th>
        <th>Name</th>

        <?php foreach($report['subjects'] as $subject){ ?>
            <th>
                <?php echo $subject['code']; ?>
            </th>
        <?php } ?>

        <th rowspan="2">Grand Total</th>
        <th rowspan="2">%</th>
    </tr>

    <tr>
        <th>Total No of Classes</th>

        <?php foreach($report['subjects'] as $subject){ ?>
            <th>
                <?php echo $subject['total_classes']; ?>
            </th>
        <?php } ?>
    </tr>

</thead>

    <tbody>
        <?php
$sl = 1;

foreach($report['students'] as $student){
?>

<tr>

    <td><?php echo $sl++; ?></td>

    <td><?php echo $student['admission_no']; ?></td>

    <td>
        <?php
        echo $this->customlib->getFullName(
            $student['firstname'],
            $student['middlename'],
            $student['lastname'],
            $sch_setting->middlename,
            $sch_setting->lastname
        );
        ?>
    </td>

    <?php
    foreach($report['subjects'] as $subject){

        $att =
            $student['subject_attendance'][$subject['id']];
    ?>

        <td>

            <?php
            echo $att['present']."/".$att['total'];

            // OR percentage:
            // echo $att['percentage'].'%';
            ?>

        </td>

    <?php } ?>

    <td>
        <?php echo $student['grand_present']; ?>
    </td>

    <td>
        <?php echo $student['grand_percentage']; ?>%
    </td>

    <!-- <td>
        <?php echo $student['grand_total']; ?>
    </td> -->

</tr>

<?php } ?>

    </tbody>