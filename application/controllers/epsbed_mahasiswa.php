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
            
            $this->tabel = 'mahasiswa';
            $this->kode_pt = $this->session->userdata('kode_pt');
            $this->dir_epsbed = read_file('epsbed.ini');
            
            $this->load->model('m_mhs','mhs');
            $this->load->model('m_pst','pst');
            $this->load->model('m_feeder','feeder');
            
            $this->load->helper('directory');
            $this->load->helper('csv');
            
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
    
    public function insert()
    {
        $kd_mhs = $this->input->post('kd_mhs');
        //var_dump($kd_mhs);
        //echo $this->dir_epsbed;
        if ($kd_mhs!='') {
            $temp_data = array();
            
            $filter_sms = "kode_prodi='".$this->session->userdata('kode_prodi')."'";
            $temp_sms = $this->feeder->getrecord($this->session->userdata('token'), 'sms', $filter_sms);

            $temp_sms['result']?$id_sms=$temp_sms['result']['id_sms']:$id_sms=$temp_sms['error_desc'];
            
            foreach ($kd_mhs as $value) {
                $temp_rec = $this->mhs->get_by($this->dir_epsbed,$value,$this->kode_pt)->result();
                //var_dump($temp_rec);
                foreach ($temp_rec as $row) {
                    //echo $row->KDPSTMSMHS;
                    if ($row->STPIDMSMHS=='B') {
                        $temp_data[] = array('nm_pd' => $row->NMMHSMSMHS,
                                                'jk' => $row->KDJEKMSMHS,
                                         'tgl_lahir' => date("Y-m-d", strtotime($row->TGLHRMSMHS)),
                                          'id_agama' => 99,
                                             'id_kk' => 0,
                                             'id_sp' => $this->session->userdata('id_sp'),
                                            'ds_kel' => $row->TPLHRMSMHS,
                                            'id_wil' => '000000',
                                      'a_terima_kps' => 0,
                                           'stat_pd' => $row->STMHSMSMHS,
                          'id_kebutuhan_khusus_ayah' => 0,
                                    'nm_ibu_kandung' => $row->NMMHSMSMHS,
                           'id_kebutuhan_khusus_ibu' => 0,
                                   'kewarganegaraan' => 'ID',
                                      'regpd_id_sms' => $id_sms,
                                       'regpd_id_sp' => $this->session->userdata('id_sp'),
                               'regpd_id_jns_daftar' => 1,
                                        'regpd_nipd' => $row->NIMHSMSMHS,
                                'regpd_tgl_masuk_sp' => date("Y-m-d", strtotime($row->TGMSKMSMHS)),
                               'regpd_a_pernah_paud' => 0,
                                 'regpd_a_pernah_tk' => 0,
                                   'regpd_mulai_smt' => $row->SMAWLMSMHS
                        );    
                    } else {
                        $temp_data[] = array('nm_pd' => $row->NMMHSMSMHS,
                                            'jk' => $row->KDJEKMSMHS,
                                     'tgl_lahir' => date("Y-m-d", strtotime($row->TGLHRMSMHS)),
                                      'id_agama' => 99,
                                         'id_kk' => 0,
                                         'id_sp' => $this->session->userdata('id_sp'),
                                        'ds_kel' => $row->TPLHRMSMHS,
                                        'id_wil' => '000000',
                                  'a_terima_kps' => 0,
                                       'stat_pd' => $row->STMHSMSMHS,
                      'id_kebutuhan_khusus_ayah' => 0,
                                'nm_ibu_kandung' => $row->NMMHSMSMHS,
                       'id_kebutuhan_khusus_ibu' => 0,
                               'kewarganegaraan' => 'ID',
                                  'regpd_id_sms' => $id_sms,
                                   'regpd_id_sp' => $this->session->userdata('id_sp'),
                           'regpd_id_jns_daftar' => 2,
                                    'regpd_nipd' => $row->NIMHSMSMHS,
                            'regpd_tgl_masuk_sp' => date("Y-m-d", strtotime($row->TGMSKMSMHS)),
                           'regpd_a_pernah_paud' => 0,
                             'regpd_a_pernah_tk' => 0,
                               'regpd_mulai_smt' => $row->SMAWLMSMHS,
                              'regpd_sks_diakui' => number_format($row->SKSDIMSMHS)
                    );
                    }
                }
            }
            $temp_result = $this->feeder->insertrset($this->session->userdata['token'], $this->tabel, $temp_data);
            //var_dump($temp_result['result']);
            //var_dump($temp_data);
            $sukses_count = 0;
            $error_count = 0;
            $error_msg = array();
            $i=0;
            
            if ($temp_result['result']) {
                foreach ($temp_result['result'] as $key) {
                    ++$i;
                    if ($key['error_desc']==NULL) {
                        ++$sukses_count;
                    } else {
                        ++$error_count;
                        $error_msg[] = "<h4>Error</h4>".$key['error_desc']." (".$key['nm_pd']." / ".$key['tgl_lahir'].")";
                    }
                }
            } else {
                echo "<div class=\"alert alert-danger\" role=\"alert\">
                          <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">
                              <span aria-hidden=\"true\">&times;</span>
                           </button>
                          <h4>Error</h4>";
                          echo $temp_result['error_desc']."</div>";
            }
            
            if ((!$sukses_count==0) || (!$error_count==0)) {
                echo "<div class=\"alert alert-warning\" role=\"alert\">
                           <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">
                              <span aria-hidden=\"true\">&times;</span>
                           </button>
                           Results (total ".$i." baris data):<br />
                           <font color=\"#3c763d\">".$sukses_count." data Mahasiswa baru berhasil ditambah</font><br />
                           <font color=\"#ce4844\" >".$error_count." data Mahasiswa baru gagal ditambahkan </font>";
                           if (!$error_count==0) {
                               echo "<a data-toggle=\"collapse\" href=\"#collapseExample\" aria-expanded=\"false\" aria-controls=\"collapseExample\">
                                          Detail error
                                     </a>";
                           }
                           echo "<div class=\"collapse\" id=\"collapseExample\">";
                                     foreach ($error_msg as $pesan) {
                                         echo "<div class=\"bs-callout bs-callout-danger\">
                                                   ".$pesan."
                                               </div><br />";
                                     }   
                           echo "</div>
                       </div>";
            }
        }/* else {
            redirect('epsbed_mahasiswa');
        }*/
        
    }
    
    public function epsbed()
    {
        $temp_smt = $this->feeder->getrset($this->session->userdata('token'), 
                                                        'semester', $this->filter, 
                                                        'nm_smt DESC', $this->limit, 
                                                        $this->offset
                                                     );
        //var_dump($temp_smt);
        //echo "Error ".$temp_smt['error_desc'];
        $data['smt'] = $temp_smt['result'];
        $data['error'] = $temp_smt['error_desc'];
        tampil('epsbed/mahasiswa/__view_mahasiswa',$data);
    }

    public function get_prodi()
    {
        $cari = $this->input->get('q');
        $cari==''?$temp_cari='':$temp_cari=$cari;
        $temp_rec = $this->pst->get_by($this->dir_epsbed,$temp_cari,$this->kode_pt)->result_array();
        //var_dump($temp_rec);
        echo json_encode($temp_rec);
    }
    
    public function filter($prodi='',$stat_masuk='',$awal_masuk='')
    {
        //echo $prodi."-".$stat_masuk."-".$awal_masuk;
        $this->session->set_userdata('kode_prodi',$prodi);
        $temp_rec = $this->mhs->get_all($this->dir_epsbed,$awal_masuk,$prodi,$this->kode_pt,$stat_masuk)->result();
        //var_dump($temp_rec);
        $temp_result='';
        $i=0;
        foreach ($temp_rec as $value) {
            $temp_result .= "<tr>";
            $temp_result .=    "<td>".++$i."</td>";
            $temp_result .=    "<td>";
            $temp_result .=    "    <input type=\"checkbox\" name=\"kd_mhs[]\" id=\"kd_mhs-".$i."\" value=\"".$value->NIMHSMSMHS."\">";                                               
            $temp_result .=    "</td>";
            $temp_result .=    "<td>".$value->NIMHSMSMHS."</td>";
            $temp_result .=    "<td>".$value->NMMHSMSMHS."</td>";
            $temp_result .=    "<td>".$value->TGLHRMSMHS."</td>";
            //$temp_result .=    "<td>".$value->NMPSTMSPST."</td>";
            $temp_result .=    "<td>".($value->STPIDMSMHS=='B'?'Peserta didik baru':'Pindahan')."</td>";
            $temp_result .=    "<td>".$value->SMAWLMSMHS."</td>";
            $temp_result .=    "<td>".$value->TAHUNMSMHS."</td>";
            $temp_result .=    "<td>".$value->STMHSMSMHS."</td>";
            //$temp_result .=    "<td><div id=\"import\"></div></td>";
            $temp_result .= "</tr>";
        }
        
        echo $temp_result;
    }
}

/* End of file epsbed_mahasiswa.php */
/* Location: ./application/controllers/epsbed_mahasiswa.php */