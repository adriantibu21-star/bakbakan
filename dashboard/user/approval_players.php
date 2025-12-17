<?php
	require_once '../classes/UserInfo.php';
	require_once '../classes/UserBalance.php';
	
	$userInfo = new UserInfo($conn);
	$userBalance = new UserBalance($conn, $_settings->userdata('id'), $_settings->userdata('type'));

	$playersUnderAgent = $userInfo->getApprovalPlayersUnderAgent($_settings->userdata('id'), $_settings->userdata('type'));

    $curbal = $userBalance->getUserBalance($_settings->userdata('id'));
?>

<?php if($_settings->userdata('type') == 1 or $_settings->userdata('type') == 2): ?>
<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>

<style>
	.breadcrumb-item.active {
		color: #fff;
	}
	.breadcrumb-item a {
		color: #ffec00;
	}
	.breadcrumb-item li {
		font-size: 1rem !important;
	}
	.breadcrumb-item-cashin.active {
		color: #6c757d;
	}
	.breadcrumb-item-cashin a {
		color: #007bff;
	}
	.breadcrumb-item-cashin li {
		font-size: 1rem !important;
	}
	
	.dropbtn {
		background-color: #4CAF50;
		color: white;
		padding: 16px;
		font-size: 16px;
		border: none;
		cursor: pointer;
	}
	.content-color {
		background-color: black;
	}
	table.dataTable th, table.dataTable td {
		padding: 0.75rem !important;
	}

	

</style>

<nav aria-label="breadcrumb">
	<ol class="breadcrumb my-0 pb-3" style="background-color: transparent;">
		<li class="breadcrumb-item" style="font-size: 1rem"><a href="#">Home</a></li>
		<li class="breadcrumb-item active" style="font-size: 1rem" aria-current="page">Approval Players</li>
	</ol>
</nav>

<div class="content-wrapper" style="background-color: #f4f6f9 !important;">
	<div class="card card-v2" style="background-color: #fff !important; margin-left: 2%; margin-right: 2%">
		<div class="card-body row">
			<div class="col-md-12">
				<form action="" method="POST">                
					<div class="input-group input-group-lg">
						<input type="text" name="search" class="form-control" id="searchUsername" placeholder="Search Username">
						<span class="input-group-append">
							<button type="submit" class="btn btn-info btn-sm ">
								<i class="fa fa-search"></i>
							</button>
						</span>
					</div>
				</form>        
			</div>
		</div>
	</div>

	<div class="card card-v2" style="margin-left: 2%; margin-right: 2%; color: #212529 !important;">
		<div class="card-header" style="background-color: #EFF3F6">
			<h3 class="card-title"><i class="fas fa-align-justify"></i>   List of PLAYERS </h3>

		</div>
		<div class="card-body table-responsive" style="padding: 0px;">
			<table id="example" class="table table-bordered table-striped">
				<thead>
					<tr>
						<th>Username</th>
						<th>Active</th>
						<th>Points</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php
						if (!empty($playersUnderAgent)) {
							foreach ($playersUnderAgent as $player) {
								?>
								<tr>
									<td class="text-bold"><?php echo $player['username']; ?></td>
									<td>
										<?php
											if ($player['active'] == 'Y') {
												echo 'Active';
											} elseif ($player['active'] == 'N') {
												echo 'Inactive';
											} else {
												echo 'For Approval';
											}
										?>
									</td>
									<td><?php echo $player['amount']; ?></td>

									<?php if($_settings->userdata('type') == 1 || $_settings->userdata('type') == 2): ?>
										<td class="text-center">
											<!-- <a class="btn btn-info btn-xs mb-1" href="?page=user/manage_user&id=<?php echo $player['id'] ?>">Edit</a> -->
											<?php if($player['active'] == 'N' or $player['active'] == 'F'): ?>
												<a class="btn btn-success btn-xs mb-1 activate_data" href="javascript:void(0)" data-id="<?php echo $player['id'] ?>">Activate</a>
											<?php endif; ?> 
											<?php if($player['active'] == 'Y'): ?>
												<a class="btn btn-warning btn-xs mb-1 deactivate_data" href="javascript:void(0)" data-id="<?php echo $player['id'] ?>">Deactivate</a>
											<?php endif; ?> 

											<a class="btn btn-danger btn-xs mb-1 delete_data" href="javascript:void(0)" data-id="<?php echo $player['id'] ?>">Delete</a>
										</td>  
									<?php endif; ?>
								</tr>
								<?php
							}
						}
					?>  
				</tbody>
					<tfoot>
						<tr>
							<th>Username</th>
							<th>Active</th>
							<th>Points</th>
							<th>Actions</th>
						</tr>
					</tfoot>
			</table>
		</div>
	</div>
