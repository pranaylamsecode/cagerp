<div class="content-wrapper">

    <section class="content-header">

        <h1>

            <i class="fa fa-gears"></i> <?php echo $this->lang->line('system_settings'); ?></h1>

    </section>



    <!-- Main content -->

    <section class="content">

        <div class="row">

            <div class="col-md-10">

                <div class="nav-tabs-custom box box-primary theme-shadow">

                     <div class="box-header with-border">

                       <h3 class="box-title titlefix"><?php echo $this->lang->line('payment_methods'); ?></h3>

                    </div> 

                    <?php
                    $radio_check = check_selected($paymentlist);
                    if (empty($radio_check) || $radio_check == 'none') {
                        $active_tab = 'razorpay';
                    } else {
                        $active_tab = $radio_check;
                    }
                    ?>

                    <ul class="nav nav-tabs nav-tabs2">
                        <li class="<?php echo ($active_tab == 'razorpay') ? 'active' : ''; ?>"><a href="#tab_7" data-gateway="razorpay" data-toggle="tab"><i class="fa fa-credit-card text-success"></i> <?php echo $this->lang->line('razorpay'); ?></a></li>
                        <li class="<?php echo ($active_tab == 'atom') ? 'active' : ''; ?>"><a href="#tab_16" data-gateway="atom" data-toggle="tab"><i class="fa fa-university text-info"></i> Atom</a></li>
                        <li class="<?php echo ($active_tab == 'ccavenue') ? 'active' : ''; ?>"><a href="#tab_4" data-gateway="ccavenue" data-toggle="tab"><?php echo $this->lang->line('ccavenue'); ?></a></li>
                        <li class="<?php echo ($active_tab == 'payu') ? 'active' : ''; ?>"><a href="#tab_3" data-gateway="payu" data-toggle="tab"><?php echo $this->lang->line('payu'); ?></a></li>
                        <li class="<?php echo ($active_tab == 'paytm') ? 'active' : ''; ?>"><a href="#tab_8" data-gateway="paytm" data-toggle="tab"><?php echo $this->lang->line('paytm'); ?></a></li>
                        <li class="<?php echo ($active_tab == 'paypal') ? 'active' : ''; ?>"><a href="#tab_1" data-gateway="paypal" data-toggle="tab"><?php echo $this->lang->line('paypal');?></a></li>
                        <li class="<?php echo ($active_tab == 'stripe') ? 'active' : ''; ?>"><a href="#tab_2" data-gateway="stripe" data-toggle="tab"><?php echo $this->lang->line('stripe');?></a></li>
                        <li class="<?php echo ($active_tab == 'instamojo') ? 'active' : ''; ?>"><a href="#tab_5" data-gateway="instamojo" data-toggle="tab"><?php echo $this->lang->line('instamojo'); ?></a></li>
                        <li class="<?php echo ($active_tab == 'paystack') ? 'active' : ''; ?>"><a href="#tab_6" data-gateway="paystack" data-toggle="tab"><?php echo $this->lang->line('paystack'); ?></a></li>
                        <li class="<?php echo ($active_tab == 'midtrans') ? 'active' : ''; ?>"><a href="#tab_9" data-gateway="midtrans" data-toggle="tab"><?php echo $this->lang->line('midtrans'); ?></a></li>
                        <li class="<?php echo ($active_tab == 'pesapal') ? 'active' : ''; ?>"><a href="#tab_10" data-gateway="pesapal" data-toggle="tab"><?php echo $this->lang->line('pesapal'); ?></a></li>
                        <li class="<?php echo ($active_tab == 'flutterwave') ? 'active' : ''; ?>"><a href="#tab_11" data-gateway="flutterwave" data-toggle="tab"><?php echo $this->lang->line('flutter_wave'); ?> </a></li>
                        <li class="<?php echo ($active_tab == 'ipayafrica') ? 'active' : ''; ?>"><a href="#tab_12" data-gateway="ipayafrica" data-toggle="tab"><?php echo $this->lang->line('ipay_africa'); ?></a></li>
                        <li class="<?php echo ($active_tab == 'jazzcash') ? 'active' : ''; ?>"><a href="#tab_13" data-gateway="jazzcash" data-toggle="tab"><?php echo $this->lang->line('jazzcash'); ?></a></li>
                        <li class="<?php echo ($active_tab == 'billplz') ? 'active' : ''; ?>"><a href="#tab_14" data-gateway="billplz" data-toggle="tab"><?php echo $this->lang->line('billplz'); ?></a></li>
                        <li class="<?php echo ($active_tab == 'sslcommerz') ? 'active' : ''; ?>"><a href="#tab_15" data-gateway="sslcommerz" data-toggle="tab"><?php echo $this->lang->line('sslcommerz'); ?></a></li>
                    </ul> 

                    <div class="tab-content pb0">

                        <div class="tab-pane <?php echo ($active_tab == 'paypal') ? 'active' : ''; ?>" id="tab_1">

                            <form role="form" id="paypal" action="<?php echo site_url('admin/paymentsettings/paypal') ?>" class="form-horizontal" method="post">

                                <div class="box-body">

                                    <div class="row">

                                            <div class="col-md-7">

                                                <?php

                                                $paypal_result = check_in_array('paypal', $paymentlist);

                                                ?>

                                                <div class="form-group">

                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">

                                                        <?php echo $this->lang->line('paypal_username'); ?><small class="req"> *</small>

                                                    </label>

                                                    <div class="col-md-7 col-sm-7 col-xs-12">

                                                        <input autofocus="" id="name" name="paypal_username" placeholder="" type="text" class="form-control col-md-7 col-xs-12" value="<?php echo isset($paypal_result->api_username) ? $paypal_result->api_username : ""; ?>" />

                                                        <span class=" text text-danger paypal_username_error"></span>

                                                    </div>

                                                </div>

                                                <div class="form-group">

                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">

                                                        <?php echo $this->lang->line('paypal_password'); ?><small class="req"> *</small>

                                                    </label>

                                                    <div class="col-md-7 col-sm-7 col-xs-12">

                                                        <input id="name" name="paypal_password" placeholder="" type="password" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($paypal_result->api_password) ? $paypal_result->api_password : ""; ?>" />

                                                        <span class=" text text-danger paypal_password_error"></span>

                                                    </div></div>

                                                <div class="form-group">

                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">

                                                        <?php echo $this->lang->line('paypal_signature'); ?><small class="req"> *</small>

                                                    </label>

                                                    <div class="col-md-7 col-sm-7 col-xs-12">

                                                        <input id="name" name="paypal_signature" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($paypal_result->api_signature) ? $paypal_result->api_signature : ""; ?>" />

                                                        <span class=" text text-danger paypal_signature_error"></span>

                                                    </div>  </div>





                                            </div>

                                            <div class="col-md-5 text text-center disblock">

                                                <a href="https://www.paypal.com/in/home" target="_blank">

                                                    <h5><?php echo $this->lang->line('multinational_payment_gateway');?></h5>

                                                    <img src="<?php echo base_url() ?>backend/images/paypal.png" width="200"><p>https://www.paypal.com</p></a>

                                            </div>

                                    </div>

                                </div>

                                <!-- /.box-body -->

                                <div class="box-footer">

                                    <div class="row">

                                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">

                                            <?php if ($this->rbac->hasPrivilege('payment_methods', 'can_edit')) { ?>

                                            

                                                <button type="submit" class="btn btn-primary paypal_save" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('save'); ?>"><?php echo $this->lang->line('save'); ?></button>  

                                            <?php } ?>

                                        </div> 

                                    </div>    

                                </div>

                            </form>



                        </div>

                        <!-- /.tab-pane -->

                        <div class="tab-pane" id="tab_2">

                            <form role="form" id="stripe" id="stripe" action="<?php echo site_url('admin/paymentsettings/stripe') ?>" class="form-horizontal" method="post">

                                <div class="box-body minheight149">

                                    <div class="row">

                                            <div class="col-md-7">

                                                <?php

                                                $stripe_result = check_in_array('stripe', $paymentlist);

                                                ?>

                                                <div class="form-group">

                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('stripe_api_secret_key'); ?><small class="req"> *</small></label>

                                                    <div class="col-sm-7">

                                                        <input type="text" class="form-control" name="api_secret_key" value="<?php echo isset($stripe_result->api_secret_key) ? $stripe_result->api_secret_key : ""; ?>">

                                                        <span class=" text text-danger api_secret_key_error"></span>

                                                    </div>

                                                </div>

                                                <div class="form-group">

                                                    <label class="col-sm-5 control-label">

                                                        <?php echo $this->lang->line('stripe_publishable_key'); ?><small class="req"> *</small></label>

                                                    <div class="col-sm-7">

                                                        <input type="text" class="form-control" name="api_publishable_key" value="<?php echo isset($stripe_result->api_publishable_key) ? $stripe_result->api_publishable_key : ""; ?>">

                                                        <span class=" text text-danger api_publishable_key_error"></span>

                                                    </div>

                                                </div>



                                            </div>

                                            <div class="col-md-5 text text-center disblock">

                                                <a href="https://stripe.com/" target="_blank">

                                                    <h5><?php echo $this->lang->line('multinational_payment_gateway');?></h5>

                                                    <img src="<?php echo base_url() ?>backend/images/stripe.png"><p>https://stripe.com</p></a>

                                            </div>

                                    </div>



                                </div>

                                <!-- /.box-body -->

                                <div class="box-footer">

                                    <div class="row">

                                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">

                                            <?php if ($this->rbac->hasPrivilege('payment_methods', 'can_edit')) { ?>

                                                <button type="submit" class="btn btn-primary stripe_save" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('save'); ?>"><?php echo $this->lang->line('save'); ?></button>

                                            <?php } ?>

                                        </div>

                                    </div>        

                                </div>

                            </form>



                        </div>

                        <!-- /.tab-pane -->

                        <div class="tab-pane" id="tab_3">

                            <form role="form" id="payu" id="custom" action="<?php echo site_url('admin/paymentsettings/payu') ?>" class="form-horizontal" method="post">

                                <div class="box-body minheight149">

                                    <div class="row">

                                            <div class="col-md-7">

                                                <?php

                                                $payu_result = check_in_array('payu', $paymentlist);

                                                ?>

                                                <div class="form-group">

                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('payu_money_key'); ?><small class="req"> *</small>

                                                    </label>

                                                    <div class="col-sm-7">

                                                        <input type="text" class="form-control" name="key" value="<?php echo isset($payu_result->api_secret_key) ? $payu_result->api_secret_key : ""; ?>">

                                                        <span class="text text-danger key_error"></span>

                                                    </div>

                                                </div>

                                                <div class="form-group">

                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('payu_money_salt'); ?><small class="req"> *</small>

                                                    </label>

                                                    <div class="col-sm-7">

                                                        <input type="text" class="form-control" name="salt" value="<?php echo isset($payu_result->salt) ? $payu_result->salt : ""; ?>">

                                                        <span class="text text-danger salt_error"></span>

                                                    </div>

                                                </div>



                                            </div>

                                            <div class="col-md-5 text text-center disblock">

                                                <a href="https://www.payumoney.com" target="_blank">

                                                    <h5><?php echo $this->lang->line('payment_gateway_for_india');?></h5>

                                                    <img src="<?php echo base_url() ?>backend/images/paym.png"><p>https://www.payumoney.com</p></a>



                                            </div>

                                    </div>



                                </div>

                                <!-- /.box-body -->



                                <div class="box-footer">

                                    <div class="row">

                                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">

                                            <?php if ($this->rbac->hasPrivilege('payment_methods', 'can_edit')) { ?>

                                                <button type="submit" class="btn btn-primary payu_save" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('save'); ?>"><?php echo $this->lang->line('save'); ?></button>

                                            <?php } ?>

                                        </div>

                                    </div>        

                                </div>

                            </form>



                        </div>

                        <!-- /.tab-pane -->



                        <div class="tab-pane" id="tab_4">

                            <form role="form" id="ccavenue"  action="<?php echo site_url('admin/paymentsettings/ccavenue') ?>" class="form-horizontal" method="post">

                                <div class="box-body minheight149">

                                    <div class="row">

                                            <div class="col-md-7">

                                                <?php

                                                $ccavenue_result = check_in_array('ccavenue', $paymentlist);

                                                ?>

                                                <div class="form-group">

                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('merchant_id'); ?><small class="req"> *</small>

                                                    </label>

                                                    <div class="col-sm-7">

                                                        <input type="text" class="form-control" name="ccavenue_secret" value="<?php echo isset($ccavenue_result->api_secret_key) ? $ccavenue_result->api_secret_key : ""; ?>">

                                                        <span class="text text-danger ccavenue_secret_error"></span>

                                                    </div>

                                                </div>

                                                <div class="form-group">

                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('working_key'); ?><small class="req"> *</small>

                                                    </label>

                                                    <div class="col-sm-7">

                                                        <input type="text" class="form-control" name="ccavenue_salt" value="<?php echo isset($ccavenue_result->salt) ? $ccavenue_result->salt : ""; ?>">

                                                        <span class="text text-danger ccavenue_salt_error"></span>

                                                    </div> 

                                                </div>

                                                <div class="form-group">

                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('access_code'); ?><small class="req"> *</small>

                                                    </label>

                                                    <div class="col-sm-7">

                                                        <input type="text" class="form-control" name="ccavenue_api_publishable_key" value="<?php echo isset($ccavenue_result->api_publishable_key) ? $ccavenue_result->api_publishable_key : ""; ?>">

                                                        <span class="text text-danger ccavenue_api_publishable_key_error"></span>

                                                    </div>

                                                </div>



                                            </div>

                                            <div class="col-md-5 text text-center disblock">

                                                <a href="https://www.ccavenue.com" target="_blank">

                                                    <h5><?php echo $this->lang->line('payment_gateway_for_india');?></h5>

                                                    <img src="<?php echo base_url() ?>backend/images/ccavenue.png" width="200"><p>https://www.ccavenue.com</p></a>

                                            </div>

                                    </div>



                                </div>

                                <!-- /.box-body -->

                                <div class="box-footer">

                                    <div class="row">

                                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">

                                            <?php if ($this->rbac->hasPrivilege('payment_methods', 'can_edit')) { ?>

                                                <button type="submit" class="btn btn-primary ccavenue_save" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('save'); ?>"><?php echo $this->lang->line('save'); ?></button>

                                            <?php } ?>

                                        </div>

                                    </div>        

                                </div>

                            </form>



                        </div>



                        <div class="tab-pane" id="tab_5">

                            <form role="form" id="instamojo"  action="<?php echo site_url('admin/paymentsettings/instamojo') ?>" class="form-horizontal" method="post">

                                <div class="box-body minheight149">

                                    <div class="row">

                                            <div class="col-md-7">

                                                <?php

                                                $instamojo_result = check_in_array('instamojo', $paymentlist);

                                                ?>

                                                <div class="form-group">

                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('private_api_key'); ?><small class="req"> *</small>

                                                    </label>

                                                    <div class="col-sm-7">

                                                        <input type="text" class="form-control" name="instamojo_apikey" value="<?php echo isset($instamojo_result->api_secret_key) ? $instamojo_result->api_secret_key : ""; ?>">

                                                        <span class="text text-danger instamojo_apikey_error"></span>

                                                    </div>

                                                </div>

                                                <div class="form-group">

                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('private_auth_token'); ?><small class="req"> *</small>

                                                    </label>

                                                    <div class="col-sm-7">

                                                        <input type="text" class="form-control" name="instamojo_authtoken" value="<?php echo isset($instamojo_result->api_publishable_key) ? $instamojo_result->api_publishable_key : ""; ?>">

                                                        <span class="text text-danger instamojo_authtoken_error"></span>

                                                    </div>

                                                </div>

                                                <div class="form-group">

                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('private_salt'); ?><small class="req"> *</small>

                                                    </label>

                                                    <div class="col-sm-7">

                                                        <input type="text" class="form-control" name="instamojo_salt" value="<?php echo isset($instamojo_result->salt) ? $instamojo_result->salt : ""; ?>">

                                                        <span class="text text-danger instamojo_salt_error"></span>

                                                    </div>

                                                </div>



                                            </div>

                                            <div class="col-md-5 text text-center disblock">

                                                <a href="https://www.instamojo.com/" target="_blank">

                                                    <h5><?php echo $this->lang->line('payment_gateway_for_india');?></h5>

                                                    <img src="<?php echo base_url() ?>backend/images/instamojo.png" width="200"><p>https://www.instamojo.com/</p></a>

                                            </div>

                                    </div>



                                </div>

                                <!-- /.box-body -->

                                <div class="box-footer">

                                    <div class="row">

                                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">

                                            <?php if ($this->rbac->hasPrivilege('payment_methods', 'can_edit')) { ?>

                                                <button type="submit" class="btn btn-primary instamojo_save" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('save'); ?>"><?php echo $this->lang->line('save'); ?></button>

                                            <?php } ?>

                                        </div>

                                    </div>        

                                </div>

                            </form>



                        </div>



                        <div class="tab-pane " id="tab_6">

                            <form role="form" id="paystack" action="<?php echo site_url('admin/paymentsettings/paystack') ?>" class="form-horizontal" method="post">

                                <div class="box-body">

                                    <div class="row">

                                            <div class="col-md-7">

                                                <?php

                                                $paystack_result = check_in_array('paystack', $paymentlist);

                                                //print_r($paystack_result);die;

                                                ?>



                                                <div class="form-group">

                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">

                                                        <?php echo $this->lang->line('paystack_secret_key'); ?><small class="req"> *</small>

                                                    </label>

                                                    <div class="col-md-7 col-sm-7 col-xs-12">

                                                        <input  name="paystack_secretkey" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($paystack_result->api_secret_key) ? $paystack_result->api_secret_key : ""; ?>" />

                                                        <span class=" text text-danger paystack_secretkey_error"></span>

                                                    </div>  </div>





                                            </div>

                                            <div class="col-md-5 text text-center disblock">

                                                <a href="https://paystack.com/" target="_blank">

                                                    <h5><?php echo $this->lang->line('payment_gateway_for_afirican_countries');?></h5>

                                                    <img src="<?php echo base_url(); ?>/backend/images/paystack.png" width="200"><p>https://paystack.com</p></a>

                                            </div>

                                    </div>

                                </div>

                                <!-- /.box-body -->

                                <div class="box-footer">

                                    <div class="row">

                                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">

                                            <?php if ($this->rbac->hasPrivilege('payment_methods', 'can_edit')) { ?>

                                                <button type="submit" class="btn btn-primary paystack_save" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('save'); ?>"><?php echo $this->lang->line('save'); ?></button>

                                            <?php } ?>

                                        </div>

                                    </div>        

                                </div>

                            </form>



                        </div>



                        <div class="tab-pane <?php echo ($active_tab == 'razorpay') ? 'active' : ''; ?>" id="tab_7">

                            <form role="form" id="razorpay" action="<?php echo site_url('admin/paymentsettings/razorpay') ?>" class="form-horizontal" method="post">

                                <div class="box-body">

                                    <div class="row">

                                            <div class="col-md-7">

                                                <?php

                                                $razorpay_result = check_in_array('razorpay', $paymentlist);

                                                //print_r($paystack_result);die;

                                                ?>



                                                <div class="form-group">

                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">

                                                        <?php echo $this->lang->line('razorpay_key_id'); ?><small class="req"> *</small>

                                                    </label>

                                                    <div class="col-md-7 col-sm-7 col-xs-12">

                                                        <input  name="razorpay_keyid" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($razorpay_result->api_publishable_key) ? $razorpay_result->api_publishable_key : ""; ?>" />

                                                        <span class=" text text-danger razorpay_keyid_error"></span>

                                                    </div>  </div>

                                                <div class="form-group">

                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">

                                                        <?php echo $this->lang->line('razorpay_key_secret'); ?><small class="req"> *</small>

                                                    </label>

                                                    <div class="col-md-7 col-sm-7 col-xs-12">

                                                        <input  name="razorpay_secretkey" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($razorpay_result->api_secret_key) ? $razorpay_result->api_secret_key : ""; ?>" />

                                                        <span class=" text text-danger razorpay_secretkey_error"></span>

                                                    </div>  </div>







                                            </div>

                                            <div class="col-md-5 text text-center disblock">

                                                <a href="https://razorpay.com/" target="_blank">

                                                    <h5><?php echo $this->lang->line('payment_gateway_for_india');?></h5>

                                                    <img src="<?php echo base_url(); ?>/backend/images/razorpay.jpg" width="200"><p>https://razorpay.com/</p></a>

                                            </div>

                                    </div>

                                </div>

                                <!-- /.box-body -->

                                <div class="box-footer">

                                    <div class="row">

                                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">

                                            <?php if ($this->rbac->hasPrivilege('payment_methods', 'can_edit')) { ?>

                                                <button type="submit" class="btn btn-primary razorpay_save" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('save'); ?>"><?php echo $this->lang->line('save'); ?></button>

                                            <?php } ?>

                                        </div>

                                    </div>        

                                </div>

                            </form>

                            <!-- ============================================== -->
                            <!-- Razorpay Live Gateway Verification & Test Suite -->
                            <!-- ============================================== -->
                            <div class="box box-solid box-default" style="margin-top: 25px; border: 1px solid #00a65a; border-radius: 4px;">
                                <div class="box-header with-border" style="background: #f4faf6; border-bottom: 1px solid #d2e8d9;">
                                    <h4 class="box-title" style="color: #00733e; font-weight: 600;">
                                        <i class="fa fa-flask"></i> Live Razorpay Gateway Test Suite & Verification Sandbox
                                    </h4>
                                    <div class="box-tools pull-right">
                                        <span class="label label-success" style="font-size: 11px;"><i class="fa fa-circle"></i> Live Gateway Testing</span>
                                    </div>
                                </div>
                                <div class="box-body" style="padding: 20px;">
                                    <p class="text-muted" style="margin-bottom: 20px;">
                                        Use this sandbox to test if your live Razorpay credentials (Key ID & Key Secret) are working properly. You can perform an <strong>instant API connection check</strong> or pay a <strong>live ₹1.00 test transaction</strong> via UPI (Google Pay, PhonePe, Paytm, QR) or Card using the exact same payment flow students use. All test payments will be verified and recorded into your database.
                                    </p>

                                    <!-- Step 1 & Step 2 Columns -->
                                    <div class="row">
                                        <!-- Test 1: API Connection Ping -->
                                        <div class="col-md-5">
                                            <div class="well well-sm" style="background: #fff; border-left: 4px solid #00c0ef;">
                                                <h5 style="margin-top: 5px; font-weight: bold; color: #0073b7;">
                                                    <i class="fa fa-plug"></i> Test 1: Fast API Connection Check
                                                </h5>
                                                <p style="font-size: 12px; color: #666;">
                                                    Directly contacts Razorpay servers using your saved credentials to verify if your account is active and keys are valid.
                                                </p>
                                                <button type="button" class="btn btn-info btn-sm" id="btn-ping-razorpay">
                                                    <i class="fa fa-refresh"></i> Check Razorpay API Connection
                                                </button>
                                                <div id="ping-result-box" style="margin-top: 10px; display: none;"></div>
                                            </div>
                                        </div>

                                        <!-- Test 2: ₹1 Live Payment Test -->
                                        <div class="col-md-7">
                                            <div class="well well-sm" style="background: #fff; border-left: 4px solid #00a65a;">
                                                <h5 style="margin-top: 5px; font-weight: bold; color: #00733e;">
                                                    <i class="fa fa-credit-card"></i> Test 2: Real ₹1 Live Payment Test (End-to-End)
                                                </h5>
                                                <p style="font-size: 12px; color: #666;">
                                                    Launches live Razorpay Checkout modal. You can pay <strong>₹1</strong> via UPI QR code or Card. The server will verify the HMAC signature, confirm capture, and save the transaction into the database table <code>gateway_test_transactions</code>.
                                                </p>

                                                <div class="form-inline" style="margin-bottom: 10px;">
                                                    <div class="form-group" style="margin-right: 10px;">
                                                        <label style="font-size: 12px;">Amount (₹): </label>
                                                        <input type="number" id="test-pay-amount" class="form-control input-sm" value="1.00" min="1" step="1" style="width: 80px; text-align: center; font-weight: bold;">
                                                    </div>
                                                    <div class="form-group" style="margin-right: 10px;">
                                                        <label style="font-size: 12px;">Payer Name: </label>
                                                        <input type="text" id="test-pay-name" class="form-control input-sm" value="Admin Test" style="width: 110px;">
                                                    </div>
                                                    <button type="button" class="btn btn-success btn-sm" id="btn-start-test-payment" style="font-weight: 600;">
                                                        <i class="fa fa-bolt"></i> Pay Test Amount via Razorpay
                                                    </button>
                                                </div>

                                                <div id="test-payment-result-box" style="margin-top: 10px; display: none;"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Recent Test Transactions Table -->
                                    <div style="margin-top: 15px;">
                                        <h5 style="font-weight: bold; border-bottom: 2px solid #eee; padding-bottom: 8px;">
                                            <i class="fa fa-history"></i> Recent Gateway Test Transactions in Database (<code>gateway_test_transactions</code>)
                                        </h5>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped table-hover" id="test-trans-table">
                                                <thead>
                                                    <tr class="bg-gray-light">
                                                        <th style="width: 50px;">ID</th>
                                                        <th>Date & Time</th>
                                                        <th>Payment ID</th>
                                                        <th>Order ID</th>
                                                        <th>Amount</th>
                                                        <th>Method / Mode</th>
                                                        <th>Bank / VPA</th>
                                                        <th>Status</th>
                                                        <th>Signature</th>
                                                        <th class="text-right">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="test-trans-tbody">
                                                    <?php if (!empty($test_transactions)) {
                                                        foreach ($test_transactions as $t) {
                                                    ?>
                                                        <tr>
                                                            <td>#<?php echo $t['id']; ?></td>
                                                            <td><?php echo date('d-m-Y h:i A', strtotime($t['created_at'])); ?></td>
                                                            <td><code><?php echo htmlspecialchars($t['payment_id']); ?></code></td>
                                                            <td><small><?php echo htmlspecialchars($t['order_id']); ?></small></td>
                                                            <td><strong>₹<?php echo number_format($t['amount'], 2); ?></strong></td>
                                                            <td><span class="badge bg-navy"><?php echo strtoupper($t['method']); ?></span></td>
                                                            <td><small><?php echo htmlspecialchars(!empty($t['vpa']) ? $t['vpa'] : (!empty($t['bank']) ? $t['bank'] : 'N/A')); ?></small></td>
                                                            <td><span class="label label-success"><?php echo strtoupper($t['status']); ?></span></td>
                                                            <td>
                                                                <?php if ($t['is_signature_verified'] == 1) { ?>
                                                                    <span class="label label-success"><i class="fa fa-check"></i> Verified</span>
                                                                <?php } else { ?>
                                                                    <span class="label label-default">N/A</span>
                                                                <?php } ?>
                                                            </td>
                                                            <td class="text-right">
                                                                <button type="button" class="btn btn-xs btn-default" onclick="viewTestPayload(<?php echo $t['id']; ?>)" title="View Raw Gateway Response">
                                                                    <i class="fa fa-eye"></i> Response
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    <?php } } else { ?>
                                                        <tr id="no-test-trans-row"><td colspan="10" class="text-center text-muted">No test transactions yet. Click "Pay Test Amount via Razorpay" above to run your first test!</td></tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>



                        </div>



                        <div class="tab-pane" id="tab_8">

                            <form role="form" id="paytm" action="<?php echo site_url('admin/paymentsettings/paytm') ?>" class="form-horizontal" method="post">

                                <div class="box-body">

                                    <div class="row">

                                            <div class="col-md-7">

                                                <?php

                                                $paytm_result = check_in_array('paytm', $paymentlist);

                                                ?> 



                                                <div class="form-group">

                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">

                                                        <?php echo $this->lang->line('paytm_merchant_id'); ?>

                                                        <small class="req"> *</small></label>

                                                    <div class="col-md-7 col-sm-7 col-xs-12">

                                                        <input  name="paytm_merchantid" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($paytm_result->api_publishable_key) ? $paytm_result->api_publishable_key : ""; ?>" />

                                                        <span class=" text text-danger paytm_merchantid_error"></span>

                                                    </div>  </div>

                                                <div class="form-group">

                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">

                                                        <?php echo $this->lang->line('paytm_merchant_key'); ?>

                                                        <small class="req"> *</small></label>

                                                    <div class="col-md-7 col-sm-7 col-xs-12">

                                                        <input  name="paytm_merchantkey" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($paytm_result->api_secret_key) ? $paytm_result->api_secret_key : ""; ?>" />

                                                        <span class=" text text-danger paytm_merchantkey_error"></span>

                                                    </div>  </div>

                                                <div class="form-group">

                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">

                                                        <?php echo $this->lang->line('paytm_website'); ?>

                                                        <small class="req"> *</small></label>

                                                    <div class="col-md-7 col-sm-7 col-xs-12">

                                                        <input  name="paytm_website" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($paytm_result->paytm_website) ? $paytm_result->paytm_website : ""; ?>" />

                                                        <span class=" text text-danger paytm_website_error"></span>

                                                    </div>  </div>

                                                <div class="form-group">

                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">

                                                        <?php echo $this->lang->line('indusrty_type'); ?>

                                                        <small class="req"> *</small></label>

                                                    <div class="col-md-7 col-sm-7 col-xs-12">

                                                        <input  name="paytm_industrytype" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($paytm_result->paytm_industrytype) ? $paytm_result->paytm_industrytype : ""; ?>" />

                                                        <span class=" text text-danger paytm_industrytype_error"></span>

                                                    </div>  </div>





                                            </div>

                                            <div class="col-md-5 text text-center disblock">

                                                <a href="https://paytm.com/" target="_blank">

                                                    <h5><?php echo $this->lang->line('payment_gateway_for_india');?></h5>

                                                    <img src="<?php echo base_url(); ?>/backend/images/paytm.jpg" width="200"><p>https://paytm.com/</p></a>

                                            </div>

                                    </div>

                                </div> 

                                <!-- /.box-body -->

                                <div class="box-footer">

                                    <div class="row">

                                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">

                                            <?php if ($this->rbac->hasPrivilege('payment_methods', 'can_edit')) { ?>

                                                <button type="submit" class="btn btn-primary paytm_save" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('save'); ?>"><?php echo $this->lang->line('save'); ?></button>

                                            <?php } ?>

                                        </div>

                                    </div>        

                                </div>

                            </form>



                        </div>

                        <!-- /.tab-pane -->

 

                        <div class="tab-pane" id="tab_9">

                            <form role="form" id="midtrans" action="<?php echo site_url('admin/paymentsettings/midtrans') ?>" class="form-horizontal" method="post">

                                <div class="box-body">

                                    <div class="row">

                                        <div class="col-md-7">

                                                <?php

                                                $midtrans_result = check_in_array('midtrans', $paymentlist);

                                                ?>



                                                <div class="form-group">

                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">

                                                        <?php echo $this->lang->line('server_key'); ?>

                                                        <small class="req"> *</small></label>

                                                    <div class="col-md-7 col-sm-7 col-xs-12">

                                                        <input  name="midtrans_serverkey" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($midtrans_result->api_secret_key) ? $midtrans_result->api_secret_key : ""; ?>" />

                                                        <span class=" text text-danger midtrans_serverkey_error"></span>

                                                    </div>  </div>









                                            </div>

                                            <div class="col-md-5 text text-center disblock">

                                                <a href="https://midtrans.com/" target="_blank">

                                                    <h5><?php echo $this->lang->line('payment_gateway_for_indonesia');?></h5>

                                                    <img src="<?php echo base_url(); ?>/backend/images/midtrans.jpg" width="200"><p>https://midtrans.com/</p></a>

                                            </div>

                                    </div>

                                </div>

                                <!-- /.box-body -->

                                <div class="box-footer">

                                    <div class="row">

                                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">

                                            <?php if ($this->rbac->hasPrivilege('payment_methods', 'can_edit')) { ?>

                                                <button type="submit" class="btn btn-primary midtrans_save" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('save'); ?>"><?php echo $this->lang->line('save'); ?></button>

                                            <?php } ?>

                                        </div>

                                    </div>        

                                </div>

                            </form>



                        </div>

                          <div class="tab-pane " id="tab_10">

                            <form role="form" id="pesapal" action="<?php echo site_url('admin/paymentsettings/pesapal') ?>" class="form-horizontal" method="post">

                                <div class="box-body">

                                    <div class="row">

                                            <div class="col-md-7">

                                                <?php

                                                $pesapal_result = check_in_array('pesapal', $paymentlist);

                                               



                                                ?>

                                               

                                                <div class="form-group">

                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">

                                                       <?php echo $this->lang->line('consumer_key'); ?>

                                                    <small class="req"> *</small></label>

                                                    <div class="col-md-7 col-sm-7 col-xs-12">

                                                        <input  name="pesapal_consumer_key" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($pesapal_result->api_publishable_key) ? $pesapal_result->api_publishable_key : ""; ?>" />

                                                        <span class=" text text-danger pesapal_consumer_key_error"></span>

                                                    </div>  </div>

                                                    <div class="form-group">

                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">

                                                       <?php echo $this->lang->line('consumer_secret'); ?>

                                                    <small class="req"> *</small></label>

                                                    <div class="col-md-7 col-sm-7 col-xs-12">

                                                        <input  name="pesapal_consumer_secret" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($pesapal_result->api_secret_key) ? $pesapal_result->api_secret_key : ""; ?>" />

                                                        <span class=" text text-danger pesapal_consumer_secret_error"></span>

                                                    </div>  

                                                </div>



                                            </div>

                                            <div class="col-md-5 text text-center disblock">

                                                <a href="https://www.pesapal.com/" target="_blank">

                                                    <h5><?php echo $this->lang->line('payment_gateway_for_afirican_countries');?></h5>

                                                    <img src="<?php echo base_url();?>/backend/images/pesapal.jpg" width="200"><p>https://www.pesapal.com/</p></a>

                                            </div>

                                    </div>

                                </div>

                                <!-- /.box-body -->

                                <div class="box-footer">

                                    <div class="row">

                                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">

                                            <?php if($this->rbac->hasPrivilege('payment_methods', 'can_edit')){ ?>

                                            <button type="submit" class="btn btn-primary midtrans_save" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('save'); ?>"><?php echo $this->lang->line('save'); ?></button>

                                            <?php } ?>

                                        </div>

                                    </div>        

                                </div>

                            </form>



                        </div>

                         <div class="tab-pane " id="tab_11">

                            <form role="form" id="flutterwave" action="<?php echo site_url('admin/paymentsettings/flutterwave') ?>" class="form-horizontal" method="post">

                                <div class="box-body">

                                    <div class="row">

                                            <div class="col-md-7">

                                                <?php

                                                $flutterwave_result = check_in_array('flutterwave', $paymentlist);

                                               

  

                                                ?>

                                               

                                                <div class="form-group">

                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">

                                                       <?php echo $this->lang->line('public')." ".$this->lang->line('Key'); ?>

                                                    <small class="req"> *</small></label>

                                                    <div class="col-md-7 col-sm-7 col-xs-12">

                                                        <input  name="public_key" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($flutterwave_result->api_publishable_key) ? $flutterwave_result->api_publishable_key : ""; ?>" />

                                                        <span class=" text text-danger public_key_error"></span>

                                                    </div>  </div>

                                                   <div class="form-group">

                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">

                                                       <?php echo $this->lang->line('secret_key'); ?>

                                                    <small class="req"> *</small></label>

                                                    <div class="col-md-7 col-sm-7 col-xs-12">

                                                        <input  name="secret_key" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($flutterwave_result->api_secret_key) ? $flutterwave_result->api_secret_key : ""; ?>" />

                                                        <span class=" text text-danger secret_key_error"></span>

                                                    </div>  

                                                </div>



                                            </div>

                                            <div class="col-md-5 text text-center disblock">

                                                <a href="https://flutterwave.com/us/" target="_blank">

                                                    <h5><?php echo $this->lang->line('multinational_payment_gateway');?></h5>

                                                    <img src="<?php echo base_url();?>/backend/images/flutterwave.png" width="200"><p>https://flutterwave.com/us</p></a>

                                            </div>

                                    </div>

                                </div>

                                <!-- /.box-body -->

                                <div class="box-footer">

                                    <div class="row">

                                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">

                                             <?php if($this->rbac->hasPrivilege('payment_methods', 'can_edit')){ ?>

                                              <button type="submit" class="btn btn-primary midtrans_save" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('save'); ?>"><?php echo $this->lang->line('save'); ?></button>

                                            <?php } ?>

                                        </div>

                                    </div>        

                                </div>

                            </form>



                        </div>

                        <div class="tab-pane " id="tab_12">

                            <form role="form" id="ipayafrica" action="<?php echo site_url('admin/paymentsettings/ipayafrica') ?>" class="form-horizontal" method="post">

                                <div class="box-body">

                                    <div class="row">

                                            <div class="col-md-7">

                                                <?php

                                                $ipayafrica_result = check_in_array('ipayafrica', $paymentlist);                                         



                                                ?>

                                               

                                                <div class="form-group">

                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">

                                                       <?php echo $this->lang->line('vendorid'); ?>

                                                    <small class="req"> *</small></label>

                                                    <div class="col-md-7 col-sm-7 col-xs-12">

                                                        <input  name="ipayafrica_vendorid" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($ipayafrica_result->api_publishable_key) ? $ipayafrica_result->api_publishable_key : ""; ?>" />

                                                        <span class=" text text-danger ipayafrica_vendorid_error"></span>

                                                    </div>  </div>

                                                    <div class="form-group">

                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">

                                                       <?php echo $this->lang->line('hashkey'); ?>

                                                    <small class="req"> *</small></label>

                                                    <div class="col-md-7 col-sm-7 col-xs-12">

                                                        <input  name="ipayafrica_hashkey" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($ipayafrica_result->api_secret_key) ? $ipayafrica_result->api_secret_key : ""; ?>" />

                                                        <span class=" text text-danger ipayafrica_hashkey_error"></span>

                                                    </div>  

                                                </div>

                                            </div>

                                            <div class="col-md-5 text text-center disblock">

                                                <a href="https://ipayafrica.com/" target="_blank">

                                                    <h5><?php echo $this->lang->line('payment_gateway_for_afirican_countries');?></h5>

                                                    <img src="<?php echo base_url();?>/backend/images/ipayafrica.png" width="200"><p>https://ipayafrica.com//</p></a>

                                            </div>

                                    </div>

                                </div>

                                <!-- /.box-body -->

                                <div class="box-footer">

                                    <div class="row">

                                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">

                                            <?php if($this->rbac->hasPrivilege('payment_methods', 'can_edit')){ ?>

                                            <button type="submit" class="btn btn-primary midtrans_save" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('save'); ?>"><?php echo $this->lang->line('save'); ?></button>

                                             <?php } ?>

                                        </div>

                                    </div>         

                                </div>

                            </form>



                        </div>



                        <div class="tab-pane " id="tab_13">

                            <form role="form" id="jazzcash" action="<?php echo site_url('admin/paymentsettings/jazzcash') ?>" class="form-horizontal" method="post">

                                <div class="box-body">

                                    <div class="row">

                                            <div class="col-md-7">

                                                <?php

                                                $jazzcash_result = check_in_array('jazzcash', $paymentlist);

                                                ?>



                                                <div class="form-group">

                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">

                                                        <?php echo $this->lang->line('pp_merchantid'); ?>

                                                        <small class="req"> *</small></label>

                                                    <div class="col-md-7 col-sm-7 col-xs-12">

                                                        <input  name="jazzcash_pp_MerchantID" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($jazzcash_result->api_secret_key) ? $jazzcash_result->api_secret_key : ""; ?>" />

                                                        <span class=" text text-danger jazzcash_pp_MerchantID_error"></span>

                                                    </div>  </div>

                                                     <div class="form-group">

                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">

                                                        <?php echo $this->lang->line('pp_password'); ?>

                                                        <small class="req"> *</small></label>

                                                    <div class="col-md-7 col-sm-7 col-xs-12">

                                                        <input  name="jazzcash_pp_Password" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($jazzcash_result->api_password) ? $jazzcash_result->api_password : ""; ?>" />

                                                        <span class=" text text-danger jazzcash_pp_Password_error"></span>

                                                    </div>  

                                                </div>









                                            </div>

                                            <div class="col-md-5 text text-center disblock">

                                                <a href="https://www.jazzcash.com.pk/" target="_blank">

                                                    <h5><?php echo $this->lang->line('payment_gateway_for_pakistan');?></h5>

                                                    <img src="<?php echo base_url(); ?>/backend/images/jazzcash.jpg" width="200"><p>https://www.jazzcash.com.pk/</p></a>

                                            </div>

                                    </div>

                                </div>

                                <!-- /.box-body -->

                                <div class="box-footer">

                                    <div class="row">

                                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">

                                            <?php if ($this->rbac->hasPrivilege('payment_methods', 'can_edit')) { ?>

                                                <button type="submit" class="btn btn-primary jazzcash_save" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('save'); ?>"><?php echo $this->lang->line('save'); ?></button>

                                            <?php } ?>

                                        </div>

                                    </div>        

                                </div>

                            </form>



                        </div>

                         <div class="tab-pane " id="tab_14">

                            <form role="form" id="billplz" action="<?php echo site_url('admin/paymentsettings/billplz') ?>" class="form-horizontal" method="post">

                                <div class="box-body">

                                    <div class="row">

                                            <div class="col-md-7">

                                                <?php

                                                $billplz_result = check_in_array('billplz', $paymentlist);

                                                ?>



                                                <div class="form-group">

                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">

                                                        <?php echo $this->lang->line('api_key'); ?>

                                                        <small class="req"> *</small></label>

                                                    <div class="col-md-7 col-sm-7 col-xs-12">

                                                        <input  name="billplz_api_key" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($billplz_result->api_secret_key) ? $billplz_result->api_secret_key : ""; ?>" />

                                                        <span class=" text text-danger billplz_api_key_error"></span>

                                                    </div>  </div>

                                                     <div class="form-group">

                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">

                                                         <?php echo $this->lang->line('customer_service_email'); ?>

                                                     

                                                        <small class="req"> *</small></label>

                                                    <div class="col-md-7 col-sm-7 col-xs-12">

                                                        <input  name="billplz_customer_service_email" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($billplz_result->api_email) ? $billplz_result->api_email : ""; ?>" />

                                                        <span class=" text text-danger billplz_customer_service_email_error"></span>

                                                    </div>  </div>

                                            </div>

                                            <div class="col-md-5 text text-center disblock">

                                                <a href="https://www.billplz.com/" target="_blank">

                                                    <h5><?php echo $this->lang->line('payment_gateway_for_malaysia');?></h5>

                                                    <img src="<?php echo base_url(); ?>/backend/images/billplz.jpg" width="200"><p>https://www.billplz.com/</p></a>

                                            </div>

                                    </div>

                                </div>

                                <!-- /.box-body -->

                                <div class="box-footer">

                                    <div class="row">

                                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">

                                            <?php if ($this->rbac->hasPrivilege('payment_methods', 'can_edit')) { ?>

                                                <button type="submit" class="btn btn-primary jazzcash_save" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('save'); ?>"><?php echo $this->lang->line('save'); ?></button>

                                            <?php } ?>

                                        </div>

                                    </div>        

                                </div>

                            </form>



                        </div>



                         <div class="tab-pane " id="tab_15">

                            <form role="form" id="sslcommerz" action="<?php echo site_url('admin/paymentsettings/sslcommerz') ?>" class="form-horizontal" method="post">

                                <div class="box-body">

                                    <div class="row">

                                            <div class="col-md-7">

                                                <?php

                                                $sslcommerz_result = check_in_array('sslcommerz', $paymentlist);

                                                ?>



                                                <div class="form-group">

                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">

                                                        <?php echo $this->lang->line('store_id'); ?>

                                                        <small class="req"> *</small></label>

                                                    <div class="col-md-7 col-sm-7 col-xs-12">

                                                        <input  name="sslcommerz_api_key" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($sslcommerz_result->api_publishable_key) ? $sslcommerz_result->api_publishable_key : ""; ?>" />

                                                        <span class=" text text-danger sslcommerz_api_key_error"></span>

                                                    </div>  </div>

                                                     <div class="form-group">

                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">

                                                         <?php echo $this->lang->line('store_password'); ?>

                                                     

                                                        <small class="req"> *</small></label>

                                                    <div class="col-md-7 col-sm-7 col-xs-12">

                                                        <input  name="sslcommerz_store_password" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($sslcommerz_result->api_password) ? $sslcommerz_result->api_password : ""; ?>" />

                                                        <span class=" text text-danger sslcommerz_store_password_error"></span>

                                                    </div>  </div>

                                            </div>

                                            <div class="col-md-5 text text-center disblock">

                                                <a href="https://www.sslcommerz.com/" target="_blank">

                                                    <h5><?php echo $this->lang->line('payment_gateway_for_bangladesh');?></h5><br>

                                                    <img src="<?php echo base_url(); ?>/backend/images/sslcommerz.png" width="200"><br><br><p>https://www.sslcommerz.com/</p></a>

                                            </div>

                                    </div>

                                </div>

                                <!-- /.box-body -->

                                <div class="box-footer">

                                    <div class="row">

                                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">

                                            <?php if ($this->rbac->hasPrivilege('payment_methods', 'can_edit')) { ?>

                                                <button type="submit" class="btn btn-primary sslcommerz_save" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('save'); ?>"><?php echo $this->lang->line('save'); ?></button>

                                            <?php } ?>

                                        </div>

                                    </div>        

                                </div>

                            </form>



                        </div>

                         <div class="tab-pane <?php echo ($active_tab == 'atom') ? 'active' : ''; ?>" id="tab_16">

                           <form role="form" id="atom" action="<?php echo site_url('admin/paymentsettings/atom') ?>" class="form-horizontal" method="post">

                                <div class="box-body">

                                    <div class="row">

                                            <div class="col-md-7">

                                                <?php

                                                $atom_result = check_in_array('atom', $paymentlist);

                                                //print_r($paystack_result);die;

                                                ?>



                                                <div class="form-group">

                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">Atom Key Id<small class="req"> *</small>

                                                    </label>

                                                    <div class="col-md-7 col-sm-7 col-xs-12">

                                                        <input  name="atom_keyid" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($atom_result->api_publishable_key) ? $atom_result->api_publishable_key : ""; ?>" />

                                                        <span class=" text text-danger atom_keyid_error"></span>

                                                    </div>  </div>

                                                <div class="form-group">

                                                    <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">Atom Key Secret<small class="req"> *</small>

                                                    </label>

                                                    <div class="col-md-7 col-sm-7 col-xs-12">

                                                        <input  name="atom_secretkey" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($atom_result->api_secret_key) ? $atom_result->api_secret_key : ""; ?>" />

                                                        <span class=" text text-danger atom_secretkey_error"></span>

                                                    </div>  </div>







                                            </div>

                                            <div class="col-md-5 text text-center disblock">

                                                <a href="https://www.nttdatapay.com/" target="_blank">

                                                    <h5>Atom Gateway for india</h5>

                                                    <img src="<?php echo base_url(); ?>/backend/images/atom.jpg" width="200"><p>https://www.nttdatapay.com</p></a>

                                            </div>

                                    </div>

                                </div>

                                <!-- /.box-body -->

                                <div class="box-footer">

                                    <div class="row">

                                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">

                                            <?php if ($this->rbac->hasPrivilege('payment_methods', 'can_edit')) { ?>

                                                <button type="submit" class="btn btn-primary atom_save" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('save'); ?>"><?php echo $this->lang->line('save'); ?></button>

                                            <?php } ?>

                                        </div>

                                    </div>        

                                </div>

                            </form>



                        </div>

                    </div>

                    <!-- /.tab-content -->

                </div>

            </div> 

            <div class="col-md-2">

                <div class="box box-primary">

                    <!-- /.box-header -->

                    <!-- form start -->

                    <form role="form" action="<?php echo site_url('admin/paymentsettings/setting') ?>" id="payment_gateway" method="POST">

                        <div class="box-body minheight199">

                            <div class="form-group"> <!-- Radio group !-->

                                <?php

                                $radio_check = check_selected($paymentlist);

                                ?>



                                <label class="control-label"><?php echo $this->lang->line('select_payment_gateway'); ?></label>


                                <div class="radio">

                                    <label>

                                        <input type="radio" name="payment_setting" value="atom" <?php

                                        if ($radio_check == 'atom') {

                                            echo "checked";

                                        }

                                        ?>>

                                        Atom

                                    </label>

                                </div>
                                <div class="radio">

                                    <label>

                                        <input type="radio" name="payment_setting" value="paypal" <?php

                                        if ($radio_check == 'paypal') {

                                            echo "checked";

                                        }

                                        ?>>

                                        <?php echo $this->lang->line('paypal');?>

                                    </label>

                                </div>

                                <div class="radio">

                                    <label>

                                        <input type="radio"  name="payment_setting" value="stripe" <?php

                                        if ($radio_check == 'stripe') {

                                            echo "checked";

                                        }

                                        ?>>

                                        <?php echo $this->lang->line('stripe');?>

                                    </label>

                                </div>

                                <div class="radio">

                                    <label>

                                        <input type="radio"  name="payment_setting" value="payu" <?php

                                        if ($radio_check == 'payu') {

                                            echo "checked";

                                        }

                                        ?>>

                                        <?php echo $this->lang->line('payu'); ?>

                                    </label>

                                </div>

                                <div class="radio">

                                    <label>

                                        <input type="radio"  name="payment_setting" value="ccavenue" <?php

                                        if ($radio_check == 'ccavenue') {

                                            echo "checked";

                                        }

                                        ?>>

                                        <?php echo $this->lang->line('ccavenue'); ?>

                                    </label>

                                </div>



                                <div class="radio">

                                    <label>

                                        <input type="radio"  name="payment_setting" value="instamojo" <?php

                                        if ($radio_check == 'instamojo') {

                                            echo "checked";

                                        }

                                        ?>>

                                        <?php echo $this->lang->line('instamojo'); ?>

                                    </label>

                                </div>

                                <div class="radio">

                                    <label>

                                        <input type="radio" name="payment_setting" value="paystack" <?php

                                        if ($radio_check == 'paystack') {

                                            echo "checked";

                                        }

                                        ?>>

                                        <?php echo $this->lang->line('paystack'); ?>

                                    </label>

                                </div>

                                <div class="radio">

                                    <label>

                                        <input type="radio"  name="payment_setting" value="razorpay" <?php

                                        if ($radio_check == 'razorpay') {

                                            echo "checked";

                                        }

                                        ?>>

                                        <?php echo $this->lang->line('razorpay'); ?>

                                    </label>

                                </div>

                                <div class="radio">

                                    <label>

                                        <input type="radio"  name="payment_setting" value="paytm" <?php

                                        if ($radio_check == 'paytm') {

                                            echo "checked";

                                        }

                                        ?>>

                                        <?php echo $this->lang->line('paytm'); ?>

                                    </label>

                                </div>



                                <div class="radio">

                                    <label>

                                        <input type="radio"  name="payment_setting" value="midtrans" <?php

                                        if ($radio_check == 'midtrans') {

                                            echo "checked";

                                        }

                                        ?>>

                                        <?php echo $this->lang->line('midtrans'); ?>

                                    </label>

                                </div>

                                  <div class="radio">

                                    <label>

                                        <input type="radio"  name="payment_setting" value="pesapal" <?php

                                        if ($radio_check == 'pesapal') {

                                            echo "checked";

                                        }

                                        ?>>

                                        <?php echo $this->lang->line('pesapal'); ?>

                                    </label>

                                </div>

                                 <div class="radio">

                                    <label>

                                        <input type="radio"  name="payment_setting" value="flutterwave" <?php

                                        if ($radio_check == 'flutterwave') {

                                            echo "checked";

                                        }

                                        ?>>

                                        <?php echo $this->lang->line('flutter_wave'); ?>

                                    </label>

                                </div>

                                  <div class="radio">

                                    <label>

                                        <input type="radio"  name="payment_setting" value="ipayafrica" <?php

                                        if ($radio_check == 'ipayafrica') {

                                            echo "checked";

                                        }

                                        ?>>

                                        <?php echo $this->lang->line('ipay_africa'); ?>

                                    </label>

                                </div>

                                 <div class="radio">

                                    <label>

                                        <input type="radio"  name="payment_setting" value="jazzcash" <?php

                                        if ($radio_check == 'jazzcash') {

                                            echo "checked";

                                        }

                                        ?>>

                                        <?php echo $this->lang->line('jazzcash'); ?>

                                    </label>

                                </div>



                                <div class="radio">

                                    <label>

                                        <input type="radio"  name="payment_setting" value="billplz" <?php

                                        if ($radio_check == 'billplz') {

                                            echo "checked";

                                        }

                                        ?>>

                                        <?php echo $this->lang->line('billplz'); ?>

                                    </label>

                                </div>

                                <div class="radio">

                                    <label>

                                        <input type="radio"  name="payment_setting" value="sslcommerz" <?php

                                        if ($radio_check == 'sslcommerz') {

                                            echo "checked";

                                        }

                                        ?>>

                                        <?php echo $this->lang->line('sslcommerz'); ?>

                                    </label>

                                </div>

                                 <span class="text text-danger payment_setting_error"></span>

                                <div class="radio">

                                    <label>

                                        <input type="radio"  name="payment_setting" value="none" <?php

                                        if ($radio_check == 'none') {

                                            echo "checked";

                                        }

                                        ?>>

                                         <?php echo $this->lang->line('none'); ?>

                                    </label>

                                </div>





                            </div>		

                        </div>

                        <!-- /.box-body -->



                        <div class="box-footer">

                            <?php if ($this->rbac->hasPrivilege('payment_methods', 'can_edit')) { ?>

                                <button type="submit" class="btn btn-primary pull-right payment_gateway_save" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('save'); ?>"><?php echo $this->lang->line('save'); ?></button>

                            <?php } ?>

                        </div>

                    </form>

                </div>

            </div>

        </div>  

    </section>

</div>



<?php



function check_selected($array) {

    $selected = "none";

    if (!empty($array)) {



        foreach ($array as $a => $element) {

            if ($element->is_active == "yes") {

                $selected = $element->payment_type;

            }

        }

    }

    return $selected;

}



function check_in_array($find, $array) {

    if (!empty($array)) {



        foreach ($array as $element) {



            if ($find == $element->payment_type) {

                return $element;

            }

        }

    }

    $object = new stdClass();

    $object->id = "";

    $object->type = "";

    $object->api_id = "";

    $object->username = "";

    $object->url = "";

    $object->name = "";

    $object->contact = "";

    $object->password = "";

    $object->authkey = "";

    $object->senderid = "";

    $object->is_active = "";

    return $object;

}

?>





<script type="text/javascript">



    $("#payment_gateway").submit(function (e) {

        $("[class$='_error']").html("");



        var $this = $(".payment_gateway_save");

        $this.button('loading');

        var url = $(this).attr('action'); // the script where you handle the form input.



        $.ajax({

            type: "POST",

            dataType: 'JSON',

            url: url,

            data: $("#payment_gateway").serialize(), // serializes the form's elements.

            success: function (data, textStatus, jqXHR)

            {

                if (data.st === 1) {

                    $.each(data.msg, function (key, value) {

                        $('.' + key + "_error").html(value);

                    });

                } else {

                    successMsg(data.msg);

                }

            },

            error: function (jqXHR, textStatus, errorThrown)

            {

                $(".custom_loader").html("");

                //if fails      

            }, complete: function () {

                $this.button('reset');

            }

        });



        e.preventDefault(); // avoid to execute the actual submit of the form.

    });







    $("#paypal").submit(function (e) {

        $("[class$='_error']").html("");



        var $this = $(".paypal_save");

        $this.button('loading');

        var url = $(this).attr('action'); // the script where you handle the form input.



        $.ajax({

            type: "POST",

            dataType: 'JSON',

            url: url,

            data: $("#paypal").serialize(), // serializes the form's elements.

            success: function (data, textStatus, jqXHR)

            {

                if (data.st === 1) {

                    $.each(data.msg, function (key, value) {

                        $('.' + key + "_error").html(value);

                    });

                } else {

                    successMsg(data.msg);

                }

            },

            error: function (jqXHR, textStatus, errorThrown)

            {

                $(".custom_loader").html("");

                //if fails      

            }, complete: function () {

                $this.button('reset');

            }

        });



        e.preventDefault(); // avoid to execute the actual submit of the form.

    });



    $("#stripe").submit(function (e) {

        $("[class$='_error']").html("");



        var $this = $(".stripe_save");

        $this.button('loading');

        var url = $(this).attr('action'); // the script where you handle the form input.



        $.ajax({

            type: "POST",

            dataType: 'JSON',

            url: url,

            data: $("#stripe").serialize(), // serializes the form's elements.

            success: function (data, textStatus, jqXHR)

            {

                if (data.st === 1) {

                    $.each(data.msg, function (key, value) {

                        $('.' + key + "_error").html(value);

                    });

                } else {

                    successMsg(data.msg);

                }

            },

            error: function (jqXHR, textStatus, errorThrown)

            {

                $(".custom_loader").html("");

                //if fails      

            }, complete: function () {

                $this.button('reset');

            }

        });



        e.preventDefault(); // avoid to execute the actual submit of the form.

    });



    $("#payu").submit(function (e) {

        $("[class$='_error']").html("");

        var $this = $(".payu_save");

        $this.button('loading');

        var url = $(this).attr('action');



        $.ajax({

            type: "POST",

            dataType: 'JSON',

            url: url,

            data: $("#payu").serialize(),

            success: function (data, textStatus, jqXHR)

            {

                if (data.st === 1) {

                    $.each(data.msg, function (key, value) {

                        $('.' + key + "_error").html(value);

                    });

                } else {

                    successMsg(data.msg);

                }

            },

            error: function (jqXHR, textStatus, errorThrown)

            {

                $(".custom_loader").html("");

            }, complete: function () {

                $this.button('reset');

            }

        });

        e.preventDefault();

    });





    $("#twocheckout").submit(function (e) {

        $("[class$='_twocheckout_error']").html("");

        var $this = $(".twocheckout_save");

        $this.button('loading');

        var url = $(this).attr('action');



        $.ajax({

            type: "POST",

            dataType: 'JSON',

            url: url,

            data: $("#twocheckout").serialize(),

            success: function (data, textStatus, jqXHR)

            {

                if (data.st === 1) {

                    $.each(data.msg, function (key, value) {

                        $('.' + key + "_twocheckout_error").html(value);

                    });

                } else {

                    successMsg(data.msg);

                }

            },

            error: function (jqXHR, textStatus, errorThrown)

            {

                $(".custom_loader").html("");

            }, complete: function () {

                $this.button('reset');

            }

        });

        e.preventDefault();

    });







    $("#ccavenue").submit(function (e) {

        $("[class$='_error']").html("");

        var $this = $(".ccavenue_save");

        $this.button('loading');

        var url = $(this).attr('action');



        $.ajax({

            type: "POST",

            dataType: 'JSON',

            url: url,

            data: $("#ccavenue").serialize(),

            success: function (data, textStatus, jqXHR)

            {

                if (data.st === 1) {

                    $.each(data.msg, function (key, value) {

                        $('.' + key + "_error").html(value);

                    });

                } else {

                    successMsg(data.msg);

                }

            },

            error: function (jqXHR, textStatus, errorThrown)

            {

                $(".custom_loader").html("");

            }, complete: function () {

                $this.button('reset');

            }

        });

        e.preventDefault();

    });

    $("#paystack").submit(function (e) {

        $("[class$='_error']").html("");

        var $this = $(".paystack_save");

        $this.button('loading');

        var url = $(this).attr('action');



        $.ajax({

            type: "POST",

            dataType: 'JSON',

            url: url,

            data: $("#paystack").serialize(),

            success: function (data, textStatus, jqXHR)

            {

                if (data.st === 1) {

                    $.each(data.msg, function (key, value) {

                        $('.' + key + "_error").html(value);

                    });

                } else {

                    successMsg(data.msg);

                }

            },

            error: function (jqXHR, textStatus, errorThrown)

            {

                $(".custom_loader").html("");

            }, complete: function () {

                $this.button('reset');

            }

        });

        e.preventDefault();

    });



    $("#instamojo").submit(function (e) {

        $("[class$='_error']").html("");

        var $this = $(".instamojo_save");

        $this.button('loading');

        var url = $(this).attr('action');



        $.ajax({

            type: "POST",

            dataType: 'JSON',

            url: url,

            data: $("#instamojo").serialize(),

            success: function (data, textStatus, jqXHR)

            {

                if (data.st === 1) {

                    $.each(data.msg, function (key, value) {

                        $('.' + key + "_error").html(value);

                    });

                } else {

                    successMsg(data.msg);

                }

            },

            error: function (jqXHR, textStatus, errorThrown)

            {

                $(".custom_loader").html("");

            }, complete: function () {

                $this.button('reset');

            }

        });

        e.preventDefault();

    });







    $("#razorpay").submit(function (e) {

        $("[class$='_error']").html("");

        var $this = $(".razorpay_save");

        $this.button('loading');

        var url = $(this).attr('action');



        $.ajax({

            type: "POST",

            dataType: 'JSON',

            url: url,

            data: $("#razorpay").serialize(),

            success: function (data, textStatus, jqXHR)

            {

                if (data.st === 1) {

                    $.each(data.msg, function (key, value) {

                        $('.' + key + "_error").html(value);

                    });

                } else {

                    successMsg(data.msg);

                }

            },

            error: function (jqXHR, textStatus, errorThrown)

            {

                $(".custom_loader").html("");

            }, complete: function () {

                $this.button('reset');

            }

        });

        e.preventDefault();

    });



    $("#paytm").submit(function (e) {

        $("[class$='_error']").html("");

        var $this = $(".paytm_save");

        $this.button('loading');

        var url = $(this).attr('action');



        $.ajax({

            type: "POST",

            dataType: 'JSON',

            url: url,

            data: $("#paytm").serialize(),

            success: function (data, textStatus, jqXHR)

            {

                if (data.st === 1) {

                    $.each(data.msg, function (key, value) {

                        $('.' + key + "_error").html(value);

                    });

                } else {

                    successMsg(data.msg);

                }

            },

            error: function (jqXHR, textStatus, errorThrown)

            {

                $(".custom_loader").html("");

            }, complete: function () {

                $this.button('reset');

            }

        });

        e.preventDefault();

    });



    $("#midtrans").submit(function (e) {

        $("[class$='_error']").html("");

        var $this = $(".midtrans_save");

        $this.button('loading');

        var url = $(this).attr('action');



        $.ajax({

            type: "POST",

            dataType: 'JSON',

            url: url,

            data: $("#midtrans").serialize(),

            success: function (data, textStatus, jqXHR)

            {

                if (data.st === 1) {

                    $.each(data.msg, function (key, value) {

                        $('.' + key + "_error").html(value);

                    });

                } else {

                    successMsg(data.msg);

                }

            },

            error: function (jqXHR, textStatus, errorThrown)

            {

                $(".custom_loader").html("");

            }, complete: function () {

                $this.button('reset');

            }

        });

        e.preventDefault();

    });

    $("#pesapal").submit(function (e) {

        $("[class$='_error']").html("");

        var $this = $(".pesapal_save");

        $this.button('loading');

        var url = $(this).attr('action');



        $.ajax({

            type: "POST",

            dataType: 'JSON',

            url: url,

            data: $("#pesapal").serialize(),

            success: function (data, textStatus, jqXHR)

            {

                if (data.st === 1) {

                    $.each(data.msg, function (key, value) {

                        $('.' + key + "_error").html(value);

                    });

                } else {

                    successMsg(data.msg);

                }

            },

            error: function (jqXHR, textStatus, errorThrown)

            {

                $(".custom_loader").html("");

            }, complete: function () {

                $this.button('reset');

            }

        });

        e.preventDefault();

    });



  $("#ipayafrica").submit(function (e) {

        $("[class$='_error']").html("");

        var $this = $(".ipayafrica_save");

        $this.button('loading');

        var url = $(this).attr('action');



        $.ajax({

            type: "POST",

            dataType: 'JSON',

            url: url,

            data: $("#ipayafrica").serialize(),

            success: function (data, textStatus, jqXHR)

            {

                if (data.st === 1) {

                    $.each(data.msg, function (key, value) {

                        $('.' + key + "_error").html(value);

                    });

                } else {

                    successMsg(data.msg);

                }

            },

            error: function (jqXHR, textStatus, errorThrown)

            {

                $(".ipayafrica_loader").html("");

            }, complete: function () {

                $this.button('reset');

            }

        });

        e.preventDefault();

    });



   $("#flutterwave").submit(function (e) {

        $("[class$='_error']").html("");

        var $this = $(".flutterwave_save");

        $this.button('loading');

        var url = $(this).attr('action');



        $.ajax({

            type: "POST",

            dataType: 'JSON',

            url: url,

            data: $("#flutterwave").serialize(),

            success: function (data, textStatus, jqXHR)

            {

                if (data.st === 1) {

                    $.each(data.msg, function (key, value) {

                        $('.' + key + "_error").html(value);

                    });

                } else {

                    successMsg(data.msg);

                }

            },

            error: function (jqXHR, textStatus, errorThrown)

            {

                $(".flutterwave_loader").html("");

            }, complete: function () {

                $this.button('reset');

            }

        });

        e.preventDefault();

    }); 



     $("#jazzcash").submit(function (e) {

        $("[class$='_error']").html("");

        var $this = $(".jazzcash_save");

        $this.button('loading');

        var url = $(this).attr('action');



        $.ajax({

            type: "POST",

            dataType: 'JSON',

            url: url,

            data: $("#jazzcash").serialize(),

            success: function (data, textStatus, jqXHR)

            {

                if (data.st === 1) {

                    $.each(data.msg, function (key, value) {

                        $('.' + key + "_error").html(value);

                    });

                } else {

                    successMsg(data.msg);

                }

            },

            error: function (jqXHR, textStatus, errorThrown)

            {

                $(".custom_loader").html("");

            }, complete: function () {

                $this.button('reset');

            }

        });

        e.preventDefault();

    });



     $("#billplz").submit(function (e) {

        $("[class$='_error']").html("");

        var $this = $(".billplz_save");

        $this.button('loading');

        var url = $(this).attr('action');



        $.ajax({

            type: "POST",

            dataType: 'JSON',

            url: url,

            data: $("#billplz").serialize(),

            success: function (data, textStatus, jqXHR)

            {

                if (data.st === 1) {

                    $.each(data.msg, function (key, value) {

                        $('.' + key + "_error").html(value);

                    });

                } else {

                    successMsg(data.msg);

                }

            },

            error: function (jqXHR, textStatus, errorThrown)

            {

                $(".custom_loader").html("");

            }, complete: function () {

                $this.button('reset');

            }

        });

        e.preventDefault();

    });

 



      $("#sslcommerz").submit(function (e) {

        $("[class$='_error']").html("");

        var $this = $(".sslcommerz_save");

        $this.button('loading');

        var url = $(this).attr('action');



        $.ajax({

            type: "POST",

            dataType: 'JSON',

            url: url,

            data: $("#sslcommerz").serialize(),

            success: function (data, textStatus, jqXHR)

            {

                if (data.st === 1) {

                    $.each(data.msg, function (key, value) {

                        $('.' + key + "_error").html(value);

                    });

                } else {

                    successMsg(data.msg);

                }

            },

            error: function (jqXHR, textStatus, errorThrown)

            {

                $(".custom_loader").html("");

            }, complete: function () {

                $this.button('reset');

            }

        });

        e.preventDefault();

    });

       $("#atom").submit(function (e) {

        $("[class$='_error']").html("");

        var $this = $(".atom_save");

        $this.button('loading');

        var url = $(this).attr('action');



        $.ajax({

            type: "POST",

            dataType: 'JSON',

            url: url,

            data: $("#atom").serialize(),

            success: function (data, textStatus, jqXHR)

            {

                if (data.st === 1) {

                    $.each(data.msg, function (key, value) {

                        $('.' + key + "_error").html(value);

                    });

                } else {

                    successMsg(data.msg);

                }

            },

            error: function (jqXHR, textStatus, errorThrown)

            {

                $(".custom_loader").html("");

            }, complete: function () {

                $this.button('reset');

            }

        });

        e.preventDefault();

    });

    // Sync sidebar radio selection with credential tabs
    $('input[type=radio][name=payment_setting]').on('change', function () {
        var selected = $(this).val();
        var tabMap = {
            'razorpay': '#tab_7',
            'atom': '#tab_16',
            'ccavenue': '#tab_4',
            'payu': '#tab_3',
            'paytm': '#tab_8',
            'paypal': '#tab_1',
            'stripe': '#tab_2',
            'instamojo': '#tab_5',
            'paystack': '#tab_6',
            'midtrans': '#tab_9',
            'pesapal': '#tab_10',
            'flutterwave': '#tab_11',
            'ipayafrica': '#tab_12',
            'jazzcash': '#tab_13',
            'billplz': '#tab_14',
            'sslcommerz': '#tab_15'
        };
        if (tabMap[selected]) {
            $('.nav-tabs a[href="' + tabMap[selected] + '"]').tab('show');
        }
    });

    // Also sync clicking tabs to radio buttons
    $('.nav-tabs a').on('shown.bs.tab', function (e) {
        var href = $(e.target).attr('href');
        var reverseMap = {
            '#tab_7': 'razorpay',
            '#tab_16': 'atom',
            '#tab_4': 'ccavenue',
            '#tab_3': 'payu',
            '#tab_8': 'paytm',
            '#tab_1': 'paypal',
            '#tab_2': 'stripe',
            '#tab_5': 'instamojo',
            '#tab_6': 'paystack',
            '#tab_9': 'midtrans',
            '#tab_10': 'pesapal',
            '#tab_11': 'flutterwave',
            '#tab_12': 'ipayafrica',
            '#tab_13': 'jazzcash',
            '#tab_14': 'billplz',
            '#tab_15': 'sslcommerz'
        };
        if (reverseMap[href]) {
            $('input[type=radio][name=payment_setting][value="' + reverseMap[href] + '"]').prop('checked', true);
        }
    });

</script>

<!-- Modal: View Test Payload -->
<div class="modal fade" id="testPayloadModal" tabindex="-1" role="dialog" aria-labelledby="payloadModalTitle">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-navy">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="payloadModalTitle" style="color:#fff;"><i class="fa fa-code"></i> Razorpay Live Test - Decrypted Gateway Payload</h4>
            </div>
            <div class="modal-body" id="test-payload-modal-body">
                <div class="text-center"><i class="fa fa-spinner fa-spin fa-2x"></i> Loading...</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
// Test 1: Fast API Connection Check
$('#btn-ping-razorpay').on('click', function () {
    var btn = $(this);
    var box = $('#ping-result-box');
    btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Checking...');
    box.hide().html('');

    $.ajax({
        url: '<?php echo site_url("admin/paymentsettings/test_razorpay_connection"); ?>',
        type: 'POST',
        dataType: 'json',
        success: function (res) {
            btn.prop('disabled', false).html('<i class="fa fa-refresh"></i> Check Razorpay API Connection');
            if (res.status === 'success') {
                box.removeClass('alert-danger').addClass('alert alert-success').html(
                    '<i class="fa fa-check-circle"></i> <strong>' + res.message + '</strong><br>' +
                    '<small>Configured Key ID: <code>' + res.key_id + '</code></small>'
                ).slideDown();
            } else {
                box.removeClass('alert-success').addClass('alert alert-danger').html(
                    '<i class="fa fa-times-circle"></i> <strong>Error:</strong> ' + res.message
                ).slideDown();
            }
        },
        error: function () {
            btn.prop('disabled', false).html('<i class="fa fa-refresh"></i> Check Razorpay API Connection');
            box.removeClass('alert-success').addClass('alert alert-danger').html(
                '<i class="fa fa-times-circle"></i> Server communication error. Please check your network or try again.'
            ).slideDown();
        }
    });
});

// Test 2: Real ₹1 Live Payment Test
$('#btn-start-test-payment').on('click', function () {
    var btn = $(this);
    var box = $('#test-payment-result-box');
    var amount = parseFloat($('#test-pay-amount').val()) || 1.00;
    var name = $('#test-pay-name').val() || 'Admin Test';

    btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Initializing Order...');
    box.hide().html('');

    // Step 1: Create Order via server
    $.ajax({
        url: '<?php echo site_url("admin/paymentsettings/create_razorpay_test_order"); ?>',
        type: 'POST',
        dataType: 'json',
        data: { amount: amount },
        success: function (res) {
            if (res.status !== 'success') {
                btn.prop('disabled', false).html('<i class="fa fa-bolt"></i> Pay Test Amount via Razorpay');
                box.removeClass('alert-success').addClass('alert alert-danger').html(
                    '<i class="fa fa-times-circle"></i> <strong>Order Creation Failed:</strong> ' + res.message
                ).slideDown();
                return;
            }

            // Step 2: Open Razorpay Live Checkout
            var options = {
                "key": res.key_id,
                "amount": res.amount,
                "currency": res.currency,
                "name": "CAG ERP Live Gateway Test",
                "description": "Test Transaction: ₹" + res.display_amount + " (Verification)",
                "order_id": res.order_id,
                "handler": function (response) {
                    btn.html('<i class="fa fa-spinner fa-spin"></i> Verifying Payment & Storing in DB...');
                    
                    // Step 3: Send back to backend for HMAC verification & DB storage
                    $.ajax({
                        url: '<?php echo site_url("admin/paymentsettings/verify_razorpay_test_payment"); ?>',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            razorpay_payment_id: response.razorpay_payment_id,
                            razorpay_order_id: response.razorpay_order_id,
                            razorpay_signature: response.razorpay_signature,
                            amount: amount,
                            name: name
                        },
                        success: function (verifyRes) {
                            btn.prop('disabled', false).html('<i class="fa fa-bolt"></i> Pay Test Amount via Razorpay');
                            if (verifyRes.status === 'success') {
                                box.removeClass('alert-danger').addClass('alert alert-success').html(
                                    '<h4><i class="icon fa fa-check-circle"></i> ' + verifyRes.message + '</h4>' +
                                    '<table class="table table-condensed" style="margin-bottom:0; background:rgba(255,255,255,0.7); color:#222;">' +
                                    '<tr><td style="width:30%;"><strong>Payment ID:</strong></td><td><code>' + verifyRes.payment_id + '</code></td></tr>' +
                                    '<tr><td><strong>Order ID:</strong></td><td><code>' + verifyRes.order_id + '</code></td></tr>' +
                                    '<tr><td><strong>Amount Paid:</strong></td><td>₹' + verifyRes.amount + '</td></tr>' +
                                    '<tr><td><strong>Payment Mode / Method:</strong></td><td>' + verifyRes.method + ' (' + verifyRes.bank_or_vpa + ')</td></tr>' +
                                    '<tr><td><strong>Signature Verified:</strong></td><td><span class="label label-success"><i class="fa fa-check"></i> HMAC-SHA256 Match</span></td></tr>' +
                                    '<tr><td><strong>Database Status:</strong></td><td><span class="label label-primary">Stored in gateway_test_transactions (ID: #' + verifyRes.test_id + ')</span></td></tr>' +
                                    '</table>'
                                ).slideDown();

                                // Prepend new row to recent test table
                                $('#no-test-trans-row').remove();
                                var newRow = '<tr>' +
                                    '<td>#' + verifyRes.test_id + '</td>' +
                                    '<td>Just now</td>' +
                                    '<td><code>' + verifyRes.payment_id + '</code></td>' +
                                    '<td><small>' + verifyRes.order_id + '</small></td>' +
                                    '<td><strong>₹' + verifyRes.amount + '</strong></td>' +
                                    '<td><span class="badge bg-navy">' + verifyRes.method + '</span></td>' +
                                    '<td><small>' + verifyRes.bank_or_vpa + '</small></td>' +
                                    '<td><span class="label label-success">' + verifyRes.payment_status + '</span></td>' +
                                    '<td><span class="label label-success"><i class="fa fa-check"></i> Verified</span></td>' +
                                    '<td class="text-right">' +
                                        '<button type="button" class="btn btn-xs btn-default" onclick="viewTestPayload(' + verifyRes.test_id + ')">' +
                                            '<i class="fa fa-eye"></i> Response' +
                                        '</button>' +
                                    '</td>' +
                                    '</tr>';
                                $('#test-trans-tbody').prepend(newRow);
                            } else {
                                box.removeClass('alert-success').addClass('alert alert-danger').html(
                                    '<i class="fa fa-times-circle"></i> <strong>Payment Verification Failed:</strong> ' + verifyRes.message
                                ).slideDown();
                            }
                        },
                        error: function () {
                            btn.prop('disabled', false).html('<i class="fa fa-bolt"></i> Pay Test Amount via Razorpay');
                            box.removeClass('alert-success').addClass('alert alert-danger').html(
                                '<i class="fa fa-times-circle"></i> Error while communicating with verification endpoint.'
                            ).slideDown();
                        }
                    });
                },
                "modal": {
                    "ondismiss": function () {
                        btn.prop('disabled', false).html('<i class="fa fa-bolt"></i> Pay Test Amount via Razorpay');
                    }
                },
                "prefill": {
                    "name": name,
                    "email": "admin@cag.edu",
                    "contact": "9999999999"
                },
                "theme": {
                    "color": "#00a65a"
                }
            };

            var rzp = new Razorpay(options);
            rzp.on('payment.failed', function (failResp) {
                btn.prop('disabled', false).html('<i class="fa fa-bolt"></i> Pay Test Amount via Razorpay');
                box.removeClass('alert-success').addClass('alert alert-danger').html(
                    '<i class="fa fa-times-circle"></i> <strong>Payment Failed:</strong> ' + failResp.error.description +
                    ' (Code: ' + failResp.error.code + ')'
                ).slideDown();
            });
            rzp.open();
        },
        error: function () {
            btn.prop('disabled', false).html('<i class="fa fa-bolt"></i> Pay Test Amount via Razorpay');
            box.removeClass('alert-success').addClass('alert alert-danger').html(
                '<i class="fa fa-times-circle"></i> Server error while creating order.'
            ).slideDown();
        }
    });
});

