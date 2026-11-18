<?php



if (!defined('BASEPATH')) {

    exit('No direct script access allowed');
}

class  Daycare_service_model extends MY_Model
{

    public function __construct()
    {

        parent::__construct();

        $this->current_session = $this->setting_model->getCurrentSession();
    }

    public function insert_service($data)
    {

        if (isset($data['id'])) {

            $this->db->where('id', $data['id']);

            $this->db->update('daycare_services', $data);
        } else {

            return $this->db->insert('daycare_services', $data);
        }
    }

    public function get_services($id = null)
    {

        if ($id != null) {

            $query = $this->db->get_where('daycare_services', ['id' => $id]);

            return $query->row();
        } else {

            $query = $this->db->get('daycare_services');

            return $query->result();
        }
    }


    public function service_name_exists($str)
    {

        $service_name = $this->security->xss_clean($str);

        $res = $this->check_data_exists($service_name);



        if ($res) {

            $pre_service_name = $this->input->post('pre_service_name');

            if (isset($pre_service_name)) {

                if ($res->service_name == $pre_service_name) {

                    return true;
                }
            }

            $this->form_validation->set_message('service_name_exists', 'Record already exists');

            return false;
        } else {

            return true;
        }
    }


    public function check_data_exists($data)
    {

        $this->db->where('daycare_services', $data);

        $query = $this->db->get('daycare_services');

        if ($query->num_rows() > 0) {

            return $query->row();
        } else {

            return false;
        }
    }

    public function delete_service($id)
    {
        return $this->db->delete('daycare_services', ['id' => $id]);
    }


    public function add_activity($data)
    {
        //$this->db->trans_start(); # Starting Transaction
        //$this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================

        if (isset($data['id']) && $data['id'] > 0) {
            $this->db->where('id', $data['id']);
            $this->db->update('daycare_activities', $data);
            $insert_id = $data['id'];
            $message = UPDATE_RECORD_CONSTANT . " On Daycare Activity id " . $insert_id;
            $action = "Update";
            $record_id = $insert_id;

            return $record_id;
        } else {

            $this->db->insert('daycare_activities', $data);

            $insert_id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On Daycare Activity id " . $insert_id;
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

    public function delete_student_activity($activity_id){

         $this->db->where('daycare_activity_id', $activity_id)->delete('student_activity');

    }


    public function getTodayActivitiesByStudent($student_id)
    {
        $this->db->select('
            student_activity.*, 
            daycare_activities.description as activity_description, 
            daycare_activities.time,
            daycare_activities.daycare_service_id,
            daycare_activities.assigned_staff,
            daycare_activities.meal,
            daycare_activities.quantity,
            daycare_services.service_name,
            daycare_services.service_description
        ');
        $this->db->from('student_activity');
        $this->db->join(
            'daycare_activities',
            'student_activity.daycare_activity_id = daycare_activities.id AND student_activity.daycare_activity_id IS NOT NULL',
            'left'
        );
        $this->db->join(
            'daycare_services',
            'daycare_activities.daycare_service_id = daycare_services.id',
            'left'
        );
        $this->db->where('student_activity.student_id', $student_id);
        $this->db->where('DATE(student_activity.date)', date('Y-m-d'));
        $this->db->order_by('student_activity.date', 'ASC');

        $query = $this->db->get();
        return $query->result_array();
    }


    public function getActivitiesByStudentAndDate($student_id, $date)
    {
        $this->db->select('
            student_activity.*, 
            daycare_activities.description as activity_description, 
            daycare_activities.time,
            daycare_activities.daycare_service_id,
            daycare_activities.assigned_staff,
            daycare_activities.meal,
            daycare_activities.quantity,
            daycare_services.service_name,
            daycare_services.service_description
        ');
        $this->db->from('student_activity');
        $this->db->join(
            'daycare_activities',
            'student_activity.daycare_activity_id = daycare_activities.id AND student_activity.daycare_activity_id IS NOT NULL',
            'left'
        );
        $this->db->join(
            'daycare_services',
            'daycare_activities.daycare_service_id = daycare_services.id',
            'left'
        );
        $this->db->where('student_activity.student_id', $student_id);
        $this->db->where('DATE(student_activity.date)', $date);
        // $this->db->order_by('student_activity.date', 'ASC');
        $this->db->order_by('daycare_activities.time', 'ASC');


        return $this->db->get()->result_array();
    }





    public function insert_activity_children($children_data) {
        $this->db->insert_batch('student_activity', $children_data); // Bulk insert into activity_children table
    }
}
