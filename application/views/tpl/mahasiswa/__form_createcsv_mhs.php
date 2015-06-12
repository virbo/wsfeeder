<!--
      <?php 
        //$error = $this->session->flashdata('error'); 
        if(!empty($stat_error)) { ?>
        <div class="bs-callout bs-callout-danger">
            <h4>Error</h4>
            <p><?php echo $stat_error;?></p>
          </div>
      <?php } ?>
      <?php 
        $error = $this->session->flashdata('sukses'); 
        if(!empty($stat_sukses)) { ?>
        <div class="bs-callout bs-callout-success">
            <h4>Sukses</h4>
            <p><?php echo $stat_sukses;?></p>
          </div>
      <?php } ?>-->
      <?php
        $attributes = array('class' => 'form-signin','enctype' => 'multipart/form-data', 'id' => 'myFRM');
        echo form_open('ws_mahasiswa/dump_csv',$attributes);
      ?>
      <div id="pesan"></div>
      <span id="loading"></span>
      <h2 class="form-signin-heading">Download Data CSV</h2>
        <div class="form-group">
            <label for="prodi">Program Studi</label>
            <select class="form-control" id="prodi" name="prodi">
            <?php
                foreach ($prodi as $key => $value) {
                    //echo $value['nm_lemb'];
                    echo "<option value=\"".$value['id_sms']."\">".$value['nm_lemb']."</option>";
                }
            ?>
            </select>
        </div>
        <div class="form-group">
            <label for="fileinput">Columns separated with:</label>
            <select class="form-control" name="separasi" id="separasi">
                <option value="," selected>Separation with coma (,)</option>
                <option value=";">Separation with semicolon (;)</option>
                <!--option value="2">Separation with dot (.)</option-->
            </select>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit" id="btn_upload" class="btn btn-default">Generate</button>
        <script>
            $(document).ready(function (e) {
                $("#myFRM").on('submit',(function(e) {
                    e.preventDefault();
                    $.ajax({
                        url: "<?php echo base_url().'index.php/ws_mahasiswa/createcsv' ?>",
                        type: "POST",
                        data: new FormData(this),
                        mimeType:"multipart/form-data",
                        contentType: false,
                        cache: false,
                        processData:false,
                        beforeSend:function()
                        {
                            $("#pesan").hide();
                            $("#loading").html('Generate files processing...Please wait...');
                            //$("#loading").show();
                        },
                        complete:function()
                        {
                            //$("#loading").hide();
                            $("#loading").empty();
                            $("#pesan").show();
                        },
                        error: function()
                        {
                            $('#pesan').html('Error, unknown');
                        },
                        success: function(data)
                        {
                            $("#pesan").html(data);
                            //window.location=data;
                        }
                    });
                }));
            });
        </script>
      <?php echo form_close();?>