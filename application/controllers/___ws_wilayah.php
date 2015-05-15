<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ws_wilayah extends CI_Controller {
        
    //private $data;
    private $limit;
    private $filter;
    private $order;
    private $offset;
    private $tabel;
    private $tabel2;
    
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
            $this->tabel = 'wilayah';
            
            $this->load->model('m_feeder','feeder');
            $this->load->helper('directory');
        }
    }
    
    public function index()
    {
        $this->view();
    }
    
    public function view($offset=0)
    {
        $temp_dic = $this->feeder->getdic($this->session->userdata('token'), $this->tabel);
        $temp_rec = $this->feeder->getrset($this->session->userdata('token'), 
                                                        $this->tabel, $this->filter, 
                                                        $this->order, $this->limit, 
                                                        $offset
                                                     );
        $temp_count = $this->feeder->count_all($this->session->userdata('token'), $this->tabel);
        
        $temp_rec2 = $this->feeder->getrset($this->session->userdata('token'), 
                                                        $this->tabel2, $this->filter, 
                                                        $this->order, $this->limit, 
                                                        $offset
                                                     );
        

        //pagination
        $config['base_url'] = site_url('ws_wilayah/view');
        $config['total_rows'] = $temp_count['result'];
        $config['per_page'] = $this->limit;
        $config['uri_segment'] = 3;
        $this->pagination->initialize($config);
        //
        $data['pagination'] = $this->pagination->create_links();
        $data['offset'] = $offset;
        $data['listsdic'] = $temp_dic;
        $data['listsrec'] = $temp_rec;
        $data['listsrec2'] = $temp_rec2;
        $data['total'] = $temp_count['result'];
        $data['url_add'] = 'index.php/ws_wilayah/form';

        tampil('__view_add',$data);
    }
    
    public function form($dir)
    {
        //tampil('mahasiswa/__mahasiswa_form',$this->data);
        //echo $dir;
        $temp_dir = directory_map('C:/DIKTI');
        var_dump($temp_dir);
    }
    
    public function getSCV()
    {
        $row = 1;
        if (($handle = fopen(base_url()."FILE/mhs.csv", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $num = count($data);
                echo "<p> $num fields in line $row: <br /></p>\n";
                $row++;
                for ($c=0; $c < $num; $c++) {
                    echo $data[$c] . "<br />\n";
                }
            }
            fclose($handle);
        }
    }
}

/* End of file ws_wilayah.php */
/* Location: ./application/controllers/ws_wilayah.php */