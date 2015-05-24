<div class="page-header" style="margin-top: 10px;" >
    <div class="row">
        <div class="col-md-4">
            <h4>Data Mahasiswa</h4>
        </div>
        <div class="col-md-8">
            
        </div>    
    </div>
    
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <a href="javascript:void();" class="modalButton btn btn-success" data-toggle="modal" data-src="<?php echo base_url();?>index.php/ws_mahasiswa/form_csv" data-target="#modalku">
                    <span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Upload data CSV
                </a>
                <a href="javascript:void();" class="modalButton btn btn-warning" data-toggle="modal" data-src="<?php echo base_url();?>index.php/welcome/listdir/mahasiswa" data-target="#modalku">
                    <span class="glyphicon glyphicon-tasks" aria-hidden="true"></span> Struktur tabel
                </a>
                <a href="<?php echo base_url();?>index.php/ws_mahasiswa/createcsv" class="modalButton btn btn-info">
                    <span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span> Download File CSV
                </a>
            </div>
            <div class="panel-body">
                <h4>Daftar Kelas Perkuliahan Setiap Semester Per Kelas.</h4>
                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span> Klik icon <a><span class="glyphicon glyphicon-search" aria-hidden="true"></span></a> di setiap Mata Kuliah untuk melihat Daftar Asli Nilai Mahasiswa.<br />
                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span> Klik icon <a><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span></a> di setiap Mata Kuliah untuk Mendownload Daftar Nilai Mahasiswa.<br />
                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span> Klik tombol <a class="btn btn-success"><span class="glyphicon glyphicon-hdd" aria-hidden="true"></span> Upload File CSV</a> untuk Mengupload Daftar Nilai Mahasiswa yang sudah diisi.
            </div>
            <table class="table table-hover table-striped table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
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
                    <?php
                        if ($total==0) {
                            echo "<tr>
                                        <td colspan=\"9\">Data kosong</td>
                                  </tr>";
                        } else {
                            $i=0+$offset;
                            foreach ($listsrec as $row) {
                                echo "<tr>
                                            <td>".++$i."</td>
                                            <td>".$row['nipd']."</td>
                                            <td>".$row['nm_pd']."</td>
                                            <td>".date('d-m-Y',strtotime($row['tgl_lahir']))."</td>
                                            <td>".$row['fk__sms']."</td>
                                            <td>".$row['fk__jns_daftar']."</td>
                                            <td>".substr($row['mulai_smt'], 0,4)."</td>";
                                            
                                            $filter_mhs = "id_pd='".$row['id_pd']."'";
                                            $dump_mlulus = $this->feeder->getrecord($this->session->userdata('token'),'mahasiswa',$filter_mhs);
                                            $dumy_mlulus = $dump_mlulus['result']['stat_pd'];
                                            
                                            $filter_lulus = "id_stat_mhs='".$dumy_mlulus."'";
                                            $dump_lulus = $this->feeder->getrecord($this->session->userdata('token'),'status_mahasiswa',$filter_lulus);
                                            $dumy_lulus = $dump_lulus['result']['nm_stat_mhs'];
                                            
                                            $label = $dumy_mlulus=='L'?'label-success':'';
                                            $label .= $dumy_mlulus=='K'?'label-danger':'';
                                            $label .= $dumy_mlulus=='D'?'label-warning':'';
                                            $label .= $dumy_mlulus=='N'?'label-default':'';
                                            $label .= $dumy_mlulus=='C'?'label-info':'';
                                            $label .= $dumy_mlulus=='G'?'label-primary':'';
                                            $label .= $dumy_mlulus=='X'?'label-default':'';
                                            $label .= $dumy_mlulus=='A'?'label-primary':'';
                                      echo "<td><span class=\"label ".$label."\">".$dumy_lulus."</span></td>
                                            <td>";
                                            if ($row['id_jns_daftar']==2) {
                                                echo "<div class=\"btn-group btn-group-sm\" role=\"group\" aria-label=\"...\">
                                                    <button type=\"button\" class=\"btn btn-default dropdown-toggle btn-danger\" data-toggle=\"dropdown\" aria-expanded=\"false\">
                                                        <span class=\"glyphicon glyphicon-th\" aria-hidden=\"true\"></span> Nilai Pindahan <span class=\"caret\"></span>
                                                    </button>
                                                    <ul class=\"dropdown-menu\" role=\"menu\">
                                                        <li>
                                                            <a href=\"javascript:void();\" class=\"modalButton\" data-toggle=\"modal\" data-src=\"".base_url()."index.php/ws_mahasiswa/view_nilai_pindah/".$row['id_reg_pd']."\" data-target=\"#modalku\">
                                                                Lihat Nilai Pindahan
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href=\"".base_url()."index.php/ws_mahasiswa/createcsv_nilai_pindahan/".$row['id_reg_pd']."\">
                                                                Download Daftar Nilai (CSV)
                                                            </a>
                                                        </li>
                                                        <li><a href=\"#\">Upload Nilai Pindahan (CSV)</a></li>
                                                    </ul>
                                                </div>";
                                            }
                                                
                                     echo "</td>
                                      </tr>";
                            }    
                        }
                        
                        /*foreach ($listsrec['result'] as $key => $value) {
                            echo "<tr>
                                        <td>".++$i."</td>";
                                        foreach ($listsdic['result'] as $key2 => $value2) {
                                            if (isset($value[$value2['column_name']])) {
                                                $temp_isi = $value[$value2['column_name']];
                                            } else {
                                                $temp_isi = "";
                                            }
                                            echo "<td>".$temp_isi."</td>";
                                        }
                               echo "</tr>";
                        }*/       
                    ?>
                </tbody>
            </table>
            <div class="panel-footer">
                <div class="row">
                    <div class="col-md-6">
                        <?php
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
