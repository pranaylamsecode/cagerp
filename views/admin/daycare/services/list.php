<style>
    .child-box {
    display: inline-block;
    text-align: center;
    margin-right: 20px;
    margin-bottom: 20px;
    cursor: pointer;
}

.child-checkbox {
    display: none;
}

.child-box label {
    display: block;
    cursor: pointer;
    font-size: 14px;
}

.child-box input[type="checkbox"]:checked + label {
    background-color: #4CAF50;
    border-radius: 5px;
}

</style>


<?php $currency_symbol = $this->customlib->getSchoolCurrencyFormat(); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <section class="content-header">
        <h1>
            <i class="fa fa-mortar-board"></i> Day Care
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php
            if ($this->rbac->hasPrivilege('daycare_service', 'can_add')) {
            ?>
                <div class="col-md-4">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Add Service</h3>
                        </div><!-- /.box-header -->
                        <form id="form1" action="<?php echo site_url('admin/daycare/service'); ?>" method="post" accept-charset="utf-8">
                            <div class="box-body">

                                <?php
                                $msg = $this->session->userdata('msg');
                                if ($msg) {
                                    echo $msg;
                                    $this->session->unset_userdata('msg');
                                }
                                ?>
                                <?php
                                if (isset($error_message)) {
                                    echo "<div class='alert alert-danger'>" . $error_message . "</div>";
                                }
                                ?>
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Service Name</label><small class="req"> *</small>
                                    <input autofocus="" id="service_name" name="service_name" placeholder="" type="text" class="form-control" value="<?php echo set_value('service_name'); ?>" />
                                    <span class="text-danger"><?php echo form_error('service_name'); ?></span>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Service Description</label>
                                    <input autofocus="" id="service_description" name="service_description" placeholder="" type="text" class="form-control" value="<?php echo set_value('service_description'); ?>" />
                                    <span class="text-danger"><?php echo form_error('service_description'); ?></span>
                                </div>


                            </div><!-- /.box-body -->

                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>

                </div><!--/.col (right) -->
                <!-- left column -->
            <?php } ?>
            <div class="col-md-<?php
                                if ($this->rbac->hasPrivilege('daycare_service', 'can_add')) {
                                    echo "8";
                                } else {
                                    echo "12";
                                }
                                ?>">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix">Service List</h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label">Service List</div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>

                                        <th>Service Name
                                        </th>
                                        <th>Service Description
                                        </th>

                                        <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>


                                <tbody>
                                    <?php
                                    foreach ($services as $service) {
                                    ?>
                                        <tr>
                                            <td class="mailbox-name">
                                                <?php echo $service->service_name; ?>

                                            </td>

                                            <td>
                                                <?php

                                                echo $service->service_description;

                                                ?>

                                            </td>
                                            <td class="mailbox-date pull-right">
                                            <?php

                                                $status = false;
                                                if (isset($grouped_activities)) {
                                                    foreach($grouped_activities as $date => $activity){
                                                        foreach($activity as $index => $row){
                                                            if($row->daycare_service_id == $service->id){
                                                                $status = true; 
                                                                break;
                                                            }
                                                        }
                                                    }
                                                }

                                                if ($this->rbac->hasPrivilege('daycare_activity', 'can_view') && !$status) {
                                                ?>
                                                    <a data-placement="left" class="btn btn-default btn-xs" data-toggle="tooltip" title="Activity" onclick="add_activity('<?php echo $service->id; ?>','<?php echo $service->service_name; ?>')">
                                                    <i class="fa fa-plus"></i>
                                                    </a>
                                                <?php
                                                }
                                                if ($this->rbac->hasPrivilege('daycare_service', 'can_edit')) {
                                                ?>
                                                    <a data-placement="left" href="<?php echo base_url(); ?>admin/daycare/edit/<?php echo $service->id; ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                <?php
                                                }
                                                if ($this->rbac->hasPrivilege('daycare_service', 'can_delete')) {
                                                ?>
                                                    <a data-placement="left" href="<?php echo base_url(); ?>admin/daycare/delete/<?php echo $service->id; ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('Are You Sure You want to delete this Service');">
                                                        <i class="fa fa-remove"></i>
                                                    </a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>

                                </tbody>
                            </table><!-- /.table -->



                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
            </div><!--/.col (left) -->
            <!-- right column -->

        </div>
        <div class="row">
            <!-- left column -->

            <!-- right column -->
            <div class="col-md-12">

            </div><!--/.col (right) -->
        </div> <!-- /.row -->
    </section><!-- /.content -->     
</div><!-- /.content-wrapper -->


<div class="modal fade" id="add_activity"  role="dialog" aria-labelledby="evaluation" style="padding-left: 0 !important">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="box-title" id="title"> </h4>
            </div>
            <div class="modal-body pt0 pb0">
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
                                            <span class="section_id_error text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
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
                                    <div class="col-lg-4 col-md-4 col-sm-4">
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


                                    <div class="col-lg-12 col-md-12 col-sm-12">
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
 function add_activity(id,name){

    $('#add_activity').modal('show');
    $('#title').html(name);
    $('#activity_id').val(id);

    if (id == 1 || id == 2 || id == 4) {
        $('#meal-field').show(); 
    } else {
        $('#meal-field').hide(); 
        $('#description-field').show();
        
    }

 }



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