/**
 * View raw test transaction payload
 */
function viewTestPayload(id) {
    $('#test-payload-modal-body').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-2x"></i> Loading...</div>');
    $('#testPayloadModal').modal('show');

    $.ajax({
        url: '<?php echo site_url("admin/paymentsettings/get_test_transaction_detail"); ?>',
        type: 'POST',
        dataType: 'json',
        data: { id: id },
        success: function (res) {
            if (res.status === 'success' && res.data) {
                var d = res.data;
                var html = '<table class="table table-bordered table-striped">' +
                    '<tr><th style="width:30%;">Test ID:</th><td>#' + d.id + '</td></tr>' +
                    '<tr><th>Payment ID:</th><td><code>' + d.payment_id + '</code></td></tr>' +
                    '<tr><th>Order ID:</th><td><code>' + (d.order_id || 'N/A') + '</code></td></tr>' +
                    '<tr><th>Amount Paid:</th><td><strong>₹' + parseFloat(d.amount).toFixed(2) + ' ' + d.currency + '</strong></td></tr>' +
                    '<tr><th>Method / VPA / Bank:</th><td>' + d.method + ' (' + (d.vpa || d.bank || 'N/A') + ')</td></tr>' +
                    '<tr><th>Date & Time:</th><td>' + d.created_at + '</td></tr>' +
                    '<tr><th>Tested By:</th><td>' + (d.tested_by || 'Admin') + '</td></tr>' +
                    '<tr><th>HMAC Signature Verified:</th><td>' + (d.is_signature_verified == 1 ? '<span class="label label-success"><i class="fa fa-check"></i> Valid</span>' : '<span class="label label-default">N/A</span>') + '</td></tr>' +
                    '</table>';

                if (d.raw_response) {
                    try {
                        var parsed = JSON.parse(d.raw_response);
                        html += '<h4>Decrypted Live Razorpay Response</h4>' +
                            '<pre style="max-height:280px; overflow-y:auto; background:#1e1e1e; color:#4ec9b0; font-size:12px;">' +
                            JSON.stringify(parsed, null, 2) +
                            '</pre>';
                    } catch (e) {
                        html += '<h4>Raw Gateway Payload</h4><pre style="max-height:200px; overflow-y:auto;">' + d.raw_response + '</pre>';
                    }
                }

                $('#test-payload-modal-body').html(html);
            } else {
                $('#test-payload-modal-body').html('<div class="alert alert-danger">Transaction not found.</div>');
            }
        },
        error: function () {
            $('#test-payload-modal-body').html('<div class="alert alert-danger">Error loading details.</div>');
        }
    });
}
</script>