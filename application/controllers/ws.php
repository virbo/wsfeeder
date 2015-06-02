<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * WS Client Feeder Ws Module
 * 
 * @author      Yusuf Ayuba
 * @copyright   2015
 * @Url         http://jago.link
 * @Github      https://github.com/virbo/wsfeeder
 * 
 */
 
class Ws extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('login')) {
            redirect('welcome');
        }
    }
    
    public function index()
    {
        $this->login();
    }

    public function login()
    {
        if ($this->input->post()) {
            $this->form_validation->set_rules('inputWs', 'URL Webservice', 'trim|required');
            $this->form_validation->set_rules('inputUsername', 'Username Feeder', 'trim|required');
            $this->form_validation->set_rules('inputPassword', 'Password Feeder', 'required');
            
            $ws = $this->input->post('inputWs', TRUE);
            $username = $this->input->post('inputUsername', TRUE);
            $password = $this->input->post('inputPassword', TRUE);
            
            if($this->form_validation->run() == TRUE) {
                $ws_client = new nusoap_client($ws, true);
                
                $temp_proxy = $ws_client->getProxy();
                $temp_error = $ws_client->getError();
                
                //var_dump($temp_error);
                
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
                        $filter_sp = "npsn = '".$username."'";
                        $temp_sp = $temp_proxy->getrecord($temp_token,'satuan_pendidikan',$filter_sp);
                        $id_sp = $temp_sp['result']['id_sp'];
                        $sessi = array('login' => TRUE, 
                                        'ws' => $ws,
                                        'token' => $temp_token,
                                        'username' => $username,
                                        'password' => $password,
                                        'url' => base_url(),
                                        'id_sp' => $id_sp);
                                        
                        $this->session->sess_expiration = '900'; //session expire 15 Minutes
                        $this->session->sess_expire_on_close = 'true';
                        
                        $this->session->set_userdata($sessi);
                        redirect('welcome','refresh');
                    }
                }
            }
        }
        tampil('__f_login');
    }
}

/* End of file ws.php */
/* Location: ./application/controllers/ws.php */