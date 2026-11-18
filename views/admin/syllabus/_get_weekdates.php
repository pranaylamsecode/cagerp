<style type="text/css">
    .attachment-block .text-green {
        color: #3498db !important
    }
</style>

<?php
$my_role = $this->customlib->getStaffRole();
$role = json_decode($my_role);
if ($role->id == 7) {
    $staff_id = $_POST['staff_id'];
} else {
    $staff_id = $staff_id;
}
?>
<div class="box-header text-center">
    <i class="fa fa-angle-left datearrow" onclick="get_weekdates('pre_week', '<?php echo $prev_week_start; ?>', '<?php echo $staff_id; ?>')"></i>
    <h3 class="box-title bmedium"> <?php echo $this_week_start . " " . $this->lang->line('to') . " " . $this_week_end; ?></h3> <i class="fa fa-angle-right datearrow" onclick="get_weekdates('next_week', '<?php echo $next_week_start; ?>', '<?php echo $staff_id; ?>')"></i>
    <input type="hidden" id="this_week_start" value="<?php echo $this_week_start; ?>">

</div>
<div class="table-responsive">
    <?php if (!empty($timetable)) {
    ?>
        <table class="table table-stripped">
            <thead>
                <tr>
                    <?php
                    $day_counter = 0;
                    foreach ($timetable as $tm_key => $tm_value) {

                        $new_date1 = $this->customlib->dateFormatToYYYYMMDD($this_week_start);
                        $next_date = date('Y-m-d', strtotime($new_date1 . ' +' . $day_counter . ' day'));

                        $holiday_or_leave1 = $this->syllabus_model->getHolidayOrLeaveByStaffAndDate($staff_id, $next_date);
                                               
                        $day_counter++;
                    ?>
                        <!-- <th class="text text-center"><?php echo $this->lang->line(strtolower($tm_key)); ?><br /><span class="bmedium"><?php echo date($this->customlib->getSchoolDateFormat(), strtotime($next_date)); ?></span></th> -->
                        <th class="text text-center"><?php echo $this->lang->line(strtolower($tm_key)); ?><br /><span class="bmedium"><?php echo date($this->customlib->getSchoolDateFormat(), strtotime($next_date)); ?></span>
                        <?php
                                               
                                                // if ($role->id != 2 && empty($holiday_or_leave1)) {
                                                    if (empty($holiday_or_leave1)) { 

                                                ?>
                                                
                                                <br>
                                                                                 
                                <select class="form-control select-option" id="status" data-date="<?php echo $next_date; ?>" data-staffid="<?php echo $staff_id; ?>" >
                                    <option value="">Select Option</option>
                                    <option <?php if($holiday_or_leave1->status == "holiday" ){ echo "selected=selected";} ?> value="holiday"><?php echo $this->lang->line('holiday'); ?></option>
                                    <option <?php if($holiday_or_leave1->status == "leave" ){ echo "selected=selected";} ?> value="leave"><?php echo $this->lang->line('leave'); ?></option>
                                </select>
                                                        
                            <?php }
                    ?>
                        
                        </th>

                    <?php }
                    ?>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <?php
                    $day_counter = 0;
                    foreach ($timetable as $tm_key => $tm_value) {

                        $new_date1 = $this->customlib->dateFormatToYYYYMMDD($this_week_start);
                        $new_date = date('Y-m-d', strtotime($new_date1 . ' +' . $day_counter . ' day'));

                        $holiday_or_leave = $this->syllabus_model->getHolidayOrLeaveByStaffAndDate($staff_id, $new_date);
                       
                        $day_counter++;
                    ?>
                        <td class="text text-center" width="14%">

                        <?php if ($holiday_or_leave) {  ?>

                            <?php if ($role->id != 2) { ?>

                                <a data-placement="left" class="btn btn-default btn-xs pull-left" data-toggle="tooltip" title="" data-original-title="<?php echo $this->lang->line('delete') ?>" onclick="staff_holiday_remove('<?php echo $holiday_or_leave->status; ?>','<?php echo $new_date; ?>','<?php echo $staff_id; ?>')"><i class="fa fa-remove"></i></a>

                            <?php
                                } if($holiday_or_leave->status == "holiday"){
                            ?>
                                                   
                                                      
                                                                      
                                <div class="attachment-block bg-danger clearfix">
                                    <b class="text text-center"><?php echo ucfirst($holiday_or_leave->status); ?></b><br>
                                    
                                </div>

                                <?php } if($holiday_or_leave->status == "leave") { ?>

                                    <div class="attachment-block bg-green clearfix">
                                    <b class="text text-center"><?php echo ucfirst($holiday_or_leave->status); ?></b><br>
                                    
                                </div>

                                <?php } ?>
                                                
                            <?php } else{ 
                                if (!$timetable[$tm_key]) { ?>
                                <div class="attachment-block clearfix">
                                    <b class="text text-center"><?php echo $this->lang->line('not'); ?> <br><?php echo $this->lang->line('scheduled'); ?></b><br>
                                </div>
                                <?php
                            } else {
                                foreach ($timetable[$tm_key] as $tm_k => $tm_kue) {

                                    $subject_group_subject_class_section = $this->lessonplan_model->getsubject_group_class_sectionsId($tm_kue->class_id, $tm_kue->section_id, $tm_kue->subject_group_id);

                                    $subject_syllabus = $this->syllabus_model->get_subject_syllabusdata($tm_kue->subject_group_subject_id, date('Y-m-d', strtotime($new_date)), $role->id, $staff_id, $tm_kue->time_from, $tm_kue->time_to, $subject_group_subject_class_section['id']);

                                    $subject_syllabus_without_topic = $this->syllabus_model->get_subject_syllabusdata_without_topic(date('Y-m-d', strtotime($new_date)),$staff_id ,$tm_kue->time_from, $tm_kue->time_to);

                                    if ($subject_syllabus[0]['total'] > 0) {
                                        $action = $subject_syllabus[0]['id'];
                                        $cond="1";
                                    }
                                    elseif($subject_syllabus_without_topic[0]['total'] > 0){
                                        $action = $subject_syllabus_without_topic[0]['id'];
                                        $cond="2";
                                    } 
                                    else {
                                        $action = 0;
                                    }
                                    if ($action != 0) {
                                         if($cond == "1"){
                                            
                                       
                                ?>
                                        <div id="hide_<?php echo $action; ?>">
                                            <a data-placement="left" class="btn btn-default btn-xs pull-left" data-toggle="tooltip" title="" data-original-title="<?php echo $this->lang->line('view') ?>" onclick="get_subject_syllabus(<?php echo $action; ?>)"><i class="fa fa-reorder"></i></a>

                                            <?php if ($this->rbac->hasPrivilege('manage_lesson_plan', 'can_edit')) { ?>
                                                <a data-placement="left" class="btn btn-default btn-xs pull-left" data-toggle="tooltip" title="" data-original-title="<?php echo $this->lang->line('edit') ?>" onclick="subject_syllabusedit(<?php echo $action; ?>)"><i class="fa fa-pencil"></i></a>
                                                <a data-placement="left" class="btn btn-default btn-xs pull-left" data-toggle="tooltip" title="" data-original-title="<?php echo $this->lang->line('delete') ?>" onclick="subject_syllabusdelete('<?php echo $action; ?>', '<?php echo $tm_kue->subject_group_subject_id; ?>', '<?php echo $tm_kue->time_from ?>', '<?php echo $tm_kue->time_to; ?>', '<?php echo $this->customlib->dateformat($new_date); ?>', '<?php echo $subject_group_subject_class_section['id']; ?>')"><i class="fa fa-remove"></i></a>
                                            <?php
                                            }
                                            ?>
                                        </div>

                                        <?php } elseif($cond == "2"){?>

                                            <div id="hide_<?php echo $action; ?>">
                                            <a data-placement="left" class="btn btn-default btn-xs pull-left" data-toggle="tooltip" title="" data-original-title="<?php echo $this->lang->line('view') ?>" onclick="get_subject_syllabus_without_topic(<?php echo $action; ?>)"><i class="fa fa-reorder"></i></a>

                                            <?php if ($this->rbac->hasPrivilege('manage_lesson_plan', 'can_edit')) { ?>
                                                <a data-placement="left" class="btn btn-default btn-xs pull-left" data-toggle="tooltip" title="" data-original-title="<?php echo $this->lang->line('delete') ?>" onclick="subject_syllabusdelete_without_topic('<?php echo $action; ?>', '<?php echo $tm_kue->subject_group_subject_id; ?>', '<?php echo $tm_kue->time_from ?>', '<?php echo $tm_kue->time_to; ?>', '<?php echo $this->customlib->dateformat($new_date); ?>', '<?php echo $subject_group_subject_class_section['id']; ?>')"><i class="fa fa-remove"></i></a>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                    <?php
                                         
                                    } } else {
                                    ?>
                                        <?php if ($this->rbac->hasPrivilege('manage_lesson_plan', 'can_add')) { ?>
                                            <a data-placement="left" class="btn btn-default btn-xs pull-left" data-toggle="tooltip" title="" data-original-title="<?php echo $this->lang->line('add') ?>" onclick="add_syllabus('<?php echo $tm_kue->subject_group_subject_id; ?>', '<?php echo $tm_kue->time_from ?>', '<?php echo $tm_kue->time_to; ?>', '<?php echo date($this->customlib->getSchoolDateFormat(), strtotime($new_date)); ?>', '<?php echo $tm_kue->class_id; ?>', '<?php echo $tm_kue->section_id; ?>','<?php echo $subject_group_subject_class_section['id']; ?>', '<?php echo $staff_id; ?>',false)"><i class="fa fa-plus"></i></a>
                                            <a data-placement="left" class="btn btn-default btn-xs pull-left" data-toggle="tooltip" title="" data-original-title="Remark" onclick="add_syllabus('<?php echo $tm_kue->subject_group_subject_id; ?>', '<?php echo $tm_kue->time_from ?>', '<?php echo $tm_kue->time_to; ?>', '<?php echo date($this->customlib->getSchoolDateFormat(), strtotime($new_date)); ?>','<?php echo $tm_kue->class_id; ?>', '<?php echo $tm_kue->section_id; ?>','<?php echo $subject_group_subject_class_section['id']; ?>', '<?php echo $staff_id; ?>',true)"><i class="fa fa-external-link"></i></a>

                                    
                                    <?php
                                        }
                                    }
                                    ?>
                                    <div class="attachment-block clearfix">
                                        <b class="text-green"><?php echo $this->lang->line('subject') ?>: <?php
                                                                                                            echo $tm_kue->subject_name;
                                                                                                            if ($tm_kue->subject_code != '') {
                                                                                                                echo " (" . $tm_kue->subject_code . ")";
                                                                                                            }
                                                                                                            ?></b><br>
                                        <strong class="text-green"><?php echo $this->lang->line('class') ?>: <?php echo $tm_kue->class . "(" . $tm_kue->section . ")"; ?></strong><br>
                                        <strong class="text-green"><?php echo $tm_kue->time_from ?></strong>
                                        <b class="text text-center">-</b>
                                        <strong class="text-green"><?php echo $tm_kue->time_to; ?></strong><br>
                                        <strong class="text-green">
                                            <?php echo "Period No"; ?>
                                            : <?php echo $tm_kue->room_no; ?></strong><br>
                                    </div>
                            <?php
                                }
                            }
                        }
                            
                            ?>
                        </td>
                    <?php
                    }
                    ?>
                </tr>
            </tbody>
        </table>
    <?php
    } else {
    ?>
        <div class="alert alert-info">
            <?php echo $this->lang->line('no_record_found'); ?>
        </div>
    <?php
    }
    ?>
</div>

<script>
    $('#created_for').val('<?php echo $staff_id; ?>');
          
    function subject_syllabusdelete(syllabus_id) {
        if (confirm('<?PHP echo $this->lang->line('delete_confirm') ?>')) {
            $.ajax({
                type: "POST",
                url: base_url + "admin/syllabus/delete_subject_syllabus",
                data: {
                    'id': syllabus_id
                },
                success: function(data) {
                    successMsg('<?php echo $this->lang->line("delete_message"); ?>');
                    $('#hide_' + syllabus_id).html('');
                    get_weekdates('pre_week', '<?php echo $this_week_start; ?>', '<?php echo $staff_id; ?>');
                },
            });

        }

    }

    function subject_syllabusdelete_without_topic(id){

        if (confirm('<?PHP echo $this->lang->line('delete_confirm') ?>')) {
            $.ajax({
                type: "POST",
                url: base_url + "admin/syllabus/delete_subject_syllabus_without_topic",
                data: {
                    'id': id
                },
                success: function(data) {
                    successMsg('<?php echo $this->lang->line("delete_message"); ?>');
                    $('#hide_' + id).html('');
                    get_weekdates('pre_week', '<?php echo $this_week_start; ?>', '<?php echo $staff_id; ?>');
                },
            });

        }

    }

    function staff_holiday_remove(status, date, staff_id) {
    if (confirm('<?PHP echo $this->lang->line('delete_confirm') ?>')) {
        $.ajax({
            type: "POST",
            url: base_url + "admin/syllabus/remove_staff_holiday_or_leave",
            data: {
                'status': status,
                'date': date,    
                'staff_id': staff_id
            },
            success: function(data) {
                
                successMsg('<?php echo $this->lang->line("delete_message"); ?>');
                get_weekdates('pre_week', '<?php echo $this_week_start; ?>', '<?php echo $staff_id; ?>');
            },
        });
    }
}

</script>