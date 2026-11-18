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
                            <h3 class="box-title titlefix"><i class="fa fa-users"></i> Student List</h3>
                        
                            <form action="<?php echo site_url('admin/examcontroller/bulk_report') ?>" method="post" target="_blank" class="pull-right">
                                <?php echo $this->customlib->getCSRF(); ?>
                                <input type="hidden" name="exam_group_id" value="<?php echo $this->input->post('exam_group_id'); ?>">
                                <input type="hidden" name="exam_date" value="<?php echo $this->input->post('exam_date'); ?>">
                                <input type="hidden" name="class_id" value="<?php echo $this->input->post('class_id'); ?>">
                                <input type="hidden" name="section_id" value="<?php echo $this->input->post('section_id'); ?>">
                                
                                <button type="submit" class="btn btn-warning btn-sm">
                                    <i class="fa fa-print"></i> Generate Bulk Report
                                </button>
                            </form>
                        
                        
                        </div>
                        <div class="box-body table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Admission No</th>
                                        <th>Student Name</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($studentList as $student) { ?>
                                        <tr>
                                            <td><?php echo $student['admission_no']; ?></td>
                                            <td><?php echo $student['firstname'] . " " . $student['lastname']; ?></td>
                                            <td class="text-right">
                                                <button type="button" class="btn btn-sm btn-success" 
                                                        onclick="openMarksModal('<?php echo $student['student_id']; ?>', '<?php echo addslashes($student['firstname'] . ' ' . $student['lastname']); ?>')">
                                                    <i class="fa fa-plus"></i> Enter Marks
                                                </button>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="marksModal" tabindex="-1" role="dialog" aria-labelledby="marksModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="marksModalLabel">Enter Marks for <span id="modal_student_name" class="text-bold"></span></h4>
            </div>
            <div class="modal-body">
                <form id="marksEntryForm">
                    <input type="hidden" id="modal_student_id" name="student_id">
                    
                    <table class="table table-bordered" id="dynamicMarksTable">
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Total Marks</th>
                                <th>Obtained Marks (Leave blank for Pending)</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="marksTableBody">
                            </tbody>
                    </table>
                    <button type="button" class="btn btn-info btn-sm" onclick="addRow()"><i class="fa fa-plus"></i> Add Subject</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="saveMarks()">Save Marks</button>
            </div>
        </div>
    </div>
</div>

<div style="display:none;" id="subjectDropdownTemplate">
    <select class="form-control subject-select" name="subject_id[]" required>
        <option value="">Select Subject</option>
        <?php foreach ($subjectlist as $sub) { ?>
            <option value="<?php echo $sub['id']; ?>"><?php echo $sub['name']; ?></option>
        <?php } ?>
    </select>
</div>

<script type="text/javascript">
    // Load sections when class changes (Preserved from your original code)
    var class_id = '<?php echo set_value('class_id') ?>';
    var section_id = '<?php echo set_value('section_id') ?>';
    
    if(class_id !== "") { getSectionByClass(class_id, section_id); }

    $(document).on('change', '#class_id', function () {
        getSectionByClass($(this).val(), 0);
    });

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

    // --- Dynamic Modal Logic ---
    // function openMarksModal(studentId, studentName) {
    //     $('#modal_student_id').val(studentId);
    //     $('#modal_student_name').text(studentName);
    //     $('#marksTableBody').html(''); // Clear previous entries
    //     addRow(); // Add default first row
    //     $('#marksModal').modal('show');
    // }

    // function addRow() {
    //     var subjectDropdown = $('#subjectDropdownTemplate').html();
    //     var rowHtml = `
    //         <tr>
    //             <td>${subjectDropdown}</td>
    //             <td><input type="number" step="0.01" class="form-control total-marks" name="total_marks[]" required></td>
    //             <td><input type="number" step="0.01" class="form-control obtained-marks" name="obtained_marks[]" placeholder="e.g. 15"></td>
    //             <td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)"><i class="fa fa-trash"></i></button></td>
    //         </tr>
    //     `;
    //     $('#marksTableBody').append(rowHtml);
    // }


    // --- Updated Dynamic Modal Logic ---
    function openMarksModal(studentId, studentName) {
        $('#modal_student_id').val(studentId);
        $('#modal_student_name').text(studentName);
        
        var exam_group_id = $('#exam_group_id').val();
        var exam_date = $('#exam_date').val();

        if(!exam_group_id || !exam_date) {
            alert("Please ensure Exam Group and Exam Date are selected.");
            return;
        }

        // Show loading state and open modal
        $('#marksTableBody').html('<tr><td colspan="4" class="text-center"><i class="fa fa-spinner fa-spin"></i> Loading data...</td></tr>');
        $('#marksModal').modal('show');

        // Fetch existing data
        $.ajax({
            url: "<?php echo base_url('admin/examcontroller/get_student_marks') ?>",
            type: "POST",
            dataType: "json",
            data: {
                exam_group_id: exam_group_id,
                exam_date: exam_date,
                student_id: studentId,
                '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
            },
            success: function(response) {
                $('#marksTableBody').html(''); // Clear loading text

                if(response.status === 'success' && response.data.length > 0) {
                    // Populate existing rows
                    $.each(response.data, function(index, mark) {
                        addRow(mark.subject_id, mark.total_marks, mark.obtained_marks, mark.status);
                    });
                } else {
                    // No data found, add one empty row
                    addRow();
                }
            },
            error: function() {
                $('#marksTableBody').html('<tr><td colspan="4" class="text-center text-danger">Failed to load data. Please try again.</td></tr>');
            }
        });
    }

    // Refactored addRow to accept optional parameters for pre-filling
    function addRow(subject_id, total_marks, obtained_marks, status) {
        // Defensive checks for empty parameters
        var subjId = (typeof subject_id !== 'undefined') ? subject_id : '';
        var tMarks = (typeof total_marks !== 'undefined') ? total_marks : '';
        var oMarks = (typeof obtained_marks !== 'undefined' && obtained_marks !== null) ? obtained_marks : '';
        var stat   = (typeof status !== 'undefined') ? status : 'present';

        var subjectDropdown = $('#subjectDropdownTemplate').html();
        
        // If status was pending, leave obtained marks blank visually
        var val_obtained = (stat === 'pending' || oMarks === '') ? '' : oMarks;
        
        var trHtml = $('<tr>' +
            '<td>' + subjectDropdown + '</td>' +
            '<td><input type="number" step="0.01" class="form-control total-marks" name="total_marks[]" value="' + tMarks + '" required></td>' +
            '<td><input type="number" step="0.01" class="form-control obtained-marks" name="obtained_marks[]" value="' + val_obtained + '" placeholder="e.g. 15"></td>' +
            '<td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)"><i class="fa fa-trash"></i></button></td>' +
        '</tr>');
        
        // Set the dropdown to the previously saved subject
        if(subjId !== '') {
            trHtml.find('.subject-select').val(subjId);
        }
        
        $('#marksTableBody').append(trHtml);
    }

    function removeRow(btn) {
        var rowCount = $('#marksTableBody tr').length;
        if (rowCount > 1) {
            $(btn).closest('tr').remove();
        } else {
            alert("You must have at least one subject row.");
        }
    }

    // function saveMarks() {
    //     // Collect Header Data
    //     var exam_group_id = $('#exam_group_id').val();
    //     var exam_date = $('#exam_date').val();
    //     var student_id = $('#modal_student_id').val();

    //     if(!exam_group_id || !exam_date) {
    //         alert("Please ensure Exam Group and Exam Date are selected in the background form.");
    //         return;
    //     }

    //     // Collect Dynamic Rows Data
    //     var marks_data = [];
    //     var isValid = true;

    //     $('#marksTableBody tr').each(function() {
    //         var sub_id = $(this).find('.subject-select').val();
    //         var total = $(this).find('.total-marks').val();
    //         var obtained = $(this).find('.obtained-marks').val();

    //         if (!sub_id || !total) { isValid = false; }

    //         marks_data.push({
    //             subject_id: sub_id,
    //             total_marks: total,
    //             obtained_marks: obtained
    //         });
    //     });

    //     if (!isValid) {
    //         alert("Please fill out all Subject and Total Marks fields.");
    //         return;
    //     }

    //     // Send via AJAX
    //     $.ajax({
    //         url: "<?php echo base_url('admin/examcontroller/save_marks') ?>",
    //         type: "POST",
    //         dataType: "json",
    //         data: {
    //             exam_group_id: exam_group_id,
    //             exam_date: exam_date,
    //             student_id: student_id,
    //             marks_data: marks_data,
    //             '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
    //         },
    //         success: function(response) {
    //             if(response.status === 'success') {
    //                 alert(response.message);
    //                 $('#marksModal').modal('hide');
    //             } else {
    //                 alert("Error: " + response.message);
    //             }
    //         },
    //         error: function() {
    //             alert("A server error occurred while saving marks.");
    //         }
    //     });
    // }

    function saveMarks() {
        var exam_group_id = $('#exam_group_id').val();
        var exam_date = $('#exam_date').val();
        var student_id = $('#modal_student_id').val();

        if(!exam_group_id || !exam_date) {
            alert("Please ensure Exam Group and Exam Date are selected.");
            return;
        }

        var marks_data = [];
        var isValid = true;
        var selectedSubjects = []; // NEW: Array to track duplicates
        var hasDuplicates = false;

        $('#marksTableBody tr').each(function() {
            var sub_id = $(this).find('.subject-select').val();
            var total = $(this).find('.total-marks').val();
            var obtained = $(this).find('.obtained-marks').val();

            if (!sub_id || !total) { isValid = false; }

            // NEW: Check for duplicate subjects
            if (sub_id && selectedSubjects.includes(sub_id)) {
                hasDuplicates = true;
            }
            if(sub_id) { selectedSubjects.push(sub_id); }

            marks_data.push({
                subject_id: sub_id,
                total_marks: total,
                obtained_marks: obtained
            });
        });

        if (!isValid) {
            alert("Please fill out all Subject and Total Marks fields.");
            return;
        }

        if (hasDuplicates) {
            alert("You have selected the same subject more than once. Please remove the duplicate row.");
            return;
        }

        // NEW: Disable the save button to prevent double-clicks
        var $saveBtn = $('#marksModal .modal-footer .btn-primary');
        var originalBtnText = $saveBtn.html();
        $saveBtn.html('<i class="fa fa-spinner fa-spin"></i> Saving...').prop('disabled', true);

        $.ajax({
            url: "<?php echo base_url('admin/examcontroller/save_marks') ?>",
            type: "POST",
            dataType: "json",
            data: {
                exam_group_id: exam_group_id,
                exam_date: exam_date,
                student_id: student_id,
                marks_data: marks_data,
                '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
            },
            success: function(response) {
                if(response.status === 'success') {
                    alert(response.message);
                    $('#marksModal').modal('hide');
                } else {
                    alert("Error: " + response.message);
                }
            },
            error: function() {
                alert("A server error occurred while saving marks.");
            },
            complete: function() {
                // NEW: Restore the button state
                $saveBtn.html(originalBtnText).prop('disabled', false);
            }
        });
    }
</script>