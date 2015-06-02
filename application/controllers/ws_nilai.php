<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * WS Client Feeder Nilai Mahasiswa Module
 * 
 * @author      Yusuf Ayuba
 * @copyright   2015
 * @Url         http://jago.link
 * @Github      https://github.com/virbo/wsfeeder
 * 
 */
 
class Ws_nilai extends CI_Controller {
        
    //private $data;
    private $limit;
    private $filter;
    private $order;
    private $offset;
    private $tabel;
    private $tbl_nilai;
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
            //$this->tabel = 'nilai';
            $this->tabel = 'kelas_kuliah';
            $this->tbl_nilai = 'nilai';
            
            $this->load->model('m_feeder','feeder');
            $this->load->helper('directory');
            $this->load->helper('csv');
            //$this->load->library('upload');
        }
    }
    
    public function index()
    {
        //$this->tabview();
        $this->kelas();
    }
    
    public function createcsv($id_kls='')
    {
        if (!$id_kls=='') {
            
            //ambil data nilai
            $filter_kls = "p.id_kls = '".$id_kls."'";
            $temp_nilai = $this->feeder->getrset($this->session->userdata('token'), 
                                                        $this->tbl_nilai, $filter_kls, 
                                                        $this->order, '', 
                                                        $this->offset
                                                     );
            $dumy_nilai = $temp_nilai['result'];
            //var_dump($dumy_nilai);
            
            //ambil struktur tabel nilai
            $temp_dic = $this->feeder->getdic($this->session->userdata('token'), $this->tbl_nilai);
            $dumy_dic = $temp_dic['result'];
            
            $array = array();
            //$header_nilai = array();
            
            
            //$temp_header = array('id_kls', 'id_reg_pd', 'asal_data', 'nim', 'nm_mhs', 'nilai_angka', 'nilai_huruf', 'nilai_indeks');
            //create header
            /* automatic create header from database
            foreach ($dumy_dic as $key => $value) {
                $header_nilai[] = $value['column_name'];
            }*/
            $header_nilai = array('no_urut', 'id_kls', 'id_reg_pd','nim', 'nm_mhs', 'nilai_angka', 'nilai_huruf', 'nilai_indeks');
            $array[] = $header_nilai;

            //create content
            $i=0;
            foreach ($dumy_nilai as $row)
            {
                //$content_nilai = array();
                /* Automatic create content from database
                 * foreach ($header_nilai as $header) {
                    $content_nilai[] = $row[$header];
                }*/
                ++$i;
                $filter_mhs_pt = "id_reg_pd = '".$row['id_reg_pd']."'";
                $temp_mhs_pt = $this->feeder->getrecord($this->session->userdata('token'),'mahasiswa_pt',$filter_mhs_pt);
                
                $filter_mhs = "id_pd = '".$temp_mhs_pt['result']['id_pd']."'";
                $temp_mhs = $this->feeder->getrecord($this->session->userdata('token'),'mahasiswa',$filter_mhs);
                
                $content_nilai = array($i,
                                       $row['id_kls'],
                                       $row['id_reg_pd'],
                                       $temp_mhs_pt['result']['nipd'],
                                       $temp_mhs['result']['nm_pd'],
                                       $row['nilai_angka'],
                                       $row['nilai_huruf'],
                                       $row['nilai_indeks']
                                );
                $array[] = $content_nilai;
            }
            //$array[] = $header_nilai;
            array_to_csv($array, $id_kls.'.csv');
            //var_dump($array);
            
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
                //$field_key = 'id_kls';
                
                //$header = array('nilai_angka', 'nilai_huruf', 'nilai_indeks');
                
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
                //$sukses_msg = '';
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
            } else {
                echo "<div class=\"bs-callout bs-callout-danger\">Error: Tidak dapat mengekstrak file CSV. Silahkan dicoba kembali</div>";
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
        //$temp_reg = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
        //var_dump($temp_reg);
        $temp_rec = $this->feeder->getrset($this->session->userdata('token'), 
                                                        $this->tabel, $this->filter, 
                                                        'id_smt DESC', $this->limit, 
                                                        $offset
                                                     );
        $temp_count = $this->feeder->count_all($this->session->userdata('token'), $this->tabel);
        
        //list prodi
        /*$temp_sp = $this->session->userdata('id_sp');
        $filter_sms= "id_sp = '".$temp_sp."'";
        $temp_prodi = $this->feeder->getrset($this->session->userdata('token'), 
                                              'sms', $filter_sms, 
                                              '', '30',''
                                             );*/
        
        //semester
        /*$temp_semester = $this->feeder->getrset($this->session->userdata('token'), 
                                              'semester', '', 
                                              'nm_smt DESC', '10',''
                                             );*/                                            
        
        
        //mata kuliah
        /*$temp_mk = $this->feeder->getrset($this->session->userdata('token'), 
                                              'mata_kuliah', '', 
                                              'id_mk ASC', '',''
                                             );*/
        //pagination
        //var_dump($temp_rec['result']);
        
        $config['base_url'] = site_url('ws_nilai/kelas');
        $config['total_rows'] = $temp_count['result'];
        $config['per_page'] = $this->limit;
        $config['uri_segment'] = 3;
        $this->pagination->initialize($config);
        //
        $data['pagination'] = $this->pagination->create_links();
        //$data['offset'] = $offset;
        $data['offset'] = $offset;
        //$data['listsdic'] = $temp_dic;
        $data['listsrec'] = $temp_rec['result'];
        $data['total'] = $temp_count['result'];
        $data['url_add'] = 'index.php/ws_nilai/csv';
        $data['tabel'] = $this->tabel;
        /*$data['listprodi'] = $temp_prodi['result'];
        $data['semester'] = $temp_semester['result'];
        $data['mk'] = $temp_mk['result'];*/
        //var_dump($temp_semester['result']);
        
        $offset==0? $start=$this->pagination->cur_page: $start=$offset+1;
        $data['start'] = $start;
        $data['end'] = $this->pagination->cur_page * $this->pagination->per_page;

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
    
    public function form_kelas()
    {
        //get prodi
        $filter_sms = "id_sp = '".$this->session->userdata('id_sp')."'";
        $temp_sms = $this->feeder->getrset($this->session->userdata('token'), 
                                                        'sms', $filter_sms, 
                                                        '', '', 
                                                        ''
                                                     );
        //var_dump($temp_sms['result]);
        
        
        //get semester aktif
        if (!empty($this->input->get('q'))) {
            $temps_cari = $this->input->get('q');
        } else {
            $temps_cari = '20';
        }
            
        //$filter_smt = "a_periode_aktif=1";
        $filter_smt = "id_smt like '%".$temps_cari."%' AND a_periode_aktif=1";
        $temp_smt = $this->feeder->getrset($this->session->userdata('token'), 
                                                        'semester', $filter_smt, 
                                                        '', '', 
                                                        ''
                                                     );
        //var_dump($temp_smt['result']);
        
        
        $data['temp_sms'] = $temp_sms['result'];
        $data['temp_smt'] = $temp_smt['result'];
        
        //$array = array();
        foreach ($temp_smt['result'] as $row) {
            $array = array('id' => $row['id_smt'], 'text' => $row['nm_smt']); 
        }
        
        //var_dump($array);
        
        //echo json_encode($arrary);
        echo json_encode($temp_smt['result']);
        //$this->load->view('tpl/nilai/__form_kelas',$data);
                                                     
    }

    public function form_kelas2()
    {
        $this->load->view('tpl/nilai/__form_kelas');
                                                     
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

/* End of file ws_nilai.php */
/* Location: ./application/controllers/ws_nilai.php */