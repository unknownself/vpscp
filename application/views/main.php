<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Control Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <style>
    .form-signin {
        max-width: 300px;
        padding: 19px 29px 29px;
        margin: 0 auto 20px;
        background-color: #fff;
        border: 1px solid #e5e5e5;
        -webkit-border-radius: 5px;
           -moz-border-radius: 5px;
                border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                box-shadow: 0 1px 2px rgba(0,0,0,.05);
      }
      .form-signin .form-signin-heading,
      .form-signin .checkbox {
        margin-bottom: 10px;
      }
      .form-signin input[type="text"],
      .form-signin input[type="password"] {
        font-size: 16px;
        height: auto;
        margin-bottom: 15px;
        padding: 7px 9px;
      }
      </style>
    <!-- Le styles -->
    <link href="<?=base_url()?>templates/css/bootstrap.css" rel="stylesheet">
    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
    </style>
    <link href="<?=base_url()?>templates/css/bootstrap-responsive.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="../assets/js/html5shiv.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a id="brand-head" class="brand" href="#">Control Panel</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li><a href="<?=base_url()?>">Virtual Servers</a></li>
              <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Account</a>
                <ul class="dropdown-menu">
                  <li><a href="#" onclick="openpage('settings')">Account Infomation</a></li>
                </ul></li>
            </ul>
            <div class="navbar-form pull-right">
              <ul class="nav">
                <li><a href="#" onclick="openpage('settings')"><?=$this->session->userdata('user')?></a></li>
                <li><button onclick="logout()" class="btn btn-danger">Logout</button></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="container">
      <div id="msgs"></div>
      <div id="main">
        <!-- table for virtual servers -->
        <?php echo $this->Vpscp_mod_vlist->show_vlist(); ?>
      </div>
    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
    <script src="<?=base_url()?>templates/js/bootstrap.js"></script>
    <script>
      function clicked_manage(id) {
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>site/vserver_validate",
                data: {"vserverid": id},
                cache: false,
                success: function(output) {
                    //  alert(output.success);
                    if(output!='error') {
                      $("#main").html('<center><img src="<?=base_url()?>templates/img/ajax-loader.gif" border="0"></center>');
                      setTimeout(function(){$("#msgs").html('<div class="alert alert-success"><a class="close" data-dismiss="alert">×</a>VServer Loaded</div>');
                      $("#main").html(output);},1000);
                    } else {
                      $("#msgs").html('<div class="alert alert-warning"><a class="close" data-dismiss="alert">×</a>You do not own this virtual server.</div>');
                      console.error('ERR: '+id+' -> '+output);
                    }
                }
                })
      }
      function security_error() {
        $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>site/security_error",
                data: {"cmd": "show"},
                cache: false,
                success: function(output) {
                    //  alert(output.success);
                    if(output!='error') {
                      $("#main").html('<center><img src="<?=base_url()?>templates/img/ajax-loader.gif" border="0"></center>');
                      setTimeout(function(){$("#main").html(output);},1000);
                    } else {
                      $("#msgs").html('<div class="alert alert-warning"><a class="close" data-dismiss="alert">×</a>You do not own this virtual server.</div>');
                      console.error('ERR: '+id+' -> '+output);
                    }
                }
                })
      }
      function openpage(name) {
        $.ajax({
          type: "POST",
          url: "<?=base_url()?>site/pages",
          data: {"page": name},
          cache: false,
          success: function(output) {
            $("#main").html('<center><img src="<?=base_url()?>templates/img/ajax-loader.gif" border="0"></center>');
            setTimeout(function(){$("#main").html(output);},1500)
          }
        })
      }
      function logout() {
               $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>site/logout",
                data: {"logout": "yes"},
                cache: false,
                success: function(output) {
                    //  alert(output.success);
                    if(output!='error') {
                      $("#msgs").html('<div class="alert alert-success"><a class="close" data-dismiss="alert">×</a>Logged out.</div>');
                      $("#main").html("You have been successfully logged out.");
                      window.location="<?=base_url()?>site/login";
                    } else {
                      $("#msgs").html('<div class="alert alert-warning"><a class="close" data-dismiss="alert">×</a>Could not log out.</div>');
                      console.error('ERR: '+id+' -> '+output);
                    }
                }
                })
      }
      <?php 
        $this->Vpscp_mod_vlist->check_level();
      ?>
    </script>
  </body>
</html>
