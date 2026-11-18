<?php if (!empty($daycare_enrolled_student)) : ?>
 <div>
     <img src="<?php echo base_url() . $daycare_enrolled_student['image']; ?>" class="profile-user-img" alt="Student Image">
 </div>
 <h4><strong>Student: </strong><?php echo $daycare_enrolled_student['firstname'] . " " . $daycare_enrolled_student['lastname']; ?></h4>
 <h5><strong>Class:</strong> <?php echo $student['class']; ?> - <strong>Section:</strong> <?php echo $student['section']; ?></h5>
 <br>
 <h5><strong>Check-in: </strong><?php echo date("Y-m-d H:i:s", strtotime($daycare_enrolled_student['check_in_time'])); ?></h5>
 <h5><strong>Check-out: </strong><?php echo !empty($daycare_enrolled_student['check_out_time']) ? date("Y-m-d H:i:s", strtotime($daycare_enrolled_student['check_out_time'])) : "Not Checked Out"; ?></h5>
 <div class="guardian-info">
     <h5><strong>Guardian Details:</strong></h5>
     <?php if ($daycare_enrolled_student['guardian_name']) : ?>
         <div class="guardian">
             <img src="<?php echo base_url() . $daycare_enrolled_student['guardian_pic']; ?>" alt="No Photo" class="guardian-photo">
             <div>
                 <p><strong>Registered Guardian:</strong> <?php echo $daycare_enrolled_student['guardian_name']; ?></p>
                 <p><strong>Pickup Person:</strong> <?php echo $daycare_enrolled_student['pick_up_guardian_name']; ?></p>
                 <p><strong>Status:</strong>
                     <?php if ($daycare_enrolled_student['is_verified'] == 'yes') : ?>
                         <span class="text-success">Verified</span>
                     <?php elseif ($daycare_enrolled_student['is_verified'] == 'no') : ?>
                         <span class="text-danger">Denied</span>
                     <?php elseif ($daycare_enrolled_student['is_verified'] == null && $daycare_enrolled_student['pick_up_guardian_name'] == null) : ?>
                         <span class="text-danger">Not Uploaded</span>
                     <?php elseif ($daycare_enrolled_student['is_verified'] == null && $daycare_enrolled_student['pick_up_guardian_name'] != null) : ?>
                         <span class="text-primary">Uploaded</span>
                     <?php endif; ?>
                 </p>
             </div>
         </div>
         <br>
         <?php if ($daycare_enrolled_student['is_verified'] == null  && $daycare_enrolled_student['pick_up_guardian_name'] != "" && $daycare_enrolled_student['guardian_pic'] != "") : ?>
             <div class="button-container">
                 <button class="btn btn-success grant-button" data-id="<?php echo $student['id']; ?>">Grant</button>
                 <button class="btn btn-danger deny-button" data-id="<?php echo $student['id']; ?>">Deny</button>
             </div>
         <?php endif; ?>
     <?php else : ?>
         <p>No guardian information available.</p>
     <?php endif; ?>
 </div>
 <?php else : ?>
     <p>No daycare details found for this student.</p>
 <?php endif; ?>

<!--==========================Grant modal========================= -->
<div class="modal fade" id="permissionModal" role="dialog" aria-labelledby="permissionModalLabel">
    <div class="modal-dialog modal-sm400">
        <div class="modal-content">
            <div class="modal-header">

                <h5 class="modal-title" id="permissionModalLabel">Grant Permission</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>
            <div class="modal-body pb0">
                Are you sure to grant permission to this guardian?
            </div>

            <div class="modal-footer" style="clear:both">
                <button type="button" class="btn btn-primary" id="confirmGrantButton">Grant</button>
            </div>

        </div>
    </div>
</div>

<!--==========================Deny Table========================= -->
<div class="modal fade" id="denyModal" role="dialog" aria-labelledby="denyModalLabel">
    <div class="modal-dialog modal-sm400">
        <div class="modal-content">
            <div class="modal-header">

                <h5 class="modal-title" id="denyModalLabel">Deny</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>

            <div class="modal-body pb0">
                Are you sure to deny permission to this guardian?
            </div>

            <div class="modal-footer" style="clear:both">
                <button type="button" class="btn btn-primary" id="confirmDenyButton">Deny</button>
            </div>

        </div>
    </div>
</div>

<script>
$(document).ready(function() {
                       
    $('.grant-button').click(function() {
        var studentId = $(this).data('id');
        $('#permissionModal').data('student-id', studentId).modal('show');
    });
    $('.deny-button').click(function() {
        var studentId = $(this).data('id');
        $('#denyModal').data('student-id', studentId).modal('show');
    });
    $('#confirmGrantButton').click(function() {
        var studentId = $('#permissionModal').data('student-id');
        $.ajax({
            url: '<?php echo site_url('user/user/grant_permission'); ?>',
            type: 'POST',
            data: {
                student_id: studentId
            },
            dataType: 'JSON',
            success: function(response) {
                console.log(response);
                if (response.status == "success") {
                    $('#permissionModal').modal('hide');
                    successMsg(response.message);
                    location.reload();
                } else {
                    alert('Error occurred while granting permission.');
                }
            },
            error: function() {
                alert('Something went wrong. Please try again.');
            }
        });
    });
    $('#confirmDenyButton').click(function() {
        var studentId = $('#denyModal').data('student-id');
        $.ajax({
            url: '<?php echo site_url('user/user/deny_permission'); ?>',
            type: 'POST',
            data: {
                student_id: studentId
            },
            dataType: 'JSON',
            success: function(response) {
                if (response.status == "success") {
                    $('#denyModal').modal('hide');
                    successMsg(response.message);
                    location.reload();
                } else {
                    alert('Error occurred while denying permission.');
                }
            },
            error: function() {
                alert('Something went wrong. Please try again.');
            }
        });
    });
    });
</script>