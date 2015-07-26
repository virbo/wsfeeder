<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * WS Client Feeder Kelas Perkuliahan Module for EPSBED
 * 
 * @author      Yusuf Ayuba
 * @copyright   2015
 * @Url         http://jago.link
 * @Github      https://github.com/virbo/wsfeeder
 * 
 */
 
class Ajax extends CI_Controller {
        
    //private $data;
    private $limit;
    private $filter;
    private $order;
    private $offset;
    private $kode_pt;
    private $dir_epsbed;
    //private $temp_result;
    
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('login')) {
            redirect('ws');
        } else {
            $this->limit = $this->config->item('limit');
            $this->filter = $this->config->item('filter');
            $this->order = $this->config->item('order');
            $this->offset = $this->config->item('offset');
            
            $this->kode_pt = $this->session->userdata('kode_pt');
            //$this->dir_epsbed = 'C:\DIKTI\\';
            $this->dir_epsbed = read_file('epsbed.ini').'\\';
            //$this->dir_epsbed = 'C:\SIPIL\\';
            
            $this->load->model('m_mhs','mhs');
            $this->load->model('m_pst','pst');
            $this->load->model('m_feeder','feeder');
        }
    }
    
    public function get_prodi()
    {
        $cari = $this->input->get('q');
        $cari==''?$temp_cari='':$temp_cari=$cari;
        $temp_rec = $this->pst->get_by($this->dir_epsbed,$temp_cari,$this->kode_pt)->result_array();
        //var_dump($temp_rec);
        echo json_encode($temp_rec);
    }
}

/* End of file ajax.php */
/* Location: ./application/controllers/ajax.php */