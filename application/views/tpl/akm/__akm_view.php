<div class="page-header" style="margin-top: 10px;" >
    <div class="row">
        <div class="col-md-4">
            <h4>Aktivitas Kuliah Mahasiswa</h4>
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
                    <span class="glyphicon glyphicon-hdd" aria-hidden="true"></span> Upload File CSV
                </a>
            </div>
            <div class="panel-body">
                <h4>Daftar Aktivitas Kuliah Mahasiswa</h4>
                <!--span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span> Klik icon <a><span class="glyphicon glyphicon-search" aria-hidden="true"></span></a> di setiap Mata Kuliah untuk melihat Daftar Asli Nilai Mahasiswa.<br />
                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span> Klik icon <a><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span></a> di setiap Mata Kuliah untuk Mendownload Daftar Nilai Mahasiswa.<br /-->
                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span> Klik tombol <a class="btn btn-success"><span class="glyphicon glyphicon-hdd" aria-hidden="true"></span> Upload File CSV</a> untuk Mengupload Daftar Aktifitas Perkuliahan Mahasiswa yang sudah diisi.<br />
                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span> Jika data semester tidak ada, klik tombol <a class="btn btn-info"><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span> Download File CSV</a> untuk membuat dan sekaligus mendownload Daftar Aktifitas Perkuliahan Mahasiswa di semester aktif.
            </div>
            <table class="table table-hover table-striped table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>NIM</th>
                        <th>Nama Mahasiswa</th>
                        <th>Program Studi</th>
                        <th>Angkatan</th>
                        <th>Semester</th>
                        <th>IPS</th>
                        <th>IPK</th>
                        <th>SKS Semester</th>
                        <th>SKS Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <?php
                    if ($total==0) {
                        //echo $total;
                        echo "<tbody>
                                    <tr>
                                        <td colspan=\"11\" align=\"center\">";
                                            $filters_smt = "id_smt='".$id_smt."'";
                                            $dumps_smt = $this->feeder->getrecord($this->session->userdata('token'),'semester',$filters_smt);
                                            echo "Tidak ada di <strong>Semester ".$dumps_smt['result']['nm_smt']."</strong><br />
                                                 <a href=\"".base_url()."index.php/ws_akm/createcsv/".$id_smt."\" class=\"btn btn-info\"><span class=\"glyphicon glyphicon-download-alt\" aria-hidden=\"true\"></span> Download File CSV</a>";
                                   echo "</td>
                                    </tr>
                              </tbody>
                              </table>";
                    } else {
                ?>
                <tbody>
                    <!--tr>
                        <td></td>
                        <td>
                            <select class="form-control" id="prodi" name="prodi">
                                <option value="" selected>Semua Prodi</option>
                                
                            </select>
                        </td>
                        <td>
                            <select class="form-control" id="prodi" name="semester">
                                 <option value="" selected >Semua Semester</option>
                                 
                            </select>
                        </td>
                        <td colspan="2">
                            <form class="form-horizontal">
                            <div class="input-group">
                              <input type="text" class="form-control" name="nm_mk" placeholder="Cari Mata Kuliah, Kode Mata Kuliah...">
                              <span class="input-group-btn">
                                <button class="btn btn-info" type="submit"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                              </span>
                            </div>
                            </form>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr-->
                    <?php
                        $i=0+$offset;
                        //echo $offset;
                        foreach ($listakm as $akm) {
                            //var_dump($akm);
                            echo "<tr>
                                        <td>".++$i."</td>";
                                        
                                        $filter_nim = "id_reg_pd='".$akm['id_reg_pd']."'";
                                        $dump_nim = $this->feeder->getrecord($this->session->userdata('token'),'mahasiswa_pt',$filter_nim);
                                   echo "<td>".$dump_nim['result']['nipd']."</td>";
                                   
                                        /*$filter_nma = "id_pd='".$dump_nim['result']['id_pd']."'";
                                        $dump_nma = $this->feeder->getrecord($this->session->userdata('token'),'mahasiswa',$filter_nma);*/
                                   echo "<td>".$dump_nim['result']['nm_pd']."</td>";
                                        
                                        $filter_sms = "id_sms='".$dump_nim['result']['id_sms']."'";
                                        $dump_sms = $this->feeder->getrecord($this->session->userdata('token'),'sms',$filter_sms);
                                        
                                        $filter_jenjang = "id_jenj_didik='".$dump_sms['result']['id_jenj_didik']."'";
                                        //$filter_jenjang = "id_jenj_didik='".$dump_nim['result']['id_sms']."'";
                                        $dump_jenjang = $this->feeder->getrecord($this->session->userdata('token'),'jenjang_pendidikan',$filter_jenjang);
                                  echo "<td>".$dump_jenjang['result']['nm_jenj_didik']." ".$dump_nim['result']['fk__sms']."</td>";
                                        
                                        $filter_smt = "id_smt='".$id_smt."'";
                                        $dump_smt = $this->feeder->getrecord($this->session->userdata('token'),'semester',$filter_smt);
                                   echo "<td>".$dump_smt['result']['nm_smt']."</td>
                                        
                                        <td>".substr($dump_nim['result']['mulai_smt'],0,-1)."</td>
                                        <td>".$akm['ips']."</td>
                                        <td>".$akm['ipk']."</td>
                                        <td>".$akm['sks_smt']."</td>
                                        <td>".$akm['sks_total']."</td>";
                                        
                                        $filter_stat = "id_stat_mhs='".$akm['id_stat_mhs']."'";
                                        $dump_stat = $this->feeder->getrecord($this->session->userdata('token'),'status_mahasiswa',$filter_stat);
                                  echo "<td>".$dump_stat['result']['nm_stat_mhs']."</td>
                                  </tr>";
                        }
                    ?>
                </tbody>
            </table>
            <div class="panel-footer">
                <div class="row">
                    <div class="col-md-6">
                        <?php
                            
                            //echo "Showing ".$start.' - '.$end.' of '.$total.' result <br />'.$this->pagination->cur_page.'<br />'.$this->pagination->per_page;
                            echo "Showing ".$start.' - '.$end.' of '.$total.' results';
                        ?>
                    </div>
                    <div class="col-md-6" style="margin-top: -15px;" >
                        <?php echo $pagination;?>
                    </div>
                </div>
            </div>
            <?php } ?>
        
        </div>    
    </div>
        
</div>