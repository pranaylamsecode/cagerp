<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Paymenttransactions extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('paymenttransaction_model');
        $this->load->model('onlinestudent_model');
        $this->sch_setting_detail = $this->setting_model->getSetting();
        $this->load->library('customlib');
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('online_admission', 'can_view') && !$this->rbac->hasPrivilege('collect_fees', 'can_view')) {
            // Allow if admin role exists
            if (!isset($this->session->userdata['admin'])) {
                access_denied();
            }
        }

        $this->session->set_userdata('top_menu', 'Student Information');
        $this->session->set_userdata('sub_menu', 'admin/paymenttransactions');

        $active_tab = $this->input->get('tab') ? $this->input->get('tab') : 'admission';
        $search     = $this->input->get('search') ? trim($this->input->get('search')) : null;
        $date_from  = $this->input->get('date_from') ? trim($this->input->get('date_from')) : null;
        $date_to    = $this->input->get('date_to') ? trim($this->input->get('date_to')) : null;
        $status     = $this->input->get('status') ? trim($this->input->get('status')) : null;

        $data['title']       = 'Online Payment Transactions Tracking';
        $data['sch_setting'] = $this->sch_setting_detail;
        $data['active_tab']  = $active_tab;
        $data['search']      = $search;
        $data['date_from']   = $date_from;
        $data['date_to']     = $date_to;
        $data['status']      = $status;

        // Summary Stats
        $data['stats'] = $this->paymenttransaction_model->getSummaryStats();

        // Load data based on active tab or preload for quick switching
        $data['admission_payments'] = $this->paymenttransaction_model->getOnlineAdmissionPayments($search, $date_from, $date_to, $status);
        $data['atom_transactions']  = $this->paymenttransaction_model->getAtomTransactions($search, $date_from, $date_to, $status);
        $data['fee_deposits']       = $this->paymenttransaction_model->getOnlineFeeDeposits($search, $date_from, $date_to);
        $data['enquiry_payments']   = $this->paymenttransaction_model->getEnquiryPayments($search, $date_from, $date_to);

        $this->load->view('layout/header', $data);
        $this->load->view('admin/paymenttransactions/index', $data);
        $this->load->view('layout/footer', $data);
    }

    /**
     * AJAX: Sync single admission record
     */
    public function sync_admission()
    {
        $admission_id = $this->input->post('admission_id');
        if (empty($admission_id)) {
            echo json_encode(array('status' => 'fail', 'message' => 'Invalid Admission ID'));
            return;
        }

        $res = $this->paymenttransaction_model->syncAdmissionPayment($admission_id);
        if ($res) {
            echo json_encode(array('status' => 'success', 'message' => 'Student payment status successfully synced to Paid!'));
        } else {
            echo json_encode(array('status' => 'fail', 'message' => 'No payment record found for this admission ID.'));
        }
    }

    /**
     * AJAX: Sync all unsynced admission records
     */
    public function sync_all_admissions()
    {
        $count = $this->paymenttransaction_model->syncAllUnsyncedAdmissions();
        echo json_encode(array(
            'status'  => 'success',
            'count'   => $count,
            'message' => $count > 0 ? "Successfully synced {$count} student admission payment records!" : "All payment records are already up-to-date."
        ));
    }

    /**
     * AJAX: Get details of an admission payment
     */
    public function get_admission_detail()
    {
        $admission_id = $this->input->post('admission_id');
        $payments = $this->paymenttransaction_model->getAdmissionPaymentById($admission_id);
        if (!empty($payments)) {
            echo json_encode(array('status' => 'success', 'data' => $payments));
        } else {
            echo json_encode(array('status' => 'fail', 'message' => 'No record found.'));
        }
    }

    /**
     * AJAX: Get Atom transaction details
     */
    public function get_atom_detail()
    {
        $trans_id = $this->input->post('trans_id');
        $record = $this->paymenttransaction_model->getAtomTransactionById($trans_id);
        if (!empty($record)) {
            echo json_encode(array('status' => 'success', 'data' => $record));
        } else {
            echo json_encode(array('status' => 'fail', 'message' => 'No record found.'));
        }
    }

    /**
     * Export tab data to CSV
     */
    public function export_csv()
    {
        $tab = $this->input->get('tab') ? $this->input->get('tab') : 'admission';
        $filename = 'transactions_' . $tab . '_' . date('Ymd_His') . '.csv';

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');

        if ($tab == 'admission') {
            fputcsv($output, array('ID', 'Ref No', 'Student Name', 'Class', 'Mobile', 'Email', 'Amount', 'Payment Mode', 'Transaction ID', 'Order ID', 'Date', 'Paid Status', 'Form Status'));
            $rows = $this->paymenttransaction_model->getOnlineAdmissionPayments();
            foreach ($rows as $r) {
                fputcsv($output, array(
                    $r['id'],
                    $r['reference_no'],
                    $r['firstname'] . ' ' . $r['lastname'],
                    $r['class'] . ' (' . $r['section'] . ')',
                    $r['mobileno'],
                    $r['email'],
                    $r['paid_amount'],
                    $r['payment_mode'],
                    $r['transaction_id'],
                    $r['order_id'],
                    $r['date'],
                    $r['paid_status'] == 1 ? 'Paid' : 'Unpaid',
                    $r['form_status'] == 1 ? 'Submitted' : 'Not Submitted'
                ));
            }
        } elseif ($tab == 'atom') {
            fputcsv($output, array('Trans ID', 'Date', 'Customer/Student Name', 'Mobile', 'Student ID', 'Amount', 'Status Code', 'Status', 'Message', 'Atom Txn ID', 'Merchant Txn ID', 'Bank Txn ID', 'Bank Name'));
            $rows = $this->paymenttransaction_model->getAtomTransactions();
            foreach ($rows as $r) {
                $status = !empty($r['response_status']) ? $r['response_status'] : ($r['amt'] > 0 ? 'SUCCESS' : 'FAILED');
                fputcsv($output, array(
                    $r['trans_id'],
                    $r['date'],
                    !empty($r['st_firstname']) ? $r['st_firstname'] . ' ' . $r['st_lastname'] : $r['udf1'],
                    $r['udf3'],
                    $r['student_id'],
                    $r['amt'],
                    $r['f_code'],
                    $status,
                    $r['response_message'],
                    $r['mmp_txn'],
                    $r['mer_txn'],
                    $r['bank_txn'],
                    $r['bank_name']
                ));
            }
        } elseif ($tab == 'enquiry') {
            fputcsv($output, array('ID', 'Enquiry ID', 'Candidate Name', 'Contact', 'Amount', 'Mode', 'Transaction ID', 'Date', 'Note'));
            $rows = $this->paymenttransaction_model->getEnquiryPayments();
            foreach ($rows as $r) {
                fputcsv($output, array(
                    $r['id'],
                    $r['enquiry_id'],
                    $r['candidate_name'],
                    $r['contact'],
                    $r['paid_amount'],
                    $r['payment_mode'],
                    $r['transaction_id'],
                    $r['date'],
                    $r['note']
                ));
            }
        }

        fclose($output);
        exit;
    }
}
