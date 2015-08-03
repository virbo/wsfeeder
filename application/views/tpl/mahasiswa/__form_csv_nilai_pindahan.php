      
      <?php
        $attributes = array('class' => 'form-signin','enctype' => 'multipart/form-data', 'id' => 'myFRM');
        $hidden = array('id_reg_pd' => $id_reg_pd);
        echo form_open('',$attributes,$hidden);
      ?>
      <div id="pesan"></div>
      <span id="loading"></span>
      <h2 class="form-signin-heading">Upload Nilai Pindahan (CSV File)</h2>
        <div class="form-group">
            <label for="fileinput">File input</label>
            <input type="file" id="fileinput" name="userfile" class="form-control" >
        </div>
        <div class="form-group">
            <label for="fileinput">Columns separated with:</label>
            <select class="form-control" name="separasi" id="separasi">
                <option value="," selected>Separation with coma (,)</option>
                <option value=";">Separation with semicolon (;)</option>
            </select>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit" id="btn_upload" class="btn btn-default">Upload</button>
        <script>
            $(document).ready(function (e) {
                
                $("#myFRM").on('submit',(function(e) {
                    e.preventDefault();
                    $.ajax({
                        url: "<?php echo base_url().'index.php/ws_mahasiswa/extractcsv_nilai_pindahan/' ?>",
                        type: "POST",
                        data: new FormData(this),
                        mimeType:"multipart/form-data",
                        contentType: false,
                        cache: false,
                        processData:false,
                        beforeSend:function()
                        {
                            $("#pesan").hide();
                            $("#loading").html('<i class=\"fa fa-spinner fa-spin\"></i> Extract file...Please wait...');
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
                            //$('#pesan').html('Error, unknown');
                            $('#pesan').html('<div class="bs-callout bs-callout-danger">Error: Unknown data</div>');
                        },
                        success: function(data)
                        {
                            $("#pesan").html(data);
                        }
                    });
                }));
            });
        </script>