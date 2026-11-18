<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Daycare extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->config->load("payroll");

        $this->config->load("app-config");

        $this->load->library('Enc_lib');

        $this->load->library('mailsmsconf');

        $this->load->model("staff_model");

        $this->load->model('Schooldivison_model');

        $this->load->model("Daycare_model");

        $this->load->model("Daycare_service_model");

        $this->load->library('encoding_lib');

        $this->load->model("leaverequest_model");

        $this->sch_current_session = $this->setting_model->getCurrentSession();

        $this->contract_type      = $this->config->item('contracttype');

        $this->marital_status     = $this->config->item('marital_status');

        $this->staff_attendance   = $this->config->item('staffattendance');

        $this->payroll_status     = $this->config->item('payroll_status');

        $this->payment_mode       = $this->config->item('payment_mode');

        $this->status             = $this->config->item('status');

        $this->sch_setting_detail = $this->setting_model->getSetting();
    }

    public function index()
    {

        if (!$this->rbac->hasPrivilege('daycare_student', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Daycare');
        $this->session->set_userdata('sub_menu', 'daycare/index');
        $data['title'] = 'Student Search';

        $class = $this->Daycare_model->getclasslist();
        $data['classlist'] = $class;
        $button = $this->input->post('search');
        if ($this->input->server('REQUEST_METHOD') == "GET") {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/daycare/studentSearch', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $class = $this->input->post('class_id');
            $section = $this->input->post('section_id');
            $search = $this->input->post('search');
            $search_text = $this->input->post('search_text');
            if (isset($search)) {
                if ($search == 'search_filter') {
                    $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
                    if ($this->form_validation->run() == false) {
                    } else {
                        $data['searchby'] = "filter";
                        $data['class_id'] = $this->input->post('class_id');
                        $data['section_id'] = $this->input->post('section_id');
                        $data['search_text'] = $this->input->post('search_text');
                        $resultlist = $this->student_model->searchDaycareStudent($class, $section);

                        $data['resultlist'] = $resultlist;
                    }
                } else if ($search == 'search_full') {
                    $data['searchby'] = "text";
                    $data['class_id'] = $this->input->post('class_id');
                    $data['section_id'] = $this->input->post('section_id');
                    $data['search_text'] = trim($this->input->post('search_text'));
                    $resultlist = $this->student_model->searchFullText($search_text);
                    $data['resultlist'] = $resultlist;
                }
            }
            $data['sch_setting'] = $this->sch_setting_detail;
            $this->load->view('layout/header', $data);
            $this->load->view('admin/daycare/studentSearch', $data);
            $this->load->view('layout/footer', $data);
        }
    }
   
    public function students()
    {
        if (!$this->rbac->hasPrivilege('daycare_student', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Daycare');
        $this->session->set_userdata('sub_menu', 'daycare/students');

        $data['title']      = 'Student';
        $data['title_list'] = 'Student Details';
        $memberList = $this->Daycare_model->get();
        $data['memberList'] = $memberList;
        $data['sch_setting'] = $this->sch_setting_detail;
        $this->load->view('layout/header');
        $this->load->view('admin/daycare/student', $data);
        $this->load->view('layout/footer');
    }

    public function get_daycare_activities_by_date()
    {
        $student_id = $this->input->post('student_id');
        $date = $this->input->post('date');

        if (!$student_id || !$date) {
            show_error('Invalid request');
        }

        $daycare_enrolled_student = $this->Daycare_model->get($student_id);

        $activities = $this->Daycare_service_model->getActivitiesByStudentAndDate($student_id, $date);

        $data['student_profile'] = $daycare_enrolled_student[0];

        $data['activities'] = $activities;

        $data['date'] = $date;

        $this->load->view('admin/daycare/activity/_activity_timeline', $data);
    }



    public function add()
    {
        if ($this->input->post('enrollment_no') != "") {

            $this->form_validation->set_rules('enrollment_no', $this->lang->line('enrollment_no'), 'required|trim|xss_clean|callback_check_cardno_exists');
            if ($this->form_validation->run() == false) {
                $data = array(
                    'enrollment_no' => form_error('enrollment_no'),
                );
                $array = array('status' => 'fail', 'error' => $data);
                echo json_encode($array);
            } else {
                $enrollment_no = $this->input->post('enrollment_no');
                $student = $this->input->post('student_id');
                $data = array(
                    'student_type' => 'student',
                    'student_id' => $student,
                    'enrollment_no' => $enrollment_no,
                );

                $inserted_id = $this->Daycare_model->add($data);
                $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'), 'inserted_id' => $inserted_id, 'enrollment_no' => $enrollment_no);
                echo json_encode($array);
            }
        } else {
            $enrollment_no = $this->input->post('enrollment_no');
            $student = $this->input->post('student_id');
            $data = array(
                'student_type' => 'student',
                'student_id' => $student,
                'enrollment_no' => $enrollment_no,
            );

            $inserted_id = $this->Daycare_model->add($data);
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'), 'inserted_id' => $inserted_id, 'enrollment_no' => $enrollment_no);
            echo json_encode($array);
        }
    }

    public function check_cardno_exists()
    {
        $data['enrollment_no'] = $this->security->xss_clean($this->input->post('enrollment_no'));

        if ($this->Daycare_model->check_data_exists($data)) {
            $this->form_validation->set_message('check_cardno_exists', $this->lang->line('card_no_already_exists'));
            return false;
        } else {
            return true;
        }
    }

    public function surrender()
    {
        $this->form_validation->set_rules('daycare_student_id', $this->lang->line('book'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
        } else {
            $daycare_student_id = $this->input->post('daycare_student_id');
            $this->Daycare_model->surrender($daycare_student_id);
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
            echo json_encode($array);
        }
    }

    public function check_in()
    {
        $student_id = $this->input->post('student_id');
        $check_in_time = date('Y-m-d H:i:s');
        $status = $this->input->post('status');

        $data = array(
            'student_id' => $student_id,
            'check_in_time' => $check_in_time,
            'status' => $status,
        );

        $activity = array(
            'student_id' => $student_id,
            'date' => $check_in_time,
            'description'  => 'Reached Day Care Center',
        );

        $result = $this->Daycare_model->insertCheckInOut($data,$activity);

        if ($result) {
            $response = array('status' => 1, 'msg' => $this->lang->line('status_change_successfully'));
            echo json_encode($response);
        }
    }


    public function check_out()
    {
        $student_id = $this->input->post('student_id');

        $guardian_name = $this->input->post('guardian_name');

        if (isset($_FILES["guardian_pic"]) && !empty($_FILES['guardian_pic']['name'])) {

            $fileInfo = pathinfo($_FILES["guardian_pic"]["name"]);

            $img_name = uniqid('guardian_', true) . '.' . $fileInfo['extension'];

            move_uploaded_file($_FILES["guardian_pic"]["tmp_name"], "./uploads/guardian_pic/" . $img_name);

            $guardian_pic = 'uploads/guardian_pic/' . $img_name;
        }

        $this->Daycare_model->updateGuardianName($student_id, $guardian_name, $guardian_pic);

        echo json_encode(['success' => true, 'msg' => 'Sent For Authorization Of Parent']);
    }


    public function final_check_out()
    {
        $student_id = $this->input->post('student_id');

        $check_out_time = date('Y-m-d H:i:s');

        $this->Daycare_model->updateCheckOut($student_id, $check_out_time);

        echo json_encode(['success' => true, 'msg' => 'Checkout Succesfully']);
    }


    public function check_verification_status()
    {
        $student_id = $this->input->post('student_id');

        $this->db->where('student_id', $student_id);
        $this->db->where('status', 'Checked In');
        $query = $this->db->get('check_in_out');
        $data = $query->row();

        echo json_encode(['is_verified' => $data->is_verified, 'guardian_pic' => $data->guardian_pic]);
    }


    public function staff()
    {
        if (!$this->rbac->hasPrivilege('daycare_staff', 'can_view')) {

            access_denied();
        }

        $data['title']  = 'Staff Search';

        $data['fields'] = $this->customfield_model->get_custom_fields('staff', 1);

        $this->session->set_userdata('top_menu', 'Daycare');

        $this->session->set_userdata('sub_menu', 'daycare/staff');

        $search             = $this->input->post("search");

        $resultlist         = $this->Daycare_model->searchFullText("", 1);

        $data['resultlist'] = $resultlist;

        $staffRole          = $this->staff_model->getStaffRole();

        $data["role"]       = $staffRole;

        $data["role_id"]    = "";

        $search_text        = $this->input->post('search_text');

        if (isset($search)) {

            if ($search == 'search_filter') {

                $this->form_validation->set_rules('role', $this->lang->line('role'), 'trim|required|xss_clean');

                if ($this->form_validation->run() == false) {

                    $data["resultlist"] = array();
                } else {

                    $data['searchby']    = "filter";

                    $role                = $this->input->post('role');

                    $data['employee_id'] = $this->input->post('empid');

                    $data["role_id"]     = $role;

                    $data['search_text'] = $this->input->post('search_text');

                    $resultlist          = $this->staff_model->getEmployee($role, 1);

                    $data['resultlist']  = $resultlist;
                }
            } else if ($search == 'search_full') {

                $data['searchby']    = "text";

                $data['search_text'] = trim($this->input->post('search_text'));

                $resultlist          = $this->staff_model->searchFullText($search_text, 1);

                $data['resultlist'] = $resultlist;

                $data['title']      = 'Search Details: ' . $data['search_text'];
            }
        }

        $this->load->view('layout/header');

        $this->load->view('admin/daycare/staffsearch', $data);

        $this->load->view('layout/footer');
    }


    public function service()
    {
        if (!$this->rbac->hasPrivilege('daycare_service', 'can_view')) {

            access_denied();
        }

        $data['title']  = 'Daycare Service';

        $this->session->set_userdata('top_menu', 'Daycare');

        $this->session->set_userdata('sub_menu', 'daycare/service');

        $this->form_validation->set_rules('service_name', 'service_name', 'trim|required|xss_clean');


        $resultlist         = $this->Daycare_model->searchFullText("", 1);

        $data['resultlist'] = $resultlist;

        $memberList = $this->Daycare_model->get_checked_in_students();

        $data['memberList'] = $memberList;

        $data['grouped_activities'] = $this->Daycare_model->getGroupedDaycareActivities(date('Y-m-d'));

        // $this->form_validation->set_rules(

        //     'service_name',
        //     'service_name',
        //     array(
        //         'required',
        //         array('service_name_exists', array($this->Daycare_service_model, 'service_name_exists')),
        //     )
        // );

        if ($this->form_validation->run() == false) {
        } else {
            $data = [

                'service_name' => $this->input->post('service_name'),
                'service_description' => $this->input->post('service_description'),
                'is_active' => 'no',
            ];

            $this->Daycare_service_model->insert_service($data);

            $this->session->set_userdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');

            redirect('admin/daycare/service');
        }

        $data['services'] = $this->Daycare_service_model->get_services();

        $this->load->view('layout/header');

        $this->load->view('admin/daycare/services/list', $data);

        $this->load->view('layout/footer');
    }

    public function edit($id)
    {

        if (!$this->rbac->hasPrivilege('daycare_service', 'can_edit')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Daycare');
        $this->session->set_userdata('sub_menu', 'daycare/service');
        $data['title']      = 'Edit Service';
        $data['id']         = $id;
        $service           = $this->Daycare_service_model->get_services($id);
        $data['service']   = $service;
        $data['title_list'] = 'Daycare Services List';

        // $this->form_validation->set_rules(
        //     'service_name',
        //     'service_name',
        //     array(
        //         'required',
        //         array('service_name_exists', array($this->Daycare_service_model, 'service_name_exists')),
        //     )
        // );

        $this->form_validation->set_rules('service_name', 'service_name', 'trim|required|xss_clean');


        if ($this->form_validation->run() == false) {

            $data['services'] = $this->Daycare_service_model->get_services();

            $this->load->view('layout/header', $data);

            $this->load->view('admin/daycare/services/serviceEdit', $data);

            $this->load->view('layout/footer', $data);
        } else {

            $pre_service_name = $this->input->post('pre_service_name');

            $update_data = [

                'id' => $id,

                'service_name' => $this->input->post('service_name'),

                'service_description' => $this->input->post('service_description'),

            ];

            $this->Daycare_service_model->insert_service($update_data);

            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('update_message') . '</div>');

            redirect('admin/daycare/service');
        }
    }


    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('daycare_service', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'Daycare Services';

        $this->Daycare_service_model->delete_service($id);

        redirect('admin/daycare/service');
    }


    public function check()
    {
        // $memberList = $this->Daycare_model->get();

        // echo "<pre>";
        // print_r($memberList);


        // $memberList = $this->Daycare_model->get_checked_in_students();

        // echo "<pre>";
        // print_r($memberList);

        $grouped_activities = $this->Daycare_model->getGroupedDaycareActivities(date('Y-m-d'));

        echo "<pre>";
        print_r($grouped_activities);


    }

    public function activity()
    {
        if (!$this->rbac->hasPrivilege('daycare_activity', 'can_view')) {
            access_denied();
        }

        $data['title'] = 'Daycare Activity';
    
        $this->session->set_userdata('top_menu', 'Daycare');

        $this->session->set_userdata('sub_menu', 'daycare/activity');

        $data['grouped_activities'] = $this->Daycare_model->getGroupedDaycareActivities();

        $resultlist         = $this->Daycare_model->searchFullText("", 1);

        $data['resultlist'] = $resultlist;

        $memberList = $this->Daycare_model->get_checked_in_students();

        $data['memberList'] = $memberList;

        $memberList_all = $this->Daycare_model->get();

        $data['memberListall'] = $memberList_all;

        $this->load->view('layout/header', $data);

        $this->load->view('admin/daycare/activity/activity_list', $data);

        $this->load->view('layout/footer', $data);
    }



    public function save_activity()
    {

        $this->form_validation->set_rules('assigned_staff', 'assigned_staff', 'trim|required|xss_clean');
        
        // $this->form_validation->set_rules('selected_children[]', 'children', 'required', array('required' => 'Please select at least one child.'));
        
        $submitted_date = $this->input->post('date');
        $current_date = date('Y-m-d');

        if ($submitted_date === $current_date) {

            $this->form_validation->set_rules('time', 'time', 'trim|required|xss_clean');
        
            $this->form_validation->set_rules('date', $this->lang->line('date'), 'trim|required|xss_clean');

            $this->form_validation->set_rules(
                'selected_children[]',
                'children',
                'required',
                ['required' => 'Please select at least one child.']
            );
        }

        if ($this->form_validation->run() == false) {
            $msg = array(
                'assigned_staff'      => form_error('assigned_staff'),
                'time'      => form_error('time'),
                'date'          => form_error('date'),
                'children' => form_error('selected_children[]'),
               
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {

                $activity_id = $this->input->post('daycare_activity_id'); // new hidden input for edit
           
                $data = array(
                    'session_id'              => $this->sch_current_session,
                    'date'                    => $this->input->post('date'),
                    'assigned_staff'          => $this->input->post('assigned_staff'),
                    'daycare_service_id'      => $this->input->post('activity_id'),
                    'meal'                    => $this->input->post('meal'),
                    'description'             => $this->input->post('description'),
                    'quantity'                => $this->input->post('quantity'),
                    'time'                    => $this->input->post('time'),
                    
                );

                if (!empty($activity_id)) {
                    $data['id'] = $activity_id;
                }
    
                $insert_id = $this->Daycare_service_model->add_activity($data);

                if (!empty($activity_id) && $submitted_date === $current_date) {

                    $this->Daycare_service_model->delete_student_activity($activity_id);
                   
                }
    
                // if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                //     $fileInfo = pathinfo($_FILES["file"]["name"]);
                //     $img_name = time() . '.' . $fileInfo['extension'];
                //     move_uploaded_file($_FILES["file"]["tmp_name"], "./uploads/syllabus_attachment/" . $img_name);
                //     $data_img = array('id' => $insert_id, 'attachment' => $img_name);
                //     $this->lessonplan_model->update_syllabus($data_img);
                // }

                if (!empty($this->input->post('selected_children'))) {
                    $children_data = array();
                    foreach ($this->input->post('selected_children') as $child_id) {
                        $children_data[] = array(
                            'daycare_activity_id' => $insert_id,
                            'student_id'          => $child_id,
                            'date'                => date('Y-m-d H:i:s'),
                            'description'         => $this->input->post('description'),
                        );
                    }
                    
                    $this->Daycare_service_model->insert_activity_children($children_data);
                }
               
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }
}
