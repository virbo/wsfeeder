<div class="page-header" style="margin-top: 10px;" >
    <div class="row">
        <div class="col-md-4">
            <h4>Daftar Program Studi</h4>
        </div>
        <div class="col-md-8">
            
        </div>    
    </div>
    
</div>
<div class="row">
    <div class="col-md-12">
        <table class="table table-hover table-striped table-bordered">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="70%">Nama Prodi</th>
                    <th width="10%">Jenjang</th>
                    <th>Kode Prodi</th>
                    <!--th width="20%">Aksi</th-->
                </tr>
            </thead>
            <tbody>
                <?php
                  //  var_dump($listtable);
                    if (!$listprodi) {
                        //echo "Data kosong";
                        echo "<tr>
                                    <td colspan=\"4\">
                                        Data Kosong.".($ses_id_sp==0?' Kode PT tidak ditemukan, silahkan masukkan Kode PT Anda melalui halaman <a href="'.base_url().'index.php/welcome/setting">Setting</a>':'')."
                                    </td>
                              </tr>";
                    } else {
                        $i=0+$offset;
                        foreach ($listprodi as $key => $value) {
                            //$filter_jenjang = $value['id_jenj_didik'];
                            $filter_jenjang = "id_jenj_didik = ".$value['id_jenj_didik'];
                            $dump_jenjang = $this->feeder->getrecord($this->session->userdata('token'),'jenjang_pendidikan',$filter_jenjang);
                            //$temp_jenjang = $dump_jenjang['nm_jenj_didik'];
                            $dumy_jenjang = $dump_jenjang['result'];
                            echo "<tr>
                                        <td>".++$i."</td>
                                        <td>".$value['nm_lemb']."</td>
                                        <td>".$dumy_jenjang['nm_jenj_didik']."</td>
                                        <!--td>".$value['kode_prodi']." - ".$value['id_jur']."</td-->
                                        <td>".$value['kode_prodi']."</td>
                                        <!--td>";
                                            echo "<a href=\"javascript:void();\" class=\"modalButton\" data-toggle=\"modal\" data-src=\"".base_url()."index.php/welcome/listdir/".$value['table']."\" data-target=\"#modalku\">
                                                      <span class=\"glyphicon glyphicon-tasks\" aria-hidden=\"true\"></span> Struktur 
                                                  </a> |  
                                                  <a href=\"javascript:void();\" class=\"modalButton\" data-toggle=\"modal\" data-src=\"".base_url()."index.php/welcome/view/".$value['table']."\" data-height=\"300\" data-width=\"560\" data-target=\"#modalku\">
                                                      <span class=\"glyphicon glyphicon-search\" aria-hidden=\"true\"></span> View Data
                                                  </a>
                                        </td-->
                                  </tr>";
                            //echo $value['table'];
                        }    
                    }        
                ?>
            </tbody>
        </table>        
    </div>
</div>
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