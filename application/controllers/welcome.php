<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * WS Client Feeder Welcome Module
 * 
 * @author      Yusuf Ayuba
 * @copyright   2015
 * @link         http://jago.link
 * @Github      https://github.com/virbo/wsfeeder
 * 
 */
 
class Welcome extends CI_Controller {
	    
    //private $data;
    private $limit;
    private $filter;
    private $order;
    private $offset;
    private $table;
    
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
            $this->table = 'sms';
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
        $data['temp_error'] = $temp_table['error_desc'];
        $data['listtable'] = $temp_table['result'];
        tampil('welcome/__listtabel',$data);
       
    }
    
    public function listprodi($offset=0)
    {
        $temp_sp = $this->session->userdata('id_sp');
                
        $filter_sms= "id_sp = '".$temp_sp."'";
        $temp_prodi = $this->feeder->getrset($this->session->userdata('token'), 
                                                        $this->table, $filter_sms, 
                                                        '', $this->limit, 
                                                        $offset
                                                     );
        
        $temp_count = $this->feeder->count_all($this->session->userdata('token'), $this->table,$filter_sms);
        //var_dump($temp_count);
        //pagination
        $config['base_url'] = site_url('welcome/listprodi');
        $config['total_rows'] = $temp_count['result'];
        $config['per_page'] = $this->limit;
        $config['uri_segment'] = 3;
        $this->pagination->initialize($config);
        //
        $data['pagination'] = $this->pagination->create_links();
        $data['offset'] = $offset;
        
        $data['temp_error'] = $temp_prodi['error_desc'];
        $data['listprodi'] = $temp_prodi['result'];
        $data['total'] = $temp_count['result'];
        $data['ses_id_sp'] = $temp_sp;

        $offset==0? $start=$this->pagination->cur_page: $start=$offset+1;
        $data['start'] = $start;
        $data['end'] = $this->pagination->cur_page * $this->pagination->per_page;

        tampil('welcome/__listprodi',$data);
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
        
        /*$temp_rec_count = $this->feeder->getrset($this->session->userdata('token'), 
                                                        $tables, $this->filter, 
                                                        $this->order, '', 
                                                        $offset
                                                     );*/                                             
        $temp_count = $this->feeder->count_all($this->session->userdata('token'), $tables,$this->filter);
        //$temp_count = count($temp_rec_count['result']);
        //var_dump($temp_count);
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
        $data['total'] = $temp_count['result'];
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
    
    public function setting()
    {
        $temp_npsn = read_file('setting.ini');
        
        if ($this->input->post()) {
            $temp_pt = $this->input->post('inputNpsn', TRUE);
            $this->form_validation->set_rules('inputNpsn', 'Kode PT', 'trim|required');
            
            if($this->form_validation->run() == TRUE) {
                $temp_result = write_file('setting.ini', $temp_pt);
                if ($temp_result) {
                        
                    $filter_sp = "npsn = '".$temp_pt."'";
                    $temp_sp = $this->feeder->getrecord($this->session->userdata['token'],'satuan_pendidikan',$filter_sp);
                    //var_dump($temp_sp);
                    if ($temp_sp['result']) {
                        $id_sp = $temp_sp['result']['id_sp'];
                    } else {
                        $id_sp = '0';
                    }
                    
                    //$this->session->set_userdata('')
                    $sessi = array('kode_pt' => $temp_pt,
                                     'id_sp' => $id_sp
                             );
                    //$this->session->set_userdata('id_sp',$id_sp);
                    $this->session->set_userdata($sessi);
                    $this->session->set_flashdata('sukses','Kode PT berhasil diupdate');
                    redirect(base_url().'index.php/welcome/setting');
                } else {
                    $this->session->set_flashdata('error','Kode PT tidak bisa diupdate. File setting tidak bisa ditulisi');
                    redirect(base_url().'index.php/welcome/setting');
                }
            }
        }
        
        $data['npsn'] = $temp_npsn;
        tampil('welcome/__setting',$data);
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */