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
            if ($this->rbac->hasPrivilege('daycare_service', 'can_add') || $this->rbac->hasPrivilege('daycare_service', 'can_edit')) {
            ?>
                <div class="col-md-4">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Edit Service</h3>
                        </div><!-- /.box-header -->
                        <form id="form1" action="<?php echo site_url('admin/daycare/edit/' . $service->id) ?>" method="post" accept-charset="utf-8">
                            <div class="box-body">


                                <?php if ($this->session->flashdata('msg')) { ?>
                                    <?php echo $this->session->flashdata('msg') ?>
                                <?php } ?>
                                <?php
                                if (isset($error_message)) {
                                    echo "<div class='alert alert-danger'>" . $error_message . "</div>";
                                }
                                ?>

                                <?php echo $this->customlib->getCSRF(); ?>

                                <input type="hidden" name="pre_service_name" value="<?php echo $service->service_name; ?>">

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Service Name</label><small class="req"> *</small>
                                    <input autofocus="" id="service_name" name="service_name" placeholder="" type="text" class="form-control" value="<?php echo set_value('service_name', $service->service_name); ?>" />
                                    <span class="text-danger"><?php echo form_error('service_name'); ?></span>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Service Description</label>
                                    <input autofocus="" id="service_description" name="service_description" placeholder="" type="text" class="form-control" value="<?php echo set_value('service_description', $service->service_description); ?>" />
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
                                if ($this->rbac->hasPrivilege('daycare_service', 'can_add') || $this->rbac->hasPrivilege('daycare_service', 'can_edit')) {
                                    echo "8";
                                } else {
                                    echo "12";
                                }
                                ?>  ">
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
                                                if ($this->rbac->hasPrivilege('daycare_service', 'can_edit')) {
                                                ?>
                                                    <a data-placement="left" href="<?php echo base_url(); ?>admin/daycare/edit/<?php echo $service->id; ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                <?php
                                                }
                                                ?>
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

<?php

function check_in_array($find, $array)
{

    foreach ($array as $element) {
        if ($find == $element->id) {
            return TRUE;
        }
    }
    return FALSE;
}
?>