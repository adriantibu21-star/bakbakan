
<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>

<?php
$agent_qry = $conn->query("SELECT id,concat(firstname,' ',lastname) as name FROM users order by firstname asc");
$agent_arr = array_column($agent_qry->fetch_all(MYSQLI_ASSOC),'name','id');
?>

<?php
$status = isset($_GET['status']) ? $_GET['status'] : '*';
$agent = isset($_GET['agent']) ? $_GET['agent'] : '*';
?>

<div class="card card-outline card-primary">
	<div class="card-header">
		<h3 class="card-title">Withdrawals</h3>
		<div class="card-tools">
		<?php if($_settings->userdata('type') == 1): ?>
		<a href="?page=maintenance/manage_withdrawals&status=<?php echo $status ?>&agent=<?php echo $agent ?>" class="btn btn-flat btn-primary"><span class="fas fa-plus"></span>  Create New</a>
		<?php else: ?>
		<a href="?page=maintenance/manage_withdrawals" class="btn btn-flat btn-primary"><span class="fas fa-plus"></span>  Create New</a>	
		<?php endif; ?> 
		</div>
	</div>
	<div class="card-body">
		<?php if($_settings->userdata('type') == 1): ?>
        <div class="row">
            <div class="col-2">
                <div class="form-group">
				<label for="status" class="control-label">Status</label>
                <select name="status" id="status" class="form-control select2bs4 select2 rounded-0" required>
                <option value="*" <?php echo isset($status) && $status == '*' ? "selected" : '' ?> >All</option>
                <option value="Y" <?php echo isset($status) && $status == 'Y' ? "selected" : '' ?>>Active</option>
                <option value="N" <?php echo isset($status) && $status == 'N' ? "selected" : '' ?>>Inactive</option>
                </select>
                </div>
            </div>

            <div class="col-2">
                 <div class="form-group">
                    <label for="agent" class="control-label">Agent</label>
                    <select name="agent" id="agent" class="form-control select2bs4 select2 rounded-0" required>
                        <option value="*" <?php echo isset($agent) && $agent == '*' ? "selected" : '' ?>>All</option>
                        <?php foreach($agent_arr as $k=>$v): ?>
                        <option value="<?php echo $k ?>"<?php echo isset($agent) && $agent == $k ? "selected" : '' ?>><?php echo $v ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="col-2">
                <div class="w-100">
				<label for="action" class="control-label">Action</label>
                    <div class="form-group d-flex justify-content-between align-middle">
                 	<button class="btn btn-flat btn-default bg-lightblue" type="button" id="filter"><i class="fa fa-filter"></i> Filter</button>
					<button class="btn btn-flat btn-default bg-success post_all" type="button"><i class="fa fa-paper-plane"></i> Post Active</button>
					</div>
                </div>
			</div>

        </div>
	    <?php endif; ?> 
		<div class="container-fluid">
        <div class="container-fluid">
			<table id ="example" class="table table-hover table-stripped">

				<thead>
					<tr>
					    <th>#</th>
					    <th>Date</th>
					    <th>Name</th>
					    <th>Amount</th>
						<th>Balance</th>
						<th>Details</th>
						<th>Status</th>
					    <th>Select</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$i = 1;
                                        
					$agent_qry = $conn->query("SELECT id,concat(firstname,' ',lastname) as name FROM users");
					$agent_arr = array_column($agent_qry->fetch_all(MYSQLI_ASSOC),'name','id');
					
				if ($_settings->userdata('type') == 1){ //use admin priv    
					
					
                    if ($status=='*'){
						if ($agent=='*'){
                        	$qry = $conn->query("SELECT *, (select amount from users where id =withdrawals.user_id) as bal from withdrawals");
						}else{
							$qry = $conn->query("SELECT *, (select amount from users where id =withdrawals.user_id) as bal from withdrawals where user_id = '$agent'");
						}
                    }
                    elseif ($status=='Y'){
						if ($agent=='*'){
                        	$qry = $conn->query("SELECT *, (select amount from users where id =withdrawals.user_id) as bal from withdrawals where active ='Y'");
						}else{
							$qry = $conn->query("SELECT *, (select amount from users where id =withdrawals.user_id) as bal from withdrawals where active ='Y' and user_id = '$agent'");							
						}
						                
                    }
                    else{
						if ($agent=='*'){
                        	$qry = $conn->query("SELECT *, (select amount from users where id =withdrawals.user_id) as bal from withdrawals where active ='N'");   
						}else{
							$qry = $conn->query("SELECT *, (select amount from users where id =withdrawals.user_id) as bal from withdrawals where active ='N' and user_id = '$agent'");  
						}
                    }


				}else{ //admin priv
					//$qry = $conn->query("SELECT * from withdrawals where active ='Y' and user_id = '{$_settings->userdata('id')}'");
					$qry = $conn->query("SELECT *, (select amount from users where id =withdrawals.user_id) as bal from withdrawals where active ='Y' and user_id IN (Select id from users where parentid = '{$_settings->userdata('id')}') ");
				}
                        
						while($row = $qry->fetch_assoc()):
                             ?>
                            <tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td><?php echo date("m-d-Y", strtotime($row['date_created'])) ?></td>
							<td ><?php echo isset($agent_arr[$row['user_id']]) ? $agent_arr[$row['user_id']] : 'N/A' ?></td>
                            <td><?php echo number_format($row['amount'],2) ?> </td>
							<td><?php echo number_format($row['bal'],2) ?> </td>
							<td><?php echo $row['description'] ?></td>
                            <td>
								<?php if($row['active'] == 'Y'): ?>
									<span class="badge badge-success">ACTIVE</span>
								<?php else: ?>
									<span class="badge badge-danger">INACTIVE</span>
								<?php endif; ?>
							</td>
							<td align="center">
						  	<button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-boundary="viewport" data-toggle="dropdown">
                            Select<span class="sr-only">Toggle Dropdown</span>
				                  </button>
				                  <div class="dropdown-menu" role="menu">
                                     
								<?php if($_settings->userdata('type') == 1): ?> 
				                    <a class="dropdown-item" href="?page=maintenance/manage_withdrawals&id=<?php echo $row['id'] ?>&status=<?php echo $status ?>&agent=<?php echo $agent ?>"><span class="fa fa-edit text-primary"></span> Edit</a>
				                    <div class="dropdown-divider"></div>
								<?php endif; ?>
									<a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> Delete</a>  
									<div class="dropdown-divider"></div>

									<a class="dropdown-item post_data" href="javascript:void(0)" 
									data-id="<?php echo $row['id']?>" 
									userid="<?php echo $row['user_id']?>"
									amount="<?php echo $row['amount']?>"
									>
									<span class="fa fa-paper-plane text-success"></span> Post</a>

				                  </div>
							</td>
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

        $('#filter').click(function(){
			location.replace("./?page=maintenance/withdrawals&status="+($('#status').val())+"&agent="+($('#agent').val()) );
        })
		$('.delete_data').click(function(){
			_conf("Are you sure to delete this record permanently?","delete_transaction",[$(this).attr('data-id')])
		})
		$('.post_data').click(function(){
			_conf("Do you want to POST this record?","post_transaction",[$(this).attr('data-id'),$(this).attr('userid'),$(this).attr('amount')])
		})
		$('.post_all').click(function(){
			_conf("Do you want to POST all active withdrawals?","post_all")
		})

                $('#example').DataTable( {
                stateSave: true
                } );
	})
	function delete_transaction($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_withdrawals",
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

	function post_transaction($id,$userid,$amount){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=post_withdrawals",
			method:"POST",
			data:{id: $id, userid: $userid, amount: $amount},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
				}else if(resp.status == 'failed' && !!resp.msg){
					alert_toast(resp.msg,'error');
                    end_loader()					
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
	function post_all(){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=postall_withdrawals",
			method:"POST",
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
				}else if(resp.status == 'failed' && !!resp.msg){
					alert_toast(resp.msg,'error');
                    end_loader()
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
</script>
