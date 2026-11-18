<form action="<?php echo site_url('admin/taxinvoice/update/' . $invoice['id']) ?>" id="editInvoiceForm" method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <div class="row">

                                <?php
                                if ($this->session->flashdata('msg')) {
                                    echo $this->session->flashdata('msg');
                                }
                                if (isset($error_message)) {
                                    echo "<div class='alert alert-danger'>" . $error_message . "</div>";
                                }
                                ?>

                                <?php echo $this->customlib->getCSRF(); ?>

                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="form-group col-sm-4">
                                            <label>Recipient Name <small class="req"> *</small></label>
                                            <select id='name' name='name' onchange="getdetails(this.value)" class="form-control">
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                <?php
                                                foreach ($itemcatlist as $item_category) {
                                                    $selected = ($item_category['id'] == $invoice['recipient_id']) ? "selected" : "";
                                                    echo "<option value='{$item_category['id']}' {$selected}>{$item_category['service_recipient']}</option>";
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
                                            <label>Address</label>
                                            <input id="address" name="address" type="text" class="form-control" value="<?php echo set_value('address', $invoice['address']); ?>" readonly />
                                        </div>

                                        <div class="form-group col-sm-4">
                                            <label>Issue Date <small class="req">*</small></label>
                                            <input id="issue_date" name="issue_date" type="text" class="form-control date" value="<?php echo set_value('issue_date', $invoice['issue_date']); ?>" />
                                            <span class="text-danger"><?php echo form_error('issue_date'); ?></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="form-group col-sm-4">
                                            <label>GSTIN</label>
                                            <input id="gstin" name="gstin" type="text" class="form-control" value="<?php echo set_value('gstin', $invoice['gstin_no']); ?>" readonly />
                                        </div>

                                        <div class="form-group col-sm-4">
                                            <label>Invoice No <small class="req">*</small></label>
                                            <input id="invoice_no" name="invoice_no" type="text" class="form-control" value="<?php echo set_value('invoice_no', $invoice['invoice_no']); ?>" />
                                            <span class="text-danger"><?php echo form_error('invoice_no'); ?></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group col-md-12 col-sm-12">
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
                                                <?php
                                                if (!empty($invoice_items)) {
                                                    $i = 1;
                                                    foreach ($invoice_items as $item) {
                                                ?>
                                                    <tr>
                                                        <td><?php echo $i; ?></td>
                                                        <td><input type="text" name="description[]" class="form-control description" value="<?php echo $item['description']; ?>"></td>
                                                        <td><input type="text" name="sac[]" class="form-control sac" value="<?php echo $item['sac']; ?>"></td>
                                                        <td><input type="text" name="unit[]" class="form-control unit" value="<?php echo $item['unit']; ?>" oninput="calculateRow(this)"></td>
                                                        <td><input type="number" name="total[]" class="form-control total" value="<?php echo $item['total']; ?>" oninput="calculateRow(this)"></td>
                                                        <td><input type="number" name="taxable_value[]" class="form-control taxable_value" value="<?php echo $item['taxable_value']; ?>" readonly></td>
                                                        <td><input type="number" name="cgst_rate[]" class="form-control cgst_rate" value="<?php echo $item['cgst_rate']; ?>" oninput="calculateRow(this)"></td>
                                                        <td><input type="number" name="cgst_amount[]" class="form-control cgst_amount" value="<?php echo $item['cgst_amount']; ?>" readonly></td>
                                                        <td><input type="number" name="sgst_rate[]" class="form-control sgst_rate" value="<?php echo $item['sgst_rate']; ?>" oninput="calculateRow(this)"></td>
                                                        <td><input type="number" name="sgst_amount[]" class="form-control sgst_amount" value="<?php echo $item['sgst_amount']; ?>" readonly></td>
                                                        <td><input type="number" name="igst_rate[]" class="form-control igst_rate" value="<?php echo $item['igst_rate']; ?>" oninput="calculateRow(this)"></td>
                                                        <td><input type="number" name="igst_amount[]" class="form-control igst_amount" value="<?php echo $item['igst_amount']; ?>" readonly></td>
                                                        <td><button type="button" class="btn btn-danger removeRow">Remove</button></td>
                                                    </tr>
                                                <?php $i++; } } ?>
                                            </tbody>
                                        </table>
                                        <button type="button" class="btn btn-success" id="addRow">Add Row</button>
                                    </div>
                                </div>

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
                                        <tr>
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
                            <button type="submit" class="btn btn-primary btn-sm pull-right" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Please Wait..">
                                <?php echo $this->lang->line('update'); ?>
                            </button>
                        </div>
                    </form>
