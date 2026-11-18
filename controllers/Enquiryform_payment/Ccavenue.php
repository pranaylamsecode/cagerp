<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Ccavenue extends OnlineAdmission_Controller
{

    public $pay_method = "";
    public $amount = 0;

    function __construct()
    {
        parent::__construct();
        $this->pay_method = $this->paymentsetting_model->getActiveMethod();
        $this->setting = $this->setting_model->getSetting();
        $this->amount = $this->setting->enquiry_form_amount;
        $this->load->library(array('Ccavenue_crypto', 'mailsmsconf'));
        $this->load->model('Enquiry_with_scanner_model');
    }

    public function index()
    {

        $reference = $this->session->userdata('reference');
        $data['setting'] = $this->setting;
        $total = $this->amount;
        $data['amount'] = $total;
        $this->load->view('enquiry/ccavenue/index', $data);
    }

    public function pay()
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $this->session->set_userdata('payment_amount', $this->amount);
            $amount                  = $this->amount;
            $details['tid']          = abs(crc32(uniqid()));
            $details['merchant_id']  = $this->pay_method->api_secret_key;
            $details['order_id']     = abs(crc32(uniqid()));
            $details['amount']       = number_format($this->amount);
            $details['currency']     = $this->setting->currency;
            $details['redirect_url'] = base_url('Enquiryform_payment/ccavenue/success');
            $details['cancel_url']   = base_url('Enquiryform_payment/ccavenue/cancel');
            $details['language']     = "EN";
            $details['billing_name'] = "title";
            $merchant_data           = "";
            foreach ($details as $key => $value) {
                $merchant_data .= $key . '=' . $value . '&';
            }
            $data['encRequest']  = $this->ccavenue_crypto->encrypt($merchant_data, $this->pay_method->salt);
            $data['access_code'] = $this->pay_method->api_publishable_key;
            $this->load->view('enquiry/ccavenue/pay', $data);
        } else {
            redirect(base_url('Enquiryform_payment/checkout'));
        }
    }

    public function success()
    {

        $status     = array();
        $rcvdString = "";
        $total_amount   = $this->amount;
        $reference  = $this->session->userdata('reference');
        $enquiry_data = $this->Enquiry_with_scanner_model->get($reference);

        $apply_date = date("Y-m-d H:i:s");
        $date         = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($apply_date));

        if (!empty($total_amount)) {

            $encResponse = $_POST["encResp"];
            $rcvdString  = $this->ccavenue_crypto->decrypt($encResponse, $this->pay_method->salt);

            if ($rcvdString !== '') {

                $decryptValues = explode('&', $rcvdString);
                $dataSize      = sizeof($decryptValues);
                for ($i = 0; $i < $dataSize; $i++) {
                    $information             = explode('=', $decryptValues[$i]);
                    $status[$information[0]] = $information[1];
                }
            }

            if (!empty($status)) {
                if ($status['order_status'] == "Success") {

                    $tracking_id = $status['tracking_id'];
                    $bank_ref_no = $status['bank_ref_no'];


                    $gateway_response['enquiry_id']   = $reference;
                    $gateway_response['paid_amount']    = $total_amount;
                    $gateway_response['transaction_id'] = $tracking_id;
                    $gateway_response['payment_mode']   = 'ccavenue';
                    $gateway_response['payment_type']   = 'online';
                    $gateway_response['note']           = "Enquiry fees deposit through CCAvenue. TXN ID: " . $tracking_id . " Bank Ref. No.: " . $bank_ref_no;
                    $gateway_response['date']           = date("Y-m-d H:i:s");
                    $return_detail                      = $this->Enquiry_with_scanner_model->paymentSuccess($gateway_response);
                    // $sender_details = array('firstname' => $enquiry_data['firstname'], 'lastname' => $enquiry_data['lastname'], 'email' => $enquiry_data['email'], 'date' => $date, 'reference_no' => $enquiry_data['reference_no'], 'mobileno' => $enquiry_data['mobileno'], 'paid_amount' => $total_amount);
                    // $this->mailsmsconf->mailsms('online_admission_fees_submission', $sender_details);
                    redirect(base_url("enquiryform/thank_you/" . $enquiry_data['reference_no']));
                } else if ($status['order_status'] === "Aborted") {
                    echo "<br>We will keep you posted regarding the status of your order through e-mail";
                } else if ($status['order_status'] === "Failure") {
                    redirect(base_url("Enquiryform_payment/checkout/paymentfailed/" . $enquiry_data['reference_no']));
                } else {
                    echo "<br>Security Error. Illegal access detected";
                }
            }
        } else {
            redirect(base_url("Enquiryform_payment/checkout/paymentfailed/" . $enquiry_data['reference_no']));
        }
    }

    public function cancel()
    {
        $reference  = $this->session->userdata('reference');
        $enquiry_data = $this->Enquiry_with_scanner_model->get($reference);
        redirect(base_url("Enquiryform_payment/checkout/paymentfailed/" . $enquiry_data['reference_no']));
    }
}
