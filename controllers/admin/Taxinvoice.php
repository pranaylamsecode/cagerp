<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Taxinvoice extends Admin_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->sch_setting_detail = $this->setting_model->getSetting();
        $this->load->model("servicerecipient_model");
        $this->load->model("taxinvoice_model");
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('tax_invoice', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Rent System');
        $this->session->set_userdata('sub_menu', 'taxinvoice/index');
        $data['title'] = 'Add Tax Invoice';
        $data['title_list'] = 'Tax Invoice lists';
        $data['tax_invoices'] = $this->taxinvoice_model->gettaxinvoicelist();
        $this->load->view('layout/header', $data);
        $this->load->view('admin/taxinvoice/taxinvoiceList', $data);
        $this->load->view('layout/footer', $data);
    }

    public function create()
    {
        $this->session->set_userdata('top_menu', 'Rent System');
        $this->session->set_userdata('sub_menu', 'taxinvoice/index');
        $data['title'] = 'Add Tax Invoice';
        $data['title_list'] = 'Tax Invoice lists';

        $itemcategory = $this->servicerecipient_model->get();
        $data['itemcatlist'] = $itemcategory;

        $this->load->view('layout/header', $data);
        $this->load->view('admin/taxinvoice/taxinvoiceCreate', $data);
        $this->load->view('layout/footer', $data);
    }

    public function check()
    {

        // $id = "2";
        // $result = array();
        // if ($id != "") {
        //     $result = $this->servicerecipient_model->get($id);
        // }
        // $result_final = array('id' => $result['id'], 'address' => $result['address'], 'gstin_no' => $result['gstin_no'], 'status' => 'success');

        // print_r($result_final);




        $tax_invoice_id = "2";
        $invoiceLIST = $this->taxinvoice_model->gettaxinvoicelist($tax_invoice_id);

        echo "<pre>";
        print_r($invoiceLIST);




        // $invoice_data = [
        //     'recipient_id' => "2",
        //     'invoice_no' => "123",
        // 'invoice_date' => date("Y-m-d"),
        // ];

        //You can add logic here to save the invoice in the database
        // $this->taxinvoice_model->save_invoice($invoice_data);

        // echo "successful";




        // $setting_result         = $this->setting_model->get();
        // $sch_setting_detail = $this->sch_setting_detail;
        // echo "<pre>";
        // print_r($sch_setting_detail);


        // $m = $this->taxinvoice_model->gettaxinvoicelist();
        // $m = json_decode($m);
        // echo "<pre>";
        // print_r($m);
        // echo "<pre>";
        // print_r(count($m->data));


        // $date= $this->customlib->dateFormatToYYYYMMDD('17-04-2025');
        // print_r($date);
    }


    public function getUser($id)
    {

        $result = $this->servicerecipient_model->get($id);

        $result_final = array('id' => $result['id'], 'address' => $result['address'], 'gstin_no' => $result['gstin_no'], 'status' => 'success');
        echo json_encode($result_final);
    }


    function add()
    {
        $this->form_validation->set_rules('name', $this->lang->line('name'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('issue_date', 'Issue Date', 'required|trim|xss_clean');

        $this->form_validation->set_rules('invoice_no', 'Invoice No', 'required|trim|xss_clean|callback_check_invoice_no_unique');

        $this->form_validation->set_rules('description[]', 'Description', 'required');
        $this->form_validation->set_rules('sac[]', 'SAC', 'required');
        $this->form_validation->set_rules('unit[]', 'Unit', 'required');
        $this->form_validation->set_rules('total[]', 'Total', 'required');

        if ($this->form_validation->run() == false) {
            $data = array(
                'name' => form_error('name'),
                'issue_date' => form_error('issue_date'),
                'invoice_no' => form_error('invoice_no'),
                'description[]' => form_error('description[]'),
                'sac[]' => form_error('sac[]'),
                'unit[]' => form_error('unit[]'),
                'total[]' => form_error('total[]'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {

            $name = $this->input->post('name');
            $address = $this->input->post('address');
            $gstin = $this->input->post('gstin');
            $description = $this->input->post('description');
            $sac = $this->input->post('sac');
            $unit = $this->input->post('unit');
            $total = $this->input->post('total');
            $taxable_value = $this->input->post('taxable_value');
            $cgst_rate = $this->input->post('cgst_rate');
            $cgst_amount = $this->input->post('cgst_amount');
            $sgst_rate = $this->input->post('sgst_rate');
            $sgst_amount = $this->input->post('sgst_amount');
            $igst_rate = $this->input->post('igst_rate');
            $igst_amount = $this->input->post('igst_amount');
            $totalAmount = $this->input->post('totalAmount');
            $taxableValue = $this->input->post('taxableValue');
            $totaltaxValue = $this->input->post('totaltaxValue');
            $totalinvoiceamount = $this->input->post('totalinvoiceamount');


            // $invoice = $this->taxinvoice_model->next_invoice_id();

            // if (!empty($invoice))
            //     $invoice_no =  $invoice['invoice_no'] + 1;
            // else
            //     $invoice_no = "1";

            $invoice_no = $this->input->post('invoice_no');

            // $current_date = date("Y-m-d");

            $current_date = $this->customlib->dateFormatToYYYYMMDD($this->input->post('issue_date'));

            $invoice_data = [
                'recipient_id' => $name,
                'invoice_no' => $invoice_no,
                'invoice_date' => $current_date,
                'totalAmount' => $totalAmount,
                'taxableValue' => $taxableValue,
                'totaltaxValue' => $totaltaxValue,
                'totalinvoiceamount' => $totalinvoiceamount,
            ];

            $invoice_id = $this->taxinvoice_model->save_invoice($invoice_data);

            $items_data = [];
            foreach ($description as $index => $desc) {
                $items_data[] = [
                    'tax_invoice_id' => $invoice_id,
                    'description' => $desc,
                    'sac' => $sac[$index],
                    'unit' => $unit[$index],
                    'total' => $total[$index],
                    'taxable_value' => $taxable_value[$index],
                    'cgst_rate' => $cgst_rate[$index],
                    'cgst_amount' => $cgst_amount[$index],
                    'sgst_rate' => $sgst_rate[$index],
                    'sgst_amount' => $sgst_amount[$index],
                    'igst_rate' => $igst_rate[$index],
                    'igst_amount' => $igst_amount[$index],
                ];
            }

            $this->taxinvoice_model->save_invoice_items($items_data);

            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
            echo json_encode($array);
        }
    }


    public function check_invoice_no_unique($invoice_no){

        $exists = $this->taxinvoice_model->check_invoice_no_exists($invoice_no);
        if ($exists) {
            $this->form_validation->set_message('check_invoice_no_unique', 'The Invoice No already exists.');
            return false;
        } else {
            return true;
        }
        
    }

    // public function getitemlist()
    // {
    //     $m = $this->taxinvoice_model->gettaxinvoicelist();
    //     $m = json_decode($m);
    //     $currency_symbol = $this->customlib->getSchoolCurrencyFormat();
    //     $dt_data = array();

    //     if (!empty($m->data)) {
    //         $last_invoice_no = '';
    //         $items = [];
    //         $currentYear = date("y");
    //         $nextYear = date("y", strtotime("+1 year"));
    //         $count = 0;

    //         foreach ($m->data as $key => $value) {
    //             $row = array();

    //             if ($last_invoice_no != $value->invoice_no) {

    //                 if ($last_invoice_no != '') {

    //                     $item_details = implode('<br>', $items);

    //                     $row[] = $last_name;
    //                     $row[] = $last_invoice_no;
    //                     // $row[] = "cges/" . $last_invoice_no . "/" . $currentYear . "-" . $nextYear;
    //                     // $row[] = $last_date;
    //                     $row[] = $item_details;
    //                     $row[] = number_format($last_totalAmount, 2);
    //                     $row[] = number_format($last_taxableValue, 2);
    //                     $row[] = number_format($last_totaltaxValue, 2);
    //                     $row[] = number_format($last_totalinvoiceamount, 2);
    //                     $row[] = "<button data-tax_invoice_id='" . $last_tax_invoice_id . "'  class='btn btn-default btn-xs printDoc' data-placement='left' title='" . $this->lang->line('print') . "' data-toggle='tooltip'><i class='fa fa-print'></i></button>";

    //                     $dt_data[] = $row;
    //                 }

    //                 $last_invoice_no = $value->invoice_no;
    //                 $last_name = $value->service_recipient;
    //                 // $last_date = date('d/m/Y', strtotime($value->invoice_date));
    //                 $items = [];
    //                 $last_totalAmount = $value->totalAmount;
    //                 $last_taxableValue = $value->taxableValue;
    //                 $last_totaltaxValue = $value->totaltaxValue;
    //                 $last_totalinvoiceamount = $value->totalinvoiceamount;
    //                 $last_tax_invoice_id = $value->tax_invoice_id;
    //             }

    //             $items[] = "<strong>Description: </strong>" . $value->description . "<br>" .
    //                 "<strong> SAC: </strong>" . $value->sac . "<br>" .
    //                 "<strong> Unit: </strong>" . $value->unit . "<br>" .
    //                 "<strong> Total: </strong>" . number_format($value->total, 2) . "<br>" .
    //                 "<strong> CGST Amount: </strong>" . number_format($value->cgst_amount, 2) . "<br>" .
    //                 "<strong> SGST Amount: </strong>" . number_format($value->sgst_amount, 2) . "<br>" .
    //                 "<strong> IGST Amount: </strong>" . number_format($value->igst_amount, 2) . "<hr>";
            

    //             $count++;
            
    //         }

    //         if ($last_invoice_no != '') {
    //             $item_details = implode('<br>', $items);
    //             $row[] = $last_name;
    //             $row[] = $last_invoice_no;
    //             // $row[] =  "cges/" . $last_invoice_no . "/" . $currentYear . "-" . $nextYear;
    //             // $row[] = $last_date;
    //             $row[] = $item_details;
    //             $row[] = number_format($last_totalAmount, 2);
    //             $row[] = number_format($last_taxableValue, 2);
    //             $row[] = number_format($last_totaltaxValue, 2);
    //             $row[] = number_format($last_totalinvoiceamount, 2);
    //             $row[] = "<button data-tax_invoice_id='" . $last_tax_invoice_id . "'  class='btn btn-default btn-xs printDoc' data-placement='left' title='" . $this->lang->line('print') . "' data-toggle='tooltip'><i class='fa fa-print'></i></button>";

    //             $dt_data[] = $row;
    //         }
    //     }

    //     $json_data = array(
    //         "draw" => intval($m->draw),
    //         "recordsTotal" => intval($m->recordsTotal),
    //         "recordsFiltered" => intval($m->recordsFiltered),
    //         "data" => $dt_data,
    //     );

    //     echo json_encode($json_data);
    // }

    public function getitemlist()
    {
        $m = $this->taxinvoice_model->gettaxinvoicelist();
        $m = json_decode($m);
        $currency_symbol = $this->customlib->getSchoolCurrencyFormat();
        $dt_data = [];

        if (!empty($m->data)) {
            $grouped = [];

            foreach ($m->data as $item) {
                $id = $item->tax_invoice_id;

                // If this invoice hasn't been grouped yet, initialize its data
                if (!isset($grouped[$id])) {
                    $grouped[$id] = [
                        'name' => $item->service_recipient,
                        'invoice_no' => $item->invoice_no,
                        'totalAmount' => $item->totalAmount,
                        'taxableValue' => $item->taxableValue,
                        'totaltaxValue' => $item->totaltaxValue,
                        'totalinvoiceamount' => $item->totalinvoiceamount,
                        'tax_invoice_id' => $item->tax_invoice_id,
                        'items' => []
                    ];
                }

                // Append item details for this invoice
                $grouped[$id]['items'][] =
                    "<strong>Description:</strong> " . $item->description . "<br>" .
                    "<strong>SAC:</strong> " . $item->sac . "<br>" .
                    "<strong>Unit:</strong> " . $item->unit . "<br>" .
                    "<strong>Total:</strong> " . number_format($item->total, 2) . "<br>" .
                    "<strong>CGST Amount:</strong> " . number_format($item->cgst_amount, 2) . "<br>" .
                    "<strong>SGST Amount:</strong> " . number_format($item->sgst_amount, 2) . "<br>" .
                    "<strong>IGST Amount:</strong> " . number_format($item->igst_amount, 2) . "<hr>";
            }

            // Format the grouped data for DataTables
            foreach ($grouped as $invoice) {
                $row = [];

                $row[] = $invoice['name'];
                $row[] = $invoice['invoice_no'];
                $row[] = implode('', $invoice['items']);
                $row[] = number_format($invoice['totalAmount'], 2);
                $row[] = number_format($invoice['taxableValue'], 2);
                $row[] = number_format($invoice['totaltaxValue'], 2);
                $row[] = number_format($invoice['totalinvoiceamount'], 2);
                // $row[] = "<button data-tax_invoice_id='" . $invoice['tax_invoice_id'] . "' class='btn btn-default btn-xs editDoc' title='Edit'><i class='fa fa-pencil'></i></button>
                // <button data-tax_invoice_id='" . $invoice['tax_invoice_id'] . "' class='btn btn-default btn-xs printDoc' data-placement='left' title='" . $this->lang->line('print') . "' data-toggle='tooltip'><i class='fa fa-print'></i></button>";
                // $row[] = "<button data-tax_invoice_id='" . $invoice['tax_invoice_id'] . "' class='btn btn-default btn-xs printDoc' data-placement='left' title='" . $this->lang->line('print') . "' data-toggle='tooltip'><i class='fa fa-print'></i></button>";
               
                $row[] = "
                                <button data-tax_invoice_id='" . $invoice['tax_invoice_id'] . "' class='btn btn-default btn-xs printDoc' data-placement='left' title='" . $this->lang->line('print') . "' data-toggle='tooltip'>
                    <i class='fa fa-print'></i>
                </button>   <button data-tax_invoice_id='" . $invoice['tax_invoice_id'] . "' class='btn btn-default btn-xs deleteDoc' title='Delete'>
                    <i class='fa fa-trash'></i>
                </button>
                
                ";
            
               
               
                $dt_data[] = $row;
            }
        }

        $json_data = array(
            "draw" => intval($m->draw),
            "recordsTotal" => intval($m->recordsTotal),
            "recordsFiltered" => intval($m->recordsFiltered),
            "data" => $dt_data,
        );

        echo json_encode($json_data);
    }

    public function edit($id)
    {
        $data['invoice'] = $this->taxinvoice_model->gettaxinvoicelist($id);

        if ($this->input->is_ajax_request()) {
            $this->load->view('admin/taxinvoice/edit_modal_form', $data); // Load just the form
        }
        //  else {
        //     $this->load->view('layout/header');
        //     $this->load->view('admin/taxinvoice/edit_full_page', $data); // fallback full page
        //     $this->load->view('layout/footer');
        // }
    }


    public function delete_invoice()
    {
        $invoice_id = $this->input->post('invoice_id');

        if (!empty($invoice_id)) {
            // Delete from invoice_items
            $this->db->where('tax_invoice_id', $invoice_id)->delete('invoice_items');

            // Delete from tax_invoice
            $this->db->where('id', $invoice_id)->delete('tax_invoice');

            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid ID']);
        }
    }




    public function pdf()
    {

        $this->load->helper('pdf_helper');
    }

    public function printInvoiceByGroup()
    {

        $tax_invoice_id = $this->input->post('tax_invoice_id');

        if ($tax_invoice_id) {

            $invoice_data = $this->taxinvoice_model->gettaxinvoicelist($tax_invoice_id);

            if ($invoice_data) {

                $data['invoice_data'] = $invoice_data;

                $setting_result         = $this->setting_model->get();

                $data['settinglist']    = $setting_result;

                $data['sch_setting']    = $this->sch_setting_detail;

                $this->load->view('print/printInvoiceById', $data);
            } else {

                echo "No data found for this invoice.";
            }
        } else {

            echo "Invalid Tax Invoice ID.";
        }
    }
}
