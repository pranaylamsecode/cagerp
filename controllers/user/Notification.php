<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Notification extends Student_Controller {

    public function __construct() {
        parent::__construct();
    }


    public function test(){

         $student_id = $this->customlib->getStudentSessionUserID();
         $notifications = $this->notification_model->getNotificationForStudent($student_id);

            $notification_bydate = array();
        foreach ($notifications as $key => $value) {

        //     $date = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value['publish_date']));

        //     if (strtotime(date($this->customlib->getSchoolDateFormat())) >= strtotime(date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value['publish_date'])))) {
        //         $status = 'yes';
        //         echo "<pre>";
        //         print_r($value['title']. $date .$status);
        //     }
        //     else{
        //         $status = 'no';
        //         echo "<pre>";
        //         print_r($value['title']. $date .$status);

        //     }
            
            
            if (strtotime(date($this->customlib->getSchoolDateFormat())) >= strtotime(date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value['publish_date'])))) {
                $notification_bydate[] = $value;
            }
             
        }
                

        $notificationlist = $notification_bydate;

        // $student      = $this->student_model->get($student_id);
        //     $get_student_class_id = $student['class_id'];
            
        //     if(!empty($get_student_class_id))
        //     {
        //         $student_class_id = $get_student_class_id;
        //     }else{
        //         $student_class_id = '';
        //     }

        // foreach($notificationlist as $key => $notification){
        //    if($notification['class_id'] == $student_class_id ) { 

        //      echo "<pre>";
        //      print_r($notification);
                                      
        //    }                               
        // }

        echo "<pre>";
        print_r($notificationlist);

       

    }
 
    public function index() {
        $this->session->set_userdata('top_menu', 'notification');
        $data['title'] = 'Notifications';
        $user_role = $this->customlib->getUserRole();
        if ($user_role == 'student') {
            $student_id = $this->customlib->getStudentSessionUserID();
            $notifications = $this->notification_model->getNotificationForStudent($student_id);

             $student      = $this->student_model->get($student_id);
            $get_student_class_id = $student['class_id'];
            
            if(!empty($get_student_class_id))
            {
                $student_class_id = $get_student_class_id;
            }else{
                $student_class_id = '';
            }
            $get_student_section_id =  $student['section_id'];
            if(!empty($get_student_section_id))
            {
                $student_section_id = $get_student_section_id;
            }else{
                $student_section_id = '';
            }
           
        } elseif ($user_role == 'parent') {
            $student_id = $this->customlib->getUsersID();
            $notifications = $this->notification_model->getNotificationForParent($student_id);
        }

        

        $notification_bydate = array();
        foreach ($notifications as $key => $value) {
            
            if (strtotime(date($this->customlib->getSchoolDateFormat())) >= strtotime(date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value['publish_date'])))) {
                $notification_bydate[] = $value;
            }
             
        }

        
        

        $data['notificationlist'] = $notification_bydate;
        $data['student_class_id'] = $student_class_id;
        $data['student_section_id'] = $student_section_id;
       
        


        $this->load->view('layout/student/header', $data);
        $this->load->view('user/notification/notificationList', $data);
        $this->load->view('layout/student/footer', $data);
    }

    public function updatestatus() {

        $notification_id = $this->input->post('notification_id');

        $user_role = $this->customlib->getUserRole();
        if ($user_role == 'student') {
            $student_id = $this->customlib->getStudentSessionUserID();
            $data = $this->notification_model->updateStatus($notification_id, $student_id);
        } elseif ($user_role == 'parent') {
            $parent_id = $this->customlib->getUsersID();
            $data = $this->notification_model->updateStatusforParent($notification_id, $parent_id);
        }

        $array = array('status' => "success", 'data' => $data);
        echo json_encode($array);
    }

    public function read() {
        $array = array('status' => "fail", 'msg' => $this->lang->line('somthing_wrong'));
        $notification_id = $this->input->post('notice');
        if ($notification_id != "") {
            $student_id = $this->customlib->getStudentSessionUserID();
            $data = $this->notification_model->updateStatusforStudent($notification_id, $student_id);
            $array = array('status' => "success", 'data' => $data, 'msg' => $this->lang->line('update_message'));
        }

        echo json_encode($array);
    }

}
