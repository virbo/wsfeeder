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
        <label for="inputWs">Webservice Feeder</label>
        <input type="text" id="inputWs" name="inputWs" class="form-control" placeholder="http://" autofocus>
        <label for="inputUsername">Username Feeder</label>
        <input type="text" id="inputUsername" name="inputUsername" class="form-control" placeholder="Username Feeder" >
        <label for="inputPassword">Password Feeder</label>
        <input type="password" id="inputPassword" name="inputPassword" class="form-control" placeholder="Password Feeder" >
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
      <?php echo form_close();?>