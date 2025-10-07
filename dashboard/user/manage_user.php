<?php if($_settings->userdata('type') == 1 or $_settings->userdata('type') == 2): ?>
<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>

<?php 
if(isset($_GET['id']) && $_GET['id'] > 0){
    $user = $conn->query("SELECT * FROM users where id ='{$_GET['id']}'");
    foreach($user->fetch_array() as $k =>$v){
        $meta[$k] = $v;
    }
}
$agent_qry = $conn->query("SELECT id,username FROM users  order by username asc");
$agent_arr = array_column($agent_qry->fetch_all(MYSQLI_ASSOC),'username','id');
?>
<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>



<style>

.content-color {
  background-color: white;
}

label {
   color: black;
}

</style>

<div class="content-color">
	<div class="card-body">

		<div class="container-fluid">
			<div id="msg"></div>
			<form action="" id="manage-user">	
				<input type="hidden" name="id" value="<?php echo isset($meta['id']) ? $meta['id']: '' ?>">

				<?php if($_settings->userdata('type') == 1): ?>            
				<div class="form-group">                  
				<label for="active" class="control-label">Status</label>
				<select name="active" id="active" class="custom-select rounded-0" required>
				<option value="N" <?php echo isset($meta['active']) && $meta['active'] == 'N' ? "selected" : '' ?>>Inactive</option>
				<option value="Y" <?php echo isset($meta['active']) && $meta['active'] == 'Y' ? "selected" : '' ?>>Active</option>
				<option value="F" <?php echo isset($meta['active']) && $meta['active'] == 'F' ? "selected" : '' ?>>For Approval</option>
                </select>              
				</div>
       			<?php endif; ?>
    

				
				<?php if($_settings->userdata('type') == 1): ?>	
				<div class="form-group">
				     <label for="parentid">Agent</label>
				            <select name="parentid" id="parentid" class="form-control select2bs4 select2 rounded-0" data-placeholder="Please Select Agent">
				                    <option value="" disabled <?php echo !isset($meta['parentid']) ? 'selected' : '' ?>></option>
				                    <?php foreach($agent_arr as $k=>$v): ?>
				                    <option value="<?php echo $k ?>" <?php echo (isset($meta['parentid']) && $meta['parentid'] == $k) ? 'selected' : '' ?>><?php echo $v ?></option>
				                    <?php endforeach; ?>
				            </select>
				<?php else: ?>
					<input type="hidden" name ="parentid" value="<?php echo isset($parentid) ? $parentid : $_settings->userdata('id') ?>">
				</div>
				<?php endif; ?>


				

				<div class="form-group">
					<label for="firstname">FirstName</label>
					<input type="text" name="firstname" id="firstname" class="form-control" value="<?php echo isset($meta['firstname']) ? $meta['firstname']: '' ?>" required>
				</div>
				<div class="form-group">
					<label for="lastname">LastName</label>
					<input type="text" name="lastname" id="lastname" class="form-control" value="<?php echo isset($meta['lastname']) ? $meta['lastname']: '' ?>" required>
				</div>
				<div class="form-group">
					<label for="middlename">Mobile Number</label>
					<input type="number" name="middlename" id="middlename" class="form-control" value="<?php echo isset($meta['middlename']) ? $meta['middlename']: '' ?>" required>
				</div>

				<div class="form-group">
					<label for="rate">Rate</label>
					<input type="number" name="rate" id="rate" min="0" max="12" inputmode="numeric" pattern="[0-9]*" step = "0.0001" class="form-control" value="<?php echo isset($meta['rate']) ? $meta['rate']: '' ?>" required>
				</div>


				<div class="form-group">
					<label for="username">Username</label>
					<input type="text" name="username" id="username" class="form-control" value="<?php echo isset($meta['username']) ? $meta['username']: '' ?>" required  autocomplete="off">
				</div>
				<div class="form-group">
					<label for="password">Password</label>
					<input type="password" name="password" id="password" class="form-control" value="" autocomplete="off" <?php echo isset($meta['id']) ? "": 'required' ?>>
                    <?php if(isset($_GET['id'])): ?>
					<small><i>Leave this blank if you dont want to change the password.</i></small>
                    <?php endif; ?>
				</div>
	


				<div class="form-group">
					<label for="type">Login Type</label>
					<select name="type" id="type" class="custom-select">
						<?php if($_settings->userdata('type') == 1): ?>
						<option value="1" <?php echo isset($meta['type']) && $meta['type'] == 1 ? 'selected' : '' ?>>Administrator</option>
						<option value="4" <?php echo isset($meta['type']) && $meta['type'] == 4 ? 'selected' : '' ?>>Declarator</option>
						<?php endif; ?>
						<option value="2" <?php echo isset($meta['type']) && $meta['type'] == 2 ? 'selected' : '' ?>>Agent</option>
						<option value="3" <?php echo isset($meta['type']) && $meta['type'] == 3 ? 'selected' : '' ?>>Player</option>
					</select>
				</div>

				<div class="form-group">
					<label for="role">Role</label>
					<select name="role" id="role" class="custom-select">
						<option value="1" <?php echo isset($meta['role']) && $meta['role'] == 1 ? 'selected' : '' ?>>Financer Account</option>
						<option value="2" <?php echo isset($meta['role']) && $meta['role'] == 2 ? 'selected' : '' ?>>Operator</option>
						<option value="3" <?php echo isset($meta['role']) && $meta['role'] == 3 ? 'selected' : '' ?>>Sub Operator</option>
						<option value="4" <?php echo isset($meta['role']) && $meta['role'] == 4 ? 'selected' : '' ?>>Master Agent</option>
						<option value="5" <?php echo isset($meta['role']) && $meta['role'] == 5 ? 'selected' : '' ?>>Player</option>
					</select>
				</div>

				<div class="form-group">
					<label for="" class="control-label">Image</label>
					<div class="custom-file">
		              <input type="file" class="custom-file-input rounded-circle" id="customFile" name="img" onchange="displayImg(this,$(this))">
		              <label class="custom-file-label" for="customFile">Choose file</label>
		            </div>
				</div>
				<div class="form-group col-6 d-flex justify-content-center">
					<img src="<?php echo validate_image(isset($meta['avatar']) ? $meta['avatar'] :'') ?>" alt="" id="cimg" class="img-fluid img-thumbnail">
				</div>
			</form>
		</div>
	</div>
	<div class="card-footer">
			<div class="col-md-12">
				<div class="row">
					<button class="btn btn-sm btn-primary mr-2" form="manage-user">Save</button>
					<a class="btn btn-sm btn-secondary" href="./?page=user/list">Cancel</a>
				</div>
			</div>
		</div>
