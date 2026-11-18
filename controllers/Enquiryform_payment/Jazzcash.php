<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Jazzcash extends OnlineAdmission_Controller
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
        date_default_timezone_set("Asia/Karachi");
    }

    public function index()
    {

        $reference = $this->session->userdata('reference');
        $data['setting'] = $this->setting;
        $total = $this->amount;
        $data['amount'] = $total;
        $this->load->view('enquiry/jazzcash/index', $data);
    }

    public function pay()
    {
        $this->session->set_userdata('payment_amount', $this->amount);
        $reference = $this->session->userdata('reference');
        $enquiry_data = $this->Enquiry_with_scanner_model->get($reference);
        $data['total']                         = $this->amount;
        $amount = number_format((float)($this->amount), 2, '.', '');
        $data = array();
        $data['setting'] = $this->setting;
        $data['api_error'] = array();
        $data['name'] = $enquiry_data['firstname'] . ' ' . $enquiry_data['lastname'];
        $data['title'] = 'Enquiry Form Fees';
        $data['return_url'] = base_url() . 'Enquiryform_payment/jazzcash/complete';
        $data['pp_MerchantID'] = $this->pay_method->api_secret_key;
        $data['pp_Password'] = $this->pay_method->api_password;
        $data['currency_code'] = $this->setting->currency;
        $data['guardian_phone'] = $enquiry_data['mobileno'];
        $data['ExpiryTime'] = date('YmdHis', strtotime("+3 hours"));
        $data['TxnDateTime'] = date('YmdHis', strtotime("+0 hours"));
        $data['TxnRefNumber'] = "T" . date('YmdHis');
        $input_para["pp_Version"] = "2.0";
        $input_para["pp_IsRegisteredCustomer"] = "Yes";
        $input_para["pp_TxnType"] = "MPAY";
        $input_para["pp_TokenizedCardNumber"] = "";
        $input_para["pp_CustomerID"] = time();
        $input_para["pp_CustomerEmail"] = "";
        $input_para["pp_CustomerMobile"] = "";
        $input_para["pp_MerchantID"] = $data['pp_MerchantID'];
        $input_para["pp_Language"] = "EN";
        $input_para["pp_SubMerchantID"] = "";
        $input_para["pp_Password"] = $data['pp_Password'];
        $input_para["pp_TxnRefNo"] = $data['TxnRefNumber'];
        $input_para["pp_Amount"] = $amount * 100;
        $input_para["pp_DiscountedAmount"] = "";
        $input_para["pp_DiscountBank"] = "";
        $input_para["pp_TxnCurrency"] = "PKR";
        $input_para["pp_TxnDateTime"] = $data['TxnDateTime'];
        $input_para["pp_TxnExpiryDateTime"] = $data['ExpiryTime'];
        $input_para["pp_BillReference"] = time();
        $input_para["pp_Description"] = 'Enquiry Form Fees';
        $input_para["pp_ReturnURL"] = $data['return_url'];
        $input_para["pp_SecureHash"] = "0123456789";
        $input_para["ppmpf_1"] = "1";
        $input_para["ppmpf_2"] = "2";
        $input_para["ppmpf_3"] = "3";
        $input_para["ppmpf_4"] = "4";
        $input_para["ppmpf_5"] = "5";
        $data['payment_data'] = $input_para;
        $this->load->view('enquiry/jazzcash/pay', $data);
    }

    public function complete()
    {
        $reference  = $this->session->userdata('reference');
        $enquiry_data = $this->Enquiry_with_scanner_model->get($reference);
        $apply_date = date("Y-m-d H:i:s");
        $date         = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($apply_date));
        $data = array();
        if ($_POST['pp_ResponseCode'] == '000') {
            $amount                           = $this->session->userdata('payment_amount');
            $reference  = $this->session->userdata('reference');
            $gateway_response['enquiry_id']   = $reference;
            $transactionid                      = $_POST['pp_TxnRefNo'];
            $gateway_response['paid_amount']    = ($amount);
            $gateway_response['transaction_id'] = $transactionid;
            $gateway_response['payment_mode']   = 'jazzcash';
            $gateway_response['payment_type']   = 'online';
            $gateway_response['date']           = date("Y-m-d H:i:s");
            $gateway_response['note']           = "Payment deposit through Jazzcash TXN ID: " . $transactionid;
            $return_detail                      = $this->Enquiry_with_scanner_model->paymentSuccess($gateway_response);
            // $sender_details = array('firstname' => $enquiry_data['firstname'], 'lastname' => $enquiry_data['lastname'], 'email' => $enquiry_data['email'], 'date' => $date, 'reference_no' => $enquiry_data['reference_no'], 'mobileno' => $enquiry_data['mobileno'], 'paid_amount' => $amount);
            // $this->mailsmsconf->mailsms('online_admission_fees_submission', $sender_details);
            redirect(base_url("enquiryform/thank_you/" . $enquiry_data['reference_no']));
        } elseif ($_POST['pp_ResponseCode'] == '112') {
            redirect(base_url("Enquiryform_payment/checkout/paymentfailed/" . $enquiry_data['reference_no']));
        } else {
            $this->session->set_flashdata('msg', $_POST['pp_ResponseMessage']);
            redirect(base_url("Enquiryform_payment/checkout/paymentfailed/" . $enquiry_data['reference_no']));
        }
    }
}
