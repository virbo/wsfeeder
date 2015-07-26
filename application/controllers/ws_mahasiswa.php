<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * WS Client Feeder Mahasiswa Module
 * 
 * @author      Yusuf Ayuba
 * @copyright   2015
 * @link         http://jago.link
 * @Github      https://github.com/virbo/wsfeeder
 * 
 */
 
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
            
            //inisial config upload
            $config['upload_path'] = $this->config->item('upload_path');
            $config['allowed_types'] = $this->config->item('upload_tipe');
            $config['max_size'] = $this->config->item('upload_max_size');
            
            $this->load->library('upload',$config);
        }
    }
    
    public function index()
    {
        //$this->tabview();
        $this->view();
    }
    
    public function extractcsv_nilai_pindahan()
    {
        if (!$this->upload->do_upload()) {
            echo "<div class=\"bs-callout bs-callout-danger\">".$this->upload->display_errors()."</div>";
        } else {
            $file_data = $this->upload->data();
            //var_dump($file_data);
            $file_path = $this->config->item('upload_path').$file_data['file_name'];
            //echo $file_path;
            $csv_array = $this->csvimport->get_array($file_path);
            //var_dump($csv_array);
            if ($csv_array) {
                $temp_data = array();
                foreach ($csv_array as $row) {
                    $id_reg_pd = $row['id_reg_pd'];
                    //echo $id_reg_pd;
                    $kode_mk_diakui = $row['kode_mk_diakui'];
                    //echo $kode_mk_diakui;
                    //$filter_mk = "kode_mk like '%".$kode_mk_diakui."%'";
                    $filter_mk = "kode_mk ='".$kode_mk_diakui."'";
                    $temp_mk = $this->feeder->getrecord($this->session->userdata('token'),'mata_kuliah',$filter_mk);
                    
                    $temp_data[] = array('id_reg_pd' => $row['id_reg_pd'],
                                             'id_mk' => $temp_mk['result']['id_mk'],
                                      'kode_mk_asal' => $row['kode_mk_asal'],
                                        'nm_mk_asal' => $row['nm_mk_asal'],
                                          'sks_asal' => $row['sks_asal'],
                                        'sks_diakui' => $row['sks_diakui'],
                                  'nilai_huruf_asal' => $row['nilai_huruf_asal'],
                                'nilai_huruf_diakui' => $row['nilai_huruf_diakui'],
                                'nilai_angka_diakui' => $row['nilai_angka_diakui'] 
                    );
                }
                //var_dump($temp_data);
                $temp_result = $this->feeder->insertrset($this->session->userdata['token'], $this->tbl_pindah, $temp_data);
                //var_dump($temp_result);
                
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
                            $error_msg[] = "<h4>Error di baris ".$i."<br /></h4>".$key['error_desc'];
                        }
                    }    
                } else {
                    echo "<div class=\"alert alert-danger\" role=\"alert\">
                              <h4>Error</h4>";
                              echo $temp_result['error_desc']."</div>";
                }
                
                
                if ((!$sukses_count==0) || (!$error_count==0)) {
                    echo "<div class=\"alert alert-warning\" role=\"alert\">
                                    Results (total ".$i." baris data):<br /><font color=\"#3c763d\">".$sukses_count." Nilai Pindahan berhasil ditambah</font><br />
                                    <font color=\"#ce4844\" >".$error_count." data error (tidak bisa ditambah) </font>";
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
            $temp_header = array('id_reg_pd',
                                 'nim',
                                 'nm_mhs',
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
                $temp_isi = array($temp_mhs['result']['id_reg_pd'],
                                  $temp_mhs['result']['nipd'],
                                  $temp_mhs['result']['nm_pd'],
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
    
    public function view($offset=0)
    {
        $temp_mhs = $this->input->post('nm_mhs');
        
        if ($temp_mhs=='') {
            $temp_rec = $this->feeder->getrset($this->session->userdata('token'), 
                                                            $this->tabel2, $this->filter, 
                                                            'nipd ASC', $this->limit, 
                                                            $offset
                                                         );
            
            $temp_count = $this->feeder->count_all($this->session->userdata('token'), $this->tabel2,$this->filter);
            $temp_jml = $temp_count['result']; 
            
            $data['temp_mhs'] = ''; 
        } else {
            $filter_nim = "nipd like '%".$temp_mhs."%'";
            $temp_rec = $this->feeder->getrset($this->session->userdata('token'), 
                                                        $this->tabel2, $filter_nim, 
                                                        'nipd ASC', $this->limit, 
                                                        $offset
                                                     );
            $temp_count = $this->feeder->count_all($this->session->userdata('token'), $this->tabel2,$filter_nim);
            $temp_jml = $temp_count['result'];
            if (!$temp_rec['result']) {
                $filter_mhs = "nm_pd like '%".$temp_mhs."%'";
                $temp_rec = $this->feeder->getrset($this->session->userdata('token'), 
                                                            $this->tabel2, $filter_mhs, 
                                                            'nipd ASC', $this->limit, 
                                                            $offset
                                                         );
                //var_dump($temp_rec_mhs);
                //$filter_id_pd = "id_pd='".$."'";
                $temp_count = $this->feeder->count_all($this->session->userdata('token'), $this->tabel2,$filter_mhs);
                $temp_jml = $temp_count['result'];
            }
            //var_dump($temp_rec['result']);
            //$temp_jml = count($temp_rec['result']);
            
            $data['temp_mhs'] = $temp_mhs;
        }
        
          
        
        //pagination
        $config['base_url'] = site_url('ws_mahasiswa/view');
        $config['total_rows'] = $temp_jml;
        $config['per_page'] = $this->limit;
        $config['uri_segment'] = 3;
        $this->pagination->initialize($config);
        //
        $data['pagination'] = $this->pagination->create_links();
        $data['offset'] = $offset;
        
        $data['temp_error'] = $temp_rec['error_desc'];
        $data['listsrec'] = $temp_rec['result'];
        $data['total'] = $temp_jml;

        $offset==0? $start=$this->pagination->cur_page: $start=$offset+1;
        $data['start'] = $start;
        $data['end'] = $this->pagination->cur_page * $this->pagination->per_page;

        tampil('/mahasiswa/__view_mahasiswa',$data);                                          
    }
    
    public function dump_csv()
    {
        if (!$this->upload->do_upload()) {
            echo "<div class=\"bs-callout bs-callout-danger\">".$this->upload->display_errors()."</div>";
        } else {
            
            $file_data = $this->upload->data();
            //var_dump($file_data);
            
            $file_path = $this->config->item('upload_path').$file_data['file_name'];
            
            //get_array($filepath=FALSE, $column_headers=FALSE, $detect_line_endings=FALSE, $initial_line=FALSE, $delimiter=FALSE)
            $separasi = $this->input->post('separasi');
            
            $csv_array = $this->csvimport->get_array($file_path,'','','',$separasi);
            //var_dump($csv_array);
            
            if ($csv_array) {
                $temp_data = array();
                foreach ($csv_array as $value) {
                    $temp_data[] = $value;
                    //'regpd_id_sms' => $this->input->post('prodi', TRUE);
                    //$temp_data[] = array('regpd_id_sp' => $this->session->userdata('id_sp'));
                }
                //var_dump($temp_data);
                
                $temp_result = $this->feeder->insertrset($this->session->userdata['token'], $this->tabel, $temp_data);
                //var_dump($temp_result);
                
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
                            $error_msg[] = "<h4>Error di baris ".$i."<br /></h4>".$key['error_desc'];
                        }
                    }
                } else {
                    echo "<div class=\"alert alert-danger\" role=\"alert\">
                              <h4>Error</h4>";
                              echo $temp_result['error_desc']."</div>";
                }
                
                
                if ((!$sukses_count==0) || (!$error_count==0)) {
                    echo "<div class=\"alert alert-warning\" role=\"alert\">
                                    Results (total ".$i." baris data):<br /><font color=\"#3c763d\">".$sukses_count." data Mahasiswa baru berhasil ditambah</font><br />
                                    <font color=\"#ce4844\" >".$error_count." data error (tidak bisa ditambahkan) </font>";
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
        
        //echo $this->input->post('prodi')."<br />".$this->input->post('separasi');
        $id_sp = $this->session->userdata('id_sp');
        $id_sms = $this->input->post('prodi');
        $separasi = $this->input->post('separasi');
        
        $sample = array('ID PD / Hapus kolom ini',
                        'Nama Lengkap Mahasiswa sesuai KTP/Tanda Pengenal Lainnya - Wajib diisi',
                        'Jenis Kelamin (L: Laki-laki, P: Perempuan, *: Belum ada informasi) - Wajib diisi',
                        'NISN (Nomor Induk Siswa Nasional) / Hapus kolom ini jika tidak ada',
                        'NIK (Nomor Induk Kependudukan) / Hapus kolom ini jika tidak ada',
                        'Tempat Lahir / Hapus kolom ini jika tidak ada',
                        'Tanggal Lahir (Format: yyyy-mm-dd / contoh: 2000-12-30) - Wajib diisi',
                        'ID Agama (1: Islam, 2: Kristen, 3: Katholik, 4: Hindu, 5: Budha, 6: Konghucu, 98: Tidak diisi, 99: Lainnya) - Wajib diisi',
                        'ID KK / Isikan angka 0 - Wajib diisi',
                        //'ID SP / Kosongkan',
                        $id_sp,
                        'Nama Jalan alamat tinggal / Hapus kolom ini jika tidak ada',
                        'RT alamat tinggal / Hapus kolom ini jika tidak ada',
                        'RW alamat tinggal / Hapus kolom ini jika tidak ada',
                        'Nama Dusun / Hapus kolom ini jika tidak ada',
                        'Nama Desa atau Keluarahan - Wajib diisi',
                        'ID WIlayah/Propinsi (Lihat tabel wilayah - Wajib diisi)',
                        'Kode Pos Alamat Tinggal / Hapus kolom ini jika tidak ada',
                        'ID Jenis Tinggal / Isikan angka 0 atau Hapus kolom ini jika tidak ada',
                        'ID Alat Transport / Isikan angka 0 Hapus kolom ini jika tidak ada',
                        'Nomor Telepon Rumah / Hapus kolom ini jika tidak ada',
                        'Nomor Telepon Seluler / Hapus kolom ini jika tidak ada',
                        'Alamat email / Hapus kolom ini jika tidak ada',
                        'Kartu Perlindungan Sosial (0: Bukan penerima KPS, 1: Penerima KPS) - Wajib diisi',
                        'Nomor KPS / Isikan jika penerima KPS, Hapus kolom ini jika bukan penerima KPS',
                        'Status Mahasiswa (A: Aktif, C: Cuti, D: Drop-out/Putus Studi, L: Lulus, P: , K: Keluar, N: Non Aktif, G: Sedang Double Degree, X: Unknown) - Wajib diisi',
                        'Nama Ayah / Hapus kolom ini jika tidak ada',
                        'Tanggal Lahir Ayah (yyy-mm-dd) / Hapus kolom ini jika tidak ada',
                        'ID Jenjang Pendidikan Ayah / Hapus kolom ini jika tidak ada',
                        'ID Pekerjaan Ayah / Hapus kolom ini jika tidak ada',
                        'ID Penghasilan Ayah / Hapus kolom ini jika tidak ada',
                        'ID Kebutuhan Khusus Ayah - Wajib diisi',
                        'Nama Ibu Kandung - Wajib diisi',
                        'Tanggal Lahir Ibu (yyy-mm-dd) / Hapus kolom ini jika tidak ada',
                        'ID Jenjang Pendidikan Ibu / Hapus kolom ini jika tidak ada',
                        'ID Penghasilan Ibu / Hapus kolom ini jika tidak ada',
                        'ID Pekerjaan Ibu / Hapus kolom ini jika tidak ada',
                        'ID Kebutuhan Khusus Ibu - Wajib diisi',
                        'Nama Wali / Hapus kolom ini jika tidak ada',
                        'Tanggal Lahir Wali (yyyy-mm-dd) / Hapus kolom ini jika tidak ada',
                        'ID Pendidikan Wali / Hapus kolom ini jika tidak ada',
                        'ID Pekerjaan Wali / Hapus kolom ini jika tidak ada',
                        'ID Penghasilan Wali / Hapus kolom ini jika tidak ada',
                        'Kewarganegaraan (ID: Indonesia) - Wajib diisi',
                        'ID Registrasi Mahasiswa - Hapus kolom ini',
                        //'ID Program Studi / Kosongkan',
                        $id_sms,
                        'ID PD - Hapus kolom ini',
                        //'ID Perguruan Tinggi / Kosongkan',
                        $id_sp,
                        'ID Jenis Daftar (2: Pindahan, 3: Naik kelas, 4: Akselerasi, 5: Mengulang, 6: Lanjutan semester, 9: Putus Sekolah, 0: Lainnya, 1: Peserta didik baru, 8: Pindahan Alih Bentuk) - Wajib diisi',
                        'NIPD (Nomor Induk Mahasiswa) - Wajib diisi',
                        'Tanggal Masuk Mahasiswa (yyyy-mm-dd / Contoh: 2000-12-30) - Wajib diisi',
                        'ID Jenis Keluar (1: Lulus, 2: Mutasi, 3: Dikeluarkan, 4: Mengundurkan diri, 5: Putus Sekolah, 6: Wafat, 7: Hilang, 8: Alih Fungsi, 9: Pensiun, Z: Lainnya)/ Hapus kolom ini jika mahasiswa aktif',
                        'Tanggal Keluar Mahasiswa (yyyy-mm-dd / Contoh: 2000-12-30) / Hapus kolom ini jika mahasiswa masih aktif',
                        'Keterangan Mahasiswa / Hapus kolom ini jika tidak ada',
                        'Surat Keterangan Hasil Ujian Nasional (SKHUN) / Hapus kolom ini jika tidak ada',
                        'Mahasiswa Pernah PAUD (0: Tidak pernah PAUD, 1: Pernah PAUD) / Hapus kolom ini jika tidak ada',
                        'Mahasiswa Pernah TK (0: Tidak pernah TK, 1: Pernah TK) / Hapus kolom ini jika tidak ada',
                        'Awal Semester Mahasiswa (ex. 20121) - Wajib diisi',
                        'SKS Diakui (Jika Status Mahasiswa Pindahan) / Hapus kolom ini jika status masuk mahasiswa baru',
                        'Jalur Keluar Mahasiswa / Hapus kolom ini jika mahasiswa masih aktif, dan isikan 0 jika mahasiswa sudah Lulus',
                        'Judul Skripsi Mahasiswa / Hapus kolom ini jika mahasiswa masih aktif',
                        'Tanggal/Bulan Awal Bimbingan Skripsi (yyyy-mm-dd) / Hapus kolom ini jika mahasiswa belum skripsi',
                        'Tanggal/Bulan Akhir Bimbingan Skripsi (yyyy-mm-dd) / Hapus kolom ini jika mahasiswa belum skripsi',
                        'Nomor SK Yudisium (yyyy-mm-dd) / Hapus kolom ini jika mahasiswa masih aktif',
                        'Tanggal SK Yudisium / Hapus kolom ini jika mahasiswa masih aktif',
                        'Indeks Prestasi Kumulatif / Hapus kolom ini jika mahasiswa masih aktif',
                        'Nomor Seri Ijazah / Hapus kolom ini jika mahasiswa masih aktif',
                        'Nomor Sertifikat Profesional / Hapus kolom ini jika tidak ada',
                        'Mahasiswa Asing Pindahan / Hapus kolom ini jika tidak ada',
                        'Nama PT Asal / Diisi jika status mahasiswa pindahan, Hapus kolom ini jika status mahasiswa baru',
                        'Nama PRODI Asal / Diisi jika status mahasiswa pindahan, Hapus kolom ini jika status mahasiswa baru'
                        
                    );
        $array[] = $sample;
        $time = time();
        write_file('temps/'.$time.'_mahasiswa.csv', array_to_csv($array,'',$separasi));
        //echo "File berhasil digenerate. <a href=\"".base_url()."temps/".$time."_mahasiswa.csv\">Download</a>";
        echo "<div class=\"bs-callout bs-callout-success\">
                 File berhasil digenerate. <a href=\"".base_url()."temps/".$time."_mahasiswa.csv\">Download</a>
              </div>";
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
    
    public function form_createcsv_mhs()
    {
        $filter_sms= "id_sp = '".$this->session->userdata('id_sp')."'";
        $temp_prodi = $this->feeder->getrset($this->session->userdata('token'), 
                                                        'sms', $filter_sms, 
                                                        $this->order, '', 
                                                        ''
                                                     );
        $data['prodi'] = $temp_prodi['result'];                                             
        //tampil('mahasiswa/__mahasiswa_csv_form',$data);
        $this->load->view('tpl/mahasiswa/__form_createcsv_mhs',$data);
    }
    
    public function form_csv_nilai_pindahan($id_reg_pd)
    {
        if (!empty($id_reg_pd)) {
            $data['id_reg_pd'] = $id_reg_pd;
            $this->load->view('tpl/mahasiswa/__form_csv_nilai_pindahan',$data);
        } else {
            redirect('ws_mahasiswa');
        }
    }
    
}

/* End of file ws_mahasiswa.php */
/* Location: ./application/controllers/ws_mahasiswa.php */