<h1>Daftar tabel</h1>
<table class="table table-hover table-striped">
    <thead>
        <tr>
            <th>#</td>
            <th width="20%">Nama Table</th>
            <th width="10%">Jenis</th>
            <th>Keterangan</th>
            <th width="20%">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
          //  var_dump($listtable);
            $i=0;
            foreach ($listtable['result'] as $key => $value) {
                echo "<tr>
                            <td>".++$i."</td>
                            <td>".$value['table']."</td>
                            <td>".$value['jenis']."</td>
                            <td>".$value['keterangan']."</td>
                            <td>";
                                echo "<a href=\"javascript:void();\" class=\"modalButton\" data-toggle=\"modal\" data-src=\"".base_url()."index.php/welcome/listdir/".$value['table']."\" data-target=\"#modalku\">
                                          <span class=\"glyphicon glyphicon-tasks\" aria-hidden=\"true\"></span> Struktur 
                                      </a> |  
                                      <a href=\"javascript:void();\" class=\"modalButton\" data-toggle=\"modal\" data-src=\"".base_url()."index.php/welcome/view/".$value['table']."\" data-height=\"300\" data-width=\"560\" data-target=\"#modalku\">
                                          <span class=\"glyphicon glyphicon-search\" aria-hidden=\"true\"></span> View Data
                                      </a>
                            </td>
                      </tr>";
                //echo $value['table'];
            }        
        ?>
    </tbody>
</table>