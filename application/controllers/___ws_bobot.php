<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ws_bobot extends CI_Controller {
        
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
            $this->limit = $this->config->item('limit');
            $this->filter = $this->config->item('filter');
            $this->order = $this->config->item('order');
            $this->offset = $this->config->item('offset');
            $this->tabel = 'bobot_nilai';
            $this->load->model('m_feeder','feeder');
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
        
        //pagination
        $config['base_url'] = site_url('ws_bobot/view');
        $config['total_rows'] = $temp_count['result'];
        $config['per_page'] = $this->limit;
        $config['uri_segment'] = 3;
        $this->pagination->initialize($config);
        //
        $data['pagination'] = $this->pagination->create_links();
        $data['offset'] = $offset;
        
        $data['listsdic'] = $temp_dic;
        $data['listsrec'] = $temp_rec;
        $data['total'] = $temp_count['result'];
        $data['url_add'] = 'index.php/ws_bobot/form';

        tampil('__view_add',$data);
    }
    
    public function form()
    {
        //$this->session->sess_destroy();
        //redirect('ws');
    }
}

/* End of file ws_bobot.php */
/* Location: ./application/controllers/ws_bobot.php */