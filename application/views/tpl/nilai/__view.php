<!--div class="page-header"-->
  <h1>View <small>Tabel <?php echo $tabel;?></small></h1>
<!--/div-->
<div class="row">
    <div class="col-md-6">
        <!--a href="javascript:void();" class="modalButton btn btn-success" data-toggle="modal" data-src="<?php echo base_url().$url_add;?>" data-target="#modalku"-->
        <a href="<?php echo base_url().$url_add;?>" class="modalButton btn btn-success">
            <span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Import data (CSV File)
        </a>
        <a href="javascript:void();" class="modalButton btn btn-warning" data-toggle="modal" data-src="<?php echo base_url();?>index.php/welcome/listdir/nilai" data-target="#modalku">
            <span class="glyphicon glyphicon-tasks" aria-hidden="true"></span> Struktur tabel
        </a>
        
    </div>
    <div class="col-md-6">
        <form action="#" method="post">
            <div class="col-md-3" style="margin-top: 10px; ">
                Kelas Perkuliahan
            </div>
            <div class="col-md-7">
                <select class="form-control" id="kelas" name="kelas">
                    <option value="7ae16096-8b43-4e76-b040-2c4031d5b47f">WAT504 - Bahasa Inggris 4 - 02 - 20122</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-danger" type="submit" id="btn_filter">Filter</button>
            </div>
        </form>
    </div>
</div>
<div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-9">
        <div class="row">
            <div class="col-md-6" style="margin-top: 40px;">
                <?php
                    $offset==0? $start=$this->pagination->cur_page: $start=$offset+1;
                    $end = $this->pagination->cur_page * $this->pagination->per_page;
                    
                    //echo "Showing ".$start.' - '.$end.' of '.$total.' result <br />'.$this->pagination->cur_page.'<br />'.$this->pagination->per_page;
                    echo "Showing ".$start.' - '.$end.' of '.$total.' results';
                ?>
            </div>
            <div class="col-md-6">
                <?php echo $pagination;?>
            </div>
        </div> 
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Filter</th>
                </tr>
            </thead>
        </table>
        
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingOne">
              <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                  Program Studi
                </a>
              </h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
              <div class="panel-body">
                <?php
                    foreach ($listprodi as $key => $value) {
                        $filter_jenjang = "id_jenj_didik = ".$value['id_jenj_didik'];
                        $dump_jenjang = $this->feeder->getrecord($this->session->userdata('token'),'jenjang_pendidikan',$filter_jenjang);
                        //$temp_jenjang = $dump_jenjang['nm_jenj_didik'];
                        $dumy_jenjang = $dump_jenjang['result'];
                        //var_dump($dumy_jenjang);
                        echo $dumy_jenjang['nm_jenj_didik'] .' - '. $value['nm_lemb'];
                    }
                  ?>
              </div>
            </div>
          </div>
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingTwo">
              <h4 class="panel-title">
                <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                  Semester
                </a>
              </h4>
            </div>
            <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
              <div class="panel-body">
                <?php
                    foreach ($semester as $key => $value) {
                        echo $value['nm_smt'].'<br />';
                    }
                  ?>
              </div>
            </div>
          </div>
        </div>

        
    </div>
    <div class="col-md-9">       
        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <?php
                        foreach ($listsdic['result'] as $key => $value) {
                            echo "<th>".$value['column_name']."</th>";
                        }
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php
                    $i=0+$offset;
                    foreach ($listsrec['result'] as $key => $value) {
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
                    }        
                ?>
            </tbody>
        </table>  
        <div class="row">
            <div class="col-md-6">
                <?php
                    $offset==0? $start=$this->pagination->cur_page: $start=$offset+1;
                    $end = $this->pagination->cur_page * $this->pagination->per_page;
                    
                    //echo "Showing ".$start.' - '.$end.' of '.$total.' result <br />'.$this->pagination->cur_page.'<br />'.$this->pagination->per_page;
                    echo "Showing ".$start.' - '.$end.' of '.$total.' results';
                ?>
            </div>
            <div class="col-md-6">
                <?php echo $pagination;?>
            </div>
        </div>              
    </div>
</div>

