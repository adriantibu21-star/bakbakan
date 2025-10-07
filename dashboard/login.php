<?php require_once('../config.php') ?>
<!DOCTYPE html>
<html lang="en" class="" style="height: auto;">
<?php
require_once('inc/header.php')
?>
<style>
  body {
    background-image: url('<?php echo validate_image($_settings->info('cover')) ?>');
    background-size: cover;
    background-repeat: no-repeat;
  }

  .page-header {
    text-shadow: 3px 2px black;
  }
</style>

<body class="hold-transition login-page ">
  <script>
    start_loader()
  </script>
  <div class="container custom-form">
    <div class="row">
      <div class="col-md-6 text-center justify-content-center align-items-center" style="display:flex;">
      <?php if(!empty($_settings->info('left_img'))){ ?>
        <img src="<?php echo  validate_image($_settings->info('left_img')) ?>" alt="logo" class="header-mobile__logo-img logo-img  mb-2 w-100">
      <?php } ?>
      </div>
      <div class="col-md-6">
        <div class="card">
          <div class="card__header">
            <h4>Login to your Account</h4>
          </div>
          <div class="card__content">
            <!-- Login Form -->
            <form id="login-frm"  method="POST" action="">
              <div class="form-group">
                <label for="login-name">Your Username</label>
                <input type="text" name="username" id="username" class="form-control" placeholder="Enter your username...">
              </div>
              <div class="form-group pb-2">
                <label for="login-password">Your Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password...">
              </div>
              <div class="form-group form-group--sm">
                <button type="submit" class="btn btn-primary btn-lg btn-block btn-dark">Sign in to your account</button>
              </div>
            </form>
            <div class="form-group form-group--password-forgot mb-0">
              <span class="password-reminder"><a href="/register.php" >Register an account</a></span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- jQuery -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.min.js"></script>

  <script>
    $(".toggle-password").click(function() {
      $(this).toggleClass("fa-eye fa-eye-slash");
      var input = $($(this).attr("toggle"));
      if (input.attr("type") == "password") {
        input.attr("type", "text");
      } else {
        input.attr("type", "password");
      }
    });
  </script>

  <script>
    $(document).ready(function() {
      end_loader();
    })
  </script>
</body>

</html>