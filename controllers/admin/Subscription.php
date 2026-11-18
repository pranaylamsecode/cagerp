<?php



if (!defined('BASEPATH')) {

    exit('No direct script access allowed');
}

class Subscription extends MY_Controller
{
    public function expired()
    {
        $data['end_date'] = $this->session->userdata('admin')['subscription_end_date'];

        //$this->load->view('layout/header', $data);
        $this->load->view('admin/subscription_expired', $data);
        //$this->load->view('layout/footer', $data);
    }
}
