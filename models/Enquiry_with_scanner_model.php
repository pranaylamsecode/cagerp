<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Enquiry_with_scanner_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }


    public function add($data)
    {
        $this->db->insert('enquiry_with_scanner', $data);
        return $this->db->insert_id();
    }


    public function paymentSuccess($response)
    {
        $this->db->update('enquiry_with_scanner', array('form_status' => 1, 'paid_status' => 1), array('id' => $response['enquiry_id']));
        $this->db->insert('enquiry_form_payment', $response);
    }

    public function edit($data)
    {

        $this->db->where('id', $data['id']);
        $this->db->update('enquiry_with_scanner', $data);
    }


    public function get($id = null, $carray = null)
    {

        $this->db->select('enquiry_with_scanner.vehroute_id,enquiry_with_scanner.description,enquiry_with_scanner.note,enquiry_with_scanner.source_id,source.source,enquiry_with_scanner.assigned,enquiry_with_scanner.enquiry_date,enquiry_with_scanner.follow_up_date,enquiry_with_scanner.status,vehicle_routes.route_id,vehicle_routes.vehicle_id,transport_route.route_title,vehicles.vehicle_no,hostel_rooms.room_no,vehicles.driver_name,vehicles.driver_contact,hostel.id as `hostel_id`,hostel.hostel_name,room_types.id as `room_type_id`,room_types.room_type ,enquiry_with_scanner.hostel_room_id,class_sections.id as class_section_id,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,enquiry_with_scanner.id,enquiry_with_scanner.admission_no , enquiry_with_scanner.roll_no,enquiry_with_scanner.admission_date,enquiry_with_scanner.firstname,enquiry_with_scanner.middlename, enquiry_with_scanner.lastname,enquiry_with_scanner.image,    enquiry_with_scanner.mobileno, enquiry_with_scanner.email ,enquiry_with_scanner.state ,   enquiry_with_scanner.city , enquiry_with_scanner.pincode , enquiry_with_scanner.note, enquiry_with_scanner.religion, enquiry_with_scanner.cast,enquiry_with_scanner.school_house_id, school_houses.house_name,   enquiry_with_scanner.dob ,enquiry_with_scanner.current_address, enquiry_with_scanner.previous_school,
            enquiry_with_scanner.guardian_is,
            enquiry_with_scanner.permanent_address,IFNULL(enquiry_with_scanner.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,enquiry_with_scanner.adhar_no,enquiry_with_scanner.samagra_id,enquiry_with_scanner.bank_account_no,enquiry_with_scanner.bank_name, enquiry_with_scanner.ifsc_code , enquiry_with_scanner.guardian_name , enquiry_with_scanner.father_pic ,enquiry_with_scanner.height ,enquiry_with_scanner.weight,enquiry_with_scanner.measurement_date, enquiry_with_scanner.mother_pic , enquiry_with_scanner.guardian_pic , enquiry_with_scanner.guardian_relation,enquiry_with_scanner.guardian_phone,enquiry_with_scanner.guardian_address,enquiry_with_scanner.is_enroll ,enquiry_with_scanner.created_at,enquiry_with_scanner.document ,enquiry_with_scanner.updated_at,enquiry_with_scanner.father_name,enquiry_with_scanner.father_phone,enquiry_with_scanner.blood_group,enquiry_with_scanner.school_house_id,enquiry_with_scanner.father_occupation,enquiry_with_scanner.mother_name,enquiry_with_scanner.mother_phone,enquiry_with_scanner.mother_occupation,enquiry_with_scanner.guardian_occupation,enquiry_with_scanner.gender,enquiry_with_scanner.guardian_is,enquiry_with_scanner.rte,enquiry_with_scanner.guardian_email,enquiry_with_scanner.paid_status,enquiry_with_scanner.form_status,enquiry_with_scanner.reference_no,enquiry_with_scanner.class_section_id')->from('enquiry_with_scanner');

        $this->db->join('class_sections', 'class_sections.id = enquiry_with_scanner.class_section_id', 'left');
        $this->db->join('classes', 'class_sections.class_id = classes.id', 'left');
        $this->db->join('sections', 'sections.id = class_sections.section_id', 'left');
        $this->db->join('hostel_rooms', 'hostel_rooms.id = enquiry_with_scanner.hostel_room_id', 'left');
        $this->db->join('hostel', 'hostel.id = hostel_rooms.hostel_id', 'left');
        $this->db->join('room_types', 'room_types.id = hostel_rooms.room_type_id', 'left');
        $this->db->join('categories', 'enquiry_with_scanner.category_id = categories.id', 'left');
        $this->db->join('vehicle_routes', 'vehicle_routes.id = enquiry_with_scanner.vehroute_id', 'left');
        $this->db->join('transport_route', 'vehicle_routes.route_id = transport_route.id', 'left');
        $this->db->join('vehicles', 'vehicles.id = vehicle_routes.vehicle_id', 'left');
        $this->db->join('school_houses', 'school_houses.id = enquiry_with_scanner.school_house_id', 'left');
        $this->db->join('source', 'source.id = enquiry_with_scanner.source_id', 'left');

        if ($carray != null) {
            $this->db->where_in('classes.id', $carray);
        }

        if ($id != null) {
            $this->db->where('enquiry_with_scanner.id', $id);
        } else {

            $this->db->order_by('enquiry_with_scanner.id', 'desc');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }


    public function getstudentlist($carray = null, $id = null)
    {

        if ($carray != null) {
            $this->datatables->where_in('classes.id', $carray);
        }

        if ($id != null) {
            $this->datatables->where('enquiry_with_scanner.id', $id);
        } else {

            $this->datatables->orderable('enquiry_with_scanner.id', 'desc');
        }

        $this->datatables
            ->select('enquiry_with_scanner.vehroute_id,enquiry_with_scanner.description,enquiry_with_scanner.note,enquiry_with_scanner.assigned,staff.email as assigned_staff_email,enquiry_with_scanner.enquiry_date,enquiry_with_scanner.follow_up_date,enquiry_with_scanner.status,vehicle_routes.route_id,vehicle_routes.vehicle_id,transport_route.route_title,vehicles.vehicle_no,hostel_rooms.room_no,vehicles.driver_name,vehicles.driver_contact,hostel.id as `hostel_id`,hostel.hostel_name,room_types.id as `room_type_id`,room_types.room_type ,enquiry_with_scanner.hostel_room_id,class_sections.id as class_section_id,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,enquiry_with_scanner.id,enquiry_with_scanner.admission_no , enquiry_with_scanner.roll_no,enquiry_with_scanner.admission_date,enquiry_with_scanner.firstname, enquiry_with_scanner.lastname,enquiry_with_scanner.image,    enquiry_with_scanner.mobileno, enquiry_with_scanner.email ,enquiry_with_scanner.state ,   enquiry_with_scanner.city , enquiry_with_scanner.pincode , enquiry_with_scanner.note, enquiry_with_scanner.religion, enquiry_with_scanner.cast, school_houses.house_name,   enquiry_with_scanner.dob ,enquiry_with_scanner.current_address, enquiry_with_scanner.previous_school,
            enquiry_with_scanner.guardian_is,
            enquiry_with_scanner.permanent_address,IFNULL(enquiry_with_scanner.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,enquiry_with_scanner.adhar_no,enquiry_with_scanner.samagra_id,enquiry_with_scanner.bank_account_no,enquiry_with_scanner.bank_name, enquiry_with_scanner.ifsc_code , enquiry_with_scanner.guardian_name , enquiry_with_scanner.father_pic ,enquiry_with_scanner.height ,enquiry_with_scanner.weight,enquiry_with_scanner.measurement_date, enquiry_with_scanner.mother_pic , enquiry_with_scanner.guardian_pic , enquiry_with_scanner.guardian_relation,enquiry_with_scanner.guardian_phone,enquiry_with_scanner.guardian_address,enquiry_with_scanner.is_enroll ,enquiry_with_scanner.created_at,enquiry_with_scanner.document ,enquiry_with_scanner.updated_at,enquiry_with_scanner.father_name,enquiry_with_scanner.father_phone,enquiry_with_scanner.blood_group,enquiry_with_scanner.school_house_id,enquiry_with_scanner.father_occupation,enquiry_with_scanner.mother_name,enquiry_with_scanner.mother_phone,enquiry_with_scanner.mother_occupation,enquiry_with_scanner.guardian_occupation,enquiry_with_scanner.gender,enquiry_with_scanner.guardian_is,enquiry_with_scanner.rte,enquiry_with_scanner.guardian_email,enquiry_with_scanner.reference_no,enquiry_with_scanner.paid_status,enquiry_with_scanner.form_status')
            ->orderable('enquiry_with_scanner.reference_no,enquiry_with_scanner.firstname,classes.class,enquiry_with_scanner.father_name,enquiry_with_scanner.dob,enquiry_with_scanner.gender,categories.category,enquiry_with_scanner.mobileno," "," "," " ')
            ->searchable('enquiry_with_scanner.reference_no,enquiry_with_scanner.id,enquiry_with_scanner.firstname,classes.class,enquiry_with_scanner.father_name,enquiry_with_scanner.dob,enquiry_with_scanner.gender,categories.category,enquiry_with_scanner.mobileno')
            ->join('class_sections', 'class_sections.id = enquiry_with_scanner.class_section_id', 'left')
            ->join('classes', 'class_sections.class_id = classes.id', 'left')
            ->join('sections', 'sections.id = class_sections.section_id', 'left')
            ->join('hostel_rooms', 'hostel_rooms.id = enquiry_with_scanner.hostel_room_id', 'left')
            ->join('hostel', 'hostel.id = hostel_rooms.hostel_id', 'left')
            ->join('room_types', 'room_types.id = hostel_rooms.room_type_id', 'left')
            ->join('categories', 'enquiry_with_scanner.category_id = categories.id', 'left')
            ->join('vehicle_routes', 'vehicle_routes.id = enquiry_with_scanner.vehroute_id', 'left')
            ->join('transport_route', 'vehicle_routes.route_id = transport_route.id', 'left')
            ->join('vehicles', 'vehicles.id = vehicle_routes.vehicle_id', 'left')
            ->join('school_houses', 'school_houses.id = enquiry_with_scanner.school_house_id', 'left')
            ->join('staff', 'staff.id = enquiry_with_scanner.assigned', 'left')

            ->from('enquiry_with_scanner');

        return $this->datatables->generate('json');
    }


    public function update($id, $data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->update('enquiry_with_scanner', $data);
        $message   = UPDATE_RECORD_CONSTANT . " On  enquiry id " . $id;
        $action    = "Update";
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



    public function student_update($data, $action = "save")
    {

        $record_update_status = true;
        $student_id           = "";
        if (isset($data['id'])) {
            $this->db->trans_begin();
            $data_id          = $data['id'];
            $class_section_id = $data['class_section_id'];

            if ($action == "enroll") {
                //==========================
                $insert             = true;
                $sch_setting_detail = $this->setting_model->getSetting();

                if ($sch_setting_detail->adm_auto_insert) {
                    if ($sch_setting_detail->adm_update_status) {

                        $admission_no = $sch_setting_detail->adm_prefix . $sch_setting_detail->adm_start_from;

                        $last_student = $this->student_model->lastRecord();

                        $last_admission_digit = str_replace($sch_setting_detail->adm_prefix, "", $last_student->admission_no);

                        $admission_no = $sch_setting_detail->adm_prefix . sprintf("%0" . $sch_setting_detail->adm_no_digit . "d", $last_admission_digit + 1);

                        $data['admission_no'] = $admission_no;
                    } else {
                        $admission_no         = $sch_setting_detail->adm_prefix . $sch_setting_detail->adm_start_from;
                        $data['admission_no'] = $admission_no;
                    }
                }

                $admission_no_exists = $this->student_model->check_adm_exists($data['admission_no']);
                if ($admission_no_exists) {
                    $insert               = false;
                    $record_update_status = false;
                }

                //============================
                if ($insert) {
                    $this->db->select('class_sections.*')->from('class_sections');
                    $this->db->where('class_sections.id', $data['class_section_id']);
                    $query                 = $this->db->get();
                    $classs_section_result = $query->row();
                    unset($data['class_section_id']);
                    unset($data['id']);
                    $this->db->insert('students', $data);
                    $student_id = $this->db->insert_id();
                    $data_new   = array(
                        'student_id' => $student_id,
                        'class_id'   => $classs_section_result->class_id,
                        'section_id' => $classs_section_result->section_id,
                        'session_id' => $this->current_session,
                    );
                    $this->db->insert('student_session', $data_new);

                    //===============Start Student ID===========
                    $user_password = $this->role->get_random_password($chars_min = 6, $chars_max = 6, $use_upper_case = false, $include_numbers = true, $include_special_chars = false);

                    $data_student_login = array(
                        'username' => $this->student_login_prefix . $student_id,
                        'password' => $user_password,
                        'user_id'  => $student_id,
                        'role'     => 'student',
                    );

                    $this->user_model->add($data_student_login);
                    //===============End Student ID===========
                    //===============Start Parent ID===========

                    $parent_password   = $this->role->get_random_password($chars_min = 6, $chars_max = 6, $use_upper_case = false, $include_numbers = true, $include_special_chars = false);
                    $temp              = $student_id;
                    $data_parent_login = array(
                        'username' => $this->parent_login_prefix . $student_id,
                        'password' => $parent_password,
                        'user_id'  => 0,
                        'role'     => 'parent',
                        'childs'   => $temp,
                    );
                    $ins_parent_id  = $this->user_model->add($data_parent_login);
                    $update_student = array(
                        'id'        => $student_id,
                        'parent_id' => $ins_parent_id,
                    );
                    $this->student_model->add($update_student);

                    //===============End Parent ID===========
                    //============== Update setting modal===============================

                    if ($sch_setting_detail->adm_auto_insert) {
                        if ($sch_setting_detail->adm_update_status == 0) {
                            $data_setting                      = array();
                            $data_setting['id']                = $sch_setting_detail->id;
                            $data_setting['adm_update_status'] = 1;
                            $this->setting_model->add($data_setting);
                        }
                    }

                    //=============================================

                    $data['is_enroll']        = 1;
                    $data['class_section_id'] = $class_section_id;
                    $data['status']           = 'won';
                }
            }


            $this->db->where('id', $data_id);
            $this->db->update('enquiry_with_scanner', $data);

            $message   = UPDATE_RECORD_CONSTANT . " On  online admissions id " . $data_id;
            $action    = "Update";
            $record_id = $data_id;
            $this->log($message, $record_id, $action);

            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
            }
        }
        return json_encode(array('record_update_status' => $record_update_status, 'student_id' => $student_id, 'user_password' => $user_password, 'parent_password' => $parent_password));
    }




    public function changeStatus($data)
    {

        $this->db->where("id", $data["id"])->update("enquiry_with_scanner", $data);
    }


    public function add_follow_up($data)
    {
        $this->db->insert('parent_follow_up', $data);
    }

    public function getfollow_up_list($enquiry_id, $follow_up = null)
    {
        $this->db->select()->from('parent_follow_up');
        if ($follow_up != null) {
            $this->db->where('parent_follow_up.id', $follow_up);
            $this->db->where('parent_follow_up.enquiry_id', $enquiry_id);
            $this->db->order_by('parent_follow_up.id desc');
        } else {
            $this->db->where('parent_follow_up.enquiry_id', $enquiry_id);
            $this->db->order_by('parent_follow_up.id desc');
        }
        $query = $this->db->get();
        if ($follow_up != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }


    public function next_follow_up_date($enquiry_id)
    {
        $this->db->select('max(`id`) as id');
        $this->db->from('parent_follow_up');
        $this->db->where('enquiry_id', $enquiry_id);
        $query = $this->db->get();
        $data  = $query->row_array();
        $id    = $data['id'];
        $this->db->select('*');
        $this->db->from('parent_follow_up');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->result_array();
    }


    public function delete_follow_up($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('parent_follow_up');
    }





    public function check_student_email_exists($str)
    {
        $email = $this->security->xss_clean($str);
        if ($email != "") {
            $id = $this->input->post('admission_id');
            if (!isset($id)) {
                $id = 0;
            }

            if ($this->check_data_exists($email, $id)) {
                $this->form_validation->set_message('check_student_email_exists', $this->lang->line('record_already_exists'));
                return false;
            } else {
                return true;
            }
        }
        return true;
    }


    public function check_data_exists($email, $id = null)
    {
        $this->db->where('email', $email);
        if ($id != null) {
            $this->db->where('id !=', $id);
        }

        $query = $this->db->get('enquiry_with_scanner');

        $student_query = $this->db->query("select email from students where email='" . $email . "' ");

        if ($query->num_rows() > 0 || $student_query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }


    public function checkreferenceno($reference_no)
    {
        $this->db->select("*");
        $this->db->from('enquiry_with_scanner');

        $this->db->where("reference_no", $reference_no);
        $query  = $this->db->get();
        $result = $query->row_array();
        if (!empty($result)) {
            return 1;
        } else {
            return 0;
        }
    }


    public function remove($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('enquiry_with_scanner');
        $message   = DELETE_RECORD_CONSTANT . " On parent enquiry id " . $id;
        $action    = "Delete";
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


    public function validate_paymentamount()
    {

        $str = $this->input->post('enquiry_form_amount');

        if ($str > 0) {
            return true;
        } elseif ($str == 0) {

            $this->form_validation->set_message('check_exists', $this->lang->line('invalid_payment_amount'));
            return false;
        } elseif ($str = "") {
            $this->form_validation->set_message('check_exists', $this->lang->line('required'));
            return false;
        }
    }

    public function getformfields()
    {
        $this->db->select('*');
        $this->db->from('enquiry_form_fields');
        $query = $this->db->get();
        return $query->result();
    }





    public function getcustomfields()
    {

        $this->db->select("name");
        $this->db->from('custom_fields');
        $this->db->where("belong_to", 'students');
        $query  = $this->db->get();
        $result = $query->result_array();
        return $result;
    }


    public function addformfields($record)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well

        $this->db->where('name', $record['name']);
        $q = $this->db->get('enquiry_form_fields');

        if ($q->num_rows() > 0) {

            $results = $q->row();
            $this->db->where('id', $results->id);
            $this->db->update('enquiry_form_fields', $record);
            $message   = UPDATE_RECORD_CONSTANT . " On  enquiry_form_fields id " . $results->id;
            $action    = "Update";
            $record_id = $insert_id = $results->id;
            $this->log($message, $record_id, $action);
        } else {

            $this->db->insert('enquiry_form_fields', $record);
            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On enquiry_form_fields id " . $insert_id;
            $action    = "Insert";
            $record_id = $insert_id;
            $this->log($message, $record_id, $action);
        }

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }


    public function editguardianfield($status)
    {

        $data = array('guardian_relation', 'guardian_name', 'guardian_phone');
        foreach ($data as $value) {
            $this->db->query("update 'enquiry_form_fields' set status=" . $status . " where name='" . $value . "'   ");
        }
    }
}
