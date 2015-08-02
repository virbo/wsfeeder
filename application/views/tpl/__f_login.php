      <!--div class="bs-callout bs-callout-danger">
        <h4>Error</h4>
        <p>Ada error yang harus di koreksi</p>
      </div-->
      <?php
        $attributes = array('class' => 'form-signin');
        echo form_open('ws',$attributes);
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
        $logout = $this->session->flashdata('logout'); 
        if(!empty($logout)) { ?>
        <div class="bs-callout bs-callout-info">
            <h4>Success</h4>
            <p><?php echo $logout;?></p>
          </div>
      <?php } ?>
      

      <!--form class="form-signin"-->
        <h2 class="form-signin-heading">Login Feeder</h2>
        <div class="form-group">
          <label for="inputUsername">Username</label>
          <input type="text" id="inputUsername" name="inputUsername" class="form-control" placeholder="Username Feeder" >
        </div>
        <div class="form-group">
          <label for="inputPassword">Password</label>
          <input type="password" id="inputPassword" name="inputPassword" class="form-control" placeholder="Password Feeder" >
        </div>
        <div class="form-group">
          <label for="db_cek">Database Live?</label><br />
          <input type="checkbox" id="db_cek" name="db_ws" checked class="form-control">
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
      <?php echo form_close();?>