</div>

<!-- Cashin Modal -->
<div class="modal fade" id="cashin-modal">
	<div class="modal-dialog modal-lg modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header" style="display: block;">

					<div class="row">
						<div class="col-sm-12">
							<h1>Load Points <span class="text-danger" >(<span class="logged-in-agent-balance"></span>)</span></h1>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<ol class="breadcrumb float-sm-left mb-0">
								<li class="breadcrumb-item breadcrumb-item-cashin active"><a href="/Portal/testdashboard">Dashboard</a></li>
								<li class="breadcrumb-item breadcrumb-item-cashin "><a href="/Portal/testactiveplayer">All Players</a></li>
								<li class="breadcrumb-item breadcrumb-item-cashin active">Load Points</li>
							</ol>
						</div>
					</div>
			</div>
			<div class="modal-body" style="color:black">

				<div class="row">
					<div class="col-md-12">
						<div class="card card-success">
							<div class="card-header">
								<h3 class="card-title">  <span> Load Points to </span><span id="cashin-user-username"></span></h3>
								<div class="card-tools">
								</div>
							</div>
							<div class="card-body table-responsive p-0">
								<div class="card-body">

									<div class="form-group row">
										<h3 class="text-white">Current POINTS: <span class="text-danger" id="cashin-user-balance"></span></h3>
									</div>

									<form action="" id="loading-form">
										<input type="hidden" name ="id" value="">  
										<input type="hidden" name ="agent_id" value="<?php echo $_settings->userdata('id') ?>">  
										<input type="hidden" name ="agent_code" value="<?php echo $curbal['password'] ?>"> 
										<input type="hidden" name ="date_created" value="<?php echo date("Y-m-d H:i") ?>">
        								<input type="hidden" name ="user_id" id="user_id" ?>

										<div class="form-group row ">
											<label class="col-sm-2 col-form-label" for="UserPoint_Points">Points</label>
											<div class="col-sm-10">
                 								<input name="amount" id="amount" type="number" inputmode="numeric" pattern="[0-9]*" step="0.01" class="form-control form  rounded-0" value= <?php echo isset($amount) ? $amount : ''; ?> >
											</div>
										</div>

										<div class="form-group row">
											<label class="col-sm-2 col-form-label" for="UserPoint_Notes">Details
											</label>
											<div class="col-sm-10">
												<textarea name="description" id="description" cols="30" rows="2" style="resize:none !important" class="form-control form no-resize rounded-0" required><?php echo isset($description) ? $description : ''; ?></textarea>
											</div>

										</div>
										<div class="form-actions form-group row float-right ">
											<div class="input-group-append ">
												<button type="submit" value="submit" class="btn btn-lg btn-success" form="loading-form" style="background-color:red;"> SUBMIT </button>
												<button type="button" class="btn btn-lg btn-dark" data-dismiss="modal">BACK</button>
											</div>
										</div>
									</form>

									<table class="table table-striped table-head-fixed text-nowrap table-dark" id="cashin-user-history">
										<thead class="text-center">
											<tr>
												<th>Date Loaded</th>
												<th>Loaded By</th>
												<!-- <th>Load From</th> -->
												<!-- <th>Loaded type</th> -->
												<th>Load To</th>
												<th>Notes</th>
												<th>Points</th>
												<th>Current Balance</th>
											</tr>
										</thead>
										<tbody>

										</tbody>
									</table>
								</div>
							</div>
                            <!-- /.card-body -->
							<div class="card-footer text-right ">
		
							</div> 
                            <!-- /.card-footer -->

                        </div>
					</div>

				</div>

			</div>
		</div>
	</div>
</div>

