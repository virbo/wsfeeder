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
        <?php
            $attributes = array('enctype' => 'multipart/form-data', 'id' => 'myFRM');
            echo form_open('epsbed_mahasiswa/insert',$attributes);
          ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-md-8">
                            <button class="modalButton btn btn-success" id="btn_eksport">
                                <span class="glyphicon glyphicon-hdd" aria-hidden="true"></span> Eksport ke Feeder
                            </button> 
                        </div>
                        <div class="col-md-4" align="right">
                                <div class="input-group">
                                    <!--input type="text" class="form-control" name="nm_mhs" placeholder="Search... (NIM, Nama)" value="<?php echo $temp_cari;?>"-->
                                    <input type="text" class="form-control" name="nm_mhs" placeholder="Search... (NIM, Nama)" id="cari">
                                    <span class="input-group-btn">
                                        <button class="btn btn-warning" type="submit" id="btn_cari"><i class="fa fa-search search-btn"></i></button>
                                    </span>
                                </div>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <div id="loading"></div><br />
                    <div id="pesan"></div>
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
                <table class="table table-striped">
                    <tr>
                        <td width="31%">
                            <div class="form-group">
                                <select class="form-control select2" id="prodi" name="cari"></select>
                            </div>
                        </td>
                        <td width="33%">
                            <select  class="form-control" id="stat_mhs">
                                <option value="">--Status Awal Masuk--</option>
                                <option value="B">Peserta didik baru</option>
                                <option value="P">Pindahan</option>
                            </select>
                        </td>
                        <td width="31%">
                            <select  class="form-control select" id="awal_masuk">
                                <option value="">--Tahun Awal Masuk--</option>
                                <?php
                                    /*if (!$error) {
                                        foreach ($smt as $value) {
                                            echo "<option value=\"".$value['id_smt']."\">".$value['nm_smt']."</option>";
                                        }
                                    } else {
                                        echo "<option value=\"\">".$error."</option>";
                                    }*/
                                    $temp_year = date("Y");
                                    for ($i=$temp_year; $i>=2000 ; $i--) { 
                                        echo "<option value=\"".$i."\">".$i."</option>";
                                    }
                                ?>
                                <!--option value="20121">2012</option>
                                <option value="20131">2013</option>
                                <option value="20141">2014</option>
                                <option value="20151">2015</option-->
                            </select>
                        </td>
                        <td>
                            
                        </td>
                        <td width="5%">
                            <button class="btn btn-info" type="submit" id="btn_filter"><i class="fa fa-search search-btn"></i> Filter</button>
                        </td>
                    </tr>
                </table>
                <table class="table table-hover table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>
                                <input type="checkbox" name="select-all" id="select-all" />
                            </th>
                            <th>NIM</th>
                            <th>Nama Mahasiswa</th>
                            <th>Tanggal Lahir</th>
                            <!--th>Program Studi</th-->
                            <th>Status Masuk</th>
                            <th>Semester Awal Masuk</th>
                            <th>Angkatan</th>
                            <th>Status</th>
                            <!--th>Status di Feeder</th-->
                        </tr>
                    </thead>
                    <tbody id="isi"></tbody>
                </table>
                <div class="panel-footer"></div>
            </div>
        </form>
    </div>
</div>