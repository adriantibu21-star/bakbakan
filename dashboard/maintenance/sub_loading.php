<?php if($_settings->userdata('type') == 1 or $_settings->userdata('type') == 2): ?> 
<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>
<div class="card card-outline card-primary">
	<div class="card-header">
		<h3 class="card-title">Sub-Agents Cash-In</h3>
	</div>
	<div class="card-body">
		<div class="container-fluid">
        <div class="container-fluid">
			<table id ="example" class="table table-hover table-stripped">
				<thead>
					<tr>
					    <th>#</th>
					    <th>Date</th>
					    <th>Name</th>
					    <th>Amount</th>
						<th>Details</th>
						<th>Status</th>
					    <th>Select</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$i = 1;                                       
					$agent_qry = $conn->query("SELECT id,firstname FROM users");
					$agent_arr = array_column($agent_qry->fetch_all(MYSQLI_ASSOC),'firstname','id');	
					$qry = $conn->query("SELECT * from loading where active ='Y' and user_id IN (Select id from users where parentid = '{$_settings->userdata('id')}') ");
						while($row = $qry->fetch_assoc()):
                             ?>
                            <tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td><?php echo date("m-d-Y", strtotime($row['date_created'])) ?></td>
							<td ><?php echo isset($agent_arr[$row['user_id']]) ? $agent_arr[$row['user_id']] : 'N/A' ?></td>
                            <td><?php echo number_format($row['amount'],2) ?> </td>
							<td><?php echo $row['description'] ?></td>
                            <td>
								<?php if($row['active'] == 'Y'): ?>
									<span class="badge badge-success">ACTIVE</span>
								<?php else: ?>
									<span class="badge badge-danger">INACTIVE</span>
								<?php endif; ?>
							</td>
							<td align="center">
						  	<button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                            Select<span class="sr-only">Toggle Dropdown</span>
				                  </button>
				                  <div class="dropdown-menu" role="menu">
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

		$('.post_data').click(function(){
			_conf("Do you want to POST this record?","post_transaction",[$(this).attr('data-id'),$(this).attr('userid'),$(this).attr('amount')])
		})
                $('#example').DataTable( {
                stateSave: true
                } );
	})
	function post_transaction($id,$userid,$amount){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=post_loading",
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
</script>
<?php endif;?>



