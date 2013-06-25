<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Login</title>
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
          <a class="brand" href="#">Login</a>
        </div>
      </div>
    </div>
    <div class="container">
      <div id="msgs"></div>

      <form id="login" class="form-signin">
        <div id="errors"></div>
        <?=validation_errors()?>
        <h2 class="form-signin-heading">Please sign in</h2>
        <input type="text" class="input-block-level" placeholder="username" id="username">
        <input type="password" class="input-block-level" id="password" placeholder="password">
        <button class="btn btn-large btn-primary" type="submit">Sign in</button>
      </form>

    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
    <script>
      $(document).ready(function() {
        $("#login").on("submit",function() {
            var user = $("#username").val();
            var pass = $("#password").val();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>site/login_validate",
                data: {"username": user, "password": pass},
                cache: false,
                success: function(output) {
                    //  alert(output.success);
                    if(output=='valid') {
                      $("#msgs").html('<div class="alert alert-success"><a class="close" data-dismiss="alert">×</a>Successly logged in. Redirecting...</div>');
                      window.location = '<?php echo base_url(); ?>site/main';

                    } else {
                      $("#msgs").html('<div class="alert alert-warning"><a class="close" data-dismiss="alert">×</a>Incorrect Information Submitted.'+output+'</div>');
                    }
                }
                })
            return false;  // To prevent form submission
            })
    });
    </script>
  </body>
</html>