</div>
<style>
	img#cimg{
		height: 15vh;
		width: 15vh;
		object-fit: cover;
		border-radius: 100% 100%;
	}
</style>
<script>
	function displayImg(input,_this) {
	    if (input.files && input.files[0]) {
	        var reader = new FileReader();
	        reader.onload = function (e) {
	        	$('#cimg').attr('src', e.target.result);
	        }

	        reader.readAsDataURL(input.files[0]);
	    }
	}
	$('#manage-user').submit(function(e){

        //check if encoded value is numeric
        //if (!$.isNumeric($('#amount').val())) {
          //  alert_toast("Invalid Amount",'error');
		//	end_loader();
          //  return false; 
        //}

	    //check max rate
		if ($("#rate").val() > 12){
            alert_toast("Max rate is 12!",'error');
			end_loader();
            return false; 
        }

        if (!$.isNumeric($('#rate').val())) {
            alert_toast("Invalid Amount",'error');
			end_loader();
            return false; 
        }
		e.preventDefault();
		var _this = $(this)
		start_loader()
		$.ajax({
			url:_base_url_+'classes/Users.php?f=save',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp ==1){
					location.href = './?page=user/list';	
				}else if (resp ==4){
					$('#msg').html('<div class="alert alert-danger"> Invalid rate</div>')
					$("html, body").animate({ scrollTop: 0 }, "fast");					
					
				}else{
					$('#msg').html('<div class="alert alert-danger">Username already exist</div>')
					$("html, body").animate({ scrollTop: 0 }, "fast");
				}
                end_loader()
			}
		})
	})

</script>
<?php endif;?>
