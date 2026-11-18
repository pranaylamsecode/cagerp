<div class="content-wrapper">
    <section class="content-header">
        <h1><?php echo $title; ?></h1>
    </section>

    <section class="content">
        <?php if (!empty($grouped_activities)): ?>
            <?php foreach ($grouped_activities as $date => $activities): ?>
                <div class="box box-info">
                    <div class="box-header with-border">
                        <?php
                            $activityDate = new DateTime($date);
                            $today = new DateTime();
                            $yesterday = (new DateTime())->modify('-1 day');

                            if ($activityDate->format('Y-m-d') == $today->format('Y-m-d')) {
                                $displayDate = $date . " ( Today )";
                            } elseif ($activityDate->format('Y-m-d') == $yesterday->format('Y-m-d')) {
                                $displayDate =  $date . " (Yesterday)";
                            } else {
                                $displayDate = $activityDate->format('d M Y');
                            }
                        ?>
                        <h3 class="box-title">Date: <?php echo $displayDate; ?></h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-striped table-bordered table-hover example">
                            <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Time</th>
                                        <th>Service</th>
                                        <th>Assigned Staff</th>
                                        <th>Meal</th>
                                        <th>Description</th>
                                        <th>Quantity</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($activities as $index => $act): ?>
                                        <tr>
                                            <td><?php echo $index + 1; ?></td>
                                            <td><?php echo $act->time; ?></td>
                                            <td><?php echo $act->service_name; ?></td>
                                            <td><?php echo $act->staff_name. " ".$act->staff_surname; ?></td>
                                            <td><?php echo $act->meal; ?></td>
                                            <td><?php echo $act->description; ?></td>
                                            <td><?php echo $act->quantity; ?></td>
                                            <?php $selected_children = explode(',', $act->selected_children_ids); ?>

                                            <td><a class="btn btn-default btn-xs edit-activity"
                                                    data-id="<?php echo $act->id; ?>"
                                                    data-date="<?php echo $act->date; ?>"
                                                    data-staff="<?php echo $act->assigned_staff; ?>"
                                                    data-serviceid="<?php echo $act->daycare_service_id;?>"
                                                    data-meal="<?php echo $act->meal; ?>"
                                                    data-description="<?php echo $act->description; ?>"
                                                    data-quantity="<?php echo $act->quantity; ?>"
                                                    data-time="<?php echo $act->time; ?>"
                                                    data-children='<?php echo json_encode($selected_children); ?>'
                                                    data-toggle="modal"
                                                    data-target="#add_activity">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-info">No activities found.</div>
        <?php endif; ?>
    </section>
</div>

