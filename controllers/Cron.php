<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Cron extends CI_Controller
{

    protected $cron_key;

    /**
     * This is default constructor of the class
     */
    public function __construct($key = "")
    {
        parent::__construct();
        $setting_result = $this->setting_model->getSetting();
        $this->cron_key = $setting_result->cron_secret_key;
        $this->load->model('feereminder_model');
    }



    public function index($key = '')
    {

        if ($key != "" && $this->cron_key == $key) {
            echo "hey cron! how are you ?";

            $this->autobackup($key);
            $this->feereminder($key);
        } else {
            echo "Invalid Key or Direct access is not allowed";
            return;
        }
    }


    public function autobackup($key = '')
    {

        if ($key != "") {
            if ($key != "" && $this->cron_key != $key) {
                echo "Invalid Key or Direct access is not allowed";
                return;
            }

            $this->load->dbutil();
            $filename = "db-" . date("Y-m-d_H-i-s") . ".sql";
            $prefs    = array(
                'ignore'     => array(),
                'format'     => 'txt',
                'filename'   => 'mybackup.sql',
                'add_drop'   => true,
                'add_insert' => true,
                'newline'    => "\n",
            );
            $backup = $this->dbutil->backup($prefs);
            $this->load->helper('file');
            write_file('./backup/database_backup/' . $filename, $backup);
        }
    }


    public function feereminder($key = "")
    {
        $setting_result = $this->setting_model->getSetting();
        if ($key != "") {
            if ($key != "" && $this->cron_key != $key) {
                echo "Invalid Key or Direct access is not allowed";
                return;
            }
            $this->load->library('mailsmsconf');
            $feereminder   = $this->feereminder_model->get(null, 1);
            $reminter_type = array();
            $studentList   = array();

            if (!empty($feereminder)) {
                foreach ($feereminder as $feereminder_key => $feereminder_value) {
                    if ($feereminder_value->reminder_type == "before") {

                        $date               = date('Y-m-d', strtotime('+' . $feereminder_value->day . ' days'));
                        $fees_type_reminder = $this->feegrouptype_model->getFeeTypeDueDateReminder($date);

                        if (!empty($fees_type_reminder)) {

                            foreach ($fees_type_reminder as $reminder_key => $reminder_value) {

                                $students = $this->feegrouptype_model->getFeeTypeStudents($reminder_value->fee_session_group_id, $reminder_value->id);

                                foreach ($students as $student_key => $student_value) {
                                    $students[$student_key]->{'due_date'}       = $date;
                                    $students[$student_key]->{'fee_type'}       = $reminder_value->type;
                                    $students[$student_key]->{'fee_code'}       = $reminder_value->code;
                                    $students[$student_key]->{'fee_amount'}     = $reminder_value->amount;
                                    $students[$student_key]->{'due_amount'}     = $reminder_value->amount;
                                    $students[$student_key]->{'deposit_amount'} = number_format((float) 0, 2, '.', '');
                                    $fees_array                                 = json_decode($student_value->amount_detail);
                                    if (json_last_error() == JSON_ERROR_NONE) {
                                        $deposit_amount = 0;
                                        foreach ($fees_array as $fee_collected_key => $fee_collected_value) {
                                            $deposit_amount = $deposit_amount + ($fee_collected_value->amount + $fee_collected_value->amount_discount);
                                        };
                                        $students[$student_key]->{'deposit_amount'} = number_format((float) ($deposit_amount), 2, '.', '');
                                        $students[$student_key]->{'due_amount'}     = number_format((float) ($reminder_value->amount - $deposit_amount), 2, '.', '');
                                    };
                                    $students[$student_key]->{'student_name'} = $this->customlib->getFullName($student_value->firstname, $student_value->middlename, $student_value->lastname, $setting_result->middlename, $setting_result->lastname);
                                    $studentList[]                            = $student_value;
                                }
                            }
                        }
                    } else if ($feereminder_value->reminder_type == "after") {

                        $date               = date('Y-m-d', strtotime('-' . $feereminder_value->day . ' days'));
                        $fees_type_reminder = $this->feegrouptype_model->getFeeTypeDueDateReminder($date);

                        if (!empty($fees_type_reminder)) {
                            foreach ($fees_type_reminder as $reminder_key => $reminder_value) {

                                $students = $this->feegrouptype_model->getFeeTypeStudents($reminder_value->fee_session_group_id, $reminder_value->id);

                                foreach ($students as $student_key => $student_value) {
                                    $students[$student_key]->{'due_date'}       = $date;
                                    $students[$student_key]->{'fee_type'}       = $reminder_value->type;
                                    $students[$student_key]->{'fee_code'}       = $reminder_value->code;
                                    $students[$student_key]->{'fee_amount'}     = $reminder_value->amount;
                                    $students[$student_key]->{'due_amount'}     = $reminder_value->amount;
                                    $students[$student_key]->{'deposit_amount'} = number_format((float) 0, 2, '.', '');
                                    $fees_array                                 = json_decode($student_value->amount_detail);
                                    if (json_last_error() == JSON_ERROR_NONE) {
                                        $deposit_amount = 0;
                                        foreach ($fees_array as $fee_collected_key => $fee_collected_value) {

                                            $deposit_amount = $deposit_amount + ($fee_collected_value->amount + $fee_collected_value->amount_discount);
                                        };
                                        $students[$student_key]->{'deposit_amount'} = number_format((float) ($deposit_amount), 2, '.', '');
                                        $students[$student_key]->{'due_amount'}     = number_format((float) ($reminder_value->amount - $deposit_amount), 2, '.', '');
                                    };

                                    $students[$student_key]->{'student_name'} = $this->customlib->getFullName($student_value->firstname, $student_value->middlename, $student_value->lastname, $setting_result->middlename, $setting_result->lastname);
                                    $students[$student_key]->{'school_name'}  =   $this->customlib->getSchoolName();
                                    $studentList[]                            = $student_value;
                                }
                            }
                        }
                    }
                }

                if (!empty($studentList)) {
                    foreach ($studentList as $eachStudent_key => $eachStudent_value) {
                        if ($eachStudent_value->due_amount <= 0) {
                            unset($studentList[$eachStudent_key]);
                        }
                    }
                }

                if (!empty($studentList)) {
                    foreach ($studentList as $eachStudent_key => $eachStudent_value) {

                        $this->mailsmsconf->mailsms('fees_reminder', $eachStudent_value);
                    }
                }
                //  else {

                //     echo "no feereminder found";
                // }
            }
        }
    }


    public function enquiryreminder($key = "")
    {
        $setting_result = $this->setting_model->getSetting();

        if ($key != "" && $this->cron_key != $key) {
            echo "Invalid Key or Direct access is not allowed";
            return;
        }

        $this->load->model('Enquiry_with_scanner_model');
        $this->load->model('class_model');
        $this->load->library('mailsmsconf');
        $this->load->library('customlib');


        $class             = $this->class_model->get();
        $data['classlist'] = $class;

        $staff_followups = [];

        if (!empty($data['classlist'])) {
            foreach ($data['classlist'] as $key => $value) {
                $carray[] = $value['id'];
            }
        }

        $student_result  = $this->Enquiry_with_scanner_model->getstudentlist($carray, null);
        $m               = json_decode($student_result);

        if (!empty($m->data)) {

            // echo "true";

            foreach ($m->data as $key => $value) {

                $next_date = $this->Enquiry_with_scanner_model->next_follow_up_date($value->id);

                $date = !empty($next_date) ? ($next_date[0]['next_date']) : ($value->follow_up_date != null ? $value->follow_up_date : "");

                echo "<pre>";
                echo $date;
                if ($date != "") {
                    if ($date == date('Y-m-d')) {

                        // echo "true";

                        $staff_email = $value->assigned_staff_email;

                        $date1         = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($date));

                        $reference_no = $value->reference_no;

                        $email        = $value->email;

                        $mobileno     = $value->guardian_phone;

                        $sender_details = array(
                            'reference_no' => $reference_no,
                            'date' => $date1,
                            'email' => $email,
                            'guardian_phone' => $mobileno
                        );

                        // print_r($sender_details);

                        $this->mailsmsconf->mailsms('enquiry_reminder', $sender_details);


                        $firstname = $value->firstname;
                        // $middlename = !empty($enquiry_data['middlename']) ? $enquiry_data['middlename'] : "";
                        $lastname = !empty($value->lastname) ? $value->lastname : "";


                        $followup_details = [

                            'Inquiry ID/Date' => $reference_no . '/' .  $date1,
                            'Child Name' => $firstname . " " . $lastname,
                            'Mother Name/No' => $value->mother_name . '/' . $value->mother_phone,
                            'Father Name/No' => $value->father_name . '/' . $value->father_phone,

                        ];

                        if (!isset($staff_followups[$staff_email])) {
                            $staff_followups[$staff_email] = [];
                        }
                        $staff_followups[$staff_email][] = $followup_details;

                        $this->mailsmsconf->sentbulkenquiryremindertostaff($staff_followups);
                    } else {

                        echo "No follow-up date found for reminders.";
                    }
                }
            }
        }

        // print_r($staff_followups);

        echo "Emails sent successfully.";
    }

    // * * /usr/bin/php /path/to/your/application/index.php cron <your_cron_key>
}
