<div class="content-wrapper">

    <section class="content-header">
        <h1>
            <i class="fa fa-object-group"></i> <?php echo $this->lang->line('rent_system'); ?>
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('tax_invoice'); ?></h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('tax_invoice', 'can_add')) {
                            ?>
                                <a href="<?php echo site_url('admin/taxinvoice/create') ?>" type="button" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add_tax_invoice'); ?></a>
                            <?php }
                            ?>

                        </div>
                    </div>
                    <div class="box-body">
                        <div class="mailbox-messages table-responsive">
                            <table class="table table-striped table-bordered table-hover item-list" data-export-title="<?php echo $this->lang->line('add_tax_invoice'); ?>">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <th><?php echo $this->lang->line('invoice_no'); ?></th>
                                        <!-- <th>Invoice Date</th> -->
                                        <th>Items</th>
                                        <th>Total Amount</th>
                                        <th>Taxable Value</th>
                                        <th>Total Tax Value</th>
                                        <th>Total Invoice Amount</th>
                                        <!-- <th><?php echo $this->lang->line('status'); ?></th> -->
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section><!-- /.content -->
</div><!-- /.content-wrapper -->


<div class="modal fade" id="editInvoiceModal" tabindex="-1" role="dialog" aria-labelledby="editInvoiceModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

      <form action="<?php echo site_url('admin/taxinvoice/update/' . $invoice['id']) ?>" id="editInvoiceForm" method="post" accept-charset="utf-8">
        <div class="modal-header">
          <h4 class="modal-title" id="editInvoiceModalLabel">
            Edit Invoice Form
          </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">

          <?php
          if ($this->session->flashdata('msg')) {
              echo $this->session->flashdata('msg');
          }
          if (isset($error_message)) {
              echo "<div class='alert alert-danger'>" . $error_message . "</div>";
          }
          ?>

          <?php echo $this->customlib->getCSRF(); ?>

          <div class="row">
            
            <div class="form-group col-sm-6">
              <label>Issue Date <small class="req">*</small></label>
              <input id="issue_date" name="issue_date" type="text" class="form-control date" value="<?php echo set_value('issue_date', $invoice['issue_date']); ?>" />
              <span class="text-danger"><?php echo form_error('issue_date'); ?></span>
            </div>

            <div class="form-group col-sm-6">
              <label>Invoice No <small class="req">*</small></label>
              <input id="invoice_no" name="invoice_no" type="text" class="form-control" value="<?php echo set_value('invoice_no', $invoice['invoice_no']); ?>" />
              <span class="text-danger"><?php echo form_error('invoice_no'); ?></span>
            </div>

          </div>

          <div class="table-responsive">
            <table class="table table-bordered" id="dynamicTable">
              <thead>
                <tr>
                  <th>SL No</th>
                  <th>Description</th>
                  <th>SAC</th>
                  <th>Unit</th>
                  <th>Total</th>
                  <th>Taxable Value</th>
                  <th colspan="2">CGST</th>
                  <th colspan="2">SGST</th>
                  <th colspan="2">IGST</th>
                  <th>Action</th>
                </tr>
                <tr>
                  <th></th><th></th><th></th><th></th><th></th><th></th>
                  <th>Rate</th><th>Amount</th><th>Rate</th><th>Amount</th><th>Rate</th><th>Amount</th><th></th>
                </tr>
              </thead>
              <tbody>
                
              </tbody>
            </table>
            <button type="button" class="btn btn-success" id="addRow">Add Row</button>
          </div>

          <hr>

          <div class="row">
            <div class="col-md-6">
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
                <tr>
                  <th>Total Invoice Amount</th>
                  <td><span id="totalinvoiceamount" name="totalinvoiceamount" class="text-primary">0</span></td>
                </tr>
              </table>
            </div>
          </div>

          <input type="hidden" name="totalAmount" id="hiddenTotalAmount">
          <input type="hidden" name="taxableValue" id="hiddenTaxableValue">
          <input type="hidden" name="totaltaxValue" id="hiddenTotalTaxValue">
          <input type="hidden" name="totalinvoiceamount" id="hiddenTotalInvoiceAmount">

        </div> <!-- modal-body -->

        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary"><?php echo $this->lang->line('update'); ?></button>
        </div>
      </form>

    </div>
  </div>
