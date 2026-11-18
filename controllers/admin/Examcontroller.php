<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Examcontroller extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->sch_current_session = $this->setting_model->getCurrentSession();
        $this->load->model('ExamModel');
        $this->load->model('examgroup_model');
        $this->sch_setting_detail  = $this->setting_model->getSetting();
        $this->load->helper('form');
    }


    public function index()
    {

        $this->session->set_userdata('top_menu', 'Examinations');
        $this->session->set_userdata('sub_menu', 'Examinations/exammarks');
        $data['title'] = 'Exam Marks Entry';
        $data['examgrouplist'] = $this->examgroup_model->get();
        $data['classlist'] = $this->class_model->get();

        $data['studentList'] = array();
        $data['dynamic_subjects'] = array();
        $data['existing_marks'] = array();

        // NEW: Keep track of selected subjects for the checkboxes after submit
        $data['selected_subject_ids'] = $this->input->post('subject_ids') ? $this->input->post('subject_ids') : array();

        if ($this->input->post('search') == 'search_filter') {
            $class_id = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');
            $subject_ids = $this->input->post('subject_ids'); // Now an Array
            $data['exam_group_id'] = $this->input->post('exam_group_id');
            $data['exam_date'] = $this->input->post('exam_date');

            $session_id = $this->setting_model->getCurrentSession();

            $data['studentList'] = $this->ExamModel->getStudentsByClassSection($class_id, $section_id);

            // Fetch all subjects, but filter to ONLY the checked ones
            $all_subjects = $this->ExamModel->getSubjectsByClassSection($class_id, $section_id, $session_id);
            if (!empty($subject_ids)) {
                $data['dynamic_subjects'] = array_values(array_filter($all_subjects, function ($s) use ($subject_ids) {
                    return in_array($s['subject_id'], $subject_ids);
                }));
            }

            $data['existing_marks'] = $this->ExamModel->getAllMarksForGrid($data['exam_group_id'], $data['exam_date'], $class_id, $section_id);
            $data['has_marks'] = !empty($data['existing_marks']);

            // NEW: Extract topics to easily populate the table headers
            // $data['existing_topics'] = array();
            // if (!empty($data['existing_marks'])) {
            //     foreach ($data['existing_marks'] as $stu_marks) {
            //         foreach ($stu_marks as $sub_id => $mark_details) {
            //             if (!isset($data['existing_topics'][$sub_id]) && !empty($mark_details['topic'])) {
            //                 $data['existing_topics'][$sub_id] = $mark_details['topic'];
            //             }
            //         }
            //     }
            // }
        }

        $this->load->view('layout/header', $data);
        $this->load->view('admin/exam1/exam_entry_view', $data);
        $this->load->view('layout/footer', $data);
    }

    /**
     * AJAX endpoint to fetch dynamic subjects based on Class and Batch
     */
    public function get_subjects_ajax()
    {

        $class_id = $this->input->post('class_id');
        $section_id = $this->input->post('section_id');

        $session_id = $this->setting_model->getCurrentSession();

        $subjects = $this->ExamModel->getSubjectsByClassSection($class_id, $section_id, $session_id);

        echo json_encode($subjects);
    }

    // Updated Save Method
    public function save_marks()
    {
        $exam_group_id = $this->input->post('exam_group_id');
        $exam_date = $this->input->post('exam_date');
        $grid_data = $this->input->post('grid_data');
        //$topics = $this->input->post('topics');
        $max_marks     = $this->input->post('max_marks');

        $db_data = array();

        if (!empty($grid_data)) {
            foreach ($grid_data as $student) {
                $student_id = $student['student_id'];

                foreach ($student['marks'] as $mark) {
                    $status = isset($mark['status']) ? $mark['status'] : 'present';
                    $subject_id = $mark['subject_id'];

                    // Match the topic to the subject
                    //$subject_topic = isset($topics[$subject_id]) ? $topics[$subject_id] : null;


                    // Get Out Of marks from subject-level input
                    $total_marks = isset($max_marks[$subject_id])
                        ? $max_marks[$subject_id]
                        : null;

                    // Do not save if Out Of marks is missing
                    if ($total_marks === null || $total_marks === '') {
                        continue;
                    }

                    $obtained_marks = ($status === 'pending')
                        ? NULL
                        : $mark['obtained_marks'];

                    //if (!empty($mark['total_marks'])) {
                    $db_data[] = array(
                        'exam_group_id' => $exam_group_id,
                        'exam_date' => $exam_date,
                        'student_id' => $student_id,
                        'subject_id' => $subject_id,
                        //'topic' => $subject_topic,
                        'topic'           => NULL,
                        'total_marks' => $total_marks,
                        'obtained_marks' => $obtained_marks,
                        'status' => $status
                    );
                    //}
                }
            }
        }

        $success = false;
        if (!empty($db_data)) {
            foreach ($db_data as $row) {
                $insert_query = $this->db->insert_string('exam_results_new', $row);
                $update_query = " ON DUPLICATE KEY UPDATE 
                    topic = VALUES(topic),
                    total_marks = VALUES(total_marks), 
                    obtained_marks = VALUES(obtained_marks),
                    status = VALUES(status),
                    updated_at = CURRENT_TIMESTAMP";
                $this->db->query($insert_query . $update_query);
            }
            $success = true;
        }

        if ($success) {
            echo json_encode(array('status' => 'success', 'message' => 'All marks saved successfully!'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'No valid marks provided to save.'));
        }
    }

    // AJAX Handler for fetching existing marks when modal opens
    public function get_student_marks()
    {
        $exam_group_id = $this->input->post('exam_group_id');
        $exam_date = $this->input->post('exam_date');
        $student_id = $this->input->post('student_id');

        $marks = $this->ExamModel->getStudentMarks($exam_group_id, $exam_date, $student_id);

        echo json_encode(array(
            'status' => 'success',
            'data' => $marks
        ));
    }

    public function individual_report_search()
    {

        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors',1);
        // error_reporting(E_ALL);

        // ini_set('display_startup_errors', 1);


        if (!$this->rbac->hasPrivilege('exam_group', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Examinations');
        $this->session->set_userdata('sub_menu', 'Examinations/examresult');
        $data['title'] = 'Generate Individual Report';

        // Fetch active classes and exam groups
        $data['examgrouplist'] = $this->examgroup_model->get();
        $data['classlist'] = $this->class_model->get();

        $this->load->view('layout/header', $data);
        $this->load->view('admin/exam1/individual_report_search', $data);
        $this->load->view('layout/footer', $data);
    }

    // AJAX method to get students for the report dropdown
    public function get_students_ajax()
    {
        $class_id = $this->input->post('class_id');
        $section_id = $this->input->post('section_id');

        $students = $this->ExamModel->getStudentsByClassSection($class_id, $section_id);

        echo json_encode($students);
    }

    public function individual_report()
    {
        $exam_group_id = $this->input->post('exam_group_id');
        $exam_date = $this->input->post('exam_date');
        $student_id_post = $this->input->post('student_id');
        $class_id = $this->input->post('class_id');
        $section_id = $this->input->post('section_id');

        $students_to_process = array();

        if ($student_id_post == 'all') {
            $students_to_process = $this->ExamModel->getStudentsByClassSection($class_id, $section_id);
        } else {

            $students_to_process[] = array('student_id' => $student_id_post);
        }

        // NEW: Fetch all subject toppers for this batch ONCE to save database load
        $subject_toppers = $this->ExamModel->getSubjectToppers($exam_group_id, $exam_date, $class_id, $section_id);


        $report_html = '<div class="box box-info" id="printArea">';
        $report_html .= '<div class="box-header with-border no-print"><button type="button" class="btn btn-primary pull-right" onclick="window.print()"><i class="fa fa-print"></i> Print Report(s)</button></div>';
        $report_html .= '<div class="box-body">';

        $valid_reports_count = 0;

        foreach ($students_to_process as $stu) {
            $current_student_id = $stu['student_id'];

            $data['student'] = $this->ExamModel->getStudentProfileForReport($current_student_id);


            $class_info = $this->db
                ->select('school_divison_id')
                ->where('id', $class_id)
                ->get('classes')
                ->row();

            $school_divison_id = !empty($class_info) ? (int) $class_info->school_divison_id : 0;

            // Assign logo according to school division
            if ($school_divison_id == 1) {

                // Pre-primary
                $data['school_logo'] = base_url('uploads/staff_images/76.png');

            } elseif ($school_divison_id == 2 || $school_divison_id == 3) {

                // Primary / Secondary
                $data['school_logo'] = base_url('uploads/staff_images/79.png');

            } else {

                // Higher Secondary / fallback
                $data['school_logo'] = base_url('uploads/staff_images/80.png');
            }
            
            $marks_data = $this->ExamModel->getIndividualReportData($current_student_id, $exam_group_id, $exam_date);

            // Skip this student if they don't have any marks for this exam
            if (empty($marks_data)) {
                continue;
            }

            $grand_total_max = 0;
            $grand_total_obtained = 0;
            $is_fail = false;

            foreach ($marks_data as $mark) {
                // $grand_total_max += $mark['total_marks'];

                // if ($mark['status'] == 'present' && $mark['obtained_marks'] !== null) {
                //     $grand_total_obtained += $mark['obtained_marks'];

                //     if (isset($mark['min_marks']) && $mark['obtained_marks'] < $mark['min_marks']) {
                //         $is_fail = true;
                //     }
                // } else {
                //     $is_fail = true;
                // }

                 $grand_total_max += (float) $mark['total_marks'];


                if (
                    $mark['status'] == 'present' &&
                    $mark['obtained_marks'] !== null &&
                    $mark['obtained_marks'] !== ''
                ) {

                    $grand_total_obtained +=
                        (float) $mark['obtained_marks'];
                }
            }

            $percentage = ($grand_total_max > 0) ? ($grand_total_obtained / $grand_total_max) * 100 : 0;

            /*
            * ==========================================
            * PASS / FAIL
            *
            * Passing percentage = 40%
            * ==========================================
            */

            $passing_percentage = 40;

            $is_fail = false;


            foreach ($marks_data as $mark) {

                /*
                * Pending / Absent means result cannot
                * be considered passed.
                */
                if (
                    $mark['status'] == 'pending' ||
                    $mark['status'] == 'absent'
                ) {

                    $is_fail = true;
                    break;
                }
            }


            /*
            * Overall percentage determines result
            */
            if ($percentage < $passing_percentage) {

                $is_fail = true;
            }


            /*
            * Remarks
            */
            $remarks = $is_fail ? 'Needs Improvement' : 'Satisfactory';
            // if ($is_fail) {

            //     $remarks = 'Needs Improvement';

            // } elseif ($percentage >= 75) {

            //     $remarks = 'Excellent';

            // } elseif ($percentage >= 60) {

            //     $remarks = 'Very Good';

            // } elseif ($percentage >= 50) {

            //     $remarks = 'Good';

            // } else {

            //     $remarks = 'Satisfactory';
            // }

            $data['marks'] = $marks_data;
            $data['grand_total_max'] = number_format($grand_total_max, 2);
            $data['grand_total_obtained'] = number_format($grand_total_obtained, 2);
            $data['percentage'] = number_format($percentage, 2);
            $data['final_result'] = $is_fail ? 'Fail' : 'Pass';

            $data['passing_percentage'] = $passing_percentage;
            $data['remarks'] = $remarks;

            // NEW: Pass the pre-calculated toppers array to the view
            $data['subject_toppers'] = $subject_toppers;
            $data['exam_date'] = date('d-m-Y', strtotime($exam_date));

            //print_r($data);die;

            $report_html .= $this->load->view('admin/exam1/_individual_report_partial', $data, TRUE);
            $valid_reports_count++;
        }

        $report_html .= '</div></div>';

        if ($valid_reports_count == 0) {
            echo json_encode(array('status' => 'error', 'message' => 'No marks found for the selected student(s) on this date.'));
            return;
        }

        echo json_encode(array('status' => 'success', 'html' => $report_html));
    }

    public function bulk_report()
    {
        $class_id = $this->input->post('class_id');
        $section_id = $this->input->post('section_id');
        $exam_group_id = $this->input->post('exam_group_id');
        $exam_date = $this->input->post('exam_date');

        $data['class_details'] = $this->class_model->get($class_id);
        $data['section_details'] = $this->section_model->get($section_id);
        $data['exam_group_details'] = $this->examgroup_model->get($exam_group_id);
        $data['exam_date'] = date('d/m/y', strtotime($exam_date));

        $raw_data = $this->ExamModel->getBulkReportData($class_id, $section_id, $exam_group_id, $exam_date);

        $students = array();
        $subjects = array();
        //$topics = array();

        foreach ($raw_data as $row) {
            $stu_id = $row['student_id'];

            if (!isset($students[$stu_id])) {
                $students[$stu_id] = array(
                    'roll_no' => $row['roll_no'],
                    'name' => $row['firstname'] . ' ' . $row['lastname'],
                    'marks' => array()
                );
            }

            if ($row['subject_id']) {

                $subjects[$row['subject_id']] = $row['subject_name'];

                // if (!empty($row['topic'])) {
                //     $topics[$row['subject_id']] = $row['topic'];
                // }

                $students[$stu_id]['marks'][$row['subject_id']] = array(
                    'obtained' => $row['obtained_marks'],
                    'total' => $row['total_marks'],
                    'status' => $row['status']
                );
            }
        }

        $data['students'] = $students;
        $data['subjects'] = $subjects;
        //$data['topics'] = $topics;

        $this->load->view('admin/exam1/bulk_report_print', $data);
    }

    /**
     * Phase 6: Dedicated Bulk Report Search Page
     */
    public function bulk_report_search()
    {
        if (!$this->rbac->hasPrivilege('exam_group', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Examinations');
        $this->session->set_userdata('sub_menu', 'Examinations/bulk_report'); // Adjust to your actual menu slug
        $data['title'] = 'Generate Bulk Report';

        // Fetch active classes and exam groups for the dropdowns
        $data['examgrouplist'] = $this->examgroup_model->get();
        $data['classlist'] = $this->class_model->get();

        $this->load->view('layout/header', $data);
        $this->load->view('admin/exam1/bulk_report_search', $data);
        $this->load->view('layout/footer', $data);
    }
}
