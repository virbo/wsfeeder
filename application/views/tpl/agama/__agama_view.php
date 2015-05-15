<div class="row">
    <div class="col-md-12">
        <div class="btn-group">
            <a href="javascript:void();" class="modalButton btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                <span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Tambah data <span class="caret"></span>
            </a>
            <ul class="dropdown-menu" role="menu">
                <li><a href="#">Input with Form</a></li>
                <li><a href="#">Input from File (Excel or CSV)</a></li>
            </ul>
        </div>
        <a href="javascript:void();" class="modalButton btn btn-warning" data-toggle="modal" data-src="<?php echo base_url();?>index.php/welcome/listdir/agama" data-target="#modalku">
            <span class="glyphicon glyphicon-tasks" aria-hidden="true"></span> Struktur tabel
        </a>    
    </div>
    
</div>
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
            $i=0;
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