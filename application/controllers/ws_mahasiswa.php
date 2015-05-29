<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ws_mahasiswa extends CI_Controller {
        
    //private $data;
    private $limit;
    private $filter;
    private $order;
    private $offset;
    private $tabel;
    private $tabel2;
    private $tbl_pindah;
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
            $this->tabel2 = 'mahasiswa_pt';
            $this->tbl_pindah = 'nilai_transfer';
            
            $this->load->model('m_feeder','feeder');
            $this->load->helper('directory');
            $this->load->helper('csv');
            //$this->load->library('upload');
        }
    }
    
    public function index()
    {
        //$this->tabview();
        $this->view_mhs();
    }
    
    public function createcsv_nilai_pindahan($id_reg_pd='')
    {
        if (!empty($id_reg_pd)) {
            $filter_nilai = "p.id_reg_pd='".$id_reg_pd."'";
            $temp_nilai = $this->feeder->getrset($this->session->userdata('token'), 
                                                        $this->tbl_pindah, $filter_nilai, 
                                                        $this->order, '', 
                                                        ''
                                                     );
            
            //var_dump($temp_nilai['result']);
            $temp_jml = count($temp_nilai['result']);
            
           
            $filter_mhs = "id_reg_pd='".$id_reg_pd."'";
            $temp_mhs = $this->feeder->getrecord($this->session->userdata('token'),'mahasiswa_pt',$filter_mhs);
            
            //var_dump($temp_mhs['result']);
            
            $array = array();
            $temp_header = array('nim',
                                 'nm_mhs',
                                 'id_reg_pd',
                                 'kode_mk_asal',
                                 'nm_mk_asal',
                                 'sks_asal',
                                 'nilai_huruf_asal',
                                 'kode_mk_diakui',
                                 'nm_mk_diakui',
                                 'nilai_huruf_diakui',
                                 'nilai_angka_diakui',
                                 'sks_diakui'
                            );
            $array[] = $temp_header;                
            if ($temp_jml==0) {
                //echo "Data 0";
                $temp_isi = array($temp_mhs['result']['nipd'],
                                  $temp_mhs['result']['nm_pd'],
                                  $temp_mhs['result']['id_reg_pd'],
                                  '',
                                  '',
                                  '',
                                  '',
                                  '',
                                  '',
                                  '',
                                  '',
                                  ''  
                            );
                $array[] = $temp_isi;
                array_to_csv($array, $temp_mhs['result']['nipd'].'-'.$temp_mhs['result']['nm_pd'].'.csv');
            }
            
        } else {
            echo "Cannot create CSV";
        }
    }
    
    public function view_nilai_pindah($id_reg_pd='')
    {
        if (!empty($id_reg_pd)) {
            $filter_nilai = "p.id_reg_pd='".$id_reg_pd."'";
            $temp_nilai = $this->feeder->getrset($this->session->userdata('token'), 
                                                        $this->tbl_pindah, $filter_nilai, 
                                                        $this->order, '', 
                                                        ''
                                                     );
            
            //var_dump($temp_nilai['result']);
            $temp_jml = count($temp_nilai['result']);
            
            $data['nilai_pindah'] = $temp_nilai['result'];
            $data['jml'] = $temp_jml;
            
            $this->load->view('tpl/mahasiswa/__view_nilai_pindah',$data);
        } else {
            redirect('ws_mahasiswa');
        }
    }
    
    public function view_mhs($offset=0)
    {
        $temp_rec = $this->feeder->getrset($this->session->userdata('token'), 
                                                        $this->tabel2, $this->filter, 
                                                        'nipd ASC', $this->limit, 
                                                        $offset
                                                     );
        //var_dump($temp_rec['result']);
        $temp_count = $this->feeder->count_all($this->session->userdata('token'), $this->tabel2);
        //var_dump($temp_count['result']);  
        
        //pagination
        $config['base_url'] = site_url('ws_mahasiswa/view_mhs');
        $config['total_rows'] = $temp_count['result'];
        $config['per_page'] = $this->limit;
        $config['uri_segment'] = 3;
        $this->pagination->initialize($config);
        //
        $data['pagination'] = $this->pagination->create_links();
        $data['offset'] = $offset;
        $data['listsrec'] = $temp_rec['result'];
        $data['total'] = $temp_count['result'];

        $offset==0? $start=$this->pagination->cur_page: $start=$offset+1;
        $data['start'] = $start;
        $data['end'] = $this->pagination->cur_page * $this->pagination->per_page;

        tampil('/mahasiswa/__view_mahasiswa',$data);                                          
    }
    
    public function test_csv()
    {
        $config['upload_path'] = $this->config->item('upload_path');
        $config['allowed_types'] = $this->config->item('upload_tipe');
        $config['max_size'] = $this->config->item('upload_max_size');
        
        $this->load->library('upload',$config);
        
        if (!$this->upload->do_upload()) {
            echo "<div class=\"bs-callout bs-callout-danger\">".$this->upload->display_errors()."</div>";
        } else {
            //echo "Ada isinya";
            $file_data = $this->upload->data();
            //var_dump($file_data);
            
            $file_path = $this->config->item('upload_path').$file_data['file_name'];
            
            $csv_array = $this->csvimport->get_array($file_path);
            //var_dump($csv_array);
            //$offset==0? $start=$this->pagination->cur_page: $start=$offset+1;
            if ($csv_array) {
                $temp = array();
                foreach ($csv_array as $value) {
                    !empty($value['nisn'])?'nisn'.$value['nisn']: 'nisn';
                    
                    $temp_data = array('nm_pd' => $value['nm_pd'], 
                                    'jk' => $value['jk'],
                                    'nisn' => !empty($value['nisn'])?$value['nisn']:'',
                                    'nik' => !empty($value['nik'])?$value['nik']:'',
                                    'tmpt_lahir' => !empty($value['tmpt_lahir'])?$value['tmpt_lahir']:'',
                                    'tgl_lahir'=> date("Y-m-d", strtotime($value['tgl_lahir'])),
                                    'id_agama' => !empty($value['id_agama'])?$value['id_agama']:'98',
                                    'id_kk' => !empty($value['id_kk'])?$value['id_kk']:'0',
                                    //'id_sp' => $value['id_sp'],
                                    'id_sp' => $this->session->userdata('id_sp'),
                                    //'jln' => !empty($value['jln'])?$value['jln']:'',
                                    //'rt' => !empty($value['rt'])?$value['rt']:'',
                                    //'rw' => !empty($value['rw'])?$value['rw']:'',
                                    //'nm_dsn' => !empty($value['nm_dsn'])?$value['nm_dsn']:'',
                                    'ds_kel' => !empty($value['ds_kel'])?$value['ds_kel']:'',
                                    'id_wil' => !empty($value['id_wil'])?$value['id_wil']:'000000',
                                    //'kode_pos' => !empty($value['kode_pos'])?$value['kode_pos']:'',
                                    //'id_jns_tinggal' => !empty($value['id_jns_tinggal'])?$value['id_jns_tinggal']:'0',
                                    //'id_alat_transport' => !empty($value['id_alat_transport'])?$value['id_alat_transport']:'0',
                                    //'telepon_rumah' => !empty($value['telepon_rumah'])?$value['telepon_rumah']:'',
                                    //'telepon_seluler' => !empty($value['telepon_seluler'])?$value['telepon_seluler']:'',
                                    //'email' => !empty($value['email'])?$value['email']:'',
                                    'a_terima_kps' => !empty($value['a_terima_kps'])?$value['a_terima_kps']:'0',
                                    //'no_kps' => !empty($value['no_kps'])?$value['no_kps']:'',
                                    'stat_pd' => !empty($value['stat_pd'])?$value['stat_pd']:'',
                                    //'nm_ayah' => !empty($value['nm_ayah'])?$value['nm_ayah']:'',
                                    //'tgl_lahir_ayah'=> !empty($value['tgl_lahir_ayah'])?date("Y-m-d", strtotime($value['tgl_lahir_ayah'])):'9999-12-30',
                                    //'id_jenjang_pendidikan_ayah' => !empty($value['id_jenjang_pendidikan_ayah'])?$value['id_jenjang_pendidikan_ayah']:'',
                                    //'id_pekerjaan_ayah' => !empty($value['id_pekerjaan_ayah'])?$value['id_pekerjaan_ayah']:'',
                                    //'id_penghasilan_ayah' => !empty($value['id_penghasilan_ayah'])?$value['id_penghasilan_ayah']:'',
                                    'id_kebutuhan_khusus_ayah' => !empty($value['id_kebutuhan_khusus_ayah'])?$value['id_kebutuhan_khusus_ayah']:'0',
                                    'nm_ibu_kandung' => !empty($value['nm_ibu_kandung'])?$value['nm_ibu_kandung']:'',
                                    //'tgl_lahir_ibu'=> !empty($value['tgl_lahir_ibu'])?date("Y-m-d", strtotime($value['tgl_lahir_ibu'])):'9999-12-30',
                                    //'id_jenjang_pendidikan_ibu' => !empty($value['id_jenjang_pendidikan_ibu'])?$value['id_jenjang_pendidikan_ibu']:'',
                                    //'id_penghasilan_ibu' => !empty($value['id_penghasilan_ibu'])?$value['id_penghasilan_ibu']:'',
                                    //'id_pekerjaan_ibu' => !empty($value['id_pekerjaan_ibu'])?$value['id_pekerjaan_ibu']:'',
                                    'id_kebutuhan_khusus_ibu' => !empty($value['id_kebutuhan_khusus_ibu'])?$value['id_kebutuhan_khusus_ibu']:'0',
                                    //'nm_wali' => !empty($value['nm_wali'])?$value['nm_wali']:'',
                                    //'tgl_lahir_wali'=> !empty($value['tgl_lahir_wali'])?date("Y-m-d", strtotime($value['tgl_lahir_wali'])):'9999-12-30',
                                    //'id_jenjang_pendidikan_wali' => !empty($value['id_jenjang_pendidikan_wali'])?$value['id_jenjang_pendidikan_wali']:'',
                                    //'id_pekerjaan_wali' => !empty($value['id_pekerjaan_wali'])?$value['id_pekerjaan_wali']:'',
                                    //'id_penghasilan_wali' => !empty($value['id_penghasilan_wali'])?$value['id_penghasilan_wali']:'',
                                    'kewarganegaraan' => !empty($value['kewarganegaraan'])?$value['kewarganegaraan']:'',
                                    
                                    //tabel reg_pd
                                    //'regpd_id_sms' => $value['regpd_id_sms'],
                                    'regpd_id_sms' => $this->input->post('prodi', TRUE),
                                    //'regpd_id_pd' => $row['id_pd'],
                                    //'regpd_id_sp' => $value['regpd_id_sp'],
                                    'regpd_id_sp' => $this->session->userdata('id_sp'),
                                    'regpd_id_jns_daftar' => !empty($value['regpd_id_jns_daftar'])?$value['regpd_id_jns_daftar']:'',
                                    'regpd_nipd' => !empty($value['regpd_nipd'])?$value['regpd_nipd']:'',
                                    'regpd_tgl_masuk_sp'=> !empty($value['regpd_tgl_masuk_sp'])?date("Y-m-d", strtotime($value['regpd_tgl_masuk_sp'])):'',
                                    'regpd_id_jns_keluar' => !empty($value['regpd_id_jns_keluar'])?$value['regpd_id_jns_keluar']:'',
                                    'regpd_tgl_keluar'=> !empty($value['regpd_tgl_keluar'])?date("Y-m-d", strtotime($value['regpd_tgl_keluar'])):'',
                                    //'regpd_ket' => !empty($value['regpd_ket'])?$value['regpd_ket']:'',
                                   // 'regpd_skhun' => !empty($value['regpd_skhun'])?$value['regpd_skhun']:'',
                                   // 'regpd_a_pernah_paud' => !empty($value['regpd_a_pernah_paud'])?$value['regpd_a_pernah_paud']:'0',
                                    //'regpd_a_pernah_tk' => !empty($value['regpd_a_pernah_tk'])?$value['regpd_a_pernah_tk']:'0',
                                    'regpd_mulai_smt' => !empty($value['regpd_mulai_smt'])?$value['regpd_mulai_smt']:'',
                                    'regpd_sks_diakui' => !empty($value['regpd_sks_diakui'])?$value['regpd_sks_diakui']:'',
                                    //'regpd_jalur_skripsi' => !empty($value['regpd_jalur_skripsi'])?$value['regpd_jalur_skripsi']:'0',
                                    'regpd_judul_skripsi' => !empty($value['regpd_judul_skripsi'])?$value['regpd_judul_skripsi']:'',
                                    //'regpd_bln_awal_bimbingan' => !empty($value['regpd_bln_awal_bimbingan'])?date("Y-m-d", strtotime($value['regpd_bln_awal_bimbingan'])):'9999-12-30', 
                                    //'regpd_bln_akhir_bimbingan' => !empty($value['regpd_bln_akhir_bimbingan'])?date("Y-m-d", strtotime($value['regpd_bln_akhir_bimbingan'])):'9999-12-30',
                                    'regpd_sk_yudisium' => !empty($value['regpd_sk_yudisium'])?$value['regpd_sk_yudisium']:'',
                                    'regpd_tgl_sk_yudisium'=> !empty($value['regpd_tgl_sk_yudisium'])?date("Y-m-d", strtotime($value['regpd_tgl_sk_yudisium'])):'9999-12-30',
                                    'regpd_ipk' => !empty($value['regpd_ipk'])?$value['regpd_ipk']:'',
                                    'regpd_no_seri_ijazah' => !empty($value['regpd_no_seri_ijazah'])?$value['regpd_no_seri_ijazah']:'',
                                    //'regpd_sert_prof' => !empty($value['regpd_sert_prof'])?$value['regpd_sert_prof']:'',
                                    //'regpd_a_pindah_mhs_asing' => !empty($value['regpd_a_pindah_mhs_asing'])?$value['regpd_a_pindah_mhs_asing']:'0',
                                    //'regpd_nm_pt_asal' => !empty($value['regpd_nm_pt_asal'])?$value['regpd_nm_pt_asal']:'',
                                    //'regpd_nm_prodi_asal' => !empty($value['regpd_nm_prodi_asal'])?$value['regpd_nm_prodi_asal']:'',
                             );
                     $hasil = $this->feeder->insertrecord($this->session->userdata['token'], $this->tabel, $temp_data);        
                }
                //$proxy->InsertRecord($token, $table, json_encode($record));
                //$hasil = $this->feeder->insertrset($this->session->userdata['token'], $this->tabel, $temp_data);
                //var_dump($temp_data);
                var_dump($hasil);
            } else {
                echo "<div class=\"bs-callout bs-callout-danger\">Error: Tidak dapat mengekstrak file CSV. Silahkan dicoba kembali</div>";
            }
        }
    }
    
    public function extractCsv()
    {
        $filter_sms= "id_sp = '".$this->session->userdata('id_sp')."'";
        $temp_prodi = $this->feeder->getrset($this->session->userdata('token'), 
                                                        'sms', $filter_sms, 
                                                        $this->order, $this->limit, 
                                                        $this->offset
                                                     );
        $data['prodi'] = $temp_prodi['result'];
        
        $config['upload_path'] = $this->config->item('upload_path');
        $config['allowed_types'] = $this->config->item('upload_tipe');
        $config['max_size'] = $this->config->item('upload_max_size');
        
        $this->load->library('upload',$config);
        
        if (!$this->upload->do_upload()) {
            //echo $this->upload->display_errors().'<br />';
            //echo base_url().'upload/';
            $data['stat_error'] = $this->upload->display_errors();
            //$this->session->set_flashdata('stat_error',$this->upload->display_errors());
            //redirect('ws_mahasiswa/csv',$data);
            tampil('mahasiswa/__mahasiswa_csv_form',$data);
        } else {
            $file_data = $this->upload->data();
            //$file_path = 'upload/'.$file_data['file_name'];
            $file_path = $this->config->item('upload_path').$file_data['file_name'];
            $csv_array = $this->csvimport->get_array($file_path);
            //date_format($date, 'Y-m-d');
            if ($csv_array) {
                $temp_result = array();
                $temp_data = array();
                $temp_header = array();
                
                $temp_dic = $this->feeder->getdic($this->session->userdata('token'), $this->tabel);
                $dumy_dic = $temp_dic['result'];
                //var_dump($dumy_dic);
                foreach ($dumy_dic as $header) {
                    $temp_header[] = $header['column_name'];
                }
               // var_dump($temp_header);
                foreach ($csv_array as $key) {
                    $temp_data[] =  $key['nm_pd'];
                    /*
                    $temp_data = array('nm_pd' => $value['nm_pd'], 
                                    'jk' => $value['jk'],
                                    'nisn' => $value['nisn'],
                                    'nik' => $value['nik'],
                                    'tmpt_lahir' => $value['tmpt_lahir'],
                                    'tgl_lahir'=> date("Y-m-d", strtotime($value['tgl_lahir'])),
                                    'id_agama' => $value['id_agama'],
                                    'id_kk' => $value['id_kk'],
                                    //'id_sp' => $value['id_sp'],
                                    'id_sp' => $this->session->userdata('id_sp'),
                                    'jln' => $value['jln'],
                                    'rt' => $value['rt'],
                                    'rw' => $value['rw'],
                                    'nm_dsn' => $value['nm_dsn'],
                                    'ds_kel' => $value['ds_kel'],
                                    'id_wil' => $value['id_wil'],
                                    'kode_pos' => $value['kode_pos'],
                                   // 'id_jns_tinggal' => $value['id_jns_tinggal'],
                                   // 'id_alat_transport' => $value['id_alat_transport'],
                                    'telepon_rumah' => $value['telepon_rumah'],
                                    'telepon_seluler' => $value['telepon_seluler'],
                                    'email' => $value['email'],
                                    'a_terima_kps' => $value['a_terima_kps'],
                                    'no_kps' => $value['no_kps'],
                                    'stat_pd' => $value['stat_pd'],
                                    'nm_ayah' => $value['nm_ayah'],
                                    'tgl_lahir_ayah'=> date("Y-m-d", strtotime($value['tgl_lahir_ayah'])),
                                    'id_jenjang_pendidikan_ayah' => $value['id_jenjang_pendidikan_ayah'],
                                    'id_pekerjaan_ayah' => $value['id_pekerjaan_ayah'],
                                    'id_penghasilan_ayah' => $value['id_penghasilan_ayah'],
                                    'id_kebutuhan_khusus_ayah' => $value['id_kebutuhan_khusus_ayah'],
                                    'nm_ibu_kandung' => $value['nm_ibu_kandung'],
                                    'tgl_lahir_ibu'=> date("Y-m-d", strtotime($value['tgl_lahir_ibu'])),
                                    'id_jenjang_pendidikan_ibu' => $value['id_jenjang_pendidikan_ibu'],
                                    'id_penghasilan_ibu' => $value['id_penghasilan_ibu'],
                                    'id_pekerjaan_ibu' => $value['id_pekerjaan_ibu'],
                                    'id_kebutuhan_khusus_ibu' => $value['id_kebutuhan_khusus_ibu'],
                                    'nm_wali' => $value['nm_wali'],
                                    'tgl_lahir_wali'=> date("Y-m-d", strtotime($value['tgl_lahir_wali'])),
                                    'id_jenjang_pendidikan_wali' => $value['id_jenjang_pendidikan_wali'],
                                    'id_pekerjaan_wali' => $value['id_pekerjaan_wali'],
                                    'id_penghasilan_wali' => $value['id_penghasilan_wali'],
                                    'kewarganegaraan' => $value['kewarganegaraan'],
                                    
                                    //tabel reg_pd
                                    //'regpd_id_sms' => $value['regpd_id_sms'],
                                    'regpd_id_sms' => $this->input->post('prodi', TRUE),
                                    //'regpd_id_pd' => $row['id_pd'],
                                    //'regpd_id_sp' => $value['regpd_id_sp'],
                                    'regpd_id_sp' => $this->session->userdata('id_sp'),
                                    'regpd_id_jns_daftar' => $value['regpd_id_jenis_daftar'],
                                    'regpd_nipd' => $value['regpd_nipd'],
                                    'regpd_tgl_masuk_sp'=> date("Y-m-d", strtotime($value['regpd_tgl_masuk_sp'])),
                                    'regpd_id_jns_keluar' => $value['regpd_id_jns_keluar'],
                                    'regpd_tgl_keluar'=> date("Y-m-d", strtotime($value['regpd_tgl_keluar'])),
                                    'regpd_ket' => $value['regpd_ket'],
                                    'regpd_skhun' => $value['regpd_skhun'],
                                    'regpd_a_pernah_paud' => $value['regpd_a_pernah_paud'],
                                    'regpd_a_pernah_tk' => $value['regpd_a_pernah_tk'],
                                    'regpd_mulai_smt' => $value['regpd_mulai_smt'],
                                    'regpd_sks_diakui' => $value['regpd_sks_diakui'],
                                    'regpd_jalur_skripsi' => $value['regpd_jalur_skripsi'],
                                    'regpd_judul_skripsi' => $value['regpd_judul_skripsi'],
                                    'regpd_bln_awal_bimbingan' => date("Y-m-d", strtotime($value['regpd_bln_awal_bimbingan'])),
                                    'regpd_bln_akhir_bimbingan' => date("Y-m-d", strtotime($value['regpd_bln_akhir_bimbingan'])),
                                    'regpd_sk_yudisium' => $value['regpd_sk_yudisium'],
                                    'regpd_tgl_sk_yudisium'=> date("Y-m-d", strtotime($value['regpd_tgl_sk_yudisium'])),
                                    'regpd_ipk' => $value['regpd_ipk'],
                                    'regpd_no_seri_ijazah' => $value['regpd_no_seri_ijazah'],
                                    'regpd_sert_prof' => $value['regpd_sert_prof'],
                                    //'regpd_a_pindah_mhs_asing' => $value['regpd_a_pindah_mhs_asing'],
                                    'regpd_nm_pt_asal' => $value['regpd_nm_pt_asal'],
                                    'regpd_nm_prodi_asal' => $value['regpd_nm_prodi_asal'],
                             );*/
                    //$temp_result = $this->feeder->insertrecord($this->session->userdata('token'),$this->tabel,$temp_data);
                }
                //var_dump($temp_data);
               // var_dump($temp_result);
                /*if ($temp_result['result']['error_desc']==NULL) {
                        $data['stat_sukses'] = 'Data berhasil ditambahkan';
                        //$this->session->set_flashdata('sukses','Data berhasil ditambahkan');
                        tampil('mahasiswa/__mahasiswa_csv_form',$data);
                        //tampil('mahasiswa/__mahasiswa_csv_form');
                    } else {
                        $data['stat_error'] = $temp_result['result']['error_desc'];
                        //$this->session->set_flashdata('error',$temp_result['result']['error_desc']);
                        tampil('mahasiswa/__mahasiswa_csv_form',$data);
                        //tampil('mahasiswa/__mahasiswa_csv_form');
                        //redirect('ws_mahasiswa/csv');
                       // break;
                    }*/
            }
        }
    }
    
    public function createcsv()
    {
        $temp_dic = $this->feeder->getdic($this->session->userdata('token'), $this->tabel);
        $dumy_dic = $temp_dic['result'];
        //var_dump($dumy_dic);
        
        $array = array();
        $header_mhs = array();
        foreach ($dumy_dic as $key) {
            $header_mhs[] = $key['column_name'];
        }
        $array[] = $header_mhs;
        //var_dump($array);
        
        $sample = array('ID PD / Kosongkan',
                        'Nama Lengkap Mahasiswa sesuai KTP/Tanda Pengenal Lainnya',
                        'Jenis Kelamin (L: Laki-laki, P: Perempuan, *: Belum ada informasi)',
                        'NISN (Nomor Induk Siswa Nasional) / Kosongkan jika tidak ada',
                        'NIK (Nomor Induk Kependudukan) / Kosongkan jika tidak ada',
                        'Tempat Lahir / Wajib diisi',
                        'Tanggal Lahir (yyyy-mm-dd)/ Wajib diisi',
                        'ID Agama (1: Islam, 2: Kristen, 3: Katholik, 4: Hindu, 5: Budha, 6: Konghucu, 98: Tidak diisi, 99: Lainnya)',
                        'ID KK / Isikan angka 0',
                        'ID SP / Kosongkan',
                        'Nama Jalan alamat tinggal / Kosongkan jika tidak ada',
                        'RT alamat tinggal / Kosongkan jika tidak ada',
                        'RW alamat tinggal / Kosongkan jika tidak ada',
                        'Nama Dusun / kosongkan jika tidak ada',
                        'Nama Desa atau Keluarahan/ kosongkan jika tidak ada',
                        'ID WIlayah/Propinsi (Lihat tabel wilayah)',
                        'Kode Pos Alamat Tinggal / Kosongkan jika tidak ada',
                        'ID Jenis Tinggal / Isikan angka 0',
                        'ID Alat Transport / Isikan angka 0',
                        'Nomor Telepon Rumah / Kosongkan jika tidak ada',
                        'Nomor Telepon Seluler / Kosongkan jika tidak ada',
                        'Alamat email / Kosongkan jika tidak ada',
                        'Kartu Perlindungan Sosial (0: Bukan penerima KPS, 1: Penerima KPS)',
                        'Nomor KPS / Isikan jika penerima KPS, kosongkan jika bukan penerima KPS',
                        'Status Mahasiswa (A: Aktif, C: Cuti, D: Drop-out/Putus Studi, L: Lulus, P: , K: Keluar, N: Non Aktif, G: Sedang Double Degree, X: Unknown)',
                        'Nama Ayah / Kosongkan jika tidak ada',
                        'Tanggal Lahir Ayah (yyy-mm-dd) / Kosongkan jika tidak ada',
                        'ID Jenjang Pendidikan Ayah / Kosongkan jika tidak ada',
                        'ID Pekerjaan Ayah / Kosongkan jika tidak ada',
                        'ID Penghasilan Ayah / Kosongkan jika tidak ada',
                        'ID Kebutuhan Khusus Ayah / Kosongkan jika tidak ada',
                        'Nama Ibu Kandung / Kosongkan jika tidak ada',
                        'Tanggal Lahir Ibu (yyy-mm-dd) / Kosongkan jika tidak ada',
                        'ID Jenjang Pendidikan Ibu / Kosongkan jika tidak ada',
                        'ID Penghasilan Ibu / Kosongkan jika tidak ada',
                        'ID Pekerjaan Ibu / Kosongkan jika tidak ada',
                        'ID Kebutuhan Khusus Ayah / Kosongkan jika tidak ada',
                        'Nama Wali / Kosongkan jika tidak ada',
                        'Tanggal Lahir Wali (yyyy-mm-dd) / Kosongkan jika tidak ada',
                        'ID Pendidikan Wali / Kosongkan jika tidak ada',
                        'ID Pekerjaan Wali / Kosongkan jika tidak ada',
                        'ID Penghasilan Wali / Kosongkan jika tidak ada',
                        'Kewarganegaraan (ID: Indonesia)',
                        'ID Registrasi Mahasiswa / Kosongkan',
                        'ID Program Studi / Kosongkan',
                        'ID PD / Kosongkan',
                        'ID Perguruan Tinggi / Kosongkan',
                        'ID Jenis Daftar (2: Pindahan, 3: Naik kelas, 4: Akselerasi, 5: Mengulang, 6: Lanjutan semester, 9: Putus Sekolah, 0: Lainnya, 1: Peserta didik baru, 8: Pindahan Alih Bentuk)',
                        'NIPD (Nomor Induk Mahasiswa)',
                        'Tanggal Masuk Mahasiswa (yyyy-mm-dd)',
                        'ID Jenis Keluar (1: Lulus, 2: Mutasi, 3: Dikeluarkan, 4: Mengundurkan diri, 5: Putus Sekolah, 6: Wafat, 7: Hilang, 8: Alih Fungsi, 9: Pensiun, Z: Lainnya)/ Kosongkan jika mahasiswa aktif',
                        'Tanggal Keluar Mahasiswa (yyyy-mm-dd) / Kosongkan jika mahasiswa masih aktif',
                        'Keterangan Mahasiswa / Kosongkan jika tidak ada',
                        'Surat Keterangan Hasil Ujian Nasional (SKHUN) / Kosongkan jika tidak ada',
                        'Mahasiswa Pernah PAUD (0: Tidak pernah PAUD, 1: Pernah PAUD)',
                        'Mahasiswa Pernah TK (0: Tidak pernah TK, 1: Pernah TK)',
                        'Awal Semester Mahasiswa (ex. 20121)',
                        'SKS Diakui (Jika Status Mahasiswa Pindahan) / Kosongkan jika status masuk mahasiswa baru',
                        'Jalur Keluar Mahasiswa / Kosogkan jika mahasiswa masih aktif, dan isikan 0 jika mahasiswa sudah Lulus',
                        'Judul Skripsi Mahasiswa / Kosongkan jika mahasiswa masih aktif',
                        'Tanggal/Bulan Awal Bimbingan Skripsi (yyyy-mm-dd) / Kosongkan jika mahasiswa belum skripsi',
                        'Tanggal/Bulan Akhir Bimbingan Skripsi (yyyy-mm-dd) / Kosongkan jika mahasiswa belum skripsi',
                        'Nomor SK Yudisium (yyyy-mm-dd) / Kosongkan jika mahasiswa masih aktif',
                        'Tanggal SK Yudisium / Kosongkan jika mahasiswa masih aktif',
                        'Indeks Prestasi Kumulatif / Kosongkan jika mahasiswa masih aktif',
                        'Nomor Seri Ijazah / Kosongkan jika mahasiswa masih aktif',
                        'Nomor Sertifikat Profesional / Kosongkan',
                        'Mahasiswa Asing Pindahan / Kosongkan',
                        'Nama PT Asal / Diisi jika status mahasiswa pindahan, kosongkan jika status mahasiswa baru',
                        'Nama PRODI Asal / Diisi jika status mahasiswa pindahan, kosongkan jika status mahasiswa baru'
                        
                    );
        $array[] = $sample;
        //var_dump($array);
        array_to_csv($array, 'mahasiswa.csv');
    }
    
    public function form_csv()
    {
        $filter_sms= "id_sp = '".$this->session->userdata('id_sp')."'";
        $temp_prodi = $this->feeder->getrset($this->session->userdata('token'), 
                                                        'sms', $filter_sms, 
                                                        $this->order, '', 
                                                        ''
                                                     );
        $data['prodi'] = $temp_prodi['result'];                                             
        //tampil('mahasiswa/__mahasiswa_csv_form',$data);
        $this->load->view('tpl/mahasiswa/__form_csv',$data);
    }
    
}

/* End of file ws_mahasiswa.php */
/* Location: ./application/controllers/ws_mahasiswa.php */