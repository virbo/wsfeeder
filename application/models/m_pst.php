<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 /**
 * WS Client Feeder Pst Models for EPSBED
 * 
 * @author      Yusuf Ayuba
 * @copyright   2015
 * @Url         http://jago.link
 * @Github      https://github.com/virbo/wsfeeder
 * 
 */
   class M_pst extends CI_Model {
       
       private $table_utama = 'MSPST';
       private $table_tamu = 'TBKOD';
       
       
       function __construct() {
           parent::__construct();
       }
       
       function get_all($dir)
       {
           $this->db->select('*')
                    ->from($dir.$this->table_utama)
                    ->join($dir.$this->table_tamu,$this->table_utama.'.KDJENMSPST='.$this->table_tamu.'.KDKODTBKOD','LEFT OUTER')
                    ->where($this->table_tamu.'.KDAPLTBKOD','01');
           $result = $this->db->get();     
           return $result;
           //var_dump($result);
       }
       
       function get_by($dir,$cari='',$kode_pt)
       {
           /*$this->db->select('*')
                    ->from($dir.$this->table_utama)
                    //->join($dir.$this->table_tamu,$this->table_utama.'.KDJENMSPST='.$this->table_tamu.'.KDKODTBKOD','LEFT OUTER')
                    ->where($this->table_utama.'.KDPTIMSPST',$kode_pt)
                    //->where($this->table_utama.'.NMPSTMSPST',$cari)
                    //->or_where($this->table_utama.'.KDPSTMSPST',$cari);
                    ->where($this->table_utama.'.KDPSTMSPST',$cari);
                    //->where($this->table_tamu.'.KDAPLTBKOD','01')
                    
           $result = $this->db->get();     */

           $result = $this->db->query('SELECT * FROM '.$dir.$this->table_utama.' a
                                      WHERE a.KDPSTMSPST LIKE \'%'.$cari.'%\'
                                      AND a.KDPTIMSPST=\''.$kode_pt.'\'
                                      ');
           return $result;
       }
   }
   
/* End of file m_pst.php */
/* Location: ./application/models/m_pst.php */