<!-- Cashout Modal -->
<div class="modal fade" id="cashout-modal">
	<div class="modal-dialog modal-lg modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header" style="display: block;">

					<div class="row">
						<div class="col-sm-12">
							<h1>Withdraw Points <span class="text-danger" >(<span class="logged-in-agent-balance"></span>)</span></h1>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<ol class="breadcrumb float-sm-left mb-0">
								<li class="breadcrumb-item breadcrumb-item-cashin active"><a href="/Portal/testdashboard">Dashboard</a></li>
								<li class="breadcrumb-item breadcrumb-item-cashin "><a href="/Portal/testactiveplayer">All Players</a></li>
								<li class="breadcrumb-item breadcrumb-item-cashin active">Withdraw Points</li>
							</ol>
						</div>
					</div>
			</div>
			<div class="modal-body" style="color:black">

				<div class="row">
					<div class="col-md-12">
						<div class="card card-success">
							<div class="card-header">
								<h3 class="card-title">  <span> Withdraw Points to </span><span id="cashout-user-username"></span></h3>
								<div class="card-tools">
								</div>
							</div>
							<div class="card-body table-responsive p-0">
								<div class="card-body">

									<div class="form-group row">
										<h3 class="text-white">Current POINTS: <span class="text-danger" id="cashout-user-balance"></span></h3>
									</div>

									<form action="" id="withdrawal-form">
										<input type="hidden" name ="cashout-id" value="">  
										<input type="hidden" name ="cashout-agent_id" value="<?php echo $_settings->userdata('id') ?>">  
										<input type="hidden" name ="cashout-agent_code" value="<?php echo $curbal['password'] ?>"> 
										<input type="hidden" name ="cashout-date_created" value="<?php echo date("Y-m-d H:i") ?>">
        								<input type="hidden" name ="cashout-user_id" id="cashout-user_id" ?>

										<div class="form-group row ">
											<label class="col-sm-2 col-form-label" for="UserPoint_Points">Points</label>
											<div class="col-sm-10">
                 								<input name="cashout-amount" id="cashout-amount" type="number" inputmode="numeric" pattern="[0-9]*" step="0.01" class="form-control form  rounded-0" value= <?php echo isset($amount) ? $amount : ''; ?> >
											</div>
										</div>

										<div class="form-group row">
											<label class="col-sm-2 col-form-label" for="UserPoint_Notes">Details
											</label>
											<div class="col-sm-10">
												<textarea name="cashout-description" id="cashout-description" cols="30" rows="2" style="resize:none !important" class="form-control form no-resize rounded-0" required><?php echo isset($description) ? $description : ''; ?></textarea>
											</div>

										</div>
										<div class="form-actions form-group row float-right ">
											<div class="input-group-append ">
												<button type="submit" value="submit" class="btn btn-lg btn-success" form="withdrawal-form" style="background-color:red;"> SUBMIT </button>
												<button type="button" class="btn btn-lg btn-dark" data-dismiss="modal">BACK</button>
											</div>
										</div>
									</form>

									<table class="table table-striped table-head-fixed text-nowrap table-dark" id="cashout-user-history">
										<thead class="text-center">
											<tr>
												<th>Date Loaded</th>
												<th>Loaded By</th>
												<!-- <th>Load From</th> -->
												<!-- <th>Loaded type</th> -->
												<th>Load To</th>
												<th>Notes</th>
												<th>Points</th>
												<th>Current Balance</th>
											</tr>
										</thead>
										<tbody>

										</tbody>
									</table>
								</div>
							</div>
                            <!-- /.card-body -->
							<div class="card-footer text-right ">
		
							</div> 
                            <!-- /.card-footer -->

                        </div>
					</div>

				</div>

			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function(){

		var userListTable = $('#example').DataTable({
			lengthChange: false,
			dom: 'lrtip',
			stateSave: true
		});
		
		$('#searchUsername').val('');
		userListTable.search('').draw();

        $('#searchUsername').on('keyup', function() {
            var searchValue = $(this).val();
            userListTable.search(searchValue).draw();
        });

		$(document).on('click', '.delete_data', function(){
			_conf("Are you sure to delete this User permanently?","delete_user",[$(this).attr('data-id')])
		})
		$(document).on('click', '.activate_data', function(){
			_conf("Are you sure to tag user as Active?","activate_user",[$(this).attr('data-id')])
		})
		$(document).on('click', '.deactivate_data', function(){
			_conf("Are you sure to tag user as Inactive?","deactivate_user",[$(this).attr('data-id')])
		})

	})

	function delete_user($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Users.php?f=delete",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}

	function activate_user($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Users.php?f=activate",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}

	function deactivate_user($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Users.php?f=deactivate",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}

</script>
<?php endif;?>
