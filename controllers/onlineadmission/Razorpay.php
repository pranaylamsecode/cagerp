<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Razorpay extends OnlineAdmission_Controller
{

    public $pay_method = "";
    public $amount = 0;

    function __construct() {
        parent::__construct();
        $this->pay_method = $this->paymentsetting_model->getActiveMethod();
        $this->setting = $this->setting_model->getSetting();
        $this->amount = $this->setting->online_admission_amount;
        $this->load->library('mailsmsconf');
        $this->load->model('onlinestudent_model');
    }

    public function index() {
        $reference = $this->session->userdata('reference');
        if (empty($reference)) {
            redirect(base_url('onlineadmission/checkout'));
            return;
        }

        $online_data = $this->onlinestudent_model->getAdmissionData($reference);
        if (empty($online_data)) {
            redirect(base_url('onlineadmission/checkout'));
            return;
        }

        $data = array();
        $amount = $this->amount;
        $data['amount'] = $amount; 
        $data['setting'] = $this->setting;
        $data['api_error'] = array();
        $data['online_data'] = $online_data;
        $data['reference_no'] = $online_data->reference_no;
        $data['name'] = trim($online_data->firstname . ' ' . $online_data->lastname);
        $data['merchant_order_id'] = time() . "01";
        $data['txnid'] = time() . "02";
        $data['title'] = $this->lang->line('online_admission_form_fees');
        $data['return_url'] = site_url() . 'onlineadmission/razorpay/complete';
        $total_paise = round($amount * 100);
        $data['total'] = $total_paise;
        $data['key_id'] = $this->pay_method->api_publishable_key;
        $data['currency_code'] = !empty($this->setting->currency) ? $this->setting->currency : 'INR';

        // Keep payment_amount in session for reference
        $this->session->set_userdata('payment_amount', $amount);

        // Attempt to create Razorpay Order for reliable checkout (avoids BAD_REQUEST_ERROR on UPI/Cards)
        $order_id = '';
        if (!empty($this->pay_method->api_publishable_key) && !empty($this->pay_method->api_secret_key)) {
            $order_payload = array(
                'amount'          => $total_paise,
                'currency'        => $data['currency_code'],
                'receipt'         => 'adm_' . $online_data->reference_no,
                'payment_capture' => 1,
                'notes'           => array(
                    'admission_id' => $reference,
                    'reference_no' => $online_data->reference_no,
                )
            );

            $ch = curl_init('https://api.razorpay.com/v1/orders');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($order_payload));
            curl_setopt($ch, CURLOPT_USERPWD, $this->pay_method->api_publishable_key . ':' . $this->pay_method->api_secret_key);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $order_resp = curl_exec($ch);
            $order_http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($order_http == 200) {
                $order_arr = json_decode($order_resp, true);
                if (!empty($order_arr['id'])) {
                    $order_id = $order_arr['id'];
                    $this->session->set_userdata('razorpay_order_id', $order_id);
                }
            }
        }
        $data['order_id'] = $order_id;

        $this->load->view('onlineadmission/razorpay/index', $data);
    }

    public function complete() {
        $reference = $this->session->userdata('reference');
        if (empty($reference)) {
            if ($this->input->is_ajax_request()) {
                echo json_encode(array('status' => 'fail', 'message' => 'Session expired. Please try again.'));
                return;
            }
            redirect(base_url('onlineadmission/checkout'));
            return;
        }

        $online_data = $this->onlinestudent_model->getAdmissionData($reference);
        if (empty($online_data)) {
            if ($this->input->is_ajax_request()) {
                echo json_encode(array('status' => 'fail', 'message' => 'Invalid admission record.'));
                return;
            }
            redirect(base_url('onlineadmission/checkout'));
            return;
        }

        $transactionid = $this->input->post('razorpay_payment_id');
        $order_id = $this->input->post('razorpay_order_id');
        $signature = $this->input->post('razorpay_signature');

        if (empty($transactionid)) {
            if ($this->input->is_ajax_request()) {
                echo json_encode(array('status' => 'fail', 'reference_no' => $online_data->reference_no, 'message' => 'Payment ID missing.'));
                return;
            }
            redirect(base_url("onlineadmission/checkout/paymentfailed/" . $online_data->reference_no));
            return;
        }

        $api_key = $this->pay_method->api_publishable_key;
        $api_secret = $this->pay_method->api_secret_key;
        $expected_amount_paise = round($this->amount * 100);

        // Step 1: Verify payment status directly with Razorpay API
        $ch = curl_init('https://api.razorpay.com/v1/payments/' . urlencode($transactionid));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $api_key . ':' . $api_secret);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $api_response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $payment_info = json_decode($api_response, true);

        // Step 2: If status is 'authorized', attempt explicit capture
        if ($http_code == 200 && isset($payment_info['status']) && $payment_info['status'] === 'authorized') {
            $cap_ch = curl_init('https://api.razorpay.com/v1/payments/' . urlencode($transactionid) . '/capture');
            curl_setopt($cap_ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($cap_ch, CURLOPT_POST, true);
            curl_setopt($cap_ch, CURLOPT_POSTFIELDS, http_build_query(array(
                'amount'   => $expected_amount_paise,
                'currency' => !empty($this->setting->currency) ? $this->setting->currency : 'INR'
            )));
            curl_setopt($cap_ch, CURLOPT_USERPWD, $api_key . ':' . $api_secret);
            curl_setopt($cap_ch, CURLOPT_TIMEOUT, 20);
            curl_setopt($cap_ch, CURLOPT_SSL_VERIFYPEER, false);
            $cap_response = curl_exec($cap_ch);
            $cap_http = curl_getinfo($cap_ch, CURLINFO_HTTP_CODE);
            curl_close($cap_ch);

            $cap_info = json_decode($cap_response, true);
            if ($cap_http == 200 && isset($cap_info['status']) && $cap_info['status'] === 'captured') {
                $payment_info = $cap_info;
            }
        }

        // Step 3: Strictly check if payment is confirmed as CAPTURED and AMOUNT matches
        $is_verified = false;
        if ($http_code == 200 && isset($payment_info['status']) && $payment_info['status'] === 'captured') {
            // Check paid amount matches expected fee
            if ((int)$payment_info['amount'] === (int)$expected_amount_paise) {
                $is_verified = true;
            }
        }

        if ($is_verified) {
            $apply_date = date("Y-m-d H:i:s");
            $date = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($apply_date));

            $gateway_response = array();
            $gateway_response['admission_id']   = $reference; 
            $gateway_response['paid_amount']    = $this->amount;
            $gateway_response['transaction_id'] = $transactionid;
            $gateway_response['payment_mode']   = 'razorpay';
            $gateway_response['payment_type']   = 'online';
            $gateway_response['note']           = "Payment deposit through Razorpay TXN ID: " . $transactionid . " [Verified & Captured]";
            $gateway_response['date']           = date("Y-m-d H:i:s");

            $this->onlinestudent_model->paymentSuccess($gateway_response);

            $sender_details = array(
                'firstname'    => $online_data->firstname,
                'lastname'     => $online_data->lastname,
                'email'        => $online_data->email,
                'date'         => $date,
                'reference_no' => $online_data->reference_no,
                'mobileno'     => $online_data->mobileno,
                'paid_amount'  => $this->amount
            );
            $this->mailsmsconf->mailsms('online_admission_fees_submission', $sender_details);

            if ($this->input->is_ajax_request()) {
                echo json_encode(array(
                    'status'       => 'success',
                    'reference_no' => $online_data->reference_no
                ));
            } else {
                redirect(base_url("onlineadmission/checkout/successinvoice/" . $online_data->reference_no));
            }
        } else {
            // Payment failed, not captured, or amount mismatch!
            $error_reason = isset($payment_info['error_description']) ? $payment_info['error_description'] : (isset($payment_info['status']) ? 'Payment status: ' . $payment_info['status'] : 'Verification failed');
            log_message('error', 'Razorpay Payment NOT captured for admission ID ' . $reference . '. Reason: ' . $error_reason . ' Payload: ' . $api_response);

            if ($this->input->is_ajax_request()) {
                echo json_encode(array(
                    'status'       => 'fail',
                    'reference_no' => $online_data->reference_no,
                    'message'      => $error_reason
                ));
            } else {
                redirect(base_url("onlineadmission/checkout/paymentfailed/" . $online_data->reference_no));
            }
        }
    }

}
