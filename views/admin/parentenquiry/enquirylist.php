<?php $currency_symbol = $this->customlib->getSchoolCurrencyFormat(); ?>
<div class="content-wrapper">

    <section class="content">
        <div class="row">
            <div class="col-md-12">

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('enquiry') . " " . $this->lang->line('list') ?></h3>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <div class="box-body table-responsive">
                        <div class="mailbox-messages">

                            <table class="table table-striped table-bordered table-hover student-list" data-export-title="<?php echo $this->lang->line('student') . " " . $this->lang->line('list'); ?>">
                                <thead>
                                    <tr>

                                        <th style="width:5%"><?php echo $this->lang->line('reference_no'); ?></th>

                                        <?php if ($this->customlib->getenquiryfieldstatus('mobile_no')) { ?>
                                            <th style="width:10%"><?php echo $this->lang->line('phone'); ?></th>
                                        <?php } ?>


                                        <th><?php echo $this->lang->line('student_name'); ?></th>

                                        <th><?php echo $this->lang->line('class'); ?></th>
                                        <?php if ($this->customlib->getenquiryfieldstatus('father_name')) { ?>
                                            <th><?php echo $this->lang->line('father_name'); ?></th>
                                        <?php } ?>
                                        <!-- <th><?php echo $this->lang->line('date_of_birth'); ?></th> -->
                                        <th><?php echo $this->lang->line('enquiry') . " " . $this->lang->line('date'); ?></th>
                                        <th><?php echo $this->lang->line('last_follow_up_date'); ?></th>
                                        <th><?php echo $this->lang->line('next_follow_up_date'); ?></th>

                                        <th><?php echo $this->lang->line('status'); ?></th>

                                        <!-- <th><?php echo $this->lang->line('gender'); ?></th>
                                        <th><?php echo $this->lang->line('category'); ?></th> -->

                                        <!-- <th><?php echo $this->lang->line('form') . ' ' . $this->lang->line('status'); ?></th> -->
                                        <?php if ($sch_setting->enquiry_form_payment == 'yes') { ?>
                                            <th><?php echo $this->lang->line('payment') . ' ' . $this->lang->line('status'); ?></th>
                                        <?php } ?>

                                        <th><?php echo $this->lang->line('enrolled'); ?></th>
                                        <th class="text-right noExport "><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>

        </div>




        <!--Edit Modal -->
        <div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content modal-media-content">
                    <div class="modal-header modal-media-header">
                        <h4 class="box-title" id="detailsModalLabel"><?php echo $this->lang->line('edit') . " " . $this->lang->line('enquiry'); ?></h4>
                        <button type="button" class="close" onclick="" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body pt0 pb0" id="getdetails">

                    </div>
                    <!-- <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('close'); ?></button>
                    </div> -->
                </div>
            </div>
        </div>



        <!-- Followup Modal -->
        <div class="modal fade" id="follow_up" tabindex="-1" role="dialog" aria-labelledby="follow_up">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content modal-media-content">
                    <div class="modal-header modal-media-header">
                        <button type="button" class="close" onclick="update()" data-dismiss="modal">&times;</button>
                        <h4 class="box-title"><?php echo $this->lang->line('follow_up_parent_enquiry'); ?></h4>
                    </div>
                    <div class="modal-body pt0 pb0" id="getdetails_follow_up">
                    </div>
                </div>
            </div>
        </div>


    </section>
</div>
<script>
    (function($) {
        'use strict';
        $(document).ready(function() {
            initDatatable('student-list', 'admin/parentenquiry/getstudentlist', [], [], 100);
        });
    }(jQuery))
</script>
<script>
    function getRecord(id) {
        $.ajax({
            url: '<?php echo base_url(); ?>admin/parentenquiry/getdetails/' + id,
            success: function(result) {
                $('#getdetails').html(result);
                $('#detailsModal').modal('show');
            },
            error: function() {
                alert('Error fetching data.');
            }
        });

    }


    function follow_up(id) {
        $.ajax({
            url: '<?php echo base_url(); ?>admin/parentenquiry/follow_up/' + id,
            success: function(data) {
                $('#getdetails_follow_up').html(data);
                $('#follow_up').modal('show');
                $.ajax({
                    url: '<?php echo base_url(); ?>admin/parentenquiry/follow_up_list/' + id,
                    success: function(data) {
                        $('#timeline').html(data);
                    },
                    error: function() {
                        alert("Fail")
                    }
                });
            },
            error: function() {
                alert("Fail")
            }
        });
    }


    function update() {
        window.location.reload(true);
    }


    function enrollEnquiry(id) {
        $.ajax({
            url: '<?php echo base_url(); ?>admin/parentenquiry/confirmationbox',
            type: "POST",
            data: {
                id: id
            },
            success: function(data) {

                if (data != "") {
                    if (confirm(data)) {
                        window.location.href = "<?php echo base_url() . 'admin/parentenquiry/edit/' ?>" + id;
                    } else {
                        return false;
                    }
                } else {
                    window.location.href = "<?php echo base_url() . 'admin/parentenquiry/edit/' ?>" + id;
                }
            }
        });
    }


    function postRecord(id) {
        $.ajax({
            url: '<?php echo base_url(); ?>admin/parentenquiry/editenquiry/' + id,
            type: 'POST',
            data: $("#myForm1").serialize(),
            dataType: 'json',
            success: function(data) {
                if (data.status == "fail") {
                    var message = "";
                    $.each(data.error, function(index, value) {
                        message += value;
                    });
                    errorMsg(message);
                } else {
                    successMsg(data.message);
                    window.location.reload(true);
                }
            },
            error: function() {
                alert("Fail")
            }
        });
    }
</script>