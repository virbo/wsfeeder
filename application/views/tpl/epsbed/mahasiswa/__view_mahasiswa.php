<div class="page-header" style="margin-top: 10px;" >
    <div class="row">
        <div class="col-md-4">
            <h4>Data Mahasiswa (Tabel MSMHS.DBF)</h4>
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
                        <a href="javascript:void();" class="modalButton btn btn-success" data-toggle="modal" data-src="<?php echo base_url();?>index.php/ws_mahasiswa/form_csv" data-target="#modalku">
                            <span class="glyphicon glyphicon-hdd" aria-hidden="true"></span> Eksport ke Feeder
                        </a>
                    </div>
                    <div class="col-md-4" align="right">
                        <?php
                            $attributes = array('class' => 'form-inline');
                            echo form_open('epsbed_mahasiswa/epsbed',$attributes);
                        ?>
                            <div class="input-group">
                                <input type="text" class="form-control" name="nm_mhs" placeholder="Search..." value="<?php echo $temp_cari;?>">
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
                                <h4>Daftar Mahasiswa</h4>
                                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span> Checklist nama Mahasiswa kemudian klik <a class="btn btn-success"><span class="glyphicon glyphicon-hdd" aria-hidden="true"></span> Eksport ke Feeder</a> Untuk memasukkan data ke Feeder<br />
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END HELPER-->
                
                
                
            </div>
            <table class="table table-hover table-striped table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>
                            <input type="checkbox" />
                        </th>
                        <th>NIM</th>
                        <th>Nama Mahasiswa</th>
                        <th>Tanggal Lahir</th>
                        <th>Program Studi</th>
                        <th>Status Masuk</th>
                        <th>Angkatan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <?php
                        if ($temp_tot==0) {
                            echo "<tr>
                                        <td colspan=\"9\">Data kosong</td>
                                  </tr>";
                        } else {
                            $i=0;
                            foreach ($temp_rec as $value) {
                                echo "<tr>
                                          <td>".++$i."</td>
                                          <td><input type=\"checkbox\" name=\"kd_mhs\" value=\"".$value->NIMHSMSMHS."\"></td>
                                          <td>".$value->NIMHSMSMHS."</td>
                                          <td>".$value->NMMHSMSMHS."</td>
                                          <td>".$value->TGLHRMSMHS."</td>
                                          <td>".$value->NMPSTMSPST."</td>
                                          <td>".($value->STPIDMSMHS=='B'?'Peserta didik baru':'Pindahan')."</td>
                                          <td>".$value->TAHUNMSMHS."</td>
                                          <td>".$value->STMHSMSMHS."</td>
                                          <td></td>
                                      </tr>";
                            }                            
                        }    
                    ?>
                </tbody>
            </table>
            <div class="panel-footer">
                <!--div class="row">
                    <div class="col-md-6">
                        <?php
                            echo "Showing ".$start.' - '.$end.' of '.$total.' results';
                        ?>
                    </div>
                    <div class="col-md-6" style="margin-top: -15px;" >
                        <?php echo $pagination;?>
                    </div>
                </div-->
            </div>
        </div>
    </div>
</div>
