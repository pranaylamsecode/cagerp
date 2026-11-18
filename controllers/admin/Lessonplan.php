<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Lessonplan extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library('Customlib');
        $this->sch_current_session = $this->setting_model->getCurrentSession();
        $this->staff_id            = $this->customlib->getStaffID();
        $this->load->library("datatables");
    }

    public function index()
    {
        if (!($this->rbac->hasPrivilege('manage_syllabus_status', 'can_view'))) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'lessonplan');
        $this->session->set_userdata('sub_menu', 'admin/lessonplan');
        $data                     = array();
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
            $data['subject_name']           = $subject_details['name'] . " (" . $subject_details['code'] . ")";
            $lessonlist                     = $this->lessonplan_model->getlessonBysubjectid($_POST['subject_id'], $subject_group_class_sectionsId['id']);

            foreach ($lessonlist as $key => $value) {

                $data['lessons'][$value['id']] = $value;
                $topics                        = $this->lessonplan_model->gettopicBylessonid($value['id'], $this->sch_current_session);
                foreach ($topics as $topic_key => $topic_value) {
                    $data['lessons'][$value['id']]['topic'][] = $topic_value;
                }
            }
        }

        $data['status'] = array('1' => '<span class="label " style="background:#0e0e0e">' . $this->lang->line('complete') . '</span>', '0' => '<span class="label " style="background:#b3b3b3">' . $this->lang->line('incomplete') . '</span>');
        $this->load->view('layout/header');
        $this->load->view('admin/lessonplan/index', $data);
        $this->load->view('layout/footer');
    }

    public function lesson()
    {
        if (!($this->rbac->hasPrivilege('lesson', 'can_view'))) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'lessonplan');
        $this->session->set_userdata('sub_menu', 'admin/lessonplan/lesson');

        $current_session  = $this->setting_model->getCurrentSession();
        $copylesson = $this->db
            ->where('session_id', $current_session)
            ->count_all_results('lesson');

        $data['copylesson'] = $copylesson;

        $class = $this->class_model->get();

        $data['classlist'] = $class;
        foreach ($class as $class_key => $class_value) {
            $data['class_array'][] = $class_value['id'];
        }
        $data['monthlist'] = $this->customlib->getShortFormMonthDropdown();
        $carray                   = array();
        $data['class_id']         = "";
        $data['section_id']       = "";
        $data['subject_group_id'] = "";
        $data['subject_id']       = "";
        $userdata                 = $this->customlib->getUserData();
        $role_id                  = $userdata["role_id"];
        $staff_id                 = $userdata["id"];
        $concate                  = "no";
        $condition                = "";
        $where_in                 = array();
        if (isset($role_id) && ($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
            $myclasssubjects = $this->subjecttimetable_model->getByStaffClassTeachersubjects($staff_id);

            if (!empty($myclasssubjects[0]->subject_group_subject_id)) {

                $timetableid = $myclasssubjects[0]->subject_group_subject_id;
                $concate     = "yes";
            }

            $mysubjects = $this->subjecttimetable_model->getByTeacherSubjects($staff_id);
            if (!empty($mysubjects[0]->subject_group_subject_id)) {
                if ($concate == 'yes') {
                    $timetableid = $timetableid . "," . $mysubjects[0]->subject_group_subject_id;
                } else {
                    $timetableid = $mysubjects[0]->subject_group_subject_id;
                }
            }

            if ($timetableid == '') {

                $condition = " and subject_timetable.id in(0) ";
            } else {

                $condition = " and subject_timetable.id in(" . $timetableid . ") ";
            }
            $where_in = explode(',', $timetableid);
        }

        $this->load->view('layout/header');
        $this->load->view('admin/lessonplan/lesson', $data);
        $this->load->view('layout/footer');
    }

    public function migrateLessonTopic()
    {
        if (!$this->rbac->hasPrivilege('lesson', 'can_add')) {
            access_denied();
        }

        $current_session  = $this->setting_model->getCurrentSession();
        $previous_session = $current_session - 1;

        $subject_group_exist = $this->db
        ->where('session_id', $current_session)
        ->count_all_results('subject_groups');

        if($subject_group_exist <= 0){
            $this->session->set_flashdata(
                'msg',
                '<div class="alert alert-danger">Please migrate Subject Groups First.</div>'
            );
            redirect('admin/lessonplan/lesson');
        }

        // PRE-CHECK: lessons already exist?
        $exists = $this->db
            ->where('session_id', $current_session)
            ->count_all_results('lesson');

        if ($exists > 0) {
            $this->session->set_flashdata(
                'msg',
                '<div class="alert alert-warning">Lessons already exist for current session.</div>'
            );
            redirect('admin/lessonplan/lesson');
        }
       
        $result = $this->lessonplan_model
            ->migrateLessonAndTopic($previous_session, $current_session);

        if ($result === true) {
            $this->session->set_flashdata(
                'msg',
                '<div class="alert alert-success">Lesson & Topic migrated successfully.</div>'
            );
        } else {
            $this->session->set_flashdata(
                'msg',
                '<div class="alert alert-danger">Migration failed. No data was copied.</div>'
            );
        }

        redirect('admin/lessonplan/lesson');
    }


    public function createlesson()
    {

        $data['title'] = 'Add Library';
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('subject_group_id', $this->lang->line('subject') . " " . $this->lang->line('group'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('subject_id', $this->lang->line('subject'), 'trim|required|xss_clean');

        $validate = 1;
        if (!empty($_POST['lessons'])) {
            foreach ($_POST['lessons'] as $lessonkey => $lessonvalue) {
                if ($lessonvalue == '') {
                    $validate = 0;
                }
            }
        } else {
            $validate = 0;
        }

        if ($this->form_validation->run() == false) {
            $msg = array(
                'class_id'         => form_error('class_id'),
                'section_id'       => form_error('section_id'),
                'subject_group_id' => form_error('subject_group_id'),
                'subject_id'       => form_error('subject_id'),
                // 'from_month'       => form_error('from_month'),
                // 'to_month'         => form_error('to_month'),
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } elseif ($validate == 0) {
            $msg   = array('lesson' => $this->lang->line('lesson') . " " . $this->lang->line('name') . " field is required");
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {

            $class_id = $_POST['class_id'];
            $subject_id = $_POST['subject_id'];
            if ($_POST['section_id'] === '0') {

                $sections = $this->subjectgroup_model->getGroupByClassandSection($class_id, '0');

                foreach ($sections as $section) {
                    $subject_group_class_sectionsId = $this->lessonplan_model->getsubject_group_class_sectionsId($class_id, $section['section_id'], $section['subject_group_id']);
                    $subject_group_subjects = $this->subjectgroup_model->getSubjectGroupsubjects($section['subject_group_id'], $subject_id);

                    foreach ($_POST['lessons'] as $value) {
                        $data = array(
                            'subject_group_subject_id'        => $subject_group_subjects,
                            'name'                            => $value,
                            'subject_group_class_sections_id' => $subject_group_class_sectionsId['id'],
                            'session_id'                      => $this->sch_current_session,
                            // 'from_month'                      => $_POST['from_month'],
                            // 'to_month'                        => $_POST['to_month'],


                        );

                        $this->lessonplan_model->add_lesson($data);
                    }
                }
            } else {
                $subject_group_class_sectionsId = $this->lessonplan_model->getsubject_group_class_sectionsId($_POST['class_id'], $_POST['section_id'], $_POST['subject_group_id']);
                foreach ($_POST['lessons'] as $key => $value) {
                    $data = array(
                        'subject_group_subject_id'        => $_POST['subject_id'],
                        'name'                            => $value,
                        'subject_group_class_sections_id' => $subject_group_class_sectionsId['id'],
                        'session_id'                      => $this->sch_current_session,
                    );

                    $this->lessonplan_model->add_lesson($data);
                }
            }

            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    public function deletelesson($id)
    {
        if (!($this->rbac->hasPrivilege('lesson', 'can_delete'))) {
            access_denied();
        }
        $this->lessonplan_model->deletelesson($id, $this->sch_current_session);
        redirect('admin/lessonplan/lesson');
    }

    public function deletelessonbulk($id, $subject_group_subject_id)
    {
        if (!($this->rbac->hasPrivilege('lesson', 'can_delete'))) {
            access_denied();
        }
        $this->lessonplan_model->deletelessonbulk($id, $this->sch_current_session, $subject_group_subject_id);
        $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('delete_message'));
        echo json_encode($array);
    }

    public function editlesson($id, $subject_group_subject_id)
    {
        if (!($this->rbac->hasPrivilege('lesson', 'can_edit'))) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'lessonplan');
        $this->session->set_userdata('sub_menu', 'admin/lessonplan/lesson');
        $class             = $this->class_model->get();
        $data['classlist'] = $class;

        $data['monthlist'] = $this->customlib->getShortFormMonthDropdown();

        foreach ($class as $class_key => $class_value) {
            $data['class_array'][] = $class_value['id'];
        }

        $carray = array();
        $result = $this->lessonplan_model->get($this->sch_current_session, '');
        if (!empty($result)) {
            foreach ($result as $key => $value) {
                $lesson = $this->lessonplan_model->getlesson($value["subject_group_subject_id"], $value["subject_group_class_sections_id"], $this->sch_current_session);
                if ($lesson != '') {
                    $lessonname[$key] = $lesson;
                }
            }
        }
        $data['result'] = $result;
        if (!empty($lessonname)) {
            $data['lessonname'] = $lessonname;
        }

        $editresult = $this->lessonplan_model->get($this->sch_current_session, $id, $subject_group_subject_id);
        $editlesson = $this->lessonplan_model->getlesson($editresult["subject_group_subject_id"], $editresult["subject_group_class_sections_id"], $this->sch_current_session);

        $data['editlessonname']                 = $editlesson;
        $data['class_id']                       = $editresult['classid'];
        $data['section_id']                     = $editresult['sectionid'];
        $data['subject_group_id']               = $editresult['subjectgroupsid'];
        $data['subject_id']                     = $editresult['subjectid'];
        $data['lesson_subject_group_subjectid'] = $editresult['subject_group_subject_id'];

        $data['from_month_selected']            = $editresult['from_month'];
        $data['to_month_selected']              = $editresult['to_month'];

        $this->load->view('layout/header');
        $this->load->view('admin/lessonplan/editlesson', $data);
        $this->load->view('layout/footer');
    }

    public function updatelesson()
    {

        $can_edit      = 1;
        $data['title'] = 'Add Library';
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('subject_group_id', $this->lang->line('subject') . " " . $this->lang->line('group'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('subject_id', $this->lang->line('subject'), 'trim|required|xss_clean');

        // $this->form_validation->set_rules('from_month', 'from_month', 'trim|required|xss_clean');
        // $this->form_validation->set_rules('to_month', 'to_month', 'trim|required|xss_clean');


        $subject_group_class_sectionsId = $this->lessonplan_model->getsubject_group_class_sectionsId($_POST['class_id'], $_POST['section_id'], $_POST['subject_group_id']);
        $all_lessons                    = $this->lessonplan_model->getlessonBysubjectid($_POST['subject_id'], $subject_group_class_sectionsId['id']);
        $userdata                       = $this->customlib->getUserData();

        $role_id = $userdata["role_id"];
        if (isset($role_id) && ($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
            $class_section = $this->lessonplan_model->ifclassteacher($_POST['class_id'], $_POST['section_id'], $userdata['id'], $_POST['subject_group_id'], $_POST['subject_id']);

            $can_edit = $class_section;
        }

        $validate = 1;
        foreach ($all_lessons as $lessonkey => $lessonvalue) {
            if (!empty($_POST['lesson_delete'])) {
                if (in_array($lessonvalue['id'], $_POST['lesson_delete'])) {
                    $validate = 1;
                }
            } elseif ($_POST['lessons_' . $lessonvalue['id']] == '') {
                $validate = 0;
            }
        }

        if (isset($_POST['lessons'])) {
            foreach ($_POST['lessons'] as $lessonkey => $lessonvalue) {
                if ($lessonvalue == '') {
                    $validate = 0;
                }
            }
        }
        if (!empty($_POST['lesson_delete'])) {
            if (count($all_lessons) == count($_POST['lesson_delete'])) {
                if (empty($_POST['topic'])) {
                    $validate = 0;
                }
            }
        }

        if ($this->form_validation->run() == false) {
            $msg = array(
                'class_id'         => form_error('class_id'),
                'section_id'       => form_error('section_id'),
                'subject_group_id' => form_error('subject_group_id'),
                'subject_id'       => form_error('subject_id'),
                // 'from_month'       => form_error('from_month'),
                // 'to_month'         => form_error('to_month'),
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } elseif ($validate == 0) {
            $msg   = array('lesson' => $this->lang->line('lesson') . " " . $this->lang->line('name') . " field is required");
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } elseif ($can_edit == 0) {
            $msg   = array('lesson' => $this->lang->line('you_are_not_authorised_to_update_lessons'));
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {

        //============uncomment this to apply changes to all sections when 'All Sections' is selected================

            // $apply_all_sections = true;

            // $class_id   = $_POST['class_id'];
            // $subject_id = $_POST['subject_id'];

            // if ($apply_all_sections) {
            //     $sections = $this->subjectgroup_model->getGroupByClassandSection($class_id, '0');
            // } else {
            //     $sections = [
            //         [
            //             'section_id' => $_POST['section_id'],
            //             'subject_group_id' => $_POST['subject_group_id']
            //         ]
            //     ];
            // }

            //var_dump($sections);die;


            if (isset($_POST['lesson_delete'])) {
                foreach ($_POST['lesson_delete'] as $delete_key => $delete_value) {
                    $this->lessonplan_model->deletelesson($delete_value, $this->sch_current_session);
                }
            }

            // if (isset($_POST['lesson_delete'])) {
            //     foreach ($sections as $section) {

            //         $sgcs = $this->lessonplan_model
            //             ->getsubject_group_class_sectionsId(
            //                 $class_id,
            //                 $section['section_id'],
            //                 $section['subject_group_id']
            //             );

            //         $lessons = $this->lessonplan_model
            //             ->getlessonBysubjectid($subject_id, $sgcs['id']);

            //         foreach ($lessons as $lesson) {
            //             if (in_array($lesson['id'], $_POST['lesson_delete'])) {
            //                 $this->lessonplan_model
            //                     ->deletelesson($lesson['id'], $this->sch_current_session);
            //             }
            //         }
            //     }
            // }


            $subject_group_class_sectionsId = $this->lessonplan_model->getsubject_group_class_sectionsId($_POST['class_id'], $_POST['section_id'], $_POST['subject_group_id']);
            $all_lessons                    = $this->lessonplan_model->getlessonBysubjectid($_POST['subject_id'], $subject_group_class_sectionsId['id']);

            foreach ($all_lessons as $lessonkey => $lessonvalue) {
                if (isset($_POST['lessons_' . $lessonvalue['id']])) {
                    $data = array(
                        'subject_group_subject_id'        => $_POST['subject_id'],
                        'name'                            => $_POST['lessons_' . $lessonvalue['id']],
                        'subject_group_class_sections_id' => $subject_group_class_sectionsId['id'],
                        'session_id'                      => $this->sch_current_session,
                        // 'from_month'                      => $_POST['from_month'],
                        // 'to_month'                        => $_POST['to_month'],
                        'id'                              => $lessonvalue['id'],
                    );

                    $this->lessonplan_model->add_lesson($data);
                }
            }

            // foreach ($sections as $section) {

            //     $sgcs = $this->lessonplan_model
            //         ->getsubject_group_class_sectionsId(
            //             $class_id,
            //             $section['section_id'],
            //             $section['subject_group_id']
            //         );

            //     $section_lessons = $this->lessonplan_model
            //         ->getlessonBysubjectid($subject_id, $sgcs['id']);

            //     foreach ($section_lessons as $lesson) {

            //         if (isset($_POST['lessons_' . $lesson['id']])) {

            //             $data = [
            //                 'id'                              => $lesson['id'],
            //                 'name'                            => $_POST['lessons_' . $lesson['id']],
            //                 'subject_group_subject_id'        => $subject_id,
            //                 'subject_group_class_sections_id' => $sgcs['id'],
            //                 'session_id'                      => $this->sch_current_session
            //             ];

            //             $this->lessonplan_model->add_lesson($data);
            //         }
            //     }
            // }


            if (isset($_POST['lessons'])) {
                foreach ($_POST['lessons'] as $key => $value) {
                    $data = array(
                        'subject_group_subject_id'        => $_POST['subject_id'],
                        'name'                            => $value,
                        'subject_group_class_sections_id' => $subject_group_class_sectionsId['id'],
                        'session_id'                      => $this->sch_current_session,
                        // 'from_month'                      => $_POST['from_month'],
                        // 'to_month'                        => $_POST['to_month'],
                    );

                    $this->lessonplan_model->add_lesson($data);
                }
            }


            // if (isset($_POST['lessons'])) {
            //     foreach ($sections as $section) {

            //         $sgcs = $this->lessonplan_model
            //             ->getsubject_group_class_sectionsId(
            //                 $class_id,
            //                 $section['section_id'],
            //                 $section['subject_group_id']
            //             );

            //         foreach ($_POST['lessons'] as $value) {

            //             $data = [
            //                 'subject_group_subject_id'        => $subject_id,
            //                 'name'                            => $value,
            //                 'subject_group_class_sections_id' => $sgcs['id'],
            //                 'session_id'                      => $this->sch_current_session
            //             ];

            //             $this->lessonplan_model->add_lesson($data);
            //         }
            //     }
            // }

            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    //==================================Topic Start===============================
    public function topic()
    {
        if (!($this->rbac->hasPrivilege('topic', 'can_view'))) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'lessonplan');
        $this->session->set_userdata('sub_menu', 'admin/lessonplan/topic');
        $class             = $this->class_model->get();
        $data['classlist'] = $class;
        foreach ($class as $class_key => $class_value) {
            $data['class_array'][] = $class_value['id'];
        }
        $data['monthlist'] = $this->customlib->getShortFormMonthDropdown();

        $carray                   = array();
        $data['class_id']         = "";
        $data['section_id']       = "";
        $data['subject_group_id'] = "";
        $data['subject_id']       = "";
        $this->load->view('layout/header');
        $this->load->view('admin/lessonplan/topic', $data);
        $this->load->view('layout/footer');
    }

    public function getlessonBysubjectid($sub_id)
    {
        $subject_group_class_sectionsId = $this->lessonplan_model->getsubject_group_class_sectionsId($_POST['class_id'], $_POST['section_id'], $_POST['subject_group_id']);
        $data                           = $this->lessonplan_model->getlessonBysubjectid($sub_id, $subject_group_class_sectionsId['id']);

        echo json_encode($data);
    }

    public function getlessonBylessonid($lesson_id)
    {
        $data = $this->lessonplan_model->getlessonBylessonid($lesson_id);

        echo json_encode($data);
    }

    public function getlessonBysubjectidedit($sub_id)
    {
        $subject_group_class_sections_id = $_POST['subject_group_class_sections_id'];
        $data                            = $this->lessonplan_model->getlessonBysubjectidedit($sub_id, $subject_group_class_sections_id);

        echo json_encode($data);
    }

    //===========================Don't uncomment this (old topic Import method)===============================
    // public function importtopic()
    // {
    //     // last_id=942
    //     //359 records in table
    //     $topiclist = $this->lessonplan_model->gettopic($this->sch_current_session, '');

    //     foreach ($topiclist as $topic) {
    //         $topic_name = $topic['name'];
    //         $subject_group_id = $topic['subjectgroupsid'];
    //         $subject_group_subject_id = $topic['subject_group_subject_id'];
    //         $subject_group_class_sections_id = $topic['subject_group_class_sections_id'];
    //         $lessonname = $topic['lessonname'];

    //         $get_data = $this->lessonplan_model->get_subject_group_class_sections_id_new($subject_group_id, $subject_group_class_sections_id);
    //         $id_new = $get_data['id'];
    //         // print_r($id_new);

    //         $lesson_id = $this->lessonplan_model->getlessonBysubjectgroupsubjectid_name_subjectgroupclasssectionsId($subject_group_subject_id, $lessonname, $id_new);
    //         // print_r($lesson_id['id']);

    //         $data = array(
    //             'lesson_id'  => $lesson_id['id'],
    //             'name'       => $topic_name,
    //             'session_id' => $this->sch_current_session,
    //         );
    //         $this->lessonplan_model->add_topic($data);
    //     }
    //     echo "Successful";
    //     // echo "<pre>";
    //     // print_r($topiclist);
    //     
    // }

    public function createtopic()
    {
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('subject_group_id', $this->lang->line('subject') . " " . $this->lang->line('group'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('subject_id', $this->lang->line('subject'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('lesson_id', $this->lang->line('lesson'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('from_month', 'from_month', 'trim|required|xss_clean');
        $this->form_validation->set_rules('to_month', 'to_month', 'trim|required|xss_clean');
        $this->form_validation->set_rules(
            'desired_attainment_percentage',
            'Desired Attainment Percentage',
            'trim|numeric|greater_than_equal_to[0]|less_than_equal_to[100]'
        );
        $validate = 1;
        if (!empty($_POST['topic'])) {
            foreach ($_POST['topic'] as $topickey => $topicvalue) {
                if ($topicvalue == '') {
                    $validate = 0;
                }
            }
        } else {
            $validate = 0;
        }
        if ($this->form_validation->run() == false) {

            $msg = array(
                'class_id'         => form_error('class_id'),
                'section_id'       => form_error('section_id'),
                'subject_group_id' => form_error('subject_group_id'),
                'subject_id'       => form_error('subject_id'),
                'lesson_id'        => form_error('lesson_id'),
                'from_month'       => form_error('from_month'),
                'to_month'         => form_error('to_month'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } elseif ($validate == 0) {
            $msg   = array('topic' => $this->lang->line('topic') . " " . $this->lang->line('name') . " field is required");
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {

            $class_id = $_POST['class_id'];
            $subject_id = $_POST['subject_id'];
            $subject_group_id = $_POST['subject_group_id'];
            $lesson = $this->lessonplan_model->getlessonBylessonid($_POST['lesson_id']);


            if ($_POST['section_id'] === '0') {
                $sections = $this->subjectgroup_model->getGroupByClassandSection($class_id, '0');
                foreach ($sections as $section) {
                    $subject_group_class_sectionsId = $this->lessonplan_model->getsubject_group_class_sectionsId($class_id, $section['section_id'], $section['subject_group_id']);
                    // $data                           = $this->lessonplan_model->getlessonBysubjectid($subject_id, $subject_group_class_sectionsId['id']);

                    $lesson_id = $this->lessonplan_model->getlessonBysubjectgroupsubjectid_name_subjectgroupclasssectionsId($lesson[0]['subject_group_subject_id'], $lesson[0]['name'], $subject_group_class_sectionsId['id']);

                    foreach ($_POST['topic'] as $key => $value) {
                        $data = array(
                            'lesson_id'  => $lesson_id['id'],
                            'name'       => $value,
                            'session_id' => $this->sch_current_session,
                            'from_month'                      => $_POST['from_month'],
                            'to_month'                        => $_POST['to_month'],
                        );
                        $insert_id = $this->lessonplan_model->add_topic($data);

                        $data1 = [
                                    'session_id' => $this->sch_current_session,
                                    'topic_id'  => $insert_id,
                                    'teaching_tool' => $this->input->post('teaching_tool'),
                                    'desired_attainment_percentage' =>
                                        $this->input->post('desired_attainment_percentage')
                                ];

                        $this->lessonplan_model->updateTopicAttainment($insert_id , $data1);

                    }
                }
            } else {
                foreach ($_POST['topic'] as $key => $value) {
                    $data = array(
                        'lesson_id'  => $_POST['lesson_id'],
                        'name'       => $value,
                        'session_id' => $this->sch_current_session,
                    );
                    $this->lessonplan_model->add_topic($data);
                }
            }


            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    public function deletetopicbulk($id)
    {
        if (!($this->rbac->hasPrivilege('topic', 'can_delete'))) {
            access_denied();
        }
        $this->lessonplan_model->deletetopicbulk($id, $this->sch_current_session);
        $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('delete_message'));
        echo json_encode($array);
    }

    public function edittopic($id)
    {
        if (!($this->rbac->hasPrivilege('topic', 'can_edit'))) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'lessonplan');
        $this->session->set_userdata('sub_menu', 'admin/lessonplan/topic');
        $class             = $this->class_model->get();
        $data['classlist'] = $class;
        foreach ($class as $class_key => $class_value) {
            $data['class_array'][] = $class_value['id'];
        }

        $data['monthlist'] = $this->customlib->getShortFormMonthDropdown();

        $carray = array();

        $result = $this->lessonplan_model->gettopic($this->sch_current_session, '');

        if (!empty($result)) {
            foreach ($result as $key => $value) {
                $topic             = $this->lessonplan_model->gettopicBylessonid($value["lesson_id"], $this->sch_current_session);
                $topicresult[$key] = $topic;
            }
        }

        $data['result'] = $result;
        if (!empty($topicresult)) {
            $data['topicresult'] = $topicresult;
        }

        $editresult                              = $this->lessonplan_model->gettopic($this->sch_current_session, $id);
        $edittopic                               = $this->lessonplan_model->gettopicBylessonid($editresult["lesson_id"], $this->sch_current_session);
        $data['lesson_id']                       = $editresult["lesson_id"];
        $data['topic_lesson_id']                 = $id;
        $data['edittopicname']                   = $edittopic;
        $data['class_id']                        = $editresult['classid'];
        $data['section_id']                      = $editresult['sectionid'];
        $data['subject_group_id']                = $editresult['subjectgroupsid'];
        $data['subject_id']                      = $editresult['subjectid'];
        $data['lesson_subject_group_subjectid']  = $editresult['subject_group_subject_id'];
        $data['subject_group_class_sections_id'] = $editresult['subject_group_class_sections_id'];

        $data['from_month_selected']            = $editresult['from_month'];
        $data['to_month_selected']              = $editresult['to_month'];
        
        $this->load->view('layout/header');
        $this->load->view('admin/lessonplan/edittopic', $data);
        $this->load->view('layout/footer');
    }

    public function updatetopic()
    {

        $can_edit = 1;
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('subject_group_id', $this->lang->line('subject_group'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('subject_id', $this->lang->line('subject'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('lesson_id', $this->lang->line('lesson'), 'trim|required|xss_clean');

        $this->form_validation->set_rules('from_month', 'from_month', 'trim|required|xss_clean');
        $this->form_validation->set_rules('to_month', 'to_month', 'trim|required|xss_clean');
        
        
        $validate = 1;

        $alltopic = $this->lessonplan_model->gettopicBylessonid($_POST['lesson_id'], $this->sch_current_session);
        $userdata = $this->customlib->getUserData();
        $role_id  = $userdata["role_id"];
        if (isset($role_id) && ($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
            $class_section = $this->lessonplan_model->ifclassteacher($_POST['class_id'], $_POST['section_id'], $this->staff_id, $_POST['subject_group_id'], $_POST['subject_id']);

            $can_edit = $class_section;
        }

        foreach ($alltopic as $topickey => $topicvalue) {
            if (!empty($_POST['topic_delete'])) {
                if (in_array($topicvalue['id'], $_POST['topic_delete'])) {
                    $validate = 1;
                }
            } elseif ($_POST['topic_' . $topicvalue['id']] == '') {
                $validate = 0;
            }
        }

        if (!empty($_POST['topic'])) {
            foreach ($_POST['topic'] as $topickey => $topicvalue) {
                if ($topicvalue == '') {
                    $validate = 0;
                }
            }
        }

        if (!empty($_POST['topic_delete'])) {
            if (count($alltopic) == count($_POST['topic_delete'])) {
                if (empty($_POST['topic'])) {
                    $validate = 0;
                }
            }
        }

        if ($this->form_validation->run() == false) {
            $msg = array(
                'class_id'         => form_error('class_id'),
                'section_id'       => form_error('section_id'),
                'subject_group_id' => form_error('subject_group_id'),
                'subject_id'       => form_error('subject_id'),
                'lesson_id'        => form_error('lesson_id'),
                'from_month'       => form_error('from_month'),
                'to_month'         => form_error('to_month'),
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } elseif ($validate == 0) {
            $msg   = array('topic' => $this->lang->line('topic') . " " . $this->lang->line('name') . " field is required");
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } elseif ($can_edit == 0) {
            $msg   = array('topic' => $this->lang->line('you_are_not_authorised_to_update_topics'));
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {

            if (isset($_POST['topic_delete'])) {
                foreach ($_POST['topic_delete'] as $delete_key => $delete_value) {
                    $this->lessonplan_model->deletetopic($delete_value, $this->sch_current_session);
                }
            }

            $alltopic = $this->lessonplan_model->gettopicBylessonid($_POST['lesson_id'], $this->sch_current_session);

            foreach ($alltopic as $topickey => $topicvalue) {
                if (isset($_POST['topic_' . $topicvalue['id']])) {
                    $data = array(
                        'lesson_id'  => $_POST['lesson_id'],
                        'name'       => $_POST['topic_' . $topicvalue['id']],
                        'session_id' => $this->sch_current_session,
                        'from_month'                      => $_POST['from_month'],
                        'to_month'                        => $_POST['to_month'],
                        'id'         => $topicvalue['id'],
                    );

                    $this->lessonplan_model->add_topic($data);
                }
            }
            if (isset($_POST['topic'])) {
                foreach ($_POST['topic'] as $key => $value) {
                    $data = array(
                        'lesson_id'  => $_POST['lesson_id'],
                        'name'       => $value,
                        'session_id' => $this->sch_current_session,
                        'from_month'                      => $_POST['from_month'],
                        'to_month'                        => $_POST['to_month'],
                    );

                    $this->lessonplan_model->add_topic($data);
                }
            }
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    public function changeTopicStatus()
    {
        $id     = $this->input->post('id');
        $status = $this->input->post('status');

        $data   = array('id' => $id, 'complete_date' => '0000-00-00', 'status' => $status);
        $result = $this->lessonplan_model->changeTopicStatus($data);

        if ($result) {
            $response = array('status' => 1, 'msg' => $this->lang->line('success_message'));
            echo json_encode($response);
        }
    }

    public function topic_completedate()
    {

        $this->form_validation->set_rules('date', $this->lang->line('date'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {

            $msg = array(
                'date' => form_error('date'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {

            $data = array(
                'complete_date' => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date'))),
                'status'        => 1,
                'id'            => $_POST['id'],
            );

            $this->lessonplan_model->changeTopicStatus($data);

            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    //==========================================Syllabus-Assign=========================

    public function gettopicBylessonid($lessonid)
    {
        $data = $this->lessonplan_model->gettopicBylessonid($lessonid, $this->sch_current_session);

        echo json_encode($data);
    }

    public function get_topicbyid()
    {
        $this->lessonplan_model->gettopic($this->sch_current_session, '');
    }

    public function gettopiclist()
    {

        $class             = $this->class_model->get();
        $data['classlist'] = $class;
        foreach ($class as $class_key => $class_value) {

            $class_array[] = $class_value['id'];
        }
        $carray                   = array();
        $data['class_id']         = "";
        $data['section_id']       = "";
        $data['subject_group_id'] = "";
        $data['subject_id']       = "";
        $result                   = $this->lessonplan_model->gettopiclist($this->sch_current_session);
        $m                        = json_decode($result);
        $currency_symbol          = $this->customlib->getSchoolCurrencyFormat();
        $dt_data                  = array();
        $skillVerbColors = [
            'Remember'   => 'label-success',
            'Understand' => 'label-info',
            'Apply'      => 'label-primary',
            'Analyze'    => 'label-warning',
            'Evaluate'   => 'label-danger',
            'Create'     => 'label-purple' // custom
        ];

        if (!empty($m->data)) {
            foreach ($m->data as $key => $value) {
                $topic1    = '';
                $topic     = "";
                $lesson_id = $key;
                $topic     = $this->lessonplan_model->gettopicBylessonid($value->lesson_id, $this->sch_current_session);

                $topicAttainment = $this->lessonplan_model->getTopicAttainmentById($value->id, $this->sch_current_session);


                $skillVerbHtml = '<span class="text-muted">Not Set</span>';

                if (!empty($topicAttainment['skill_verb'])) {
                    $verb  = $topicAttainment['skill_verb'];
                    $class = $skillVerbColors[$verb] ?? 'label-default';

                    $skillVerbHtml = '<span class="label ' . $class . '">' . $verb . '</span>';
                }

                if ($this->rbac->hasPrivilege('topic', 'can_edit')) {
                    $editbtn = "<a href='" . base_url() . "admin/lessonplan/edittopic/" . $value->lesson_id . "'   class='btn btn-default btn-xs'  data-toggle='tooltip' data-placement='left' title='" . $this->lang->line('edit') . "'><i class='fa fa-pencil'></i></a>";
                
                    // $attainmentBtn = "
                    //     <button class='btn btn-xs btn-info edit-attainment'
                    //         data-topic-id='{$value->id}'
                    //         title='Edit Attainment'>
                    //         <i class='fa fa-bullseye'></i>
                    //     </button>";

                    $attainmentBtn = "
                        <button class='btn btn-xs btn-default'
                            onclick='editAttainment({$value->id})'
                            title='Edit Attainment'>
                            <i class='fa fa-bullseye'></i>
                        </button>";


                
                }
                if ($this->rbac->hasPrivilege('topic', 'can_delete')) {
                    $deletebtn = '';
                    $deletebtn = "<a onclick='deletetopicbulk(" . $this->lang->line('delete_confirm') . ")'  class='btn btn-default btn-xs' data-placement='left' title='" . $this->lang->line('delete') . "' data-toggle='tooltip'><i class='fa fa-trash'></i></a>";
                }

                foreach ($topic as $rl_value) {
                    $topic = $rl_value['name'] . '<br>';
                    $topic1 .=  $topic;
                }

                if (in_array($value->classid, $class_array)) {
                    $lesson_id = $key;
                    $row       = array();
                    $row[]     = $value->cname;
                    $row[]     = $value->sname;
                    $row[]     = $value->sgname;
                    $row[]     = $value->subname;
                    $row[]     = $value->lessonname;
                    $row[]     = $topic1;
                    $row[]     = $skillVerbHtml;
                    // NEW: Teaching Tool
                    $row[] = !empty($topicAttainment['teaching_tool'])
                        ? $topicAttainment['teaching_tool']
                        : '<span class="text-muted">Not Set</span>';

                    // NEW: Desired Attainment %
                    $row[] = !empty($topicAttainment['desired_attainment_percentage'])
                        ? $topicAttainment['desired_attainment_percentage'] . '%'
                        : '<span class="text-muted">Not Set</span>';
                    // $row[]     = $value->from_month . " - " . $value->to_month;
                    $row[]     = $attainmentBtn .' '.$editbtn .' '. $deletebtn;
                    $dt_data[] = $row;
                }
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

    public function getTopicAttainment()
    {
        if (!$this->rbac->hasPrivilege('topic', 'can_edit')) {
            access_denied();
        }

        $topic_id = $this->input->post('topic_id');

        $topic = $this->lessonplan_model->getTopicAttainmentById($topic_id , $this->sch_current_session);

        echo json_encode([
            'status' => 'success',
            'data'   => $topic
        ]);
    }

    public function saveTopicAttainment()
    {
        if (!$this->rbac->hasPrivilege('topic', 'can_edit')) {
            access_denied();
        }

        $data = [
            'session_id' => $this->sch_current_session,
            'topic_id'  => $this->input->post('topic_id'),
            'teaching_tool' => $this->input->post('teaching_tool'),
            'desired_attainment_percentage' =>
                $this->input->post('desired_attainment_percentage'),
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $topic_id = $this->input->post('topic_id');

        //print_r($data);die;

        $this->lessonplan_model->updateTopicAttainment($topic_id, $data);

        echo json_encode(['status' => 'success','message' => 'Attainment Updated Successfully']);
    }

    public function check()
    {

        $result  = $this->lessonplan_model->getlessonlist($this->sch_current_session, '');
        $m       = json_decode($result);
        echo "<pre>";
        print_r($m);

        // $class_id = 21;
        // $section_id = "0";
        // $data = $this->subjectgroup_model->getGroupByClassandSection($class_id, $section_id);

        // $subject_id = $this->subjectgroup_model->getGroupsubjects1(62);

        // print_r(sizeof($data));
        // echo "<pre>";
        // print_r($data);
        // print_r($subject_id);

        // $id = 566;
        // $data = $this->subjectgroup_model->getSubjectId($id);

        // $subject_group_id = 61;
        // $subject_id = 1;
        // $subject_group_subjects = $this->subjectgroup_model->getSubjectGroupsubjects($subject_group_id, $subject_id);



        //==========================================================================
        // $class_id = 21;
        // $data = $this->subjectgroup_model->getGroupByClassandSection($class_id, '0');
        // // $class_id = $_POST['class_id'];
        // $subject_id = 1;
        // $lessons = array(
        //     'lesson1' => 'h1',
        //     'lesson2' => 'h2',
        //     'lesson3' => 'h3',
        //     'lesson4' => 'h4'
        // );


        // // $lessons = $_POST('lessons');
        // // $this->lessonplan_model->add_multi_lesson($data, $_POST['class_id'], $_POST['subject_id'], $_POST('lessons'));
        // $insert_lesson_array = array();

        // for ($i = 0; $i < sizeof($data); $i++) {
        //     $lessons_array = array();

        //     $subject_group_class_sectionsId = $this->lessonplan_model->getsubject_group_class_sectionsId($class_id, $data[$i]['section_id'], $data[$i]['subject_group_id']);
        //     $subject_group_subjects = $this->subjectgroup_model->getSubjectGroupsubjects($data[$i]['subject_group_id'], $subject_id);

        //     foreach ($lessons as $key => $value) {
        //         $data1 = array(
        //             'subject_group_subject_id'        => $subject_group_subjects,
        //             'name'                            => $value,
        //             'subject_group_class_sections_id' => $subject_group_class_sectionsId['id'],
        //             'session_id'                      => $this->sch_current_session,
        //         );

        //         $lessons_array[] = $data1;

        //         // $this->lessonplan_model->add_lesson($data);
        //     }
        //     $insert_lesson_array[] = $lessons_array;
        // }
        // // $this->db->insert_batch('lesson', $insert_lesson_array);
        // echo "<pre>";
        // print_r($insert_lesson_array);

        //============================================================================
        // $class_id = "46";
        // $section_id = "0";
        // $subject_group_id = "85";

        // $subject_group_class_sectionsId = $this->lessonplan_model->getsubject_group_class_sectionsId($class_id, $section_id, $subject_group_id);
        // // if (empty($subject_group_class_sectionsId)) {
        // //     echo "No data found.";
        // // } else {
        // echo "<pre>";
        // print_r($subject_group_class_sectionsId);
        // }


        //============================================================================


        // $class_id = "30";
        // $lesson_id = "238";
        // $lesson = $this->lessonplan_model->getlessonBylessonid($lesson_id);
        // $sections = $this->subjectgroup_model->getGroupByClassandSection($class_id, '0');
        // $insert_lesson_array = array();

        // foreach ($sections as $section) {
        //     $subject_group_class_sectionsId = $this->lessonplan_model->getsubject_group_class_sectionsId($class_id, $section['section_id'], $section['subject_group_id']);
        //     // $data                           = $this->lessonplan_model->getlessonBysubjectid($subject_id, $subject_group_class_sectionsId['id']);


        //     // $lesson_id = $this->lessonplan_model->getlessonBysubjectgroupsubjectid_name_subjectgroupclasssectionsId($lesson[0]['subject_group_subject_id'], $lesson[0]['name'], $subject_group_class_sectionsId['id']);
        //     // $lessons_array = array();
        //     // foreach ($_POST['topic'] as $key => $value) {
        //     //     $data = array(
        //     //         'lesson_id'  => $lesson_id['id'],
        //     //         'name'       => $value,
        //     //         'session_id' => $this->sch_current_session,
        //     //     );
        //     //     // $this->lessonplan_model->add_topic($data);

        //     $lessons_array[] = $subject_group_class_sectionsId;
        //     // }
        //     $insert_lesson_array[] = $lessons_array;
        // }


        // // $subject_group_subject_id = "856";
        // // $subject_group_class_sectionsId = "53";
        // // $lesson_id = $this->lessonplan_model->getlessonBysubjectgroupsubjectid_name_subjectgroupclasssectionsId($lesson[0]['subject_group_subject_id'], $lesson[0]['name'], $subject_group_class_sectionsId);

        // echo "<pre>";
        // print_r($insert_lesson_array);
    }

    public function getlessonlist()
    {
        $class = $this->class_model->get();

        foreach ($class as $class_key => $class_value) {
            $class_array[] = $class_value['id'];
        }
        $result  = $this->lessonplan_model->getlessonlist($this->sch_current_session, '');
        $m       = json_decode($result);
        $dt_data = array();
        if (!empty($m->data)) {
            foreach ($m->data as $key => $value) {

                $topic       = "";
                $lesson_id   = $key;
                $lesson_name = "";
                $lesson      = $this->lessonplan_model->getlesson($value->subject_group_subject_id, $value->subject_group_class_sections_id, $this->sch_current_session);

                if ($this->rbac->hasPrivilege('lesson', 'can_edit')) {
                    $editbtn = "<a href='" . base_url() . "admin/lessonplan/editlesson/" . $value->subject_group_class_sections_id . "/" . $value->subject_group_subject_id . "'   class='btn btn-default btn-xs'  data-toggle='tooltip' data-placement='left' title='" . $this->lang->line('edit') . "'><i class='fa fa-pencil'></i></a>";
                }
                if ($this->rbac->hasPrivilege('lesson', 'can_delete')) {
                    $deletebtn = '';
                    $deletebtn = "<a onclick='deletelessonbulk(" . '"' . $value->subject_group_class_sections_id . '"' . ',' . '"' . $value->subject_group_subject_id . '"' . "  )'  class='btn btn-default btn-xs' data-placement='left' title='" . $this->lang->line('delete') . "' data-toggle='tooltip'><i class='fa fa-remove'></i></a>";
                }

                if (in_array($value->classid, $class_array)) {

                    foreach ($lesson as $rl_value) {
                        $lesson_name .= $rl_value['name'] . '<br>';
                    };
                    $row       = array();
                    $row[]     = $value->cname;
                    $row[]     = $value->sname;
                    $row[]     = $value->sgname;
                    $row[]     = $value->subname;
                    $row[]     = $lesson_name;
                    // $row[]     = $value->from_month . " - " . $value->to_month;
                    $row[]     = $editbtn . ' ' . $deletebtn;
                    $dt_data[] = $row;
                }
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
}
