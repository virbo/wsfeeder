<table class="table table-hover table-striped">
    <thead>
        <tr>
            <td>#</td>
            <td>Nama Kolom</td>
            <td>Primary Key</td>
            <td>Tipe</td>
            <td>Not Null</td>
            <td>Keterangan</td>
        </tr>
    </thead>
    <tbody>
        <?php
          //var_dump($listdic);
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