<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#424242" />
    <title><?php echo $setting->name; ?></title>
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/style-main.css">
    <style type="text/css">
        .table2 tr.border_bottom td {
            box-shadow: none;
            border-radius: 0;
            border-bottom: 1px solid #e6e6e6;
        }

        .table2 td {
            padding-bottom: 3px;
            padding-top: 6px;
        }

        .title {
            color: #0084B4;
            font-weight: 600 !important;
            font-size: 15px !important;
            display: inline;
        }

        .product-description {
            display: block;
            color: #999;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .text-fine {
            color: #bf4f4d;
        }
    </style>
</head>

<body style="background: #ededed;">
    <div class="container">
        <div class="row">
            <div class="paddtop20">
                <div class="col-md-8 col-md-offset-2 text-center">
                    <img src="<?php echo base_url('uploads/school_content/logo/' . $setting->image); ?>">
                </div>
                <div class="col-md-6 col-md-offset-3 mt20">
                    <div class="paymentbg">
                        <div class="invtext"><?php echo $this->lang->line('payment_details'); ?> </div>
                        <div class="padd2 paddtzero">
                            <form action="#" method="post">
                                <table class="table2" width="100%">
                                    <tr>
                                        <th><?php echo $this->lang->line('description'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('amount') ?></th>
                                    </tr>

                                    <tr class="border_bottom">
                                        <td>
                                            <span class="title"><?php echo $this->lang->line('online_admission_form_fees') . ' ( ' . $name . ')';  ?></span>
                                        </td>
                                        <td class="text-right"><?php echo $setting->currency_symbol . number_format((float) $amount, 2, '.', ''); ?></td>
                                    </tr>
                                    <tr class="bordertoplightgray">
                                        <td colspan="2" class="text-right"><?php echo $this->lang->line('total'); ?>: <?php echo $setting->currency_symbol . number_format((float)($amount), 2, '.', ''); ?></td>
                                    </tr>
                                    <tr class="bordertoplightgray">
                                        <td bgcolor="#fff"><button type="submit" onclick="window.history.go(-1); return false;" name="search" value="" class="btn btn-info"><i class="fa fa fa-chevron-left"></i> <?php echo $this->lang->line('back'); ?> </button> </td>
                                        <td bgcolor="#fff" class="text-right"> <button type="button" id="pay_btn" onclick="pay()" name="search" value="" class="btn btn-success"> <?php echo $this->lang->line('pay_with_razorpay'); ?> <i class="fa fa fa-chevron-right"></i></button> </td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

    <script>
        var SITEURL = "<?php echo base_url(); ?>";
        var defaultRef = "<?php echo isset($reference_no) ? $reference_no : ''; ?>";

        function pay(e) {
            var totalAmount = <?php echo $total; ?>;
            var product_id = <?php echo $merchant_order_id; ?>;
            var options = {
                "key": "<?php echo $key_id; ?>",
                "amount": "<?php echo $total; ?>",
                "name": "<?php echo addslashes($name); ?>",
                "description": "<?php echo addslashes($title); ?>",
                "currency": "<?php echo $currency_code; ?>",
                <?php if (!empty($order_id)) { ?>
                "order_id": "<?php echo $order_id; ?>",
                <?php } ?>
                "image": "<?php echo base_url('uploads/school_content/logo/' . $setting->image); ?>",
                "handler": function(response) {
                    $('#pay_btn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Verifying Payment...');
                    $.ajax({
                        url: '<?php echo $return_url; ?>',
                        type: 'post',
                        dataType: 'json',
                        data: {
                            razorpay_payment_id: response.razorpay_payment_id,
                            razorpay_order_id: response.razorpay_order_id || '<?php echo !empty($order_id) ? $order_id : ""; ?>',
                            razorpay_signature: response.razorpay_signature || '',
                            totalAmount: totalAmount,
                            product_id: product_id
                        },
                        success: function(res) {
                            if (typeof res === 'object' && res.status === 'success') {
                                window.location.assign(SITEURL + 'onlineadmission/checkout/successinvoice/' + res.reference_no);
                            } else if (typeof res === 'string' && res.length > 0 && res.indexOf('{') === -1) {
                                window.location.assign(SITEURL + 'onlineadmission/checkout/successinvoice/' + res);
                            } else {
                                var ref = (res && res.reference_no) ? res.reference_no : defaultRef;
                                window.location.assign(SITEURL + 'onlineadmission/checkout/paymentfailed/' + ref);
                            }
                        },
                        error: function() {
                            window.location.assign(SITEURL + 'onlineadmission/checkout/paymentfailed/' + defaultRef);
                        }
                    });
                },
                "modal": {
                    "ondismiss": function() {
                        $('#pay_btn').prop('disabled', false).html('<?php echo $this->lang->line('pay_with_razorpay'); ?> <i class="fa fa fa-chevron-right"></i>');
                    }
                },
                "theme": {
                    "color": "#528FF0"
                }
            };
            var rzp1 = new Razorpay(options);
            rzp1.open();
        }
    </script>
</body>

</html>
