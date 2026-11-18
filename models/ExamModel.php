<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class ExamModel extends CI_Model {


    public function __construct() {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    public function getStudentProfileForReport($student_id, $session_id = null) {
        
        // Select ONLY the fields needed for the report card
        $this->db->select('
            students.id, 
            students.admission_no, 
            students.roll_no, 
            students.firstname, 
            students.lastname, 
            students.image, 
            classes.class as course_name, 
            sections.section as batch_name,
            sessions.session as academic_session
        ');
        
        $this->db->from('students');
        
        // Keep only the essential academic joins
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('sessions', 'sessions.id = student_session.session_id');

        // Filter by the specific student
        $this->db->where('students.id', $student_id);

        // Optional: If you want to ensure it pulls from the current academic session
        if ($session_id != null) {
            $this->db->where('student_session.session_id', $session_id);
        } elseif (isset($this->current_session)) {
            $this->db->where('student_session.session_id', $this->current_session);
        }

        $query = $this->db->get();

        // Return a single row array since we are fetching one specific student
        return $query->row_array();
    }

    // Fetch students based on Course (Class) and Batch (Section)
    public function getStudentsByClassSection($class_id, $section_id) {
        $this->db->select('students.id as student_id, students.admission_no, students.roll_no, students.firstname, students.lastname');
        $this->db->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('student_session.class_id', $class_id);
        $this->db->where('student_session.section_id', $section_id);
        $this->db->where('students.is_active', 'yes');
        $this->db->order_by('CAST(students.admission_no AS UNSIGNED)', 'ASC');        
        $query = $this->db->get();
        return $query->result_array();
    }

    // Fetch all active subjects to populate the dynamic modal dropdown
    public function getSubjects() {
        $this->db->select('id, name, code');
        $this->db->from('subjects');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function saveExamResults($exam_group_id, $exam_date, $student_id, $data, $submitted_subject_ids) {
        
        // --- STEP 1: CLEANUP DELETED ROWS ---
        // Delete any marks for this specific student/exam/date that are NOT in the submitted list
        if (!empty($submitted_subject_ids)) {
            $this->db->where('exam_group_id', $exam_group_id);
            $this->db->where('exam_date', $exam_date);
            $this->db->where('student_id', $student_id);
            $this->db->where_not_in('subject_id', $submitted_subject_ids);
            $this->db->delete('exam_results_new');
        } else {
            // Defensive check: If the user somehow deleted ALL rows in the frontend, 
            // delete all records for this student's exam.
            $this->db->where('exam_group_id', $exam_group_id);
            $this->db->where('exam_date', $exam_date);
            $this->db->where('student_id', $student_id);
            $this->db->delete('exam_results_new');
        }

        // --- STEP 2: INSERT OR UPDATE CURRENT ROWS ---
        if (!empty($data)) {
            foreach ($data as $row) {
                $insert_query = $this->db->insert_string('exam_results_new', $row);
                $update_query = " ON DUPLICATE KEY UPDATE 
                    total_marks = VALUES(total_marks), 
                    obtained_marks = VALUES(obtained_marks),
                    status = VALUES(status),
                    updated_at = CURRENT_TIMESTAMP";
                
                $this->db->query($insert_query . $update_query);
            }
        }
        
        return true;
    }

    // Fetch existing marks for a specific student, exam group, and date
    public function getStudentMarks($exam_group_id, $exam_date, $student_id) {
        $this->db->select('subject_id, total_marks, obtained_marks, status');
        $this->db->from('exam_results_new');
        $this->db->where('exam_group_id', $exam_group_id);
        $this->db->where('exam_date', $exam_date);
        $this->db->where('student_id', $student_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getIndividualReportData($student_id, $exam_group_id, $exam_date) {
        $this->db->select('eg.name,er.total_marks, er.obtained_marks, er.min_marks, er.status, er.topic, s.name as subject_name, s.id as subject_id');
        $this->db->from('exam_results_new er');
        $this->db->join('subjects s', 'er.subject_id = s.id');
        $this->db->join('exam_groups eg', 'er.exam_group_id = eg.id');
        $this->db->where('er.student_id', $student_id);
        $this->db->where('er.exam_group_id', $exam_group_id);
        $this->db->where('er.exam_date', $exam_date);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getBulkReportData($class_id, $section_id, $exam_group_id, $exam_date) {
        $this->db->select('
            students.id as student_id, 
            students.roll_no, 
            students.firstname, 
            students.lastname, 
            er.subject_id, 
            er.topic,
            er.total_marks, 
            er.obtained_marks, 
            er.status, 
            s.name as subject_name
        ');
        $this->db->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        
        // LEFT JOIN exam_results so we still get the student even if they have no marks yet
        $this->db->join('exam_results_new er', "er.student_id = students.id AND er.exam_group_id = " . $this->db->escape($exam_group_id) . " AND er.exam_date = " . $this->db->escape($exam_date), 'left');
        $this->db->join('subjects s', 'er.subject_id = s.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('student_session.class_id', $class_id);
        $this->db->where('student_session.section_id', $section_id);
        $this->db->where('students.is_active', 'yes');
        
        // Order by roll number to match a standard attendance sheet
        $this->db->order_by('CAST(students.admission_no AS UNSIGNED)', 'ASC');        
        
        $query = $this->db->get();
        return $query->result_array();
    }


    // Fetch dynamic subjects based on Class and Section relationships
    public function getSubjectsByClassSection($class_id, $section_id, $session_id) {
        $this->db->select('subjects.id as subject_id, subjects.name as subject_name');
        $this->db->from('class_sections');
        $this->db->join('subject_group_class_sections', 'subject_group_class_sections.class_section_id = class_sections.id');
        $this->db->join('subject_group_subjects', 'subject_group_subjects.subject_group_id = subject_group_class_sections.subject_group_id');
        $this->db->join('subjects', 'subjects.id = subject_group_subjects.subject_id');
        
        $this->db->where('class_sections.class_id', $class_id);
        $this->db->where('class_sections.section_id', $section_id);
        
        // Ensure we are pulling for the active academic session
        $this->db->where('subject_group_class_sections.session_id', $session_id);
        $this->db->where('subject_group_subjects.session_id', $session_id);
        
        $this->db->group_by('subjects.id'); // Prevent duplicates
        $query = $this->db->get();
        return $query->result_array();
    }

    // Fetch all existing marks for a specific exam grid to pre-fill inputs
    public function getAllMarksForGrid($exam_group_id, $exam_date, $class_id, $section_id) {
        $this->db->select('exam_results.student_id, exam_results.subject_id, exam_results.topic, exam_results.total_marks, exam_results.obtained_marks, exam_results.status');
        $this->db->from('exam_results_new as exam_results');
        $this->db->join('student_session', 'student_session.student_id = exam_results.student_id');
        
        $this->db->where('exam_results.exam_group_id', $exam_group_id);
        $this->db->where('exam_results.exam_date', $exam_date);
        $this->db->where('student_session.class_id', $class_id);
        $this->db->where('student_session.section_id', $section_id);
        
        $query = $this->db->get();
        
        // Restructure array so it's easy to access in the View: $array[student_id][subject_id]
        $result = array();
        foreach ($query->result_array() as $row) {
            $result[$row['student_id']][$row['subject_id']] = $row;
        }
        return $result;
    }


    /**
     * Fetch the toppers (highest marks and names) for each subject in a specific exam
     */
    public function getSubjectToppers($exam_group_id, $exam_date, $class_id, $section_id) {
        // 1. Find the highest mark for each subject
        $this->db->select('er.subject_id, MAX(er.obtained_marks) as max_mark');
        $this->db->from('exam_results_new er');
        $this->db->join('student_session ss', 'ss.student_id = er.student_id');
        $this->db->where('er.exam_group_id', $exam_group_id);
        $this->db->where('er.exam_date', $exam_date);
        $this->db->where('ss.class_id', $class_id);
        $this->db->where('ss.section_id', $section_id);
        $this->db->where('ss.session_id', $this->current_session);
        $this->db->where('er.status', 'present');
        $this->db->group_by('er.subject_id');
        $max_marks_query = $this->db->get()->result_array();

        if (empty($max_marks_query)) return [];

        $toppers = array();
        
        // 2. Fetch the names of the students who got those maximum marks
        foreach ($max_marks_query as $max) {
            if ($max['max_mark'] === null || $max['max_mark'] == 0) continue; 

            $this->db->select('s.firstname, s.lastname');
            $this->db->from('exam_results_new er');
            $this->db->join('students s', 's.id = er.student_id');
            $this->db->join('student_session ss', 'ss.student_id = er.student_id');
            $this->db->where('er.exam_group_id', $exam_group_id);
            $this->db->where('er.exam_date', $exam_date);
            $this->db->where('ss.class_id', $class_id);
            $this->db->where('ss.section_id', $section_id);
            $this->db->where('ss.session_id', $this->current_session);
            $this->db->where('er.subject_id', $max['subject_id']);
            $this->db->where('er.obtained_marks', $max['max_mark']);
            
            $students = $this->db->get()->result_array();
            
            // Format names into a comma-separated string
            $names = array();
            foreach($students as $stu) {
                $names[] = trim($stu['firstname'] . ' ' . $stu['lastname']);
            }
            
            $toppers[$max['subject_id']] = array(
                'marks' => $max['max_mark'],
                'names' => implode(', ', $names)
            );
        }
        
        return $toppers;
    }


}
