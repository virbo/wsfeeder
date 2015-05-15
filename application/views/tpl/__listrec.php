<h4>Tabel <?php echo $tabel;?> (Jumlah Record: <?php echo $total['result'];?>)</h4>
<?php //echo $pagination;?>
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