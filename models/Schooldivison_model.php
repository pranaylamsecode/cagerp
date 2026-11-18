<?php



if (!defined('BASEPATH'))

    exit('No direct script access allowed');



class Schooldivison_model extends MY_Model
{



    public function __construct()
    {

        parent::__construct();

        $this->current_session = $this->setting_model->getCurrentSession();
    }



    /**

     * This funtion takes id as a parameter and will fetch the record.

     * If id is not provided, then it will fetch all the records form the table.

     * @param int $id

     * @return mixed

     */

    public function get($id = null)
    {

        $this->db->select()->from('school_divison');

        if ($id != null) {

            $this->db->where('school_divison.id', $id);
        } else {

            $this->db->order_by('school_divison.sl_no');
        }

        $query = $this->db->get();

        if ($id != null) {

            return $query->row_array();
        } else {

            return $query->result_array();
        }
    }
}
