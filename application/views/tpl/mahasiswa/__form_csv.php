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
      <h2 class="form-signin-heading">Upload Data CSV</h2>
        <!--div class="form-group">
            <label for="prodi">Program Studi</label>
            <select class="form-control" id="prodi" name="prodi">
            <?php
                foreach ($prodi as $key => $value) {
                    //echo $value['nm_lemb'];
                    echo "<option value=\"".$value['id_sms']."\">".$value['nm_lemb']."</option>";
                }
            ?>
            </select>
        </div-->
        <div class="form-group">
            <label for="fileinput">File input</label>
            <input type="file" id="fileinput" name="userfile" class="form-control" >
        </div>
        <div class="form-group">
            <label for="fileinput">Columns separated with:</label>
            <select class="form-control" name="separasi" id="separasi">
                <option value="," selected>Separation with coma (,)</option>
                <option value=";">Separation with semicolon (;)</option>
                <!--option value="2">Separation with dot (.)</option-->
            </select>
        </div>
        <!--div class="form-group">
            <input type="radio" name="stat_pd" value="0" checked> Mahasiswa Baru&nbsp; 
            <input type="radio" name="stat_pd" value="1"> Mahasiswa Pindahan
        </div-->
        <button class="btn btn-lg btn-primary btn-block" type="submit" id="btn_upload" class="btn btn-default">Upload</button>
        <script>
            $(document).ready(function () {
                $("#myFRM").on('submit',(function(e) {
                    e.preventDefault();
                    $.ajax({
                        url: "<?php echo base_url().'index.php/ws_mahasiswa/dump_csv/' ?>",
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
                       /*beforeSend: function() { //brfore sending form
                               // submitbutton.attr('disabled', ''); // disable upload button
                                statustxt.empty();
                                progressbox.slideDown(); //show progressbar
                                progressbar.width(completed); //initial value 0% of progressbar
                                statustxt.html(completed); //set status text
                                statustxt.css('color','#000'); //initial color of status text
                            },
                        
                        uploadProgress: function(event, position, total, percentComplete) { //on progress
                                progressbar.width(percentComplete + '%') //update progressbar percent complete
                                statustxt.html(percentComplete + '%'); //update status text
                                if(percentComplete>50)
                                    {
                                        statustxt.css('color','#fff'); //change status text to white after 50%
                                    }
                                },*/
                        complete:function()
                        {
                            //$("#loading").hide();
                            $("#loading").empty();
                            $("#pesan").show();
                        },
                       /*complete: function(response) { // on complete
                                //output.html(response.responseText); //update element with received data
                                //myform.resetForm();  // reset form
                                //submitbutton.removeAttr('disabled'); //enable submit button
                                progressbox.slideUp(); // hide progressbar
                        },*/
                        error: function()
                        {
                            $('#pesan').html('Error, unknown');
                        },
                        success: function(data)
                        {
                            $("#pesan").html(data);
                        }
                    });
                }));
            });
        </script>
      <?php echo form_close();?>