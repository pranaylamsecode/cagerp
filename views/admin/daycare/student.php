<style type="text/css">
    @media print {

        .no-print,
        .no-print * {
            display: none !important;
        }
    }

    .table-responsive .mailbox-name {
        text-align: left;
    }

    .table-responsive .mailbox-name img {
        display: block;
        margin: 0 auto;
        width: 40px;
        height: 40px;
        border-radius: 50%;
    }

    .table-responsive .mailbox-name span {
        display: block;
        margin-top: 5px;
        font-size: 14px;
        font-weight: bold;
    }

    .highlight-name {
        color: rgb(9, 8, 7);
        font-weight: bold;
        font-size: 16px;
        background-color: rgb(248, 249, 249);
        padding: 2px 5px;
        border-radius: 3px;

    }
 

</style>
<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-object-group"></i> Daycare
        </h1>
    </section>
    <section class="content">
        <div class="row">

            <div class="col-md-12">
                <div class="box box-primary" id="tachelist">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix">Day Care Student</h3>
                        <div class="box-tools pull-right">

                            <?php if ($this->rbac->hasPrivilege('daycare_student', 'can_add')) {
                            ?>
                                <a href="<?php echo site_url('admin/daycare') ?>" type="button" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add Student</a>
                            <?php }
                            ?>

                        </div>
                    </div>
                    <div class="box-body">
                        <div class="mailbox-controls">
                        </div>
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label">Students</div>
                            <table class="table table-striped table-bordered table-hover example" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <!-- <th>Daycare Student Id</th> -->
                                        <th>Enrollment No</th>
                                        <th><?php echo $this->lang->line('admission_no'); ?></th>
                                        <!-- <th>Image</th> -->
                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <th><?php echo $this->lang->line('member_type'); ?></th>
                                        <th> Guardian Name </th>
                                        <th><?php echo $this->lang->line('phone'); ?></th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($memberList)) {
                                        $count = 1;

                                        foreach ($memberList as $member) {

                                            if ($member['student_type'] == "student") {
                                                $name = $member['firstname'] . " " . $member['lastname'];
                                                $phone = $member['guardian_phone'];
                                            } else {
                                                $email = $member['teacher_email'];
                                                $name = $member['teacher_name'];
                                                $sex = $member['teacher_sex'];
                                                $phone = $member['teacher_phone'];
                                            }
                                    ?>
                                            <tr>
                                                <!-- <td>
                                                    <?php echo $member['daycare_student_id']; ?>
                                                </td> -->
                                                <td class="mailbox-name">
                                                    <?php echo $member['enrollment_no']; ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo $member['admission_no']; ?>
                                                </td>

                                                <td class="mailbox-name">
                                                    <img src="<?php echo base_url() . $member['image'] ?>" alt="User profile picture">
                                                    <span style="text-align: center;"><?php echo $name . ' (' . $member['gender'] . ')'; ?></span>


                                                </td>
                                                <!-- <td class="mailbox-name">
                                                    <?php echo $name . ' (' . $member['gender'] . ')'; ?>
                                                </td> -->
                                                <td class="mailbox-name">
                                                    <?php echo $this->lang->line($member['student_type']); ?>
                                                </td>

                                                <td class="mailbox-name">
                                                    <?php echo $member['guardian_name']; ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo $phone; ?>
                                                </td>

                                                <!-- <td class="mailbox-name">
                                                    <?php if ($member['status'] === 'Checked In'): ?>
                                                        <button class="btn btn-success" data-id="<?php echo $member['id']; ?>" onclick="checkGuardianVerification(<?php echo $member['id']; ?>)">Check-Out</button>
                                                    <?php else: ?>
                                                        <button class="btn btn-primary" data-id="<?php echo $member['id']; ?>" data-name="<?php echo $name; ?>" onclick="openCheckInModal(<?php echo $member['id']; ?>, '<?php echo $name; ?>')">Check-In</button>
                                                    <?php endif; ?>
                                                    
                                                    <button class="btn btn-sm btn-info view-daycare-activities" data-student-id="<?= $member['id']; ?>">
                                                        View Activities
                                                    </button>

                                                </td> -->

                                                <td class="mailbox-name">
                                                    
                                                    <div class="btn-group-vertical" style="gap: 5px; display: flex;">
                                                        <?php if ($member['status'] === 'Checked In'): ?>
                                                            <button class="btn btn-outline-success btn-sm" 
                                                                    data-id="<?php echo $member['id']; ?>" 
                                                                    onclick="checkGuardianVerification(<?php echo $member['id']; ?>)">
                                                                <i class="fa fa-sign-out"></i> Check-Out
                                                            </button>
                                                        <?php else: ?>
                                                            <button class="btn btn-outline-primary btn-sm" 
                                                                    data-id="<?php echo $member['id']; ?>" 
                                                                    data-name="<?php echo $name; ?>" 
                                                                    onclick="openCheckInModal(<?php echo $member['id']; ?>, '<?php echo $name; ?>')">
                                                                <i class="fa fa-sign-in"></i> Check-In
                                                            </button>
                                                        <?php endif; ?>

                                                        <button class="btn btn-outline-info btn-sm view-daycare-activities" 
                                                                data-student-id="<?= $member['id']; ?>">
                                                            <i class="fa fa-calendar"></i> View Activities
                                                        </button>
                                                    </div>
                                                    
                                                </td>


                                            </tr>
                                    <?php
                                        }
                                        $count++;
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


<!-- Check-In Modal -->
<div class="modal fade" id="checkInModal" tabindex="-1" aria-labelledby="checkInModalLabel" aria-hidden="true" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title title modal_title" id="checkInModalLabel">Confirm Check-In</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="student_name">
                <p></p>

            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button> -->
                <button type="button" class="btn btn-primary" id="confirmCheckInBtn">Yes</button>
            </div>
        </div>
    </div>
</div>


<!-- Check-Out Modal -->
<div class="modal fade" id="checkOutModal" tabindex="-1" aria-labelledby="checkOutModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="checkOutForm" action="" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title title modal_title" id="checkOutModalLabel">Check-Out Student</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="student_id" id="modalStudentId">
                    <p></p>
                    <div class="mb-3">
                        <label for="exampleInputFile">Upload Guardian Photo</label><small class="req" style="color:red;"> *</small>
                        <div><input class="filestyle form-control" type='file' name="guardian_pic" id="guardian_pic" size='20' required></div>
                    </div>
                    <div class="mb-3">
                        <label for="guardian_name" class="form-label">Guardian Name </label>
                        <input type="text" class="form-control" name="guardian_name" id="guardian_name">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="saveBtn" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal After Verification -->
<div class="modal fade" id="modalAfterVerification" tabindex="-1" aria-labelledby="modalAfterVerificationLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="finalCheckOutForm" action="" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title title modal_title" id="modalAfterVerificationLabel">Check-Out Student</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="student_id" id="finalCheckOutStudentId">

                    <div id="guardianVerifiedSection" style="display:none;">
                        <img id="guardianPic" src="" alt="Guardian" style="width: 50px; height: 50px; border-radius: 50%; margin-right: 10px;">
                        <span style="color: green; font-size: 20px;">
                            <i class="fa fa-check-circle"></i> Verified
                        </span>
                    </div>

                    <p><strong>Verification Status: </strong> <span id="verificationStatus"></span></p>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- Daycare Activity Modal -->
<div class="modal fade" id="superadminActivityModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <!-- <h4>Daycare Activities - <span id="activity-date-display"></span></h4> -->
                     <h4>Student Activity</h4>

                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="form-inline mb-2" style="gap: 10px;">
                    <input type="date" id="activity-date-picker" class="form-control input-sm" style="width: 180px;" />
                    <button id="refresh-day" class="btn btn-sm btn-default" title="Refresh"><i class="fa fa-refresh"></i></button>
                    <div class="text-right mb-2">
                        <button class="btn btn-sm btn-default" onclick="printActivityReport()">
                            <i class="fa fa-print"></i> Print
                        </button>
                        <button class="btn btn-sm btn-primary" onclick="exportActivityToPDF()">
                            <i class="fa fa-file-pdf-o"></i> Export to PDF
                        </button>
                    </div>
                                
                </div>
                
                <br>
                <div id="superadmin-activity-container" style="min-height: 100px;">
                    <p>Select a date to load activities.</p>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>

<script>
    // $('#checkOutModal').on('shown.bs.modal', function() {
    //     $('#guardian_pic').val("");
    //     $('#guardian_name').val("");
    // });

    function openCheckInModal(studentId, studentName) {
        $('#checkInModal').find('.modal-body p').html("Are you sure you want to check-in <span class='highlight-name'>" + studentName + "</span>?");
        $('#checkInModal').modal('show');
        $('#confirmCheckInBtn').data('student-id', studentId);
    }

    function checkGuardianVerification(studentId) {
        // $('#guardian_pic').attr('src', '');
        $('#guardian_pic').val("");
        $('#guardian_name').val("");

        $.ajax({
            url: '<?php echo site_url('admin/daycare/check_verification_status'); ?>',
            type: 'POST',
            data: {
                student_id: studentId
            },
            dataType: 'json',
            success: function(response) {
                if (response.is_verified != null) {
                    if (response.is_verified === 'yes') {

                        $('#guardianVerifiedSection').show();
                        $('#finalCheckOutStudentId').val(studentId);

                        $('#guardianPic').attr('src', '<?php echo base_url(); ?>' + response.guardian_pic);
                        $('#verificationStatus').text("Verified");

                        $('#modalAfterVerification').modal('show');

                    } else {

                        $('#checkOutModal .modal-body p').empty();
                        $('#checkOutModal .modal-body').append('<p style="color:red; font-weight:bold;">Verification Unsuccessful!</p>');
                        $('#modalStudentId').val(studentId);
                        $('#checkOutModal').modal('show');
                    }
                } else {
                    if (response.is_verified === null && response.guardian_pic === null) {
                        $('#modalStudentId').val(studentId);
                        $('#checkOutModal').modal('show');
                    } else {
                        alert("Please Wait For Parent's Verification");
                    }
                }
            }
        });
    }

    $('#confirmCheckInBtn').on('click', function() {
        var studentId = $(this).data('student-id');

        var status = 'Checked In';

        $.ajax({
            url: '<?php echo site_url('admin/daycare/check_in'); ?>',
            type: 'POST',
            data: {
                student_id: studentId,
                status: status,
            },
            dataType: "json",
            success: function(data) {
                $('#checkInModal').modal('hide');
                successMsg(data.msg);
                window.location.reload();
            },

        });
    });

    $('#checkOutForm').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            url: '<?php echo site_url('admin/daycare/check_out'); ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,

            dataType: "json",
            success: function(data) {
                $('#checkOutModal').modal('hide');
                successMsg(data.msg);
                window.location.reload();
            },

        });
    });

    $('#finalCheckOutForm').on('submit', function(e) {

        var student_id = $('#finalCheckOutStudentId').val();
        console.log(student_id);

        $.ajax({
            url: '<?php echo site_url('admin/daycare/final_check_out'); ?>',
            type: 'POST',
            data: {
                student_id: student_id
            },
            dataType: "json",
            success: function(data) {
                $('#modalAfterVerification').modal('hide');
                successMsg(data.msg);
                window.location.reload();
            },

        });

    });


    let currentDate = new Date();
    let selectedStudentId = null;

    function formatDateDisplay(date) {
        return date.toLocaleDateString('en-GB', {
            weekday: 'long',
            day: '2-digit',
            month: 'short',
            year: 'numeric'
        });
    }

    function formatDateParam(date) {
        return date.toISOString().split('T')[0];
    }

    function isToday(date) {
        const today = new Date();
        return (
            date.getFullYear() === today.getFullYear() &&
            date.getMonth() === today.getMonth() &&
            date.getDate() === today.getDate()
        );
    }

    function loadActivities(date) {
        let displayDate = formatDateDisplay(date);
        let paramDate = formatDateParam(date);

        $('#activity-date-display').text(displayDate);
        $('#activity-date-picker').val(paramDate);
        $('#refresh-day').prop('disabled', true);
        $('#refresh-day i').addClass('fa-spin');

        $.ajax({
            url: "<?= site_url('admin/daycare/get_daycare_activities_by_date') ?>",
            type: "POST",
            data: {
                student_id: selectedStudentId,
                date: paramDate
            },
            success: function (response) {
                $('#superadmin-activity-container').html(response);
            },
            error: function () {
                $('#superadmin-activity-container').html('<div class="alert alert-danger">Error loading activities.</div>');
            },
            complete: function () {
                $('#refresh-day i').removeClass('fa-spin');
                $('#refresh-day, #prev-day').prop('disabled', false);
                if (!isToday(date)) $('#next-day').prop('disabled', false);
            }
        });
    }

    $('.view-daycare-activities').on('click', function () {
        selectedStudentId = $(this).data('student-id');
        currentDate = new Date();
        loadActivities(currentDate,selectedStudentId);
        $('#superadminActivityModal').modal('show');
    });

    $('#activity-date-picker').on('change', function () {
        currentDate = new Date(this.value);
        loadActivities(currentDate);
    });
 
    $('#refresh-day').click(function () {
        loadActivities(currentDate);
    });

    function printActivityReport() {
    const printContents = document.getElementById('activity-report-content').innerHTML;
    const originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
    location.reload(); 
    }

    function exportActivityToPDF() {
        const element = document.getElementById('activity-report-content');

        const opt = {
            margin:       0.5,
            filename:     'daycare_activity_report.pdf',
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2 },
            jsPDF:        { unit: 'in', format: 'a4', orientation: 'portrait' }
        };

        html2pdf().from(element).set(opt).save();
    }
</script>