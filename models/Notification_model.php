<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Notification_model extends MY_Model
{

    public $current_session;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * This funtion takes id as a parameter and will fetch the record.
     * If id is not provided, then it will fetch all the records form the table.
     * @param int $id
     * @return mixed
     */
    public function get($id = null)
    {

        $userdata = $this->customlib->getUserData();
        $role_id = $userdata["role_id"];
        $school_divison_id = $userdata["school_divison_id"];
        
        if($id == null) {
            
            $sql = "SELECT send_notification.*, notification_roles.roles, 
            GROUP_CONCAT(send_notification.id) AS concatenated_send_notification_ids,school_divison.divison FROM send_notification 
            LEFT JOIN ( SELECT send_notification_id, GROUP_CONCAT(role_id) AS roles 
            FROM notification_roles GROUP BY send_notification_id ) AS notification_roles 
            ON notification_roles.send_notification_id = send_notification.id   
            LEFT JOIN school_divison 
            ON send_notification.school_divison_id IS NOT NULL AND school_divison.id = send_notification.school_divison_id
            WHERE send_notification.is_active = 'yes'";


            if ($school_divison_id != 5 && $school_divison_id != null) {
                $sql .= " AND (send_notification.school_divison_id IS NULL 
                            OR send_notification.school_divison_id = " . $school_divison_id . ")";
            }

            $sql .= "GROUP BY IF(send_notification.school_divison_id IS NOT NULL, send_notification.school_divison_id, send_notification.id) 
            ORDER BY send_notification.id DESC ";

        }

      
        // if ($school_divison_id != 5)
        //     $sql .= "where send_notification.school_divison_id =" . $school_divison_id;

        if ($id != null) {

            $sql = "SELECT * from send_notification 
            left JOIN (SELECT send_notification_id,GROUP_CONCAT(role_id) as roles 
             FROM notification_roles  group by send_notification_id) as notification_roles 
             on notification_roles.send_notification_id = send_notification.id ";

            $sql .= "where send_notification.id =" . $id;
        }
             
        $query = $this->db->query($sql);
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function get1($id = null)
    {
        $userdata = $this->customlib->getUserData();
        $role_id = $userdata["role_id"];
        $school_divison_id = $userdata["school_divison_id"];

        if ($id == null) {

            $sql = "
            SELECT 
            sn.id,
            sn.title,
            sn.message,
            sn.publish_date,
            sn.date,
            sn.created_by,
            sn.created_id,
            sn.visible_student,
            sn.visible_staff,
            sn.visible_parent,
            sn.path,
            sn.school_divison_id,
            sd.divison,
            nr.roles,

            GROUP_CONCAT(c.class ORDER BY c.id) AS class_names,
            GROUP_CONCAT(sn.id) AS notice_ids

            FROM send_notification sn

            LEFT JOIN classes c 
                ON c.id = sn.class_id

            LEFT JOIN (
                SELECT 
                    send_notification_id,
                    GROUP_CONCAT(role_id) AS roles
                FROM notification_roles
                GROUP BY send_notification_id
            ) nr ON nr.send_notification_id = sn.id

            LEFT JOIN school_divison sd
                ON sd.id = sn.school_divison_id

            WHERE sn.is_active = 'yes'
            ";

            /**
             * ===============================
             * MULTI SCHOOL FILTER
             * ===============================
             */

            // Super Admin (5) → see all schools
            if ($school_divison_id != 5 && !empty($school_divison_id)) {

                $sql .= " AND (
                            sn.school_divison_id IS NULL
                            OR sn.school_divison_id = ".$this->db->escape($school_divison_id)."
                        )";
            }

            /**
             * ===============================
             * STAFF VISIBILITY FILTER
             * ===============================
             */

            $sql .= " AND sn.visible_staff = 'Yes' ";

            /**
             * IMPORTANT: GROUP BY NOTICE ONLY
             */
            $sql .= " GROUP BY 
                    sn.title,
                    sn.message,
                    sn.publish_date,
                    sn.school_divison_id
                    ORDER BY sn.id DESC";
        }

        // SINGLE RECORD
        if ($id != null) {

            $sql = "
            SELECT sn.*, nr.roles
            FROM send_notification sn
            LEFT JOIN (
                SELECT send_notification_id,
                    GROUP_CONCAT(role_id) AS roles
                FROM notification_roles
                GROUP BY send_notification_id
            ) nr ON nr.send_notification_id = sn.id
            WHERE sn.id = ".$this->db->escape($id);
        }

        $query = $this->db->query($sql);

        return ($id != null)
            ? $query->row_array()
            : $query->result_array();
    }

    public function getRole($arr)
    {

        $query = $this->db->where_in("id", $arr)->get("roles");
        return $query->result_array();
    }

    public function getUnreadStaffNotification($staffid = null, $role_id = null)
    {

        $userdata = $this->customlib->getUserData();
        $school_divison_id = $userdata['school_divison_id'];
        $sql = "select send_notification.*,GROUP_CONCAT(send_notification.id) AS concatenated_send_notification_ids,school_divison.divison from send_notification 
        INNER JOIN notification_roles on notification_roles.send_notification_id = send_notification.id
         left JOIN read_notification on read_notification.staff_id=" . $this->db->escape($staffid) . "
          and read_notification.notification_id = send_notification.id
          LEFT JOIN school_divison 
            ON send_notification.school_divison_id IS NOT NULL AND school_divison.id = send_notification.school_divison_id
           WHERE send_notification.created_id !=" . $this->db->escape($staffid) . " 
           and send_notification.is_active='yes'
           and send_notification.visible_staff='yes' 
           and read_notification.id IS NULL 
           and notification_roles.role_id=" . $this->db->escape($role_id) . " 
          ";


           if ($school_divison_id != 5 && $school_divison_id != null) {
            $sql .= " AND (send_notification.school_divison_id IS NULL 
                        OR send_notification.school_divison_id = " . $school_divison_id . ")";
        }

        $sql .= "GROUP BY IF(send_notification.school_divison_id IS NOT NULL, send_notification.school_divison_id, send_notification.id) 
        ORDER BY send_notification.id DESC ";


        $query = $this->db->query($sql);
        return $query->result();
    }

    public function getUnreadStudentNotification()
    {


        $sql = "select send_notification.* from send_notification  left JOIN read_notification on  read_notification.student_id=" . $this->customlib->getStudentSessionUserID() . " and read_notification.notification_id = send_notification.id WHERE  send_notification.visible_student='yes' and read_notification.id IS NULL  group by send_notification.id order by send_notification.id desc";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function getUnreadParentNotification()
    {
        $sql = "select send_notification.* from send_notification  left JOIN read_notification on  read_notification.notification_id = send_notification.id WHERE  send_notification.visible_parent='yes' and read_notification.id IS NULL   order by send_notification.id desc";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function getNotificationForStudent($studentid = null)
    {
        $date = date('Y-m-d');
        $query = $this->db->query("SELECT
send_notification.id,send_notification.title,send_notification.publish_date,send_notification.date,send_notification.message,send_notification.path,send_notification.class_id,send_notification.secid,
IF (read_notification.id IS NULL,'unread','read') as notification_id
FROM send_notification
LEFT JOIN read_notification ON send_notification.id = read_notification.notification_id and read_notification.student_id=" . $this->db->escape($studentid) . " where send_notification.visible_student='Yes' and send_notification.is_active='yes'  order by send_notification.publish_date desc");
        return $query->result_array();
    }

    public function getNotificationForParent($parentid = null)
    {
        $date = date('Y-m-d');
        $query = $this->db->query("SELECT
        send_notification.id,send_notification.title,send_notification.publish_date,send_notification.date,send_notification.message,
        send_notification.school_divison_id,GROUP_CONCAT(send_notification.id) AS concatenated_send_notification_ids,
        IF (read_notification.id IS NULL,'unread','read') as notification_id
        FROM send_notification
        LEFT JOIN read_notification ON send_notification.id = read_notification.notification_id and read_notification.parent_id=" . $this->db->escape($parentid) . " where send_notification.visible_parent='Yes' and send_notification.is_active='yes' GROUP BY IF(send_notification.school_divison_id IS NOT NULL, send_notification.school_divison_id, send_notification.id) order by send_notification.publish_date desc");
        return $query->result_array();
    }

    public function countUnreadNotificationStudent($studentid = null)
    {
        $date = date('Y-m-d');

        $query = $this->db->query("select * from (SELECT  IF (read_notification.id IS NULL,'unread','read') as notification_id FROM send_notification left JOIN read_notification ON send_notification.id = read_notification.notification_id and read_notification.student_id=" . $this->db->escape($studentid) . " where  send_notification.visible_student='Yes' and send_notification.publish_date <='" . $date . "') final where notification_id ='unread'");

        return $query->num_rows();
    }

    public function countUnreadNotificationParent($parentid = null)
    {
        $date = date('Y-m-d');
        $query = $this->db->query("select * from (SELECT  IF (read_notification.id IS NULL,'unread','read') as notification_id FROM send_notification LEFT JOIN read_notification ON send_notification.id = read_notification.notification_id and read_notification.parent_id=" . $this->db->escape($parentid) . " where  send_notification.visible_parent='Yes'  and send_notification.publish_date <='" . $date . "') final where notification_id ='unread'");
        return $query->num_rows();
    }

    /**
     * This function will delete the record based on the id
     * @param $id
     */
    public function remove($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('send_notification');
        $message = DELETE_RECORD_CONSTANT . " On send notification id " . $id;
        $action = "Delete";
        $record_id = $id;
        $this->log($message, $record_id, $action);
        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /* Optional */
        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            //return $return_value;
        }
    }

    /**
     * This function will take the post data passed from the controller
     * If id is present, then it will do an update
     * else an insert. One function doing both add and edit.
     * @param $data
     */
    public function add($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('send_notification', $data);
            $message = UPDATE_RECORD_CONSTANT . " On  send notification id " . $data['id'];
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
                //return $return_value;
            }
        } else {
            $this->db->insert('send_notification', $data);
            $id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On  send notification id " . $id;
            $action = "Insert";
            $record_id = $id;
            $this->log($message, $record_id, $action);

            //======================Code End==============================

            $this->db->trans_complete(); # Completing transaction
            /* Optional */

            if ($this->db->trans_status() === false) {
                # Something went wrong.
                $this->db->trans_rollback();
                return false;
            } else {
                //return $return_value;
            }
            return $id;
        }
    }

    public function insertBatch($data, $staff_roles, $delete_array = array())
    {



        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================

        if (isset($data['id'])) {
            $insert_id = $data['id'];
            $this->db->where('id', $data['id']);
            $this->db->update('send_notification', $data);
            $message = UPDATE_RECORD_CONSTANT . " On  send notification id " . $data['id'];
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
                //return $return_value;
            }
        } else {
            $this->db->insert('send_notification', $data);
            $insert_id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On send notification id " . $insert_id;
            $action = "Insert";
            $record_id = $insert_id;
            $this->log($message, $record_id, $action);
            //======================Code End==============================

            $this->db->trans_complete(); # Completing transaction
            /* Optional */

            if ($this->db->trans_status() === false) {
                # Something went wrong.
                $this->db->trans_rollback();
                return false;
            } else {
                //return $return_value;
            }
        }


        if (!empty($staff_roles)) {
            foreach ($staff_roles as $stf_role_key => $stf_role_value) {
                $staff_roles[$stf_role_key]['send_notification_id'] = $insert_id;
            }

            $this->db->insert_batch('notification_roles', $staff_roles);
        }
        if (!empty($delete_array)) {

            $this->db->where('send_notification_id', $insert_id);
            $this->db->where_in('role_id', $delete_array);
            $this->db->delete('notification_roles');
        }


        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {

            $this->db->trans_rollback();
            return FALSE;
        } else {

            $this->db->trans_commit();
            return TRUE;
        }
    }

    public function fetchSchoolDivisonId($classid)
    {

        $this->db->select('school_divison_id');
        $this->db->from('classes');
        $this->db->where('id', $classid);
        $query = $this->db->get();
        return $query->row()->school_divison_id;
    }

    public function insertBatch2($data, $staff_roles, $data_search_by_class = array())
    {

        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        // $userdata   = $this->customlib->getUserData();
        // $school_divison_id = $userdata['school_divison_id'];

        // if ($school_divison_id != 5)
        //     $data['school_divison_id'] = $school_divison_id;


        if (!empty($data_search_by_class)) {

            if ($data_search_by_class['specific_class'] == 'Yes') {

                $data['school_divison_id'] = $this->fetchSchoolDivisonId($data_search_by_class['get_class_id_send']);
                $data['class_id']  = $data_search_by_class['get_class_id_send'];
                $data['secid']  = $data_search_by_class['get_secid_send'];
                $this->db->insert('send_notification', $data);

                $insert_id = $this->db->insert_id();
                $message = INSERT_RECORD_CONSTANT . " On send notification id " . $insert_id;
                $action = "Insert";
                $record_id = $insert_id;
                $this->log($message, $record_id, $action);
            } else {
            }
        }

        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            //return $return_value;
        }



        if (!empty($staff_roles)) {
            foreach ($staff_roles as $stf_role_key => $stf_role_value) {
                $staff_roles[$stf_role_key]['send_notification_id'] = $insert_id;
            }

            $this->db->insert_batch('notification_roles', $staff_roles);
        }


        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {

            $this->db->trans_rollback();
            return FALSE;
        } else {

            $this->db->trans_commit();
            return TRUE;
        }
    }

    public function updateStatus($notification_id, $studentid)
    {
        $this->db->where('notification_id', $notification_id);
        $this->db->where('student_id', $studentid);
        $q = $this->db->get('read_notification');
        if ($q->num_rows() > 0) {
            return true;
        } else {
            $data = array(
                'notification_id' => $notification_id,
                'student_id' => $studentid
            );
            $this->db->insert('read_notification', $data);
        }
    }

    public function updateStatusforStaff($notification_id, $staff_id)
    {
        $this->db->where('notification_id', $notification_id);
        $this->db->where('parent_id', $staff_id);
        $q = $this->db->get('read_notification');
        if ($q->num_rows() > 0) {
            return true;
        } else {
            $data = array(
                'notification_id' => $notification_id,
                'staff_id' => $staff_id
            );
            $this->db->insert('read_notification', $data);
        }
    }

    public function updateStatusforParent($notification_id, $parentid)
    {
        $this->db->where('notification_id', $notification_id);
        $this->db->where('parent_id', $parentid);
        $q = $this->db->get('read_notification');
        if ($q->num_rows() > 0) {
            return true;
        } else {
            $data = array(
                'notification_id' => $notification_id,
                'parent_id' => $parentid
            );
            $this->db->insert('read_notification', $data);
        }
    }

    public function updateStatusforStudent($notification_id, $studentid)
    {
        $this->db->where('notification_id', $notification_id);
        $this->db->where('student_id', $studentid);
        $q = $this->db->get('read_notification');
        if ($q->num_rows() > 0) {
            return true;
        } else {
            $data = array(
                'notification_id' => $notification_id,
                'student_id' => $studentid
            );
            $this->db->insert('read_notification', $data);
        }
    }

    function add_exam_schedule($data)
    {
        $this->db->where('exam_id', $data['exam_id']);
        $this->db->where('teacher_subject_id', $data['teacher_subject_id']);
        $q = $this->db->get('exam_schedules');
        if ($q->num_rows() > 0) {
            $result = $q->row_array();
            $this->db->where('id', $result['id']);
            $this->db->update('exam_schedules', $data);
        } else {
            $this->db->insert('exam_schedules', $data);
        }
    }

    public function getcreatedByName($id)
    {
        $query = $this->db->select('staff.name,staff.surname')->where("id", $id)->get("staff");
        return $query->row_array();
    }
}
