<div class="page-header" style="margin-top: 10px;" >
    <div class="row">
        <div class="col-md-4">
            <h4>Nilai Perkuliahan Mahasiswa</h4>
        </div>
        <div class="col-md-8">
            
        </div>    
    </div>
    
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <a href="javascript:void();" class="modalButton btn btn-success" data-toggle="modal" data-src="<?php echo base_url(); ?>index.php/ws_nilai/form_csv/" data-target="#modalku" title="Upload file CSV">
                    <span class="glyphicon glyphicon-hdd" aria-hidden="true"></span> Upload Nilai (CSV)
                </a>
                <!--a href="javascript:void();" class="modalButton btn btn-info" data-toggle="modal" data-src="<?php echo base_url(); ?>index.php/ws_nilai/form_kelas2/" data-target="#modalku" title="Tambah Kelas">
                    <span class="glyphicon glyphicon-retweet" aria-hidden="true"></span> Tambah Kelas
                </a-->
            </div>
            <div class="panel-body">
                <h4>Daftar Kelas Perkuliahan Setiap Semester Per Kelas.</h4>
                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span> Klik icon <a><span class="glyphicon glyphicon-search" aria-hidden="true"></span></a> di setiap Mata Kuliah untuk melihat Daftar Asli Nilai Mahasiswa.<br />
                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span> Klik icon <a><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span></a> di setiap Mata Kuliah untuk Mendownload Daftar Nilai Mahasiswa.<br />
                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span> Klik tombol <a class="btn btn-success"><span class="glyphicon glyphicon-hdd" aria-hidden="true"></span> Upload Nilai (CSV)</a> untuk Mengupload Daftar Nilai Mahasiswa yang sudah diisi.
            </div>
            <table class="table table-hover table-striped table-bordered">
                <thead>
                    <tr>
                        <th width="10px">#</th>
                        <th width="180px">Program Studi</th>
                        <th width="180px">Semester</th>
                        <th width="100px">Kode MK</th>
                        <th>Mata Kuliah</th>
                        <th width="5px">Kelas</th>
                        <th width="5px">SKS</th>
                        <th width="5px">MHS KRS</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td>
                            <select class="form-control" id="prodi" name="prodi">
                                <option value="" selected>Semua Prodi</option>
                                <?php
                                    foreach ($listprodi as $key => $value) {
                                        $filter_jenjang = "id_jenj_didik = ".$value['id_jenj_didik'];
                                        $dump_jenjang = $this->feeder->getrecord($this->session->userdata('token'),'jenjang_pendidikan',$filter_jenjang);
                                        //$temp_jenjang = $dump_jenjang['nm_jenj_didik'];
                                        $dumy_jenjang = $dump_jenjang['result'];
                                        //var_dump($dumy_jenjang);
                                        //echo $dumy_jenjang['nm_jenj_didik'] .' - '. $value['nm_lemb'];
                                        echo "<option value=\"".$value['id_sms']."\">".$dumy_jenjang['nm_jenj_didik']." ".$value['nm_lemb']."</option>";
                                    }
                                ?>
                            </select>
                        </td>
                        <td>
                            <select class="form-control" id="prodi" name="semester">
                                 <option value="" selected >Semua Semester</option>
                                 <?php
                                    foreach ($semester as $key => $value) {
                                        //echo $value['nm_smt'].'<br />';
                                        echo "<option value=\"".$value['id_smt']."\">".$value['nm_smt']."</option>";
                                        
                                    }
                                 ?>
                            </select>
                        </td>
                        <td colspan="2" style="width: auto;">
                            <form class="form-horizontal">
                            <div class="input-group">
                              <!--input type="text" class="form-control" name="nm_mk" placeholder="Cari Mata Kuliah, Kode Mata Kuliah...">
                              <span class="input-group-btn">
                                <button class="btn btn-info" type="submit"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                              </span-->
                              <select class="form-control" id="id_mk" name="id_mk">
                                  <option value="" selected >Semua Mata Kuliah</option>
                                  <?php
                                    foreach ($mk as $row) {
                                        //echo $value['nm_smt'].'<br />';
                                        echo "<option value=\"".$row['id_mk']."\">".$row['kode_mk']." - ".$row['nm_mk']." (".$row['sks_mk']." SKS)</option>";
                                        
                                    }
                                 ?>
                              </select>
                            </div>
                            </form>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <?php
                        $i=0+$offset;
                        //echo $offset;
                        foreach ($listsrec as $key => $value) {
                            echo "<tr>
                                        <td>".++$i."</td>";
                                        //ambil nama prodi
                                        $filter_sms = "id_sms = '".$value['id_sms']."'";
                                        $dump_sms = $this->feeder->getrecord($this->session->userdata('token'),'sms',$filter_sms);
                                        //ambil jenjang prodi
                                        $filter_jenjang = "id_jenj_didik = ".$dump_sms['result']['id_jenj_didik'];
                                        $dump_jenjang = $this->feeder->getrecord($this->session->userdata('token'),'jenjang_pendidikan',$filter_jenjang);
                                        
                                        echo "<td>".$dump_jenjang['result']['nm_jenj_didik'].' '.$dump_sms['result']['nm_lemb']."</td>";
                                        
                                        $filter_smt = "id_smt = '".$value['id_smt']."'";
                                        $dump_smt = $this->feeder->getrecord($this->session->userdata('token'),'semester',$filter_smt);
                                        echo "<td>".$dump_smt['result']['nm_smt']."</td>";
                                        
                                        //ambil kode mk dan nama mk
                                        $filter_mk = "id_mk = '".$value['id_mk']."'";
                                        $dump_mk = $this->feeder->getrecord($this->session->userdata('token'),'mata_kuliah',$filter_mk);
                                        
                                        echo "<td>".$dump_mk['result']['kode_mk']."</td>
                                              <td>".$dump_mk['result']['nm_mk']."</td>
                                              <td>".$value['nm_kls']."</td>
                                              <td>".$value['sks_mk']."</td>";
                                        
                                        $filter_kls = "p.id_kls = '".$value['id_kls']."'";
                                        $dump_kls = $this->feeder->getrset($this->session->userdata('token'), 
                                                        'nilai', $filter_kls, 
                                                        '', '', 
                                                        ''
                                                     );
                                        $count_krs = count($dump_kls['result']);
                                        
                                        echo "<td>".$count_krs."</td>
                                              <td>
                                                  <a href=\"javascript:void();\" class=\"modalButton\" data-toggle=\"modal\" data-src=\"".base_url()."index.php/ws_nilai/nilai/".$value['id_kls']."\" data-target=\"#modalku\">
                                                      <span class=\"glyphicon glyphicon-search\" aria-hidden=\"true\"></span>
                                                  </a>&nbsp;
                                                  <a href=\"".base_url()."index.php/ws_nilai/createcsv/".$value['id_kls']."\" title=\"Download file CSV\"><span class=\"glyphicon glyphicon-download-alt\" aria-hidden=\"true\"></span></a>&nbsp;
                                                  <!--a href=\"#expand".$i."\" data-src=\"".base_url()."index.php/ws_nilai/epsbed\" class=\"epsbed".$i."\" data-toggle=\"collapse\" aria-expanded=\"false\" aria-controls=\"expand\">
                                                      <span class=\"glyphicon glyphicon-retweet\" aria-hidden=\"true\">
                                                  </a-->
                                              </td>
                                </tr>
                                <tr class=\"collapse\" id=\"expand".$i."\">
                                    <td colspan=\"8\">
                                        <div class=\"panel panel-default\">
                                            <div class=\"panel-heading\">Semester</div>
                                            <div id=\"isi_epsbed\">Loading...</div>
                                            <!--div class=\"panel-body\">
                                                Data dibawah ini berasal dari tabel TRNLM yang ada di aplikasi Layar Biru (EPSBED).<br />
                                                <font color=\"#FF0000\">Apabila tidak ada data yang muncul, maka pastikan settingan Lokasi Aplikasi Layar Biru (PATH) telah benar.</font><br />
                                                <i>Untuk merubah settingan lokasi Aplikasi Layar dapat dilakukan melalui halaman <strong>Setting</strong></i>.
                                            </div>
                                            <table class=\"table table-hover table-striped table-bordered\">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                    </tr>
                                                </thead>
                                            </table-->
                                        </div>
                                    </td>
                                </tr>";
                        }
                    ?>
                </tbody>
            </table>
            <div class="panel-footer">
                <div class="row">
                    <div class="col-md-6">
                        <?php
                        
                            //$offset==0? $start=$this->pagination->cur_page: $start=$offset+1;
                            //$end = $this->pagination->cur_page * $this->pagination->per_page;
                            
                            //echo "Showing ".$start.' - '.$end.' of '.$total.' result <br />'.$this->pagination->cur_page.'<br />'.$this->pagination->per_page;
                            echo "Showing ".$start.' - '.$end.' of '.$total.' results';
                        ?>
                    </div>
                    <div class="col-md-6" style="margin-top: -15px;" >
                        <?php echo $pagination;?>
                    </div>
                </div>
            </div>
            
        
        </div>    
    </div>
        
</div>
<script>
        $(document).ready(function(){
          $('a.epsbed1').click(function(){
            var src = $(this).attr('data-src');
            $('#isi_epsbed').load(src);
            //return false;
          });
        });
</script>