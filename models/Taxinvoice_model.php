    

<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}


class Taxinvoice_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();

        $this->current_session      = $this->setting_model->getCurrentSession();

        $this->current_session_name = $this->setting_model->getCurrentSessionName();

        $this->start_month          = $this->setting_model->getStartMonth();
    }


    public function gettaxinvoicelist($id = null)
    {
        $sql = "select tax_invoice.id AS tax_invoice_id, tax_invoice.invoice_no, tax_invoice.totalAmount, tax_invoice.taxableValue, tax_invoice.totaltaxValue, tax_invoice.totalinvoiceamount, tax_invoice.invoice_date, tax_invoice.recipient_id,  
                        service_recipient.service_recipient,service_recipient.address,service_recipient.gstin_no,service_recipient.pan_no,invoice_items.description, invoice_items.sac, invoice_items.unit, invoice_items.total, invoice_items.taxable_value, 
                        invoice_items.cgst_rate, invoice_items.cgst_amount, invoice_items.sgst_rate, invoice_items.sgst_amount, 
                        invoice_items.igst_rate, invoice_items.igst_amount from tax_invoice inner join
                        invoice_items on invoice_items.tax_invoice_id = tax_invoice.id inner join
                        service_recipient on service_recipient.id = tax_invoice.recipient_id";

        // if ($id != null)
        //     $sql .= " WHERE tax_invoice_id = " . intval($id);

        if ($id !== null) {
            $sql .= " WHERE tax_invoice.id = " . intval($id);

            $query = $this->db->query($sql);
            return $query->result_array();
        }

        //  $sql .= " ORDER BY tax_invoice_id DESC";

        $this->datatables->query($sql)
            ->orderable('tax_invoice.id,null')
            ->searchable('service_recipient.service_recipient,tax_invoice.invoice_no,null')
            ->query_where_enable(TRUE);
            

        return $this->datatables->generate('json');
    }

    public function next_invoice_id()
    {
        $this->db->select('max(`invoice_no`) as invoice_no');

        $this->db->from('tax_invoice');

        $query = $this->db->get();

        return $query->row_array();
    }


    public function check_invoice_no_exists($invoice_no)

    {
        $this->db->where('invoice_no', $invoice_no);

        $query = $this->db->get('tax_invoice'); 
        
        return $query->num_rows() > 0;
    }



    public function save_invoice($data)
    {
        $this->db->insert('tax_invoice', $data);

        return $this->db->insert_id();
    }

    public function save_invoice_items($data)
    {
        $this->db->insert_batch('invoice_items', $data);
    }
}
