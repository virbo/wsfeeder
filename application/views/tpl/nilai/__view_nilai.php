<h4>Jumlah Record: <?php echo $jml;?></h4>
<table class="table table-hover table-striped">
    <thead>
        <tr>
            <th>#</th>
            <?php
                foreach ($listdic as $key => $value) {
                    echo "<th>".$value['column_name']."</th>";
                }
            ?>
        </tr>
    </thead>
    <tbody>
        <?php
            $i=0;
            foreach ($nilai as $key => $value) {
                echo "<tr>
                            <td>".++$i."</td>";
                            foreach ($listdic as $key2 => $value2) {
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