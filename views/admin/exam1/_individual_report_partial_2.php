<div class="box box-info" id="printArea">
    <div class="box-header with-border no-print">
        <button type="button" class="btn btn-primary pull-right" onclick="printReport()"><i class="fa fa-print"></i> Print Report Card</button>
    </div>
    <div class="box-body">
        
        <div class="report-wrapper" style="border: 2px solid #333; padding: 20px;">
            
            <div class="text-center" style="border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px;">
                <h3 style="font-weight: bold; margin-top: 0;">NIYATI DESTINY FOR EXCELLENCE, AMARAVATI, MAHARASHTRA</h3>
                <h5 style="margin-bottom: 0;">2025-26</h5>
            </div>

            <table class="table table-bordered border-dark profile-table" style="margin-bottom: 20px;">
                <tr>
                    <td width="30%"><strong>ADMISSION NO:</strong> <?php echo $student['admission_no']; ?></td>
                    <td width="30%"><strong>ROLL NUMBER:</strong> <?php echo $student['roll_no']; ?></td>
                    <td width="40%" rowspan="5" class="text-center">
                        <?php if(!empty($student['image'])) { ?>
                            <img src="<?php echo base_url($student['image']); ?>" style="width: 100px; height: 100px; border: 1px solid #ccc;">
                        <?php } else { ?>
                            <div style="width: 100px; height: 100px; border: 1px solid #ccc; display: inline-block; background: #eee;"></div>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><strong>NAME:</strong> <?php echo strtoupper($student['firstname'] . ' ' . $student['lastname']); ?></td>
                </tr>
                <tr>
                    <td colspan="2"><strong>FATHER NAME:</strong> <?php echo strtoupper($student['father_name']); ?></td>
                </tr>
                <tr>
                    <td colspan="2"><strong>MOTHER NAME:</strong> <?php echo strtoupper($student['mother_name']); ?></td>
                </tr>
                <tr>
                    <td colspan="2"><strong>DATE OF BIRTH:</strong> <?php echo date('d-m-Y', strtotime($student['dob'])); ?></td>
                </tr>
            </table>

            <h4 class="text-center" style="font-weight: bold; text-decoration: underline;">CERTIFICATED THAT</h4>

            <table class="table table-bordered border-dark text-center marks-table">
                <thead>
                    <tr style="background-color: #f9f9f9;">
                        <th>Subjects</th>
                        <th>Out Of</th>
                        <!-- <th>Min. Marks</th> -->
                        <th>Marks Obtained</th>
                        <th>Note</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($marks as $mark) { ?>
                        <tr>
                            <td class="text-left" style="font-weight:bold;"><?php echo strtoupper($mark['subject_name']); ?></td>
                            <td><?php echo $mark['total_marks']; ?></td>
                            <!-- <td><?php echo $mark['min_marks']; ?></td> -->
                            <td>
                                <?php 
                                    if ($mark['status'] == 'pending') {
                                        echo "Pending";
                                    } elseif ($mark['status'] == 'absent') {
                                        echo "Absent";
                                    } else {
                                        echo $mark['obtained_marks']; 
                                    }
                                ?>
                            </td>
                            <td>
                                <?php echo ($mark['status'] == 'present' && $mark['obtained_marks'] < $mark['min_marks']) ? 'Failed Subject' : ''; ?>
                            </td>
                        </tr>
                    <?php } ?>
                    
                    <tr style="background-color: #f9f9f9; font-weight: bold;">
                        <td></td>
                        <td><?php echo $grand_total_max; ?></td>
                        <!-- <td>Grand Total</td> -->
                        <td><?php echo $grand_total_obtained; ?></td>
                        <td></td>
                    </tr>
                    
                    <tr style="background-color: #f9f9f9; font-weight: bold;">
                        <td>Percentage</td>
                        <td><?php echo $percentage; ?></td>
                        <!-- <td>Result</td> -->
                        <td>Result: <?php echo strtoupper($final_result); ?></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>

            <div style="margin-top: 20px;">
                <p><strong>Result:</strong> <?php echo strtoupper($final_result); ?></p>
                <p><strong>Division:</strong> <?php echo ($percentage >= 60) ? 'First' : (($percentage >= 45) ? 'Second' : 'Third'); ?></p>
            </div>

        </div>
    </div>
</div>

<style>
    /* Ensure table borders are strictly black and crisp */
    .border-dark th, .border-dark td {
        border: 1px solid #000 !important;
    }
    
    /* Print Media Query */
    @media print {
        body * { visibility: hidden; }
        #printArea, #printArea * { visibility: visible; }
        #printArea { position: absolute; left: 0; top: 0; width: 100%; margin: 0; padding: 0; }
        .no-print { display: none !important; }
        .report-wrapper { border: none !important; padding: 0 !important; }
    }
</style>

<script>
    function printReport() {
        window.print();
    }
</script>