</div>



<script type="text/javascript">

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


    $(document).on('click', '.editDoc', function () {
        var tax_invoice_id = $(this).data('tax_invoice_id');
        
        if (tax_invoice_id) {
            $('#editInvoiceModal').modal('show');
            // $('#editModalBody').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-2x"></i> Loading...</div>');

            // $.ajax({
            //     url: '<?php echo site_url("admin/taxinvoice/edit") ?>/' + tax_invoice_id,
            //     type: 'GET',
            //     success: function (response) {
            //         $('#editModalBody').html(response);
            //     },
            //     error: function () {
            //         $('#editModalBody').html('<div class="alert alert-danger">Failed to load form. Please try again.</div>');
            //     }
            // });
        }
    });



    $(document).on('click', '.deleteDoc', function () {
    let invoiceId = $(this).data('tax_invoice_id');

    if (confirm("Are you sure you want to delete this invoice and its items?")) {
        $.ajax({
            url: base_url + 'admin/taxinvoice/delete_invoice',
            type: 'POST',
            data: { invoice_id: invoiceId },
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    alert('Invoice deleted successfully');
                    $('#dynamicTable').DataTable().ajax.reload(); // Reload table
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function () {
                alert('Something went wrong while deleting.');
            }
        });
    }
    });


   
    $(document).on('click', '.printDoc', function() {

        var tax_invoice_id = $(this).data('tax_invoice_id');

        if (tax_invoice_id) {

            $.ajax({

                url: '<?php echo site_url("admin/taxinvoice/printInvoiceByGroup") ?>',

                type: 'post',

                data: {

                    'tax_invoice_id': tax_invoice_id,

                },

                success: function(response) {

                    //console.log("Response received:", response);

                    Popup(response);

                },


            });

        } else {

            console.error("Tax Invoice ID is missing");

        }

    });
</script>

<script type="text/javascript">
    var base_url = '<?php echo base_url() ?>';


    function Popup(data, winload = false)

    {

        // console.log("Data passed to Popup:", data);

        var frame1 = $('<iframe />').attr("id", "printDiv");

        frame1[0].name = "frame1";

        frame1.css({
            "position": "absolute",
            "top": "-1000000px"
        });

        $("body").append(frame1);

        var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;

        frameDoc.document.open();

        frameDoc.document.write('<html>');

        frameDoc.document.write('<head>');

        frameDoc.document.write('<title></title>');

        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/bootstrap/css/bootstrap.min.css">');

        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/font-awesome.min.css">');

        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/ionicons.min.css">');

        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/AdminLTE.min.css">');

        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/skins/_all-skins.min.css">');

        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/iCheck/flat/blue.css">');

        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/morris/morris.css">');

        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/jvectormap/jquery-jvectormap-1.2.2.css">');

        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/datepicker/datepicker3.css">');

        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/daterangepicker/daterangepicker-bs3.css">');

        frameDoc.document.write('</head>');

        frameDoc.document.write('<body>');

        frameDoc.document.write(data);

        frameDoc.document.write('</body>');

        frameDoc.document.write('</html>');

        frameDoc.document.close();

        setTimeout(function() {

            document.getElementById('printDiv').contentWindow.focus();

            document.getElementById('printDiv').contentWindow.print();

            $("#printDiv", top.document).remove();

            // frame1.remove();

            if (winload) {

                window.location.reload(true);

            }

        }, 500);

        return true;

    }
</script>

<script>
    (function($) {
        'use strict';
        $(document).ready(function() {
            initDatatable('item-list', 'admin/taxinvoice/getitemlist', [], [], 100, [[0, 'desc']]);
        });
    }(jQuery))
</script>