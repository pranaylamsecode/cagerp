<?php



if (!defined('BASEPATH')) {

    exit('No direct script access allowed');
}

class  Daycare_model extends MY_Model
{

    public function __construct()
    {

        parent::__construct();

        $this->current_session = $this->setting_model->getCurrentSession();
    }


    public function getclasslist($id = null, $classteacher = null)
    {

        $this->db->select()->from('classes')->order_by("CASE WHEN class='Playhouse' THEN 0 ELSE 1 END", 'ASC');


        if ($id != null) {

            $this->db->where('id', $id);
        } else {

            $this->db->order_by('id');
        }

        $this->db->where('school_divison_id=', 1);

        $query = $this->db->get();

        if ($id != null) {

            $classlist = $query->row_array();
        } else {

            $classlist = $query->result_array();
        }


        return $classlist;
    }



    public function get($id = null)
    {

        $query = "SELECT daycare_students.id as `daycare_student_id`,daycare_students.enrollment_no,
       daycare_students.student_type,
       students.id,students.admission_no,students.firstname,students.lastname,
       students.image,students.gender,students.guardian_name,students.guardian_phone,
       check_in_out.id as `check_in_out_id`,
       check_in_out.check_in_time as `check_in_time`,
       check_in_out.guardian_name as `pick_up_guardian_name`,
       check_in_out.check_out_time as `check_out_time`,
       check_in_out.is_verified,
       check_in_out.guardian_pic,
       check_in_out.status as `status`,
       null as `teacher_name`,null as `teacher_email`,null as `teacher_sex`,
       null as `teacher_phone`,students.middlename FROM `daycare_students` 
       INNER JOIN students on daycare_students.student_id = students.id 
       LEFT JOIN (SELECT * FROM check_in_out
       WHERE id IN (SELECT MAX(id) FROM check_in_out GROUP BY student_id)) AS check_in_out
       ON check_in_out.student_id = daycare_students.student_id 
       WHERE daycare_students.student_type='student' 
       and students.is_active = 'yes'";

        // if (!empty($id)) {
        //     $query .= " AND daycare_students.student_id = " . $this->db->escape($id);
        // }

        if (!empty($id)) {
            $query .= "AND daycare_students.student_id = " . $this->db->escape($id) . "  AND DATE(check_in_out.check_in_time) = " . $this->db->escape(date('Y-m-d'));
        }

        $query .= "ORDER BY daycare_students.enrollment_no ASC";

        $query = $this->db->query($query);
        return $query->result_array();
    }


    public function get_checked_in_students($id = null)
    {

        $query = "SELECT daycare_students.id as `daycare_student_id`,daycare_students.enrollment_no,
       daycare_students.student_type,
       students.id,students.admission_no,students.firstname,students.lastname,
       students.image,students.gender,students.guardian_name,students.guardian_phone,
       check_in_out.id as `check_in_out_id`,
       check_in_out.check_in_time as `check_in_time`,
       check_in_out.guardian_name as `pick_up_guardian_name`,
       check_in_out.check_out_time as `check_out_time`,
       check_in_out.is_verified,
       check_in_out.guardian_pic,
       check_in_out.status as `status`,
       null as `teacher_name`,null as `teacher_email`,null as `teacher_sex`,
       null as `teacher_phone`,students.middlename FROM `daycare_students` 
       INNER JOIN students on daycare_students.student_id = students.id 
       LEFT JOIN (SELECT * FROM check_in_out
       WHERE id IN (SELECT MAX(id) FROM check_in_out GROUP BY student_id)) AS check_in_out
       ON check_in_out.student_id = daycare_students.student_id 
       WHERE daycare_students.student_type='student' 
       and check_in_out.status='Checked In'
       and students.is_active = 'yes' AND DATE(check_in_out.check_in_time) = ". $this->db->escape(date('Y-m-d'));
       
       $query .= "ORDER BY daycare_students.enrollment_no ASC";

       $query = $this->db->query($query);
       return $query->result_array();
    }



