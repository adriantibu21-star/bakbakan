<?php
	require_once '../classes/UserInfo.php';
	require_once '../classes/UserBalance.php';
	
	$userInfo = new UserInfo($conn);
	$userBalance = new UserBalance($conn, $_settings->userdata('id'), $_settings->userdata('type'));

	$playersUnderAgent = $userInfo->getAllPlayersUnderAgent($_settings->userdata('id'), $_settings->userdata('type'));

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
		<li class="breadcrumb-item active" style="font-size: 1rem" aria-current="page">Active Player</li>
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
						<th>Agent </th>
						<th>Points</th>
						<th></th>
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
									<td><?php echo $player['type'] == 2 ? "Agent" : "Not an Agent"; ?></td>
									<td><?php echo $player['amount']; ?></td>
									<td class="text-center">
										<a class="btn btn-danger btn-xs user-load-btn" data-user-id="<?php echo $player['id']?>">Load</a>
										<a class="btn btn-primary btn-xs user-withdraw-load-btn" data-user-id="<?php echo $player['id']?>">Withdraw Load</a>
										<a class="btn btn-success btn-xs user-history-btn" data-user-id="<?php echo $player['id']?>">History</a>

										<a class="btn btn-info btn-xs" href="ColorGameBet.php?un=ZGc4OUEzUXM=&amp;ui=QlZsaVVpRXVzdz09">ColorGame Bet</a>
										<a class="btn btn-warning  btn-xs" href="PlayerSummary?un=ZGc4OUEzUXM=&amp;ui=QlZsaVVpRXVzdz09">Summary</a>
										<button class="deactivate_btn btn btn-dark btn-xs" id="288490">Set as Agent</button>
									</td>  
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
							<th>Agent </th>
							<th>Points</th>
							<th></th>
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
							<h1>Load Points <span class="text-danger">( 0.00 )</span></h1>
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
							<h1>Withdraw Points <span class="text-danger">( 0.00 )</span></h1>
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

