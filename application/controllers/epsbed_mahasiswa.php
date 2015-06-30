<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * WS Client Feeder Mahasiswa Module for EPSBED
 * 
 * @author      Yusuf Ayuba
 * @copyright   2015
 * @Url         http://jago.link
 * @Github      https://github.com/virbo/wsfeeder
 * 
 */
 
class Epsbed_mahasiswa extends CI_Controller {
        
    //private $data;
    private $limit;
    private $filter;
    private $order;
    private $offset;
    private $tabel;
    private $dir_epsbed;
    //private $temp_result;
    
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('login')) {
            redirect('ws');
        } else {
            //$this->dir_epsbed = 'C:\DIKTI';
            //$dsn = 'DRIVER='.'{'."Microsoft dBase Driver (*.dbf)".'}'.";Dbq=".$this->dir_epsbed;
            $this->limit = $this->config->item('limit');
            $this->filter = $this->config->item('filter');
            $this->order = $this->config->item('order');
            $this->offset = $this->config->item('offset');
            
            $this->load->model('m_feeder','feeder');
            $this->load->helper('directory');
            $this->load->helper('csv');
            //$this->load->library('upload');
            
            //inisial config upload
            $config['upload_path'] = $this->config->item('upload_path');
            $config['allowed_types'] = $this->config->item('upload_tipe');
            $config['max_size'] = $this->config->item('upload_max_size');
            
            $this->load->library('upload',$config);
        }
    }
    
    public function index()
    {
        $this->epsbed();
    }
    
    public function epsbed($offset=0,$order_column='NIMHSMSMHS',$order_type='asc',$cari='')
    {
        $temp_cari = $this->input->post('nm_mhs');
        //$temp_rec = $this->db->query("SELECT * FROM MSMHS JOIN MSPST ON (MSPST.KDPTIMSPST=MSMHS.KDPTIMSMHS)")->result();
        $temp_rec = $this->db->query("SELECT * FROM MSMHS, MSPST WHERE MSPST.KDPTIMSPST=MSMHS.KDPTIMSMHS")->result();
        //$temp_rec = $this->m_msmhs->get_all($this->limit,$offset,$order_column,$order_type,$temp_cari)->result();
        //$temp_tot = $this->m_msmhs->get_count($temp_cari)->num_rows();
        //$temp_tot = $this->m_msmhs->count_all()->num_rows();
        $temp_tot = count($temp_rec);
        //var_dump($temp_rec);
        //echo $temp_tot;
        $data['temp_rec'] = $temp_rec;
        $data['temp_tot'] = $temp_tot;
        $data['temp_cari'] = $temp_cari;
        tampil('epsbed/mahasiswa/__view_mahasiswa',$data);
    }
}

/* End of file epsbed_mahasiswa.php */
/* Location: ./application/controllers/epsbed_mahasiswa.php */