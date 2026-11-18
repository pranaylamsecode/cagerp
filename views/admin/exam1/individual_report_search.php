<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1><i class="fa fa-file-text-o"></i> Generate Individual Student Report</h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> Select Report Criteria</h3>
                    </div>
                    <div class="box-body">
                        <form role="form" id="reportSearchForm">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Exam Group</label><small class="req"> *</small>
                                        <select id="exam_group_id" name="exam_group_id" class="form-control select2" required>
                                            <option value="">Select</option>
                                            <?php foreach ($examgrouplist as $ex_group) { ?>
                                                <option value="<?php echo $ex_group->id ?>"><?php echo $ex_group->name; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Exam Date</label><small class="req"> *</small>
                                        <input type="date" id="exam_date" name="exam_date" class="form-control" required>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Class</label><small class="req"> *</small>
                                        <select id="class_id" name="class_id" class="form-control" required>
                                            <option value="">Select</option>
                                            <?php foreach ($classlist as $class) { ?>
                                                <option value="<?php echo $class['id'] ?>"><?php echo $class['class'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Section</label><small class="req"> *</small>
                                        <select id="section_id" name="section_id" class="form-control" required>
                                            <option value="">Select</option>
                                            </select>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Student</label><small class="req"> *</small>
                                        <select id="student_id" name="student_id" class="form-control select2" required>
                                            <option value="">Select Class & Section First</option>
                                            </select>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-print"></i> Generate Report</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <div id="reportContainer"></div>
</div>

<script type="text/javascript">
    // 1. Load Batches when Course changes
    $(document).on('change', '#class_id', function () {
        var class_id = $(this).val();
        $('#section_id').html('<option value="">Select</option>');
        $('#student_id').html('<option value="">Select Class & Section First</option>');

        if (class_id !== "") {
            $.ajax({
                type: "GET",
                url: "<?php echo base_url('sections/getByClass') ?>",
                data: {'class_id': class_id},
                dataType: "json",
                success: function (data) {
                    $.each(data, function (i, obj) {
                        $('#section_id').append("<option value='" + obj.section_id + "'>" + obj.section + "</option>");
                    });
                }
            });
        }
    });

    // 2. Load Students when Batch changes
    $(document).on('change', '#section_id', function () {
        var class_id = $('#class_id').val();
        var section_id = $(this).val();
        $('#student_id').html('<option value="">Loading students...</option>');

        if (class_id !== "" && section_id !== "") {
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('admin/examcontroller/get_students_ajax') ?>",
                data: {
                    'class_id': class_id, 
                    'section_id': section_id,
                    '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                },
                dataType: "json",
                success: function (data) {
                    $('#student_id').html('<option value="">Select Student</option>');

                    // NEW: Add "All Students" option
                    $('#student_id').append('<option value="all" style="font-weight:bold; color:green;">-- All Students in Batch --</option>');

                    $.each(data, function (i, obj) {
                        $('#student_id').append("<option value='" + obj.student_id + "'>" + obj.firstname + " " + obj.lastname + " (" + obj.admission_no + ")</option>");
                    });
                }
            });
        }
    });

    // Handle "Generate Report" Submission via AJAX
    $('#reportSearchForm').on('submit', function(e) {
        e.preventDefault(); // Stop normal form submission

        var $btn = $(this).find('button[type="submit"]');
        var originalText = $btn.html();
        
        $btn.html('<i class="fa fa-spinner fa-spin"></i> Generating...').prop('disabled', true);
        $('#reportContainer').html('<div class="text-center" style="margin-top:20px;"><i class="fa fa-spinner fa-spin fa-2x"></i></div>');

        $.ajax({
            url: "<?php echo base_url('admin/examcontroller/individual_report') ?>",
            type: "POST",
            dataType: "json",
            data: $(this).serialize() + '&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>',
            success: function(response) {
                if(response.status === 'success') {
                    // Inject the returned HTML report into the container
                    $('#reportContainer').html(response.html);
                } else {
                    $('#reportContainer').html('<div class="alert alert-danger">' + response.message + '</div>');
                }
            },
            error: function() {
                $('#reportContainer').html('<div class="alert alert-danger">An error occurred while generating the report.</div>');
            },
            complete: function() {
                $btn.html(originalText).prop('disabled', false);
            }
        });
    });
</script>

<style>
    /* Global Table Border styling for the report cards */
    .border-dark th, .border-dark td { 
        border: 1px solid #000 !important; 
    }
    
    /* Print-Specific Styles */
    @media print {
        /* 1. Completely hide the Admin UI, Sidebar, Footer, and the Search Form */
        .main-header, .main-sidebar, .main-footer, .content-header, .content, .box-primary { 
            display: none !important; 
        }
        
        /* 2. Reset the main wrapper so it uses the full page without margins */
        .content-wrapper { 
            margin-left: 0 !important; 
            padding: 0 !important; 
            background-color: transparent !important; 
            min-height: auto !important;
        }

        /* 3. Keep the print container in the normal document flow (DO NOT use position: absolute) */
        #printArea { 
            position: relative !important; 
            width: 100% !important; 
            border: none !important; 
            box-shadow: none !important; 
        }

        /* 4. Enforce page breaks for each student's report card */
        .report-wrapper { 
            page-break-after: always !important; 
            border: none !important; /* Hide outer border to save ink/look cleaner */
            padding: 0 !important; 
            margin-bottom: 0 !important;
        }
        
        /* 5. Prevent a blank white page from printing at the very end */
        .report-wrapper:last-child {
            page-break-after: auto !important;
        }

        /* Hide specific elements tagged with no-print (like the print button itself) */
        .no-print { 
            display: none !important; 
        }
    }
</style>
