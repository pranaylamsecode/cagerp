<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Pages extends Public_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function terms_of_use()
    {
   
        $this->load->view('term/term');
      
    }

    public function privacy_policy()
    {
        
        $this->load->view('privacypolicy/privacypolicy');
        
    }

    public function delete_account()
    {
        
        $this->load->view('deleteaccount/delete_account');
        
    }
    
}
