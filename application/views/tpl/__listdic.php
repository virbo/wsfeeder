<h4>Tabel <?php echo $tabel;?></h4>
<?php 
        $error = $this->session->flashdata('token_error'); 
        if(!empty($error)) { ?>
        <div class="bs-callout bs-callout-danger">
            <h4>Error</h4>
            <p><?php echo $error;?></p>
            errorrrrr
          </div>
    <?php } 
        $sukses = $this->session->flashdata('token_sukses'); 
        if(!empty($sukses)) { ?>
        <div class="bs-callout bs-callout-success">
            <h4>Sukses</h4>
            <p><?php echo $sukses;?></p>
          </div>
    <?php } ?>
<table class="table table-hover table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Nama Kolom</th>
            <th>Primary Key</th>
            <th>Tipe</th>
            <th>Not Null</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        <?php
          //  var_dump($listtable);
            $i=0;
            foreach ($listdic['result'] as $key => $value) {
                isset($value['pk'])?$pk='PK':$pk='';
                isset($value['not_null'])?$nl='not_null':$nl='null';
                echo "<tr>
                            <td>".++$i."</td>
                            <td>".$value['column_name']."</td>
                            <td>".$pk."</td>
                            <td>".$value['type']."</td>
                            <td>".$nl."</td>
                            <td>".$value['desc']."</td>
                      </tr>";
                //echo $value['table'];
            }        
        ?>
    </tbody>
</table>