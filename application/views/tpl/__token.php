<h1>Generate Token</h1>
<div class="form-signin">
    <?php 
        $error = $this->session->flashdata('token_error'); 
        if(!empty($error)) { ?>
        <div class="bs-callout bs-callout-danger">
            <h4>Error</h4>
            <p><?php echo $error;?></p>
            errorrrrr
          </div>
    <?php } 
        $sukses = $this->session->flashdata('token_sukses'); 
        if(!empty($sukses)) { ?>
        <div class="bs-callout bs-callout-success">
            <h4>Sukses</h4>
            <p><?php echo $sukses;?></p>
          </div>
    <?php } ?>
</div>