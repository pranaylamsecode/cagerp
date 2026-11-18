<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * 
 */
class Generatecertificate_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    public function getcertificatebyid($certificate) {
        $this->db->select('*');
        $this->db->from('certificates');
        $this->db->where('id', $certificate);
        $query = $this->db->get();
        return $query->result();
    }
    
    public function gettccertificates($student_id) {
        $this->db->select('*');
        $this->db->from('certificate_tc');
        $this->db->where('student_id', $student_id);
        $query = $this->db->get();
        $result = $query->result();
        
        if(isset($result[0])){
            $res = $result[0];
        }else{
            $res = [];
        }
        return $res;
    }

    public function gettccertificateByStudentId($student_id) {
        $this->db->select('*');
        $this->db->from('certificate_tc');
        $this->db->where('student_id', $student_id);
        $query = $this->db->get();
        $result = $query->result();
       
        return $query->result();
    }

    public function getsingletccertificates($student_id) {
      $this->db->where('student_id', $student_id);
      $this->db->limit(1);
      $query = $this->db->get('certificate_tc');
      
      return $query->result();
    }
    
    public function inserttccertificates($student_id) 
    {
        $this->db->select_max('tc_no');
        $query = $this->db->get('certificate_tc');
        $r=$query->result();

        foreach($r as $d)
        {
            $n = $d->tc_no;
        }
        if(isset($n))
        {
            $nput = $n+1;
        }
        else{
           $nput = 0001;
        }
            $data = array(
            'student_id'=>$student_id,
            'tc_no'=>$nput
        );

        $this->db->insert('certificate_tc',$data);

       /* return $this->db->truncate('certificate_tc');*/
        return ($this->db->affected_rows() != 1) ? false : true;
    }
}
?>