<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset ($site_title)?$site_title.' | '.$this->config->item('site_title'):$this->config->item('site_title'); ?></title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="<?php echo $this->config->item('meta_desc');?>" name="description" />
    <meta content="<?php echo $this->config->item('meta_key');?>" name="keywords" />
    <meta content="<?php echo $this->config->item('meta_author');?>" name="author" />

    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url();?>assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo base_url();?>assets/css/template.css" rel="stylesheet">
    <link href="<?php echo base_url();?>assets/font-awesome/css/font-awesome.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <!-- Fixed navbar -->
    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?php echo base_url();?>">WS Client</a>
        </div>
    
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <?php
                if ($this->session->userdata('login')) {
                    echo "<ul class=\"nav navbar-nav\">
                                <li class=\"dropdown\">
                                  <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-expanded=\"false\"><span class=\"glyphicon glyphicon-file\" aria-hidden=\"true\"></span> Import Data <span class=\"caret\"></span></a>
                                  <ul class=\"dropdown-menu\" role=\"menu\">
                                    <!--li><a href=\"".base_url()."index.php/ws_agama\">Agama</a></li>
                                    <li><a href=\"".base_url()."index.php/ws_bobot\">Bobot Nilai</a></li-->
                                    <li><a href=\"".base_url()."index.php/ws_mahasiswa\">Mahasiswa</a></li>
                                    <li><a href=\"".base_url()."index.php/ws_nilai\">Nilai Semester Mahasiswa</a></li>
                                    <li><a href=\"".base_url()."index.php/ws_akm\">Aktivitas Kuliah Mahasiswa</a></li>
                                    <li><a href=\"".base_url()."index.php/#\">Aktivitas Mengajar Dosen</a></li>
                                    <li><a href=\"".base_url()."index.php/ws_npindahan\">Nilai Mahasiswa Pindahan</a></li>
                                    <li><a href=\"".base_url()."index.php/ws_wilayah\">Wilayah</a></li>
                                    <!--li class=\"divider\"></li>
                                    <li><a href=\"#\">Separated link</a></li>
                                    <li class=\"divider\"></li>
                                    <li><a href=\"#\">One more separated link</a></li-->
                                  </ul>
                                </li>
                                <li><a href=\"".base_url()."index.php/welcome/listtable\">List Tabel</a></li>
                                <!--li><a href=\"#\">Tabel Data</a></li-->
                      </ul>";
                }
            ?>
          
          <ul class="nav navbar-nav navbar-right">
            <?php
                if ($this->session->userdata('login')) {
                    echo "<!--li><a href=\"".base_url()."index.php/welcome/token\">Generate Token</a></li-->
                          <li class=\"dropdown active\">
                              <!--a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-expanded=\"false\">
                                    ".$this->session->userdata('username')." on ".$this->session->userdata('ws')." <span class=\"caret\"></span>
                              </a-->
                              <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-expanded=\"false\">
                                    ".$this->session->userdata('username')." <span class=\"caret\"></span>
                              </a>
                              <ul class=\"dropdown-menu\" role=\"menu\">
                                <li><a href=\"".base_url()."index.php/welcome/token/".$this->uri->segment(1)."-".$this->uri->segment(2)."\">Generate Token</a></li>
                                <li class=\"divider\"></li>
                                <li><a href=\"".base_url()."index.php/welcome/logout\"><span class=\"glyphicon glyphicon-lock\" aria-hidden=\"true\"></span> Logout</a></li>
                              </ul>
                          </li>";
                } else {
                    echo "<li><a href=\"".base_url()."index.php/ws\">Login</a></li>";
                }
            ?>
          </ul>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </nav>

    <div class="container-fluid">
        <?php echo $view; ?>
    </div> <!-- /container -->
    
    <div class="footer navbar-default navbar-fixed-bottom">
      <div class="container-fluid" align="right">
          <?php if ($this->session->userdata('login')) { ?>
          <!--span class="label label-danger">
                  <?php echo $this->session->userdata('username');?> | 
                  <?php echo $this->session->userdata('ws');?> | 
                  <span class="glyphicon glyphicon-lock" aria-hidden="true"></span> <a href="<?php echo base_url();?>index.php/welcome/logout">Logout</a>
          </span-->
          <?php
                $temp_pecah = explode('/ws/', $this->session->userdata('ws'));
                $temp_pecah2 = explode('.php?wsdl', $temp_pecah[1]);
                //echo $temp_pecah[0];
          ?>
          <div class="btn-group btn-group-xs" role="group" aria-label="Switch Database">
              <button type="button" id="btn_live" class="btn <?php echo $temp_pecah2['0']=='live'?"btn-success":"btn-default" ?>"  data-src="live" >Live <?php echo $temp_pecah2['0']=='live'?"(ON)":"(OFF)" ?></button>
              <button type="button" id="btn_sandbox" class="btn <?php echo $temp_pecah2['0']=='sandbox'?"btn-danger":"btn-default" ?>"  data-src="sandbox" >Sandbox <?php echo $temp_pecah2['0']=='sandbox'?"(ON)":"(OFF)" ?></button>
          </div>
          <?php }?> 
      </div>
    </div>


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?php echo base_url();?>assets/js/jquery.js"></script>
    <script src="<?php echo base_url();?>assets/js/bootstrap.min.js"></script>
    <script>
      $('#btn_sandbox').on('click', function () {
        var sandbox = $(this).attr('data-src');
        var uri = '<?php echo $this->uri->segment(1); ?>-<?php echo $this->uri->segment(2); ?>';
        var url = '<?php echo base_url().'index.php/welcome/set_koneksi/'; ?>';
        
        var temp_confirm = confirm('Anda yakin ingin mengubah jenis koneksi ke Webservice Feeder');
        if (temp_confirm==true) {
            $(location).attr('href',url+sandbox+'/'+uri);
        };
      })
      
      $('#btn_live').on('click', function () {
        var live = $(this).attr('data-src');
        var uri = '<?php echo $this->uri->segment(1); ?>-<?php echo $this->uri->segment(2); ?>';
        var url = '<?php echo base_url().'index.php/welcome/set_koneksi/'; ?>';
        var temp_confirm = confirm('Anda yakin ingin mengubah jenis koneksi ke Webservice Feeder');
        if (temp_confirm==true) {
            $(location).attr('href',url+live+'/'+uri);
        };
      })
    </script>
    <script>
        $(document).ready(function(){
          $('a.modalButton').click(function(){
            var src = $(this).attr('data-src');
            
            $('#isi').html('Loading, please wait...');
            $('#isi').load(src);
          });
        });
    </script>
    <script>
        $('#myTabs a').click(function (e) {
          e.preventDefault()
          //$(this).tab('show')
          var url = $(this).attr("data-src");
          var href = this.hash;
          var pane = $(this);
          
          $(href).load(url,function(result) {
          //$('#isitab').load(url,function(result) {
            pane.tab('show');
          });
        });
        // load first tab content
        $('#tab-1').load($('.active a').attr("data-src"),function(result){
          $('.active a').tab('show');
        });

    </script>
    <div class="modal fade modal-wide" id="modalku" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h3 class="modal-title" id="myLargeModalLabel">View</h3>
                </div>
                <div class="modal-body">
                    <!--iframe frameborder="0"></iframe-->
                    <div id="isi"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
  </body>
</html>
