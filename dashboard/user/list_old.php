
<?php if($_settings->userdata('type') == 1 or $_settings->userdata('type') == 2): ?>
<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>

<style>
    .img-avatar{
        width:45px;
        height:45px;
        object-fit:cover;
        object-position:center center;
        border-radius:100%;
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
 th, td {
    font-size: 20px;
  }

</style>



<div class="content-color">
	<div class="card-header">
		<h3 class="card-title" text style="color: white">Agents</h3>
		<?php if($_settings->userdata('type') == 1): ?>
		<div class="card-tools">
			<a href="?page=user/manage_user" class="btn btn-flat btn-primary"><span class="fas fa-plus"></span>  Create New</a>
		</div>
		<?php endif; ?> 		
	</div>
	<div class="card-body">
		<div class="container-fluid">
        <div class="container-fluid">
			<table class="table table-hover table-stripped table-primary" id="example">

				<thead>
					<tr>
						<th>#</th>	
						<th>Username</th>
						<th>Type</th>
						<th>Commission Rate</th>
						<th>Balance</th> 
						<th>Status</th>	

						<?php if($_settings->userdata('type') == 1): ?>
							<th>Edit</th>
							<th>Delete</th>
						<?php endif; ?>

						<th>Activation</th>

						<?php if($_settings->userdata('type') == 1 or $_settings->userdata('type') == 2): ?>
							<th>Edit Status</th>
						<?php endif; ?>

						<th>History</th>

						<!-- <th>Option</th> -->
						
					</tr>
				</thead>
				<tbody>
					<?php 

					$i = 1;
					//$qry = $conn->query($mysql);

					if ($_settings->userdata('type') == 1){ //use admin priv
						$qry = $conn->query("SELECT * from `users` where type in (1,2,4) order by username");
					}else{
						$qry = $conn->query("SELECT * from `users`  where type = 2 and parentid = '{$_settings->userdata('id')}' order by username ");
					}


						while($row = $qry->fetch_assoc()):
					?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td><?php echo ucwords($row['username']) ?></td>
							<td><?php echo ($row['type'] == 1) ? 'Administrator' : (  ($row['type'] == 2) ? 'Agent' : (($row['type'] == 3) ? 'Player' : 'Declarator')) ?></td>
							<td><?php echo number_format($row['rate'],2) ?>%</td>
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

							
							<?php if($_settings->userdata('type') == 1): ?>
								<td align="center">
									<a class="btn btn-primary" href="?page=user/manage_user&id=<?php echo $row['id'] ?>">
										Edit
									</a>		
								</td>
								<td align="center">
									<a class="btn btn-danger delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">
										Delete
									</a>		
								</td>
							<?php endif; ?>

							
							<td align="center">
								<?php if($row['active'] == 'N'): ?>
									<a class="btn btn-success activate_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">
										Activate
									</a>
								<?php endif; ?>
								<?php if($row['active'] == 'Y'): ?>
									<a class="btn btn-danger deactivate_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">
										Deactivate
									</a>
								<?php endif; ?> 
							</td>

							<?php if($_settings->userdata('type') == 1 or $_settings->userdata('type') == 2): ?>
								<td align="center">
									<?php if($row['active'] == 'Y'): ?>
										<a class="btn btn-warning convert_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">
											Edit Status
										</a>		
									<?php endif; ?> 
								</td>
							<?php endif; ?> 

							
							<td align="center">
								<?php if($row['type'] == '3'): ?>
									<a class="btn btn-secondary" href="?page=user/view_history_player&id=<?php echo $row['id']?>">
										 History
									</a>
								<?php else: ?>
									<a class="btn btn-secondary" href="?page=user/view_history&id=<?php echo $row['id']?>">
										History
									</a>
								<?php endif; ?>
							</td>



							<!-- <td align="center">
								 <button type="button" class="btn dropbtn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-boundary="viewport" data-toggle="dropdown">
				                  		Option
				                    <span class="sr-only">Toggle Dropdown</span>
				                  </button>
				                  <div class="dropdown-menu" role="menu">
								<?php if($_settings->userdata('type') == 1): ?>
				                    <a class="dropdown-item" href="?page=user/manage_user&id=<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary"></span> Edit</a>
				                    <div class="dropdown-divider"></div>
				                    <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> Delete</a>
									<div class="dropdown-divider"></div>
								<?php endif; ?> 



									<?php if($row['active'] == 'N'): ?>
									<a class="dropdown-item activate_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-check text-success"></span> Activate</a>
									<?php endif; ?> 


									<?php if($row['active'] == 'Y'): ?>
									<a class="dropdown-item deactivate_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-times text-danger"></span> Deactivate</a>
									<div class="dropdown-divider"></div>
									<?php endif; ?> 

									<?php if($_settings->userdata('type') == 1 or $_settings->userdata('type') == 2): ?>
									<?php if($row['active'] == 'Y'): ?>
									<a class="dropdown-item convert_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-redo text-success"></span> Edit Status</a>
									<div class="dropdown-divider"></div>
									<?php endif; ?> 
									<?php endif; ?> 

									<?php if($row['type'] == '3'): ?>
									<a class="dropdown-item" href="?page=user/view_history_player&id=<?php echo $row['id']?>"><span class="fa fa-history text-primary"></span> History</a>
									<?php else: ?>
									<a class="dropdown-item" href="?page=user/view_history&id=<?php echo $row['id']?>"><span class="fa fa-history text-primary"></span> History</a>
									<?php endif; ?>


				                  </div>
							</td> -->
	
	
							


						</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('.view_history').click(function(){
			uni_modal("<i class='fa fa-history'></i> History" ,'maintenance/view_history.php?id='+$(this).attr('data-id'))
		})
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
		$('#example').DataTable( {
                stateSave: true
                } );
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