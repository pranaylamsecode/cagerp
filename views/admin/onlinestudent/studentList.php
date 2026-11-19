<?php $currency_symbol = $this->customlib->getSchoolCurrencyFormat(); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">  

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('student') . " " . $this->lang->line('list') ?></h3>
                        <div class="box-tools pull-right">
                            <a href="<?php echo site_url('admin/paymenttransactions'); ?>" class="btn btn-primary btn-sm"><i class="fa fa-credit-card"></i> All Payment Transactions</a>
                            <button type="button" class="btn btn-warning btn-sm" onclick="syncAllAdmissionPayments()"><i class="fa fa-refresh"></i> Sync Unsynced Payments</button>
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <!-- Filters for Payment Status, Form Status & Class -->
                        <div class="well well-sm" style="background: #fdfdfe; border: 1px solid #d2d6de; border-left: 4px solid #3c8dbc; margin-bottom: 15px; border-radius: 3px;">
                            <div class="row">
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <label style="font-size: 12px; font-weight: 600; color: #333; margin-bottom: 4px;">
                                        <i class="fa fa-credit-card text-primary"></i> Payment Status:
                                    </label>
                                    <select id="filter_payment_status" class="form-control input-sm" style="width: 100%;">
                                        <option value="">All Payment Statuses</option>
                                        <option value="paid">&#10004; Paid</option>
                                        <option value="unpaid">&#10008; Unpaid</option>
                                    </select>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <label style="font-size: 12px; font-weight: 600; color: #333; margin-bottom: 4px;">
                                        <i class="fa fa-file-text-o text-success"></i> Form Status:
                                    </label>
                                    <select id="filter_form_status" class="form-control input-sm" style="width: 100%;">
                                        <option value="">All Form Statuses</option>
                                        <option value="submitted">&#10004; Submitted</option>
                                        <option value="not_submitted">&#10008; Not Submitted</option>
                                    </select>
                                </div>

                                <?php if (!empty($classlist)) { ?>
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <label style="font-size: 12px; font-weight: 600; color: #333; margin-bottom: 4px;">
                                        <i class="fa fa-graduation-cap text-warning"></i> Class:
                                    </label>
                                    <select id="filter_class_id" class="form-control input-sm" style="width: 100%;">
                                        <option value="">All Classes</option>
                                        <?php foreach ($classlist as $c) { ?>
                                            <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['class']); ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <?php } ?>

                                <div class="col-md-3 col-sm-6 col-xs-12" style="margin-top: 22px;">
                                    <button type="button" class="btn btn-primary btn-sm" id="btn-apply-filter" onclick="applyOnlineStudentFilter()">
                                        <i class="fa fa-filter"></i> Filter
                                    </button>
                                    <button type="button" class="btn btn-default btn-sm" id="btn-reset-filter" onclick="resetOnlineStudentFilter()">
                                        <i class="fa fa-undo"></i> Reset
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="mailbox-messages">
                            
                            <table class="table table-striped table-bordered table-hover student-list" data-export-title="<?php echo $this->lang->line('student')." ".$this->lang->line('list'); ?>">
                                <thead>
                                    <tr>

                                        <th style="width:5%"><?php echo $this->lang->line('reference_no'); ?></th>
                                        <th><?php echo 'Admission Date'; ?></th>

                                        <th><?php echo $this->lang->line('student_name'); ?></th>
                                        <th><?php echo $this->lang->line('class'); ?></th>
                                         <?php if ($sch_setting->father_name) { ?>
                                            <th><?php echo $this->lang->line('father_name'); ?></th>
                                        <?php } ?>
                                        <th><?php echo $this->lang->line('date_of_birth'); ?></th>
                                        <th><?php echo $this->lang->line('gender'); ?></th>
                                        <th><?php echo $this->lang->line('category'); ?></th>
                                          <?php  if ($sch_setting->mobile_no) { ?>
                                        <th style="width:10%"><?php echo $this->lang->line('student') . " " . $this->lang->line('mobile_no'); ?></th>
                                       <?php } ?>
                                        <th><?php echo $this->lang->line('form').' '.$this->lang->line('status'); ?></th>
                                        <?php if($sch_setting->online_admission_payment=='yes'){ ?>
                                            <th><?php echo $this->lang->line('payment').' '.$this->lang->line('status'); ?></th>
                                            <?php } ?>
                                        
                                        <th><?php echo $this->lang->line('enrolled'); ?></th>
                                        <th class="text-right noExport "><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody> 
                                </tbody>
                            </table><!-- /.table -->
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->

                </div>
            </div><!--/.col (left) -->
            <!-- right column -->

        </div>

    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<!-- Modal: View Admission Payment Details -->
<div class="modal fade" id="admissionPaymentModal" tabindex="-1" role="dialog" aria-labelledby="admModalTitle">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="admModalTitle" style="color:#fff;"><i class="fa fa-credit-card"></i> Student Payment Details</h4>
            </div>
            <div class="modal-body" id="admission-modal-body">
                <div class="text-center"><i class="fa fa-spinner fa-spin fa-2x"></i> Loading...</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
function applyOnlineStudentFilter() {
    var params = {
        'payment_status': $('#filter_payment_status').val(),
        'form_status': $('#filter_form_status').val(),
        'class_id': $('#filter_class_id').val()
    };
    initDatatable('student-list', 'admin/onlinestudent/getstudentlist', params, [], 100);
}

function resetOnlineStudentFilter() {
    $('#filter_payment_status').val('');
    $('#filter_form_status').val('');
    if ($('#filter_class_id').length) {
        $('#filter_class_id').val('');
    }
    applyOnlineStudentFilter();
}

(function ($) {
    'use strict';
    $(document).ready(function () {
        applyOnlineStudentFilter();

        // Auto-filter immediately when any dropdown changes
        $('#filter_payment_status, #filter_form_status, #filter_class_id').on('change', function () {
            applyOnlineStudentFilter();
        });
    });
}(jQuery));

function viewAdmissionPayment(id) {
    $('#admission-modal-body').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-2x"></i> Loading...</div>');
    $('#admissionPaymentModal').modal('show');

    $.ajax({
        url: '<?php echo base_url(); ?>admin/onlinestudent/getpaymentdetails',
        type: 'POST',
        dataType: 'json',
        data: { id: id },
        success: function (res) {
            if (res.status === 'success' && res.data.length > 0) {
                var p = res.data[0];
                var html = '<table class="table table-bordered table-striped">' +
                    '<tr><th style="width:35%">Reference Number:</th><td><strong>' + (p.reference_no || 'N/A') + '</strong></td></tr>' +
                    '<tr><th>Student Name:</th><td>' + (p.firstname || '') + ' ' + (p.lastname || '') + '</td></tr>' +
                    '<tr><th>Mobile / Email:</th><td>' + (p.mobileno || 'N/A') + ' / ' + (p.email || 'N/A') + '</td></tr>' +
                    '<tr><th>Amount Paid:</th><td><span class="label label-success" style="font-size:14px;">₹' + parseFloat(p.paid_amount).toFixed(2) + '</span></td></tr>' +
                    '<tr><th>Payment Gateway / Mode:</th><td><span class="badge bg-navy">' + (p.payment_mode || '').toUpperCase() + '</span> (' + (p.payment_type || 'online') + ')</td></tr>' +
                    '<tr><th>Transaction ID:</th><td><code>' + p.transaction_id + '</code></td></tr>' +
                    (p.order_id ? '<tr><th>Order ID:</th><td><code>' + p.order_id + '</code></td></tr>' : '') +
                    '<tr><th>Payment Date & Time:</th><td>' + p.date + '</td></tr>' +
                    '<tr><th>Note:</th><td>' + (p.note || 'None') + '</td></tr>' +
                    '<tr><th>Status:</th><td>' + (p.paid_status == 1 ? '<span class="label label-success">Paid & Submitted</span>' : '<span class="label label-warning">Unsynced (Payment recorded in DB)</span>') + '</td></tr>' +
                    '</table>';

                if (p.paid_status != 1) {
                    html += '<div class="text-center" style="margin-top:15px;">' +
                        '<button type="button" class="btn btn-warning" onclick="syncAdmissionPayment(' + p.admission_id + ')"><i class="fa fa-refresh"></i> Sync Status to Paid</button>' +
                        '</div>';
                }

                $('#admission-modal-body').html(html);
            } else {
                $('#admission-modal-body').html('<div class="alert alert-danger">No payment details found for this student.</div>');
            }
        },
        error: function () {
            $('#admission-modal-body').html('<div class="alert alert-danger">Error loading record.</div>');
        }
    });
}

function syncAdmissionPayment(id) {
    if (!confirm('Are you sure you want to mark this student as Paid and sync their admission status?')) {
        return;
    }

    $.ajax({
        url: '<?php echo base_url(); ?>admin/onlinestudent/syncpayment',
        type: 'POST',
        dataType: 'json',
        data: { id: id },
        success: function (res) {
            alert(res.message);
            location.reload();
        },
        error: function () {
            alert('Server error while syncing payment.');
        }
    });
}

function syncAllAdmissionPayments() {
    if (!confirm('This will find all students who have a payment record in the database and automatically mark their status as PAID. Proceed?')) {
        return;
    }

    $.ajax({
        url: '<?php echo base_url(); ?>admin/onlinestudent/syncallpayments',
        type: 'POST',
        dataType: 'json',
        success: function (res) {
            alert(res.message);
            location.reload();
        },
        error: function () {
            alert('Server error while syncing payments.');
        }
    });
}
</script>
<script>
    function checkpaymentstatus(id){
       $.ajax({
            url: '<?php echo base_url(); ?>admin/onlinestudent/checkpaymentstatus',
            type: "POST",
            data: {id:id},
            success: function (data) {

               if(data!=""){
                    if(confirm(data)){
                      window.location.href="<?php echo base_url().'admin/onlinestudent/edit/' ?>"+id ;
                    }else{
                         return false ;
                    }
                }else{
                     window.location.href="<?php echo base_url().'admin/onlinestudent/edit/' ?>"+id ;
                }
            }
        });

    }
</script>