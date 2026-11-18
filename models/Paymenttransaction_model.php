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
     */
    public function getOnlineAdmissionPayments($search = null, $date_from = null, $date_to = null, $sync_status = null)
    {
        $this->db->select('
            p.id,
            p.admission_id,
            p.paid_amount,
            p.payment_mode,
            p.payment_type,
            p.transaction_id,
            p.note,
            p.date,
            p.status as payment_status,
            p.order_id,
            p.raw_response,
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
            (CASE WHEN o.paid_status = 1 THEN "synced" ELSE "unsynced" END) AS sync_status
        ');
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
        return $query->result_array();
    }

    /**
     * Get Atom / App transactions
     */
    public function getAtomTransactions($search = null, $date_from = null, $date_to = null, $status = null)
    {
        $this->db->select('
            t.*,
            st.admission_no,
            st.firstname as st_firstname,
            st.lastname as st_lastname,
            st.mobileno as st_mobileno,
            st.email as st_email
        ');
        $this->db->from('atom_transaction t');
        $this->db->join('students st', 'st.id = t.student_id', 'left');

        if (!empty($date_from)) {
            $this->db->where('DATE(t.date) >=', date('Y-m-d', strtotime($date_from)));
        }
        if (!empty($date_to)) {
            $this->db->where('DATE(t.date) <=', date('Y-m-d', strtotime($date_to)));
        }
        if (!empty($status)) {
            if ($status == 'SUCCESS') {
                $this->db->group_start();
                $this->db->where('t.response_status', 'SUCCESS');
                $this->db->or_where('t.f_code', 'OTS0000');
                $this->db->or_where('t.f_code', 'Ok');
                $this->db->or_where('(t.response_status IS NULL AND t.amt > 0)');
                $this->db->group_end();
            } elseif ($status == 'FAILED') {
                $this->db->group_start();
                $this->db->where('t.response_status', 'FAILED');
                $this->db->or_where('t.f_code', 'OTS0600');
                $this->db->or_where('t.f_code', 'F');
                $this->db->group_end();
            } elseif ($status == 'CANCELLED') {
                $this->db->group_start();
                $this->db->where('t.response_status', 'CANCELLED');
                $this->db->or_where('t.f_code', 'OTS0101');
                $this->db->group_end();
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
        return $query->result_array();
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
        return $query->result_array();
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
        return $query->result_array();
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
        return $query->result_array();
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
        return $query->row_array();
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
        $records = $this->db->query($sql)->result_array();
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
        ")->row_array();

        // Unsynced count
        $unsynced = $this->db->query("
            SELECT COUNT(DISTINCT p.admission_id) as unsynced_count
            FROM online_admission_payment p
            JOIN online_admissions o ON o.id = p.admission_id
            WHERE o.paid_status = 0 OR o.paid_status IS NULL
        ")->row_array();

        // Atom / App transactions
        $atom_stats = $this->db->query("
            SELECT 
                COUNT(*) as total_count,
                IFNULL(SUM(CASE WHEN amt REGEXP '^[0-9]+(\.[0-9]+)?$' THEN amt ELSE 0 END), 0) as total_amount,
                SUM(CASE WHEN response_status = 'SUCCESS' OR f_code IN ('OTS0000', 'Ok') OR (response_status IS NULL AND amt > 0) THEN 1 ELSE 0 END) as success_count,
                SUM(CASE WHEN response_status = 'FAILED' OR f_code IN ('OTS0600', 'F') THEN 1 ELSE 0 END) as failed_count,
                SUM(CASE WHEN response_status = 'CANCELLED' OR f_code = 'OTS0101' THEN 1 ELSE 0 END) as cancelled_count
            FROM atom_transaction
        ")->row_array();

        // Enquiry payments
        $enq_stats = $this->db->query("
            SELECT 
                COUNT(*) as total_count,
                IFNULL(SUM(paid_amount), 0) as total_amount
            FROM enquiry_form_payment
        ")->row_array();

        return array(
            'admission_count'     => $adm_stats['total_count'] ?? 0,
            'admission_amount'    => $adm_stats['total_amount'] ?? 0,
            'unsynced_count'      => $unsynced['unsynced_count'] ?? 0,
            'atom_total_count'    => $atom_stats['total_count'] ?? 0,
            'atom_total_amount'   => $atom_stats['total_amount'] ?? 0,
            'atom_success_count'  => $atom_stats['success_count'] ?? 0,
            'atom_failed_count'   => $atom_stats['failed_count'] ?? 0,
            'atom_cancelled_count'=> $atom_stats['cancelled_count'] ?? 0,
            'enquiry_count'       => $enq_stats['total_count'] ?? 0,
            'enquiry_amount'      => $enq_stats['total_amount'] ?? 0,
        );
    }
}
