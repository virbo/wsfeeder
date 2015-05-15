<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ws_mahasiswa extends CI_Controller {
        
    //private $data;
    private $limit;
    private $filter;
    private $order;
    private $offset;
    private $tabel;
    private $tabel2;
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
    
    public function tabview()
    {
        tampil('mahasiswa/__mahasiswa_tab');
    }
    
    public function view_mhs($offset=0)
    {
        $temp_dic = $this->feeder->getdic($this->session->userdata('token'), $this->tabel);
        $temp_rec = $this->feeder->getrset($this->session->userdata('token'), 
                                                        $this->tabel, $this->filter, 
                                                        $this->order, $this->limit, 
                                                        $offset
                                                     );
        $temp_count = $this->feeder->count_all($this->session->userdata('token'), $this->tabel);
        

        //pagination
        $config['base_url'] = site_url('ws_mahasiswa/view_mhs');
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
        $data['url_add'] = 'index.php/ws_mahasiswa/csv';
        $data['tabel'] = 'mahasiswa';

        tampil('/mahasiswa/__view_mahasiswa',$data);
    }
    
    public function form($dir)
    {
        //tampil('mahasiswa/__mahasiswa_form',$this->data);
        //echo $dir;
        $temp_dir = directory_map('C:/DIKTI');
        var_dump($temp_dir);
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
                var_dump($temp_header);
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
                var_dump($temp_data);
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
                        'Tanggal Lahir (yyy-mm-dd)/ Wajib diisi',
                        'ID Agama (1: Islam, 2: Kristen, 3: Katholik, 4: Hindu, 5: Budha, 6: Konghucu, 98: Tidak diisi, 99: Lainnya)',
                        'ID KK / Isikan angka 0',
                        'ID SP / Hapus Kolom ini',
                        'Nama Jalan alamat tinggal / Kosongkan jika tidak ada',
                        'RT alamat tinggal / Kosongkan jika tidak ada',
                        'RW alamat tinggal / Kosongkan jika tidak ada',
                        'Nama Dusun / kosongkan jika tidak ada',
                        'Nama Desa atau Keluarahan/ kosongkan jika tidak ada',
                        'ID WIlayah/Propinsi',
                        'Kode Pos Alamat Tinggal / Kosongkan jika tidak ada',
                        'ID Jenis Tinggal / Isikan angka 0',
                        'ID Alat Transport / Isikan angka 0',
                        'Nomor Telepon Rumah / Kosongkan jika tidak adak',
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
                        'Tanggal Masuk Mahasiswa',
                        'ID Jenis Keluar (1: Lulus, 2: Mutasi, 3: Dikeluarkan, 4: Mengundurkan diri, 5: Putus Sekolah, 6: Wafat, 7: Hilang, 8: Alih Fungsi, 9: Pensiun, Z: Lainnya)/ Kosongkan jika mahasiswa aktif',
                        'Tanggal Keluar Mahasiswa (yyyy-mm-dd) / Kosongkan jika mahasiswa masih aktif',
                        'Keterangan Mahasiswa / Kosongkan jika tidak ada',
                        'Surat Keterangan Hasil Ujian Nasional (SKHUN) / Kosongkan jika tidak ada',
                        'Mahasiswa Pernah PAUD (0: Tidak pernah PAUD, 1: Pernah PAUD)',
                        'Mahasiswa Pernah TK (0: Tidak pernah TK, 1: Pernah TK)',
                        'Awal Semester Mahasiswa',
                        'SKS Diakui (Jika Status Mahasiswa Pindahan) / Kosongkan jika status masuk mahasiswa baru',
                        'Jalur Keluar Mahasiswa / Kosogkan jika mahasiswa masih aktif',
                        'Judul Skripsi Mahasiswa / Kosongkan jika mahasiswa masih aktif',
                        'Tanggal/Bulan Awal Bimbingan Skripsi (yyyy-mm-dd) / Kosongkan jika mahasiswa belum skripsi',
                        'Tanggal/Bulan Akhir Bimbingan Skripsi (yyyy-mm-dd) / Kosongkan jika mahasiswa belum skripsi',
                        'Nomor SK Yudisium / Kosongkan jika mahasiswa masih aktif',
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
    
    public function csv()
    {
        $filter_sms= "id_sp = '".$this->session->userdata('id_sp')."'";
        $temp_prodi = $this->feeder->getrset($this->session->userdata('token'), 
                                                        'sms', $filter_sms, 
                                                        $this->order, $this->limit, 
                                                        $this->offset
                                                     );
        $data['prodi'] = $temp_prodi['result'];                                             
        tampil('mahasiswa/__mahasiswa_csv_form',$data);
    }
    /*
    public function getSCV()
    {
        $row = 1;
        if (($handle = fopen(base_url()."FILE/mhs.csv", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $num = count($data);
                echo "<p> $num fields in line $row: <br /></p>\n";
                //$row++;
                //$record['id_pd'] = ;
                $record['nm_pd'] = $data[0];
                $record['jk'] = $data[1];
                $record['nisn'] = $data[2];
                $record['nik'] = $data[3];
                $record['tmpt_lahir'] = $data[4];
                $record['tgl_lahir']= $data[5];
                $record['id_agama'] = $data[6];
                $record['id_kk'] = $data[7];
                $record['id_sp'] = $data[8];
                $record['jln'] = $data[9];
                $record['rt']= $data[10]; 
                $record['rw'] = $data[11];
                $record['nm_dsn'] = $data[12];
                $record['ds_kel'] = $data[13];
                $record['id_wil'] = $data[14];
                $record['kode_pos'] = $data[15];
               // $record['id_jns_tinggal'] = $data[16];
               // $record['id_alat_transport'] = $data[17];
                $record['telepon_rumah'] = $data[18];
                $record['telepon_seluler'] = $data[19];
                $record['email'] = $data[20];
                $record['a_terima_kps'] = $data[21];
                $record['no_kps'] = $data[22];
                $record['stat_pd'] = $data[23];
                $record['nm_ayah'] = $data[24];
                $record['tgl_lahir_ayah'] = $data[25];
                $record['id_jenjang_pendidikan_ayah'] = $data[26];
                $record['id_pekerjaan_ayah'] = $data[27];
                $record['id_penghasilan_ayah'] = $data[28];
                $record['id_kebutuhan_khusus_ayah'] = $data[29];
                $record['nm_ibu_kandung'] = $data[30];
                $record['tgl_lahir_ibu'] = $data[31];
                $record['id_jenjang_pendidikan_ibu'] = $data[32];
                $record['id_penghasilan_ibu'] = $data[33];
                $record['id_pekerjaan_ibu'] = $data[34];
                $record['id_kebutuhan_khusus_ibu'] = $data[35];
                $record['nm_wali'] = $data[36];
                $record['tgl_lahir_wali'] = $data[37];
                $record['id_jenjang_pendidikan_wali'] = $data[38];
                $record['id_pekerjaan_wali'] = $data[39];
                $record['id_penghasilan_wali'] = $data[40];
                $record['kewarganegaraan'] = $data[41];
                
                
                $record['regpd_id_sms'] = $data[42];
                $record['regpd_id_pd'] = $row['id_pd'];
                $record['regpd_id_sp'] = $data[43];
                $record['regpd_id_jns_daftar'] = $data[44];
                $record['regpd_nipd'] = $data[45];
                $record['regpd_tgl_masuk_sp'] = $data[46];
                $record['regpd_id_jns_keluar'] = $data[47];
                $record['regpd_tgl_keluar'] = $data[48];
                $record['regpd_ket'] = $data[49];
                $record['regpd_skhun'] = $data[50];
                $record['regpd_a_pernah_paud'] = $data[51];
                $record['regpd_a_pernah_tk'] = $data[52];
                $record['regpd_mulai_smt'] = $data[53];
                $record['regpd_sks_diakui'] = $data[54];
                $record['regpd_jalur_skripsi'] = $data[55];
                $record['regpd_judul_skripsi'] = $data[56];
                $record['regpd_bln_awal_bimbingan'] = $data[57];
                $record['regpd_bln_akhir_bimbingan'] = $data[58];
                $record['regpd_sk_yudisium'] = $data[59];
                $record['regpd_tgl_sk_yudisium'] = $data[60];
                $record['regpd_ipk'] = $data[61];
                $record['regpd_no_seri_ijazah'] = $data[62];
                $record['regpd_sert_prof'] = $data[63];
                //$record['regpd_a_pindah_mhs_asing'] = $data[64];
                $record['regpd_nm_pt_asal'] = $data[65];
                $record['regpd_nm_prodi_asal'] = $data[66];
                
                for ($c=0; $c < $num; $c++) {
                    echo $data[$c] . "<br />\n";
                    //$proxy->InsertRecordset($token, $table, json_encode($data[$c]));
                    //$hasil = $this->feeder->insertrset($this->session->userdata('token'),$this->tabel,$data[$c]);
                    //var_dump($hasil);
                }
                //var_dump($data[0]);
                //$hasil = $this->feeder->insertrecord($this->session->userdata('token'),$this->tabel,$record);
                //if ($hasil['result']['error_desc']==NULL) {
                //    echo "Insert berhasil";
                //} else {
                //    echo $hasil['result']['error_desc'];
                //}
                //var_dump($hasil);
                ++$row;
                
            }
            fclose($handle);
        }
    }*/
}

/* End of file ws_mahasiswa.php */
/* Location: ./application/controllers/ws_mahasiswa.php */