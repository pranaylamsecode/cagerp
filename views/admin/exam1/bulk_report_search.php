<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1><i class="fa fa-file-text-o"></i> Generate Bulk Examination Report</h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> Select Report Criteria</h3>
                    </div>
                    <div class="box-body">
                        <form role="form" action="<?php echo site_url('admin/examcontroller/bulk_report') ?>" method="post" target="_blank">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                
                                <div class="col-sm-6 col-lg-3 col-md-3">
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
                                
                                <div class="col-sm-6 col-lg-3 col-md-3">
                                    <div class="form-group">
                                        <label>Exam Date</label><small class="req"> *</small>
                                        <input type="date" id="exam_date" name="exam_date" class="form-control" required>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-lg-3 col-md-3">
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

                                <div class="col-sm-6 col-lg-3 col-md-3">
                                    <div class="form-group">
                                        <label>Section</label><small class="req"> *</small>
                                        <select id="section_id" name="section_id" class="form-control" required>
                                            <option value="">Select Class First</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-12 mt-2">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-print"></i> Generate Bulk Report</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
    // Dynamically load Batches based on the selected Course
    $(document).on('change', '#class_id', function () {
        var class_id = $(this).val();
        $('#section_id').html('<option value="">Loading...</option>');

        if (class_id !== "") {
            $.ajax({
                type: "GET",
                url: "<?php echo base_url('sections/getByClass') ?>",
                data: {'class_id': class_id},
                dataType: "json",
                success: function (data) {
                    $('#section_id').html('<option value="">Select</option>');
                    $.each(data, function (i, obj) {
                        $('#section_id').append("<option value='" + obj.section_id + "'>" + obj.section + "</option>");
                    });
                },
                error: function() {
                    $('#section_id').html('<option value="">Error Loading Batches</option>');
                }
            });
        } else {
            $('#section_id').html('<option value="">Select Course First</option>');
        }
    });
</script>
