<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Paymentsetting_model extends MY_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * This funtion takes id as a parameter and will fetch the record.
     * If id is not provided, then it will fetch all the records form the table.
     * @param int $id
     * @return mixed
     */
    public function get() {
        $this->db->select()->from('payment_settings');
        $query = $this->db->get();
        return $query->result();
    }

    public function getActiveMethod() {
        $this->db->select()->from('payment_settings');
        $this->db->where('is_active', 'yes');
        $query = $this->db->get();
        return $query->row();
    }

    public function add($data) {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('payment_type', $data['payment_type']);
        $q = $this->db->get('payment_settings');

        if ($q->num_rows() > 0) {

            $this->db->where('id', $q->row()->id);
            $this->db->update('payment_settings', $data);
            $message = UPDATE_RECORD_CONSTANT . " On payment settings id " . $q->row()->id;
            $action = "Update";
            $record_id = $q->row()->id;
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

            $this->db->insert('payment_settings', $data);
            $insert_id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On payment settings id " . $insert_id;
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
    }

    public function valid_paymentsetting() {

        $payment_setting = $this->input->post('payment_setting');
        if ($payment_setting == "none") {
            return true;
        }
        if (!$this->check_data_exists($payment_setting)) {
            $this->form_validation->set_message('paymentsetting', 'Please fill your %s detail');
            return FALSE;
        } else {
            return TRUE;
        }
    }
 
    function check_data_exists($payment_setting) {

        $this->db->where('payment_type', $payment_setting);
        $query = $this->db->get('payment_settings');

        if ($query->num_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
        
    }

    public function active($data, $other = false) {


        if (!$other) {
            $this->db->where('payment_type', $data['payment_type']);
            $this->db->update('payment_settings', $data);
            $data['is_active'] = "no";
            $payment_type = $data['payment_type'];
            unset($data['payment_type']);
            $this->db->where('payment_type !=', $payment_type);
            $this->db->update('payment_settings', $data);
        } else {

            $this->db->update('payment_settings', $data);
        }
    }

    /**
     * Ensure test transactions table exists
     */
    public function ensureTestTable() {
        $sql = "CREATE TABLE IF NOT EXISTS `gateway_test_transactions` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `gateway` varchar(50) NOT NULL DEFAULT 'razorpay',
          `payment_id` varchar(100) NOT NULL,
          `order_id` varchar(100) DEFAULT NULL,
          `signature` varchar(255) DEFAULT NULL,
          `amount` decimal(10,2) NOT NULL DEFAULT 1.00,
          `currency` varchar(10) NOT NULL DEFAULT 'INR',
          `status` varchar(50) NOT NULL DEFAULT 'captured',
          `method` varchar(50) DEFAULT NULL,
          `bank` varchar(100) DEFAULT NULL,
          `vpa` varchar(150) DEFAULT NULL,
          `email` varchar(150) DEFAULT NULL,
          `contact` varchar(50) DEFAULT NULL,
          `is_signature_verified` tinyint(1) NOT NULL DEFAULT 0,
          `raw_response` longtext DEFAULT NULL,
          `tested_by` varchar(100) DEFAULT NULL,
          `created_at` datetime NOT NULL,
          PRIMARY KEY (`id`),
          KEY `idx_payment_id` (`payment_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $this->db->query($sql);
    }

    /**
     * Save test transaction
     */
    public function saveTestTransaction($data) {
        $this->ensureTestTable();
        $this->db->insert('gateway_test_transactions', $data);
        return $this->db->insert_id();
    }

    /**
     * Get recent test transactions
     */
    public function getTestTransactions($limit = 10) {
        $this->ensureTestTable();
        $this->db->select('*');
        $this->db->from('gateway_test_transactions');
        $this->db->order_by('id', 'DESC');
        $this->db->limit($limit);
        $query = $this->db->get();
        return $query ? $query->result_array() : array();
    }

    /**
     * Get single test transaction
     */
    public function getTestTransactionById($id) {
        $this->ensureTestTable();
        $this->db->where('id', $id);
        $query = $this->db->get('gateway_test_transactions');
        return $query ? $query->row_array() : array();
    }

}
