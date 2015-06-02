<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * WS Client Feeder Welcome Module
 * 
 * @author      Yusuf Ayuba
 * @copyright   2015
 * @Url         http://jago.link
 * @Github      https://github.com/virbo/wsfeeder
 * 
 */
 
class Welcome extends CI_Controller {
	    
    //private $data;
    private $limit;
    private $filter;
    private $order;
    private $offset;
    
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
            $this->load->model('m_feeder','feeder');
        }
    }
    
	public function index()
	{
		$this->listprodi();
	}
    
    public function listtable()
    {
        $temp_table = $this->feeder->listtable($this->session->userdata('token'));
        //$data['pagination'] = '';
        $data['listtable'] = $temp_table;
        tampil('__listtabel',$data);
       
    }
    
    public function listprodi()
    {
        $temp_sp = $this->session->userdata('id_sp');
                
        $filter_sms= "id_sp = '".$temp_sp."'";
        $temp_prodi = $this->feeder->getrset($this->session->userdata('token'), 
                                                        'sms', $filter_sms, 
                                                        '', $this->limit, 
                                                        ''
                                                     );
       // var_dump($temp_pt);
        
        $data['listprodi'] = $temp_prodi['result'];
        tampil('__listprodi',$data);
    }
    
    public function listdir($table='')
    {
        isset($table)?$tables=$table:$tables='mahasiswa';
        $temp_dic = $this->feeder->getdic($this->session->userdata('token'), $tables);
        $data['listdic'] = $temp_dic;
        $data['tabel'] = $tables;
        $this->load->view('tpl/__listdic',$data);
    }
    
    public function view($table='',$offset=0)
    {
        isset($table)?$tables=$table:$tables='mahasiswa';
        $temp_dic = $this->feeder->getdic($this->session->userdata('token'), $tables);
        $temp_rec = $this->feeder->getrset($this->session->userdata('token'), 
                                                        $tables, $this->filter, 
                                                        $this->order, $this->limit, 
                                                        $offset
                                                     );
        $temp_count = $this->feeder->count_all($this->session->userdata('token'), $tables);
        
        //pagination
        $config['base_url'] = site_url('welcome/view');
        $config['total_rows'] = $temp_count['result'];
        $config['per_page'] = $this->limit;
        $config['uri_segment'] = 3;
        $this->pagination->initialize($config);
        //
        $data['pagination'] = $this->pagination->create_links();
        $data['offset'] = $offset;
        $data['tabel'] = $tables;
        $data['listsdic'] = $temp_dic;
        $data['listsrec'] = $temp_rec;
        $data['total'] = $temp_count;
        $this->load->view('tpl/__listrec',$data);
        
    }
    
    public function token($uri='')
    {
        if (!empty($uri)) {
            $temp_uri = explode('-', $uri);
            $new_uri = $temp_uri[0].'/'.$temp_uri[1];
            
            $temp_token = $this->feeder->new_token($this->session->userdata('username'),$this->session->userdata('password'));
            $this->session->set_userdata('token',$temp_token);
            redirect(base_url().'index.php/'.$new_uri);
        }
    }
    
    public function set_koneksi($koneksi='',$uri)
    {
        if (!empty($koneksi)) {
            $temp_pecah = explode('/ws/', $this->session->userdata('ws'));
            $url = $temp_pecah[0].'/ws/'.$koneksi.'.php?wsdl';
            
            $temp_uri = explode('-', $uri);
            $new_uri = $temp_uri[0].'/'.$temp_uri[1];
            echo $new_uri;
            
            $this->session->set_userdata('ws',$url);
            redirect(base_url().'index.php/'.$new_uri);
            
        } else {
            redirect('ws');
        }
    }
    
    public function logout()
    {
        $this->session->sess_destroy();
        redirect('ws');
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */