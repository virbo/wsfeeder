<div class="page-header" style="margin-top: 10px;" >
    <div class="row">
        <div class="col-md-4">
            <h4>Setting Ws Feeder</h4>
        </div>
        <div class="col-md-8">
            
        </div>    
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <?php
            $attributes = array('class' => 'form-signin form-inline');
            echo form_open('welcome/setting',$attributes);
        ?>
        <?php if(validation_errors()) {?>
          <div class="bs-callout bs-callout-danger">
            <h4>Error</h4>
            <p><?php echo validation_errors();?></p>
          </div>    
        <?php } ?>
        <?php 
            $error = $this->session->flashdata('error'); 
            if(!empty($error)) { ?>
            <div class="bs-callout bs-callout-danger">
                <h4>Error</h4>
                <p><?php echo $error;?></p>
            </div>
        <?php } ?>
        <?php 
            $sukses = $this->session->flashdata('sukses'); 
            if(!empty($sukses)) { ?>
            <div class="bs-callout bs-callout-success">
                <h4>Sukses</h4>
                <p><?php echo $sukses;?></p>
            </div>
        <?php } ?><br />
        <div class="form-group">
            <label for="inputNpsn">Kode PT</label>&nbsp;
            <input type="text" id="inputNpsn" name="inputNpsn" class="form-control" value="<?php echo $npsn;?>" autofocus>
        </div>
        <button type="submit" class="btn btn-primary btn-lg">Simpan</button>
        <?php echo form_close();?>
    </div>
</div>