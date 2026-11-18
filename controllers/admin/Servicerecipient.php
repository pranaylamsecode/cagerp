<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Servicerecipient extends Admin_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model("servicerecipient_model");
    }

    function index()
    {
        if (!$this->rbac->hasPrivilege('service_recipient', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Rent System');
        $this->session->set_userdata('sub_menu', 'servicerecipient/index');
        $data['title'] = 'Service Recipient List';
        $itemsupplier_result = $this->servicerecipient_model->get();
        $data['itemsupplierlist'] = $itemsupplier_result;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/servicerecipient/servicerecipientList', $data);
        $this->load->view('layout/footer', $data);
    }

    function delete($id)
    {
        if (!$this->rbac->hasPrivilege('service_recipient', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'Service Recipient List';
        $this->servicerecipient_model->remove($id);
        redirect('admin/servicerecipient/index');
    }

    function create()
    {
        if (!$this->rbac->hasPrivilege('service_recipient', 'can_add')) {
            access_denied();
        }
        $data['title'] = 'Add Service Recipient';
        $itemsupplier_result = $this->servicerecipient_model->get();
        $data['itemsupplierlist'] = $itemsupplier_result;

        $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('address', $this->lang->line('address'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('gstin', $this->lang->line('gstin'), 'trim|xss_clean|required');


        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/servicerecipient/servicerecipientList', $data);
            $this->load->view('layout/footer', $data);
        } else {


            $data = array(
                // 'phone' => $this->input->post('phone'),
                // 'contact_person_phone' => $this->input->post('contact_person_phone'),
                'service_recipient' => $this->input->post('name'),
                // 'email' => $this->input->post('email'),
                'address' => $this->input->post('address'),
                'gstin_no' => $this->input->post('gstin'),
                'pan_no' => $this->input->post('pan_no'),
                // 'description' => $this->input->post('description'),
            );
            $this->servicerecipient_model->add($data);
            $this->session->set_userdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/servicerecipient/index');
        }
    }

    function edit($id)
    {
        if (!$this->rbac->hasPrivilege('service_recipient', 'can_edit')) {
            access_denied();
        }
        $data['title'] = 'Edit Service Recipient';
        $itemsupplier_result = $this->servicerecipient_model->get();
        $data['itemsupplierlist'] = $itemsupplier_result;
        $data['id'] = $id;
        $store = $this->servicerecipient_model->get($id);
        $data['itemsupplier'] = $store;

        $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('address', $this->lang->line('address'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('gstin', $this->lang->line('gstin'), 'trim|xss_clean|required');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/servicerecipient/servicerecipientEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {


            $data = array(
                'id' => $id,
                // 'phone' => $this->input->post('phone'),
                // 'contact_person_phone' => $this->input->post('contact_person_phone'),
                'service_recipient' => $this->input->post('name'),
                // 'email' => $this->input->post('email'),
                'address' => $this->input->post('address'),
                'gstin_no' => $this->input->post('gstin'),
                'pan_no' => $this->input->post('pan_no'),
                // 'description' => $this->input->post('description'),
            );
            $this->servicerecipient_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('update_message') . '</div>');
            redirect('admin/servicerecipient/index');
        }
    }
}
