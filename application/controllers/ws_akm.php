<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * WS Client Feeder Aktivitas Kuliah Mahasiswa Module
 * 
 * @author      Yusuf Ayuba
 * @copyright   2015
 * @Url         http://jago.link
 * @Github      https://github.com/virbo/wsfeeder
 * 
 */
 
class Ws_akm extends CI_Controller {
        
    //private $data;
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
            //$this->tabel = 'nilai';
            $this->tabel = 'kuliah_mahasiswa';
            
            $this->load->model('m_feeder','feeder');
            $this->load->helper('directory');
            $this->load->helper('csv');
            //$this->load->library('upload');
        }
    }
    
    public function index()
    {
        //$this->tabview();
        $this->akm();
        
    }
    
    public function akm($offset=0,$id_smt='')
    {
        //$id_smt = '20131';
        !empty($id_smt)?$tahuns=$id_smt:$tahuns='20141';
        
        $filter_smt = "id_smt='".$tahuns."'";
        
        $temp_rec = $this->feeder->getrset($this->session->userdata('token'), 
                                                        $this->tabel, $filter_smt, 
                                                        $this->order, $this->limit, 
                                                        $offset
                                                     );
        /*                                             
        $temp_rec_count = $this->feeder->getrset($this->session->userdata('token'), 
                                                        $this->tabel, $filter_smt, 
                                                        $this->order, '', 
                                                        ''
                                                     );*/ 
        //var_dump($temp_rec_count);
                                                     
        //var_dump($temp_rec['result']);
        //$temp_count = count($temp_rec_count['result']);
         $temp_count = $this->feeder->count_all($this->session->userdata('token'), $this->tabel,$this->filter);
         $temp_jml = $temp_count['result'];
        //echo $temp_count;
        //
        
        //echo $start;
        
        $config['base_url'] = site_url('ws_akm/akm');
        $config['total_rows'] = $temp_jml;
        $config['per_page'] = $this->limit;
        $config['uri_segment'] = 3;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        
        $data['offset'] = $offset;
        $data['total'] = $temp_jml;
        $data['id_smt'] = $tahuns;
        $data['listakm'] = $temp_rec['result'];
        
        $offset==0? $start=$this->pagination->cur_page: $start=$offset+1;
        $data['start'] = $start;
        $data['end'] = $this->pagination->cur_page * $this->pagination->per_page;
        
        tampil('akm/__akm_view',$data);
    }
    
    public function createcsv($id_smt='')
    {
        if (!$id_smt=='') {
            
            $array = array();
            $temp_header = array('no_urut', 'nim_mhs', 'nm_mhs', 'semester', 'ips', 'ipk', 'sks_semester', 'sks_total', 'status_mhs');
            $array[] = $temp_header;
            
            $filter_smt = "id_smt='".$id_smt."'";
            
            $temp_rec = $this->feeder->getrset($this->session->userdata('token'), 
                                                        $this->tabel, $filter_smt, 
                                                        '', '', 
                                                        ''
                                                     );
            //var_dump($temp_rec['result']);
            $i=0;
            foreach ($temp_rec['result'] as $row) {
                ++$i;
                
                //get NPM Mahasiswa
                $filter_nim = "id_reg_pd='".$row['id_reg_pd']."'";
                $dump_nim = $this->feeder->getrecord($this->session->userdata('token'),'mahasiswa_pt',$filter_nim);
                var_dump($dump_nim['result']);
                //echo
                
                //get nama mahasiswa
                /*$filter_nma = "id_reg_pd='".$row['id_reg_pd']."'";
                $dump_nim = $this->feeder->getrecord($this->session->userdata('token'),'mahasiswa_pt',$filter_nim);*/
                
                //$filter_smt = "id_reg_pd='".$row['id_reg_pd']."'";
                $dump_nim = $this->feeder->getrecord($this->session->userdata('token'),'mahasiswa_pt',$filter_nim);
                $content = array($i,
                                $dump_nim['result']['nipd'],
                                $dump_nim['result']['nm_pd'],
                                $id_smt,
                                $row['ips'],
                                $row['ipk'],
                                $row['sks_smt'],
                                $row['sks_total'],
                                $row['id_stat_mhs']
                ); 
                $array[] = $content;
            }
            
            //var_dump($array);                                         
           // array_to_csv($array, $id_kls.'.csv');
        } else {
            echo "Cannot create CSV";
        }
    }
    
    public function extractcsv()
    {
        $config['upload_path'] = $this->config->item('upload_path');
        $config['allowed_types'] = $this->config->item('upload_tipe');
        $config['max_size'] = $this->config->item('upload_max_size');
        
        $this->load->library('upload',$config);
        if (!$this->upload->do_upload()) {
            echo "<div class=\"bs-callout bs-callout-danger\">".$this->upload->display_errors()."</div>";
        } else {
            $file_data = $this->upload->data();
            $file_path = $this->config->item('upload_path').$file_data['file_name'];
            $csv_array = $this->csvimport->get_array($file_path);
            
            if ($csv_array) {
                $array = array();
                $field_key = 'id_kls';
                
                $header = array('nilai_angka', 'nilai_huruf', 'nilai_indeks');
                
                foreach ($csv_array as $key) {
                    $temp_key = array('id_kls' => $key['id_kls'],
                                    'id_reg_pd' => $key['id_reg_pd']
                                );
                    $temp_data = array('nilai_angka' => $key['nilai_angka'],
                                    'nilai_huruf' => $key['nilai_huruf'],
                                    'nilai_indeks' => $key['nilai_indeks']
                    );
                    
                    $array[] = array('key'=>$temp_key,'data'=>$temp_data);
                }
                
                $temp_result = $this->feeder->updaterset($this->session->userdata('token'),$this->tbl_nilai,$array);
                
                $sukses_count = 0;
                $sukses_msg = '';
                $error_count = 0;
                $error_msg = array();
                $i=0;
                
                foreach ($temp_result['result'] as $key) {
                    ++$i;
                    if ($key['error_desc']==NULL) {
                        ++$sukses_count;
                    } else {
                        ++$error_count;
                        $error_msg[] = "<h4>Error di baris ".$i."<br /></h4>".$key['error_desc'];
                    }
                }
                
                if ((!$sukses_count==0) || (!$error_count==0)) {
                    echo "<div class=\"alert alert-warning\" role=\"alert\">
                                    Results (total ".$i." baris data):<br /><font color=\"#3c763d\">".$sukses_count." data berhasil diupdate</font><br />
                                    <font color=\"#ce4844\" >".$error_count." data error (tidak bisa diupdate) </font>";
                                    if (!$error_count==0) {
                                        echo "<a data-toggle=\"collapse\" href=\"#collapseExample\" aria-expanded=\"false\" aria-controls=\"collapseExample\">
                                          Detail error
                                        </a>";    
                                    }
                                    //echo "<br />Total: ".$i." baris data";
                                    echo "<div class=\"collapse\" id=\"collapseExample\">";
                                        foreach ($error_msg as $pesan) {
                                            echo "<div class=\"bs-callout bs-callout-danger\">
                                                    ".$pesan."
                                                  </div><br />";    
                                        } 
                                        
                                    echo "</div>
                                </div>";
                }
            }
        }
    }
    
    public function nilai($id_kls='')
    {
        
        $filter_kls = "p.id_kls = '".$id_kls."'";
        $temp_rec = $this->feeder->getrset($this->session->userdata('token'), 
                                                        $this->tbl_nilai, $filter_kls, 
                                                        $this->order, '', 
                                                        $this->offset
                                                     );
        //var_dump($temp_rec['result']);
        
        $temp_dic = $this->feeder->getdic($this->session->userdata('token'), $this->tbl_nilai);
        //var_dump($temp_dic['result']);
        
        $temp_count = count($temp_rec['result']);
        //echo $temp_count;
        $data['jml'] = $temp_count;
        $data['listdic'] = $temp_dic['result'];
        $data['nilai'] = $temp_rec['result'];
        $this->load->view('tpl/nilai/__view_nilai',$data);
    }
    
    public function kelas($offset=0)
    {
        $temp_rec = $this->feeder->getrset($this->session->userdata('token'), 
                                                        $this->tabel, $this->filter, 
                                                        'id_smt DESC', $this->limit, 
                                                        $offset
                                                     );
        $temp_count = $this->feeder->count_all($this->session->userdata('token'), $this->tabel);
        
        //list prodi
        $temp_sp = $this->session->userdata('id_sp');
        $filter_sms= "id_sp = '".$temp_sp."'";
        $temp_prodi = $this->feeder->getrset($this->session->userdata('token'), 
                                              'sms', $filter_sms, 
                                              '', '30',''
                                             );
        
        //semester
        $temp_semester = $this->feeder->getrset($this->session->userdata('token'), 
                                              'semester', '', 
                                              'nm_smt DESC', '10',''
                                             );                                            
        
        //pagination
        $config['base_url'] = site_url('ws_nilai/kelas');
        $config['total_rows'] = $temp_count['result'];
        $config['per_page'] = $this->limit;
        $config['uri_segment'] = 3;
        $this->pagination->initialize($config);
        //
        $data['pagination'] = $this->pagination->create_links();
        $data['offset'] = $offset;
        //$data['listsdic'] = $temp_dic;
        $data['listsrec'] = $temp_rec['result'];
        $data['total'] = $temp_count['result'];
        $data['url_add'] = 'index.php/ws_nilai/csv';
        $data['tabel'] = $this->tabel;
        $data['listprodi'] = $temp_prodi['result'];
        $data['semester'] = $temp_semester['result'];
        //var_dump($temp_semester['result']);

        tampil('nilai/__view_kelas',$data);
    }
 
    public function form_csv($id_kls = '')
    {
        //if (!$id_kls=='') {
            //echo $id_kls;
            $data['id_kls'] = $id_kls;
            //$data['url'] = ''
            $this->load->view('tpl/nilai/__form_csv',$data);
        //} else {
            //echo "Error. Please back and try again";
        //}
    }

    public function epsbed()
    {
        $dir_dbf = "C:/DIKTI";
        $temp_db = dbase_open($dir_dbf."/TRNLM.dbf", 0);
        
        $temp_isi = array();
        $x=0;
        if ($temp_db) {
            $jml_record = dbase_numrecords($temp_db);
            
            for ($i=1; $i <= $jml_record ; $i++) {
                $row = dbase_get_record_with_names($temp_db, $i);
                if (($row['THSMSTRNLM']=='20141') && ($row['KDKMKTRNLM']=='WAT301    ')) {
                    $temp_isi[] = $row['NIMHSTRNLM'];
                    $temp_isi[] = $row['KDKMKTRNLM'];
                    $temp_isi[] = $row['NLAKHTRNLM'];
                    $temp_isi[] = $row['BOBOTTRNLM'];
                    $temp_isi[] = $row['KELASTRNLM'];
                    ++$x;
                }
            }
            //var_dump($temp_isi);
            //echo $x;
        } else {
            echo "Tida bisa membuka tabel TRNLM. Silahkan cek kembali Settingan Anda";
        }
        //$data['isi_db'] = $temp_isi;
        var_dump($temp_isi);
        echo $x;
    }

}

/* End of file ws_akm.php */
/* Location: ./application/controllers/ws_akm.php */