<ul class="timeline timeline-inverse">

    <?php
    if (empty($follow_up_list)) {
    ?>
        <?php
    } else {
        foreach ($follow_up_list as $key => $value) {
        ?>
            <li class="time-label">
                <span class="bg-blue">
                    <?php
                    echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value['date']));
                    ?>
                </span>
            </li>
            <li>
                <i class="fa fa-phone bg-blue"></i>
                <div class="timeline-item">
                    <span class="time">
                        <?php
                        if ($this->rbac->hasPrivilege('parent_enquiry', 'can_delete')) {
                        ?>
                            <a class="defaults-c text-right" data-toggle="tooltip" title="" onclick="delete_next_call(<?php echo $value['id']; ?>,<?php echo $id; ?>)" data-original-title="Delete"><i class="fa fa-trash"></i></a>
                        <?php } ?></span>
                    <h3 class="timeline-header"><a href="#"> <?php echo $value['followup_by']; ?></a> </h3>
                    <div class="timeline-body">
                        <strong>Next Follow Up Date: </strong><?php echo $value['next_date']; ?>
                        <div class="divider"></div>
                        <strong>Responce : </strong><?php echo $value['response']; ?>
                        <div class="divider"></div>
                        <strong>Note : </strong><?php echo $value['note']; ?>



                    </div>
                    <div class="">


                    </div>
                </div>
            </li>
    <?php
        }
    }
    ?>
    <li><i class="fa fa-clock-o bg-gray"></i></li>
</ul>
<script>
    var status = $('#status_data').val();

    function delete_next_call(follow_up_id, enquiry_id) {

        var permission = confirm("Are you sure you want to delete?");
        if (permission == false) {

        } else {

            $.ajax({
                url: '<?php echo base_url(); ?>admin/parentenquiry/follow_up_delete/' + follow_up_id + '/' + enquiry_id,

                success: function(data) {
                    follow_up(enquiry_id);

                },

                error: function() {
                    alert("Fail")
                }
            });
        }
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
</script>