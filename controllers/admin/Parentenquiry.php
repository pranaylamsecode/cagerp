<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Parentenquiry extends Admin_Controller
{

    public $sch_setting_detail = array();

    public function __construct()
    {
        parent::__construct();
        $this->load->library('smsgateway');
        $this->load->library('mailsmsconf');
        $this->load->library('encoding_lib');
        $this->load->library('customlib');
        $this->load->model("classteacher_model");
        $this->load->model("Enquiry_with_scanner_model");
        $this->load->model("enquiry_model");
        $this->load->model("timeline_model");

        $this->blood_group        = $this->config->item('bloodgroup');
        $this->sch_setting_detail = $this->setting_model->getSetting();
        $this->role;
        $this->config->load("payroll");
        $this->enquiry_status = $this->config->item('enquiry_status');
    }


    public function index()
    {
        if (!$this->rbac->hasPrivilege('parent_enquiry', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'front_office');
        $this->session->set_userdata('sub_menu', 'parentenquiry');
        $data['title']       = 'Student List';
        $data['sch_setting'] = $this->sch_setting_detail;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/parentenquiry/enquirylist', $data);
        $this->load->view('layout/footer', $data);
    }

    public function getByClass()
    {
        $class_id = $this->input->post('class_id');
        $data     = $this->section_model->getClassBySection($class_id);
        $this->jsonlib->output(200, $data);
    }

    public function confirmationbox()
    {

        $id          = $_REQUEST['id'];

        $sch_setting = $this->sch_setting_detail;

        $result = $this->Enquiry_with_scanner_model->get($id);

        if ($sch_setting->enquiry_form_payment == 'yes') {

            if ($result['paid_status'] == 1) {

                $message = 'Enquiry Form Status' . "         : " . 'Submitted' . " \n" . 'Payment Status' . "         : " . 'Paid' . " \n" .  $this->lang->line('reference_no') . "    : " . $result['reference_no'] . " \n \n" . 'Do you want to enroll this student to CAGS' . " ";
            } else {

                $message = 'Enquiry Form Status' . "         : " . 'Submitted' . " \n" . 'Payment Status' . "         : " . 'Unpaid' . " \n" .  $this->lang->line('reference_no') . "    : " . $result['reference_no'] . " \n \n" . 'Do you want to enroll this student to CAGS' . " ";
            }
        } else {

            $message = 'Enquiry Form Status' . "         : " . 'Submitted' . " \n" . $this->lang->line('reference_no') . "    : " . $result['reference_no'] . " \n \n" . 'Do you want to enroll this student to CAGS' . " ";
        }

        // $message = "";

        echo $message;
    }

    public function check()
    {
        //     $class             = $this->class_model->get();
        //     $data['classlist'] = $class;
        //     $sch_setting       = $this->sch_setting_detail;
        //     if (!empty($data['classlist'])) {
        //         foreach ($data['classlist'] as $key => $value) {
        //             $carray[] = $value['id'];
        //         }
        //     }

        //     $student_result  = $this->Enquiry_with_scanner_model->getstudentlist($carray, null);
        //     $m               = json_decode($student_result);
        //     echo "<pre>";
        //     print_r($m);


        // $stff_list = $this->staff_model->get();
        // echo "<pre>";
        // print_r($stff_list);





        // $refno = "931149";
        // $dob = $this->customlib->dateFormatToYYYYMMDD('11-06-2024');

        // $is_enroll = $this->customlib->checkisenroll($refno);




        // $status = $this->onlinestudent_model->checkadmissionstatus($refno, $dob);


        // $admin_session = $this->session->userdata('admin');
        // print_r($admin_session);



        //=============================================================

        $id = 10;
        $details = $this->Enquiry_with_scanner_model->get($id);


        $sch_setting       = $this->sch_setting_detail;


        // $category                   = $this->category_model->get();
        // $categorylist       = $category;

        // $houses                       = $this->student_model->gethouselist();

        // $houses         = $houses;

        // $bloodgroup    = $this->blood_group;

        echo "<pre";
        print_r($sch_setting);
        echo "<pre>";
        print_r($details);


        // echo "<pre>";
        // print_r($categorylist);



        // echo "<pre>";
        // print_r($houses);


        // echo "<pre>";
        // print_r($bloodgroup);



        //==================================================================


        // $data['enquiry_status'] = $this->enquiry_status;
        // echo "<pre>";
        // print_r($data);
    }


    public function getstudentlist()
    {

        $class             = $this->class_model->get();
        $data['classlist'] = $class;
        $sch_setting       = $this->sch_setting_detail;
        if (!empty($data['classlist'])) {
            foreach ($data['classlist'] as $key => $value) {
                $carray[] = $value['id'];
            }
        }

        $student_result  = $this->Enquiry_with_scanner_model->getstudentlist($carray, null);
        $m               = json_decode($student_result);
        $currency_symbol = $this->customlib->getSchoolCurrencyFormat();
        $dt_data         = array();
        if (!empty($m->data)) {
            foreach ($m->data as $key => $value) {
                $followupbtn   = '';
                $editbtn   = '';
                $deletebtn = '';
                $document  = '';
                $last_name = "";
                $mobileno  = "";
                $printbtn  = "";
                $status    = 'admin';


                $next_date = $this->Enquiry_with_scanner_model->next_follow_up_date($value->id);

                if ($this->rbac->hasPrivilege('parent_enquiry', 'can_view')) {
                    if (!$value->is_enroll) {
                        $followupbtn = "<a data-placement='left'  class='btn btn-default btn-xs mt-5 pull-right' data-toggle='tooltip' title='" . $this->lang->line('follow_up_parent_enquiry') . "' onclick='return follow_up(" . '"' . $value->id . '"' . "  )'><i class='fa fa-phone'></i></a>";
                    }
                }



                if ($this->rbac->hasPrivilege('parent_enquiry', 'can_edit')) {
                    if (!$value->is_enroll) {
                        $editbtn = "<a data-placement='left'  class='btn btn-default btn-xs mt-5 pull-right' data-toggle='tooltip' title='" . $this->lang->line('edit') . "' onclick='return getRecord(" . '"' . $value->id . '"' .  "  )'><i class='fa fa-edit'></i></a>";
                    }
                }

                if ($this->rbac->hasPrivilege('parent_enquiry', 'can_delete')) {
                    $deletebtn = '';

                    $deletebtn = "<a data-placement='left' href='" . base_url() . 'admin/parentenquiry/delete/' . $value->id . "' class='btn btn-default btn-xs mt-5 pull-right' data-toggle='tooltip' title='" . $this->lang->line('delete') . "' onclick='return confirm(" . '"' . $this->lang->line('delete_confirm') . '"' . "  )'><i class='fa fa-remove'></i></a>";
                }

                // if (!empty($value->reference_no)) {
                //     $printbtn = "<a data-placement='left' target='_blank' href='" . base_url() . 'welcome/online_admission_review/' . $value->reference_no . "'  class='btn btn-default btn-xs mt-5 pull-right' data-toggle='tooltip' title='" . $this->lang->line('print') . "' ><i class='fa fa-print'></i></a>";
                // } else {
                //     $printbtn = "";
                // }

                if (($value->is_enroll)) {
                    $enroll = "<i class='fa fa-check'></i><span style='display:none'>" . $this->lang->line('yes') . "</span>";
                } else {
                    $enroll = "<i class='fa fa-minus-circle'></i><span style='display:none'>" . $this->lang->line('no') . "</span>";
                }


                if ($value->dob != null) {
                    $dob = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value->dob));
                } else {
                    $dob = "";
                }


                if (!empty($next_date)) {
                    $follow_up_date = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($next_date[0]['date']));
                } else {
                    // if ($value->follow_up_date != null) {
                    //     $follow_up_date = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value->follow_up_date));
                    // } else {
                    $follow_up_date = "";
                    // }
                }


                if (!empty($next_date)) {
                    $next_follow_up_date = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($next_date[0]['next_date']));
                } else {
                    if ($value->follow_up_date != null) {
                        $next_follow_up_date = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value->follow_up_date));
                    } else {
                        $next_follow_up_date = "";
                    }
                }


                // if ($value->document) {
                //     $document = "<a data-placement='left' href='" . base_url() . "admin/onlinestudent/onlineadmission_download/" . $value->document . "' class='btn btn-default btn-xs'  data-toggle='tooltip' title='" . $this->lang->line('download') . "'>
                //          <i class='fa fa-download'></i> </a>";
                // }

                if ($this->customlib->getenquiryfieldstatus('lastname')) {
                    $last_name = $value->lastname;
                }


                $row   = array();
                $row[] = $value->reference_no;

                if ($this->customlib->getenquiryfieldstatus('guardian_phone')) {
                    $row[] = $value->guardian_phone;
                }


                $row[] = "<a onclick='return getRecord(" . '"' . $value->id . '"' . "  )'> $value->firstname " . " " . " $last_name </a>";


                $row[] = $value->class . "(" . $value->section . ")";

                if ($this->customlib->getenquiryfieldstatus('father_name')) {
                    $row[] = $value->father_name;
                }

                // $row[] = $dob;
                $row[] = $value->enquiry_date;
                $row[] = $follow_up_date;
                $row[] = $next_follow_up_date;

                $row[] = $value->status;
                // $row[] = $value->gender;
                // $row[] = $value->category;


                // if ($value->form_status == 1) {
                //     $row[] = '<span class="label label-success">' . $this->lang->line('submitted') . '</span>';
                // } else if ($value->form_status == 0) {
                //     $row[] = '<span class="label label-danger">' . $this->lang->line('not_submitted') . '</span>';
                // }

                if ($sch_setting->enquiry_form_payment == 'yes') {
                    if ($value->paid_status == 1) {
                        $row[] = '<span class="label label-success">' . $this->lang->line('paid') . '</span>';
                    } else {
                        $row[] = '<span class="label label-danger">' . $this->lang->line('unpaid') . '</span>';
                    }
                }

                $row[]     = $enroll;
                // $row[]     = $document . ' ' . $printbtn . ' ' . $editbtn . ' ' . $deletebtn;
                $row[]     = $followupbtn . ' ' . $editbtn . ' ' . $deletebtn;
                $dt_data[] = $row;
            }
        }

        $json_data = array(
            "draw"            => intval($m->draw),
            "recordsTotal"    => intval($m->recordsTotal),
            "recordsFiltered" => intval($m->recordsFiltered),
            "data"            => $dt_data,
        );
        echo json_encode($json_data);
    }


    public function getdetails($id)
    {
        if (!$this->rbac->hasPrivilege('parent_enquiry', 'can_edit')) {

            access_denied();
        }

        $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;

        $data['title']           = 'Edit Enquiry';

        $data['class_list'] = $this->enquiry_model->getclasses();

        $genderList      = $this->customlib->getGender();

        $data['genderList']         = $genderList;

        $vehroute_result            = $this->vehroute_model->get();

        $data['vehroutelist']       = $vehroute_result;

        $data["bloodgroup"]     = $this->blood_group;


        $hostelList                 = $this->hostel_model->get();

        $data['hostelList']         = $hostelList;

        $houses                       = $this->student_model->gethouselist();

        $data['houses']         = $houses;

        $category                   = $this->category_model->get();

        $data['categorylist']       = $category;

        $data['sch_setting']        = $this->sch_setting_detail;


        $data['source'] = $this->enquiry_model->getComplaintSource();

        $data['enquiry_data'] = $this->Enquiry_with_scanner_model->get($id);


        $data['stff_list'] = $this->staff_model->get();

        $this->load->view('admin/parentenquiry/parentenquiryeditmodalview', $data);
    }



    public function editenquiry($id)
    {
        if (!$this->rbac->hasPrivilege('parent_enquiry', 'can_edit')) {
            access_denied();
        }

        $data['id']      = $id;


        // $data['class_list'] = $this->enquiry_model->getclasses();

        // $genderList      = $this->customlib->getGender();
        // $data['genderList']         = $genderList;

        // $category                   = $this->category_model->get();
        // $data['categorylist']       = $category;
        // $data['enquiry_data'] = $this->Enquiry_with_scanner_model->get($id);


        $this->form_validation->set_rules(
            'email',
            $this->lang->line('email'),
            array(
                'valid_email',
                // array('check_student_email_exists', array($this->Enquiry_with_scanner_model, 'check_student_email_exists')),

            )
        );


        $this->form_validation->set_rules('firstname', $this->lang->line('first_name'), 'trim|required|xss_clean');

        $this->form_validation->set_rules('dob', $this->lang->line('date_of_birth'), 'trim|required|xss_clean');

        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');

        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');

        $this->form_validation->set_rules('gender', $this->lang->line('gender'), 'trim|required|xss_clean');

        $this->form_validation->set_rules('follow_up_date', $this->lang->line('follow_up_date'), 'trim|required|xss_clean');

        $this->form_validation->set_rules('assigned', $this->lang->line('assigned'), 'trim|required|xss_clean');

        //=============================added=============================

        if ($this->customlib->getenquiryfieldstatus('source')) {

            $this->form_validation->set_rules('source_id', $this->lang->line('source'), 'trim|required|xss_clean');
        }


        if ($this->customlib->getenquiryfieldstatus('if_guardian_is')) {

            $this->form_validation->set_rules('guardian_is', $this->lang->line('guardian'), 'trim|required|xss_clean');

            $this->form_validation->set_rules('guardian_name', $this->lang->line('guardian_name'), 'trim|required|xss_clean');

            $this->form_validation->set_rules('guardian_relation', $this->lang->line('guardian_relation'), 'trim|required|xss_clean');

            $this->form_validation->set_rules('guardian_phone', $this->lang->line('guardian_phone'), 'trim|required|xss_clean');
        }



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
        //============================================================================

        if ($this->form_validation->run() == false) {
            $msg = array(
                'firstname' => form_error('firstname'),
                'class_id' => form_error('class_id'),
                'dob' => form_error('dob'),
                'gender' => form_error('gender'),
                'source_id' => form_error('source_id'),
                'follow_up_date' => form_error('follow_up_date'),
                'assigned' => form_error('assigned'),
                'guardian_name' => form_error('guardian_name'),
                'guardian_phone' => form_error('guardian_phone'),
                'guardian_is' => form_error('guardian_is'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            // $student_id     = $this->input->post('student_id');
            $class_id       = $this->input->post('class_id');
            $section_id     = $this->input->post('section_id');
            $hostel_room_id = $this->input->post('hostel_room_id');
            $fees_discount  = $this->input->post('fees_discount');
            $vehroute_id    = $this->input->post('vehroute_id');
            if (empty($vehroute_id)) {
                $vehroute_id = 0;
            }
            if (empty($hostel_room_id)) {
                $hostel_room_id = 0;
            }


            //=================added===================================================
            $custom_field_post = $this->input->post("custom_fields[students]");

            if (isset($custom_field_post)) {

                $custom_value_array = array();

                foreach ($custom_field_post as $key => $value) {

                    $check_field_type = $this->input->post("custom_fields[students][" . $key . "]");

                    $field_value      = is_array($check_field_type) ? implode(",", $check_field_type) : $check_field_type;

                    $array_custom     = array(

                        'belong_table_id' => $id,

                        'custom_field_id' => $key,

                        'field_value'     => $field_value,

                    );

                    $custom_value_array[] = $array_custom;
                }

                $this->customfield_model->updateRecord($custom_value_array, $id, 'students');
            }
            //==================================================================
            $class_id   = $this->input->post('class_id');

            $section_id = $this->input->post('section_id');


            $data = array(

                'firstname'        => $this->input->post('firstname'),

                'class_section_id' => $this->input->post('section_id'),

                'dob'              => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('dob'))),

                'gender'           => $this->input->post('gender'),

            );



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

            $assigned         = $this->input->post('assigned');

            $source_id         = $this->input->post('source_id');

            $father_name       = $this->input->post('father_name');

            $father_phone      = $this->input->post('father_phone');

            $father_occupation = $this->input->post('father_occupation');



            $mother_name       = $this->input->post('mother_name');

            $mother_phone      = $this->input->post('mother_phone');

            $mother_occupation = $this->input->post('mother_occupation');

            $previous_school   = $this->input->post('previous_school');

            $note              = $this->input->post('note');

            $description              = $this->input->post('description');

            $follow_up_date              = $this->input->post('follow_up_date');

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


            if (!empty($assigned)) {

                $data['assigned'] = $this->input->post('assigned');
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

            if (isset($description)) {

                $data['description'] = $this->input->post('description');
            }

            if (isset($follow_up_date)) {

                $data['follow_up_date'] = date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('follow_up_date')));
            }

            if (isset($previous_school)) {

                $data['previous_school'] = $this->input->post('previous_school');
            }

            if (isset($rte)) {

                $data['rte'] = $this->input->post('rte');
            }

            $response = $this->Enquiry_with_scanner_model->update($id, $data);

            if ($response) {

                if ($this->input->post('assigned') !== $this->input->post('previous_assigned_staff')) {

                    $enquiry_data = $this->Enquiry_with_scanner_model->get($id);

                    $staff_data = $this->staff_model->get($this->input->post('assigned'));


                    $firstname    = $enquiry_data['firstname'];

                    $lastname     = $enquiry_data['lastname'];

                    $date         = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($enquiry_data['enquiry_date']));

                    $reference_no = $enquiry_data['reference_no'];

                    $mobileno     = $enquiry_data['guardian_phone'];

                    $email        = $staff_data['email'];


                    $sender_details = array('firstname' => $firstname, 'lastname' => $lastname, 'email' => $email, 'date' => $date, 'reference_no' => $reference_no, 'mobileno' => $mobileno);


                    $this->mailsmsconf->mailsms('enquiry_form_submisssion', $sender_details);
                }
            }


            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('update_message'));

            // if ($response) {
            //     $response = json_decode($response);

            //     to upload document from online student to main firl
            //     if (isset($student['document']) && !empty($student['document'])) {
            //         $uploaddir = './uploads/student_documents/' . $response->student_id . '/';
            //         if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
            //             die("Error creating folder $uploaddir");
            //         }

            //         $file_name           = basename($student['document']);
            //         $img_name            = $uploaddir . $file_name;
            //         $filePath            = "./uploads/student_documents/online_admission_doc/" . $student['document'];
            //         $destinationFilePath = $img_name;
            //         copy($filePath, $destinationFilePath);

            //         $data_img = array('student_id' => $response->student_id, 'doc' => $file_name);
            //         $this->student_model->adddoc($data_img);
            //     }

            //     to upload father mother student and guardian image

            //     if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {

            //         $fileInfo = pathinfo($_FILES["file"]["name"]);
            //         $img_name = $response->student_id . '.' . $fileInfo['extension'];
            //         move_uploaded_file($_FILES["file"]["tmp_name"], "./uploads/student_images/" . $img_name);
            //         $data_img = array('id' => $response->student_id, 'image' => 'uploads/student_images/' . $img_name);
            //         $this->student_model->add($data_img);
            //     } else {

            //         if ($student['image'] != "") {
            //             $filePath     = $student['image'];
            //             $path =  "./uploads/student_documents/online_admission_doc/" . $student['image'];
            //             $fileInfo = pathinfo($path);
            //             $img_name = $response->student_id . '.' . $fileInfo['extension'];

            //             $uploaddir = './uploads/student_images/' . $img_name;
            //             $destinationFilePath = $uploaddir;

            //             copy($filePath, $destinationFilePath);
            //             $data_img = array('id' => $response->student_id, 'image' => 'uploads/student_images/' . $img_name);
            //             $this->student_model->add($data_img);
            //         }
            //     }

            //     if (isset($_FILES["father_pic"]) && !empty($_FILES['father_pic']['name'])) {
            //         $fileInfo = pathinfo($_FILES["father_pic"]["name"]);
            //         $img_name = $response->student_id . "father" . '.' . $fileInfo['extension'];
            //         move_uploaded_file($_FILES["father_pic"]["tmp_name"], "./uploads/student_images/" . $img_name);
            //         $data_img = array('id' => $response->student_id, 'father_pic' => 'uploads/student_images/' . $img_name);
            //         $this->student_model->add($data_img);
            //     } else {
            //         if ($student['father_pic'] != "") {
            //             $filePath     = $student['father_pic'];
            //             $fileInfo = pathinfo($student['father_pic']);
            //             $img_name = $response->student_id . "father" . '.' . $fileInfo['extension'];
            //             $uploaddir = './uploads/student_images/' . $img_name;
            //             $destinationFilePath = $uploaddir;
            //             copy($filePath, $destinationFilePath);
            //             $data_img = array('id' => $response->student_id, 'father_pic' => 'uploads/student_images/' . $img_name);
            //             $this->student_model->add($data_img);
            //         }
            //     }

            //     if (isset($_FILES["mother_pic"]) && !empty($_FILES['mother_pic']['name'])) {
            //         $fileInfo = pathinfo($_FILES["mother_pic"]["name"]);
            //         $img_name = $response->student_id . "mother" . '.' . $fileInfo['extension'];
            //         move_uploaded_file($_FILES["mother_pic"]["tmp_name"], "./uploads/student_images/" . $img_name);
            //         $data_img = array('id' => $response->student_id, 'mother_pic' => 'uploads/student_images/' . $img_name);
            //         $this->student_model->add($data_img);
            //     } else {

            //         if ($student['mother_pic'] != "") {
            //             $filePath     = $student['mother_pic'];;
            //             $fileInfo = pathinfo($student['mother_pic']);
            //             $img_name = $response->student_id . "mother" . '.' . $fileInfo['extension'];
            //             $uploaddir = './uploads/student_images/' . $img_name;
            //             $destinationFilePath = $uploaddir;
            //             copy($filePath, $destinationFilePath);
            //             $data_img = array('id' => $response->student_id, 'mother_pic' => 'uploads/student_images/' . $img_name);
            //             $this->student_model->add($data_img);
            //         }
            //     }

            //     if (isset($_FILES["guardian_pic"]) && !empty($_FILES['guardian_pic']['name'])) {
            //         $fileInfo = pathinfo($_FILES["guardian_pic"]["name"]);
            //         $img_name = $response->student_id . "guardian" . '.' . $fileInfo['extension'];
            //         move_uploaded_file($_FILES["guardian_pic"]["tmp_name"], "./uploads/student_images/" . $img_name);
            //         $data_img = array('id' => $response->student_id, 'guardian_pic' => 'uploads/student_images/' . $img_name);
            //         $this->student_model->add($data_img);
            //     } else {

            //         if ($student['guardian_pic'] != "") {
            //             $filePath     = $student['guardian_pic'];;
            //             $fileInfo = pathinfo($student['guardian_pic']);
            //             $img_name = $response->student_id . "guardian" . '.' . $fileInfo['extension'];

            //             $uploaddir = './uploads/student_images/' . $img_name;
            //             $destinationFilePath = $uploaddir;
            //             copy($filePath, $destinationFilePath);
            //             $data_img = array('id' => $response->student_id, 'guardian_pic' => 'uploads/student_images/' . $img_name);
            //             $this->student_model->add($data_img);
            //         }
            //     }

            //     if ($response->student_id != "") {

            //         $sender_details = array('student_id' => $response->student_id, 'contact_no' => $this->input->post('guardian_phone'), 'email' => $this->input->post('guardian_email'));
            //         $this->mailsmsconf->mailsms('student_admission', $sender_details);

            //         $student_login_detail = array('id' => $response->student_id, 'credential_for' => 'student', 'username' => $this->student_login_prefix . $response->student_id, 'password' => $response->user_password, 'contact_no' => $this->input->post('mobileno'), 'email' => $this->input->post('email'));
            //         $this->mailsmsconf->mailsms('login_credential', $student_login_detail);

            //         $parent_login_detail = array('id' => $response->student_id, 'credential_for' => 'parent', 'username' => $this->parent_login_prefix . $response->student_id, 'password' => $response->parent_password, 'contact_no' => $this->input->post('guardian_phone'), 'email' => $this->input->post('guardian_email'));
            //         $this->mailsmsconf->mailsms('login_credential', $parent_login_detail);
            //     }

            //     $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('update_message') . '</div>');
            //     redirect('admin/parentenquiry');
            // } else {
            //     $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('please_check_student_admission_no') . '</div>');
            //     redirect($_SERVER['HTTP_REFERER']);
            // }
        }
        echo json_encode($array);
    }


    public function edit($id)
    {
        if (!$this->rbac->hasPrivilege('parent_enquiry', 'can_edit')) {
            access_denied();
        }
        $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
        $data['title']           = 'Edit Student';

        $data['id']      = $id;
        $enquiry_data = $this->Enquiry_with_scanner_model->get($id);
        $genderList      = $this->customlib->getGender();
        $data['student'] = $enquiry_data;
        //print_r($student);die;
        $data['genderList']         = $genderList;
        $session                    = $this->setting_model->getCurrentSession();
        $vehroute_result            = $this->vehroute_model->get();
        $data['vehroutelist']       = $vehroute_result;
        $class                      = $this->class_model->get();
        $setting_result             = $this->setting_model->get();
        $data["bloodgroup"]         = $this->blood_group;
        $data["student_categorize"] = 'class';
        $data['classlist']          = $class;
        $category                   = $this->category_model->get();
        $data['categorylist']       = $category;
        $hostelList                 = $this->hostel_model->get();
        $data['hostelList']         = $hostelList;
        $houses                     = $this->houselist_model->get();
        $data['houses']             = $houses;
        $data['sch_setting']        = $this->sch_setting_detail;

        if ($this->input->post('save') == 'enroll') {
            if (!$this->sch_setting_detail->adm_auto_insert) {

                $this->form_validation->set_rules('admission_no', $this->lang->line('admission_no'), array('required', array('check_admission_no_exists', array($this->student_model, 'valid_student_admission_no'))));
            }
            $this->form_validation->set_rules(
                'email',
                $this->lang->line('email'),
                array(
                    'valid_email',
                    array('check_student_email_exists', array($this->student_model, 'check_student_email_exists')),
                )
            );
        }

        $this->form_validation->set_rules('firstname', $this->lang->line('first_name'), 'trim|required|xss_clean');
        if ($this->sch_setting_detail->guardian_name) {
            $this->form_validation->set_rules('guardian_is', $this->lang->line('guardian'), 'trim|required|xss_clean');
        }

        $this->form_validation->set_rules('dob', $this->lang->line('date_of_birth'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('gender', $this->lang->line('gender'), 'trim|required|xss_clean');
        if ($this->sch_setting_detail->guardian_name) {
            $this->form_validation->set_rules('guardian_name', $this->lang->line('guardian_name'), 'trim|required|xss_clean');
        }
        if ($this->sch_setting_detail->rte) {
            $this->form_validation->set_rules('rte', $this->lang->line('rtl'), 'trim|required|xss_clean');
        }
        if ($this->sch_setting_detail->guardian_phone) {
            $this->form_validation->set_rules('guardian_phone', $this->lang->line('guardian_phone'), 'trim|required|xss_clean');
        }

        $custom_fields = $this->customfield_model->getByBelong('students');
        foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
            if ($custom_fields_value['validation'] && $this->customlib->getfieldstatus($custom_fields_value['name'])) {
                $custom_fields_id   = $custom_fields_value['id'];
                $custom_fields_name = $custom_fields_value['name'];
                $this->form_validation->set_rules("custom_fields[students][" . $custom_fields_id . "]", $custom_fields_name, 'trim|required');
            }
        }

        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/parentenquiry/student_Edit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $student_id     = $this->input->post('student_id');
            $class_id       = $this->input->post('class_id');
            $section_id     = $this->input->post('section_id');
            $hostel_room_id = $this->input->post('hostel_room_id');
            $fees_discount  = $this->input->post('fees_discount');
            $vehroute_id    = $this->input->post('vehroute_id');
            if (empty($vehroute_id)) {
                $vehroute_id = 0;
            }
            if (empty($hostel_room_id)) {
                $hostel_room_id = 0;
            }

            $custom_field_post = $this->input->post("custom_fields[students]");
            if (isset($custom_field_post)) {
                $custom_value_array = array();
                foreach ($custom_field_post as $key => $value) {
                    $check_field_type = $this->input->post("custom_fields[students][" . $key . "]");
                    $field_value      = is_array($check_field_type) ? implode(",", $check_field_type) : $check_field_type;
                    $array_custom     = array(
                        'belong_table_id' => $id,
                        'custom_field_id' => $key,
                        'field_value'     => $field_value,
                    );
                    $custom_value_array[] = $array_custom;
                }
                $this->customfield_model->updateRecord($custom_value_array, $id, 'students');
            }

            $data = array(
                'id'                => $student_id,
                'admission_no'      => $this->input->post('admission_no'),
                'roll_no'           => $this->input->post('roll_no'),
                'firstname'         => $this->input->post('firstname'),
                'lastname'          => $this->input->post('lastname'),
                'rte'               => $this->input->post('rte'),
                'mobileno'          => $this->input->post('mobileno'),
                'email'             => $this->input->post('email'),
                'state'             => $this->input->post('state'),
                'city'              => $this->input->post('city'),
                'previous_school'   => $this->input->post('previous_school'),
                'pincode'           => $this->input->post('pincode'),
                'measurement_date'  => $this->customlib->dateFormatToYYYYMMDD($this->input->post('measure_date')),
                'religion'          => $this->input->post('religion'),
                'dob'               => $this->customlib->dateFormatToYYYYMMDD($this->input->post('dob')),
                'admission_date'    => $this->customlib->dateFormatToYYYYMMDD($this->input->post('admission_date')),
                'current_address'   => $this->input->post('current_address'),
                'permanent_address' => $this->input->post('permanent_address'),
                'category_id'       => $this->input->post('category_id'),
                'adhar_no'          => $this->input->post('adhar_no'),
                'samagra_id'        => $this->input->post('samagra_id'),
                'bank_account_no'   => $this->input->post('bank_account_no'),
                'bank_name'         => $this->input->post('bank_name'),
                'ifsc_code'         => $this->input->post('ifsc_code'),
                'cast'              => $this->input->post('cast'),
                'father_name'       => $this->input->post('father_name'),
                'father_phone'      => $this->input->post('father_phone'),
                'father_occupation' => $this->input->post('father_occupation'),
                'mother_name'       => $this->input->post('mother_name'),
                'mother_phone'      => $this->input->post('mother_phone'),
                'mother_occupation' => $this->input->post('mother_occupation'),
                'guardian_email'    => $this->input->post('guardian_email'),
                'gender'            => $this->input->post('gender'),
                'guardian_name'     => $this->input->post('guardian_name'),
                'guardian_relation' => $this->input->post('guardian_relation'),
                'guardian_phone'    => $this->input->post('guardian_phone'),
                'guardian_address'  => $this->input->post('guardian_address'),
                'vehroute_id'       => $vehroute_id,
                'hostel_room_id'    => $hostel_room_id,
                'note'              => $this->input->post('note'),
                'class_section_id'  => $section_id,
            );
            if ($this->sch_setting_detail->guardian_name) {
                $data['guardian_is'] = $this->input->post('guardian_is');
            }

            if ($this->sch_setting_detail->is_student_house) {
                $data['school_house_id'] = $this->input->post('house');
            }

            if ($this->sch_setting_detail->guardian_occupation) {
                $data['guardian_occupation'] = $this->input->post('guardian_occupation');
            }

            if ($this->sch_setting_detail->is_blood_group) {
                $data['blood_group'] = $this->input->post('blood_group');
            }

            if ($this->sch_setting_detail->student_height) {
                $data['height'] = $this->input->post('height');
            }

            if ($this->sch_setting_detail->student_weight) {
                $data['weight'] = $this->input->post('weight');
            }
            if ($this->sch_setting_detail->measurement_date) {
                $data['measurement_date'] = $this->input->post('measure_date');
            }

            $response = $this->Enquiry_with_scanner_model->student_update($data, $this->input->post('save'));

            if ($response) {
                $response = json_decode($response);

                //to upload document from online student to main firl
                // if (isset($student['document']) && !empty($student['document'])) {
                //     $uploaddir = './uploads/student_documents/' . $response->student_id . '/';
                //     if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
                //         die("Error creating folder $uploaddir");
                //     }

                //     $file_name           = basename($student['document']);
                //     $img_name            = $uploaddir . $file_name;
                //     $filePath            = "./uploads/student_documents/online_admission_doc/" . $student['document'];
                //     $destinationFilePath = $img_name;
                //     copy($filePath, $destinationFilePath);

                //     $data_img = array('student_id' => $response->student_id, 'doc' => $file_name);
                //     $this->student_model->adddoc($data_img);
                // }

                // to upload father mother student and guardian image

                if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {

                    $fileInfo = pathinfo($_FILES["file"]["name"]);
                    $img_name = $response->student_id . '.' . $fileInfo['extension'];
                    move_uploaded_file($_FILES["file"]["tmp_name"], "./uploads/student_images/" . $img_name);
                    $data_img = array('id' => $response->student_id, 'image' => 'uploads/student_images/' . $img_name);
                    $this->student_model->add($data_img);
                } else {

                    if ($student['image'] != "") {
                        $filePath     = $student['image'];
                        $path =  "./uploads/front_office/enquiry/" . $student['image'];
                        $fileInfo = pathinfo($path);
                        $img_name = $response->student_id . '.' . $fileInfo['extension'];

                        $uploaddir = './uploads/student_images/' . $img_name;
                        $destinationFilePath = $uploaddir;

                        copy($filePath, $destinationFilePath);
                        $data_img = array('id' => $response->student_id, 'image' => 'uploads/student_images/' . $img_name);
                        $this->student_model->add($data_img);
                    }
                }

                if (isset($_FILES["father_pic"]) && !empty($_FILES['father_pic']['name'])) {
                    $fileInfo = pathinfo($_FILES["father_pic"]["name"]);
                    $img_name = $response->student_id . "father" . '.' . $fileInfo['extension'];
                    move_uploaded_file($_FILES["father_pic"]["tmp_name"], "./uploads/student_images/" . $img_name);
                    $data_img = array('id' => $response->student_id, 'father_pic' => 'uploads/student_images/' . $img_name);
                    $this->student_model->add($data_img);
                } else {
                    if ($student['father_pic'] != "") {
                        $filePath     = $student['father_pic'];
                        $fileInfo = pathinfo($student['father_pic']);
                        $img_name = $response->student_id . "father" . '.' . $fileInfo['extension'];
                        $uploaddir = './uploads/student_images/' . $img_name;
                        $destinationFilePath = $uploaddir;
                        copy($filePath, $destinationFilePath);
                        $data_img = array('id' => $response->student_id, 'father_pic' => 'uploads/student_images/' . $img_name);
                        $this->student_model->add($data_img);
                    }
                }

                if (isset($_FILES["mother_pic"]) && !empty($_FILES['mother_pic']['name'])) {
                    $fileInfo = pathinfo($_FILES["mother_pic"]["name"]);
                    $img_name = $response->student_id . "mother" . '.' . $fileInfo['extension'];
                    move_uploaded_file($_FILES["mother_pic"]["tmp_name"], "./uploads/student_images/" . $img_name);
                    $data_img = array('id' => $response->student_id, 'mother_pic' => 'uploads/student_images/' . $img_name);
                    $this->student_model->add($data_img);
                } else {

                    if ($student['mother_pic'] != "") {
                        $filePath     = $student['mother_pic'];;
                        $fileInfo = pathinfo($student['mother_pic']);
                        $img_name = $response->student_id . "mother" . '.' . $fileInfo['extension'];
                        $uploaddir = './uploads/student_images/' . $img_name;
                        $destinationFilePath = $uploaddir;
                        copy($filePath, $destinationFilePath);
                        $data_img = array('id' => $response->student_id, 'mother_pic' => 'uploads/student_images/' . $img_name);
                        $this->student_model->add($data_img);
                    }
                }

                if (isset($_FILES["guardian_pic"]) && !empty($_FILES['guardian_pic']['name'])) {
                    $fileInfo = pathinfo($_FILES["guardian_pic"]["name"]);
                    $img_name = $response->student_id . "guardian" . '.' . $fileInfo['extension'];
                    move_uploaded_file($_FILES["guardian_pic"]["tmp_name"], "./uploads/student_images/" . $img_name);
                    $data_img = array('id' => $response->student_id, 'guardian_pic' => 'uploads/student_images/' . $img_name);
                    $this->student_model->add($data_img);
                } else {

                    if ($student['guardian_pic'] != "") {
                        $filePath     = $student['guardian_pic'];;
                        $fileInfo = pathinfo($student['guardian_pic']);
                        $img_name = $response->student_id . "guardian" . '.' . $fileInfo['extension'];

                        $uploaddir = './uploads/student_images/' . $img_name;
                        $destinationFilePath = $uploaddir;
                        copy($filePath, $destinationFilePath);
                        $data_img = array('id' => $response->student_id, 'guardian_pic' => 'uploads/student_images/' . $img_name);
                        $this->student_model->add($data_img);
                    }
                }

                if ($response->student_id != "") {

                    $sender_details = array('student_id' => $response->student_id, 'contact_no' => $this->input->post('guardian_phone'), 'email' => $this->input->post('guardian_email'));
                    $this->mailsmsconf->mailsms('student_admission', $sender_details);

                    $student_login_detail = array('id' => $response->student_id, 'credential_for' => 'student', 'username' => $this->student_login_prefix . $response->student_id, 'password' => $response->user_password, 'contact_no' => $this->input->post('mobileno'), 'email' => $this->input->post('email'));
                    $this->mailsmsconf->mailsms('login_credential', $student_login_detail);

                    $parent_login_detail = array('id' => $response->student_id, 'credential_for' => 'parent', 'username' => $this->parent_login_prefix . $response->student_id, 'password' => $response->parent_password, 'contact_no' => $this->input->post('guardian_phone'), 'email' => $this->input->post('guardian_email'));
                    $this->mailsmsconf->mailsms('login_credential', $parent_login_detail);
                }

                $this->session->set_userdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('update_message') . '</div>');
                redirect('admin/parentenquiry');
            } else {
                $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('please_check_student_admission_no') . '</div>');
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
    }



    public function follow_up($enquiry_id)
    {

        if (!$this->rbac->hasPrivilege('parent_enquiry', 'can_view')) {
            access_denied();
        }
        $data['id'] = $enquiry_id;
        $data['enquiry_data'] = $this->Enquiry_with_scanner_model->get($enquiry_id);
        // $data['stff_list'] = $this->staff_model->get();

        $data['next_date'] = $this->Enquiry_with_scanner_model->next_follow_up_date($enquiry_id);
        $data['enquiry_status'] = $this->enquiry_status;

        $this->load->view('admin/parentenquiry/parentenquiry_follow_up_modal', $data);
    }


    public function follow_up_insert()
    {
        if (!$this->rbac->hasPrivilege('parent_enquiry', 'can_add')) {
            access_denied();
        }

        $this->form_validation->set_rules('response', $this->lang->line('response'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('date', $this->lang->line('follow_up_date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('follow_up_date', $this->lang->line('next_follow_up_date'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $msg = array(
                'response' => form_error('response'),
                'follow_up_date' => form_error('follow_up_date'),
                'date' => form_error('date'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $admin = $this->customlib->getLoggedInUserData();
            $follow_up = array(
                'date' => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date'))),
                'next_date' => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('follow_up_date'))),
                'response' => $this->input->post('response'),
                'note' => $this->input->post('note'),
                'followup_by' => $admin['username'],
                'enquiry_id' => $this->input->post('enquiry_id')
            );
            $this->Enquiry_with_scanner_model->add_follow_up($follow_up);
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }

        echo json_encode($array);
    }



    function follow_up_list($id)
    {
        $data['id'] = $id;
        $data['follow_up_list'] = $this->Enquiry_with_scanner_model->getfollow_up_list($id);
        $this->load->view('admin/parentenquiry/followuplist', $data);
    }


    public function change_status()
    {
        $id = $this->input->post("id");
        $status = $this->input->post("status");
        if (!empty($id)) {
            $data = array('id' => $id, 'status' => $status);
            $this->Enquiry_with_scanner_model->changeStatus($data);
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        } else {
            $array = array('status' => 'fail', 'error' => '', 'message' => $this->lang->line('update_message'));
        }

        echo json_encode($array);
    }



    public function follow_up_delete($follow_up_id, $enquiry_id)
    {
        if (!$this->rbac->hasPrivilege('parent_enquiry', 'can_delete')) {
            access_denied();
        }
        $this->Enquiry_with_scanner_model->delete_follow_up($follow_up_id);
        $data['id'] = $enquiry_id;
        $data['follow_up_list'] = $this->Enquiry_with_scanner_model->getfollow_up_list($enquiry_id);
        $this->load->view('admin/parentenquiry/followuplist', $data);
    }


    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('parent_enquiry', 'can_delete')) {
            access_denied();
        }
        $this->Enquiry_with_scanner_model->remove($id);

        redirect('admin/parentenquiry');
    }
}