<div class="modal fade" id="add_activity"  role="dialog" aria-labelledby="evaluation" style="padding-left: 0 !important">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="box-title" id="title"> </h4>
            </div>
            <div class="modal-body pt0 pb0">
                <div id="activity-message-box" class="alert alert-info" style="display: none;">
                    <strong>Note:</strong> You Can select children ,Date & Time only for today's date.
                </div>
            <form id="activity_form" method="post" class="ptt10" enctype="multipart/form-data" accept-charset="utf-8">
                    <div class="">
                    <?php echo $this->customlib->getCSRF(); ?>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="row">
                                    
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Assigned Staff</label>
                                            <small class="req"> *</small>
                                            <select  id="assigned_staff" name="assigned_staff" class="form-control" >
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                <?php foreach ($resultlist as $staff) {
                                                    ?>
                                                        <option
                                                                value="<?php echo $staff['id'] ?>"><?php echo $staff['name'] . " " . $staff['surname'] ?></option>
                                                    <?php }
                                                    ?>
                                            </select>
                                            <input type="hidden" id="activity_id" name="activity_id" class="form-control" >
                                            <input type="hidden" id="daycare_activity_id" name="daycare_activity_id" class="form-control" >
                                            <span class="section_id_error text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4" id="date_show">
                                        <div class="form-group">
                                        <label for="date">Date</label><small class="req"> *</small>
                                        <input type="date" class="form-control" id="date" name="date" value="<?= date('Y-m-d'); ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4" id="meal-field" style="display:none;">
                                        <div class="form-group">
                                        <label for="meal">Meal Name</label>
                                        <input type="text" class="form-control" id="meal" name="meal" placeholder="Enter meal name">
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-4 col-sm-4" id="description-field" style="display:none;">
                                        <div class="form-group">
                                        <label for="meal">Description</label><small class="req"> *</small>
                                        <input type="text" class="form-control" id="description" name="description" placeholder="Enter Description">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                        <label for="quantity">Quantity (Optional)</label>
                                        <input type="text" class="form-control" id="quantity" name="quantity">  
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4" id="time_show">
                                        <div class="form-group">
                                        <label for="time">Time</label><small class="req"> *</small>
                                        <input type="time" class="form-control" id="time" name="time">
                                        </div>
                                    </div>
                                   
                                    <!-- <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="form-group">
                                        <label>Children</label>
                                            <div class="d-flex">

                                            <?php foreach( $memberList as $data){ ?>
                                                <div class="child-box text-center">
                                                    <img src="<?php echo base_url() . $data['image']; ?>" class="rounded-circle" width="50" height="50">
                                                    <p><?php echo $data['firstname']." ".$data['lastname']?></p>
                                                </div>
                                                <?php }?>
                                                
                                            </div>
                                        </div>
                                    </div> -->


                                    <!-- <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <label>Children</label>
                                            <div class="d-flex flex-wrap">
                                                <?php foreach ($memberList as $data) { ?>
                                                    <div class="child-box text-center mr-4 mb-4">
                                                        <input type="checkbox" name="selected_children[]" value="<?php echo $data['id']; ?>" id="child_<?php echo $data['id']; ?>" class="child-checkbox">
                                                        <label for="child_<?php echo $data['id']; ?>">
                                                            <img src="<?php echo base_url() . $data['image']; ?>" class="rounded-circle" width="50" height="50">
                                                            <p><?php echo $data['firstname'] . " " . $data['lastname']; ?></p>
                                                        </label>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div> -->


                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <label>Children</label>

                                            <!-- This wrapper will be used for selecting (if date is today) -->
                                            <div id="selectable-children-list" class="d-flex flex-wrap">
                                                <?php foreach ($memberList as $data) { ?>
                                                    <div class="child-box text-center mr-4 mb-4">
                                                        <input type="checkbox" name="selected_children[]" value="<?php echo $data['id']; ?>" id="child_<?php echo $data['id']; ?>" class="child-checkbox">
                                                        <label for="child_<?php echo $data['id']; ?>">
                                                            <img src="<?php echo base_url() . $data['image']; ?>" class="rounded-circle" width="50" height="50">
                                                            <p><?php echo $data['firstname'] . " " . $data['lastname']; ?></p>
                                                        </label>
                                                    </div>
                                                <?php } ?>
                                            </div>

                                            <!-- Read-only list for other dates -->
                                            <div id="readonly-children-list" class="alert alert-info" style="display: none;"></div>
                                        </div>
                                    </div>



                                </div><!--./row-->
                            </div><!--./col-md-12-->
                        </div><!--./row-->
                        <div class="box-footer row">
                            <div class="pull-right">
                                <button type="submit" class="btn btn-info pull-right" id="submit" data-loading-text="<i class='fa fa-spinner fa-spin'></i> Please wait"><?php echo $this->lang->line('save') ?></button>
                            </div>
                        </div>
                    </div>

                </form>
               
            </div>
        </div>
    </div>
</div>

<script>
    $(document).on('click', '.edit-activity', function () {
        // Populate basic fields
        $('#daycare_activity_id').val($(this).data('id'));
        $('#activity_id').val($(this).data('serviceid'));
        $('#assigned_staff').val($(this).data('staff'));
        $('#date').val($(this).data('date'));
        $('#meal').val($(this).data('meal'));
        $('#description').val($(this).data('description'));
        $('#quantity').val($(this).data('quantity'));
        $('#time').val($(this).data('time'));

        // Show/hide relevant fields based on values
        if ($(this).data('meal')) {
            $('#meal-field').show();
        } else {
            $('#meal-field').hide();
        }

        if ($(this).data('description')) {
            $('#description-field').show();
        } else {
            $('#description-field').hide();
        }

        // Handle selected children
        // $('.child-checkbox').prop('checked', false);
        // var selectedChildren = $(this).data('children');
        // if (Array.isArray(selectedChildren)) {
        //     selectedChildren.forEach(function (id) {
        //         $('#child_' + id).prop('checked', true);
        //     });
        // }


        var selectedChildren = $(this).data('children');
        var selectedDate = $(this).data('date');
        var today = new Date().toISOString().split('T')[0];

        // Hide both by default
        $('.child-checkbox').closest('.child-box').hide();
        $('#readonly-children-list').hide();

        // Show checkboxes if it's today
        if (selectedDate === today) {
            $('.child-checkbox').prop('checked', false); // Uncheck all
            if (Array.isArray(selectedChildren)) {
                selectedChildren.forEach(function (id) {
                    $('#child_' + id).prop('checked', true);
                });
            }
            $('.child-checkbox').closest('.child-box').show();
            $('#selectable-children-list').show();
            $('#readonly-children-list').hide();
            $('#date_show').show();
            $('#time_show').show();

            $('#activity-message-box')
            .removeClass('alert-info')
            .addClass('alert-warning')
            .html('<strong>Heads up!</strong> You are editing activity for a different date. Children selection is disabled.')
            .show();
        } else {
            // Show read-only list
            let names = [];
            <?php foreach ($memberListall as $data): ?>
                if (selectedChildren.includes("<?php echo $data['id']; ?>")) {
                    names.push("<?php echo $data['firstname'] . ' ' . $data['lastname']; ?>");
                }
            <?php endforeach; ?>

            $('#readonly-children-list').text(names.join(', ')).show();
            $('#selectable-children-list').hide();
            $('#date_show').hide();
            $('#time_show').hide();

            $('#activity-message-box').hide();
        }


            // Set modal title
            $('#title').text('Edit Daycare Activity');
        });


        $("#activity_form").on('submit', (function (e) {
            e.preventDefault();
        
            var $this = $(this).find("button[type=submit]:focus");

            $.ajax({
                url: "<?php echo site_url("admin/daycare/save_activity") ?>",
                type: "POST",
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function () {
                    $this.button('loading');

                },
                success: function (res)
                {

                    if (res.status == "fail") {

                        var message = "";
                        $.each(res.error, function (index, value) {

                            message += value;
                        });
                        errorMsg(message);

                    } else {

                        successMsg(res.message);
                        window.location.reload(true);

                    }
                },
                error: function (xhr) { 
                    alert("Error occured.please try again");
                    $this.button('reset');
                },
                complete: function () {
                    $this.button('reset');
                }

            });
        }));
</script>


