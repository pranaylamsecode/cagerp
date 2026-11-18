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
                                        <label>Class</label><small class="req"> *</small>
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
                                        <label>Section</label><small class="req"> *</small>
                                        <select id="section_id" name="section_id" class="form-control" required>
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Select Subjects</label><small class="req"> *</small>
                                        <div id="subject_checkboxes" style="display: flex; gap: 20px; flex-wrap: wrap; padding: 5px 0;">
                                            <span class="text-muted">Select Class & Section</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12 mt-2">
                                    <div class="form-group">
                                        <button type="submit" name="search" value="search_filter" class="btn btn-primary pull-right btn-sm"><i class="fa fa-search"></i> Render Table</button>
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
                                <!-- 1. Enter the Topic for each subject in the table headers. <br>
                                2. Enter Obtained marks (Left) and Max marks (Right). <strong style="text-decoration: underline;">Leave 'Obt' blank for Pending.</strong> -->

                                 1. Enter the <strong>Out Of Marks</strong> once for each subject.
                                <br>

                                2. Enter Obtained Marks for each student.
                                <br>

                                3. Leave Obtained Marks blank for <strong>Pending</strong>.
                                <br>

                                4. Enter <strong>absent</strong> if the student was absent.
                            </div>

                            <div class="table-responsive">
                                <?php if (empty($dynamic_subjects)) { ?>
                                    <div class="alert alert-danger">Please select at least one subject from the checkboxes above.</div>
                                <?php } else { ?>
                                    <table class="table table-striped table-bordered table-hover" id="marksGridTable">
                                        <thead>
                                            <tr>
                                                <th style="width: 80px; vertical-align: middle;">Roll No</th>
                                                <th style="vertical-align: middle;">Student Name</th>

                                                <?php foreach ($dynamic_subjects as $subject) { ?>
                                                    <th class="text-center" style="min-width: 160px; background-color: #f4f4f4;">
                                                        <?php
                                                        $sub_id = $subject['subject_id'];

                                                        // Get existing Out Of marks from existing records
                                                        $max_marks_val = '';

                                                        if (!empty($existing_marks)) {

                                                            foreach ($existing_marks as $stu_marks) {

                                                                if (isset($stu_marks[$sub_id])) {

                                                                    $mark_data = $stu_marks[$sub_id];

                                                                    if (
                                                                        isset($mark_data['total_marks']) &&
                                                                        $mark_data['total_marks'] !== ''
                                                                    ) {
                                                                        $max_marks_val = $mark_data['total_marks'];
                                                                        break;
                                                                    }
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                        <strong style="font-size: 16px;"><?php echo $subject['subject_name']; ?></strong>
                                                        <div style="margin-top: 8px; margin-bottom: 8px;">
                                                            <!-- <input type="text" class="form-control input-sm subject-topic-input" 
                                                                   data-subject-id="<?php echo $subject['subject_id']; ?>" 
                                                                   placeholder="Enter Topic (Required)" 
                                                                   style="text-align: center; border-color: #007bff;">

                                                                   <?php
                                                                    // $sub_id = $subject['subject_id'];
                                                                    // $topic_val = isset($existing_topics[$sub_id]) ? $existing_topics[$sub_id] : '';
                                                                    ?>
                                                                    <input type="text" class="form-control input-sm subject-topic-input" 
                                                                        data-subject-id="<?php //echo $sub_id; 
                                                                                            ?>" 
                                                                        placeholder="Enter Topic (Required)" 
                                                                        value="<?php //echo htmlspecialchars($topic_val); 
                                                                                ?>" 
                                                                        style="text-align: center; border-color: #007bff;"> -->

                                                            <input
                                                                type="number"
                                                                min="1"
                                                                step="1"
                                                                class="form-control input-sm subject-max-input"
                                                                data-subject-id="<?php echo $sub_id; ?>"
                                                                placeholder="Out Of"
                                                                value="<?php echo htmlspecialchars($max_marks_val); ?>"
                                                                style="text-align: center; border-color: #007bff;">
                                                        </div>
                                                        <!-- <small class="text-muted">(Obt &nbsp;/&nbsp; Out Of)</small> -->

                                                        <small class="text-muted">
                                                            Out Of
                                                        </small>
                                                    </th>
                                                <?php } ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($studentList as $student) {
                                                $stu_id = $student['student_id']; ?>
                                                <tr class="student-row" data-student-id="<?php echo $stu_id; ?>">
                                                    <td style="vertical-align: middle;"><?php echo $student['roll_no']; ?></td>
                                                    <td style="vertical-align: middle; font-weight: 500;"><?php echo $student['firstname'] . " " . $student['lastname']; ?></td>

                                                    <?php foreach ($dynamic_subjects as $subject) {

                                                        $sub_id = $subject['subject_id'];
                                                        $obt_val = '';
                                                        //$tot_val = '';

                                                        if (isset($existing_marks[$stu_id][$sub_id])) {

                                                            $mark_data = $existing_marks[$stu_id][$sub_id];
                                                            //$tot_val = $mark_data['total_marks'];

                                                            if ($mark_data['status'] == 'pending') {

                                                                $obt_val = '';
                                                            } elseif ($mark_data['status'] == 'absent') {

                                                                $obt_val = 'absent';
                                                            } else {

                                                                $obt_val = $mark_data['obtained_marks'];
                                                            }
                                                        }
                                                    ?>
                                                        <!-- <td class="text-center subject-cell" data-subject-id="<?php echo $sub_id; ?>" style="vertical-align: middle;">
                                                            <div style="display: flex; align-items: center; justify-content: center; gap: 5px;">
                                                                <input
                                                                    type="text"
                                                                    class="form-control input-sm obt-input"
                                                                    style="width: 70px; text-align: center;"
                                                                    placeholder="Obt"
                                                                    value="<?php echo $obt_val; ?>">
                                                                <span style="font-size: 18px; color: #ccc;">/</span>
                                                                <input type="number" step="1" class="form-control input-sm tot-input" style="width: 70px; text-align: center;" placeholder="Out Of" value="<?php echo $tot_val; ?>">
                                                            </div>
                                                        </td> -->

                                                        <td class="text-center subject-cell"
                                                            data-subject-id="<?php echo $sub_id; ?>"
                                                            style="vertical-align: middle;">

                                                            <input
                                                                type="text"
                                                                class="form-control input-sm obt-input"
                                                                style="width: 80px; margin: auto; text-align: center;"
                                                                placeholder="Obt"
                                                                value="<?php echo $obt_val; ?>"
                                                            >

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
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
    // Preserved variables for repopulating
    var class_id = '<?php echo set_value('class_id') ?>';
    var section_id = '<?php echo set_value('section_id') ?>';

    // Parse the selected subjects array from PHP
    var selected_subjects = <?php echo json_encode($selected_subject_ids); ?>;

    if (class_id !== "") {
        getSectionByClass(class_id, section_id);
    }
    if (class_id !== "" && section_id !== "") {
        getSubjectsByClassSection(class_id, section_id, selected_subjects);
    }

    // Events
    $(document).on('change', '#class_id', function() {
        getSectionByClass($(this).val(), 0);
        $('#subject_checkboxes').html('<span class="text-muted">Select Batch First</span>');
    });

    $(document).on('change', '#section_id', function() {
        var c_id = $('#class_id').val();
        getSubjectsByClassSection(c_id, $(this).val(), []);
    });

    function getSectionByClass(class_id, section_id) {
        if (class_id !== "") {
            $('#section_id').html('<option value="">Loading...</option>');
            $.ajax({
                type: "GET",
                url: "<?php echo base_url('sections/getByClass') ?>",
                data: {
                    'class_id': class_id
                },
                dataType: "json",
                success: function(data) {
                    $('#section_id').html('<option value="">Select</option>');
                    $.each(data, function(i, obj) {
                        var sel = (section_id == obj.section_id) ? "selected" : "";
                        $('#section_id').append("<option value='" + obj.section_id + "' " + sel + ">" + obj.section + "</option>");
                    });
                }
            });
        }
    }

    // NEW AJAX Function to render Checkboxes
    function getSubjectsByClassSection(class_id, section_id, active_ids) {
        if (class_id !== "" && section_id !== "") {
            $('#subject_checkboxes').html('<span class="text-muted"><i class="fa fa-spinner fa-spin"></i> Loading...</span>');
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('admin/examcontroller/get_subjects_ajax') ?>",
                data: {
                    'class_id': class_id,
                    'section_id': section_id,
                    '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                },
                dataType: "json",
                success: function(data) {
                    $('#subject_checkboxes').html('');
                    if (data.length === 0) {
                        $('#subject_checkboxes').html('<span class="text-danger">No subjects assigned to this batch.</span>');
                    } else {
                        $.each(data, function(i, obj) {
                            // Check if this ID is in the active_ids array
                            var isChecked = (active_ids && active_ids.includes(obj.subject_id.toString())) ? "checked" : "";

                            var checkboxHtml = `
                                <label style="font-weight: normal; cursor: pointer; display: flex; align-items: center; gap: 5px;">
                                    <input type="checkbox" name="subject_ids[]" value="${obj.subject_id}" ${isChecked}> 
                                    ${obj.subject_name}
                                </label>
                            `;
                            $('#subject_checkboxes').append(checkboxHtml);
                        });
                    }
                }
            });
        }
    }

    function saveBulkMarks() {
        var exam_group_id = $('#exam_group_id').val();
        var exam_date = $('#exam_date').val();

        if (!exam_group_id || !exam_date) {
            alert("Exam Group and Exam Date are missing. Please search again.");
            return;
        }

        // NEW: Collect Topics from Headers
        // var topics = {};
        // var missingTopic = false;

        // $('.subject-topic-input').each(function() {
        //     var s_id = $(this).data('subject-id');
        //     var topic_val = $(this).val().trim();

        //     if (topic_val === '') {
        //         missingTopic = true;
        //         $(this).css('border-color', 'red');
        //     } else {
        //         $(this).css('border-color', '#007bff');
        //         topics[s_id] = topic_val;
        //     }
        // });

        // if (missingTopic) {
        //     alert("Validation Error: Please enter a Topic for every subject column before saving.");
        //     return;
        // }


         /*
        * -------------------------------------------------------
        * 1. Collect subject-wise Out Of marks
        * -------------------------------------------------------
        */

        var max_marks = {};
        var missingMaxMarks = false;

        $('.subject-max-input').each(function () {

            var subject_id = $(this).data('subject-id');
            var max_val = $.trim($(this).val());

            $(this).css('border-color', '#007bff');

            if (max_val === '') {

                missingMaxMarks = true;

                $(this).css('border-color', 'red');

            } else {

                var max_num = parseFloat(max_val);

                if (isNaN(max_num) || max_num <= 0) {

                    missingMaxMarks = true;

                    $(this).css('border-color', 'red');

                } else {

                    max_marks[subject_id] = max_val;
                }
            }
        });

        if (missingMaxMarks) {

            alert(
                "Validation Error: Please enter valid Out Of marks for every subject."
            );

            return;
        }



          /*
     * -------------------------------------------------------
     * 2. Collect student marks
     * -------------------------------------------------------
     */

        var grid_data = [];
        var isValid = true;
        var errorMessage = "Please fill out the fields correctly.";

        $('.student-row').each(function() {
            var student_id = $(this).data('student-id');
            var studentMarks = [];

            $(this).find('.subject-cell').each(function() {
                var subject_id = $(this).data('subject-id');
                var $obtInput = $(this).find('.obt-input');
                var $totInput = $(this).find('.tot-input');

                var obt_val = $obtInput.val().trim();
                var status = 'present';

                // Pending
                if (obt_val === '') {
                    status = 'pending';
                }
                // Absent
                else if (/^absent$/i.test(obt_val)) {
                    status = 'absent';
                    obt_val = 0;
                }

                /*
             * Numeric validation
             */
            else {

                var obt_num = parseFloat(obt_val);
                var max_num = parseFloat(max_marks[subject_id]);

                if (isNaN(obt_num)) {

                    isValid = false;

                    errorMessage =
                        "Validation Error: Obtained marks must be a valid number or 'absent'.";

                    $obtInput.css('border-color', 'red');

                    return false;
                }

                if (obt_num < 0) {

                    isValid = false;

                    errorMessage =
                        "Validation Error: Obtained marks cannot be negative.";

                    $obtInput.css('border-color', 'red');

                    return false;
                }

                if (obt_num > max_num) {

                    isValid = false;

                    errorMessage =
                        "Validation Error: Obtained marks cannot be greater than Out Of marks.";

                    $obtInput.css('border-color', 'red');

                    return false;
                }
            }


                // var tot_val = $totInput.val();

                // $obtInput.css('border-color', '#ccc');
                // $totInput.css('border-color', '#ccc');

                // if (obt_val !== "" && tot_val === "") {
                //     isValid = false;
                //     errorMessage = "Validation Error: If you enter 'Obtained' marks, you MUST enter 'Max' marks.";
                //     $totInput.css('border-color', 'red');
                // } else if (obt_val !== "" && tot_val !== "") {
                //     var obt_num = parseFloat(obt_val);
                //     var tot_num = parseFloat(tot_val);

                //     if (obt_num > tot_num) {
                //         isValid = false;
                //         errorMessage = "Validation Error: Obtained marks cannot be greater than Max marks.";
                //         $obtInput.css('border-color', 'red');
                //     }
                // }

                //if (tot_val !== "") {
                    studentMarks.push({
                        subject_id: subject_id,
                        obtained_marks: obt_val,
                        //total_marks: tot_val,
                        status: status
                    });
                //}
            });

            if (studentMarks.length > 0) {
                grid_data.push({
                    student_id: student_id,
                    marks: studentMarks
                });
            }
        });

        if (!isValid) {
            alert(errorMessage);
            return;
        }
        if (grid_data.length === 0) {
            alert("No marks entered. Please enter marks before saving.");
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
                //topics: topics, // Send the extracted topics
                max_marks: max_marks,
                grid_data: grid_data,
                '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
            },
            success: function(response) {
                if (response.status === 'success') {
                    alert(response.message);
                    $('#bulkReportBtnContainer').show();
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
<script>
    $(document).on('input', '.obt-input', function() {

        let value = $(this).val().toLowerCase();

        // Allow empty
        if (value === '') {
            return;
        }

        if (value === '-' || value === '.' || value === '-.') {
            return;
        }

        // Allow numbers
        // if (/^-?\d+$/.test(value)) {
        //     return;
        // }
        if (/^-?\d*\.?\d*$/.test(value)) {
            return;
        }

        // Allow typing "absent" progressively
        if ('absent'.startsWith(value)) {
            return;
        }

        // Remove invalid character
        $(this).val($(this).val().slice(0, -1));

    });
</script>
