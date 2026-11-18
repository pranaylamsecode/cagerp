<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Midtrans extends OnlineAdmission_Controller
{

    public $pay_method = "";
    public $amount = 0;

    function __construct()
    {
        parent::__construct();
        $this->pay_method = $this->paymentsetting_model->getActiveMethod();
        $this->setting = $this->setting_model->getSetting();
        $this->amount = $this->setting->enquiry_form_amount;
        $this->load->library('midtrans_lib');
        $this->load->library('mailsmsconf');
        $this->load->model('Enquiry_with_scanner_model');
    }

    public function index()
    {
        $data = array();
        $reference = $this->session->userdata('reference');
        $data['setting'] = $this->setting;
        $total = $this->amount;
        $data['amount'] = $total;
        $enable_payments = array('credit_card');
        $transaction = array(
            'enabled_payments' => $enable_payments,
            'transaction_details' => array(
                'order_id' => time(),
                'gross_amount' => round($total), // no decimal allowed
            ),
        );
        $data['return_url'] = base_url() . "Enquiryform_payment/midtrans/complete";
        $snapToken = $this->midtrans_lib->getSnapToken($transaction, $this->pay_method->api_secret_key);
        $data['snap_Token'] = $snapToken;
        $this->load->view('enquiry/midtrans/index', $data);
    }

    public function complete()
    {
        $reference  = $this->session->userdata('reference');
        $enquiry_data = $this->Enquiry_with_scanner_model->get($reference);
        $apply_date = date("Y-m-d H:i:s");
        $date         = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($apply_date));
        $amount = $this->amount;
        $response                           = json_decode($_POST['result_data']);
        $payment_id                         = $response->transaction_id;
        $transactionid                      = $payment_id;
        $gateway_response['enquiry_id']   = $reference;
        $gateway_response['paid_amount']    = $this->amount;
        $gateway_response['transaction_id'] = $transactionid;
        $gateway_response['payment_mode']   = 'midtrans';
        $gateway_response['payment_type']   = 'online';
        $gateway_response['note']           = "Payment deposit through Midtrans TXN ID: " . $transactionid;
        $gateway_response['date']           = date("Y-m-d H:i:s");
        $return_detail                      = $this->Enquiry_with_scanner_model->paymentSuccess($gateway_response);
        // $sender_details = array('firstname' => $enquiry_data['firstname'], 'lastname' => $enquiry_data['lastname'], 'email' => $enquiry_data['email'], 'date' => $date, 'reference_no' => $enquiry_data['reference_no'], 'mobileno' => $enquiry_data['mobileno'], 'paid_amount' => $amount);
        // $this->mailsmsconf->mailsms('online_admission_fees_submission', $sender_details);
        echo $enquiry_data['reference_no'];
    }
}
