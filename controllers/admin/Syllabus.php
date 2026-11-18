<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Syllabus extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->sch_current_session = $this->setting_model->getCurrentSession();
        $this->staff_id            = $this->customlib->getStaffID();
        $this->sch_setting_detail  = $this->setting_model->getSetting();
        $this->start_weekday       = strtolower($this->sch_setting_detail->start_week);
    }

    public function index()
    {
        if (!($this->rbac->hasPrivilege('manage_lesson_plan', 'can_view'))) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'lessonplan');
        $this->session->set_userdata('sub_menu', 'admin/syllabus');
        $my_role                 = $this->customlib->getStaffRole();
        $role                    = json_decode($my_role);
        $data['role_id']         = $role->id;
        $staff_list              = $this->staff_model->getEmployee('2');
        $data['staff_list']      = $staff_list;
        $monday                  = strtotime("last " . $this->start_weekday);
        $monday                  = date('w', $monday) == date('w') ? $monday + 7 * 86400 : $monday;
        $sunday                  = strtotime(date("Y-m-d", $monday) . " +6 days");
        $this_week_start         = date("Y-m-d", $monday);
        $this_week_end           = date("Y-m-d", $sunday);
        $data['this_week_start'] = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($this_week_start));
        $data['this_week_end']   = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($this_week_end));
        $data['staff_id']        = $this->staff_id;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/syllabus/index', $data);
        $this->load->view('layout/footer', $data);
    }

    public function check()
    {
        // $this_week_start         = $this->customlib->dateFormatToYYYYMMDD('24-03-2025');
        // $prev_week_start         = date("Y-m-d", strtotime('last ' . $this->start_weekday, strtotime($this_week_start))); //2025-02-10

        // $next_week_start         = date("Y-m-d", strtotime('next ' . $this->start_weekday, strtotime($this_week_start))); //2025-02-24
        // $this_week_end           = date("Y-m-d", strtotime($this_week_start . " +6 day"));
        // $data['this_week_start'] = $this->customlib->dateformat($this_week_start);
        // $data['this_week_end']   = $this->customlib->dateformat($this_week_end);
        // $data['prev_week_start'] = $this->customlib->dateformat($prev_week_start);
        // $data['next_week_start'] = $this->customlib->dateformat($next_week_start);

        // $staff_id = "72";

        // $days              = $this->customlib->getDaysname();
        // $userdata          = $this->customlib->getUserData();

        // $role_id           = $userdata["role_id"];
        // $condition         = "";

        // foreach ($days as $day_key => $day_value) {

        //     $timetableid = "";
        //     $concate     = "no";
        //     if (isset($role_id) && ($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
        //         $myclasssubjects = $this->subjecttimetable_model->getByStaffClassTeacherandDay($staff_id, $day_key);

        //         if (!empty($myclasssubjects[0]->timetable_id)) {

        //             $timetableid = $myclasssubjects[0]->timetable_id;
        //             $concate     = "yes";
        //         }
        //     }

        //     $mysubjects = $this->subjecttimetable_model->getByTeacherSubjectandDay($staff_id, $day_key);
        //     if (!empty($mysubjects[0]->timetable_id)) {
        //         if ($concate == 'yes') {
        //             $timetableid = $timetableid . "," . $mysubjects[0]->timetable_id;
        //         } else {
        //             $timetableid = $mysubjects[0]->timetable_id;
        //         }
        //     }

        //     if ($timetableid == '') {

        //         $condition = " and subject_timetable.id in(0) ";
        //     } else {

        //         $condition = " and subject_timetable.id in(" . $timetableid . ") ";
        //     }
        //     $data['timetable'][$day_key] = $this->subjecttimetable_model->getSyllabussubject($staff_id, $day_key, $condition);
        // }

        // echo "<pre>";
        // print_r($data['timetable']);

        //==============================================================================
        
        // $next_date="2025-03-08";
        
        // $holiday_or_leave = $this->syllabus_model->getHolidayOrLeaveByStaffAndDate($staff_id, $next_date);

        // if($holiday_or_leave){
        //     echo 1;
        // }
        // else{
        //     echo 0;
        // }

        //======================================================================

        // $all_staff=$this->staff_model->getStaffbyrole(2);

        // echo "<Pre>";
        // echo count($all_staff);
        // print_r($all_staff);


        //=============================================================

        // $class_id = '27';
        // $section_id = '12';
        // $subject_group_id = '101';
        // $subject_group_subject_id ='1261';

        // $new_date="25-03-2025";
        // $role ="1";
        // $staff_id="72";
        // $time_from ="8:00 AM";
        // $time_to = "8:40 AM";

        // $subject_group_subject_class_section = $this->lessonplan_model->getsubject_group_class_sectionsId($class_id, $section_id, $subject_group_id);

        // $subject_syllabus = $this->syllabus_model->get_subject_syllabusdata($subject_group_subject_id, date('Y-m-d', strtotime($new_date)), $role, $staff_id, $time_from, $time_to, $subject_group_subject_class_section['id']);

        // $subject_syllabus_without_topic = $this->syllabus_model->get_subject_syllabusdata_without_topic(date('Y-m-d', strtotime($new_date)),$staff_id ,$time_from, $time_to);

        // if ($subject_syllabus[0]['total'] > 0) {
        //     $action = $subject_syllabus[0]['id'];
        // } else {
        //     $action = 0;
        // }

        // echo "<pre>";
        // print_r($subject_syllabus);

        // echo "<pre>";
        //  print_r($subject_syllabus_without_topic);

        //=========================================================================

        $data['id']      = '1';
        $staff_id        = '72';
        
        $result = $this->syllabus_model->get_subject_syllabus_without_topic($data, $staff_id);

        echo "<pre>";
        print_r($result);

    }

    public function get_weekdates()
    {
        $this_week_start         = $this->customlib->dateFormatToYYYYMMDD($_POST['date']);
        $prev_week_start         = date("Y-m-d", strtotime('last ' . $this->start_weekday, strtotime($this_week_start)));
        $next_week_start         = date("Y-m-d", strtotime('next ' . $this->start_weekday, strtotime($this_week_start)));
        $this_week_end           = date("Y-m-d", strtotime($this_week_start . " +6 day"));
        $data['this_week_start'] = $this->customlib->dateformat($this_week_start);
        $data['this_week_end']   = $this->customlib->dateformat($this_week_end);
        $data['prev_week_start'] = $this->customlib->dateformat($prev_week_start);
        $data['next_week_start'] = $this->customlib->dateformat($next_week_start);
        $this->session->set_userdata('top_menu', 'Time_table');
        $staff_id          = $_POST['staff_id'];
        $data['timetable'] = array();
        $days              = $this->customlib->getDaysname();
        $userdata          = $this->customlib->getUserData();
        $role_id           = $userdata["role_id"];
        $condition         = "";
        foreach ($days as $day_key => $day_value) {

            $timetableid = "";
            $concate     = "no";
            if (isset($role_id) && ($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
                $myclasssubjects = $this->subjecttimetable_model->getByStaffClassTeacherandDay($staff_id, $day_key);

                if (!empty($myclasssubjects[0]->timetable_id)) {

                    $timetableid = $myclasssubjects[0]->timetable_id;
                    $concate     = "yes";
                }
            }

            $mysubjects = $this->subjecttimetable_model->getByTeacherSubjectandDay($staff_id, $day_key);
            if (!empty($mysubjects[0]->timetable_id)) {
                if ($concate == 'yes') {
                    $timetableid = $timetableid . "," . $mysubjects[0]->timetable_id;
                } else {
                    $timetableid = $mysubjects[0]->timetable_id;
                }
            }

            if ($timetableid == '') {

                $condition = " and subject_timetable.id in(0) ";
            } else {

                $condition = " and subject_timetable.id in(" . $timetableid . ") ";
            }
            $data['timetable'][$day_key] = $this->subjecttimetable_model->getSyllabussubject($staff_id, $day_key, $condition);
        }

        $data['staff_id'] = $staff_id;
        $this->load->view('admin/syllabus/_get_weekdates', $data);
    }

    public function get_subject_syllabus()
    {
        $data['id']      = $_POST['id'];
        $staff_id        = $_POST['staff_id'];
        $my_role         = $this->customlib->getStaffRole();
        $role            = json_decode($my_role);
        $data['role_id'] = $role->id;

        $data['result'] = $this->syllabus_model->get_subject_syllabus($data, $staff_id);

        $data['result'] = $data['result'][0];

        $this->load->view('admin/syllabus/_get_subject_syllabus', $data);
    }

    public function get_subject_syllabus_without_topic(){

        $data['id']      = $_POST['id'];
        $staff_id        = $_POST['staff_id'];
        $my_role         = $this->customlib->getStaffRole();
        $role            = json_decode($my_role);
        $data['role_id'] = $role->id;

        $data['result'] = $this->syllabus_model->get_subject_syllabus_without_topic($data, $staff_id);

        $data['result'] = $data['result'][0];

        $this->load->view('admin/syllabus/_get_subject_syllabus_without_topic', $data);

    }


    public function markHolidayOrLeave()
    {
    
        $staff_id = $this->input->post('staff_id');
        $date = $this->input->post('date');
        $status = $this->input->post('status');
                
        if (empty($staff_id) || empty($date) || empty($status)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid data provided.']);
            return;
        }
       
        // // Check if the record already exists for this staff and date(code for single staff added as holiday or leave)
        // $existingRecord = $this->db->get_where('staff_holidays_leaves', ['staff_id' => $staff_id, 'date' => $date])->row_array();
        
        // if($existingRecord){
        //     $data = [
        //         'id' => $existingRecord['id'],
        //         'status' => $status,
        //     ];
        // }
        // else{

        //     $data = [
        //         'staff_id' => $staff_id,
        //         'date' => $date,
        //         'status' => $status,
        //         'created_at' => date('Y-m-d H:i:s'),
        //     ];
        // }
        // $result = $this->syllabus_model->addHolidayOrLeave($data);
        
        $all_staff=$this->staff_model->getStaffbyrole(2);

        if($status==="holiday"){

            for($i = 0; $i < count($all_staff) ; $i++){

                $data = [
                    'staff_id' => $all_staff[$i]['id'],
                    'date' => $date,
                    'status' => $status,
                    'created_at' => date('Y-m-d H:i:s'),
                ];

                $result = $this->syllabus_model->addHolidayOrLeave($data);
            }
        }
        else{
            $data = [
                        'staff_id' => $staff_id,
                        'date' => $date,
                        'status' => $status,
                        'created_at' => date('Y-m-d H:i:s'),
                    ];

            $result = $this->syllabus_model->addHolidayOrLeave($data);
        }
             
        echo json_encode(['success' => true]);
   
    }

    public function remove_staff_holiday_or_leave() {
        
        $status = $this->input->post('status');
        $date = $this->input->post('date');
        $staff_id = $this->input->post('staff_id');
    
        if ($status == 'holiday') {
            
            $this->syllabus_model->deleteHoliday($status,$date,null);
            
        } elseif ($status == 'leave') {
           
            $this->syllabus_model->deleteHoliday($status,$date,$staff_id);
            
        }
    
    }
    

    public function delete_subject_syllabus()
    {

        $this->syllabus_model->delete_subject_syllabus($_POST['id']);
    }

    public function delete_subject_syllabus_without_topic(){

        $this->syllabus_model->delete_subject_syllabus_without_topic($_POST['id']);

    }

    public function get_subject_syllabusdata()
    {
        $staff_id = $this->customlib->getStaffID();
        $my_role  = $this->customlib->getStaffRole();
        $role     = json_decode($my_role);

        $subject_syllabus = $this->syllabus_model->get_subject_syllabusdata($_POST['subject_group_subject_id'], date('Y-m-d', strtotime($_POST['new_date'])), $role->id, $staff_id);

        if ($subject_syllabus[0]['total'] > 0) {
            echo $subject_syllabus[0]['id'];
        } else {
            echo 0;
        }
    }

    public function getsubject_syllabus($id)
    {
        $data         = $this->syllabus_model->get_subject_syllabusdatabyid($id);
        $data['date'] = date($this->customlib->getSchoolDateFormat(), strtotime($data['date']));
        echo json_encode($data);
    }


    public function add_extra_syllabus() {
              
        $response = array('status' => 'fail', 'message' => '', 'error' => array());
    
        $extra_time_from = $this->input->post('extra_time_from');
        $extra_time_to = $this->input->post('extra_time_to');
        $extra_date = $this->input->post('extra_date');

        $extra_remarks = $this->input->post('extra_remarks');
        $subject_group_subject_id = $this->input->post('subject_group_subject_id');
        $staff_id = $this->input->post('staff_id');
    
        $this->form_validation->set_rules('extra_remarks', 'Remarks', 'required');
    
        if ($this->form_validation->run() == FALSE) {
            $response['error']['remarks'] = validation_errors();
        } else {
            
            $data = array(
                'session_id'              => $this->sch_current_session,
                'created_by'              => $this->staff_id,
                'created_for'             => $staff_id,
                'date'                    => date('Y-m-d', $this->customlib->datetostrtotime($extra_date)),
                'class_id'                =>$this->input->post('class_id'),
                'section_id'              =>$this->input->post('section_id'),
                'time_from'               => $extra_time_from,
                'time_to'                 => $extra_time_to,
                'sub_topic'               => $extra_remarks,
                                
            );
    
            $result = $this->lessonplan_model->add_extra_syllabus($data);
            
            if ($result) {
                $response['status'] = 'success';
                $response['message'] = 'Extra syllabus added successfully.';
            } else {
                $response['message'] = 'Failed to add extra syllabus.';
            }
        }
    
        echo json_encode($response);
    }
    

    public function add_syllabus()
    {

        $this->form_validation->set_rules('lesson_id', $this->lang->line('lesson'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('topic_id', $this->lang->line('topic'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('date', $this->lang->line('date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('time_from', $this->lang->line('time_from'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('time_to', $this->lang->line('time_to'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('file', $this->lang->line('attachment'), 'callback_handle_upload');
        $this->form_validation->set_rules('lacture_video', $this->lang->line('lacture_video'), 'handle_uploadlacturevideo');
        $this->form_validation->set_rules('desired_attainment_percentage','Desired Attainment Percentage',
            'trim|numeric|greater_than_equal_to[0]|less_than_equal_to[100]'
        );
        if ($this->form_validation->run() == false) {
            $msg = array(
                'lessonid'      => form_error('lesson_id'),
                'topic_id'      => form_error('topic_id'),
                'date'          => form_error('date'),
                'time_from'     => form_error('time_from'),
                'time_to'       => form_error('time_to'),
                'file'          => form_error('file'),
                'lacture_video' => form_error('lacture_video'),
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
           
                $data = array(
                    'session_id'              => $this->sch_current_session,
                    'topic_id'                => $_POST['topic_id'],
                    'date'                    => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date'))),
                    'time_from'               => $_POST['time_from'],
                    'time_to'                 => $_POST['time_to'],
                    'presentation'            => $_POST['presentation'],
                    'sub_topic'               => $_POST['sub_topic'],
                    'teaching_method'         => $_POST['teaching_method'],
                    'teaching_tool'           => $this->input->post('teaching_tool'),
                    'desired_attainment_percentage' => $this->input->post('desired_attainment_percentage'),
                    'general_objectives'      => $_POST['general_objectives'],
                    'previous_knowledge'      => $_POST['previous_knowledge'],
                    'comprehensive_questions' => $_POST['comprehensive_questions'],
                    'lacture_youtube_url'     => $_POST['lacture_youtube_url'],
                    'created_by'              => $this->staff_id,
                    'created_for'             => $_POST['created_for'],
                    'id'                      => $_POST['subject_syllabusid'],
                );
    
                $insert_id = $this->lessonplan_model->add_syllabus($data);

                if (!empty($_POST['outcome_code'])) {

                    foreach ($_POST['outcome_code'] as $key => $code) {

                        if ($code != '' && $_POST['outcome_statement'][$key] != '') {

                            $exists = $this->db
                                ->where('session_id', $this->sch_current_session)
                                ->where('topic_id', $_POST['topic_id'])
                                ->where('outcome_code', $code)
                                ->get('obe_subject_syllabus_outcomes')
                                ->row();

                            if (!$exists) {
                                $obe_data = array(
                                    'session_id'               => $this->sch_current_session,
                                    //'subject_syllabus_id'      => $insert_id,
                                    'topic_id'                 => $_POST['topic_id'],
                                    'outcome_code'             => $code,
                                    'outcome_statement'        => $_POST['outcome_statement'][$key],
                                    'blooms_level'             => $_POST['blooms_level'][$key],
                                    'created_by'                => $this->staff_id,
                                );

                                $this->lessonplan_model->save_obe_outcomes($obe_data);
                            }
                        }
                    }
                }

    
                if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                    $fileInfo = pathinfo($_FILES["file"]["name"]);
                    $img_name = time() . '.' . $fileInfo['extension'];
                    move_uploaded_file($_FILES["file"]["tmp_name"], "./uploads/syllabus_attachment/" . $img_name);
                    $data_img = array('id' => $insert_id, 'attachment' => $img_name);
                    $this->lessonplan_model->update_syllabus($data_img);
                }
                if (isset($_FILES["lacture_video"]) && !empty($_FILES['lacture_video']['name'])) {
                    $fileInfo = pathinfo($_FILES["lacture_video"]["name"]);
                    $img_name = time() . '.' . $fileInfo['extension'];
                    move_uploaded_file($_FILES["lacture_video"]["tmp_name"], "./uploads/syllabus_attachment/lacture_video/" . $img_name);
                    $data_img = array('id' => $insert_id, 'lacture_video' => $img_name);
                    $this->lessonplan_model->update_syllabus($data_img);
                }

            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    public function getTopicOutcomes()
    {
        $topic_id = $this->input->post('topic_id');

        if (!$topic_id) {
            echo json_encode([]);
            return;
        }

        $outcomes = $this->lessonplan_model->getTopicOutcomes(
            $this->sch_current_session,
            $topic_id
        );

        echo json_encode($outcomes);
    }


    public function download($doc)
    {
        $this->load->helper('download');
        $filepath = "./uploads/syllabus_attachment/" . $this->uri->segment(4);
        $data     = file_get_contents($filepath);
        $name     = $this->uri->segment(4);
        force_download($name, $data);
    }

    public function lacture_video_download($doc)
    {
        $this->load->helper('download');
        $filepath = "./uploads/syllabus_attachment/lacture_video/" . $this->uri->segment(4);

        $data = file_get_contents($filepath);
        $name = $this->uri->segment(4);
        force_download($name, $data);
    }

    public function handle_uploadlacturevideo()
    {

        $image_validate = $this->config->item('file_validate');
        $result         = $this->filetype_model->get();
        if (isset($_FILES["lacture_video"]) && !empty($_FILES['lacture_video']['name'])) {

            $file_type = $_FILES["lacture_video"]['type'];
            $file_size = $_FILES["lacture_video"]["size"];
            $file_name = $_FILES["lacture_video"]["name"];

            $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->file_extension)));
            $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->file_mime)));
            $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mtype = finfo_file($finfo, $_FILES['lacture_video']['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mtype, $allowed_mime_type)) {
                $this->form_validation->set_message('handle_uploadlacturevideo', 'File Type Not Allowed');
                return false;
            }

            if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                $this->form_validation->set_message('handle_uploadlacturevideo', 'Extension Not Allowed');
                return false;
            }
            if ($file_size > $result->file_size) {
                $this->form_validation->set_message('handle_uploadlacturevideo', $this->lang->line('file_size_shoud_be_less_than') . number_format($image_validate['upload_size'] / 1048576, 2) . " MB");
                return false;
            }

            return true;
        } else {
        }
        return true;
    }

    public function handle_upload()
    {

        $image_validate = $this->config->item('file_validate');
        $result         = $this->filetype_model->get();
        if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {

            $file_type = $_FILES["file"]['type'];
            $file_size = $_FILES["file"]["size"];
            $file_name = $_FILES["file"]["name"];

            $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->file_extension)));
            $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->file_mime)));
            $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mtype = finfo_file($finfo, $_FILES['file']['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mtype, $allowed_mime_type)) {
                $this->form_validation->set_message('handle_upload', 'File Type Not Allowed');
                return false;
            }

            if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                $this->form_validation->set_message('handle_upload', 'Extension Not Allowed');
                return false;
            }
            if ($file_size > $result->file_size) {
                $this->form_validation->set_message('handle_upload', $this->lang->line('file_size_shoud_be_less_than') . number_format($image_validate['upload_size'] / 1048576, 2) . " MB");
                return false;
            }

            return true;
        } else {
        }
        return true;
    }

    public function status()
    {

        if (!($this->rbac->hasPrivilege('manage_syllabus_status', 'can_view'))) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'lessonplan');
        $this->session->set_userdata('sub_menu', 'admin/lessonplan');
        $data                     = array();
        $data['no_record']        = '0';
        $class                    = $this->class_model->get();
        $data['classlist']        = $class;
        $data['class_id']         = "";
        $data['section_id']       = "";
        $data['subject_group_id'] = "";
        $data['subject_id']       = "";
        $data['subject_name']     = "";
        $data['lessons']          = array();
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('subject_group_id', $this->lang->line('subject') . " " . $this->lang->line('group'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('subject_id', $this->lang->line('subject'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
        } else {
            $data['class_id']               = $_POST['class_id'];
            $data['section_id']             = $_POST['section_id'];
            $data['subject_group_id']       = $_POST['subject_group_id'];
            $data['subject_id']             = $_POST['subject_id'];
            $subject_details                = $this->lessonplan_model->get_subjectNameBySubjectGroupSubjectId($_POST['subject_id']);
            $subject_group_class_sectionsId = $this->lessonplan_model->getsubject_group_class_sectionsId($_POST['class_id'], $_POST['section_id'], $_POST['subject_group_id']);
            if ($subject_details['code'] == '') {
                $data['subject_name'] = $subject_details['name'];
            } else {
                $data['subject_name'] = $subject_details['name'] . " (" . $subject_details['code'] . ")";
            }
            $lessonlist        = $this->lessonplan_model->getlessonBysubjectid($_POST['subject_id'], $subject_group_class_sectionsId['id']);
            $data['no_record'] = '1';
            foreach ($lessonlist as $key => $value) {
                $data['no_record']             = '2';
                $data['lessons'][$value['id']] = $value;
                $topics                        = $this->lessonplan_model->gettopicBylessonid($value['id'], $this->sch_current_session);
                foreach ($topics as $topic_key => $topic_value) {
                    $data['lessons'][$value['id']]['topic'][] = $topic_value;
                }
            }

            // echo "<pre>";
            // print_r($data);die;
        }

        $data['status'] = array('1' => '<span class="label " style="background:#0e0e0e">' . $this->lang->line('complete') . '</span>', '0' => '<span class="label " style="background:#b3b3b3">' . $this->lang->line('incomplete') . '</span>');
        $this->load->view('layout/header');
        $this->load->view('admin/lessonplan/index', $data);
        $this->load->view('layout/footer');
    }

    public function getTopicAttainment()
    {
        $topic_id = $this->input->post('topic_id');

        $data = $this->lessonplan_model
            ->getTopicAttainmentById($topic_id, $this->sch_current_session);

        echo json_encode(['status'=>'success','data'=> $data]);
    }

    public function saveTopicAttainment()
    {

        $this->form_validation->set_rules(
            'skill_verb',
            'Skill Verb',
            'required|in_list[Remember,Understand,Apply,Analyze,Evaluate,Create]'
        );

        $data = [
            'session_id' => $this->sch_current_session,
            'topic_id'   => $this->input->post('topic_id'),
            'teaching_tool' => $this->input->post('teaching_tool'),
            'desired_attainment_percentage' => $this->input->post('desired_attainment_percentage'),
            'assessment_tools' => $this->input->post('assessment_tools'),
            'skill_verb' => $this->input->post('skill_verb'),
            'student_attainment_percentage' => $this->input->post('student_attainment_percentage'),
            'remedial_action' => $this->input->post('remedial_action')!==null ? $this->input->post('remedial_action') : '',
            'created_by' => $this->staff_id,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $this->lessonplan_model->updateTopicAttainment($this->input->post('topic_id'), $data);

        echo json_encode(['status'=>'success']);
    }


}
