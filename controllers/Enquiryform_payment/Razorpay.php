<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Razorpay extends OnlineAdmission_Controller
{

    public $pay_method = "";
    public $amount = 0;

    function __construct()
    {
        parent::__construct();
        $this->pay_method = $this->paymentsetting_model->getActiveMethod();
        $this->setting = $this->setting_model->getSetting();
        $this->amount = $this->setting->enquiry_form_amount;
        $this->load->library('mailsmsconf');
        $this->load->model('Enquiry_with_scanner_model');
    }

    public function index()
    {
        $data = array();
        $amount = $this->amount;
        $data['amount'] = $amount;
        $data['setting'] = $this->setting;
        $data['api_error'] = array();
        $reference = $this->session->userdata('reference');
        $enquiry_data = $this->Enquiry_with_scanner_model->get($reference);

        // print_r($enquiry_data);
        // die;

        $lastname = !empty($enquiry_data['lastname']) ? $enquiry_data['lastname'] : "";
        $data['name'] = $enquiry_data['firstname'] . ' ' . $lastname;
        $data['merchant_order_id'] = time() . "01";
        $data['txnid'] = time() . "02";
        $data['title'] = "Enquiry Form Fees";
        $data['return_url'] = site_url() . 'Enquiryform_payment/razorpay/complete';
        $data['total'] = $amount * 100;
        $data['key_id'] = $this->pay_method->api_publishable_key;
        $data['currency_code'] = $this->setting->currency;

        $this->load->view('enquiry/razorpay/index', $data);
    }

    public function complete()
    {
        $amount = $this->session->userdata('payment_amount');
        $reference  = $this->session->userdata('reference');
        $enquiry_data = $this->Enquiry_with_scanner_model->get($reference);
        $apply_date = date("Y-m-d H:i:s");
        $date         = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($apply_date));
        $data   = array();
        if (isset($_POST['razorpay_payment_id'])) {

            $transactionid                      = $_POST['razorpay_payment_id'];
            $gateway_response['enquiry_id']     = $reference;
            $gateway_response['paid_amount']    = $this->amount;
            $gateway_response['transaction_id'] = $transactionid;
            $gateway_response['payment_mode']   = 'razorpay';
            $gateway_response['payment_type']   = 'online';
            $gateway_response['note']           = "Payment deposit through Razorpay TXN ID: " . $transactionid;
            $gateway_response['date']           = date("Y-m-d H:i:s");
            $return_detail                      = $this->Enquiry_with_scanner_model->paymentSuccess($gateway_response);
            // $sender_details = array('firstname' => $enquiry_data['firstname'], 'lastname' => $enquiry_data['lastname'], 'email' => $enquiry_data['email'], 'date' => $date, 'reference_no' => $enquiry_data['reference_no'], 'mobileno' => $enquiry_data['mobileno'], 'paid_amount' => $amount);
            // $this->mailsmsconf->mailsms('online_admission_fees_submission', $sender_details);
            echo $enquiry_data['reference_no'];
        } else {
            redirect(base_url("Enquiryform_payment/checkout/paymentfailed/" . $enquiry_data['reference_no']));
        }
    }
}
