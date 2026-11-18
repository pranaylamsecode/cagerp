<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Paypal extends OnlineAdmission_Controller
{

    public $pay_method = "";
    public $amount = 0;

    function __construct()
    {
        parent::__construct();
        $this->pay_method = $this->paymentsetting_model->getActiveMethod();
        $this->setting = $this->setting_model->getSetting();
        $this->amount = $this->setting->enquiry_form_amount;
        $this->currency = $this->setting_model->getCurrency();
        $this->load->library('paypal_payment');
        $this->load->library('mailsmsconf');
        $this->load->model('Enquiry_with_scanner_model');
    }

    public function index()
    {
        $this->session->set_userdata('payment_amount', $this->amount);
        $data['setting'] = $this->setting;
        $total = $this->amount;
        $data['amount'] = $total;
        $this->load->view('enquiry/paypal/index', $data);
    }


    public function checkout()
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            if ($this->session->has_userdata('payment_amount')) {
                $setting                = $this->setting;
                $reference = $this->session->userdata('reference');
                $total = $this->amount;
                $enquiry_data = $this->Enquiry_with_scanner_model->get($reference);
                $data["id"]             = $reference;
                $data['total']          = $total;
                $data['productinfo']    = "Enquiry form Fees";
                $data['guardian_phone'] = "";
                $data['name']           = $enquiry_data['firstname'] . " " . $enquiry_data['lastname'];
                $payment = array(
                    'guardian_phone' => $data['guardian_phone'],
                    'name' => $data['name'],
                    'description' => $data['productinfo'],
                    'amount' => $data['total'],
                    'currency' => $this->currency,
                );

                $payment['cancelUrl'] = base_url('Enquiryform_payment/paypal/getsuccesspayment');
                $payment['returnUrl'] = base_url('Enquiryform_payment/paypal/getsuccesspayment');
                $response               = $this->paypal_payment->payment($payment);
                if ($response->isSuccessful()) {
                } elseif ($response->isRedirect()) {
                    $response->redirect();
                } else {

                    echo $response->getMessage();
                }
            }
        }
    }

    //paypal successpayment
    public function getsuccesspayment()
    {
        $reference = $this->session->userdata('reference');
        $total = $this->amount;
        $enquiry_data = $this->Enquiry_with_scanner_model->get($reference);
        $data["id"]             = $reference;
        $data['total']          = $total;
        $data['productinfo']    = "Enquiry Form Fees";
        $data['guardian_phone'] = "";
        $data['name']           = $enquiry_data['firstname'] . " " . $enquiry_data['lastname'];
        $success_data = array(
            'guardian_phone' => $data['guardian_phone'],
            'name' => $data['name'],
            'description' => "Online Admission Fees",
            'amount' => $data['total'],
            'currency' => $this->currency,
        );

        $success_data['cancelUrl'] = base_url('Enquiryform_payment/paypal/getsuccesspayment');
        $success_data['returnUrl'] = base_url('Enquiryform_payment/paypal/getsuccesspayment');
        $response = $this->paypal_payment->success($success_data);

        $paypalResponse = $response->getData();
        if ($response->isSuccessful()) {
            $purchaseId = $_GET['PayerID'];

            if (isset($paypalResponse['PAYMENTINFO_0_ACK']) && $paypalResponse['PAYMENTINFO_0_ACK'] === 'Success') {
                if ($purchaseId) {

                    $ref_id = $paypalResponse['PAYMENTINFO_0_TRANSACTIONID'];
                    $gateway_response['enquiry_id']   = $reference;
                    $gateway_response['paid_amount']    = $total;
                    $gateway_response['transaction_id'] = $ref_id;
                    $gateway_response['payment_mode']   = 'paypal';
                    $gateway_response['payment_type']   = 'online';
                    $gateway_response['note']           = "Payment deposit through Paypal Ref. Id ID: " . $ref_id;
                    $gateway_response['date']           = date("Y-m-d H:i:s");
                    $return_detail                      = $this->Enquiry_with_scanner_model->paymentSuccess($gateway_response);
                    // $sender_details = array('firstname' => $enquiry_data['firstname'], 'lastname' => $enquiry_data['lastname'], 'email' => $enquiry_data['email'], 'date' => $date, 'reference_no' => $enquiry_data['reference_no'], 'mobileno' => $enquiry_data['mobileno'], 'paid_amount' => $amount);
                    // $this->mailsmsconf->mailsms('online_admission_fees_submission', $sender_details);
                    redirect(base_url("enquiryform/thank_you/" . $enquiry_data['reference_no']));
                }
            }
        } elseif ($response->isRedirect()) {
            $response->redirect();
        } else {
            redirect(base_url("Enquiryform_payment/checkout/paymentfailed/" . $enquiry_data['reference_no']));
        }
    }
}