<!-- History Modal -->
<div class="modal fade" id="user-history-modal">
	<div class="modal-dialog modal-xl modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header" style="display: block;">

					<div class="row">
						<div class="col-sm-12">
							<h1>Bet History of <span class="text-danger user-history-username"></span></h1>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<ol class="breadcrumb float-sm-left mb-0">
								<li class="breadcrumb-item breadcrumb-item-cashin active"><a href="/Portal/testdashboard">Dashboard</a></li>
								<li class="breadcrumb-item breadcrumb-item-cashin active">Bet History</li>
							</ol>
						</div>
					</div>
			</div>
			<div class="modal-body" style="color:black">

				<div class="row">
					<div class="col-md-12">
						<div class="card card-success">
							<div class="card-header">
								<h3 class="card-title">  
									<span> Current Points of </span>
									<span class="user-history-username"></span>: 
									(<span id="user-history-current-points"></span>)
								</h3>
								<div class="card-tools">
									<button type="button" class="btn btn-lg btn-dark" data-dismiss="modal">BACK</button>
								</div>
							</div>
							<div class="card-body table-responsive p-0">
								<div class="card-body">

									<table class="table table-striped table-head-fixed text-nowrap table-dark" id="user-bet-history">
										<thead class="text-center">
											<tr>
												<th>Username</th>
												<th>Date</th>
												<th>Fight #- Event</th>
												<th>Meron/Pula</th>
												<th>Wala/Asul</th>
												<th>Draw</th>
												<th>Result</th>
												<th>Earned</th>
												<th>Balance</th>
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

		$('.convert_data').click(function(){
			uni_modal("<i class='fa fa-redo'></i> Confirmation",'user/convert_user.php?id='+$(this).attr('data-id'))
    	})
		$('.delete_data').click(function(){
			_conf("Are you sure to delete this User permanently?","delete_user",[$(this).attr('data-id')])
		})
		$('.activate_data').click(function(){
			_conf("Are you sure to tag user as Active?","activate_user",[$(this).attr('data-id')])
		})
		$('.deactivate_data').click(function(){
			_conf("Are you sure to tag user as Inactive?","deactivate_user",[$(this).attr('data-id')])
		})

		$('.view_history').click(function(){
			uni_modal("<i class='fa fa-history'></i> History" ,'user/view_history?id='+$(this).attr('data-id'))
		})

		$('.user-load-btn').click(function(){
			clear_modal_values();
			var userId = $(this).data('user-id');
			console.log("load user",userId);
			$.ajax({
				url: _base_url_ + "classes/UserInfo.php?f=get_user_data",
				method: "POST",
				data: { user_id: userId },
				dataType: "json",
				success: function(data){
					if(data.status == 'success'){
						set_modal_values(data.content);
					}else{
						alert_toast("An error occurred.",'error');
					}
				},
				error: function(jqXHR, textStatus, errorThrown){
					console.log(errorThrown);
					alert_toast("An error occurred.",'error');
				}
			});
			
			$.ajax({
				url: _base_url_ + "classes/CashinInfo.php?f=get_cashin_history_of_user",
				method: "POST",
				data: { user_id: userId },
				dataType: "json",
				success: function(data){
					if(data.status == 'success'){
						set_cashin_history_table(data.content);
					}else{
						alert_toast("An error occurred.",'error');
					}
				},
				error: function(jqXHR, textStatus, errorThrown){
					console.log(errorThrown);
					alert_toast("An error occurred.",'error');
				}
			});
			$('#cashin-modal').modal('show');
		});

		$('.user-withdraw-load-btn').click(function(){
			clear_modal_values();
			var userId = $(this).data('user-id');
			console.log("withdrawal user",userId);
			$.ajax({
				url: _base_url_ + "classes/UserInfo.php?f=get_user_data",
				method: "POST",
				data: { user_id: userId },
				dataType: "json",
				success: function(data){
					if(data.status == 'success'){
						set_cashout_modal_values(data.content);
					}else{
						alert_toast("An error occurred.",'error');
					}
				},
				error: function(jqXHR, textStatus, errorThrown){
					console.log(errorThrown);
					alert_toast("An error occurred.",'error');
				}
			});
			
			$.ajax({
				url: _base_url_ + "classes/CashoutInfo.php?f=get_cashout_history_of_user",
				method: "POST",
				data: { user_id: userId },
				dataType: "json",
				success: function(data){
					if(data.status == 'success'){
						set_cashout_history_table(data.content);
					}else{
						alert_toast("An error occurred.",'error');
					}
				},
				error: function(jqXHR, textStatus, errorThrown){
					console.log(errorThrown);
					alert_toast("An error occurred.",'error');
				}
			});
			$('#cashout-modal').modal('show');
		});

		$('.user-history-btn').click(function(){
			var userId = $(this).data('user-id');
			var beginningBalanceData;
			var userName;

			console.log("user-history-btn",userId);
			$.ajax({
				url: _base_url_ + "classes/UserInfo.php?f=get_user_data",
				method: "POST",
				data: { user_id: userId },
				dataType: "json",
				success: function(data){
					if(data.status == 'success'){
						set_history_modal_values(data.content);
						userName = data.content.username;
					}else{
						alert_toast("An error occurred.",'error');
					}
				},
				error: function(jqXHR, textStatus, errorThrown){
					console.log(errorThrown);
					alert_toast("An error occurred.",'error');
				}
			});
			
			$.ajax({
				url: _base_url_ + "classes/BetInfo.php?f=get_initial_balance_of_user",
				method: "POST",
				data: { user_id: userId },
				dataType: "json",
				success: function(data){
					if(data.status == 'success'){
						beginningBalanceData = data.content;

						$.ajax({
							url: _base_url_ + "classes/BetInfo.php?f=get_bet_history_of_user",
							method: "POST",
							data: { user_id: userId },
							dataType: "json",
							success: function(data){
								if(data.status == 'success'){
									console.log("bet history",data.content);
									set_bet_history_table(data.content,beginningBalanceData,userName);
								}else{
									alert_toast("An error occurred.",'error');
								}
							},
							error: function(jqXHR, textStatus, errorThrown){
								console.log(errorThrown);
								alert_toast("An error occurred.",'error');
							}
						});

					}else{
						alert_toast("An error occurred.",'error');
					}
				},
				error: function(jqXHR, textStatus, errorThrown){
					console.log(errorThrown);
					alert_toast("An error occurred.",'error');
				}
			});
			
			$('#user-history-modal').modal('show');
		});

		$('#loading-form').submit(function(e){
		//check if encoded value is numeric
			if (!$.isNumeric($('#amount').val())) {
				alert_toast("Invalid Amount",'error');
				end_loader();
				return false; 
			}
			e.preventDefault();
			var _this = $(this)
			var _this = $(this)
			$('.err-msg').remove();               
			start_loader();
			$.ajax({
				url:_base_url_+"classes/Master.php?f=save_loading1",
				data: new FormData($(this)[0]),
				cache: false,
				contentType: false,
				processData: false,
				method: 'POST',
				type: 'POST',
				dataType: 'json',
				error:err=>{
				console.log(err)
				alert_toast("An error occured",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp =='object' && resp.status == 'success'){
						location.href = "./?page=user/list_players"; 
				}else if(resp.status == 'failed' && !!resp.msg){
					var el = $('<div>')
						el.addClass("alert alert-danger err-msg").text(resp.msg)
						_this.prepend(el)
						el.show('slow')
						$("html, body").animate({ scrollTop: 0 }, "fast");
						end_loader()
				}else{
					alert_toast("An error occured",'error');
					end_loader();
					console.log(resp)
					
				}
			}
			})
		})
	
		$('#withdrawal-form').submit(function(e){
			//check if encoded value is numeric
			if (!$.isNumeric($('#cashout-amount').val())) {
				alert_toast("Invalid Amount",'error');
				end_loader();
			return false; 
			}
			e.preventDefault();
			var _this = $(this)
			var _this = $(this)
			$('.err-msg').remove();               
			start_loader();
			$.ajax({
				url:_base_url_+"classes/Master.php?f=save_withdrawals1",
				data: new FormData($(this)[0]),
				cache: false,
				contentType: false,
				processData: false,
				method: 'POST',
				type: 'POST',
				dataType: 'json',
				error:err=>{
					console.log(err)
					alert_toast("An error occured",'error');
					end_loader();
				},
				success:function(resp){
					if(typeof resp =='object' && resp.status == 'success'){
							location.href = "./?page=user/list_players"; 
					}else if(resp.status == 'failed' && !!resp.msg){
						var el = $('<div>')
							el.addClass("alert alert-danger err-msg").text(resp.msg)
							_this.prepend(el)
							el.show('slow')
							$("html, body").animate({ scrollTop: 0 }, "fast");
							end_loader()
					}else{
						alert_toast("An error occured",'error');
						end_loader();
						console.log(resp)
						
					}
				}
			})
		})

	})

	function clear_modal_values(){
		$('#cashin-user-username').text('');
		$('#cashin-user-balance').text('');
		$('#user_id').val('');
		$('#amount').val('');
		$('#description').val('');

		$('#cashout-user-username').text('');
		$('#cashout-user-balance').text('');
		$('#cashout-user_id').val('');
		$('#cashout-amount').val('');
		$('#cashout-description').val('');
	}
	
	function set_modal_values(data){
		$('#cashin-user-username').text(data.username);
		$('#cashin-user-balance').text(data.amount);
		$('#user_id').val(data.id);
	}

	function set_cashin_history_table(data){
    	$('#cashin-user-history tbody').empty();

		for (var i = 0; i < data.length; i++) {
			var row = '<tr>';
			row += '<td>' + data[i].date_created + '</td>';
			row += '<td>' + data[i].agent_username + '</td>';
			//row += '<td>' + history[i].load_from + '</td>';
			//row += '<td>' + history[i].loaded_type + '</td>';
			row += '<td>' + data[i].user_username + '</td>';
			row += '<td>' + data[i].description + '</td>';
			row += '<td>' + data[i].amount + '</td>';
			row += '<td>' + data[i].user_amount + '</td>';
			row += '</tr>';
			$('#cashin-user-history tbody').append(row);
		}
		
	}

	function set_cashout_modal_values(data){
		$('#cashout-user-username').text(data.username);
		$('#cashout-user-balance').text(data.amount);
		$('#cashout-user_id').val(data.id);
	}

	function set_history_modal_values(data){
		$('.user-history-username').text(data.username);
		$('#user-history-current-points').text(data.amount);
	}

	function set_cashout_history_table(data){
    	$('#cashout-user-history tbody').empty();

		for (var i = 0; i < data.length; i++) {
			var row = '<tr>';
			row += '<td>' + data[i].date_created + '</td>';
			row += '<td>' + data[i].agent_username + '</td>';
			//row += '<td>' + history[i].load_from + '</td>';
			//row += '<td>' + history[i].loaded_type + '</td>';
			row += '<td>' + data[i].user_username + '</td>';
			row += '<td>' + data[i].description + '</td>';
			row += '<td>' + data[i].amount + '</td>';
			row += '<td>' + data[i].user_amount + '</td>';
			row += '</tr>';
			$('#cashout-user-history tbody').append(row);
		}
		
	}

	function set_bet_history_table(data, beginningBalanceData,userName){
    	$('#user-bet-history tbody').empty();
		let bal = 0;
		let beginning_row;

		if (beginningBalanceData && beginningBalanceData.length > 0) {
			bal += Number(beginningBalanceData[0].amount);

			beginning_row = '<tr>';
			beginning_row += '<td class="">'+userName+'</td>';
			beginning_row += '<td><span class="badge">' + beginningBalanceData[0].ending_asof + '</span></td>';
			beginning_row += '<td>' + beginningBalanceData[0].type + '</td>';
			beginning_row += '<td>' + parseFloat(beginningBalanceData[0].red_amount || 0).toFixed(2) + '</td>';
			beginning_row += '<td>' + parseFloat(beginningBalanceData[0].blue_amount || 0).toFixed(2) + '</td>';
			beginning_row += '<td>' + parseFloat(beginningBalanceData[0].yellow_amount || 0).toFixed(2) + '</td>';
			beginning_row += '<td><span class="badge badge-light">N/A</span></td>';
			beginning_row += '<td>' + beginningBalanceData[0].amount + '</td>';
			beginning_row += '<td>' + beginningBalanceData[0].amount + '</td>';
			beginning_row += '</tr>';
		}
		
		for (var i = 0; i < data.length; i++) {
			let typeColumnContent = '';
			let amountDisplay = Number(data[i].amount).toFixed(2);
			const amount = parseFloat(data[i].amount);
			const type = data[i].type;
			
			if (data[i].type === 1) {
				bal += amount;
				console.log("Bal",bal);
				typeColumnContent = 'Cash-In';
			} else if (data[i].type === 2) {
				bal -= amount;
				amountDisplay = '-' + amountDisplay;
				typeColumnContent = 'Cash-Out';
			} else if (data[i].type === 3) {
				bal += amount;
				typeColumnContent = 'Commission';
			} else {
				bal += amount;
				typeColumnContent = data[i].drawno;
			}
			
			let winnerBadge;
			const winner = String(data[i].winner);
			if (winner === '1') {
				winnerBadge = '<span class="badge badge-danger">Meron/Pula</span>';
			} else if (winner === '2') {
				winnerBadge = '<span class="badge badge-primary">Wala/Asul</span>';
			} else if (winner === '3') {
				winnerBadge = '<span class="badge badge-success">Draw</span>';
			} else if (winner === '4') {
				winnerBadge = '<span class="badge badge-light">Cancelled</span>';
			} else {
				winnerBadge = '<span class="badge badge-light">N/A</span>';
			}

			let row = '<tr>';
			row += '<td class="">'+userName+'</td>'; // Index
			row += '<td><span class="badge">' + data[i].date_created + '</span></td>';
			row += '<td>' + typeColumnContent + '</td>'; // Type / Drawno
			
			// Red, Blue, Yellow Amounts (formatted to 2 decimal places)
			row += '<td>' + parseFloat(data[i].red_amount || 0).toFixed(2) + '</td>';
			row += '<td>' + parseFloat(data[i].blue_amount || 0).toFixed(2) + '</td>';
			row += '<td>' + parseFloat(data[i].yellow_amount || 0).toFixed(2) + '</td>';

			row += '<td>' + winnerBadge + '</td>'; // Winner Status
			
			let winnerStatus ='';
			// Type (1: Cash-In, 2: Cash-Out, 3: Commission, 4: Draw)
			if (data[i].type === 4 && data[i].amount < 0 ) {
				winnerStatus = '<span class="badge badge-danger">Lose</span>';
			}else if (data[i].type === 4 && data[i].amount > 0 ) {
				winnerStatus = '<span class="badge badge-success">Win</span>';
			}
			
			// Amount (with negative sign for Cash-Out, formatted to 2 decimal places)
			row += '<td>' + amountDisplay +' ' +  winnerStatus + '</td>';

			// Running Balance (formatted to 2 decimal places)
			row += '<td>' + bal.toFixed(2) + '</td>';

			row += '</tr>';

			$('#user-bet-history tbody').prepend(row);
		}
		$('#user-bet-history tbody').append(beginning_row);
		
	}

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
