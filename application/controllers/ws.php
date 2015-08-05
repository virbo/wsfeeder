<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * WS Client Feeder Ws Module
 * 
 * @author      Yusuf Ayuba
 * @copyright   2015
 * @link         http://jago.link
 * @Github      https://github.com/virbo/wsfeeder
 * 
 */
 
class Ws extends CI_Controller {
    
    private $dir_ws;
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('login')) {
            redirect('welcome');
        }
        //$this->dir_ws  = 'http://localhost:8082/ws/';
        $temp_ws = read_file('epsbed.ini');
        $pecah = explode('#', $temp_ws);
        $this->dir_ws = $pecah[1];
    }
    
    public function index()
    {
        $this->login();
    }

    public function login()
    {
        if ($this->input->post()) {
            //$this->form_validation->set_rules('inputWs', 'URL Webservice', 'trim|required');
            $this->form_validation->set_rules('inputUsername', 'Username Feeder', 'trim|required');
            $this->form_validation->set_rules('inputPassword', 'Password Feeder', 'required');
            
            //$ws = $this->input->post('inputWs', TRUE);
            $username = $this->input->post('inputUsername', TRUE);
            $password = $this->input->post('inputPassword', TRUE);
            $temp_ws = $this->input->post('db_ws');
            
            if($this->form_validation->run() == TRUE) {
                $ws = $temp_ws=='on'?$this->dir_ws.'live.php?wsdl':$this->dir_ws.'sandbox.php?wsdl';
                $ws_client = new nusoap_client($ws, true);
                
                $temp_proxy = $ws_client->getProxy();
                $temp_error = $ws_client->getError();
                if ($temp_proxy==NULL) {
                    $this->session->set_flashdata('error','Gagal melakukan koneksi ke Webservice Feeder.<br /><pre>'.$temp_error.'</pre>');
                    //$this->session->set_flashdata('error',$temp_error);
                    redirect(base_url());
                } else {
                    $temp_token = $temp_proxy->GetToken($username, $password);
                    if ($temp_token=='ERROR: username/password salah') {
                        $this->session->set_flashdata('error',$temp_token);
                        redirect(base_url());
                    } else {
                        //$temp_npsn = substr($username, 0,6);
                        $temp_npsn = read_file('setting.ini');
                        //echo $temp_npsn;
                        
                        $filter_sp = "npsn = '".$temp_npsn."'";
                        $temp_sp = $temp_proxy->getrecord($temp_token,'satuan_pendidikan',$filter_sp);
                        //var_dump($temp_sp);
                        if ($temp_sp['result']) {
                            $id_sp = $temp_sp['result']['id_sp'];
                        } else {
                            $id_sp = '0';
                        }

                        $sessi = array('login' => TRUE, 
                                          'ws' => $ws,
                                       'token' => $temp_token,
                                    'username' => $username,
                                    'password' => $password,
                                         'url' => base_url(),
                                     'kode_pt' => $temp_npsn,
                                       'id_sp' => $id_sp);
                                        
                        $this->session->sess_expiration = '900'; //session expire 15 Minutes
                        $this->session->sess_expire_on_close = 'true';
                        
                        $this->session->set_userdata($sessi);
                        
                        //var_dump($sessi);
                        redirect('welcome');
                    }
                }
            }
        }
        tampil('__f_login');
    }
}

/* End of file ws.php */
/* Location: ./application/controllers/ws.php */