<?php $currency_symbol = $this->customlib->getSchoolCurrencyFormat(); ?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-credit-card"></i> <?php echo $this->lang->line('online'); ?> Payment Transactions Tracking
            <small>Track all admission, mobile app & online fee payments in database</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('admin/admin/dashboard'); ?>"><i class="fa fa-dashboard"></i> <?php echo $this->lang->line('dashboard'); ?></a></li>
            <li><a href="<?php echo base_url('admin/onlinestudent'); ?>"><?php echo $this->lang->line('online_admission'); ?></a></li>
            <li class="active">Payment Transactions</li>
        </ol>
    </section>

    <section class="content">
        <!-- Top Stats Row -->
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-aqua">
                    <span class="info-box-icon"><i class="fa fa-user-plus"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Admission Payments</span>
                        <span class="info-box-number"><?php echo isset($stats['admission_count']) ? $stats['admission_count'] : 0; ?> (<?php echo $currency_symbol . number_format(isset($stats['admission_amount']) ? $stats['admission_amount'] : 0, 2); ?>)</span>
                        <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                        <span class="progress-description text-right">
                            <a href="<?php echo site_url('admin/paymenttransactions?tab=admission'); ?>" style="color:#fff; text-decoration:underline;">View Details &rarr;</a>
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-green">
                    <span class="info-box-icon"><i class="fa fa-mobile-phone"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">App / Atom Success</span>
                        <span class="info-box-number"><?php echo isset($stats['atom_success_count']) ? $stats['atom_success_count'] : 0; ?> (<?php echo $currency_symbol . number_format(isset($stats['atom_total_amount']) ? $stats['atom_total_amount'] : 0, 2); ?>)</span>
                        <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                        <span class="progress-description text-right">
                            <a href="<?php echo site_url('admin/paymenttransactions?tab=atom&status=SUCCESS'); ?>" style="color:#fff; text-decoration:underline;">View Success &rarr;</a>
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-red">
                    <span class="info-box-icon"><i class="fa fa-times-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">App Failed / Cancelled</span>
                        <span class="info-box-number"><?php echo (isset($stats['atom_failed_count']) ? $stats['atom_failed_count'] : 0) + (isset($stats['atom_cancelled_count']) ? $stats['atom_cancelled_count'] : 0); ?> attempts</span>
                        <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                        <span class="progress-description text-right">
                            <a href="<?php echo site_url('admin/paymenttransactions?tab=atom&status=FAILED'); ?>" style="color:#fff; text-decoration:underline;">View Failed &rarr;</a>
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box <?php echo (isset($stats['unsynced_count']) && $stats['unsynced_count'] > 0) ? 'bg-yellow' : 'bg-teal'; ?>">
                    <span class="info-box-icon"><i class="fa fa-refresh <?php echo (isset($stats['unsynced_count']) && $stats['unsynced_count'] > 0) ? 'fa-spin' : ''; ?>"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Unsynced Admissions</span>
                        <span class="info-box-number" id="unsynced-count-badge"><?php echo isset($stats['unsynced_count']) ? $stats['unsynced_count'] : 0; ?> Records</span>
                        <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                        <span class="progress-description text-right">
                            <?php if (isset($stats['unsynced_count']) && $stats['unsynced_count'] > 0) { ?>
                                <button type="button" class="btn btn-xs btn-default" onclick="syncAllAdmissionPayments()"><i class="fa fa-bolt"></i> Sync All Now</button>
                            <?php } else { ?>
                                <span style="color:#fff;">All in sync &check;</span>
                            <?php } ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <?php if (isset($stats['unsynced_count']) && $stats['unsynced_count'] > 0) { ?>
            <div class="alert alert-warning alert-dismissible" id="unsynced-alert-box">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-warning"></i> Payment Synchronization Alert!</h4>
                Found <strong><?php echo $stats['unsynced_count']; ?> student(s)</strong> whose admission fee has been successfully deposited in the database (<code>online_admission_payment</code>), but their status in <code>Online Admission</code> list shows <strong>Unpaid</strong>!
                <button type="button" class="btn btn-primary btn-sm pull-right" style="margin-top:-5px;" onclick="syncAllAdmissionPayments()">
                    <i class="fa fa-refresh"></i> Sync All Unsynced Records Now
                </button>
            </div>
        <?php } ?>

        <!-- Main Filter & Content Box -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-filter"></i> Filters & Search
                </h3>
                <div class="box-tools pull-right">
                    <a href="<?php echo site_url('admin/onlinestudent'); ?>" class="btn btn-default btn-sm">
                        <i class="fa fa-arrow-left"></i> Back to Student List
                    </a>
                    <a href="<?php echo site_url('admin/paymenttransactions/export_csv?tab=' . $active_tab); ?>" class="btn btn-success btn-sm">
                        <i class="fa fa-file-excel-o"></i> Export CSV
                    </a>
                </div>
            </div>

            <div class="box-body">
                <form method="get" action="<?php echo site_url('admin/paymenttransactions'); ?>" class="form-inline">
                    <input type="hidden" name="tab" value="<?php echo htmlspecialchars($active_tab); ?>">

                    <div class="form-group" style="margin-right: 10px;">
                        <label>Search: </label>
                        <input type="text" name="search" class="form-control input-sm" placeholder="Ref No / Txn ID / Name / Mobile" value="<?php echo htmlspecialchars(!empty($search) ? $search : ''); ?>" style="width: 220px;">
                    </div>

                    <div class="form-group" style="margin-right: 10px;">
                        <label>From: </label>
                        <input type="date" name="date_from" class="form-control input-sm" value="<?php echo htmlspecialchars(!empty($date_from) ? $date_from : ''); ?>">
                    </div>

                    <div class="form-group" style="margin-right: 10px;">
                        <label>To: </label>
                        <input type="date" name="date_to" class="form-control input-sm" value="<?php echo htmlspecialchars(!empty($date_to) ? $date_to : ''); ?>">
                    </div>

                    <?php if ($active_tab == 'admission') { ?>
                        <div class="form-group" style="margin-right: 10px;">
                            <label>Sync Status: </label>
                            <select name="status" class="form-control input-sm">
                                <option value="">All Statuses</option>
                                <option value="synced" <?php echo $status == 'synced' ? 'selected' : ''; ?>>Synced (Paid)</option>
                                <option value="unsynced" <?php echo $status == 'unsynced' ? 'selected' : ''; ?>>Unsynced (Pending)</option>
                            </select>
                        </div>
                    <?php } elseif ($active_tab == 'atom') { ?>
                        <div class="form-group" style="margin-right: 10px;">
                            <label>Status: </label>
                            <select name="status" class="form-control input-sm">
                                <option value="">All Statuses</option>
                                <option value="SUCCESS" <?php echo $status == 'SUCCESS' ? 'selected' : ''; ?>>SUCCESS</option>
                                <option value="FAILED" <?php echo $status == 'FAILED' ? 'selected' : ''; ?>>FAILED</option>
                                <option value="CANCELLED" <?php echo $status == 'CANCELLED' ? 'selected' : ''; ?>>CANCELLED</option>
                            </select>
                        </div>
                    <?php } ?>

                    <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i> Filter</button>
                    <a href="<?php echo site_url('admin/paymenttransactions?tab=' . $active_tab); ?>" class="btn btn-default btn-sm"><i class="fa fa-undo"></i> Reset</a>
                </form>
            </div>
        </div>

        <!-- Custom Tabs -->
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="<?php echo $active_tab == 'admission' ? 'active' : ''; ?>">
                    <a href="<?php echo site_url('admin/paymenttransactions?tab=admission'); ?>">
                        <i class="fa fa-graduation-cap"></i> <strong>Online Admission Payments</strong> 
                        <span class="badge bg-blue"><?php echo count($admission_payments); ?></span>
                    </a>
                </li>
                <li class="<?php echo $active_tab == 'atom' ? 'active' : ''; ?>">
                    <a href="<?php echo site_url('admin/paymenttransactions?tab=atom'); ?>">
                        <i class="fa fa-mobile"></i> <strong>Phone App / Gateway Transactions (Atom)</strong> 
                        <span class="badge bg-green"><?php echo count($atom_transactions); ?></span>
                    </a>
                </li>
                <li class="<?php echo $active_tab == 'feedeposit' ? 'active' : ''; ?>">
                    <a href="<?php echo site_url('admin/paymenttransactions?tab=feedeposit'); ?>">
                        <i class="fa fa-money"></i> <strong>Student Fee Online Deposits</strong> 
                        <span class="badge bg-purple"><?php echo count($fee_deposits); ?></span>
                    </a>
                </li>
                <li class="<?php echo $active_tab == 'enquiry' ? 'active' : ''; ?>">
                    <a href="<?php echo site_url('admin/paymenttransactions?tab=enquiry'); ?>">
                        <i class="fa fa-question-circle"></i> <strong>Enquiry Payments</strong> 
                        <span class="badge bg-yellow"><?php echo count($enquiry_payments); ?></span>
                    </a>
                </li>
            </ul>

            <div class="tab-content" style="padding: 15px;">
                <!-- TAB 1: Online Admission Payments -->
                <?php if ($active_tab == 'admission') { ?>
                    <div class="tab-pane active" id="tab_admission">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover datatable-export">
                                <thead>
                                    <tr class="bg-gray-light">
                                        <th>#</th>
                                        <th>Reference No</th>
                                        <th>Student Name</th>
                                        <th>Class</th>
                                        <th>Mobile / Email</th>
                                        <th>Amount (₹)</th>
                                        <th>Gateway Mode</th>
                                        <th>Transaction ID</th>
                                        <th>Payment Date & Time</th>
                                        <th>Sync Status</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($admission_payments)) {
                                        foreach ($admission_payments as $idx => $row) {
                                            $is_synced = ($row['paid_status'] == 1);
                                            $ref_no    = !empty($row['reference_no']) ? $row['reference_no'] : 'N/A';
                                            $s_fname   = !empty($row['firstname']) ? $row['firstname'] : '';
                                            $s_lname   = !empty($row['lastname']) ? $row['lastname'] : '';
                                            $s_class   = !empty($row['class']) ? $row['class'] : '';
                                            $s_sec     = !empty($row['section']) ? $row['section'] : '';
                                            $s_mob     = !empty($row['mobileno']) ? $row['mobileno'] : 'N/A';
                                            $s_email   = !empty($row['email']) ? $row['email'] : '';
                                            $pmode     = !empty($row['payment_mode']) ? $row['payment_mode'] : 'online';
                                    ?>
                                        <tr id="adm-row-<?php echo $row['admission_id']; ?>">
                                            <td><?php echo $idx + 1; ?></td>
                                            <td>
                                                <a href="<?php echo site_url('admin/onlinestudent/edit/' . $row['admission_id']); ?>" target="_blank" title="Edit Student">
                                                    <strong><?php echo htmlspecialchars($ref_no); ?></strong>
                                                </a>
                                            </td>
                                            <td><?php echo htmlspecialchars(trim($s_fname . ' ' . $s_lname)); ?></td>
                                            <td><?php echo htmlspecialchars($s_class . ' (' . $s_sec . ')'); ?></td>
                                            <td>
                                                <i class="fa fa-phone text-muted"></i> <?php echo htmlspecialchars($s_mob); ?><br>
                                                <small class="text-muted"><i class="fa fa-envelope-o"></i> <?php echo htmlspecialchars($s_email); ?></small>
                                            </td>
                                            <td><span class="label label-info" style="font-size: 13px;">₹<?php echo number_format($row['paid_amount'], 2); ?></span></td>
                                            <td><span class="badge bg-navy"><?php echo strtoupper(htmlspecialchars($pmode)); ?></span></td>
                                            <td>
                                                <code><?php echo htmlspecialchars($row['transaction_id']); ?></code>
                                                <?php if (!empty($row['order_id'])) { ?>
                                                    <br><small class="text-muted">Order: <?php echo htmlspecialchars($row['order_id']); ?></small>
                                                <?php } ?>
                                            </td>
                                            <td><?php echo date('d-m-Y h:i A', strtotime($row['date'])); ?></td>
                                            <td>
                                                <?php if ($is_synced) { ?>
                                                    <span class="label label-success"><i class="fa fa-check"></i> Paid (Synced)</span>
                                                <?php } else { ?>
                                                    <span class="label label-warning" title="Paid in DB but marked Unpaid in Student list"><i class="fa fa-exclamation-triangle"></i> Unsynced</span>
                                                <?php } ?>
                                            </td>
                                            <td class="text-right">
                                                <?php if (!$is_synced) { ?>
                                                    <button type="button" class="btn btn-xs btn-warning btn-sync" onclick="syncAdmissionPayment(<?php echo $row['admission_id']; ?>)" title="Sync status to Paid in Online Admission List">
                                                        <i class="fa fa-refresh"></i> Sync Status
                                                    </button>
                                                <?php } ?>
                                                <button type="button" class="btn btn-xs btn-default" onclick="viewAdmissionDetails(<?php echo $row['admission_id']; ?>)" title="View Full Payment Details">
                                                    <i class="fa fa-eye"></i> View
                                                </button>
                                            </td>
                                        </tr>
                                    <?php } } else { ?>
                                        <tr><td colspan="11" class="text-center text-muted">No admission payments found matching the criteria.</td></tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php } ?>

                <!-- TAB 2: Phone App / Atom Transactions -->
                <?php if ($active_tab == 'atom') { ?>
                    <div class="tab-pane active" id="tab_atom">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover datatable-export">
                                <thead>
                                    <tr class="bg-gray-light">
                                        <th>Trans ID</th>
                                        <th>Date & Time</th>
                                        <th>Customer / Student Name</th>
                                        <th>Mobile</th>
                                        <th>Student ID</th>
                                        <th>Amount (₹)</th>
                                        <th>Response Code</th>
                                        <th>Status</th>
                                        <th>Message / Description</th>
                                        <th>Atom Txn ID</th>
                                        <th>Bank Txn ID</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($atom_transactions)) {
                                        foreach ($atom_transactions as $row) {
                                            $disp_name = !empty($row['st_firstname']) ? ($row['st_firstname'] . ' ' . $row['st_lastname']) : (!empty($row['udf1']) ? $row['udf1'] : 'N/A');
                                            $disp_phone = !empty($row['st_mobileno']) ? $row['st_mobileno'] : (!empty($row['udf3']) ? $row['udf3'] : 'N/A');

                                            // Determine display status
                                            $status_str = !empty($row['response_status']) ? strtoupper($row['response_status']) : '';
                                            if (empty($status_str)) {
                                                if ($row['f_code'] == 'OTS0000' || $row['f_code'] == 'Ok' || $row['amt'] > 0) {
                                                    $status_str = 'SUCCESS';
                                                } elseif ($row['f_code'] == 'OTS0101') {
                                                    $status_str = 'CANCELLED';
                                                } else {
                                                    $status_str = 'FAILED';
                                                }
                                            }

                                            $status_badge = 'label-default';
                                            if ($status_str == 'SUCCESS') $status_badge = 'label-success';
                                            elseif ($status_str == 'CANCELLED') $status_badge = 'label-warning';
                                            elseif ($status_str == 'FAILED') $status_badge = 'label-danger';

                                            $resp_msg = !empty($row['response_message']) ? $row['response_message'] : (!empty($row['discriminator']) ? $row['discriminator'] : 'Processed by App');
                                            $atom_txn = !empty($row['mmp_txn']) ? $row['mmp_txn'] : (!empty($row['mer_txn']) ? $row['mer_txn'] : 'N/A');
                                    ?>
                                        <tr>
                                            <td><strong>#<?php echo $row['trans_id']; ?></strong></td>
                                            <td><?php echo !empty($row['date']) ? date('d-m-Y h:i A', strtotime($row['date'])) : 'N/A'; ?></td>
                                            <td>
                                                <strong><?php echo htmlspecialchars($disp_name); ?></strong>
                                                <?php if (!empty($row['admission_no'])) { ?>
                                                    <br><small class="text-muted">Adm No: <?php echo htmlspecialchars($row['admission_no']); ?></small>
                                                <?php } ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($disp_phone); ?></td>
                                            <td><?php echo htmlspecialchars(!empty($row['student_id']) ? $row['student_id'] : 'N/A'); ?></td>
                                            <td><span class="label label-primary" style="font-size: 13px;">₹<?php echo number_format((float)$row['amt'], 2); ?></span></td>
                                            <td><code><?php echo htmlspecialchars(!empty($row['f_code']) ? $row['f_code'] : 'N/A'); ?></code></td>
                                            <td><span class="label <?php echo $status_badge; ?>"><?php echo $status_str; ?></span></td>
                                            <td>
                                                <small><?php echo htmlspecialchars($resp_msg); ?></small>
                                            </td>
                                            <td><code><?php echo htmlspecialchars($atom_txn); ?></code></td>
                                            <td>
                                                <small><?php echo htmlspecialchars(!empty($row['bank_txn']) ? $row['bank_txn'] : 'N/A'); ?></small>
                                                <?php if (!empty($row['bank_name'])) { ?>
                                                    <br><small class="text-muted">(<?php echo htmlspecialchars($row['bank_name']); ?>)</small>
                                                <?php } ?>
                                            </td>
                                            <td class="text-right">
                                                <button type="button" class="btn btn-xs btn-default" onclick="viewAtomDetails(<?php echo $row['trans_id']; ?>)" title="View Decrypted Payload">
                                                    <i class="fa fa-info-circle"></i> Details
                                                </button>
                                            </td>
                                        </tr>
                                    <?php } } else { ?>
                                        <tr><td colspan="12" class="text-center text-muted">No Atom app transactions found matching the criteria.</td></tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php } ?>

                <!-- TAB 3: Student Fee Online Collections -->
                <?php if ($active_tab == 'feedeposit') { ?>
                    <div class="tab-pane active" id="tab_feedeposit">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover datatable-export">
                                <thead>
                                    <tr class="bg-gray-light">
                                        <th>Deposit ID</th>
                                        <th>Date</th>
                                        <th>Student Name</th>
                                        <th>Admission No</th>
                                        <th>Class</th>
                                        <th>Fee Group & Type</th>
                                        <th>Amount (₹)</th>
                                        <th>Payment Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($fee_deposits)) {
                                        foreach ($fee_deposits as $row) {
                                            $detail = json_decode($row['amount_detail'], true);
                                            $amt  = isset($detail['amount']) ? $detail['amount'] : 0;
                                            $date = isset($detail['date']) ? $detail['date'] : (isset($row['created_at']) ? $row['created_at'] : '');
                                            $desc = isset($detail['description']) ? $detail['description'] : 'online';
                                            $fg_name = !empty($row['fee_group_name']) ? $row['fee_group_name'] : '';
                                            $ft_type = !empty($row['fee_type']) ? $row['fee_type'] : '';
                                    ?>
                                        <tr>
                                            <td>#<?php echo $row['id']; ?></td>
                                            <td><?php echo date('d-m-Y', strtotime($date)); ?></td>
                                            <td><strong><?php echo htmlspecialchars(trim($row['firstname'] . ' ' . $row['lastname'])); ?></strong></td>
                                            <td><?php echo htmlspecialchars($row['admission_no']); ?></td>
                                            <td><?php echo htmlspecialchars($row['class'] . ' (' . $row['section'] . ')'); ?></td>
                                            <td><?php echo htmlspecialchars($fg_name . ' - ' . $ft_type); ?></td>
                                            <td><span class="label label-success" style="font-size: 13px;">₹<?php echo number_format((float)$amt, 2); ?></span></td>
                                            <td><span class="badge bg-green"><?php echo htmlspecialchars($desc); ?></span></td>
                                        </tr>
                                    <?php } } else { ?>
                                        <tr><td colspan="8" class="text-center text-muted">No fee online deposits found.</td></tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php } ?>

                <!-- TAB 4: Enquiry Payments -->
                <?php if ($active_tab == 'enquiry') { ?>
                    <div class="tab-pane active" id="tab_enquiry">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover datatable-export">
                                <thead>
                                    <tr class="bg-gray-light">
                                        <th>#</th>
                                        <th>Enquiry ID</th>
                                        <th>Candidate Name</th>
                                        <th>Contact No</th>
                                        <th>Amount (₹)</th>
                                        <th>Payment Mode</th>
                                        <th>Transaction ID</th>
                                        <th>Date & Time</th>
                                        <th>Note</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($enquiry_payments)) {
                                        foreach ($enquiry_payments as $idx => $row) {
                                            $cand_name = !empty($row['candidate_name']) ? $row['candidate_name'] : 'N/A';
                                            $cand_cont = !empty($row['contact']) ? $row['contact'] : 'N/A';
                                            $cand_note = !empty($row['note']) ? $row['note'] : '';
                                    ?>
                                        <tr>
                                            <td><?php echo $idx + 1; ?></td>
                                            <td>#<?php echo $row['enquiry_id']; ?></td>
                                            <td><strong><?php echo htmlspecialchars($cand_name); ?></strong></td>
                                            <td><?php echo htmlspecialchars($cand_cont); ?></td>
                                            <td><span class="label label-info" style="font-size: 13px;">₹<?php echo number_format($row['paid_amount'], 2); ?></span></td>
                                            <td><span class="badge bg-navy"><?php echo strtoupper($row['payment_mode']); ?></span></td>
                                            <td><code><?php echo htmlspecialchars($row['transaction_id']); ?></code></td>
                                            <td><?php echo date('d-m-Y h:i A', strtotime($row['date'])); ?></td>
                                            <td><small><?php echo htmlspecialchars($cand_note); ?></small></td>
                                        </tr>
                                    <?php } } else { ?>
                                        <tr><td colspan="9" class="text-center text-muted">No enquiry payments found.</td></tr>
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

<!-- Modal: View Admission Payment Details -->
<div class="modal fade" id="admissionDetailModal" tabindex="-1" role="dialog" aria-labelledby="admModalTitle">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="admModalTitle" style="color:#fff;"><i class="fa fa-info-circle"></i> Online Admission Payment Details</h4>
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

<!-- Modal: View Atom Transaction Details -->
<div class="modal fade" id="atomDetailModal" tabindex="-1" role="dialog" aria-labelledby="atomModalTitle">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-navy">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="atomModalTitle" style="color:#fff;"><i class="fa fa-code"></i> Atom Transaction Details & Decrypted Payload</h4>
            </div>
            <div class="modal-body" id="atom-modal-body">
                <div class="text-center"><i class="fa fa-spinner fa-spin fa-2x"></i> Loading...</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    if ($.fn.DataTable.isDataTable('.datatable-export')) {
        $('.datatable-export').DataTable().destroy();
    }
    $('.datatable-export').DataTable({
        "ordering": false,
        "pageLength": 50,
        "language": {
            "search": "Quick Filter:"
        }
    });
});

/**
 * Sync a single admission payment
 */
function syncAdmissionPayment(admission_id) {
    if (!confirm('Are you sure you want to mark this student as Paid and sync their form submission status?')) {
        return;
    }
    var btn = $('#adm-row-' + admission_id + ' .btn-sync');
    btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Syncing...');

    $.ajax({
        url: '<?php echo site_url("admin/paymenttransactions/sync_admission"); ?>',
        type: 'POST',
        dataType: 'json',
        data: { admission_id: admission_id },
        success: function (res) {
            if (res.status === 'success') {
                alert(res.message);
                location.reload();
            } else {
                alert(res.message);
                btn.prop('disabled', false).html('<i class="fa fa-refresh"></i> Sync Status');
            }
        },
        error: function () {
            alert('Server error occurred while syncing payment.');
            btn.prop('disabled', false).html('<i class="fa fa-refresh"></i> Sync Status');
        }
    });
}

/**
 * Sync all unsynced admission payments
 */
function syncAllAdmissionPayments() {
    if (!confirm('This will find all students who have a payment recorded in the database and automatically update their status to PAID and SUBMITTED. Proceed?')) {
        return;
    }

    $.ajax({
        url: '<?php echo site_url("admin/paymenttransactions/sync_all_admissions"); ?>',
        type: 'POST',
        dataType: 'json',
        success: function (res) {
            alert(res.message);
            location.reload();
        },
        error: function () {
            alert('Server error occurred while syncing all payments.');
        }
    });
}

/**
 * View admission payment details modal
 */
function viewAdmissionDetails(admission_id) {
    $('#admission-modal-body').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-2x"></i> Loading...</div>');
    $('#admissionDetailModal').modal('show');

    $.ajax({
        url: '<?php echo site_url("admin/paymenttransactions/get_admission_detail"); ?>',
        type: 'POST',
        dataType: 'json',
        data: { admission_id: admission_id },
        success: function (res) {
            if (res.status === 'success' && res.data.length > 0) {
                var p = res.data[0];
                var html = '<table class="table table-bordered table-striped">' +
                    '<tr><th style="width:35%">Reference Number:</th><td><strong>' + (p.reference_no || 'N/A') + '</strong></td></tr>' +
                    '<tr><th>Student Name:</th><td>' + (p.firstname || '') + ' ' + (p.lastname || '') + '</td></tr>' +
                    '<tr><th>Mobile / Email:</th><td>' + (p.mobileno || 'N/A') + ' / ' + (p.email || 'N/A') + '</td></tr>' +
                    '<tr><th>Amount Paid:</th><td><span class="label label-success" style="font-size:14px;">₹' + parseFloat(p.paid_amount).toFixed(2) + '</span></td></tr>' +
                    '<tr><th>Gateway / Mode:</th><td><span class="badge bg-navy">' + (p.payment_mode || '').toUpperCase() + '</span> (' + (p.payment_type || 'online') + ')</td></tr>' +
                    '<tr><th>Transaction ID:</th><td><code>' + p.transaction_id + '</code></td></tr>' +
                    (p.order_id ? '<tr><th>Order ID:</th><td><code>' + p.order_id + '</code></td></tr>' : '') +
                    '<tr><th>Payment Date & Time:</th><td>' + p.date + '</td></tr>' +
                    '<tr><th>System Note:</th><td>' + (p.note || 'None') + '</td></tr>' +
                    '<tr><th>Current Status:</th><td>' + (p.paid_status == 1 ? '<span class="label label-success">Paid & Submitted</span>' : '<span class="label label-warning">Unsynced (Payment Recorded)</span>') + '</td></tr>' +
                    '</table>';

                if (p.paid_status != 1) {
                    html += '<div class="text-center" style="margin-top:15px;">' +
                        '<button type="button" class="btn btn-warning" onclick="syncAdmissionPayment(' + p.admission_id + ')"><i class="fa fa-refresh"></i> Sync Status Now</button>' +
                        '</div>';
                }

                $('#admission-modal-body').html(html);
            } else {
                $('#admission-modal-body').html('<div class="alert alert-danger">Payment record not found.</div>');
            }
        },
        error: function () {
            $('#admission-modal-body').html('<div class="alert alert-danger">Error loading record.</div>');
        }
    });
}

