<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 /**
 * WS Client Feeder MS Mhs Models for EPSBED
 * 
 * @author      Yusuf Ayuba
 * @copyright   2015
 * @Url         http://jago.link
 * @Github      https://github.com/virbo/wsfeeder
 * 
 */
   class M_msmhs extends CI_Model {
       
       private $table_name = 'MSMHS';
       private $primary_key = 'NIMHSMSMHS';
       
       
       function __construct() {
           parent::__construct();
       }
       
       function get_all($limit=0,$offset=0,$order_column='',$order_type='asc',$cari='') {
        /*
           if (empty($order_column) || empty($order_type))
                $this->db->order_by($this->primary_key,'desc');  
           else
                $this->db->order_by($order_column,$order_type);
          */ 
           if (!empty($cari)) {
              // $this->db->like($this->primary_key,$cari);
              // $this->db->or_like('NMMHSMSMHS',$cari);
              // $this->db->or_like('TPLHRMSMHS',$cari);
              $this->db->where($this->primary_key,$cari);
              $this->db->or_where('NMMHSMSMHS',$cari);
              $this->db->or_where('TPLHRMSMHS',$cari);
              $this->db->or_where('TAHUNMSMHS',$cari);
           }
         
          /*$this->db->select('*')
            ->from($this->table_name)
            ->join('ms_pst',$this->table_name.'.kode_mspst=ms_pst.kode_pst')
            ->join('ref_jenjang',$this->table_name.'.kode_jenjang=ref_jenjang.kode_jenjang')
            ->limit($limit,$offset);*/
            
          $this->db->select('*')
            ->from($this->table_name.',MSPST')
            ->where('MSPST.KDPTIMSPST='.$this->table_name.'.KDPTIMSMHS')
            //->join('MSPST','MSPST.KDPTIMSPST='.$this->table_name.'.KDPTIMSMHS')
            ->limit($limit,$offset);
          $result = $this->db->get();
          return $result;
       }
       
       function get_count() {
        /*
           if (!empty($cari)) {
               // $this->db->like($this->primary_key,$cari);
              // $this->db->or_like('NMMHSMSMHS',$cari);
              // $this->db->or_like('TPLHRMSMHS',$cari);
              $this->db->where($this->primary_key,$cari);
              $this->db->or_where('NMMHSMSMHS',$cari);
              $this->db->or_where('TPLHRMSMHS',$cari);
           }
        */  
          $this->db->select('*')
            ->from($this->table_name);
          $result = $this->db->get();
          return $result;
       }
       
       function get_by_id($id) {
           $this->db->where($this->primary_key,$id);
           return $this->db->get($this->table_name);
       }
       
       function count_all() {
           return $this->db->count_all($this->table_name);
       }
       
       function update($id,$item) {
           $this->db->where($this->primary_key,$id);
           $this->db->update($this->table_name,$item);
       }
       
       function save($item) {
           $this->db->insert($this->table_name,$item);
           return $this->db->insert_id();
       }
   }
   
/* End of file m_msmhs.php */
/* Location: ./application/models/m_msmhs.php */