<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-object-group"></i> <?php echo $this->lang->line('rent_system'); ?>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php if ($this->rbac->hasPrivilege('service_supplier', 'can_add') || $this->rbac->hasPrivilege('service_supplier', 'can_edit')) { ?>
                <div class="col-md-4">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('edit_service_supplier'); ?></h3>
                        </div><!-- /.box-header -->
                        <!-- form start -->
                        <form action="<?php echo site_url("admin/servicesupplier/edit/" . $id) ?>" id="employeeform" name="employeeform" method="post" accept-charset="utf-8">
                            <div class="box-body">

                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"> <?php echo $this->lang->line('name'); ?></label><small class="req"> *</small>
                                    <input autofocus="" id="name" name="name" placeholder="name" type="text" class="form-control" value="<?php echo set_value('servicesupplier', $itemsupplier['service_supplier']); ?>" />
                                    <span class="text-danger"><?php echo form_error('servicesupplier'); ?></span>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('address'); ?></label>
                                    <textarea class="form-control" id="address" name="address" placeholder="" rows="3" placeholder="Enter ..."><?php echo set_value('address', $itemsupplier['address']); ?></textarea>
                                    <span class="text-danger"><?php echo form_error('address'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"> <?php echo $this->lang->line('gstin'); ?></label><small class="req"> *</small>
                                    <input id="gstin" name="gstin" placeholder="" type="text" class="form-control" value="<?php echo set_value('gstin', $itemsupplier['gstin_no']); ?>" />
                                    <span class="text-danger"><?php echo form_error('gstin'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('pan_no'); ?></label>
                                    <input id="pan_no" name="pan_no" placeholder="" type="text" class="form-control" value="<?php echo set_value('pan_no', $itemsupplier['pan_no']); ?>" />
                                    <span class="text-danger"><?php echo form_error('pan_no'); ?></span>
                                </div>
                                <!-- <div class="form-group">
                                    <label for="exampleInputEmail1"> <?php echo $this->lang->line('contact_person_email'); ?></label>
                                    <input id="contact_person_email" name="contact_person_email" placeholder="" type="text" class="form-control" value="<?php echo set_value('contact_person_email', $itemsupplier['contact_person_email']); ?>" />
                                    <span class="text-danger"><?php echo form_error('contact_person_email'); ?></span>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                                    <textarea class="form-control" id="description" name="description" placeholder="" rows="3" placeholder="Enter ..."><?php echo set_value('description', $itemsupplier['description']); ?></textarea>
                                    <span class="text-danger"><?php echo form_error('description'); ?></span>
                                </div> -->
                            </div><!-- /.box-body -->
                            <div class="box-footer">

                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>
                </div><!--/.col (right) -->
            <?php } ?>
            <div class="col-md-<?php
                                if ($this->rbac->hasPrivilege('service_supplier', 'can_add') || $this->rbac->hasPrivilege('service_supplier', 'can_edit')) {
                                    echo "8";
                                } else {
                                    echo "12";
                                }
                                ?>">
                <!-- general form elements -->
                <div class="box box-primary" id="exphead">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('service_supplier_list'); ?></h3>
                    </div><!-- /.box-header -->
                    <div class="box-body  ">
                        <div class="mailbox-messages table-responsive">
                            <div class="download_label"><?php echo $this->lang->line('service_supplier_list'); ?></div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('service_supplier'); ?></th>
                                        <th><?php echo $this->lang->line('gstin'); ?></th>
                                        <th><?php echo $this->lang->line('address'); ?></th>
                                        <th class="text-right no-print"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($itemsupplierlist)) {
                                    ?>

                                        <?php
                                    } else {
                                        $count = 1;
                                        foreach ($itemsupplierlist as $supplier) {
                                        ?>
                                            <tr>


                                                <td class="mailbox-name">

                                                    <a href="#" data-toggle="popover" class="detail_popover">
                                                        <?php echo $supplier['service_supplier'] ?>
                                                        <br>
                                                    </a>

                                                    <div class="fee_detail_popover" style="display: none">
                                                        <?php
                                                        if ($supplier['description'] == "") {
                                                        ?>
                                                            <p class="text text-danger"><?php echo $this->lang->line('no_description'); ?></p>
                                                        <?php
                                                        } else {
                                                        ?>
                                                            <p class="text text-info"><?php echo $supplier['description']; ?></p>
                                                        <?php
                                                        }
                                                        ?>
                                                    </div>
                                                </td>


                                                <td class="mailbox-name">
                                                    <?php
                                                    if ($supplier['gstin_no'] != "") {
                                                    ?>
                                                        <!-- <i class="fa fa-building"></i>  -->
                                                        <?php echo $supplier['gstin_no'] ?>
                                                    <?php
                                                    }
                                                    ?>

                                                </td>

                                                <td class="mailbox-name">
                                                    <?php
                                                    if ($supplier['address'] != "") {
                                                    ?>
                                                        <i class="fa fa-building"></i> <?php echo $supplier['address'] ?>
                                                    <?php
                                                    }
                                                    ?>

                                                </td>
                                                <td class="mailbox-date pull-right no-print">
                                                    <?php if ($this->rbac->hasPrivilege('service_supplier', 'can_edit')) { ?>
                                                        <a data-placement="left" href="<?php echo base_url(); ?>admin/servicesupplier/edit/<?php echo $supplier['id'] ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                    <?php }
                                                    if ($this->rbac->hasPrivilege('service_supplier', 'can_delete')) { ?>
                                                        <a data-placement="left" href="<?php echo base_url(); ?>admin/servicesupplier/delete/<?php echo $supplier['id'] ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                            <i class="fa fa-remove"></i>
                                                        </a>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                        $count++;
                                    }
                                    ?>

                                </tbody>
                            </table><!-- /.table -->
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
            </div>

        </div> <!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<script>
    $(document).ready(function() {
        $('.detail_popover').popover({
            placement: 'right',
            trigger: 'hover',
            container: 'body',
            html: true,
            content: function() {
                return $(this).closest('td').find('.fee_detail_popover').html();
            }
        });
    });
</script>