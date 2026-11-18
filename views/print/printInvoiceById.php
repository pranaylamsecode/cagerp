<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tax Invoice</title>
    <link rel="stylesheet" href="<?php echo base_url('backend/bootstrap/css/bootstrap.min.css'); ?>">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .invoice-container {
            border: 1px solid #ccc;
            padding: 20px;
        }

        .invoice-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .invoice-header h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .invoice-details {
            margin-bottom: 30px;
        }

        .invoice-details table {
            width: 100%;
            border-collapse: collapse;
        }

        .invoice-details th,
        .invoice-details td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }


        .table-bordered {
            width: 100%;
            border-collapse: collapse;
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
            vertical-align: middle;
        }

        .table-bordered th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .tax-column {
            display: flex;
            flex-direction: column;
        }

        .tax-column div {
            padding: 2px 0;
            border-bottom: 1px solid #000;
        }

        .tax-column div:last-child {
            border-bottom: none;
        }

        .invoice-footer {
            text-align: left;
            margin-top: 20px;
            font-size: 14px;

        }

        .signature-section {
            margin-top: 25px;
            text-align: right;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <div class="invoice-header">
            <h1>Tax Invoice</h1>
            <p><strong>Supplier:</strong>
                <!-- The Calcutta Gujarati Education Society -->
                <?php echo $sch_setting->name; ?>
            </p>
            <p><strong>GSTIN:</strong> 19AAATT8527F1Z7 | <strong>PAN:</strong> AAATT8527F </p>
            <p><strong>Address:</strong>
                <!-- 16A, Brabourne Road Kolkata - 700 001 -->
                <?php echo $sch_setting->address; ?>
            </p>
        </div>


        <div class="invoice-details">
            <table>
                <tr>
                    <th>Invoice No:</th>
                    <td><?php $currentyear = date('y');
                        $nextyear = date('y', strtotime('+1 year'));
                        // echo "cges/" . $invoice_data[0]['invoice_no'] . "/" . $currentyear . "-" . $nextyear; 
                        echo $invoice_data[0]['invoice_no']; 

                        
                        ?></td>
                    <th>Date:</th>
                    <td><?php echo date('d/m/Y', strtotime($invoice_data[0]['invoice_date'])); ?></td>
                </tr>
                <!-- <tr>
                    <th>Challan No:</th>
                    <td><?php echo ""; ?></td>
                    <th>Date:</th>
                    <td><?php echo ""; ?></td>
                </tr> -->
                <!-- <tr>
                    <th>Ref No:</th>
                    <td><?php echo ""; ?></td>
                    <th>Date:</th>
                    <td><?php echo ""; ?></td>
                </tr> -->
                <tr>
                    <td colspan="4">
                        <strong>Billing Details:</strong>
                        <div style="border: 1px solid #000; padding: 10px; margin-top: 5px;">
                            <table style="width: 100%;">
                                <tr>
                                    <th style="text-align: left;">Name:</th>
                                    <td><?php echo $invoice_data[0]['service_recipient']; ?></td>
                                </tr>
                                <tr>
                                    <th style="text-align: left;">Address:</th>
                                    <td><?php echo $invoice_data[0]['address'];
                                        ?></td>
                                </tr>
                                <tr>
                                    <th style="text-align: left;">GSTIN No:</th>
                                    <td><?php echo $invoice_data[0]['gstin_no'];
                                        ?></td>
                                </tr>

                                <tr>
                                    <th style="text-align: left;">PAN No:</th>
                                    <td><?php echo $invoice_data[0]['pan_no'];
                                        ?></td>
                                </tr>

                                <tr>
                                    <th style="text-align: left;">Place of supply:</th>
                                    <td><?php echo "West Bengal";
                                        ?></td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
            </table>
        </div>


        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>S. no.</th>
                    <th>Description</th>
                    <th>SAC</th>
                    <th>Time</th>
                    <th>Unit (Hours/Days)</th>
                    <th>Rate</th>
                    <th>Total (₹)</th>
                    <th>Discount</th>
                    <th>Taxable Value (₹)</th>
                    <th colspan="2">CGST</th>
                    <th colspan="2">SGST</th>
                    <!-- <th colspan="2">IGST</th> -->
                </tr>
                <tr>
                    <!-- Sub-headers for % and Amount -->
                    <th colspan="9"></th>
                    <th>%</th>
                    <th>Amount (₹)</th>
                    <th>%</th>
                    <th>Amount (₹)</th>
                    <!-- <th>%</th>
                    <th>Amount (₹)</th> -->
                </tr>
            </thead>
            <tbody>
                <?php
                $counter = 1;
                $grandTotal = 0;
                $grandTaxableValue = 0;
                $grandTaxAmount = 0;

                foreach ($invoice_data as $item) {
                    $grandTotal += $item['total'];
                    $grandTaxableValue += $item['taxable_value'];
                    $grandtotalinvestamount += $item['taxable_value'] + $item['cgst_amount'] + $item['sgst_amount'] + $item['igst_amount'];
                    $totalcgstamount += $item['cgst_amount'];
                    $totalsgstamount += $item['sgst_amount'];
                    $totaligstamount += $item['igst_amount'];
                    $grandTaxAmount += $item['cgst_amount'] + $item['sgst_amount'] + $item['igst_amount'];
                ?>
                    <tr>
                        <td><?php echo $counter++; ?></td>
                        <td><?php echo $item['description']; ?></td>
                        <td><?php echo $item['sac']; ?></td>
                        <td><?php echo ""; ?></td>
                        <td><?php echo $item['unit']; ?></td>
                        <td><?php echo ""; ?></td>
                        <td><?php echo number_format($item['total'], 2); ?></td>
                        <td><?php echo ""; ?></td>
                        <td><?php echo number_format($item['taxable_value'], 2); ?></td>
                        <td><?php echo $item['cgst_rate']; ?>%</td>
                        <td><?php echo number_format($item['cgst_amount'], 2); ?></td>
                        <td><?php echo $item['sgst_rate']; ?>%</td>
                        <td><?php echo number_format($item['sgst_amount'], 2); ?></td>
                        <!-- <td><?php echo $item['igst_rate']; ?>%</td>
                        <td><?php echo number_format($item['igst_amount'], 2); ?></td> -->
                    </tr>
                <?php } ?>
                <!-- Footer row -->

                <tr>
                    <td></td>
                    <td colspan="5" style="text-align: left; font-weight: bold;">Add : Freight Insurance</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <!-- <td></td>
                    <td></td> -->

                </tr>
                <tr>
                    <td></td>
                    <td colspan="5" style="text-align: left; font-weight: bold;">Add : Packaging and Forwarding Charges</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <!-- <td></td>
                    <td></td> -->


                </tr>
                <tr>
                    <td></td>
                    <td colspan="5" style="text-align: left; font-weight: bold;">Add : Other Charges</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <!-- <td></td>
                    <td></td> -->

                </tr>

                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>

                    <td colspan="2" style="text-align: right; font-weight: bold;">Total</td>
                    <td><?php echo number_format($grandTotal, 2); ?></td>

                    <td></td>
                    <td><?php echo number_format($grandTaxableValue, 2); ?></td>
                    <td></td>
                    <td><?php echo number_format($totalcgstamount, 2); ?></td>
                    <td></td>
                    <td><?php echo number_format($totalsgstamount, 2); ?></td>
                    <!-- <td></td>
                    <td><?php echo number_format($totaligstamount, 2); ?></td> -->
                </tr>
            </tbody>
        </table>


        <?php
        function convert_to_words_array($number)
        {
            $words = array(
                '0' => 'Zero', '1' => 'One', '2' => 'Two',
                '3' => 'Three', '4' => 'Four', '5' => 'Five',
                '6' => 'Six', '7' => 'Seven', '8' => 'Eight',
                '9' => 'Nine', '10' => 'Ten', '11' => 'Eleven',
                '12' => 'Twelve', '13' => 'Thirteen', '14' => 'Fourteen',
                '15' => 'Fifteen', '16' => 'Sixteen', '17' => 'Seventeen',
                '18' => 'Eighteen', '19' => 'Nineteen', '20' => 'Twenty',
                '30' => 'Thirty', '40' => 'Forty', '50' => 'Fifty', '60' => 'Sixty',
                '70' => 'Seventy', '80' => 'Eighty', '90' => 'Ninety'
            );

            if ($number <= 20) {
                return $words[$number];
            }
            elseif ($number < 100) {
                return $words[10 * floor($number / 10)]
                    . ($number % 10 > 0 ? ' ' . $words[$number % 10] : '');
            }
            else {
                $output = '';
                if ($number >= 1000000000) {
                    $output .= convert_to_words_array(floor($number / 1000000000))
                        . ' Billion ';
                    $number %= 1000000000;
                }
                if ($number >= 1000000) {
                    $output .= convert_to_words_array(floor($number / 1000000))
                        . ' Million ';
                    $number %= 1000000;
                }
                if ($number >= 100000) {
                    $output .= convert_to_words_array(floor($number / 100000))
                        . ' Lakh ';
                    $number %= 100000;
                }
                if ($number >= 1000) {
                    $output .= convert_to_words_array(floor($number / 1000))
                        . ' Thousand ';
                    $number %= 1000;
                }
                if ($number >= 100) {
                    $output .= convert_to_words_array(floor($number / 100))
                        . ' Hundred ';
                    $number %= 100;
                }
                if ($number > 0) {
                    $output .= ($number <= 20) ? $words[$number] :
                    $words[10 * floor($number / 10)] . ' '
                        . ($number % 10 > 0 ? $words[$number % 10] : '');
                }
                return trim($output); 
            }
        }
        
        ?>


        <!-- Total Summary -->
        <div class="invoice-footer">
            <p><strong>Total Taxable Amount:</strong> ₹<?php echo number_format($grandTaxableValue, 2); ?></p>
            <p><strong>Total Tax Amount:</strong> ₹<?php echo number_format($grandTaxAmount, 2); ?></p>
            <p><strong>Total Invoice Amount:</strong> ₹ <?php echo number_format($grandtotalinvestamount, 2); ?></p>
             <p><strong>Amount in Words:</strong><?php echo " ".convert_to_words_array($grandtotalinvestamount). " ". "Only"; ?></p>
            <p><strong>Amount of Tax to be paid by 'Supplier' / 'Receiver' </strong> <?php echo ""; ?></p>

        </div>

<br><br>
        <div class="signature-section">
            <p>Authorized Signatory</p>
            <p><strong>The Calcutta Gujarati Education Society</strong></p>
        </div>
    </div>
</body>

</html>