/**
 * View Atom transaction details modal
 */
function viewAtomDetails(trans_id) {
    $('#atom-modal-body').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-2x"></i> Loading...</div>');
    $('#atomDetailModal').modal('show');

    $.ajax({
        url: '<?php echo site_url("admin/paymenttransactions/get_atom_detail"); ?>',
        type: 'POST',
        dataType: 'json',
        data: { trans_id: trans_id },
        success: function (res) {
            if (res.status === 'success' && res.data) {
                var t = res.data;
                var html = '<table class="table table-bordered table-striped">' +
                    '<tr><th style="width:30%">Transaction ID:</th><td><strong>#' + t.trans_id + '</strong></td></tr>' +
                    '<tr><th>Date & Time:</th><td>' + (t.date || 'N/A') + '</td></tr>' +
                    '<tr><th>Student / Customer:</th><td>' + (t.st_firstname ? (t.st_firstname + ' ' + t.st_lastname) : (t.udf1 || 'N/A')) + ' (Student ID: ' + (t.student_id || 'N/A') + ')</td></tr>' +
                    '<tr><th>Mobile:</th><td>' + (t.st_mobileno || t.udf3 || 'N/A') + '</td></tr>' +
                    '<tr><th>Amount:</th><td><span class="label label-primary" style="font-size:14px;">₹' + parseFloat(t.amt).toFixed(2) + '</span></td></tr>' +
                    '<tr><th>Response Code (f_code):</th><td><code>' + (t.f_code || 'N/A') + '</code></td></tr>' +
                    '<tr><th>Response Status:</th><td><span class="label label-info">' + (t.response_status || (t.amt > 0 ? 'SUCCESS' : 'FAILED')) + '</span></td></tr>' +
                    '<tr><th>Response Message:</th><td>' + (t.response_message || t.discriminator || 'N/A') + '</td></tr>' +
                    '<tr><th>Atom Txn ID:</th><td><code>' + (t.mmp_txn || 'N/A') + '</code></td></tr>' +
                    '<tr><th>Merchant Txn ID:</th><td><code>' + (t.mer_txn || 'N/A') + '</code></td></tr>' +
                    '<tr><th>Bank Txn ID & Bank:</th><td>' + (t.bank_txn || 'N/A') + ' (' + (t.bank_name || 'N/A') + ')</td></tr>' +
                    '<tr><th>Client Code & Merch ID:</th><td>' + (t.clientcode || 'N/A') + ' / ' + (t.merchant_id || 'N/A') + '</td></tr>' +
                    '<tr><th>Fee Master IDs (udf2):</th><td>' + (t.udf2 || 'N/A') + '</td></tr>' +
                    '</table>';

                if (t.raw_response) {
                    try {
                        var parsed = JSON.parse(t.raw_response);
                        html += '<h4>Decrypted Raw Gateway Payload</h4>' +
                            '<pre style="max-height:250px; overflow-y:auto; background:#222; color:#0f0; font-size:12px;">' +
                            JSON.stringify(parsed, null, 2) +
                            '</pre>';
                    } catch (e) {
                        html += '<h4>Raw Gateway Payload</h4><pre style="max-height:200px; overflow-y:auto;">' + t.raw_response + '</pre>';
                    }
                }

                $('#atom-modal-body').html(html);
            } else {
                $('#atom-modal-body').html('<div class="alert alert-danger">Record not found.</div>');
            }
        },
        error: function () {
            $('#atom-modal-body').html('<div class="alert alert-danger">Error loading record.</div>');
        }
    });
}
</script>
