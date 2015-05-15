<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ws_agama extends CI_Controller {
        
    private $ws_client;
    private $temp_proxy;
    private $limit;
    private $filter;
    private $order;
    private $offset;
    private $tabel;
    
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('login')) {
            redirect('ws');
        } else {
            $this->ws_client = new nusoap_client($this->session->userdata('ws'), true);
            $this->temp_proxy = $this->ws_client->getProxy();
            $this->limit = $this->config->item('limit');
            $this->filter = $this->config->item('filter');
            $this->order = $this->config->item('order');
            $this->offset = $this->config->item('offset');
            $this->tabel = 'agama';
            $this->load->model('m_feeder','feeder');
        }
    }
    
    public function index()
    {
        //tampil('welcome');
        $this->view();
    }
    /*
    public function listdic()
    {
        //isset($table)?$tables=$table:$tables='mahasiswa';
        $temp_dir = $this->temp_proxy->GetDictionary($this->session->userdata('token'), $this->tabel);
        $this->data['listdic'] = $temp_dir;
        $this->load->view('tpl/agama/__agama_dic',$this->data);
        //tampil('agama/__agama_dic',$this->data);
    }*/
    
    public function view($offset=0)
    {
        $temp_dir = $this->temp_proxy->GetDictionary($this->session->userdata('token'), $this->tabel);
        $temp_rec = $this->temp_proxy->GetRecordset($this->session->userdata('token'), 
                                                        $this->tabel, $this->filter, 
                                                        $this->order, $this->limit, 
                                                        $offset
                                                     );
        $temp_count = $this->feeder->count_all($this->session->userdata('token'), $this->tabel);
        
        

        //pagination
        $config['base_url'] = site_url('ws_agama/view');
        $config['total_rows'] = $temp_count['result'];
        $config['per_page'] = $this->limit;
        $config['uri_segment'] = 3;
        $this->pagination->initialize($config);
        //
        $data['pagination'] = $this->pagination->create_links();
        $data['offset'] = $offset;
        $data['listsdic'] = $temp_dir;
        $data['listsrec'] = $temp_rec;
        $data['total'] = $temp_count['result'];
        $data['url_add'] = 'index.php/ws_agama/form';
        //$this->load->view('tpl/__listrec',$this->data);
        tampil('__view_add',$data);
    }
    
    public function form()
    {
        //$this->session->sess_destroy();
        //redirect('ws');
    }
}

/* End of file ws_agama.php */
/* Location: ./application/controllers/ws_agama.php */