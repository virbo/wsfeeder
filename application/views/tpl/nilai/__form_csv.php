
<form method="post" accept-charset="utf-8" id="myFRM" class="form-signin frmku" enctype="multipart/form-data">
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
<br />
<!--div id="progressbox"><div id="progressbar"></div ><div id="statustxt">0%</div ></div-->

<br />

<div class="bs-callout bs-callout-info">
    Perhatian:<br />Data lama akan diupdate dengan data yang baru.<br />Pastikan data yang anda upload adalah benar
</div>
<?php //echo form_close();?>
<script>
    $(document).ready(function (e) {
        $("#myFRM").on('submit',(function(e) {
            e.preventDefault();
            $.ajax({
                url: "<?php echo base_url().'index.php/ws_nilai/extractcsv/' ?>",
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