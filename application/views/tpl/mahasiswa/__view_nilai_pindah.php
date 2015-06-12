<table class="table table-hover table-striped table-bordered">
    <thead>
        <!--tr>
            <th rowspan="2">#</th>
            <th>Kode MK Asal</th>
            <th>Nama MK Asal</th>
            <th>SKS Asal</th>
            <th>NH Asal</th>
        </tr-->
        <tr>
            <th rowspan="2">#</th>
            <th colspan="4">Nilai PT Asal</th>
            <th colspan="5">Nilai Konversi PT Baru (diakui)</th>
        </tr>
        <tr>
            <th>Kode MK</th>
            <th>Nama MK</th>
            <th>SKS</th>
            <th>Nilai Huruf</th>
            
            <th>Kode MK</th>
            <th>Nama MK</th>
            <th>SKS</th>
            <th>Nilai Huruf</th>
            <th>Nilai Angka</th>
        </tr>
        
        
        
    </thead>
    <?php
        if ($jml==0) {
            echo "<tr>
                      <td colspan=\"10\">Data kosong</td>
                  </tr>";
        } else {
            $i=0;
            $jml_sks_asal=0;
            $jml_sks_akui=0;
            foreach ($nilai_pindah as $row) {
                $jml_sks_asal = $jml_sks_asal+$row['sks_asal'];
                echo "<tr>
                            <td>".++$i."</td>
                            <td>".$row['kode_mk_asal']."</td>
                            <td>".$row['nm_mk_asal']."</td>
                            <td>".$row['sks_asal']."</td>
                            <td>".$row['nilai_huruf_asal']."</td>";
                            
                            $filter_mk = "id_mk='".$row['id_mk']."'";
                            $temp_mk = $this->feeder->getrecord($this->session->userdata('token'),'mata_kuliah',$filter_mk);
                            //var_dump($temp_mk['result']);
                            $jml_sks_akui = $jml_sks_akui+$row['sks_diakui'];
                            echo "<td>".$temp_mk['result']['kode_mk']."</td>
                                  <td>".$temp_mk['result']['nm_mk']."</td>
                                  <td>".$row['sks_diakui']."</td>
                                  <td>".$row['nilai_huruf_diakui']."</td>
                                  <td>".$row['nilai_angka_diakui']."</td>";
                echo "</tr>";
            }
            echo "<tr>
                        <td colspan=\"3\">Jumlah SKS</td>
                        <td>".$jml_sks_asal."</td>
                        <td colspan=\"3\"></td>
                        <td>".$jml_sks_akui."</td>
                        <td colspan=\"2\"></td>
                  </tr>";
        }
    ?>
</table>