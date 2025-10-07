
<?php if($_settings->userdata('type') == 1 or $_settings->userdata('type') == 2): ?>
<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>

<div class="card card-outline card-primary">
	<div class="card-header">
		<h3 class="card-title">Players</h3>	
	</div>
	<div class="card-body">
		<div class="container-fluid">
        <div class="container-fluid">
			<table class="table table-hover table-stripped" id="example">

				<thead>
					<tr>
						<th>#</th>
						<th>Player</th>
						<th>Agent</th>
						<th>Details</th>
						<th>Type</th>
						<th>Balance</th> 
						<th>Status</th>	
						<th>Action</th>
						
					</tr>
				</thead>
				<tbody>
					<?php 
					$i = 1;
					$agent_qry = $conn->query("SELECT id,username FROM users");
					$agent_arr = array_column($agent_qry->fetch_all(MYSQLI_ASSOC),'username','id');
						$qry = $conn->query("SELECT * from `users`  where type = 3  and active not in ('Y','N') and parentid = '{$_settings->userdata('id')}' order by username asc ");		
						//$qry = $conn->query($mysql);			
						while($row = $qry->fetch_assoc()):
					?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td><?php echo ucwords($row['username']) ?></td>
							<td ><?php echo isset($agent_arr[$row['parentid']]) ? $agent_arr[$row['parentid']] : 'N/A' ?></td>
							<td ><p class="m-0 truncate-1"><?php echo $row['middlename'] ?></p></td>
							<td><?php echo ($row['type'] == 1) ? 'Administrator' : (($row['type'] == 2) ? 'Agent' : 'Player') ?></td>
							<td><?php echo number_format($row['amount'],2) ?></td>
							<td>
								<?php if($row['active'] == 'Y'): ?>
									<span class="badge badge-success">ACTIVE</span>
								<?php elseif($row['active'] == 'N'): ?>
									<span class="badge badge-danger">INACTIVE</span>
								<?php else: ?>
									<span class="badge badge-warning">FOR APPROVAL</span>
								<?php endif; ?>
							</td>
							<td align="center">
								 <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-boundary="viewport" data-toggle="dropdown">
				                  		Action
				                    <span class="sr-only">Toggle Dropdown</span>
				                  </button>


				                  <div class="dropdown-menu" role="menu" style="position:fixed;z-index:10000;">


									<?php if($row['active'] == 'N' or $row['active'] == 'F'): ?>
									<a class="dropdown-item activate_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-check text-success"></span> Activate</a>
									<?php endif; ?> 
									<?php if($row['active'] == 'Y'): ?>
									<a class="dropdown-item deactivate_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-times text-danger"></span> Deactivate</a>
									<div class="dropdown-divider"></div>
									<?php endif; ?> 

									<?php if($_settings->userdata('role') !== 4): ?>
									<?php if($row['active'] == 'Y'): ?>
									<a class="dropdown-item convert_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-redo text-success"></span> Convert as Agent</a>
									<div class="dropdown-divider"></div>
									<?php endif; ?> 
									<?php endif; ?> 



									<a class="dropdown-item fa fa-history" href="?page=user/view_history_player&id=<?php echo $row['id'] ?><span class="fa fa-history text-primary"></span> History</a>

				                  </div>

							</td>
				
	
							


						</tr>
							
					<?php endwhile; ?>

		
				</tbody>

						<tr>
									<td><br><br><td>
						</tr>
			</table>
		</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('.convert_data').click(function(){
			uni_modal("<i class='fa fa-redo'></i> Confirmation",'user/convert_user.php?id='+$(this).attr('data-id'))
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



		$('#example').DataTable( {
                stateSave: true
                } );
	})
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