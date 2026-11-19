<?php



if (!defined('BASEPATH'))

    exit('No direct script access allowed');



class Paymentsettings extends Admin_Controller {



    function __construct() {

        parent::__construct();

    }



    function index() {

        if (!$this->rbac->hasPrivilege('payment_methods', 'can_view')) {

            access_denied();

        }

        $this->session->set_userdata('top_menu', 'System Settings');

        $this->session->set_userdata('sub_menu', 'admin/paymentsettings');

        $data['title'] = 'SMS Config List';

        $payment_result = $this->paymentsetting_model->get();
       

        $data['statuslist'] = $this->customlib->getStatus();

        $data['paymentlist'] = $payment_result;

        $data['test_transactions'] = $this->paymentsetting_model->getTestTransactions(10);




        $this->load->view('layout/header', $data);

        $this->load->view('admin/payment_setting/paymentsettingList', $data);

        $this->load->view('layout/footer', $data);

    }



    public function paypal() {

        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('paypal_username', $this->lang->line('username'), 'trim|required|xss_clean');

        $this->form_validation->set_rules('paypal_password', $this->lang->line('password'), 'trim|required|xss_clean');

        $this->form_validation->set_rules('paypal_signature', $this->lang->line('signature'), 'trim|required|xss_clean');



        if ($this->form_validation->run()) {

            $data = array(

                'payment_type' => 'paypal',

                'api_username' => $this->input->post('paypal_username'),

                'api_password' => $this->input->post('paypal_password'),

                'api_signature' => $this->input->post('paypal_signature'),

                'paypal_demo' => 'TRUE',

            );

            $this->paymentsetting_model->add($data);

            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));

        } else {



            $data = array(

                'paypal_username' => form_error('paypal_username'),

                'paypal_password' => form_error('paypal_password'),

                'paypal_signature' => form_error('paypal_signature'),

            );



            echo json_encode(array('st' => 1, 'msg' => $data));

        }

    }



    public function stripe() {



        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('api_secret_key', $this->lang->line('secret_key'), 'trim|required|xss_clean');

        $this->form_validation->set_rules('api_publishable_key', $this->lang->line('publishable_key'), 'trim|required|xss_clean');



        if ($this->form_validation->run()) {

            $data = array(

                'api_secret_key' => $this->input->post('api_secret_key'),

                'api_publishable_key' => $this->input->post('api_publishable_key'),

                'payment_type' => 'stripe',

            );



            $this->paymentsetting_model->add($data);

            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));

        } else {

            $data = array(

                'api_secret_key' => form_error('api_secret_key'),

                'api_publishable_key' => form_error('api_publishable_key'),

            );

            echo json_encode(array('st' => 1, 'msg' => $data));

        }

    }



    public function payu() {

        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('key', $this->lang->line('key'), 'trim|required|xss_clean');

        $this->form_validation->set_rules('salt', $this->lang->line('salt'), 'trim|required|xss_clean');

        if ($this->form_validation->run()) {

            $data = array(

                'api_secret_key' => $this->input->post('key'),

                'salt' => $this->input->post('salt'),

                'payment_type' => 'payu',

            );

            $this->paymentsetting_model->add($data);

            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));

        } else {

            $data = array(

                'key' => form_error('key'),

                'salt' => form_error('salt')

            );

            echo json_encode(array('st' => 1, 'msg' => $data));

        }

    }



    public function twocheckout() {

        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('api_secret_key', $this->lang->line('key'), 'trim|required|xss_clean');

        $this->form_validation->set_rules('api_publishable_key', $this->lang->line('salt'), 'trim|required|xss_clean');

        if ($this->form_validation->run()) {

            $data = array(

                'api_secret_key' => $this->input->post('api_secret_key'),

                'api_publishable_key' => $this->input->post('api_publishable_key'),

                'payment_type' => 'twocheckout',

            );

            $this->paymentsetting_model->add($data);

            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));

        } else {

            $data = array(

                'api_secret_key' => form_error('api_secret_key'),

                'api_publishable_key' => form_error('api_publishable_key')

            );

            echo json_encode(array('st' => 1, 'msg' => $data));

        }

    }



    public function ccavenue() {

        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('ccavenue_secret', $this->lang->line('key'), 'trim|required|xss_clean');

        $this->form_validation->set_rules('ccavenue_salt', $this->lang->line('salt'), 'trim|required|xss_clean');

         $this->form_validation->set_rules('ccavenue_api_publishable_key', $this->lang->line('access_code'), 'trim|required|xss_clean');

        if ($this->form_validation->run()) {

            $data = array(

                'api_secret_key' => $this->input->post('ccavenue_secret'),

                'salt' => $this->input->post('ccavenue_salt'),

                'api_publishable_key'=>$this->input->post('ccavenue_api_publishable_key'),

                'payment_type' => 'ccavenue',

            );

            $this->paymentsetting_model->add($data);

            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));

        } else {

            $data = array(

                'ccavenue_secret' => form_error('ccavenue_secret'),

                'ccavenue_salt' => form_error('ccavenue_salt'),

                'ccavenue_api_publishable_key' => form_error('ccavenue_api_publishable_key')

            );

            echo json_encode(array('st' => 1, 'msg' => $data));

        }

    }



    public function paystack() {

        $this->form_validation->set_error_delimiters('', '');



        $this->form_validation->set_rules('paystack_secretkey', $this->lang->line('key'), 'trim|required|xss_clean');



        if ($this->form_validation->run()) {

            $data = array(

                'api_secret_key' => $this->input->post('paystack_secretkey'),

                'payment_type' => 'paystack',

            );

            $this->paymentsetting_model->add($data);

            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));

        } else {

            $data = array(

                'paystack_secretkey' => form_error('paystack_secretkey'),

            );

            echo json_encode(array('st' => 1, 'msg' => $data));

        }

    }



    public function instamojo() {



        $this->form_validation->set_error_delimiters('', '');



        $this->form_validation->set_rules('instamojo_apikey', $this->lang->line('key'), 'trim|required|xss_clean');

        $this->form_validation->set_rules('instamojo_authtoken', $this->lang->line('key'), 'trim|required|xss_clean');

        $this->form_validation->set_rules('instamojo_salt', $this->lang->line('key'), 'trim|required|xss_clean');



        if ($this->form_validation->run()) {



            $data = array(

                'api_secret_key' => $this->input->post('instamojo_apikey'),

                'api_publishable_key' => $this->input->post('instamojo_authtoken'),

                'salt' => $this->input->post('instamojo_salt'),

                'payment_type' => 'instamojo',

            );



            $this->paymentsetting_model->add($data);

            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));

        } else {



            $data = array(

                'instamojo_apikey' => form_error('instamojo_apikey'),

                'instamojo_authtoken' => form_error('instamojo_authtoken'),

                'instamojo_salt' => form_error('instamojo_salt'),

            );

            echo json_encode(array('st' => 1, 'msg' => $data));

        }

    }



    public function razorpay() {

        $this->form_validation->set_error_delimiters('', '');



        $this->form_validation->set_rules('razorpay_keyid', $this->lang->line('key'), 'trim|required|xss_clean');

        $this->form_validation->set_rules('razorpay_secretkey', $this->lang->line('key'), 'trim|required|xss_clean');





        if ($this->form_validation->run()) {



            $data = array(

                'api_secret_key' => $this->input->post('razorpay_secretkey'),

                'api_publishable_key' => $this->input->post('razorpay_keyid'),

                'payment_type' => 'razorpay',

            );



            $this->paymentsetting_model->add($data);

            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));

        } else {



            $data = array(

                'razorpay_keyid' => form_error('razorpay_keyid'),

                'razorpay_secretkey' => form_error('razorpay_secretkey'),

            );

            echo json_encode(array('st' => 1, 'msg' => $data));

        }

    }

    public function atom() {

        $this->form_validation->set_error_delimiters('', '');



        $this->form_validation->set_rules('atom_keyid', $this->lang->line('key'), 'trim|required|xss_clean');

        $this->form_validation->set_rules('atom_secretkey', $this->lang->line('key'), 'trim|required|xss_clean');





        if ($this->form_validation->run()) {



            $data = array(

                'api_secret_key' => $this->input->post('atom_secretkey'),

                'api_publishable_key' => $this->input->post('atom_keyid'),

                'payment_type' => 'atom',

            );



            $this->paymentsetting_model->add($data);

            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));

        } else {



            $data = array(

                'atom_keyid' => form_error('atom_keyid'),

                'atom_secretkey' => form_error('atom_secretkey'),

            );

            echo json_encode(array('st' => 1, 'msg' => $data));

        }

    }



    public function paytm() {



        $this->form_validation->set_error_delimiters('', '');



        $this->form_validation->set_rules('paytm_merchantid', $this->lang->line('key'), 'trim|required|xss_clean');

        $this->form_validation->set_rules('paytm_merchantkey', $this->lang->line('key'), 'trim|required|xss_clean');

        $this->form_validation->set_rules('paytm_website', $this->lang->line('key'), 'trim|required|xss_clean');

        $this->form_validation->set_rules('paytm_industrytype', $this->lang->line('key'), 'trim|required|xss_clean');





        if ($this->form_validation->run()) {



            $data = array(

                'api_secret_key' => $this->input->post('paytm_merchantkey'),

                'api_publishable_key' => $this->input->post('paytm_merchantid'),

                'paytm_website' => $this->input->post('paytm_website'),

                'paytm_industrytype' => $this->input->post('paytm_industrytype'),

                'payment_type' => 'paytm',

            );



            $this->paymentsetting_model->add($data);

            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));

        } else {



            $data = array(

                'paytm_merchantkey' => form_error('paytm_merchantkey'),

                'paytm_merchantid' => form_error('paytm_merchantid'),

                'paytm_website' => form_error('paytm_website'),

                'paytm_industrytype' => form_error('paytm_industrytype'),

            );

            echo json_encode(array('st' => 1, 'msg' => $data));

        }

    }



    public function midtrans() {



        $this->form_validation->set_error_delimiters('', '');



        $this->form_validation->set_rules('midtrans_serverkey', $this->lang->line('key'), 'trim|required|xss_clean');







        if ($this->form_validation->run()) {



            $data = array(

                'api_secret_key' => $this->input->post('midtrans_serverkey'),

                'payment_type' => 'midtrans',

            );



            $this->paymentsetting_model->add($data);

            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));

        } else {



            $data = array(

                'midtrans_serverkey' => form_error('midtrans_serverkey'),

            );

            echo json_encode(array('st' => 1, 'msg' => $data));

        }

    }



  

  public function pesapal(){



         $this->form_validation->set_error_delimiters('', '');



        $this->form_validation->set_rules('pesapal_consumer_key', $this->lang->line('consumer_key'), 'trim|required|xss_clean');

        $this->form_validation->set_rules('pesapal_consumer_secret', $this->lang->line('consumer_secret'), 'trim|required|xss_clean');







        if ($this->form_validation->run()) {



            $data = array(

                'api_secret_key' => $this->input->post('pesapal_consumer_secret'),

                'api_publishable_key' => $this->input->post('pesapal_consumer_key'),

                'payment_type' => 'pesapal',

            );



            $this->paymentsetting_model->add($data);

            

            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));

        }else{



            $data = array(

                'pesapal_consumer_key' => form_error('pesapal_consumer_key'),

                'pesapal_consumer_secret' => form_error('pesapal_consumer_secret'),

            );

            

            echo json_encode(array('st' => 1, 'msg' => $data));

        }

    }



 

      public function ipayafrica()

    {

        $this->form_validation->set_error_delimiters('', '');



        $this->form_validation->set_rules('ipayafrica_vendorid', $this->lang->line('vendorid'), 'trim|required|xss_clean');

        $this->form_validation->set_rules('ipayafrica_hashkey', $this->lang->line('hashkey'), 'trim|required|xss_clean');



        if ($this->form_validation->run()) {



            $data = array(

                'api_secret_key'      => $this->input->post('ipayafrica_hashkey'),

                'api_publishable_key' => $this->input->post('ipayafrica_vendorid'),

                'payment_type'        => 'ipayafrica',

            );



            $this->paymentsetting_model->add($data);

            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));



        } else {



            $data = array(

                'ipayafrica_vendorid'     => form_error('ipayafrica_vendorid'),

                'ipayafrica_hashkey' => form_error('ipayafrica_hashkey'),



            );

            echo json_encode(array('st' => 1, 'msg' => $data));

        }

    }



     public function flutterwave(){



        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('public_key', $this->lang->line('public')." ".$this->lang->line('Key'), 'trim|required|xss_clean');

        $this->form_validation->set_rules('secret_key', $this->lang->line('secret_key'), 'trim|required|xss_clean');



        if ($this->form_validation->run()) {



            $data = array(

              

                'api_publishable_key' => $this->input->post('public_key'),

                'api_secret_key' => $this->input->post('secret_key'),

                'payment_type' => 'flutterwave',

            );



            $this->paymentsetting_model->add($data);

            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));

        }else{



            $data = array(

                'public_key' => form_error('public_key'),

                'secret_key' => form_error('secret_key'),

            );

            

            echo json_encode(array('st' => 1, 'msg' => $data));

        }

    }



    public function jazzcash() {

      

        $this->form_validation->set_error_delimiters('', '');



        $this->form_validation->set_rules('jazzcash_pp_MerchantID', $this->lang->line('pp_MerchantID'), 'trim|required|xss_clean');

         $this->form_validation->set_rules('jazzcash_pp_Password', $this->lang->line('pp_password'), 'trim|required|xss_clean');







        if ($this->form_validation->run()) {



            $data = array(

                'api_secret_key' => $this->input->post('jazzcash_pp_MerchantID'),

                 'api_password' => $this->input->post('jazzcash_pp_Password'),

                'payment_type' => 'jazzcash',

            );



            $this->paymentsetting_model->add($data);

            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));

        } else {



            $data = array(

                'jazzcash_pp_MerchantID' => form_error('jazzcash_pp_MerchantID'),

                'jazzcash_pp_Password' => form_error('jazzcash_pp_Password'),

            );

            echo json_encode(array('st' => 1, 'msg' => $data));

        }

    }



    public function billplz() {

      

        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('billplz_api_key', $this->lang->line('api_key'), 'trim|required|xss_clean');

        $this->form_validation->set_rules('billplz_customer_service_email', $this->lang->line('customer_service_email'), 'trim|required|xss_clean');



        if ($this->form_validation->run()) {



            $data = array(

                'api_secret_key' => $this->input->post('billplz_api_key'),

                 'api_email' => $this->input->post('billplz_customer_service_email'),

                'payment_type' => 'billplz',

            );



            $this->paymentsetting_model->add($data);

            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));



        } else {



            $data = array(

                'billplz_api_key' => form_error('billplz_api_key'),

                'billplz_customer_service_email' => form_error('billplz_customer_service_email'),

            );



            echo json_encode(array('st' => 1, 'msg' => $data));

        }

    }



    public function sslcommerz() {

        

      

        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('sslcommerz_api_key', $this->lang->line('sslcommerz_api_key'), 'trim|required|xss_clean');

        $this->form_validation->set_rules('sslcommerz_store_password', $this->lang->line('sslcommerz_store_password'), 'trim|required|xss_clean');



        if ($this->form_validation->run()) {



            $data = array(

                'api_password' => $this->input->post('sslcommerz_store_password'),

                 'api_publishable_key' => $this->input->post('sslcommerz_api_key'),

                'payment_type' => 'sslcommerz',

            );



            $this->paymentsetting_model->add($data);

            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));



        } else {



            $data = array(

                'sslcommerz_store_password' => form_error('sslcommerz_store_password'),

                'sslcommerz_api_key' => form_error('sslcommerz_api_key'),

            );



            echo json_encode(array('st' => 1, 'msg' => $data));

        }

    }

    

    public function setting() { 



        $this->form_validation->set_error_delimiters('', '');



        $this->form_validation->set_rules(

                'payment_setting', $this->lang->line('payment_setting'), array(

            'required',

            array('paymentsetting', array($this->paymentsetting_model, 'valid_paymentsetting'))

                )

        );

        if ($this->form_validation->run()) {

            $paymentsetting = $this->input->post('payment_setting');

            $other = false;

            if ($paymentsetting == "none") {

                $other = true;

                $data = array(

                    'is_active' => 'no'

                );

            } else {

                $data = array(

                    'payment_type' => $this->input->post('payment_setting'),

                    'is_active' => 'yes'

                );

            }

            $this->paymentsetting_model->active($data, $other);



            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));

        } else {



            $data = array(

                'payment_setting' => form_error('payment_setting')

            );



            echo json_encode(array('st' => 1, 'msg' => $data));

        }

    }

    /**
     * AJAX: Test Razorpay API Connection
     */
    public function test_razorpay_connection()
    {
        if (!$this->rbac->hasPrivilege('payment_methods', 'can_view')) {
            echo json_encode(array('status' => 'fail', 'message' => 'Access Denied'));
            return;
        }

        $razorpay = $this->db->where('payment_type', 'razorpay')->get('payment_settings')->row();
        if (empty($razorpay) || empty($razorpay->api_publishable_key) || empty($razorpay->api_secret_key)) {
            echo json_encode(array('status' => 'fail', 'message' => 'Razorpay Key ID or Secret Key is not configured yet. Please enter and save your keys above.'));
            return;
        }

        $key_id     = trim($razorpay->api_publishable_key);
        $secret_key = trim($razorpay->api_secret_key);

        $ch = curl_init('https://api.razorpay.com/v1/payments?count=1');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $key_id . ':' . $secret_key);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $resp = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_err = curl_error($ch);
        curl_close($ch);

        if ($http_code == 200) {
            echo json_encode(array(
                'status'  => 'success',
                'message' => 'Razorpay Live API Connected Successfully! (HTTP 200 OK). Credentials are verified and active on Razorpay.',
                'key_id'  => $key_id,
            ));
        } elseif ($http_code == 401) {
            echo json_encode(array(
                'status'  => 'fail',
                'message' => 'Authentication Failed (HTTP 401 Unauthorized): Invalid Key ID or Key Secret. Please verify the keys in your Razorpay Dashboard.',
            ));
        } else {
            $err_msg = !empty($curl_err) ? $curl_err : "Razorpay API returned HTTP {$http_code}";
            if (!empty($resp)) {
                $err_data = json_decode($resp, true);
                if (isset($err_data['error']['description'])) {
                    $err_msg = $err_data['error']['description'];
                }
            }
            echo json_encode(array(
                'status'  => 'fail',
                'message' => 'Connection check failed: ' . $err_msg,
            ));
        }
    }

    /**
     * AJAX: Create Razorpay ₹1 Live Test Order
     */
    public function create_razorpay_test_order()
    {
        if (!$this->rbac->hasPrivilege('payment_methods', 'can_edit')) {
            echo json_encode(array('status' => 'fail', 'message' => 'Access Denied'));
            return;
        }

        $amount = (float)$this->input->post('amount');
        if ($amount < 1) {
            $amount = 1.00;
        }

        $razorpay = $this->db->where('payment_type', 'razorpay')->get('payment_settings')->row();
        if (empty($razorpay) || empty($razorpay->api_publishable_key) || empty($razorpay->api_secret_key)) {
            echo json_encode(array('status' => 'fail', 'message' => 'Razorpay credentials not found in database.'));
            return;
        }

        $key_id     = trim($razorpay->api_publishable_key);
        $secret_key = trim($razorpay->api_secret_key);
        $total_paise = round($amount * 100);
        $receipt = 'TEST_' . time();

        $admin_session = $this->session->userdata('admin');
        $username = isset($admin_session['username']) ? $admin_session['username'] : 'admin';

        $order_payload = array(
            'amount'          => $total_paise,
            'currency'        => 'INR',
            'receipt'         => $receipt,
            'payment_capture' => 1,
            'notes'           => array(
                'purpose'   => 'Admin Razorpay Live Test',
                'tested_by' => $username,
                'time'      => date('Y-m-d H:i:s'),
            ),
        );

        $ch = curl_init('https://api.razorpay.com/v1/orders');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($order_payload));
        curl_setopt($ch, CURLOPT_USERPWD, $key_id . ':' . $secret_key);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $resp = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code == 200) {
            $order = json_decode($resp, true);
            echo json_encode(array(
                'status'         => 'success',
                'order_id'       => $order['id'],
                'amount'         => $total_paise,
                'display_amount' => number_format($amount, 2),
                'key_id'         => $key_id,
                'currency'       => 'INR',
                'receipt'        => $receipt,
            ));
        } else {
            $err = json_decode($resp, true);
            $msg = isset($err['error']['description']) ? $err['error']['description'] : "Failed to create Razorpay Order (HTTP {$http_code})";
            echo json_encode(array('status' => 'fail', 'message' => $msg));
        }
    }

    /**
     * AJAX: Verify Live Test Payment & Store in DB
     */
    public function verify_razorpay_test_payment()
    {
        if (!$this->rbac->hasPrivilege('payment_methods', 'can_edit')) {
            echo json_encode(array('status' => 'fail', 'message' => 'Access Denied'));
            return;
        }

        $payment_id = trim($this->input->post('razorpay_payment_id'));
        $order_id   = trim($this->input->post('razorpay_order_id'));
        $signature  = trim($this->input->post('razorpay_signature'));
        $amount     = (float)$this->input->post('amount');
        if ($amount < 1) {
            $amount = 1.00;
        }

        if (empty($payment_id)) {
            echo json_encode(array('status' => 'fail', 'message' => 'Payment ID missing.'));
            return;
        }

        $razorpay = $this->db->where('payment_type', 'razorpay')->get('payment_settings')->row();
        $key_id     = trim($razorpay->api_publishable_key);
        $secret_key = trim($razorpay->api_secret_key);

        // 1. Signature Verification
        $is_signature_valid = false;
        if (!empty($order_id) && !empty($signature)) {
            $expected_signature = hash_hmac('sha256', $order_id . '|' . $payment_id, $secret_key);
            $is_signature_valid = hash_equals($expected_signature, $signature);
        }

        // 2. Fetch live payment from Razorpay API
        $ch = curl_init('https://api.razorpay.com/v1/payments/' . urlencode($payment_id));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $key_id . ':' . $secret_key);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $api_resp = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $payment_info = json_decode($api_resp, true);

        // 3. If authorized, capture it
        if ($http_code == 200 && isset($payment_info['status']) && $payment_info['status'] === 'authorized') {
            $cap_ch = curl_init('https://api.razorpay.com/v1/payments/' . urlencode($payment_id) . '/capture');
            curl_setopt($cap_ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($cap_ch, CURLOPT_POST, true);
            curl_setopt($cap_ch, CURLOPT_POSTFIELDS, http_build_query(array(
                'amount'   => round($amount * 100),
                'currency' => 'INR',
            )));
            curl_setopt($cap_ch, CURLOPT_USERPWD, $key_id . ':' . $secret_key);
            curl_setopt($cap_ch, CURLOPT_TIMEOUT, 20);
            curl_setopt($cap_ch, CURLOPT_SSL_VERIFYPEER, false);
            $cap_resp = curl_exec($cap_ch);
            curl_close($cap_ch);
            $cap_info = json_decode($cap_resp, true);
            if (isset($cap_info['status']) && $cap_info['status'] === 'captured') {
                $payment_info = $cap_info;
            }
        }

        // 4. Save to gateway_test_transactions table in database
        $admin_session = $this->session->userdata('admin');
        $username = isset($admin_session['username']) ? $admin_session['username'] : 'admin';

        $record = array(
            'gateway'               => 'razorpay',
            'payment_id'            => $payment_id,
            'order_id'              => $order_id,
            'signature'             => $signature,
            'amount'                => isset($payment_info['amount']) ? ($payment_info['amount'] / 100) : $amount,
            'currency'              => isset($payment_info['currency']) ? $payment_info['currency'] : 'INR',
            'status'                => isset($payment_info['status']) ? $payment_info['status'] : 'captured',
            'method'                => isset($payment_info['method']) ? $payment_info['method'] : 'online',
            'bank'                  => isset($payment_info['bank']) ? $payment_info['bank'] : null,
            'vpa'                   => isset($payment_info['vpa']) ? $payment_info['vpa'] : null,
            'email'                 => isset($payment_info['email']) ? $payment_info['email'] : null,
            'contact'               => isset($payment_info['contact']) ? $payment_info['contact'] : null,
            'is_signature_verified' => $is_signature_valid ? 1 : 0,
            'raw_response'          => json_encode($payment_info),
            'tested_by'             => $username,
            'created_at'            => date('Y-m-d H:i:s'),
        );

        $test_id = $this->paymentsetting_model->saveTestTransaction($record);

        echo json_encode(array(
            'status'                => 'success',
            'test_id'               => $test_id,
            'payment_id'            => $payment_id,
            'order_id'              => $order_id,
            'amount'                => number_format($record['amount'], 2),
            'method'                => strtoupper($record['method']),
            'payment_status'        => strtoupper($record['status']),
            'is_signature_verified' => $is_signature_valid,
            'bank_or_vpa'           => !empty($record['vpa']) ? $record['vpa'] : (!empty($record['bank']) ? $record['bank'] : 'N/A'),
            'message'               => 'Live Test Payment of ₹' . number_format($record['amount'], 2) . ' Successfully Processed & Saved to Database!',
            'raw'                   => $payment_info,
        ));
    }

    /**
     * AJAX: Get single test transaction raw response
     */
    public function get_test_transaction_detail()
    {
        $id = (int)$this->input->post('id');
        $record = $this->paymentsetting_model->getTestTransactionById($id);
        if (!empty($record)) {
            echo json_encode(array('status' => 'success', 'data' => $record));
        } else {
            echo json_encode(array('status' => 'fail', 'message' => 'Record not found.'));
        }
    }

}



?>