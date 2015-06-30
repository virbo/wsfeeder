
<form method="post" action="extractcsv" accept-charset="utf-8" id="myFRM" class="form-signin frmku" enctype="multipart/form-data">
<div id="pesan"></div>
<span id="loading"></span>    
<!--input type="hidden" name="id_kls" class="id_kls" value="<?php echo $id_kls; ?>" /-->
<h2 class="form-signin-heading">Upload Data CSV</h2>
<div class="form-group">
    <label for="fileupload">File input</label>
    <!--input type="file" id="upload_file" name="userfile" class="userfile" -->
    <input type="file" name="userfile" id="fileupload" class="form-control" >
</div>
<div class="form-group">
    <label for="fileinput">Columns separated with:</label>
    <select class="form-control" name="separasi" id="separasi">
        <option value="," selected>Separation with coma (,)</option>
        <option value=";">Separation with semicolon (;)</option>
    </select>
</div>
<button class="btn btn-lg btn-primary btn-block" type="submit" id="submit" class="btn btn-default">Upload</button>
<?php //echo form_close();?>
<script>
    $(document).ready(function (e) {
        //var progressbox     = $('#progressbox');
        //var progressbar     = $('#progressbar');
        //var statustxt       = $('#statustxt');
        //var submitbutton    = $("#submit");
        //var myform          = $("#myFRM");
        //var output          = $("#output");
        //var completed       = '0%';
        
        $("#myFRM").on('submit',(function(e) {
            e.preventDefault();
            $.ajax({
                url: "<?php echo base_url().'index.php/ws_akm/extractcsv' ?>",
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
</form>