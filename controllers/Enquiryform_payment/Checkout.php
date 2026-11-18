<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Checkout extends OnlineAdmission_Controller
{
    public $pay_method;
    public $setting;

    public function __construct()
    {
        parent::__construct();
        $this->setting = $this->setting_model->getSetting();
        $this->pay_method = $this->paymentsetting_model->getActiveMethod();
    }

    public function index()
    {

        $post_data = $this->security->xss_clean($this->input->post());
        $reference = $post_data['id'];
        $this->session->set_userdata("reference", $reference);
        $data = array();

        // print_r('data is:' . $this->session->userdata("reference"));

        if (!empty($this->pay_method)) {
            if ($this->pay_method->payment_type == "payu") {
                redirect(base_url("Enquiryform_payment/payu"));
            } elseif ($this->pay_method->payment_type == "stripe") {
                redirect(base_url("Enquiryform_payment/stripe"));
            } elseif ($this->pay_method->payment_type == "ccavenue") {
                redirect(base_url("Enquiryform_payment/ccavenue"));
            } elseif ($this->pay_method->payment_type == "paypal") {
                redirect(base_url("Enquiryform_payment/paypal"));
            } elseif ($this->pay_method->payment_type == "instamojo") {
                redirect(base_url("Enquiryform_payment/instamojo"));
            } elseif ($this->pay_method->payment_type == "paytm") {
                redirect(base_url("Enquiryform_payment/paytm"));
            } elseif ($this->pay_method->payment_type == "razorpay") {
                redirect(base_url("Enquiryform_payment/razorpay"));
            } elseif ($this->pay_method->payment_type == "paystack") {
                redirect(base_url("Enquiryform_payment/paystack"));
            } elseif ($this->pay_method->payment_type == "midtrans") {
                redirect(base_url("Enquiryform_payment/midtrans"));
            } elseif ($this->pay_method->payment_type == "ipayafrica") {
                redirect(base_url("Enquiryform_payment/ipayafrica"));
            } elseif ($this->pay_method->payment_type == "jazzcash") {
                redirect(base_url("Enquiryform_payment/jazzcash"));
            } elseif ($this->pay_method->payment_type == "pesapal") {
                redirect(base_url("Enquiryform_payment/pesapal"));
            } elseif ($this->pay_method->payment_type == "flutterwave") {
                redirect(base_url("Enquiryform_payment/flutterwave"));
            } elseif ($this->pay_method->payment_type == "billplz") {
                redirect(base_url("Enquiryform_payment/billplz"));
            } elseif ($this->pay_method->payment_type == "sslcommerz") {
                redirect(base_url("Enquiryform_payment/sslcommerz"));
            }
        }
    }

    public function successinvoice($reference_no)
    {
        $data['setting'] = $this->setting;
        $data["reference_no"] = $reference_no;
        $this->load->view("enquiry/success_invoice", $data);
    }

    public function paymentfailed($reference_no)
    {
        $data['setting'] = $this->setting;
        $data["reference_no"] = $reference_no;
        $this->load->view("enquiry/payment_failed", $data);
    }
}
