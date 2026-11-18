<div class="report-wrapper" style="border: 2px solid #333; padding: 20px; margin-bottom: 40px; page-break-after: always; background-color: #fff;">
    

    <div style="border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; text-align: center;">
        
        <!-- <div style="margin-bottom: 10px;"> -->
            <img src="<?php echo $school_logo; ?>" alt="School Logo" style="height: 70px; width: auto; max-width: 150px; margin-bottom: 8px;">
        <!-- </div> -->

        <h2 style="font-weight: bold; margin: 0; font-size: 24px; color: #000;">
        THE CAG SCHOOL <br>
        <!-- <span style="font-size: 16px; font-weight: normal; color: #555; text-align: center;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (6 & 29, Pollock Street, Kolkata - 700 001)</span> -->
        </h2>

        <div style="
        margin-top: 5px;
        font-size: 13px;
        font-weight: 600;
        color: #555;
        ">
            ISO 9001:2015 Certified
        </div>
        
        <!-- <h5 style="margin: 0; font-size: 15px; color: #444; font-weight: normal;">
            <?php //echo $student['academic_session']; ?>
        </h5> -->
        <!-- <div class="text-center" style="border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px;">
            <h3 style="font-weight: bold; margin-top: 0;">NIYATI DESTINY FOR EXCELLENCE, AMRAVATI, MAHARASHTRA</h3>
            <h5 style="margin-bottom: 0;">2025-26</h5>
        </div> -->
    </div>

    <table class="table table-bordered border-dark profile-table" style="margin-bottom: 20px;">
        <tr>
            <td width="30%"><strong>ADMISSION NO:</strong> <?php echo $student['admission_no']; ?></td>
            <td width="30%"><strong>ROLL NUMBER:</strong> <?php echo $student['roll_no']; ?></td>
            <!-- <td width="40%" rowspan="5" class="text-center">
                <?php if(!empty($student['image'])) { ?>
                    <img src="<?php echo base_url($student['image']); ?>" style="width: 100px; height: 100px; border: 1px solid #ccc;">
                <?php } else { ?>
                    <div style="width: 100px; height: 100px; border: 1px solid #ccc; display: inline-block; background: #eee;"></div>
                <?php } ?>
            </td> -->
        </tr>
        <tr>
            <td colspan="2"><strong>NAME:</strong> <?php echo strtoupper($student['firstname'] . ' ' . $student['lastname']); ?></td>
        </tr>
        <tr>
            <td colspan="2"><strong>CLASS:</strong> <?php echo $student['course_name']. ' - '. $student['batch_name']; ?></td>
        </tr>
        <tr>
            <td colspan="2"><strong>EXAM NAME:</strong> <?php echo $marks[0]['name']; ?></td>
        </tr>
        <tr>
            <td colspan="2"><strong>DATE:</strong> <?php echo date('d-m-Y', strtotime($exam_date)); ?></td>
        </tr>
        <!-- <tr>
            <td colspan="2"><strong>DATE OF BIRTH:</strong> <?php echo date('d-m-Y', strtotime($student['dob'])); ?></td>
        </tr> -->
    </table>

    <h4 class="text-center" style="font-weight: bold; text-decoration: underline;">MARK SHEET</h4>

    <table class="table table-bordered border-dark text-center marks-table">
        <thead>
            <tr style="background-color: #f9f9f9;">
                <th>Subjects</th>
                <th>Out Of</th>
                <th>Marks Obtained</th>
                <!-- <th>Highest Marks</th> -->
            </tr>
        </thead>
        <tbody>
            <?php foreach ($marks as $mark) { ?>
                <tr>
                    <td class="text-left" style="font-weight:bold;">
                        <?php echo strtoupper($mark['subject_name']); ?>
                        <!-- <?php //if(!empty($mark['topic'])) { ?>
                            <br><span style="font-weight: normal; font-size: 13px; color: #555;"><?php echo htmlspecialchars($mark['topic']); ?></span>
                        <?php //} ?> -->
                    </td>
                    <td><?php echo $mark['total_marks']; ?></td>
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
                    <!-- <td>
                        <?php echo ($mark['status'] == 'present' && isset($mark['min_marks']) && $mark['obtained_marks'] < $mark['min_marks']) ? 'Failed Subject' : ''; ?>
                    </td> -->
                    <!-- <td style="vertical-align: middle;">
                        <?php 
                            // 1. Existing Fail Logic
                            // if ($mark['status'] == 'present' && isset($mark['min_marks']) && $mark['obtained_marks'] < $mark['min_marks']) {
                            //     echo '<span style="color:red; font-weight:bold;">Failed Subject</span><br>';
                            // }
                            
                            // 2. NEW Topper Logic UI
                            // if (isset($subject_toppers[$mark['subject_id']])) {
                            //     $topper = $subject_toppers[$mark['subject_id']];
                                
                            //     // Clean UI box for the topper list
                            //     echo '<div style="font-size: 11px; margin-top: 2px; text-align: left; padding-left: 10px;">';
                            //     echo '<span style="color: #d69000; font-weight: bold;"><i class="fa fa-trophy"></i> Highest ('.$topper['marks'].'):</span><br>';
                            //     echo '<span style="color: #444;">' . htmlspecialchars($topper['names']) . '</span>';
                            //     echo '</div>';
                            // }
                        ?>
                    </td> -->
                </tr>
            <?php } ?>
            
            <tr style="background-color: #f9f9f9; font-weight: bold;">
                <td></td>
                <td><?php echo $grand_total_max; ?></td>
                <td><?php echo $grand_total_obtained; ?></td>
                <!-- <td></td> -->
            </tr>
            
            <tr style="background-color: #f9f9f9; font-weight: bold;">
                <td>Percentage</td>
                <td><?php echo $percentage; ?></td>
                <td>Result : <?php echo strtoupper($final_result); ?></td>
                <!-- <td></td> -->
            </tr>
        </tbody>
    </table>

    <div style="margin-top: 20px;">
         <!-- <p><strong>Result:</strong> <?php //echo strtoupper($final_result); ?></p>
               <p><strong>Division:</strong> <?php //echo ($percentage >= 60) ? 'First' : (($percentage >= 45) ? 'Second' : 'Third'); ?></p> -->

        <!-- <div style="margin-top: 20px;">

            <p>
                <strong>Result:</strong>
                <?php echo strtoupper($final_result); ?>
            </p>

            <p>
                <strong>Percentage:</strong>
                <?php echo $percentage; ?>%
            </p>

            <p>
                <strong>Remarks:</strong>
                <?php echo htmlspecialchars($remarks); ?>
            </p>

        </div> -->
        <table class="table table-bordered" style="margin-top: 20px;">

    <tr>
        <td width="25%">
            <strong>Percentage</strong>
        </td>

        <td width="25%">
            <?php echo $percentage; ?>%
        </td>

        <td width="25%">
            <strong>Result</strong>
        </td>

        <td width="25%">
            <strong>
                <?php echo strtoupper($final_result); ?>
            </strong>
        </td>
    </tr>

    <tr>
        <td>
            <strong>Remarks</strong>
        </td>

        <td colspan="3">
            <?php if (!empty($remarks1)) { ?>
                <?php echo htmlspecialchars($remarks1); ?>
            <?php } ?>
        </td>
    </tr>

</table>
    </div>
</div>
