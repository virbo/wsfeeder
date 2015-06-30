<div class="page-header" style="margin-top: 10px;" >
    <div class="row">
        <div class="col-md-4">
            <h4>Daftar Pekerjaan</h4>
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
                    echo form_open('ref_pekerjaan/view',$attributes);
                ?>
                    <div class="input-group">
                        <input type="text" class="form-control" name="nm_pek" placeholder="Search..." value="<?php echo $temp_pek;?>">
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
                            <?php
                                foreach ($listsdic as $value) {
                                    echo "<th>".$value['column_name']."</th>";
                                }
                            ?>
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
                                $i=0;
                                foreach ($listsrec as $value) {
                                    echo "<tr>
                                                <td>".++$i."</td>";
                                                foreach ($listsdic as $value2) {
                                                    if (isset($value[$value2['column_name']])) {
                                                        $temp_isi = $value[$value2['column_name']];
                                                    } else {
                                                        $temp_isi = "";
                                                    }
                                                    echo "<td>".$temp_isi."</td>";
                                                }
                                       echo "</tr>";
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
