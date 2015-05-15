<div class="page-header">
  <h1>Daftar Program Studi</h1>
</div>
<table class="table table-hover table-striped">
    <thead>
        <tr>
            <th width="5%">#</td>
            <th width="70%">Nama Prodi</th>
            <th width="10%">Jenjang</th>
            <th>Kode Prodi</th>
            <!--th width="20%">Aksi</th-->
        </tr>
    </thead>
    <tbody>
        <?php
          //  var_dump($listtable);
            $i=0;
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
        ?>
    </tbody>
</table>