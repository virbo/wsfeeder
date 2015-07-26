<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 /**
 * WS Client Feeder Mhs Models for EPSBED
 * 
 * @author      Yusuf Ayuba
 * @copyright   2015
 * @Url         http://jago.link
 * @Github      https://github.com/virbo/wsfeeder
 * 
 */
   class M_mhs extends CI_Model {
       
       private $table_utama = 'MSMHS';
       private $table_tamu = 'MSPST';
       private $primary_key = 'NIMHSMSMHS';
       
       
       function __construct() {
           parent::__construct();
       }
       
       //($this->dir_epsbed,$awal_masuk,$prodi,$this->kode_pt,$stat_masuk)->result();
       function get_all($dir,$smt,$pst,$kode_pt,$stat_masuk)
       {
           $this->db->select("*")
                //->from("{oj ".$dir.$this->table_utama)
                ->from($dir.$this->table_utama)
                //->join($dir.$this->table_tamu,$this->table_tamu.".KDPTIMSPST=".$this->table_utama.".KDPTIMSMHS}","LEFT OUTER")
                ->where($this->table_utama.".TAHUNMSMHS",$smt)
                //->where($this->table_utama.".SMAWLMSMHS",$smt)
                ->where($this->table_utama.".KDPSTMSMHS",$pst)
                ->where($this->table_utama.".KDPTIMSMHS",$kode_pt)
                ->where($this->table_utama.".STPIDMSMHS",$stat_masuk);
           $result = $this->db->get();     
           return $result;
           //var_dump($result);
       }
       
       function get_by($dir,$cari,$kode_pt)
       {
           $this->db->select("*")
                ->from($dir.$this->table_utama)
                ->join($dir.$this->table_tamu,$this->table_tamu.".KDPTIMSPST=".$this->table_utama.".KDPTIMSMHS","LEFT OUTER")
                ->where($this->table_utama.".KDPTIMSMHS",$kode_pt)
                ->where($this->table_utama.".NIMHSMSMHS",$cari)
                ->or_where($this->table_utama.".NMMHSMSMHS",$cari);
           $result = $this->db->get();
           return $result;           
       }
   }
   
/* End of file m_mhs.php */
/* Location: ./application/models/m_mhs.php */