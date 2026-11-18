<style>
    .content-wrapper {
        background: #f8f9fa;
        padding: 30px;
    }

    .box {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        border: none;
    }

    .box-header {
        background-color: #007bff;
        color: #fff;
        padding: 15px;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
    }

    .box-title {
        font-size: 20px;
        font-weight: 500;
        margin: 0;
    }

    .form-group label {
        font-weight: 500;
        color: #333;
    }

    .form-control {
        border-radius: 4px;
        border-color: #ced4da;
        transition: border-color 0.3s ease;
    }

    .form-control:focus {
        border-color: #007bff;
        box-shadow: none;
    }

    .table-bordered th,
    .table-bordered td {
        vertical-align: middle !important;
        text-align: center;
    }

    .btn-success, .btn-danger {
        border-radius: 4px;
        padding: 5px 12px;
        font-size: 14px;
    }

    #addRow {
        margin-top: 15px;
    }

    .box-footer {
        border-top: 1px solid #dee2e6;
        padding-top: 15px;
    }

    .box-footer .btn {
        padding: 8px 18px;
        font-weight: 500;
    }

    table.table th {
        background-color: #f1f1f1;
        font-weight: 600;
    }

    table.table td span {
        font-weight: bold;
        font-size: 16px;
    }

    @media screen and (max-width: 768px) {
        .form-group.col-sm-4 {
            width: 100%;
        }

        .table-responsive {
            overflow-x: auto;
        }
    }
</style>


