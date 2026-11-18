<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Enquiryform extends Public_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->config('form-builder');

        $this->load->config('app-config');

        $this->load->library(array('mailer', 'form_builder', 'mailsmsconf'));

        $this->load->model(array(
            'frontcms_setting_model',
            'complaint_Model',
            'Visitors_model',
            'onlinestudent_model',
            'Enquiry_with_scanner_model',
            'filetype_model',
            'customfield_model',
            'enquiry_model'
        ));

        $this->blood_group = $this->config->item('bloodgroup');

        $this->load->library('Ajax_pagination');

        $this->load->library('module_lib');

        $this->load->library('captchalib');

        $this->load->library('customlib');

        $this->load->helper('customfield');

        $this->banner_content         = $this->config->item('ci_front_banner_content');

        $this->perPage                = 12;

        $ban_notice_type              = $this->config->item('ci_front_notice_content');

        $this->sch_setting_detail     = $this->setting_model->getSetting();

        $this->data['banner_notices'] = $this->cms_program_model->getByCategory($ban_notice_type, array('start' => 0, 'limit' => 5));
    }

    public function check()
    {
        // $form_admission = $this->setting_model->getEnquiryFormStatus();
        // print_r($form_admission);


        // $sourcelist = $this->enquiry_model->getComplaintSource();
        // echo "<pre>";
        // print_r($sourcelist);

        // $sch_setting                  = $this->sch_setting_detail;
        // print_r($sch_setting);

        $reference = "1";

        $enquiry_data = $this->Enquiry_with_scanner_model->get($reference);

        echo "<pre>";
        print_r($enquiry_data);
    }

    public function index()
    {
        // if (!$this->rbac->hasPrivilege('student_categories', 'can_view')) {
        //     access_denied();
        // }
        // $this->session->set_userdata('top_menu', 'Student Information');
        // $this->session->set_userdata('sub_menu', 'enquiryform/index');


        $data['form_admission'] = $this->setting_model->getEnquiryFormStatus();

        $data['title']        = 'Enquiry Form';

        $genderList                   = $this->customlib->getGender();

        $data['genderList']     = $genderList;

        $class                        = $this->class_model->getAll();

        $data['classlist']      = $class;

        $category                     = $this->category_model->get();

        $data['categorylist']   = $category;


        $data["bloodgroup"]     = $this->blood_group;

        $houses                       = $this->student_model->gethouselist();

        $data['houses']         = $houses;


        $data['sourcelist'] = $this->enquiry_model->getComplaintSource();

        $reference_no                 = "";

        $refence_status               = "";

        $sch_setting                  = $this->sch_setting_detail;

        $data['online_admission_instruction'] = $sch_setting->online_admission_instruction;


        if ($this->customlib->getenquiryfieldstatus('student_email')) {

            $this->form_validation->set_rules(

                'email',
                $this->lang->line('email'),
                array(

                    'valid_email',
                    'required',

                    array('check_student_email_exists', array($this->Enquiry_with_scanner_model, 'check_student_email_exists')),

                )

            );
        }



        $this->form_validation->set_rules('firstname', $this->lang->line('first_name'), 'trim|required|xss_clean');

        $this->form_validation->set_rules('dob', $this->lang->line('date_of_birth'), 'trim|required|xss_clean');

        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');

        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');

        $this->form_validation->set_rules('gender', $this->lang->line('gender'), 'trim|required|xss_clean');

        if ($this->customlib->getenquiryfieldstatus('source')) {

            $this->form_validation->set_rules('source_id', $this->lang->line('source'), 'trim|required|xss_clean');
        }


        if ($this->customlib->getenquiryfieldstatus('if_guardian_is')) {

            $this->form_validation->set_rules('guardian_is', $this->lang->line('guardian'), 'trim|required|xss_clean');

            $this->form_validation->set_rules('guardian_name', $this->lang->line('guardian_name'), 'trim|required|xss_clean');

            $this->form_validation->set_rules('guardian_relation', $this->lang->line('guardian_relation'), 'trim|required|xss_clean');

            $this->form_validation->set_rules('guardian_phone', $this->lang->line('guardian_phone'), 'trim|required|xss_clean');
        }


        //=============================added=============================

        if (!empty($_FILES['father_pic']['name'])) {



            $this->form_validation->set_rules('father_pic', $this->lang->line('father') . " " . $this->lang->line('photo'), 'callback_document_handle_upload[father_pic]');
        }

        if (!empty($_FILES['mother_pic']['name'])) {

            $this->form_validation->set_rules('mother_pic', $this->lang->line('mother') . " " . $this->lang->line('photo'), 'callback_document_handle_upload[mother_pic]');
        }



        if (!empty($_FILES['file']['name'])) {

            $this->form_validation->set_rules('file', $this->lang->line('student') . " " . $this->lang->line('photo'), 'callback_document_handle_upload[file]');
        }

        if (!empty($_FILES['guardian_pic']['name'])) {



            $this->form_validation->set_rules('guardian_pic', $this->lang->line('guardian') . " " . $this->lang->line('photo'), 'callback_document_handle_upload[guardian_pic]');
        }

        $custom_fields = $this->customfield_model->getByBelong('students');


        foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {

            if ($custom_fields_value['validation'] && $this->customlib->getenquiryfieldstatus($custom_fields_value['name'])) {

                $custom_fields_id   = $custom_fields_value['id'];

                $custom_fields_name = $custom_fields_value['name'];

                $this->form_validation->set_rules("custom_fields[students][" . $custom_fields_id . "]", $custom_fields_name, 'trim|required');
            }
        }

        //===============================================================

        if ($this->form_validation->run() == false) {



            $this->load->view('enquiry/enquiryForm', $data);
        } else {
            $document_validate = true;


            //====================================added===================================================
            $custom_field_post  = $this->input->post("custom_fields[students]");

            $custom_value_array = array();

            if (!empty($custom_field_post)) {



                foreach ($custom_field_post as $key => $value) {

                    $check_field_type = $this->input->post("custom_fields[students][" . $key . "]");

                    $field_value      = is_array($check_field_type) ? implode(",", $check_field_type) : $check_field_type;

                    $array_custom     = array(

                        'belong_table_id' => 0,

                        'custom_field_id' => $key,

                        'field_value'     => $field_value,

                    );

                    $custom_value_array[] = $array_custom;
                }
            }

            //=========================================================================

            if ($document_validate) {



                $class_id   = $this->input->post('class_id');

                $section_id = $this->input->post('section_id');



                $data = array(

                    'firstname'        => $this->input->post('firstname'),

                    'class_section_id' => $this->input->post('section_id'),

                    'dob'              => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('dob'))),

                    'gender'           => $this->input->post('gender'),

                );

                // for inserting system fields





                if ($this->customlib->getenquiryfieldstatus('if_guardian_is')) {

                    $data['guardian_is'] = $this->input->post('guardian_is');



                    $data['guardian_name']     = $this->input->post('guardian_name');

                    $data['guardian_relation'] = $this->input->post('guardian_relation');

                    $data['guardian_phone']    = $this->input->post('guardian_phone');



                    if ($this->customlib->getenquiryfieldstatus('guardian_occupation')) {

                        $data['guardian_occupation'] = $this->input->post('guardian_occupation');
                    }

                    if ($this->customlib->getenquiryfieldstatus('guardian_email')) {

                        $data['guardian_email'] = $this->input->post('guardian_email');
                    }

                    if ($this->customlib->getenquiryfieldstatus('guardian_address')) {

                        $data['guardian_address'] = $this->input->post('guardian_address');
                    }
                }



                $middlename       = $this->input->post('middlename');

                $lastname         = $this->input->post('lastname');

                $mobileno         = $this->input->post('mobileno');

                $email            = $this->input->post('email');

                $category_id      = $this->input->post('category_id');

                $religion         = $this->input->post('religion');

                $cast             = $this->input->post('cast');

                $house            = $this->input->post('house');

                $blood_group      = $this->input->post('blood_group');

                $height           = $this->input->post('height');

                $weight           = $this->input->post('weight');

                $measurement_date = $this->input->post('measure_date');

                $source_id         = $this->input->post('source_id');

                $father_name       = $this->input->post('father_name');

                $father_phone      = $this->input->post('father_phone');

                $father_occupation = $this->input->post('father_occupation');



                $mother_name       = $this->input->post('mother_name');

                $mother_phone      = $this->input->post('mother_phone');

                $mother_occupation = $this->input->post('mother_occupation');

                $previous_school   = $this->input->post('previous_school');

                $note              = $this->input->post('note');



                $current_address   = $this->input->post('current_address');

                $permanent_address = $this->input->post('permanent_address');



                $bank_account_no = $this->input->post('bank_account_no');

                $bank_name       = $this->input->post('bank_name');

                $ifsc_code       = $this->input->post('ifsc_code');

                $adhar_no        = $this->input->post('adhar_no');

                $samagra_id      = $this->input->post('samagra_id');

                $previous_school = $this->input->post('previous_school');

                $note            = $this->input->post('note');

                $rte             = $this->input->post('rte');



                if (isset($middlename)) {

                    $data['middlename'] = $this->input->post('middlename');
                }

                if (isset($lastname)) {

                    $data['lastname'] = $this->input->post('lastname');
                }

                if (isset($mobileno)) {

                    $data['mobileno'] = $this->input->post('mobileno');
                }

                if (isset($email)) {

                    $data['email'] = $this->input->post('email');
                }

                if (isset($category_id)) {

                    $data['category_id'] = $this->input->post('category_id');
                }



                if (isset($religion)) {

                    $data['religion'] = $this->input->post('religion');
                }

                if (isset($cast)) {

                    $data['cast'] = $this->input->post('cast');
                }

                if (isset($house)) {

                    $data['school_house_id'] = $this->input->post('house');
                }

                if (isset($blood_group)) {

                    $data['blood_group'] = $this->input->post('blood_group');
                }

                if (isset($height)) {

                    $data['height'] = $this->input->post('height');
                }

                if (isset($weight)) {

                    $data['weight'] = $this->input->post('weight');
                }

                if (isset($weight)) {

                    $data['weight'] = $this->input->post('weight');
                }

                if (!empty($measurement_date)) {

                    $data['measurement_date'] = date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('measure_date')));
                }

                if (!empty($source_id)) {

                    $data['source_id'] = $this->input->post('source_id');
                }

                if (isset($father_name)) {

                    $data['father_name'] = $this->input->post('father_name');
                }

                if (isset($father_phone)) {

                    $data['father_phone'] = $this->input->post('father_phone');
                }

                if (isset($father_occupation)) {

                    $data['father_occupation'] = $this->input->post('father_occupation');
                }

                if (isset($mother_name)) {

                    $data['mother_name'] = $this->input->post('mother_name');
                }

                if (isset($mother_phone)) {

                    $data['mother_phone'] = $this->input->post('mother_phone');
                }

                if (isset($mother_occupation)) {

                    $data['mother_occupation'] = $this->input->post('mother_occupation');
                }

                if ($current_address) {

                    $data['current_address'] = $this->input->post('current_address');
                }

                if ($permanent_address) {

                    $data['permanent_address'] = $this->input->post('permanent_address');
                }

                if (isset($bank_account_no)) {

                    $data['bank_account_no'] = $this->input->post('bank_account_no');
                }

                if (isset($bank_name)) {

                    $data['bank_name'] = $this->input->post('bank_name');
                }

                if (isset($ifsc_code)) {

                    $data['ifsc_code'] = $this->input->post('ifsc_code');
                }

                if (isset($adhar_no)) {

                    $data['adhar_no'] = $this->input->post('adhar_no');
                }

                if (isset($samagra_id)) {

                    $data['samagra_id'] = $this->input->post('samagra_id');
                }

                if (isset($note)) {

                    $data['note'] = $this->input->post('note');
                }

                if (isset($previous_school)) {

                    $data['previous_school'] = $this->input->post('previous_school');
                }

                if (isset($rte)) {

                    $data['rte'] = $this->input->post('rte');
                }



                do {

                    $reference_no   = mt_rand(100000, 999999);

                    $refence_status = $this->Enquiry_with_scanner_model->checkreferenceno($reference_no);
                } while ($refence_status);



                $data['reference_no'] = $reference_no;

                $data['enquiry_date'] = date('Y-m-d');

                $data['status'] = 'active';

                $insert_id            = $this->Enquiry_with_scanner_model->add($data);




                if (!empty($custom_value_array)) {

                    $this->customfield_model->insertRecord($custom_value_array, $insert_id);
                }


                if (!empty($insert_id)) {

                    $enquiry_data = $this->Enquiry_with_scanner_model->get($insert_id);


                    $firstname    = $enquiry_data['firstname'];

                    $lastname     = $enquiry_data['lastname'];

                    $date         = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($enquiry_data['enquiry_date']));

                    $reference_no = $enquiry_data['reference_no'];

                    $mobileno     = $enquiry_data['guardian_phone'];

                    $email        = $enquiry_data['email'];


                    $sender_details = array('firstname' => $firstname, 'lastname' => $lastname, 'email' => $email, 'date' => $date, 'reference_no' => $reference_no, 'mobileno' => $mobileno);


                    $this->mailsmsconf->mailsms('enquiry_form_submisssion', $sender_details);
                }




                // if (isset($_FILES["document"]) && !empty($_FILES['document']['name'])) {



                //     $time     = md5($_FILES["document"]['name'] . microtime());

                //     $fileInfo = pathinfo($_FILES["document"]["name"]);

                //     $doc_name = $time . '.' . $fileInfo['extension'];

                //     move_uploaded_file($_FILES["document"]["tmp_name"], "./uploads/student_documents/online_admission_doc/" . $doc_name);

                //     $data['document'] = $doc_name;

                //     $data_img         = array('id' => $insert_id, 'document' => $doc_name);

                //     $this->onlinestudent_model->edit($data_img);
                // }



                if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {



                    $fileInfo = pathinfo($_FILES["file"]["name"]);

                    $img_name = $insert_id . '.' . $fileInfo['extension'];

                    move_uploaded_file($_FILES["file"]["tmp_name"], "./uploads/front_office/enquiry/" . $img_name);

                    $data_img = array('id' => $insert_id, 'image' => 'uploads/front_office/enquiry/' . $img_name);

                    $this->Enquiry_with_scanner_model->edit($data_img);
                }



                if (isset($_FILES["father_pic"]) && !empty($_FILES['father_pic']['name'])) {

                    $fileInfo = pathinfo($_FILES["father_pic"]["name"]);

                    $img_name = $insert_id . "father" . '.' . $fileInfo['extension'];

                    move_uploaded_file($_FILES["father_pic"]["tmp_name"], "./uploads/front_office/enquiry/" . $img_name);

                    $data_img = array('id' => $insert_id, 'father_pic' => 'uploads/front_office/enquiry/' . $img_name);

                    $this->Enquiry_with_scanner_model->edit($data_img);
                }

                if (isset($_FILES["mother_pic"]) && !empty($_FILES['mother_pic']['name'])) {

                    $fileInfo = pathinfo($_FILES["mother_pic"]["name"]);

                    $img_name = $insert_id . "mother" . '.' . $fileInfo['extension'];

                    move_uploaded_file($_FILES["mother_pic"]["tmp_name"], "./uploads/front_office/enquiry/" . $img_name);

                    $data_img = array('id' => $insert_id, 'mother_pic' => 'uploads/front_office/enquiry/' . $img_name);

                    $this->Enquiry_with_scanner_model->edit($data_img);
                }

                if (isset($_FILES["guardian_pic"]) && !empty($_FILES['guardian_pic']['name'])) {

                    $fileInfo = pathinfo($_FILES["guardian_pic"]["name"]);

                    $img_name = $insert_id . "guardian" . '.' . $fileInfo['extension'];

                    move_uploaded_file($_FILES["guardian_pic"]["tmp_name"], "./uploads/front_office/enquiry/" . $img_name);

                    $data_img = array('id' => $insert_id, 'guardian_pic' => 'uploads/front_office/enquiry/' . $img_name);

                    $this->Enquiry_with_scanner_model->edit($data_img);
                }



                $this->data['class_id']            = $class_id;

                $this->data['section_id']          = $section_id;

                $this->data['roll_no']             = $this->input->post('roll_no');

                $this->data['mobileno']            = $this->input->post('mobileno');

                $this->data['email']               = $this->input->post('email');

                $this->data['firstname']           = $this->input->post('firstname');

                $this->data['lastname']            = $this->input->post('lastname');

                $this->data['mobileno']            = $this->input->post('mobileno');

                $this->data['class_section_id']    = $this->input->post('section_id');

                $this->data['source_id']              = $this->input->post('source_id');

                $this->data['guardian_is']         = $this->input->post('guardian_is');

                $this->data['dob']                 = date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('dob')));

                $this->data['ifsc_code']           = $this->input->post('ifsc_code');

                $this->data['bank_account_no']     = $this->input->post('bank_account_no');

                $this->data['bank_name']           = $this->input->post('bank_name');

                $this->data['current_address']     = $this->input->post('current_address');

                $this->data['permanent_address']   = $this->input->post('permanent_address');

                $this->data['father_name']         = $this->input->post('father_name');

                $this->data['father_phone']        = $this->input->post('father_phone');

                $this->data['father_occupation']   = $this->input->post('father_occupation');

                $this->data['mother_name']         = $this->input->post('mother_name');

                $this->data['mother_phone']        = $this->input->post('mother_phone');

                $this->data['mother_occupation']   = $this->input->post('mother_occupation');

                $this->data['guardian_occupation'] = $this->input->post('guardian_occupation');

                $this->data['guardian_email']      = $this->input->post('guardian_email');

                $this->data['gender']              = $this->input->post('gender');

                $this->data['guardian_name']       = $this->input->post('guardian_name');

                $this->data['guardian_relation']   = $this->input->post('guardian_relation');

                $this->data['guardian_phone']      = $this->input->post('guardian_phone');

                $this->data['guardian_address']    = $this->input->post('guardian_address');

                $this->data['admission_id']        = $insert_id;


                $this->session->set_userdata('validlogin', $reference_no);

                // $this->session->set_flashdata('msg', '<div class="alert alert-success">' . ' ' . $this->lang->line('thanks_for_registration_please_note_your_reference_number') . ' ' . $reference_no . ' ' . $this->lang->line('for_further_communication') . '</div>');
                $this->session->set_userdata('msg', '<div class="alert alert-success ">' . ' ' . $this->lang->line('thanks_for_enquiry_please_note_your_enquiry_number') . ' ' . $reference_no . ' ' . $this->lang->line('for_further_communication') . '</div>');



                $this->data['online_admission_payment'] = $this->sch_setting_detail->online_admission_payment;


                if ($sch_setting->enquiry_form_payment == 'yes') {
                    // redirect('Enquiryform_payment/checkout/index/' . $insert_id);

                    $form_url = base_url('Enquiryform_payment/checkout');

                    $data = array('id' => $insert_id,);


                    echo '<form id="autoSubmitForm" action="' . $form_url . '" method="POST">';
                    foreach ($data as $key => $value) {
                        echo '<input type="hidden" name="' . $key . '" value="' . htmlspecialchars($value) . '">';
                    }
                    echo '</form>';


                    echo '<script type="text/javascript">
                    document.getElementById("autoSubmitForm").submit();
                    </script>';
                } else {

                    redirect('enquiryform/thank_you/' . $reference_no);
                }
            }
        }

        // $category_result      = $this->category_model->get();
        // $data['categorylist'] = $category_result;
        // $this->load->view('layout/header', $data);

        // $this->load->view('layout/footer', $data);
    }


    // public function checkout($id, $reference_no)
    // {

    //     $post_data = $this->security->xss_clean($this->input->post());
    //     $reference = $id;
    //     $this->session->set_userdata("reference", $reference);
    //     $data = array();
    //     if (!empty($this->pay_method)) {
    //         if ($this->pay_method->payment_type == "payu") {
    //             redirect(base_url("Enquiryform_payment/payu"));
    //         } elseif ($this->pay_method->payment_type == "stripe") {
    //             redirect(base_url("Enquiryform_payment/stripe"));
    //         } elseif ($this->pay_method->payment_type == "ccavenue") {
    //             redirect(base_url("Enquiryform_payment/ccavenue"));
    //         } elseif ($this->pay_method->payment_type == "paypal") {
    //             redirect(base_url("Enquiryform_payment/paypal"));
    //         } elseif ($this->pay_method->payment_type == "instamojo") {
    //             redirect(base_url("Enquiryform_payment/instamojo"));
    //         } elseif ($this->pay_method->payment_type == "paytm") {
    //             redirect(base_url("Enquiryform_payment/paytm"));
    //         } elseif ($this->pay_method->payment_type == "razorpay") {
    //             redirect(base_url("Enquiryform_payment/razorpay"));
    //         } elseif ($this->pay_method->payment_type == "paystack") {
    //             redirect(base_url("Enquiryform_payment/paystack"));
    //         } elseif ($this->pay_method->payment_type == "midtrans") {
    //             redirect(base_url("Enquiryform_payment/midtrans"));
    //         } elseif ($this->pay_method->payment_type == "ipayafrica") {
    //             redirect(base_url("Enquiryform_payment/ipayafrica"));
    //         } elseif ($this->pay_method->payment_type == "jazzcash") {
    //             redirect(base_url("Enquiryform_payment/jazzcash"));
    //         } elseif ($this->pay_method->payment_type == "pesapal") {
    //             redirect(base_url("Enquiryform_payment/pesapal"));
    //         } elseif ($this->pay_method->payment_type == "flutterwave") {
    //             redirect(base_url("Enquiryform_payment/flutterwave"));
    //         } elseif ($this->pay_method->payment_type == "billplz") {
    //             redirect(base_url("Enquiryform_payment/billplz"));
    //         } elseif ($this->pay_method->payment_type == "sslcommerz") {
    //             redirect(base_url("Enquiryform_payment/sslcommerz"));
    //         }
    //     }
    // }


    // public function successinvoice($reference_no)
    // {
    //     $data['setting'] = $this->setting;
    //     $data["reference_no"] = $reference_no;
    //     $this->load->view("enquiry/success_invoice", $data);
    // }

    // public function paymentfailed($reference_no)
    // {
    //     $data['setting'] = $this->setting;
    //     $data["reference_no"] = $reference_no;
    //     $this->load->view("enquiry/payment_failed", $data);
    // }




    public function document_handle_upload($str, $var)

    {



        $image_validate = $this->config->item('image_validate');

        $result         = $this->filetype_model->get();



        if (isset($_FILES[$var]) && !empty($_FILES[$var]['name'])) {



            $file_type = $_FILES[$var]['type'];

            $file_size = $_FILES[$var]["size"];

            $file_name = $_FILES[$var]["name"];



            $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->file_extension)));

            $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->file_mime)));

            $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));



            $finfo = finfo_open(FILEINFO_MIME_TYPE);

            $mtype = finfo_file($finfo, $_FILES[$var]['tmp_name']);



            if (!in_array($mtype, $allowed_mime_type)) {

                $this->form_validation->set_message('document_handle_upload', 'File Type Not Allowed');

                return false;
            }



            if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {

                $this->form_validation->set_message('document_handle_upload', 'Extension Not Allowed');

                return false;
            }



            if ($file_size > $result->file_size) {

                $this->form_validation->set_message('document_handle_upload', $this->lang->line('file_size_shoud_be_less_than') . number_format($image_validate['upload_size'] / 1048576, 2) . " MB");

                return false;
            }



            return true;
        }

        return true;
    }

    public function thank_you($id)
    {
        // if (!$this->rbac->hasPrivilege('student_categories', 'can_view')) {
        //     access_denied();
        // }
        $data['title']    = 'Thank You';
        // $category         = $this->category_model->get($id);
        $data['reference_no'] = $id;
        // $this->load->view('layout/header', $data);
        $this->load->view('enquiry/thank_you', $data);
        // $this->load->view('layout/footer', $data);
    }

    // public function delete($id)
    // {
    //     if (!$this->rbac->hasPrivilege('student_categories', 'can_delete')) {
    //         access_denied();
    //     }
    //     $data['title'] = 'Category List';
    //     $this->category_model->remove($id);
    //     $this->session->set_flashdata('msgdelete', '<div class="alert alert-success text-left">' . $this->lang->line('delete_message') . '</div>');
    //     redirect('category/index');
    // }

    // public function create()
    // {
    //     if (!$this->rbac->hasPrivilege('student_categories', 'can_add')) {
    //         access_denied();
    //     }
    //     $data['title']        = 'Add Category';
    //     $category_result      = $this->category_model->get();
    //     $data['categorylist'] = $category_result;
    //     $this->form_validation->set_rules('category', $this->lang->line('category'), 'trim|required|xss_clean');
    //     if ($this->form_validation->run() == false) {
    //         $this->load->view('layout/header', $data);
    //         $this->load->view('category/categoryList', $data);
    //         $this->load->view('layout/footer', $data);
    //     } else {
    //         $data = array(
    //             'category' => $this->input->post('category'),
    //         );
    //         $this->category_model->add($data);
    //         $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
    //         redirect('category/index');
    //     }
    // }

    // public function edit($id)
    // {
    //     if (!$this->rbac->hasPrivilege('student_categories', 'can_edit')) {
    //         access_denied();
    //     }
    //     $data['title']        = 'Edit Category';
    //     $category_result      = $this->category_model->get();
    //     $data['categorylist'] = $category_result;
    //     $data['id']           = $id;
    //     $category             = $this->category_model->get($id);
    //     $data['category']     = $category;
    //     $this->form_validation->set_rules('category', $this->lang->line('category'), 'trim|required|xss_clean');
    //     if ($this->form_validation->run() == false) {
    //         $this->load->view('layout/header', $data);
    //         $this->load->view('category/categoryEdit', $data);
    //         $this->load->view('layout/footer', $data);
    //     } else {
    //         $data = array(
    //             'id'       => $id,
    //             'category' => $this->input->post('category'),
    //         );
    //         $this->category_model->add($data);
    //         $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('update_message') . '</div>');
    //         redirect('category/index');
    //     }
    // }
}
