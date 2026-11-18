<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1><i class="fa fa-map-o"></i> Examination Marks Entry</h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> Select Criteria</h3>
                    </div>
                    <div class="box-body">
                        <form role="form" action="<?php echo site_url('admin/examcontroller/index') ?>" method="post">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-sm-6 col-lg-3 col-md-3">
                                    <div class="form-group">
                                        <label>Exam Group</label><small class="req"> *</small>
                                        <select id="exam_group_id" name="exam_group_id" class="form-control select2" required>
                                            <option value="">Select</option>
                                            <?php foreach ($examgrouplist as $ex_group) { ?>
                                                <option value="<?php echo $ex_group->id ?>" <?php echo set_select('exam_group_id', $ex_group->id); ?>><?php echo $ex_group->name; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-3 col-md-3">
                                    <div class="form-group">
                                        <label>Exam Date</label><small class="req"> *</small>
                                        <input type="date" id="exam_date" name="exam_date" class="form-control" value="<?php echo set_value('exam_date', date('Y-m-d')); ?>" required>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-3 col-md-3">
                                    <div class="form-group">
                                        <label>Course</label><small class="req"> *</small>
                                        <select id="class_id" name="class_id" class="form-control" required>
                                            <option value="">Select</option>
                                            <?php foreach ($classlist as $class) { ?>
                                                <option value="<?php echo $class['id'] ?>" <?php echo set_select('class_id', $class['id']); ?>><?php echo $class['class'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-3 col-md-3">
                                    <div class="form-group">
                                        <label>Batch</label><small class="req"> *</small>
                                        <select id="section_id" name="section_id" class="form-control" required>
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <button type="submit" name="search" value="search_filter" class="btn btn-primary pull-right btn-sm"><i class="fa fa-search"></i> Search</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <?php if (!empty($studentList)) { ?>
                    <div class="box box-info">
                        <div class="box-header ptbnull">
                            <h3 class="box-title titlefix"><i class="fa fa-list-alt"></i> Batch Marks Insert</h3>
                            
                            <div id="bulkReportBtnContainer" style="display: <?php echo (isset($has_marks) && $has_marks) ? 'inline-block' : 'none'; ?>;" class="pull-right">
                                <form action="<?php echo site_url('admin/examcontroller/bulk_report') ?>" method="post" target="_blank">
                                    <?php echo $this->customlib->getCSRF(); ?>
                                    <input type="hidden" name="exam_group_id" value="<?php echo $this->input->post('exam_group_id'); ?>">
                                    <input type="hidden" name="exam_date" value="<?php echo $this->input->post('exam_date'); ?>">
                                    <input type="hidden" name="class_id" value="<?php echo $this->input->post('class_id'); ?>">
                                    <input type="hidden" name="section_id" value="<?php echo $this->input->post('section_id'); ?>">
                                    <button type="submit" class="btn btn-warning btn-sm"><i class="fa fa-print"></i> Generate Bulk Report</button>
                                </form>
                            </div>
                        </div>

                        <div class="box-body">
                        <div class="alert alert-info" style="padding: 10px 15px; margin-bottom: 15px; border-left: 5px solid #00c0ef;">
                            <i class="fa fa-info-circle"></i> <strong>Instructions:</strong> 
                            Enter the Obtained marks in the left box, and Max marks in the right box. 
                            <strong style="text-decoration: underline;">Leave 'Obt' blank to mark a test as Pending.</strong>
                        </div>

                        <div class="table-responsive">
                            <?php if (empty($dynamic_subjects)) { ?>
                                <div class="alert alert-danger">No subjects are assigned to this Course & Batch. Please assign subjects first.</div>
                            <?php } else { ?>
                                <table class="table table-striped table-bordered table-hover" id="marksGridTable">
                                    <thead>
                                        <tr>
                                            <th style="width: 80px;">Roll No</th>
                                            <th>Student Name</th>
                                            <?php foreach ($dynamic_subjects as $subject) { ?>
                                                <th class="text-center">
                                                    <?php echo $subject['subject_name']; ?><br>
                                                    <small class="text-muted">(Obtained / Out Of)</small>
                                                </th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($studentList as $student) { $stu_id = $student['student_id']; ?>
                                            <tr class="student-row" data-student-id="<?php echo $stu_id; ?>">
                                                <td><?php echo $student['admission_no']; ?></td>
                                                <td><?php echo $student['firstname'] . " " . $student['lastname']; ?></td>
                                                
                                                <?php foreach ($dynamic_subjects as $subject) { 
                                                    $sub_id = $subject['subject_id'];
                                                    
                                                    // Check if marks exist for this specific cell
                                                    $obt_val = '';
                                                    $tot_val = '';
                                                    if (isset($existing_marks[$stu_id][$sub_id])) {
                                                        $mark_data = $existing_marks[$stu_id][$sub_id];
                                                        $tot_val = $mark_data['total_marks'];
                                                        $obt_val = ($mark_data['status'] == 'pending') ? '' : $mark_data['obtained_marks'];
                                                    }
                                                ?>
                                                    <td class="text-center subject-cell" data-subject-id="<?php echo $sub_id; ?>">
                                                        <div style="display: flex; align-items: center; justify-content: center; gap: 5px;">
                                                            <input type="number" step="1" class="form-control input-sm obt-input" style="width: 60px; text-align: center;" placeholder="Obt" value="<?php echo $obt_val; ?>">
                                                            <span style="font-size: 16px;">/</span>
                                                            <input type="number" step="1" class="form-control input-sm tot-input" style="width: 60px; text-align: center;" placeholder="Out Of" value="<?php echo $tot_val; ?>">
                                                        </div>
                                                    </td>
                                                <?php } ?>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                
                                <hr>
                                <div class="text-right">
                                    <button type="button" id="saveAllMarksBtn" class="btn btn-success btn-lg" onclick="saveBulkMarks()">
                                        <i class="fa fa-save"></i> Save All Marks
                                    </button>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
    var class_id = '<?php echo set_value('class_id') ?>';
    var section_id = '<?php echo set_value('section_id') ?>';
    if(class_id !== "") { getSectionByClass(class_id, section_id); }

    $(document).on('change', '#class_id', function () { getSectionByClass($(this).val(), 0); });

    function getSectionByClass(class_id, section_id) {
        if (class_id !== "") {
            $('#section_id').html('<option value="">Select</option>');
            $.ajax({
                type: "GET",
                url: "<?php echo base_url('sections/getByClass') ?>",
                data: {'class_id': class_id},
                dataType: "json",
                success: function (data) {
                    $.each(data, function (i, obj) {
                        var sel = (section_id == obj.section_id) ? "selected" : "";
                        $('#section_id').append("<option value='" + obj.section_id + "' " + sel + ">" + obj.section + "</option>");
                    });
                }
            });
        }
    }

    // --- Bulk Save Grid Logic ---
    function saveBulkMarks() {
        var exam_group_id = $('#exam_group_id').val();
        var exam_date = $('#exam_date').val();

        if(!exam_group_id || !exam_date) {
            alert("Exam Group and Exam Date are missing. Please search again.");
            return;
        }

        var grid_data = [];
        var isValid = true;
        var errorMessage = "Please fill out the fields correctly.";

        // Iterate through each student row
        $('.student-row').each(function() {
            var student_id = $(this).data('student-id');
            var studentMarks = [];

            // Iterate through each subject cell in this row
            $(this).find('.subject-cell').each(function() {
                var subject_id = $(this).data('subject-id');
                var $obtInput = $(this).find('.obt-input');
                var $totInput = $(this).find('.tot-input');
                
                var obt_val = $obtInput.val();
                var tot_val = $totInput.val();

                // Reset borders
                $obtInput.css('border-color', '#ccc');
                $totInput.css('border-color', '#ccc');

                // Validation 1: Cannot have obtained marks without max marks
                if (obt_val !== "" && tot_val === "") {
                    isValid = false; 
                    errorMessage = "Validation Error: If you enter 'Obtained' marks, you MUST enter 'Out Of' marks.";
                    $totInput.css('border-color', 'red');
                } 
                // NEW Validation 2: Obtained cannot be greater than Total
                else if (obt_val !== "" && tot_val !== "") {
                    var obt_num = parseFloat(obt_val);
                    var tot_num = parseFloat(tot_val);
                    
                    if (obt_num > tot_num) {
                        isValid = false;
                        errorMessage = "Validation Error: Obtained marks (" + obt_val + ") cannot be greater than Out Of marks (" + tot_val + ").";
                        $obtInput.css('border-color', 'red');
                    }
                }

                // Push to array if total marks exist
                if (tot_val !== "") {
                    studentMarks.push({
                        subject_id: subject_id,
                        obtained_marks: obt_val,
                        total_marks: tot_val
                    });
                }
            });

            // Only push the student if they have data
            if (studentMarks.length > 0) {
                grid_data.push({
                    student_id: student_id,
                    marks: studentMarks
                });
            }
        });

        // Trigger the alert and stop saving if validation failed
        if (!isValid) {
            alert(errorMessage);
            return;
        }

        if (grid_data.length === 0) {
            alert("No data entered. Please enter marks before saving.");
            return;
        }

        var $btn = $('#saveAllMarksBtn');
        var originalText = $btn.html();
        $btn.html('<i class="fa fa-spinner fa-spin"></i> Saving...').prop('disabled', true);

        $.ajax({
            url: "<?php echo base_url('admin/examcontroller/save_marks') ?>",
            type: "POST",
            dataType: "json",
            data: {
                exam_group_id: exam_group_id,
                exam_date: exam_date,
                grid_data: grid_data,
                '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
            },
            success: function(response) {
                if(response.status === 'success') {
                    alert(response.message);
                    $('#bulkReportBtnContainer').show(); // Reveal print button
                } else {
                    alert("Error: " + response.message);
                }
            },
            error: function() {
                alert("A server error occurred while saving marks.");
            },
            complete: function() {
                $btn.html(originalText).prop('disabled', false);
            }
        });
    }
</script>