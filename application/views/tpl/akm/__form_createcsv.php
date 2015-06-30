      <?php
        $attributes = array('class' => 'form-signin','enctype' => 'multipart/form-data', 'id' => 'myFRM');
        echo form_open('ws_akm/createcsv',$attributes);
      ?>
      <div id="pesan"></div>
      <span id="loading"></span>
      <h2 class="form-signin-heading">Generate AKM CSV</h2>
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
                        url: "<?php echo base_url().'index.php/ws_akm/createcsv' ?>",
                        type: "POST",
                        data: new FormData(this),
                        mimeType:"multipart/form-data",
                        contentType: false,
                        cache: false,
                        processData:false,
                        beforeSend:function()
                        {
                            $("#pesan").hide();
                            $("#loading").html('Generate files is processing. Please wait...');
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