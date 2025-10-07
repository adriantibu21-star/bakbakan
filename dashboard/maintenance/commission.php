<?php if($_settings->userdata('type') == 1 or $_settings->userdata('type') == 2): ?> 
<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>

<?php
$agent_qry = $conn->query("SELECT id,username FROM users order by username asc");
$agent_arr = array_column($agent_qry->fetch_all(MYSQLI_ASSOC),'username','id');
?>

<?php
$agent = isset($_GET['agent']) ? $_GET['agent'] : '*';
?>

<div class="card card-outline card-primary">
	<div class="card-header">
		<h3 class="card-title">Commissions</h3>	
		<div class="card-tools">
		</div>
	</div>
	<div class="card-body">

	<?php if($_settings->userdata('type') == 1): ?>
        <div class="row">
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

            <div class="col-3">
                <div class="w-100">
				<label for="action" class="control-label">Action</label>
                    <div class="form-group d-flex justify-content-between align-middle">
                 	<button class="btn btn-flat btn-default bg-lightblue" type="button" id="filter"><i class="fa fa-filter"></i> Filter</button>
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
					    <th>Agent Name</th>
					    <th>Amount</th>
					    <th>Select</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$i = 1;
					$bal=0;
                                        
					$agent_qry = $conn->query("SELECT id,username FROM users");
					$agent_arr = array_column($agent_qry->fetch_all(MYSQLI_ASSOC),'username','id');
					
                    if ($_settings->userdata('type') == 1){ //use admin priv
					
							if ($agent=='*'){

								$qry = $conn->query("SELECT id, com_amount_bal from users where com_amount_bal > 0 and type <> 3 order by com_amount_bal desc");

							}else{
							
								$qry = $conn->query("SELECT id, com_amount_bal from users where id = '$agent' and com_amount_bal > 0 and type <> 3 order by com_amount_bal desc");
							}
                        
		     }else{

						$qry = $conn->query("SELECT id, com_amount_bal from users where id IN (Select id from users where parentid = '{$_settings->userdata('id')}') and com_amount_bal > 0 order by com_amount_bal desc");


			}
						while($row = $qry->fetch_assoc()):
							$bal = $bal + $row['com_amount_bal'];
                             ?>
                            <tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td ><?php echo isset($agent_arr[$row['id']]) ? $agent_arr[$row['id']] : 'N/A' ?></td>
                            				<td><?php echo number_format($row['com_amount_bal'],2) ?> </td>
							
							<td align="center">

							<a><button type="button" id="post_data" class="btn btn-success btn-sm  post_data" href="javascript:void(0)"
							data-id="<?php echo $row['id'] ?>"
							agentid="<?php echo $_settings->userdata('id') ?>">
							<span class="nav-icon fas fa-coins"></span> Convert to Wallet </a>
                                    

							</td>
							
							</tr>
							
						<?php endwhile; ?>
						<h5><strong>Total Commisssions: <?php echo number_format($bal,2) ?></strong></h5>
		
				</tbody>
				
			</table>
			
		</div>
		</div>
</div>
</div>
<script>
	var $id;
	var $agentid;

	$(document).ready(function(){
        	$('#filter').click(function(){
			location.replace("./?page=maintenance/commission&agent="+($('#agent').val()) );
        	})
		$('.batch_post').click(function(){
			_conf("Do you want to POST commissions for single agent?","batch_post",[$(this).attr('userid')])
		})
		$('.post_all').click(function(){
			_conf("Do you want to POST all active commissions?","post_all")
		})
		$('.delete_data').click(function(){
			_conf("Are you sure to delete this record permanently?","delete_transaction",[$(this).attr('data-id')])
		})
		$('.post_data').click(function(){
			$id = $(this).attr('data-id')
			$agentid = $(this).attr('agentid')
      			uni_modal("<i class='fa fa-coins'></i> Commission to Wallet", 'transactions/post_commission.php?id=' + $id + '&agentid=' + $agentid)
		})
                $('#example').DataTable( {
                stateSave: true
                } );


	})
	function delete_transaction($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_commission",
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

	function post_transaction(){
		start_loader();		
		$.ajax({
			url:_base_url_+"classes/Master.php?f=post_commission",
			method:"POST",
			data:{id: $id, agentid: $agentid},
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


	function batch_post($userid){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=batchpost_commission",
			method:"POST",
			data:{userid: $userid},
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
			url:_base_url_+"classes/Master.php?f=postall_commission",
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
<?php endif;?>



