<!--div class="page-header"-->
  <h1>View <small>Tabel <?php echo $tabel;?></small></h1>
<!--/div-->
<div class="row">
    <div class="col-md-6">
        <!--a href="javascript:void();" class="modalButton btn btn-success" data-toggle="modal" data-src="<?php echo base_url().$url_add;?>" data-target="#modalku"-->
        <a href="<?php echo base_url().$url_add;?>" class="modalButton btn btn-success">
            <span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Import data (CSV/Excel)
        </a>
        <a href="javascript:void();" class="modalButton btn btn-warning" data-toggle="modal" data-src="<?php echo base_url();?>index.php/welcome/listdir/mahasiswa" data-target="#modalku">
            <span class="glyphicon glyphicon-tasks" aria-hidden="true"></span> Struktur tabel
        </a>
        <a href="<?php echo base_url();?>/index.php/ws_mahasiswa/createcsv" class="modalButton btn btn-info">
            <span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span> Download File CSV
        </a>
    </div>
    <div class="col-md-6">
        
    </div>
</div>
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