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
                <div class="row">
                    <div class="col-md-8">
                        <a href="javascript:void();" class="modalButton btn btn-success" data-toggle="modal" data-src="<?php echo base_url(); ?>index.php/ws_nilai/form_csv/" data-target="#modalku" title="Upload file CSV">
                            <span class="glyphicon glyphicon-hdd" aria-hidden="true"></span> Upload Nilai (CSV)
                        </a>
                    </div>
                    <div class="col-md-4" align="right">
                        <?php
                            $attributes = array('class' => 'form-inline');
                            echo form_open('ws_nilai/kelas',$attributes);
                        ?>
                            <div class="input-group">
                                <input type="text" class="form-control" name="mk" placeholder="Search..." value="<?php echo $temp_mk;?>">
                                <span class="input-group-btn">
                                    <button class="btn btn-warning" type="submit"><i class="fa fa-search search-btn"></i></button>
                                </span>
                            </div>
                        </form>
                    </div>
                </div>
                
            </div>
            <div class="panel-body">
                
                <!-- HELPER-->
                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingThree">
                            <h4 class="panel-title">
                                <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    Petunjuk Pengoperasian <span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span>
                                </a>
                            </h4>
                        </div>
                        <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                            <div class="panel-body">
                                <h4>Daftar Kelas Perkuliahan Setiap Semester Per Kelas.</h4>
                                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span> Klik icon <a><span class="glyphicon glyphicon-search" aria-hidden="true"></span></a> di setiap Mata Kuliah untuk melihat Daftar Asli Nilai Mahasiswa.<br />
                                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span> Klik icon <a><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span></a> di setiap Mata Kuliah untuk Mendownload Daftar Nilai Mahasiswa.<br />
                                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span> Klik tombol <a class="btn btn-success"><span class="glyphicon glyphicon-hdd" aria-hidden="true"></span> Upload Nilai (CSV)</a> untuk Mengupload Daftar Nilai Mahasiswa yang sudah diisi.<br />
                                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span> Jika kolom MHS KRS bernilai <strong>0</strong>, maka lakukan pengisian KRS mahasiswa di Aplikasi Feeder.
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END HELPER-->
                
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
                    <?php
                        $temp_error!=''?$pesan=$temp_error:$pesan='Data kosong';
                        if (!$listsrec) {
                            echo "<tr>
                                      <td colspan=\"10\">".$pesan."</td>
                                  </tr>";
                        } else {
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
                                                  <!--td>".$dump_mk['result']['nm_mk']."</td-->
                                                  <td>".$value['fk__id_mk']."</td>
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
                                                  <td>";
                                                        if ($count_krs!=0) {
                                                          echo "<a href=\"javascript:void();\" class=\"modalButton\" data-toggle=\"modal\" data-src=\"".base_url()."index.php/ws_nilai/nilai/".$value['id_kls']."\" data-target=\"#modalku\">
                                                                    <span class=\"glyphicon glyphicon-search\" aria-hidden=\"true\"></span>
                                                                </a>&nbsp;
                                                                <a href=\"javascript:void();\" class=\"modalButton\" data-toggle=\"modal\" data-src=\"".base_url()."index.php/ws_nilai/form_create_csv/".$value['id_kls']."\" data-target=\"#modalku\" title=\"Download file CSV\">
                                                                    <span class=\"glyphicon glyphicon-download-alt\" aria-hidden=\"true\"></span>
                                                                </a>";
                                                        }
                                           echo "</td>
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