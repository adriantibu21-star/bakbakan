<?php
// header("Location: luckybet777.live");
// die;
require_once('config.php'); ?>
<!DOCTYPE html>
<html lang="en">
<?php require_once('inc/header.php') ?>
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

<body class="hold-transition login-page">

	<?php
	if (isset($_GET['refcode']) &&   !empty($_GET['refcode'])) {
		$qry = $conn->query("SELECT * from `users` where refcode = '{$_GET['refcode']}' ");
		if ($qry->num_rows > 0) {
			foreach ($qry->fetch_assoc() as $k => $v) {
				$$k = $v;
			}
		}
	}
	?>

	<?php if ($_settings->chk_flashdata('success')) : ?>
		<script>
			alert_toast("<?php echo $_settings->flashdata('success') ?>", 'success')
		</script>
	<?php endif; ?>


	<div class="container custom-form">

		<div class="row">
			<div class="col-md-6 text-center justify-content-center align-items-center" style="display:flex;">
				<img src="<?php echo validate_image($_settings->info('left_img')) ?>" alt="logo" class="header-mobile__logo-img logo-img  mb-2 w-100">
			</div>
			<div class="col-md-6">
				<div class="card">
					<div class="card__header">
						<h4>REGISTER NEW ACCOUNT</h4>
					</div>
					<div class="card__content pt-1">
						<!-- Login Form -->
						<div id="msg"></div>
						<form id="manage-user" method="POST" action="">
							<input type="hidden" name="parentid" value="<?php echo isset($id) ? $id : '' ?>">

							<div class="form-group">
								<label for="username">Username</label>
								<input type="text" name="username" id="username" class="form-control" value="" required placeholder="Username" autocomplete="off">
							</div>
							<div class="form-group">
								<label for="password">Password</label>
								<input type="password" name="password" id="password" class="form-control" value="" placeholder="Password" autocomplete="off" required>
							</div>

							<div class="form-group pb-2">
								<label for="password">Confirm Password</label>
								<input type="password" name="repassword" id="repassword" class="form-control" value="" placeholder="Confirm Password" autocomplete="off" required>
							</div>

							<div class="form-group">
								<label for="name">Mobile Number</label>
								<input type="number" name="middlename" id="middlename" class="form-control" value="" placeholder="Mobile Number" required onKeyPress="if(this.value.length==11) return false;" onkeydown="if(event.key==='.'){event.preventDefault();}" oninput="event.target.value = event.target.value.replace(/[^0-9]*/g,'');">
							</div>

							<div class="form-group form-group--sm">
								<button type="submit" class="btn btn-primary btn-lg btn-block btn-success">REGISTER</button>
							</div>

							<a href="/" ><div class="form-group">
								<div class="btn btn-primary btn-lg btn-block btn-dark">GO TO LOGIN PAGE</div>
							</div></a>

						</form>

					</div>
				</div>
			</div>
		</div>
	</div>

	<script>
		$(document).ready(function() {
			$("#sub").prop('disabled', true);
			$("#repassword").keyup(function() {
				if ($("#password").val() != $("#repassword").val()) {
					$("#msg").html("Password do not match").css("color", "red");
					$("#sub").prop('disabled', true);
				} else {
					$("#msg").html("Password matched").css("color", "white");
					$("#sub").prop('disabled', false);
				}
			});
		});
	</script>

	<script>
		$('#manage-user').submit(function(e) {
			e.preventDefault();
			var _this = $(this)
			start_loader()
			$.ajax({
				url: _base_url_ + 'classes/Users.php?f=register',
				data: new FormData($(this)[0]),
				cache: false,
				contentType: false,
				processData: false,
				method: 'POST',
				type: 'POST',
				success: function(resp) {
					if (resp == 1) {
						//location.href = './dashboard/login.php';
						location.reload();
					} else {
						$('#msg').html('<div class="alert alert-danger">Username already exist</div>')
						$("html, body").animate({
							scrollTop: 0
						}, "fast");
					}
					end_loader()
				}
			})
		})
	</script>
</body>

</html>