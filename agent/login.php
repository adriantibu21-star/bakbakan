<?php require_once('../config.php') ?>
<!DOCTYPE html>
<html lang="en" class="" style="height: auto;">
 <?php require_once('inc/header.php') ?>
 <style>
   body{
     background-color:gray;

   }
 </style>
<body class="hold-transition login-page ">
  <script>
    start_loader()
  </script>
  <h1 class="text-center pb-4 mb-4 text-dark page-header"><?php echo $_settings->info('name') ?></h1>
<div class="login-box">
  <!-- /.login-logo -->
  <div class="card card-primary">
    <div class="card-body">
      <form id="flogin-frm" action="" method="post">
        <div class="input-group mb-3">
          <input type="text" class="form-control" name="username" placeholder="Username">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input id="password-field" type="password" class="form-control" name="password" placeholder="Password"> 
          <div class="input-group-append">
            <div class="input-group-text">
            <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
            </div>
          </div>
        </div>
        <div class="row justify-content-between">
          <div class="col">
            <a href="<?php echo base_url ?>">Go to Portal</a>
          </div>
          <!-- /.col -->
          <div class="col text-right">
            <button type="submit" class="btn btn-primary btn-flat btn-sm">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
      <!-- /.social-auth-links -->

      <!-- <p class="mb-1">
        <a href="forgot-password.html">I forgot my password</a>
      </p> -->
      
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>
<!-- /.login-box -->

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
  $(document).ready(function(){
    end_loader();
  })
</script>
</body>
</html>