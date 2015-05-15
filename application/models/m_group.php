<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
   /*
	* @author : Yusuf Ayuba 
	* 
    */
   
   class M_group extends CI_Model {
       
	   private $primary_key = 'id_group';
	   private $table_name = 'user_group';
	   
       function __construct() {
           parent::__construct();
       }
	   
	   function get_group() {
	   	  return $this->db->get($this->table_name);
	   }
	   
	   function get_by_id($id) {
	   	   $this->db->where($this->primary_key,$id);
		   return $this->db->get($this->table_name);
	   }
   }
   
/* End of file m_group.php */
/* Location: ./application/models/m_group.php */