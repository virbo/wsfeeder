      
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
                            $("#loading").html('Extract file...Please wait...');
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