<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-building-o"></i> <?php echo $this->lang->line('rent_system'); ?></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('tax_invoice'); ?></h3>
                    </div>

                    <form action="<?php echo site_url('admin/taxinvoice/add') ?>" id="issueitem" name="employeeform" method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <div class="row">
                                <!-- <?php if ($this->session->flashdata('msg')) { ?>
                                    <?php echo $this->session->flashdata('msg') ?>
                                <?php } ?> -->

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

                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="form-group col-sm-4">
                                            <label for="exampleInputEmail1">
                                                Recipient Name
                                                <!-- <?php echo $this->lang->line('name'); ?> -->
                                            </label><small class="req"> *</small>

                                            <select id='name' name='name' onchange="getdetails(this.value)" class="form-control">
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                <?php
                                                foreach ($itemcatlist as $item_category) {
                                                ?>
                                                    <option value="<?php echo $item_category['id'] ?>" <?php
                                                                                                        // if (set_value('id') == $item_category['id']) {
                                                                                                        //     echo "selected = selected";
                                                                                                        // }
                                                                                                        ?>><?php echo $item_category['service_recipient'] ?></option>

                                                <?php
                                                }
                                                ?>
                                            </select>
                                            <span class="text-danger"><?php echo form_error('name'); ?></span>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="form-group col-sm-4">
                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('address'); ?></label>
                                            <input id='address' name='address' placeholder="" type="text" class="form-control" value="<?php echo set_value('address'); ?>" readonly />

                                            <span class="text-danger"><?php echo form_error('address'); ?></span>
                                        </div>


                                        <div class="form-group col-sm-4">
                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('issue_date'); ?></label><small class="req"> *</small>
                                            <input id="issue_date" name="issue_date" placeholder="" type="text" class="form-control date" value="<?php echo set_value('issue_date'); ?>"/>
                                            <span class="text-danger"><?php echo form_error('issue_date'); ?></span>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="form-group col-sm-4">
                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('gstin'); ?></label>
                                            <input id='gstin' name='gstin' placeholder="" type="text" class="form-control" value="<?php echo set_value('gstin'); ?>" readonly />

                                            <span class="text-danger"><?php echo form_error('gstin'); ?></span>
                                        </div>

                                        <div class="form-group col-sm-4">
                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('invoice_no'); ?></label><small class="req"> *</small>
                                            <input id="invoice_no" name="invoice_no" placeholder="" type="text" class="form-control" value="<?php echo set_value('invoice_no'); ?>"/>
                                            <span class="text-danger"><?php echo form_error('invoice_no'); ?></span>
                                        </div>

                                    </div>
                                </div>


                                <div class="col-md-12">
                                    <div class="row">
                                                                                
                                    </div>
                                </div>
                                <!-- <div class="form-group col-md-4 col-sm-4">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('issue_date'); ?></label><small class="req"> *</small>
                                    <input id="issue_date" name="issue_date" placeholder="" type="text" class="form-control date" value="<?php echo set_value('issue_date'); ?>" readonly />
                                    <span class="text-danger"><?php echo form_error('issue_date'); ?></span>
                                </div>

                                <div class="form-group col-md-4 col-sm-4">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('note'); ?></label>
                                    <textarea name="note" class="form-control" id="note" /><?php echo set_value('note'); ?></textarea>
                                    <span class="text-danger"><?php echo form_error('note'); ?></span>
                                </div> -->

                                <div class="form-group col-md-12 col-sm-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dynamicTable">
                                        <thead>
                                            <tr>
                                                <th>SL No</th>
                                                <th>Description of Service<small class="req"> *</small></th>
                                                <th>SAC<small class="req"> *</small></th>
                                                <th>Unit (Hours/Days)<small class="req"> *</small></th>
                                                <th>Total<small class="req"> *</small></th>
                                                <th>Taxable Value</th>
                                                <th colspan="2">CGST</th>
                                                <th colspan="2">SGST</th>
                                                <th colspan="2">IGST</th>
                                                <th>Action</th>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th>Rate</th>
                                                <th>Amount</th>
                                                <th>Rate</th>
                                                <th>Amount</th>
                                                <th>Rate</th>
                                                <th>Amount</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                    <button type="button" class="btn btn-success" id="addRow">Add Row</button>
                                    </div>
                                </div>  

                                <!-- <div class="form-group col-md-12 col-sm-12">
                                    <table class="table">
                                        <tr>
                                            <th>Total</th>
                                            <td><span id="totalAmount" name="totalAmount">0</span></td>
                                        </tr>
                                        <tr>
                                            <th>Taxable Value</th>
                                            <td><span id="taxableValue" name="taxableValue">0</span></td>
                                        </tr>

                                        <tr>
                                            <th>Total Tax Value</th>
                                            <td><span id="totaltaxValue" name="totaltaxValue">0</span></td>
                                        </tr>


                                        <tr>
                                            <th>Total Invoice Amount</th>
                                            <td><span id="totalinvoiceamount" name="totalinvoiceamount">0</span></td>
                                        </tr>
                                    </table>
                                </div> -->

                                <div class="form-group col-md-12 col-sm-12">
                                        <table class="table table-borderless">
                                            <tr>
                                                <th>Total</th>
                                                <td><span id="totalAmount" name="totalAmount">0</span></td>
                                            </tr>
                                            <tr>
                                                <th>Taxable Value</th>
                                                <td><span id="taxableValue" name="taxableValue">0</span></td>
                                            </tr>
                                            <tr>
                                                <th>Total Tax Value</th>
                                                <td><span id="totaltaxValue" name="totaltaxValue">0</span></td>
                                            </tr>
                                            <tr class="bg-light">
                                                <th>Total Invoice Amount</th>
                                                <td><span id="totalinvoiceamount" name="totalinvoiceamount" class="text-primary">0</span></td>
                                            </tr>
                                        </table>
                                    </div>



                                <input type="hidden" name="totalAmount" id="hiddenTotalAmount">
                                <input type="hidden" name="taxableValue" id="hiddenTaxableValue">
                                <input type="hidden" name="totaltaxValue" id="hiddenTotalTaxValue">
                                <input type="hidden" name="totalinvoiceamount" id="hiddenTotalInvoiceAmount">

                            </div>
                        </div>

                        <div class="box-footer">
                            <button type="submit" class="allot-fees btn btn-primary btn-sm pull-right" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Please Wait.."><?php echo $this->lang->line('submit'); ?>
                            </button>
                        </div>
                    </form>
                </div>

            </div><!--/.col (right) -->
        </div>
        <div class="row">
            <div class="col-md-12">
            </div><!--/.col (right) -->
        </div> <!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<script type="text/javascript">
    // $(document).ready(function() {

    $("#addRow").click(function() {
        var rowCount = $('#dynamicTable tr').length - 1;
        var newRow = '<tr>' +
            '<td>' + rowCount + '</td>' +
            '<td><input type="text" name="description[]" class="form-control description"></td>' +
            '<td><input type="text" name="sac[]" class="form-control sac"></td>' +
            '<td><input type="text" name="unit[]" class="form-control unit" oninput="calculateRow(this)"></td>' +
            '<td><input type="number" name="total[]" class="form-control total" oninput="calculateRow(this)"></td>' +
            '<td><input type="number" name="taxable_value[]" class="form-control taxable_value" readonly></td>' +
            '<td><input type="number" name="cgst_rate[]" class="form-control cgst_rate" oninput="calculateRow(this)"></td>' +
            '<td><input type="number" name="cgst_amount[]" class="form-control cgst_amount" readonly></td>' +
            '<td><input type="number" name="sgst_rate[]" class="form-control sgst_rate" oninput="calculateRow(this)"></td>' +
            '<td><input type="number" name="sgst_amount[]" class="form-control sgst_amount" readonly></td>' +
            '<td><input type="number" name="igst_rate[]" class="form-control igst_rate" oninput="calculateRow(this)"></td>' +
            '<td><input type="number" name="igst_amount[]" class="form-control igst_amount" readonly></td>' +
            '<td><button type="button" name="" class="btn btn-danger removeRow">Remove</button></td>' +
            '</tr>';

        $('#dynamicTable tbody').append(newRow);
        calculateSubtotals();
    });

    $(document).on('click', '.removeRow', function() {
        $(this).closest('tr').remove();
        calculateSubtotals();
    });


    $(document).on('input', '.total, .taxableValue', function() {
        calculateSubtotals();
    });


    function calculateRow(element) {
        var row = $(element).closest('tr');
        var unit = row.find('.unit').val() || 0;
        var total = row.find('.total').val() || 0;
        var cgstRate = row.find('.cgst_rate').val() || 0;
        var sgstRate = row.find('.sgst_rate').val() || 0;
        var igstRate = row.find('.igst_rate').val() || 0;

        var taxableValue = total;
        row.find('.taxable_value').val(taxableValue);

        var cgstAmount = (cgstRate / 100) * taxableValue;
        var sgstAmount = (sgstRate / 100) * taxableValue;
        var igstAmount = (igstRate / 100) * taxableValue;

        row.find('.cgst_amount').val(cgstAmount);
        row.find('.sgst_amount').val(sgstAmount);
        row.find('.igst_amount').val(igstAmount);

        calculateSubtotals();
    }

    function calculateSubtotals() {
        var totalAmount = 0;
        var taxableValue = 0;
        var totaltaxValue = 0;
        var totalinvoiceamount = 0;

        $('#dynamicTable tbody tr').each(function() {
            var total = $(this).find('.total').val() || 0;
            var taxValue = $(this).find('.taxable_value').val() || 0;
            var totalcgsttax = $(this).find('.cgst_amount').val() || 0;
            var totalsgsttax = $(this).find('.sgst_amount').val() || 0;
            var totaligsttax = $(this).find('.igst_amount').val() || 0;

            totalAmount += parseFloat(total);
            taxableValue += parseFloat(taxValue);
            totaltaxValue += parseFloat(parseInt(totalcgsttax) + parseInt(totalsgsttax) + parseInt(totaligsttax));
            totalinvoiceamount += parseFloat(parseInt(taxValue) + parseInt(totalcgsttax) + parseInt(totalsgsttax) + parseInt(totaligsttax));
        });

        $('#totalAmount').text(totalAmount.toFixed(2));
        $('#taxableValue').text(taxableValue.toFixed(2));
        $('#totaltaxValue').text(totaltaxValue.toFixed(2));
        $('#totalinvoiceamount').text(totalinvoiceamount.toFixed(2));


        // Set hidden input fields with the calculated values
        $('#hiddenTotalAmount').val(totalAmount.toFixed(2));
        $('#hiddenTaxableValue').val(taxableValue.toFixed(2));
        $('#hiddenTotalTaxValue').val(totaltaxValue.toFixed(2));
        $('#hiddenTotalInvoiceAmount').val(totalinvoiceamount.toFixed(2));
    }
    // });

    var base_url = '<?php echo base_url() ?>';

    function getdetails(id) {
        if (id) {
            $.ajax({
                type: "POST",
                url: base_url + "admin/taxinvoice/getUser/" + id,
                data: {
                    'id': id
                },
                dataType: "json",
                success: function(data) {

                    console.log(data);
                    if (data.status == 'success') {

                        $('#address').val(data.address);
                        $('#gstin').val(data.gstin_no);
                    } else {

                        $('#address').val('');
                        $('#gstin').val('');
                    }
                }
            });
        } else {

            $('#address').val('');
            $('#gstin').val('');
        }
    }


    $("#issueitem").submit(function(e) {
        var data = $(this).serializeArray();
        data.push({});

        var $this = $('.allot-fees');
        $this.button('loading');
        e.preventDefault();

        var postData = data;
        var formURL = $(this).attr("action");

        $.ajax({
            url: formURL,
            type: "POST",
            data: postData,
            dataType: 'Json',
            success: function(data, textStatus, jqXHR) {
                if (data.status == "fail") {
                    var message = "";
                    $.each(data.error, function(index, value) {

                        message += value;
                    });
                    errorMsg(message);
                } else {
                    $('#div_avail').css('display', 'none');
                    document.getElementById("issueitem").reset();
                    successMsg(data.message);
                    window.location.reload(true);
                }

                $this.button('reset');
                // window.location.reload(true);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $this.button('reset');
            }
        });

    });
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>backend/dist/js/savemode.js"></script>












