<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Subjectgroup extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('subject_group', 'can_view')) {
            access_denied();
        }

        $json_array = array();
        $this->session->set_userdata('top_menu', 'Academics');
        $this->session->set_userdata('sub_menu', 'subjectgroup/index');
        $data['title'] = 'Add Class';
        $data['title_list'] = 'Class List';
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $data['section_array'] = $json_array;

        $this->form_validation->set_rules(
            'name',
            $this->lang->line('name'),
            array(
                'required',
                array('class_exists', array($this->subjectgroup_model, 'class_exists')),
            )
        );

        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');

        $this->form_validation->set_rules('subject[]', $this->lang->line('subject'), 'trim|required|xss_clean');

        $this->form_validation->set_rules(
            'sections[]',
            $this->lang->line('section'),
            array(
                'required',
                array('check_section_exists', array($this->subjectgroup_model, 'check_section_exists'))
            )
        );

        $current_session  = $this->setting_model->getCurrentSession();
        $copy_subject_group = $this->db
        ->where('session_id', $current_session)
        ->count_all_results('subject_groups');

        $data['copy_subject_group'] = $copy_subject_group;

        if ($this->form_validation->run() == false) {
            $data['section_array'] = $this->input->post('sections');
        } else {
            $class_id = $this->input->post('class_id');
            $school_divison_id = $this->subjectgroup_model->fetchSchoolDivisonId($class_id);
            $name = $this->input->post('name');
            $session = $this->setting_model->getCurrentSession();
            $class_array = array(
                'name' => $this->input->post('name'),
                'school_divison_id' => $school_divison_id,
                'session_id' => $session,
                'description' => $this->input->post('description'),
            );
            $subject = $this->input->post('subject');
            $sections = $this->input->post('sections');

            // print_r($class_array);
            // die;

            $this->subjectgroup_model->add($class_array, $subject, $sections);
            // $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            $this->session->set_userdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/subjectgroup');
        }
        $subject_list = $this->subject_model->get();
        $data['subjectlist'] = $subject_list;
        $subjectgroupList = $this->subjectgroup_model->getByID();
        $data['subjectgroupList'] = $subjectgroupList;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/subjectgroup/subjectgroupList', $data);
        $this->load->view('layout/footer', $data);
    }

    public function copyPreviousSession()
    {
        if (!$this->rbac->hasPrivilege('subject_group', 'can_add')) {
            access_denied();
        }

        $current_session = $this->setting_model->getCurrentSession();
        $previous_session = $current_session - 1;

        // Safety check
        $exists = $this->db
            ->where('session_id', $current_session)
            ->count_all_results('subject_groups');

        if ($exists > 0) {
            $this->session->set_flashdata(
                'msg',
                '<div class="alert alert-warning">Subject Groups already exist for current session.</div>'
            );
            redirect('admin/subjectgroup');
        }

        $this->load->model('Subjectgroup_model');

        $result = $this->Subjectgroup_model
            ->cloneSubjectGroups($previous_session, $current_session);

        if ($result) {
            $this->session->set_flashdata(
                'msg',
                '<div class="alert alert-success">Subject Groups copied successfully.</div>'
            );
        } else {
            $this->session->set_flashdata(
                'msg',
                '<div class="alert alert-danger">Copy failed. Transaction rolled back.</div>'
            );
        }

        redirect('admin/subjectgroup');
    }

   

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('subject_group', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'Fees Master List';
        $this->subjectgroup_model->remove($id);
        redirect('admin/subjectgroup');
    }

    public function edit($id)
    {
        if (!$this->rbac->hasPrivilege('subject_group', 'can_edit')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Academics');
        $this->session->set_userdata('sub_menu', 'subjectgroup/index');
        $json_array = array();
        $old_sections = array();
        $old_subjects = array();
        $data['title'] = 'Edit Class';
        $data['id'] = $id;
        $class = $this->class_model->get();
        $data['classlist'] = $class;

        $subject_list = $this->subject_model->get();
        $data['subjectlist'] = $subject_list;
        $subjectgroupList = $this->subjectgroup_model->getByID();
        $data['class_id'] = 0;
        $data['subjectgroupList'] = $subjectgroupList;
        $subjectgroup = $this->subjectgroup_model->getByID($id);


        if (!empty($subjectgroup[0]->sections)) {

            $data['class_id'] = $subjectgroup[0]->sections[0]->class_id;
            foreach ($subjectgroup[0]->sections as $key => $value) {

                $old_sections[] = ($value->class_section_id);
                $json_array[] = ($value->class_section_id);
            }
        }
        if (!empty($subjectgroup[0]->group_subject)) {


            foreach ($subjectgroup[0]->group_subject as $key => $value) {

                $old_subjects[] = $value->subject_id;
            }
        }

        $data['section_array'] = $json_array;

        $data['subjectgroup'] = $subjectgroup;
        $this->form_validation->set_rules(
            'name',
            $this->lang->line('name'),
            array(
                'required',
                array('class_exists', array($this->subjectgroup_model, 'class_exists')),
            )
        );

        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');

        $this->form_validation->set_rules(
            'sections[]',
            $this->lang->line('section'),
            array(
                'required',
                array('check_section_exists', array($this->subjectgroup_model, 'check_section_exists'))
            )
        );

        $this->form_validation->set_rules('subject[]', $this->lang->line('subject'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            if ($this->input->server('REQUEST_METHOD') == "POST") {
                $data['section_array'] = $this->input->post('sections');
            }

            $this->load->view('layout/header', $data);
            $this->load->view('admin/subjectgroup/subjectgroupEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {

            $class_array = array(
                'id' => $this->input->post('id'),
                'name' => $this->input->post('name'),
                'description' => $this->input->post('description'),
            );
            $subject = $this->input->post('subject');
            $sections = $this->input->post('sections');
            $delete_sections = array_diff($old_sections, $sections);
            $add_sections = array_diff($sections, $old_sections);
            $delete_subjects = array_diff($old_subjects, $subject);
            $add_subjects = array_diff($subject, $old_subjects);
            $this->subjectgroup_model->edit($class_array, $delete_sections, $add_sections, $delete_subjects, $add_subjects);
            redirect('admin/subjectgroup');
        }
    }

    public function addsubjectgroup()
    {
        $this->form_validation->set_rules('subject_group_id', $this->lang->line('fee_group'), 'required|trim|xss_clean');

        if ($this->form_validation->run() == false) {
            $data = array(
                'subject_group_id' => form_error('subject_group_id'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {
            $student_session_id = $this->input->post('student_session_id');
            $subject_group_id = $this->input->post('subject_group_id');
            $student_sesssion_array = isset($student_session_id) ? $student_session_id : array();
            $student_ids = $this->input->post('student_ids');
            $delete_student = array_diff($student_ids, $student_sesssion_array);

            $preserve_record = array();
            if (!empty($student_sesssion_array)) {
                foreach ($student_sesssion_array as $key => $value) {

                    $insert_array = array(
                        'student_session_id' => $value,
                        'subject_group_id' => $subject_group_id,
                    );
                    $inserted_id = $this->studentsubjectgroup_model->add($insert_array);

                    $preserve_record[] = $inserted_id;
                }
            }

            if (!empty($delete_student)) {
                $this->studentsubjectgroup_model->delete($subject_group_id, $delete_student);
            }

            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
            echo json_encode($array);
        }
    }

    public function getGroupByClassandSection()
    {
        $class_id = $this->input->post('class_id');
        $section_id = $this->input->post('section_id');


        $data = $this->subjectgroup_model->getGroupByClassandSection($class_id, $section_id);

        echo json_encode($data);
    }


    public function getSubjectByClassandSectionDate()
    {


        $date = date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date')));

        $day = date('l', strtotime($date));

        $class_id = $this->input->post('class_id');
        $section_id = $this->input->post('section_id');
        $data = $this->subjecttimetable_model->getSubjectByClassandSectionDay($class_id, $section_id, $day);
        echo json_encode($data);
    }

    public function getSubjectByClassandSection()
    {


        $class_id = $this->input->post('class_id');
        $section_id = $this->input->post('section_id');
        $data = $this->subjecttimetable_model->getSubjectByClassandSection($class_id, $section_id);
        echo json_encode($data);
    }

    public function check()
    {
        // $this->db->select('school_divison_id');
        // $this->db->from('classes');
        // $this->db->where('id', 26);
        // $query = $this->db->get();
        // echo $query->row()->school_divison_id;


        $class_id = 54;
        $section_id = 17;
        $data = $this->subjecttimetable_model->getAllSubjectByClassandSection($class_id, $section_id);

        echo '<pre>';

        echo count($data);
        
        print_r($data);
    }

    public function getAllSubjectByClassandSection()
    {

        $class_id = $this->input->post('class_id');
        $section_id = $this->input->post('section_id');
        $data = $this->subjecttimetable_model->getAllSubjectByClassandSection($class_id, $section_id);
        echo json_encode($data);
    }

    public function getGroupsubjects()
    {

        $subject_group_id = $this->input->post('subject_group_id');
        $data = $this->subjectgroup_model->getGroupsubjects($subject_group_id);
        echo json_encode($data);
    }
}
