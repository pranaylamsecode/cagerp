<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Lessonplan_model extends MY_model
{

    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();

        $this->current_session_name = $this->setting_model->getCurrentSessionName();
        $this->start_month = $this->setting_model->getStartMonth();
        $this->userdata = $this->customlib->getUserData();
    }

    public function migrateLessonAndTopic($old_session, $new_session)
    {
        $this->db->trans_start();
        $this->db->trans_strict(true);

        /*
         * STEP 1: BUILD subject_group_subject MAP
         */
        $old_sgs = $this->db->where('session_id', $old_session)
            ->get('subject_group_subjects')->result_array();

        $new_sgs = $this->db->where('session_id', $new_session)
            ->get('subject_group_subjects')->result_array();

        $sgs_map = [];
        foreach ($old_sgs as $old) {
            foreach ($new_sgs as $new) {

                $old_name = $this->db->where('id', $old['subject_group_id'])
                ->get('subject_groups')->row_array();

                $new_name = $this->db->where('name', $old_name['name'])
                ->where('session_id', $new_session)
                ->get('subject_groups')->row_array();


                if ($old['subject_id'] == $new['subject_id'] && $new['subject_group_id'] == $new_name['id']) {
                    $sgs_map[$old['id']] = $new['id'];
                    break;
                }
            }
        }

        /*
         * STEP 2: BUILD subject_group_class_sections MAP
         */
        $old_sec = $this->db->where('session_id', $old_session)
            ->get('subject_group_class_sections')->result_array();

        $new_sec = $this->db->where('session_id', $new_session)
            ->get('subject_group_class_sections')->result_array();

        $sec_map = [];
        foreach ($old_sec as $old) {
            foreach ($new_sec as $new) {
                if ($old['class_section_id'] == $new['class_section_id']) {
                    $sec_map[$old['id']] = $new['id'];
                    break;
                }
            }
        }

        /*
         * STEP 3: MIGRATE LESSON
         */
        $lessons = $this->db->where('session_id', $old_session)
            ->get('lesson')->result_array();

        $lesson_map = [];

        foreach ($lessons as $lesson) {

            if (
                !isset($sgs_map[$lesson['subject_group_subject_id']]) ||
                !isset($sec_map[$lesson['subject_group_class_sections_id']])
            ) {
                continue; // SAFELY SKIP
            }

            $old_lesson_id = $lesson['id'];
            unset($lesson['id']);

            $lesson['session_id'] = $new_session;
            $lesson['subject_group_subject_id']
                = $sgs_map[$lesson['subject_group_subject_id']];
            $lesson['subject_group_class_sections_id']
                = $sec_map[$lesson['subject_group_class_sections_id']];
            $lesson['name'] = $lesson['name'];
            $lesson['created_at'] = date('Y-m-d H:i:s');


            $this->db->insert('lesson', $lesson);
            $lesson_map[$old_lesson_id] = $this->db->insert_id();
        }

        /*
         * STEP 4: MIGRATE TOPIC
         */
        $topics = $this->db->where('session_id', $old_session)
            ->get('topic')->result_array();

        $topic_batch = [];

        foreach ($topics as $topic) {

            if (!isset($lesson_map[$topic['lesson_id']])) {
                continue;
            }

            unset($topic['id']);
            $topic['session_id'] = $new_session;
            $topic['lesson_id']  = $lesson_map[$topic['lesson_id']];

            // 🔥 RESET COMPLETION DATA (CRITICAL)
            $topic['status'] = 0;
            $topic['complete_date'] = '0000-00-00';
            $topic['name']       = $topic['name'];
            $topic['created_at'] = date('Y-m-d H:i:s');

            $topic_batch[] = $topic;
        }

        if (!empty($topic_batch)) {
            $this->db->insert_batch('topic', $topic_batch);
        }

        $this->db->trans_complete();

        return $this->db->trans_status();
    }



    public function add_lesson($data)
    {


        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id']) && $data['id'] != '') {

            $this->db->where('id', $data['id']);
            $query = $this->db->update('lesson', $data);
            $insert_id = $data['id'];
            $message = UPDATE_RECORD_CONSTANT . " On lesson id " . $insert_id;
            $action = "Update";
            $record_id = $insert_id;
        } else {
            $this->db->insert('lesson', $data);
            $insert_id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On lesson id " . $insert_id;
            $action = "Insert";
            $record_id = $insert_id;
        }





        $this->log($message, $record_id, $action);




        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return $insert_id;
        }
    }

    public function getlessonBysubjectid($sub_id, $getlessonBysubjectid)
    {
        return $this->db->select('*')->from('lesson')->where('subject_group_subject_id', $sub_id)->where('subject_group_class_sections_id', $getlessonBysubjectid)->get()->result_array();
    }

    public function getlessonBylessonid($lesson_id)
    {
        return $this->db->select('*')->from('lesson')->where('id', $lesson_id)->get()->result_array();
    }

    public function getlessonBysubjectidedit($sub_id, $subject_group_class_sections_id)
    {
        return $this->db->select('*')->from('lesson')->where('subject_group_subject_id', $sub_id)->where('subject_group_class_sections_id', $subject_group_class_sections_id)->get()->result_array();
    }

    public function get_subjectNameBySubjectGroupSubjectId($subject_group_subject_id)
    {
        return $this->db->select('*')->from('subject_group_subjects')->join("subjects", "subjects.id = subject_group_subjects.subject_id")->where('subject_group_subjects.id', $subject_group_subject_id)->get()->row_array();
    }

    public function getlessonBysubjectgroupsubjectid_name_subjectgroupclasssectionsId($subject_group_subject_id, $name, $subject_group_class_sectionsId)
    {
        return $this->db->select('*')->from('lesson')->where('subject_group_subject_id', $subject_group_subject_id)->where('subject_group_class_sections_id', $subject_group_class_sectionsId)->where('name', $name)->get()->row_array();
    }

    public function get_subject_group_class_sections_id_new($subject_group_id, $subject_group_class_sections_id)
    {
        return $this->db->select('*')->from('subject_group_class_sections')->where('subject_group_id', $subject_group_id)->where('id!=', $subject_group_class_sections_id)->get()->row_array();
    }

    //=======================topic==========================

    public function add_topic($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id']) && $data['id'] != '') {
            $this->db->where('id', $data['id']);
            $this->db->update('topic', $data);

            $message = UPDATE_RECORD_CONSTANT . " On topic id " . $data['id'];
            $insert_id = $data['id'];
            $action = "Update";
            $record_id = $data['id'];
        } else {
            $this->db->insert('topic', $data);
            $insert_id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On topic id " . $insert_id;
            $action = "Insert";
            $record_id = $insert_id;
        }

        $this->log($message, $record_id, $action);
        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return $insert_id;
        }
    }

    // public function gettopicBylessonid($lessonid, $session)
    // {
    //     return $this->db->select('*')->from('topic')->where('lesson_id', $lessonid)->where('session_id', $session)->get()->result_array();
    // }

    public function gettopicBylessonid($lessonid, $session)
    {
        return $this->db
            ->select('
                t.*,
                ta.id as attainment_id,
                ta.teaching_tool,
                ta.assessment_tools,
                ta.skill_verb,
                ta.desired_attainment_percentage,
                ta.student_attainment_percentage,
                ta.remedial_action
            ')
            ->from('topic t')
            ->join(
                'topic_attainments ta',
                'ta.topic_id = t.id AND ta.session_id = ' . $session,
                'left'
            )
            ->where('t.lesson_id', $lessonid)
            ->where('t.session_id', $session)
            ->get()
            ->result_array();
    }


    public function gettopic($session, $id = null)
    {

        $this->db->select('topic.*,subject_groups.name as sgname,subjects.name as subname,sections.section as sname,sections.id as sectionid,subject_groups.id as subjectgroupsid,subjects.id as subjectid,class_sections.id as csectionid,classes.class as cname,classes.id as classid,lesson.name as lessonname,lesson.subject_group_class_sections_id,lesson.subject_group_subject_id')->from('topic');

        if ($id != null) {
            $this->db->where('topic.lesson_id', $id);
        }
        $this->db->where('topic.session_id', $session);

        $this->db->join("lesson", "lesson.id = topic.lesson_id");
        $this->db->join("subject_group_subjects", "subject_group_subjects.id = lesson.subject_group_subject_id");
        $this->db->join("subject_groups", "subject_groups.id = subject_group_subjects.subject_group_id");
        $this->db->join("subjects", "subjects.id = subject_group_subjects.subject_id");
        $this->db->join("subject_group_class_sections", "subject_group_class_sections.id = lesson.subject_group_class_sections_id", 'inner');
        $this->db->join("class_sections", "class_sections.id = subject_group_class_sections.class_section_id");

        $this->db->join("sections", "sections.id = class_sections.section_id");
        $this->db->join("classes", "classes.id = class_sections.class_id");
        $this->db->group_by("lesson.subject_group_subject_id");
        $this->db->group_by("topic.lesson_id");

        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function deletetopic($id, $session)
    {
        $this->db->where("id", $id)->where("session_id", $session)->delete('topic');
    }

    public function deletetopicbulk($id, $session)
    {
        $this->db->where("lesson_id", $id)->where("session_id", $session)->delete('topic');
    }

    public function changeTopicStatus($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $data['id']);
        $query = $this->db->update('topic', $data);

        $message = UPDATE_RECORD_CONSTANT . " On  topic id " . $data['id'];
        $action = "Update";
        $record_id = $data['id'];
        $this->log($message, $record_id, $action);
        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return true;
        }
    }

    //==========================syllabus============================

    public function add_syllabus($data)
    {
        //$this->db->trans_start(); # Starting Transaction
        //$this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================

        if (isset($data['id']) && $data['id'] > 0) {
            $this->db->where('id', $data['id']);
            $this->db->update('subject_syllabus', $data);
            $insert_id = $data['id'];
            $message = UPDATE_RECORD_CONSTANT . " On Subject Syllabus id " . $insert_id;
            $action = "Update";
            $record_id = $insert_id;

            return $record_id;
        } else {

            $this->db->insert('subject_syllabus', $data);
            $insert_id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On Subject Syllabus id " . $insert_id;
            $action = "Insert";
            $record_id = $insert_id;
            //$this->log($message, $record_id, $action);

            return $this->db->insert_id();
        }

        //======================Code End==============================
        //	$this->db->trans_complete(); # Completing transaction
        /* Optional */

        //if ($this->db->trans_status() === false) {
        # Something went wrong.
        //	$this->db->trans_rollback();
        //	return false;
        //} else {
        //	return $insert_id;
        //}
    }

    public function save_obe_outcomes($data)
    {
        $this->db->insert('obe_subject_syllabus_outcomes', $data);
    }

    public function getTopicOutcomes($session_id, $topic_id)
    {
        return $this->db
            ->where('session_id', $session_id)
            ->where('topic_id', $topic_id)
            ->order_by('id', 'ASC')
            ->get('obe_subject_syllabus_outcomes')
            ->result_array();
    }


    public function add_extra_syllabus($data) {
        
        $this->db->insert('subject_syllabus_without_topic', $data);
        
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }
    

    public function update_syllabus($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $data['id']);
        $query = $this->db->update('subject_syllabus', $data);

        $message = UPDATE_RECORD_CONSTANT . " On  Subject Syllabus id " . $data['id'];
        $action = "Update";
        $record_id = $data['id'];
        $this->log($message, $record_id, $action);
        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return true;
        }
    }

    public function get($session, $id = null, $subject_group_subject_id = null)
    {

        $this->db->select('lesson.*,subject_groups.name as sgname,subjects.name as subname,sections.section as sname,sections.id as sectionid,subject_groups.id as subjectgroupsid,subjects.id as subjectid,class_sections.id as csectionid,classes.class as cname,classes.id as classid')->from('lesson');

        if ($id != null) {
            $this->db->where('lesson.subject_group_class_sections_id', $id);
        }
        if ($subject_group_subject_id != null) {
            $this->db->where('subject_group_subjects.id', $subject_group_subject_id);
        }


        $this->db->where('lesson.session_id', $session);

        $this->db->join("subject_group_subjects", "subject_group_subjects.id = lesson.subject_group_subject_id");
        $this->db->join("subject_groups", "subject_groups.id = subject_group_subjects.subject_group_id");
        $this->db->join("subjects", "subjects.id = subject_group_subjects.subject_id");
        $this->db->join("subject_group_class_sections", "subject_group_class_sections.id = lesson.subject_group_class_sections_id", 'inner');
        $this->db->join("class_sections", "class_sections.id = subject_group_class_sections.class_section_id");
        $this->db->join("sections", "sections.id = class_sections.section_id");
        $this->db->join("classes", "classes.id = class_sections.class_id");
        $this->db->group_by("lesson.subject_group_subject_id");
        $this->db->group_by("lesson.subject_group_class_sections_id");
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function getsubject_group_class_sectionsId($class_id, $section_id, $subject_group_id)
    {

        if ($section_id === "0") {
            $sql = "SELECT subject_groups.name, subject_group_class_sections.* from subject_group_class_sections
            INNER JOIN class_sections on class_sections.id=subject_group_class_sections.class_section_id
             INNER JOIN subject_groups on subject_groups.id=subject_group_class_sections.subject_group_id
              WHERE class_sections.class_id=" . $this->db->escape($class_id)

                . " and subject_groups.id=" . $this->db->escape($subject_group_id)
                . "and subject_groups.session_id=" . $this->db->escape($this->current_session)
                . " ORDER by subject_groups.id DESC";
        } else {
            $sql = "SELECT subject_groups.name, subject_group_class_sections.* from subject_group_class_sections
            INNER JOIN class_sections on class_sections.id=subject_group_class_sections.class_section_id
             INNER JOIN subject_groups on subject_groups.id=subject_group_class_sections.subject_group_id
              WHERE class_sections.class_id=" . $this->db->escape($class_id)
                . " and class_sections.section_id=" . $this->db->escape($section_id)
                . " and subject_groups.id=" . $this->db->escape($subject_group_id)
                . "and subject_groups.session_id=" . $this->db->escape($this->current_session)
                . " ORDER by subject_groups.id DESC";
        }


        $query = $this->db->query($sql);

        return $query->row_array();
    }

    public function getlesson($subject_group_subjectid, $subject_group_class_sections_id, $session)
    {
        return $this->db->select('*')->from('lesson')->where('lesson.subject_group_subject_id', $subject_group_subjectid)->where("session_id", $session)->where('subject_group_class_sections_id', $subject_group_class_sections_id)->get()->result_array();
    }

    public function deletelesson($id, $session)
    {

        $this->db->trans_start();
        $this->db->trans_strict(false);

        $this->db->where("id", $id)->where("session_id", $session);
        $query = $this->db->delete('lesson');

        if ($query) {
            $message = DELETE_RECORD_CONSTANT . " On lesson id " . $id;
            $action = "Delete";
            $record_id = $id;

            $this->log($message, $record_id, $action);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        }

        return true;
    }

    public function deletelessonbulk($id, $session, $subject_group_subject_id)
    {
        $this->db->where("subject_group_class_sections_id", $id)->where("subject_group_subject_id", $subject_group_subject_id)->where("session_id", $session)->delete('lesson');
    }

    public function get_subjectstatus($id, $subject_group_class_section_id)
    {

        $sql = "SELECT COUNT(CASE WHEN topic.status = 0 then 1 ELSE NULL END) as 'incomplete', COUNT(CASE WHEN topic.status = 1 then 1 ELSE NULL END) as 'complete',count('*') as total FROM `lesson` inner join topic on lesson.id=topic.lesson_id WHERE lesson.subject_group_class_section_id=" . $this->db->escape($subject_group_class_section_id) . "and lesson.subject_group_subject_id=" . $this->db->escape($id);

        $query = $this->db->query($sql);
        return $query->result();
    }

    public function get_subject_syllabus($subject_group_subject_id, $subject_id, $time_from, $time_to, $new_date, $staddID, $session)
    {

        $this->db->select('subject_syllabus.*,topic.name as tname')
            ->join("topic", "topic.id = subject_syllabus.topic_id")
            ->from('subject_syllabus')
            ->where("subject_syllabus.subject_group_subject_id", $subject_group_subject_id)
            ->where("subject_syllabus.subject_id", $subject_id)
            ->where("subject_syllabus.time_from", $time_from)
            ->where("subject_syllabus.time_to", $time_to)
            ->where("subject_syllabus.date", $new_date)
            ->where("subject_syllabus.created_by", $staddID)
            ->where("subject_syllabus.session_id", $session);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function ifclassteacher($class_id, $section_id, $staff_id, $subject_group_id, $subject_group_subject_id)
    {
        $class_teacher = $this->db->select('*')->from('class_teacher')->where('class_id', $class_id)->where('section_id', $section_id)->where('staff_id', $staff_id)->get()->num_rows();
        if ($class_teacher > 0) {
            return 1;
        } else {
            $subject_teacher = $this->db->select('*')->from('subject_timetable')->where('class_id', $class_id)->where('section_id', $section_id)->where('staff_id', $staff_id)->where('subject_group_id', $subject_group_id)->where('subject_group_subject_id', $subject_group_subject_id)->get()->num_rows();

            if ($subject_teacher > 0) {
                return 1;
            } else {
                return 0;
            }
        }
    }

    public function getTopicAttainmentById($topic_id , $session)
    {
        $this->db->select('*');
        $this->db->from('topic_attainments');
        $this->db->where('topic_id', $topic_id);
        $this->db->where('session_id', $session);
        return $this->db->get()->row_array();
    }

    public function updateTopicAttainment($topic_id, $data)
    {
        // $this->db->where('id', $topic_id);
        // $this->db->update('topic', $data);

        $this->db->trans_start(); # Starting Transaction    

        $exists = $this->db
        ->where('topic_id', $data['topic_id'])
        ->where('session_id', $data['session_id'])
        ->get('topic_attainments')
        ->row();

        if ($exists) {
            $this->db->where('id', $exists->id)->update('topic_attainments', $data);
        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            $this->db->insert('topic_attainments', $data);
        }
        $this->db->trans_complete(); # Complete Transaction
    }



    public function gettopiclist($session)
    {

        $this->datatables
            ->select('topic.*,subject_groups.name as sgname,subjects.name as subname,sections.section as sname,sections.id as sectionid,subject_groups.id as subjectgroupsid,subjects.id as subjectid,class_sections.id as csectionid,classes.class as cname,classes.id as classid,lesson.name as lessonname,lesson.subject_group_class_sections_id,lesson.subject_group_subject_id')
            ->searchable('classes.class,sections.section,subjects.name,subject_groups.name,classes.class,lesson.name')
            ->orderable('cname,sname,sgname,subname,lessonname," " ')
            ->join("lesson", "lesson.id = topic.lesson_id")
            ->join("subject_group_subjects", "subject_group_subjects.id = lesson.subject_group_subject_id")
            ->join("subject_groups", "subject_groups.id = subject_group_subjects.subject_group_id")
            ->join("subjects", "subjects.id = subject_group_subjects.subject_id")
            ->join("subject_group_class_sections", "subject_group_class_sections.id = lesson.subject_group_class_sections_id", 'inner')
            ->join("class_sections", "class_sections.id = subject_group_class_sections.class_section_id")
            ->join("sections", "sections.id = class_sections.section_id")
            ->join("classes", "classes.id = class_sections.class_id")
            ->where('topic.session_id', $session)
            ->group_by("lesson.subject_group_subject_id")
            ->group_by("topic.lesson_id")
            ->from('topic');


        // if ($this->userdata['school_divison_id'] != 5) {
        //     $this->datatables->where('subject_groups.school_divison_id', $this->userdata['school_divison_id']);
        // }

        if(in_array($this->userdata['school_divison_id'],[1,2,3,4])){

            $this->datatables->where('subject_groups.school_divison_id', $this->userdata['school_divison_id']);

        }

        elseif (in_array($this->userdata['school_divison_id'], [6, 7, 8])) {
                
            $school_division_filter = ($this->userdata['school_divison_id'] == 6) ? [1, 2] : (($this->userdata['school_divison_id'] == 7) ? [2, 3] : [1, 2, 3]);
            $this->datatables->where_in('subject_groups.school_divison_id', $school_division_filter);
                               
        }
        return $this->datatables->generate('json');
    }
    public function getlessonlist($session, $id = null)
    {

        $this->datatables
            ->select('lesson.*,subject_groups.name as sgname,subjects.name as subname,sections.section as sname,sections.id as sectionid,subject_groups.id as subjectgroupsid,subjects.id as subjectid,class_sections.id as csectionid,classes.class as cname,classes.id as classid')
            ->searchable('classes.class,sections.section,subject_groups.name,subjects.name,lesson.name')
            ->orderable('classes.class,sections.section,subject_groups.name,subjects.name,lesson.name')
            ->join("subject_group_subjects", "subject_group_subjects.id = lesson.subject_group_subject_id")
            ->join("subject_groups", "subject_groups.id = subject_group_subjects.subject_group_id")
            ->join("subjects", "subjects.id = subject_group_subjects.subject_id")
            ->join("subject_group_class_sections", "subject_group_class_sections.id = lesson.subject_group_class_sections_id", 'inner')
            ->join("class_sections", "class_sections.id = subject_group_class_sections.class_section_id")
            ->join("sections", "sections.id = class_sections.section_id")
            ->join("classes", "classes.id = class_sections.class_id")
            ->where('lesson.session_id', $session)
            ->group_by("lesson.subject_group_subject_id")
            ->group_by("lesson.subject_group_class_sections_id")
            ->from('lesson');


        // if ($this->userdata['school_divison_id'] != 5) {
        //     $this->datatables->where('subject_groups.school_divison_id', $this->userdata['school_divison_id']);
        // }

        if(in_array($this->userdata['school_divison_id'],[1,2,3,4])){

            $this->datatables->where('subject_groups.school_divison_id', $this->userdata['school_divison_id']);

        }

        elseif (in_array($this->userdata['school_divison_id'], [6, 7, 8])) {
                
            $school_division_filter = ($this->userdata['school_divison_id'] == 6) ? [1, 2] : (($this->userdata['school_divison_id'] == 7) ? [2, 3] : [1, 2, 3]);
            $this->datatables->where_in('subject_groups.school_divison_id', $school_division_filter);
                               
        }


        return $this->datatables->generate('json');
    }
}
