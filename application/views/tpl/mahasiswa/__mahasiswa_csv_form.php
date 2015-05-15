<div class="row">
    <div class="col-md-12">
        <h2><a href="<?php echo base_url();?>index.php/ws_mahasiswa">Kembali</a></h2>
    </div>
</div>
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
      <?php } ?>
      <?php
        $attributes = array('class' => 'form-signin','enctype' => 'multipart/form-data');
        echo form_open('ws_mahasiswa/extractCsv',$attributes);
      ?>
      <h2 class="form-signin-heading">Upload Data CSV</h2>
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
            <label for="exampleInputFile">File input</label>
            <input type="file" id="exampleInputFile" name="userfile" >
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit" id="btn_upload" class="btn btn-default">Upload</button>
      <?php echo form_close();?>
<!--form action="extractCsv" method="post"  enctype="multipart/form-data">
  <div class="form-group">
    <label for="exampleInputFile">File input</label>
    <input type="file" id="exampleInputFile" name="userfile" >
  </div>
  <button type="submit" id="btn_upload" class="btn btn-default">Submit</button>
</form-->