<div class="page-header" style="margin-top: 10px;" >
    <div class="row">
        <div class="col-md-4">
            <h4>Daftar Wilayah</h4>
        </div>
        <div class="col-md-8">
            
        </div>    
    </div>
    
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?php
                    $attributes = array('class' => 'form-inline');
                    echo form_open('ref_wilayah/view',$attributes);
                ?>
                    <div class="input-group">
                        <input type="text" class="form-control" name="nm_wil" placeholder="Search..." value="<?php echo $temp_wil;?>">
                        <span class="input-group-btn">
                            <button class="btn btn-success" type="submit"><i class="fa fa-search search-btn"></i></button>
                        </span>
                    </div>
                </form>
            </div>
            <div class="panel-body">
                <table class="table table-hover table-striped table-bordered">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th>id_wil</th>
                            <th>nm_wil</th>
                            <th>asal_wil</th>
                            <th>kode_bps</th>
                            <th>kode_dagri</th>
                            <th>kode_keu</th>
                            <th>id_induk_wilayah</th>
                            <th>id_level_wil</th>
                            <th>id_negara</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if (!$listsrec) {
                                echo "<tr>
                                            <td colspan=\"10\">
                                                Data Kosong
                                            </td>
                                      </tr>";
                            } else {
                                $i=0+$offset;
                                foreach ($listsrec as $value) {
                                    echo "<tr>
                                              <td>".++$i."</td>
                                              <td>".$value['id_wil']."</td>
                                              <td>".$value['nm_wil']."</td>
                                              <td>".$value['asal_wil']."</td>
                                              <td>".$value['kode_bps']."</td>
                                              <td>".$value['kode_dagri']."</td>
                                              <td>".$value['kode_keu']."</td>
                                              <td>".$value['id_induk_wilayah']."</td>
                                              <td>".$value['id_level_wil']."</td>
                                              <td>".$value['id_negara']."</td>
                                          </tr>";    
                                } 
                            }        
                        ?>
                    </tbody>
                </table> 
            </div>
            <div class="panel-footer">
                <div class="row">
                    <div class="col-md-6">
                        <?php
                            echo "Showing ".$start.' - '.$end.' of '.$total.' results';
                        ?>
                    </div>
                    <div class="col-md-6" style="margin-top: -15px;" >
                        <?php echo $pagination;?>
                    </div>
                </div>
            </div>
        </div>       
    </div>
</div>
