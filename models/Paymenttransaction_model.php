<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Paymenttransaction_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    /**
     * Get Online Admission Payments with student and class details
     * Dynamic columns ensure 100% compatibility with untouched live DB schema.
     */
    public function getOnlineAdmissionPayments($search = null, $date_from = null, $date_to = null, $sync_status = null)
    {
        $has_status   = $this->db->field_exists('status', 'online_admission_payment');
        $has_order_id = $this->db->field_exists('order_id', 'online_admission_payment');
        $has_raw      = $this->db->field_exists('raw_response', 'online_admission_payment');

        $status_col = $has_status ? "p.status as payment_status" : "'success' as payment_status";
        $order_col  = $has_order_id ? "p.order_id" : "'' as order_id";
        $raw_col    = $has_raw ? "p.raw_response" : "'' as raw_response";

        $this->db->select("
            p.id,
            p.admission_id,
            p.paid_amount,
            p.payment_mode,
            p.payment_type,
            p.transaction_id,
            p.note,
            p.date,
            {$status_col},
            {$order_col},
            {$raw_col},
            o.reference_no,
            o.firstname,
            o.lastname,
            o.mobileno,
            o.email,
            o.paid_status,
            o.form_status,
            o.created_at as admission_date,
            c.class,
            s.section,
            (CASE WHEN o.paid_status = 1 THEN 'synced' ELSE 'unsynced' END) AS sync_status
        ", false);
        $this->db->from('online_admission_payment p');
        $this->db->join('online_admissions o', 'o.id = p.admission_id', 'left');
        $this->db->join('class_sections cs', 'cs.id = o.class_section_id', 'left');
        $this->db->join('classes c', 'c.id = cs.class_id', 'left');
        $this->db->join('sections s', 's.id = cs.section_id', 'left');

        if (!empty($date_from)) {
            $this->db->where('DATE(p.date) >=', date('Y-m-d', strtotime($date_from)));
        }
        if (!empty($date_to)) {
            $this->db->where('DATE(p.date) <=', date('Y-m-d', strtotime($date_to)));
        }
        if (!empty($sync_status)) {
            if ($sync_status == 'synced') {
                $this->db->where('o.paid_status', 1);
            } elseif ($sync_status == 'unsynced') {
                $this->db->where('(o.paid_status = 0 OR o.paid_status IS NULL)');
            }
        }
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('o.reference_no', $search);
            $this->db->or_like('o.firstname', $search);
            $this->db->or_like('o.lastname', $search);
            $this->db->or_like('o.mobileno', $search);
            $this->db->or_like('p.transaction_id', $search);
            $this->db->or_like('p.payment_mode', $search);
            $this->db->or_like('c.class', $search);
            $this->db->group_end();
        }

        $this->db->order_by('p.id', 'DESC');
        $query = $this->db->get();
        return $query ? $query->result_array() : array();
    }

    /**
     * Get Atom / App transactions
     * Dynamic columns ensure 100% compatibility with untouched live DB schema.
     */
    public function getAtomTransactions($search = null, $date_from = null, $date_to = null, $status = null)
    {
        $has_atom_status = $this->db->field_exists('response_status', 'atom_transaction');
        $has_atom_raw    = $this->db->field_exists('raw_response', 'atom_transaction');

        $status_col = $has_atom_status 
            ? 't.response_status' 
            : '(CASE WHEN t.f_code IN ("OTS0000", "Ok") OR (t.amt > 0 AND (t.f_code NOT IN ("OTS0600", "F", "OTS0101") OR t.f_code IS NULL)) THEN "SUCCESS" WHEN t.f_code = "OTS0101" THEN "CANCELLED" ELSE "FAILED" END)';
        $msg_col = $has_atom_status 
            ? 't.response_message' 
            : 'IFNULL(t.discriminator, "Processed by App")';
        $raw_col = $has_atom_raw ? 't.raw_response' : "''";

        $this->db->select("
            t.*,
            {$status_col} as response_status,
            {$msg_col} as response_message,
            {$raw_col} as raw_response,
            st.admission_no,
            st.firstname as st_firstname,
            st.lastname as st_lastname,
            st.mobileno as st_mobileno,
            st.email as st_email
        ", false);
        $this->db->from('atom_transaction t');
        $this->db->join('students st', 'st.id = t.student_id', 'left');

        if (!empty($date_from)) {
            $this->db->where('DATE(t.date) >=', date('Y-m-d', strtotime($date_from)));
        }
        if (!empty($date_to)) {
            $this->db->where('DATE(t.date) <=', date('Y-m-d', strtotime($date_to)));
        }
        if (!empty($status)) {
            if ($has_atom_status) {
                $this->db->where('t.response_status', $status);
            } else {
                if ($status == 'SUCCESS') {
                    $this->db->group_start();
                    $this->db->where_in('t.f_code', array('OTS0000', 'Ok'));
                    $this->db->or_where('(t.amt > 0 AND (t.f_code NOT IN ("OTS0600", "F", "OTS0101") OR t.f_code IS NULL))');
                    $this->db->group_end();
                } elseif ($status == 'FAILED') {
                    $this->db->group_start();
                    $this->db->where_in('t.f_code', array('OTS0600', 'F'));
                    $this->db->or_where('(t.amt = 0 AND (t.f_code IS NULL OR t.f_code = ""))');
                    $this->db->group_end();
                } elseif ($status == 'CANCELLED') {
                    $this->db->where('t.f_code', 'OTS0101');
                }
            }
        }
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('t.mmp_txn', $search);
            $this->db->or_like('t.mer_txn', $search);
            $this->db->or_like('t.bank_txn', $search);
            $this->db->or_like('t.udf1', $search);
            $this->db->or_like('t.udf3', $search);
            $this->db->or_like('st.admission_no', $search);
            $this->db->or_like('st.firstname', $search);
            $this->db->or_like('st.lastname', $search);
            $this->db->group_end();
        }

        $this->db->order_by('t.trans_id', 'DESC');
        $query = $this->db->get();
        return $query ? $query->result_array() : array();
    }

    /**
     * Get online fee deposits from student_fees_deposite
     */
    public function getOnlineFeeDeposits($search = null, $date_from = null, $date_to = null)
    {
        $this->db->select('
            d.id,
            d.student_fees_master_id,
            d.fee_groups_feetype_id,
            d.amount_detail,
            d.created_at,
            m.student_session_id,
            st.id as student_id,
            st.admission_no,
            st.firstname,
            st.lastname,
            st.mobileno,
            c.class,
            s.section,
            fg.name as fee_group_name,
            ft.type as fee_type
        ');
        $this->db->from('student_fees_deposite d');
        $this->db->join('student_fees_master m', 'm.id = d.student_fees_master_id', 'inner');
        $this->db->join('student_session ss', 'ss.id = m.student_session_id', 'inner');
        $this->db->join('students st', 'st.id = ss.student_id', 'inner');
        $this->db->join('classes c', 'c.id = ss.class_id', 'left');
        $this->db->join('sections s', 's.id = ss.section_id', 'left');
        $this->db->join('fee_groups_feetype fgft', 'fgft.id = d.fee_groups_feetype_id', 'left');
        $this->db->join('fee_groups fg', 'fg.id = fgft.fee_groups_id', 'left');
        $this->db->join('feetype ft', 'ft.id = fgft.feetype_id', 'left');

        // Only include online deposits
        $this->db->like('d.amount_detail', '"payment_mode":"online"');

        if (!empty($date_from)) {
            $this->db->where('DATE(d.created_at) >=', date('Y-m-d', strtotime($date_from)));
        }
        if (!empty($date_to)) {
            $this->db->where('DATE(d.created_at) <=', date('Y-m-d', strtotime($date_to)));
        }
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('st.admission_no', $search);
            $this->db->or_like('st.firstname', $search);
            $this->db->or_like('st.lastname', $search);
            $this->db->or_like('d.amount_detail', $search);
            $this->db->group_end();
        }

        $this->db->order_by('d.id', 'DESC');
        $this->db->limit(500);
        $query = $this->db->get();
        return $query ? $query->result_array() : array();
    }

    /**
     * Get Enquiry Payments
     */
    public function getEnquiryPayments($search = null, $date_from = null, $date_to = null)
    {
        $this->db->select('
            ep.*,
            e.name as candidate_name,
            e.contact,
            e.email,
            e.reference
        ');
        $this->db->from('enquiry_form_payment ep');
        $this->db->join('enquiry e', 'e.id = ep.enquiry_id', 'left');

        if (!empty($date_from)) {
            $this->db->where('DATE(ep.date) >=', date('Y-m-d', strtotime($date_from)));
        }
        if (!empty($date_to)) {
            $this->db->where('DATE(ep.date) <=', date('Y-m-d', strtotime($date_to)));
        }
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('ep.transaction_id', $search);
            $this->db->or_like('e.name', $search);
            $this->db->or_like('e.contact', $search);
            $this->db->group_end();
        }

        $this->db->order_by('ep.id', 'DESC');
        $query = $this->db->get();
        return $query ? $query->result_array() : array();
    }

    /**
     * Get payment details by admission id
     */
    public function getAdmissionPaymentById($admission_id)
    {
        $this->db->select('p.*, o.reference_no, o.firstname, o.lastname, o.mobileno, o.email, o.paid_status, o.form_status');
        $this->db->from('online_admission_payment p');
        $this->db->join('online_admissions o', 'o.id = p.admission_id', 'left');
        $this->db->where('p.admission_id', $admission_id);
        $this->db->order_by('p.id', 'DESC');
        $query = $this->db->get();
        return $query ? $query->result_array() : array();
    }

    /**
     * Get single Atom transaction by trans_id
     */
    public function getAtomTransactionById($trans_id)
    {
        $this->db->select('t.*, st.admission_no, st.firstname as st_firstname, st.lastname as st_lastname');
        $this->db->from('atom_transaction t');
        $this->db->join('students st', 'st.id = t.student_id', 'left');
        $this->db->where('t.trans_id', $trans_id);
        $query = $this->db->get();
        return $query ? $query->row_array() : array();
    }

    /**
     * Sync one admission payment record with online_admissions table
     */
    public function syncAdmissionPayment($admission_id)
    {
        $payment = $this->db->where('admission_id', $admission_id)->get('online_admission_payment')->row();
        if (!empty($payment)) {
            $this->db->where('id', $admission_id);
            $this->db->update('online_admissions', array(
                'paid_status' => 1,
                'form_status' => 1,
                'updated_at'  => date('Y-m-d H:i:s')
            ));
            return true;
        }
        return false;
    }

    /**
     * Sync all unsynced admission payments
     */
    public function syncAllUnsyncedAdmissions()
    {
        $sql = "SELECT DISTINCT p.admission_id 
                FROM online_admission_payment p 
                JOIN online_admissions o ON o.id = p.admission_id 
                WHERE o.paid_status = 0 OR o.paid_status IS NULL";
        $res = $this->db->query($sql);
        $records = $res ? $res->result_array() : array();
        $count = 0;
        foreach ($records as $r) {
            $this->db->where('id', $r['admission_id']);
            $this->db->update('online_admissions', array(
                'paid_status' => 1,
                'form_status' => 1,
                'updated_at'  => date('Y-m-d H:i:s')
            ));
            $count++;
        }
        return $count;
    }

    /**
     * Get overall statistics for summary widgets
     */
    public function getSummaryStats()
    {
        // Admission payments
        $adm_stats = $this->db->query("
            SELECT 
                COUNT(*) as total_count,
                IFNULL(SUM(paid_amount), 0) as total_amount
            FROM online_admission_payment
        ");
        $adm_row = $adm_stats ? $adm_stats->row_array() : array();

        // Unsynced count
        $unsynced = $this->db->query("
            SELECT COUNT(DISTINCT p.admission_id) as unsynced_count
            FROM online_admission_payment p
            JOIN online_admissions o ON o.id = p.admission_id
            WHERE o.paid_status = 0 OR o.paid_status IS NULL
        ");
        $unsynced_row = $unsynced ? $unsynced->row_array() : array();

        // Atom / App transactions
        $has_atom_status = $this->db->field_exists('response_status', 'atom_transaction');
        if ($has_atom_status) {
            $atom_stats = $this->db->query("
                SELECT 
                    COUNT(*) as total_count,
                    IFNULL(SUM(CASE WHEN amt REGEXP '^[0-9]+(\.[0-9]+)?$' THEN amt ELSE 0 END), 0) as total_amount,
                    SUM(CASE WHEN response_status = 'SUCCESS' THEN 1 ELSE 0 END) as success_count,
                    SUM(CASE WHEN response_status = 'FAILED' THEN 1 ELSE 0 END) as failed_count,
                    SUM(CASE WHEN response_status = 'CANCELLED' THEN 1 ELSE 0 END) as cancelled_count
                FROM atom_transaction
            ");
        } else {
            $atom_stats = $this->db->query("
                SELECT 
                    COUNT(*) as total_count,
                    IFNULL(SUM(CASE WHEN amt REGEXP '^[0-9]+(\.[0-9]+)?$' THEN amt ELSE 0 END), 0) as total_amount,
                    SUM(CASE WHEN f_code IN ('OTS0000', 'Ok') OR (amt > 0 AND (f_code NOT IN ('OTS0600', 'F', 'OTS0101') OR f_code IS NULL)) THEN 1 ELSE 0 END) as success_count,
                    SUM(CASE WHEN f_code IN ('OTS0600', 'F') THEN 1 ELSE 0 END) as failed_count,
                    SUM(CASE WHEN f_code = 'OTS0101' THEN 1 ELSE 0 END) as cancelled_count
                FROM atom_transaction
            ");
        }
        $atom_row = $atom_stats ? $atom_stats->row_array() : array();

        // Enquiry payments
        $enq_stats = $this->db->query("
            SELECT 
                COUNT(*) as total_count,
                IFNULL(SUM(paid_amount), 0) as total_amount
            FROM enquiry_form_payment
        ");
        $enq_row = $enq_stats ? $enq_stats->row_array() : array();

        return array(
            'admission_count'     => isset($adm_row['total_count']) ? (int)$adm_row['total_count'] : 0,
            'admission_amount'    => isset($adm_row['total_amount']) ? (float)$adm_row['total_amount'] : 0,
            'unsynced_count'      => isset($unsynced_row['unsynced_count']) ? (int)$unsynced_row['unsynced_count'] : 0,
            'atom_total_count'    => isset($atom_row['total_count']) ? (int)$atom_row['total_count'] : 0,
            'atom_total_amount'   => isset($atom_row['total_amount']) ? (float)$atom_row['total_amount'] : 0,
            'atom_success_count'  => isset($atom_row['success_count']) ? (int)$atom_row['success_count'] : 0,
            'atom_failed_count'   => isset($atom_row['failed_count']) ? (int)$atom_row['failed_count'] : 0,
            'atom_cancelled_count'=> isset($atom_row['cancelled_count']) ? (int)$atom_row['cancelled_count'] : 0,
            'enquiry_count'       => isset($enq_row['total_count']) ? (int)$enq_row['total_count'] : 0,
            'enquiry_amount'      => isset($enq_row['total_amount']) ? (float)$enq_row['total_amount'] : 0,
        );
    }
}
