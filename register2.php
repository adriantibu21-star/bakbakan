<?php require_once('config.php'); ?>
<!DOCTYPE html>
<html lang="en">
<?php require_once('inc/header.php') ?>
<style>
   body{
     background-image: url('<?php echo validate_image($_settings->info('cover')) ?>');
     background-size:cover;
     background-repeat:no-repeat;
   }
   .page-header{
      text-shadow: 3px 2px black;
   }
 </style>
<body class="hold-transition login-page">

<?php 
if(isset($_GET['refcode']) &&   !empty($_GET['refcode']) ){
    $qry = $conn->query("SELECT * from `users` where refcode = '{$_GET['refcode']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
    }
}
?>

<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>


<div class="login-box">
  <!-- /.login-logo -->


  <div class="card card-primary bg-success">

    <div class="card-body">
	<div id="msg"></div>
			<form action="" id="manage-user">	

				<input type="hidden" name ="parentid" value="<?php echo isset($id) ? $id : '' ?>">

				<div class="form-group">
					<label for="first">FirstName</label>
					<input type="text" name="firstname" id="firstname" class="form-control" value="" placeholder="Firstname" required>
				</div>

        		<div class="form-group">
					<label for="lastname">LastName</label>
					<input type="text" name="lastname" id="lastname" class="form-control" value="" placeholder="Lastname" required>
				</div>
        
        
				<div class="form-group">
					<label for="name">Mobile Number</label>
					<input type="number" name="middlename" id="middlename" class="form-control" value="" placeholder="Mobile Number" required onKeyPress="if(this.value.length==11) return false;" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="event.target.value = event.target.value.replace(/[^0-9]*/g,'');">
				</div>


				<div class="form-group">
					<label for="username">Username</label>
					<input type="text" name="username" id="username" class="form-control" value="" required placeholder="Username" autocomplete="off">
				</div>
				<div class="form-group">
					<label for="password">Password</label>
					<input type="password" name="password" id="password" class="form-control" value="" placeholder="Password" autocomplete="off" required>
				</div>


			</form>

				<div class="form-group">
					<label for="password">Confirm Password</label>
					<input type="password" name="repassword" id="repassword" class="form-control" value="" placeholder="Confirm Password" autocomplete="off" required>
				</div>

		<div class="row justify-content-between">
          <div class="col">
            <a style="color:white" href="<?php echo base_url.'dashboard/login.php'?>">Login</a>
          </div>
          <!-- /.col -->
          <div class="col text-right">
          <button class="btn btn-dark btn-flat btn-sm" form="manage-user" id="sub">Sign up</button>
        </div>

          <!-- /.col -->
        </div>

		</div>
	</div>
</div>

<script>
    $(document).ready(function(){
		$("#sub").prop('disabled', true);
        $("#repassword").keyup(function(){
             if ($("#password").val() != $("#repassword").val()) {
                 $("#msg").html("Password do not match").css("color","red");
				 $("#sub").prop('disabled', true);
             }else{
                 $("#msg").html("Password matched").css("color","white");
				 $("#sub").prop('disabled', false);
            }
      });
});
</script> 

<script>

	$('#manage-user').submit(function(e){
		e.preventDefault();
		var _this = $(this)
		start_loader()
		$.ajax({
			url:_base_url_+'classes/Users.php?f=register',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp ==1){
					//location.href = './dashboard/login.php';
          		location.reload();
				}else{
					$('#msg').html('<div class="alert alert-danger">Username already exist</div>')
					$("html, body").animate({ scrollTop: 0 }, "fast");
				}
                end_loader()
			}
		})
	})
</script>
</body>
</html>