    public function add($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('daycare_students', $data);
            $message = UPDATE_RECORD_CONSTANT . " On  daycare students id " . $data['id'];
            $action = "Update";
            $record_id = $insert_id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('daycare_students', $data);
            $insert_id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On daycare students id " . $insert_id;
            $action = "Insert";
            $record_id = $insert_id;
            $this->log($message, $record_id, $action);
        }
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
        // return $insert_id;
    }


    function check_data_exists($data)
    {
        $this->db->where('enrollment_no', $data['enrollment_no']);
        $query = $this->db->get('daycare_students');
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }


    function surrender($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('daycare_students');
        $message = DELETE_RECORD_CONSTANT . " On daycare students id " . $id;
        $action = "Delete";
        $record_id = $id;
        $this->log($message, $record_id, $action);

        // $this->db->where('member_id', $id);
        // $this->db->delete('book_issues');
        // $message = DELETE_RECORD_CONSTANT . " On book issues id " . $id;
        // $action = "Delete";
        // $record_id = $id;
        // $this->log($message, $record_id, $action);
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


    public function insertCheckInOut($data , $activity)
    {

        $this->db->insert('student_activity', $activity);

        return $this->db->insert('check_in_out', $data);
    }

    public function updateGuardianName($student_id, $guardian_name, $guardian_pic)
    {
        $this->db->where('student_id', $student_id);
        $this->db->where('status', 'Checked In');
        $this->db->update('check_in_out', [
            'guardian_name' => $guardian_name,
            'guardian_pic' => $guardian_pic,
            'is_verified' => null,

        ]);
    }

    public function updateStatus($student_id, $data)
    {
        $this->db->where('student_id', $student_id);
        $this->db->where('status', 'Checked In');
        $this->db->update('check_in_out', $data);
    }

    public function updateCheckOut($student_id, $check_out_time)
    {
        $this->db->where('student_id', $student_id);
        $this->db->where('status', 'Checked In');
        $this->db->update('check_in_out', [

            'check_out_time' => $check_out_time,
            'status' => 'Checked Out',
        ]);


        $this->db->insert('student_activity', [
            'student_id' => $student_id,
            'date'   => $check_out_time,
            'description'=>'Left Day Care Center',

        ]);
    }

    public function searchFullText($searchterm, $active)

    {

        $i             = 1;

        $custom_fields = $this->customfield_model->get_custom_fields('staff', 1);

        $field_k_array = array();

        $join_array    = "";

        if (!empty($custom_fields)) {

            foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {

                $tb_counter = "table_custom_" . $i;

                array_push($field_k_array, '`table_custom_' . $i . '`.`field_value` as `' . $custom_fields_value->name . '`');

                $join_array .= " LEFT JOIN `custom_field_values` as `" . $tb_counter . "` ON `staff`.`id` = `" . $tb_counter . "`.`belong_table_id` AND `" . $tb_counter . "`.`custom_field_id` = " . $custom_fields_value->id;



                $i++;
            }
        }

        $field_var = count($field_k_array) > 0 ? "," . implode(',', $field_k_array) : "";


        $query = "SELECT `staff`.*, `staff_roles`.`school_divison_id` as `school_divison_id`,
         `staff_designation`.`designation` as `designation`, `department`.`department_name` as `department`,
         `roles`.`name` as user_type " . $field_var . "  
         FROM `staff` " . $join_array . " 
         LEFT JOIN `staff_designation` ON `staff_designation`.`id` = `staff`.`designation` 
         LEFT JOIN `staff_roles` ON `staff_roles`.`staff_id` = `staff`.`id` 
         LEFT JOIN `roles` ON `staff_roles`.`role_id` = `roles`.`id` 
         LEFT JOIN `department` ON `department`.`id` = `staff`.`department` 
         WHERE `staff`.`is_active` = '$active' 
         AND `staff_roles`.`daycare_alloted` = 'Yes'
         AND (CONCAT(`staff`.`name`,' ',`staff`.`surname`) LIKE '%" . $this->db->escape_like_str($searchterm) . "%' ESCAPE '!' 
         OR `staff`.`surname` LIKE '%" . $this->db->escape_like_str($searchterm) . "%' ESCAPE '!' 
         OR `staff`.`employee_id` LIKE '%" . $this->db->escape_like_str($searchterm) . "%' ESCAPE '!' 
         OR `staff`.`local_address` LIKE '%" . $this->db->escape_like_str($searchterm) . "%' ESCAPE '!' 
         OR `staff`.`contact_no` LIKE '%" . $this->db->escape_like_str($searchterm) . "%' ESCAPE '!' 
         OR `staff`.`email` LIKE '%" . $this->db->escape_like_str($searchterm) . "%' ESCAPE '!' 
         OR `roles`.`name` LIKE '%" . $this->db->escape_like_str($searchterm) . "' ESCAPE '!') 
         ORDER BY `staff`.`employee_id`";

        $query = $this->db->query($query);

        return $query->result_array();
    }


    // public function getGroupedActivitiesByDate()
    // {
    //     $this->db->select('
    //         daycare_activities.*,
    //         staff.name AS staff_name,staff.surname,
    //         daycare_services.service_name AS service_name
    //     ');
    //     $this->db->from('daycare_activities');
    //     $this->db->join('staff', 'staff.id = daycare_activities.assigned_staff', 'left');
    //     $this->db->join('daycare_services', 'daycare_services.id = daycare_activities.daycare_service_id', 'left');
    //     $this->db->order_by('daycare_activities.date', 'DESC');
    //     $this->db->order_by('daycare_activities.time', 'ASC');
        
    //     $query = $this->db->get();
    //     $results = $query->result();
       
    //     $grouped = [];
    //     foreach ($results as $row) {
    //         $grouped[$row->date][] = $row;
    //     }

    //     return $grouped;
    // }



    public function getGroupedDaycareActivities($date = null)
    {
        $this->db->select('
            da.*, 
            ds.service_name AS service_name, 
            s.name AS staff_name, 
            s.surname AS staff_surname, 
            GROUP_CONCAT(sa.student_id) AS selected_children_ids
        ');
        $this->db->from('daycare_activities da');
        $this->db->join('daycare_services ds', 'ds.id = da.daycare_service_id', 'left');
        $this->db->join('staff s', 's.id = da.assigned_staff', 'left');
        $this->db->join('student_activity sa', 'sa.daycare_activity_id = da.id AND sa.daycare_activity_id IS NOT NULL', 'left');
        
        if(!empty($date)){
            $this->db->where('da.date', $date);
        }
        
        $this->db->group_by('da.id');
        $this->db->order_by('da.date', 'DESC');
        $this->db->order_by('da.time', 'ASC');

        $result = $this->db->get()->result();

        // return $result;

        $grouped = [];
        foreach($result as $row){

           $grouped [$row->date] [] = $row;

        }

        return $grouped;
    }

}