<!-- //==============================Invoice Receipt without added style=================== -->

<!-- <style type="text/css">
    #div_avail {
        display: none;
    }

    .table input {
        width: 100px;
        text-align: right;
    }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-building-o"></i> <?php echo $this->lang->line('rent_system'); ?></h1>
    </section>
    
    <section class="content">
        <div class="row">
            <div class="col-md-12">

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('tax_invoice'); ?></h3>
                    </div>

                    <form action="<?php echo site_url('admin/taxinvoice/add') ?>" id="issueitem" name="employeeform" method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <div class="row">
                               
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

                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="form-group col-sm-4">
                                            <label for="exampleInputEmail1">
                                                Recipient Name
                                                
                                            </label><small class="req"> *</small>

                                            <select id='name' name='name' onchange="getdetails(this.value)" class="form-control">
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                <?php
                                                foreach ($itemcatlist as $item_category) {
                                                ?>
                                                    <option value="<?php echo $item_category['id'] ?>" ><?php echo $item_category['service_recipient'] ?></option>

                                                <?php
                                                }
                                                ?>
                                            </select>
                                            <span class="text-danger"><?php echo form_error('name'); ?></span>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="form-group col-sm-4">
                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('address'); ?></label>
                                            <input id='address' name='address' placeholder="" type="text" class="form-control" value="<?php echo set_value('address'); ?>" readonly />

                                            <span class="text-danger"><?php echo form_error('address'); ?></span>
                                        </div>


                                        <div class="form-group col-sm-4">
                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('issue_date'); ?></label><small class="req"> *</small>
                                            <input id="issue_date" name="issue_date" placeholder="" type="text" class="form-control date" value="<?php echo set_value('issue_date'); ?>"/>
                                            <span class="text-danger"><?php echo form_error('issue_date'); ?></span>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="form-group col-sm-4">
                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('gstin'); ?></label>
                                            <input id='gstin' name='gstin' placeholder="" type="text" class="form-control" value="<?php echo set_value('gstin'); ?>" readonly />

                                            <span class="text-danger"><?php echo form_error('gstin'); ?></span>
                                        </div>

                                        <div class="form-group col-sm-4">
                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('invoice_no'); ?></label><small class="req"> *</small>
                                            <input id="invoice_no" name="invoice_no" placeholder="" type="text" class="form-control" value="<?php echo set_value('invoice_no'); ?>"/>
                                            <span class="text-danger"><?php echo form_error('invoice_no'); ?></span>
                                        </div>

                                    </div>
                                </div>


                                <div class="col-md-12">
                                    <div class="row">
                                                                                
                                    </div>
                                </div>
                               
                                <div class="form-group col-md-12 col-sm-12">
                                    <table class="table table-bordered" id="dynamicTable">
                                        <thead>
                                            <tr>
                                                <th>SL No</th>
                                                <th>Description of Service<small class="req"> *</small></th>
                                                <th>SAC<small class="req"> *</small></th>
                                                <th>Unit (Hours/Days)<small class="req"> *</small></th>
                                                <th>Total<small class="req"> *</small></th>
                                                <th>Taxable Value</th>
                                                <th colspan="2">CGST</th>
                                                <th colspan="2">SGST</th>
                                                <th colspan="2">IGST</th>
                                                <th>Action</th>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th>Rate</th>
                                                <th>Amount</th>
                                                <th>Rate</th>
                                                <th>Amount</th>
                                                <th>Rate</th>
                                                <th>Amount</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                    <button type="button" class="btn btn-success" id="addRow">Add Row</button>
                                </div>

                                <div class="form-group col-md-12 col-sm-12">
                                    <table class="table">
                                        <tr>
                                            <th>Total</th>
                                            <td><span id="totalAmount" name="totalAmount">0</span></td>
                                        </tr>
                                        <tr>
                                            <th>Taxable Value</th>
                                            <td><span id="taxableValue" name="taxableValue">0</span></td>
                                        </tr>

                                        <tr>
                                            <th>Total Tax Value</th>
                                            <td><span id="totaltaxValue" name="totaltaxValue">0</span></td>
                                        </tr>


                                        <tr>
                                            <th>Total Invoice Amount</th>
                                            <td><span id="totalinvoiceamount" name="totalinvoiceamount">0</span></td>
                                        </tr>
                                    </table>
                                </div>


                                <input type="hidden" name="totalAmount" id="hiddenTotalAmount">
                                <input type="hidden" name="taxableValue" id="hiddenTaxableValue">
                                <input type="hidden" name="totaltaxValue" id="hiddenTotalTaxValue">
                                <input type="hidden" name="totalinvoiceamount" id="hiddenTotalInvoiceAmount">

                            </div>
                        </div>

                        <div class="box-footer">
                            <button type="submit" class="allot-fees btn btn-primary btn-sm pull-right" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Please Wait.."><?php echo $this->lang->line('submit'); ?>
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
    // $(document).ready(function() {

    $("#addRow").click(function() {
        var rowCount = $('#dynamicTable tr').length - 1;
        var newRow = '<tr>' +
            '<td>' + rowCount + '</td>' +
            '<td><input type="text" name="description[]" class="form-control description"></td>' +
            '<td><input type="text" name="sac[]" class="form-control sac"></td>' +
            '<td><input type="text" name="unit[]" class="form-control unit" oninput="calculateRow(this)"></td>' +
            '<td><input type="number" name="total[]" class="form-control total" oninput="calculateRow(this)"></td>' +
            '<td><input type="number" name="taxable_value[]" class="form-control taxable_value" readonly></td>' +
            '<td><input type="number" name="cgst_rate[]" class="form-control cgst_rate" oninput="calculateRow(this)"></td>' +
            '<td><input type="number" name="cgst_amount[]" class="form-control cgst_amount" readonly></td>' +
            '<td><input type="number" name="sgst_rate[]" class="form-control sgst_rate" oninput="calculateRow(this)"></td>' +
            '<td><input type="number" name="sgst_amount[]" class="form-control sgst_amount" readonly></td>' +
            '<td><input type="number" name="igst_rate[]" class="form-control igst_rate" oninput="calculateRow(this)"></td>' +
            '<td><input type="number" name="igst_amount[]" class="form-control igst_amount" readonly></td>' +
            '<td><button type="button" name="" class="btn btn-danger removeRow">Remove</button></td>' +
            '</tr>';

        $('#dynamicTable tbody').append(newRow);
        calculateSubtotals();
    });

    $(document).on('click', '.removeRow', function() {
        $(this).closest('tr').remove();
        calculateSubtotals();
    });


    $(document).on('input', '.total, .taxableValue', function() {
        calculateSubtotals();
    });


    function calculateRow(element) {
        var row = $(element).closest('tr');
        var unit = row.find('.unit').val() || 0;
        var total = row.find('.total').val() || 0;
        var cgstRate = row.find('.cgst_rate').val() || 0;
        var sgstRate = row.find('.sgst_rate').val() || 0;
        var igstRate = row.find('.igst_rate').val() || 0;

        var taxableValue = total;
        row.find('.taxable_value').val(taxableValue);

        var cgstAmount = (cgstRate / 100) * taxableValue;
        var sgstAmount = (sgstRate / 100) * taxableValue;
        var igstAmount = (igstRate / 100) * taxableValue;

        row.find('.cgst_amount').val(cgstAmount);
        row.find('.sgst_amount').val(sgstAmount);
        row.find('.igst_amount').val(igstAmount);

        calculateSubtotals();
    }

    function calculateSubtotals() {
        var totalAmount = 0;
        var taxableValue = 0;
        var totaltaxValue = 0;
        var totalinvoiceamount = 0;

        $('#dynamicTable tbody tr').each(function() {
            var total = $(this).find('.total').val() || 0;
            var taxValue = $(this).find('.taxable_value').val() || 0;
            var totalcgsttax = $(this).find('.cgst_amount').val() || 0;
            var totalsgsttax = $(this).find('.sgst_amount').val() || 0;
            var totaligsttax = $(this).find('.igst_amount').val() || 0;

            totalAmount += parseFloat(total);
            taxableValue += parseFloat(taxValue);
            totaltaxValue += parseFloat(parseInt(totalcgsttax) + parseInt(totalsgsttax) + parseInt(totaligsttax));
            totalinvoiceamount += parseFloat(parseInt(taxValue) + parseInt(totalcgsttax) + parseInt(totalsgsttax) + parseInt(totaligsttax));
        });

        $('#totalAmount').text(totalAmount.toFixed(2));
        $('#taxableValue').text(taxableValue.toFixed(2));
        $('#totaltaxValue').text(totaltaxValue.toFixed(2));
        $('#totalinvoiceamount').text(totalinvoiceamount.toFixed(2));


        // Set hidden input fields with the calculated values
        $('#hiddenTotalAmount').val(totalAmount.toFixed(2));
        $('#hiddenTaxableValue').val(taxableValue.toFixed(2));
        $('#hiddenTotalTaxValue').val(totaltaxValue.toFixed(2));
        $('#hiddenTotalInvoiceAmount').val(totalinvoiceamount.toFixed(2));
    }
    // });

    var base_url = '<?php echo base_url() ?>';

    function getdetails(id) {
        if (id) {
            $.ajax({
                type: "POST",
                url: base_url + "admin/taxinvoice/getUser/" + id,
                data: {
                    'id': id
                },
                dataType: "json",
                success: function(data) {

                    console.log(data);
                    if (data.status == 'success') {

                        $('#address').val(data.address);
                        $('#gstin').val(data.gstin_no);
                    } else {

                        $('#address').val('');
                        $('#gstin').val('');
                    }
                }
            });
        } else {

            $('#address').val('');
            $('#gstin').val('');
        }
    }


    $("#issueitem").submit(function(e) {
        var data = $(this).serializeArray();
        data.push({});

        var $this = $('.allot-fees');
        $this.button('loading');
        e.preventDefault();

        var postData = data;
        var formURL = $(this).attr("action");

        $.ajax({
            url: formURL,
            type: "POST",
            data: postData,
            dataType: 'Json',
            success: function(data, textStatus, jqXHR) {
                if (data.status == "fail") {
                    var message = "";
                    $.each(data.error, function(index, value) {

                        message += value;
                    });
                    errorMsg(message);
                } else {
                    $('#div_avail').css('display', 'none');
                    document.getElementById("issueitem").reset();
                    successMsg(data.message);
                    window.location.reload(true);
                }

                $this.button('reset');
                // window.location.reload(true);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $this.button('reset');
            }
        });

    });
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>backend/dist/js/savemode.js"></script> -->