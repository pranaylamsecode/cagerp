<?php
class Enquiryform extends Admin_Controller
{
    public $sch_setting_detail = array();

    public function __construct()
    {
        parent::__construct();

        $this->load->model("enquiry_with_scanner_model");
        $this->sch_setting_detail = $this->setting_model->getSetting();
        $this->role;
    }

    public function enquirysetting()
    {
        $this->session->set_userdata('top_menu', 'System Settings');
        $this->session->set_userdata('sub_menu', 'System Settings/enquiryformsetting');
        $data                    = array();
        $data['result']          = $this->setting_model->getSetting();
        $data['fields']          = get_enquiry_form_editable_fields();
        $data['inserted_fields'] = $this->enquiry_with_scanner_model->getformfields();
        $data['sch_setting_detail'] = $this->sch_setting_detail;
        $data['custom_fields'] = $this->enquiry_with_scanner_model->getcustomfields();

        if (!empty($this->input->post('submitbtn'))) {

            if ($this->input->post('enquiry_form_payment') == 'yes') {

                $this->form_validation->set_rules(
                    'enquiry_form_amount',
                    $this->lang->line('amount'),
                    array(
                        'required',
                        'xss_clean',
                        array('check_exists', array($this->enquiry_with_scanner_model, 'validate_paymentamount')),
                    )
                );

                if ($this->form_validation->run() == true) {
                    $data = array(
                        'enquiry_form'               => $this->input->post('enquiry_form'),
                        'enquiry_form_payment'       => $this->input->post('enquiry_form_payment'),
                        'enquiry_form_amount'        => $this->input->post('enquiry_form_amount'),
                        'online_admission_instruction'   => $this->input->post('online_admission_instruction'),
                        'online_admission_conditions'    => $this->input->post('online_admission_conditions'),
                        'id'                             => 1
                    );
                    $this->setting_model->add($data);
                    redirect('admin/enquiryform/enquirysetting');
                } else {

                    $this->load->view("layout/header");
                    $this->load->view("admin/enquiryform/enquiryform_setting", $data);
                    $this->load->view("layout/footer");
                }
            } else {

                $data = array(
                    'enquiry_form'          => $this->input->post('enquiry_form'),
                    'enquiry_form_payment'  => 'no',
                    'id' => 1
                );
                $this->setting_model->add($data);
                redirect('admin/enquiryform/enquirysetting');
            }
        } else {
            $this->load->view("layout/header");
            $this->load->view("admin/enquiryform/enquiryform_setting", $data);
            $this->load->view("layout/footer");
        }
    }

    public function changeformfieldsetting()
    {

        $this->form_validation->set_rules('name', $this->lang->line('student'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('status', $this->lang->line('status'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {

            $msg = array(
                'status' => form_error('status'),
                'name'   => form_error('name'),
            );

            $array = array('status' => '0', 'error' => $msg, 'msg' => $this->lang->line('something_went_wrong'));
        } else {
            $insert = array(
                'name'   => $this->input->post('name'),
                'status' => $this->input->post('status'),
            );


            $this->enquiry_with_scanner_model->addformfields($insert);

            if ($this->input->post('name') == 'if_guardian_is') {

                $status = $this->input->post('status');
                $this->enquiry_with_scanner_model->editguardianfield($status);
            }

            $array = array('status' => '1', 'error' => '', 'msg' => $this->lang->line('success_message'));
        }

        echo json_encode($array);
    }
}
