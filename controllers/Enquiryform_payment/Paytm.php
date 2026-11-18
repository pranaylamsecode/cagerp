<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Paytm extends OnlineAdmission_Controller
{

    public $pay_method = "";
    public $amount = 0;

    function __construct()
    {
        parent::__construct();
        $this->pay_method = $this->paymentsetting_model->getActiveMethod();
        $this->setting = $this->setting_model->getSetting();
        $this->amount = $this->setting->enquiry_form_amount;
        $this->load->library('paytm_lib');
        $this->load->library('mailsmsconf');
        $this->load->model('Enquiry_with_scanner_model');
    }

    public function index()
    {
        $reference = $this->session->userdata('reference');
        $params = $this->session->userdata('params');
        $data = array();
        $data['params'] = $params;
        $data['setting'] = $this->setting;
        $data['api_error'] = array();
        $amount = $this->amount;
        $data['amount'] = $amount;
        $paytmParams = array();
        $ORDER_ID = time();
        $CUST_ID = time();

        $paytmParams = array(
            "MID" => $this->pay_method->api_publishable_key,
            "WEBSITE" => $this->pay_method->paytm_website,
            "INDUSTRY_TYPE_ID" => $this->pay_method->paytm_industrytype,
            "CHANNEL_ID" => "WEB",
            "ORDER_ID" => $ORDER_ID,
            "CUST_ID" => $reference,
            "TXN_AMOUNT" => $data['amount'],
            "CALLBACK_URL" => base_url() . "Enquiryform_payment/paytm/complete",
        );

        $paytmChecksum = $this->paytm_lib->getChecksumFromArray($paytmParams, $this->pay_method->api_secret_key);
        $paytmParams["CHECKSUMHASH"] = $paytmChecksum;

        $transactionURL = 'https://securegw.paytm.in/order/process';
        $data['paytmParams'] = $paytmParams;
        $data['transactionURL'] = $transactionURL;
        $this->load->view("enquiry/paytm/index", $data);
    }

    public function complete()
    {
        $reference  = $this->session->userdata('reference');
        $paytmChecksum = "";
        $paramList = array();
        $isValidChecksum = "FALSE";
        $paramList = $_POST;
        $paytmChecksum = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : "";
        $isValidChecksum = $this->paytm_lib->verifychecksum_e($paramList, $this->pay_method->api_secret_key, $paytmChecksum);
        $enquiry_data = $this->Enquiry_with_scanner_model->get($reference);

        if ($isValidChecksum == "TRUE") {

            if ($_POST["STATUS"] == "TXN_SUCCESS") {


                $apply_date = date("Y-m-d H:i:s");
                $date         = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($apply_date));
                $amount = $this->session->userdata('payment_amount');
                $transactionid = $_POST['TXNID'];
                $gateway_response['enquiry_id']   = $reference;
                $gateway_response['paid_amount']    = $this->amount;
                $gateway_response['transaction_id'] = $transactionid;
                $gateway_response['payment_mode']   = 'paytm';
                $gateway_response['payment_type']   = 'online';
                $gateway_response['note']           = "Payment deposit through Paytm TXN ID: " . $transactionid;
                $gateway_response['date']           = date("Y-m-d H:i:s");
                $return_detail                      = $this->Enquiry_with_scanner_model->paymentSuccess($gateway_response);
                // $sender_details = array('firstname' => $enquiry_data['firstname'], 'lastname' => $enquiry_data['lastname'], 'email' => $enquiry_data['email'], 'date' => $date, 'reference_no' => $enquiry_data['reference_no'], 'mobileno' => $enquiry_data['mobileno'], 'paid_amount' => $amount);
                // $this->mailsmsconf->mailsms('online_admission_fees_submission', $sender_details);
                redirect(base_url("enquiryform/thank_you/" . $enquiry_data['reference_no']));
            } else {
                redirect(base_url("Enquiryform_payment/checkout/paymentfailed/" . $enquiry_data['reference_no']));
            }
        } else {
            redirect(base_url("Enquiryform_payment/checkout/paymentfailed/" . $enquiry_data['reference_no']));
        }
    }
}
