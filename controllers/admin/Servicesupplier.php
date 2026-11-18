<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Servicesupplier extends Admin_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model("servicesupplier_model");
    }

    function index()
    {
        if (!$this->rbac->hasPrivilege('service_supplier', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Rent System');
        $this->session->set_userdata('sub_menu', 'servicesupplier/index');
        $data['title'] = 'Service Supplier List';
        $itemsupplier_result = $this->servicesupplier_model->get();
        $data['itemsupplierlist'] = $itemsupplier_result;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/servicesupplier/servicesupplierList', $data);
        $this->load->view('layout/footer', $data);
    }

    public function check()
    {
        $itemsupplier_result = $this->servicesupplier_model->get();
        print_r($itemsupplier_result);
    }

    function delete($id)
    {
        if (!$this->rbac->hasPrivilege('service_supplier', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'Service Supplier List';
        $this->servicesupplier_model->remove($id);
        redirect('admin/servicesupplier/index');
    }

    function create()
    {
        if (!$this->rbac->hasPrivilege('service_supplier', 'can_add')) {
            access_denied();
        }
        $data['title'] = 'Add Service supplier';
        $itemsupplier_result = $this->servicesupplier_model->get();
        $data['itemsupplierlist'] = $itemsupplier_result;

        $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('address', $this->lang->line('address'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('gstin', $this->lang->line('gstin'), 'trim|xss_clean|required');


        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/servicesupplier/servicesupplierList', $data);
            $this->load->view('layout/footer', $data);
        } else {

            $data = array(
                // 'phone' => $this->input->post('phone'),
                // 'contact_person_phone' => $this->input->post('contact_person_phone'),
                'service_supplier' => $this->input->post('name'),
                // 'email' => $this->input->post('email'),
                'address' => $this->input->post('address'),
                'gstin_no' => $this->input->post('gstin'),
                'pan_no' => $this->input->post('pan_no'),
                // 'description' => $this->input->post('description'),
            );
            $this->servicesupplier_model->add($data);
            $this->session->set_userdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/servicesupplier/index');
        }
    }

    function edit($id)
    {
        if (!$this->rbac->hasPrivilege('service_supplier', 'can_edit')) {
            access_denied();
        }
        $data['title'] = 'Edit Service Supplier';
        $itemsupplier_result = $this->servicesupplier_model->get();
        $data['itemsupplierlist'] = $itemsupplier_result;
        $data['id'] = $id;
        $store = $this->servicesupplier_model->get($id);
        $data['itemsupplier'] = $store;

        $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('address', $this->lang->line('address'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('gstin', $this->lang->line('gstin'), 'trim|xss_clean|required');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/servicesupplier/servicesupplierEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {


            $data = array(
                'id' => $id,
                // 'phone' => $this->input->post('phone'),
                // 'contact_person_phone' => $this->input->post('contact_person_phone'),
                'service_supplier' => $this->input->post('name'),
                // 'email' => $this->input->post('email'),
                'address' => $this->input->post('address'),
                'gstin_no' => $this->input->post('gstin'),
                'pan_no' => $this->input->post('pan_no'),
                // 'description' => $this->input->post('description'),
            );
            $this->servicesupplier_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('update_message') . '</div>');
            redirect('admin/servicesupplier/index');
        }
    }
}
