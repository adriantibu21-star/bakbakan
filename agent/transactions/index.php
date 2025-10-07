<?php
require_once('../classes/getWorkingDays.php');
?>
<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>

<?php
$status = isset($_GET['status']) ? $_GET['status'] : '*';
?>

<div class="card card-outline card-primary">
	<div class="card-header">
		<h3 class="card-title">List of Transactions</h3>
		<div class="card-tools">
			<a href="?page=transactions/manage_transaction&status=<?php echo $status ?>" class="btn btn-flat btn-primary"><span class="fas fa-plus"></span>  Create New</a>
			<! �� &status=<?php echo $status ?> ��>
		</div>
	</div>
	<div class="card-body">

        <div class="row">
            <div class="col-4">
                <div class="form-group">
                <select name="status" id="status" class="form-control select2bs4 select2 rounded-0" required>
                    

                <option value="*" <?php echo isset($status) && $status == '*' ? "selected" : '' ?> >All</option>
                <option value="incoming" <?php echo isset($status) && $status == 'incoming' ? "selected" : '' ?>>Incoming</option>
                <option value="outgoing" <?php echo isset($status) && $status == 'outgoing' ? "selected" : '' ?>>Outgoing</option>
                <option value="forwarded" <?php echo isset($status) && $status == 'forwarded' ? "selected" : '' ?>>Forwarded</option>
                </select>
                </div>
            </div>
            <div class="col-4">
                 <div class="form-group">
                 <button class="btn btn-flat btn-default bg-lightblue" type="button" id="filter"><i class="fa fa-filter"></i> Filter</button>
                 </div>
            </div>
        </div>
	    
		<div class="container-fluid">
        <div class="container-fluid">
			<table id ="example" class="table table-hover table-stripped">    <! �� id ="example" ��>
				<colgroup>
					<col width="5%">
					<col width="5%">
					<col width="5%">
					<col width="5%">
					<col width="20%">
					<col width="15%">
					<col width="10%">
					<col width="5%">
					<col width="10%">
					<col width="5%">
					<col width="10%">
					<col width="5%">
				</colgroup>
				<thead>
					<tr>
					    <th>#</th>
					    <th>Tracking No.</th>
					    <th>Type/Days Remaining</th>
					    <th>Date</th>
					    <th>Description</th>
					    <th>Remarks</th>
					    <th>Source</th>
					    <th>Status</th>
					    <th>Office</th>
					    <th>Action</th>
                                            <th>Office</th>
					    <th>Select</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$i = 1;
                                        
                    $holidays = array();
                    $holiday_qry = $conn->query("SELECT name FROM holidays");
                    while($h_row = $holiday_qry->fetch_assoc()){
                        array_push($holidays,$h_row['name']);
                    }

					$department_qry = $conn->query("SELECT id,name FROM department_list");
					$dept_arr = array_column($department_qry->fetch_all(MYSQLI_ASSOC),'name','id');
					
					$user_qry = $conn->query("SELECT id,username FROM users");
					$user_arr = array_column($user_qry->fetch_all(MYSQLI_ASSOC),'username','id');
                        
                    if ($status=='*'){
                        $qry = $conn->query("SELECT * from transactions where (office2='{$_settings->userdata('department_id')}' and status ='2' and finished='N') or (office1='{$_settings->userdata('department_id')}' and status ='1' and finished='N') or (office1='{$_settings->userdata('department_id')}' and status ='2' and finished='N')");
                    }
                    elseif ($status=='incoming'){
                        $qry = $conn->query("SELECT * from transactions where office2='{$_settings->userdata('department_id')}' and status ='2' and finished='N'");                
                    }
                    elseif ($status=='outgoing'){
                        $qry = $conn->query("SELECT * from transactions where office1='{$_settings->userdata('department_id')}' and status ='1' and finished='N'");   
                    }
                    else{
                        $qry = $conn->query("SELECT * from transactions where office1='{$_settings->userdata('department_id')}' and status ='2' and finished='N'");   
                    }
                        
						while($row = $qry->fetch_assoc()):
						

                                                $startDate =date("Y-m-d", strtotime($row['date_created']));


                                        ?>
                            <tr>
							<td class="text-center"><?php echo $i++; ?> <span><a href="javascript:void(0)" class="view_data" data-id="<?php echo $row['id'] ?>"><span class="fa fa-qrcode"></span></a></span>   </td>
                            <td><?php echo $row['trackingno'] ?> </td>
							<td class="">
							
                                         <?php if($row['finished'] == 'Y'){
											$endDate =date("Y-m-d", strtotime($row['date_updated'])); 
                                         } else{
                                         	$endDate = date("Y-m-d");
                                         } 
                                         ?>    
 

                                <?php if($row['type'] == 1): ?>
                                                                    <?php if((3-(getWorkingDays($startDate,$endDate,$holidays)-1))>0): ?>
                                                                         <span class="badge badge-primary">Simple(<?php echo 3-(getWorkingDays($startDate,$endDate,$holidays)-1) ; ?>)</span>
                                                                    <?php else: ?>
                                                                         <span class="badge badge-danger">Simple(<?php echo 3-(getWorkingDays($startDate,$endDate,$holidays)-1) ; ?>)</span>
                                                                    <?php endif; ?>
								<?php elseif($row['type'] == 2): ?>
                                                                    <?php if((7-(getWorkingDays($startDate,$endDate,$holidays)-1))>0): ?>
                                                                         <span class="badge badge-primary">Complex(<?php echo 7-(getWorkingDays($startDate,$endDate,$holidays)-1) ; ?>)</span>
                                                                    <?php else: ?>
                                                                         <span class="badge badge-danger">Complex(<?php echo 7-(getWorkingDays($startDate,$endDate,$holidays)-1) ; ?>)</span>
                                                                    <?php endif; ?>
								<?php else: ?>
                                                                    <?php if((15-(getWorkingDays($startDate,$endDate,$holidays)-1))>0): ?>
                                                                         <span class="badge badge-primary">Highly-Technical(<?php echo 15-(getWorkingDays($startDate,$endDate,$holidays)-1) ; ?>)</span>
                                                                    <?php else: ?>
                                                                         <span class="badge badge-danger">Highly-Technical(<?php echo 15-(getWorkingDays($startDate,$endDate,$holidays)-1) ; ?>)</span>
                                                                    <?php endif; ?>
								<?php endif; ?>
							</td>
							<td><?php echo date("m-d-Y", strtotime($row['date_created'])) ?></td>
							<td><?php echo $row['description'] ?></td>
							<td><?php echo $row['remarks'] ?></td>
                                                        <td ><?php echo isset($dept_arr[$row['source']]) ? $dept_arr[$row['source']] : 'N/A' ?></td>
 							
                                                         <td class="">
								<?php if($row['finished'] == 'N'): ?>
									<span class="badge badge-success">Ongoing</span>
								<?php else: ?>
									<span class="badge badge-primary">Finished</span>
								<?php endif; ?>
							</td>


                            <td ><?php echo isset($dept_arr[$row['office1']]) ? $dept_arr[$row['office1']] : 'N/A' ?></td>
                            <td class="">
								<?php if($row['status'] == '1'): ?>
									<span class="badge badge-success">received from</span>
								<?php else: ?>
									<span class="badge badge-primary">forwarded to</span>
								<?php endif; ?>
							</td>
                                                        <td ><?php echo isset($dept_arr[$row['office2']]) ? $dept_arr[$row['office2']] : 'N/A' ?></td>

							<td align="center">
						  <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                         Select
				                         <span class="sr-only">Toggle Dropdown</span>
				                  </button>
				                  <div class="dropdown-menu" role="menu">


     				  	            <a class="dropdown-item view_tracking" href="?page=transactions/tracking_rpt&id=<?php echo $row['id'] ?>"><span class="fa fa-print text-success"></span> Preview</a>
				                    <div class="dropdown-divider"></div>

				  	                <a class="dropdown-item view_tracking" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-eye"></span> Details</a>
				                    <div class="dropdown-divider"></div>
                                    
                                    <?php if($row['finished']== 'N'): ?>
				                    <a class="dropdown-item" href="?page=transactions/manage_transaction&id=<?php echo $row['id'] ?>&status=<?php echo $status ?>"><span class="fa fa-edit text-primary"></span> Edit</a>
				                                                                                                                    <! �� &status=<?php echo $status ?> ��>
                                    <div class="dropdown-divider"></div>
                                    <?php endif; ?>
                                    
				                    <?php if($_settings->userdata('type') == 1): ?>
				                    <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> Delete</a>
                                                     <div class="dropdown-divider"></div>
                                                     <?php endif; ?>

                                                    


				                    <?php if($row['status'] == 2 and $row['office2'] == $_settings->userdata('department_id') && $row['finished'] =='N'): ?>
				                    <a class="dropdown-item receive_data" href="javascript:void(0)"
                                                       office1="<?php echo $row['office2'] ?>"
                                                       office2="<?php echo $row['office1'] ?>"
                                                       status = "1"
                                                       userid = "<?php echo $_settings->userdata('id') ?>"
                                                       data-id="<?php echo $row['id'] ?>">
                                                    <span class="fa fa-hand-holding text-primary"></span> Receive</a>
                                                    <div class="dropdown-divider"></div>
                                                    <?php endif; ?>
                                                    
				                    <?php if($row['status'] == 1 and $row['office1'] == $_settings->userdata('department_id') && $row['finished'] =='N'): ?>
				                    <a class="dropdown-item forward_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"> <span class="fa fa-forward text-primary"></span> Forward</a>
                                                    <div class="dropdown-divider"></div>
                                                    <?php endif; ?>


				                    <?php if($row['status'] == 2 and $row['office1'] == $_settings->userdata('department_id') && $row['finished'] =='N'): ?>
				                    <a class="dropdown-item recall_data" href="javascript:void(0)"
                                                       office1="<?php echo $row['office1'] ?>"
                                                       office2="<?php echo $row['office2'] ?>"
                                                       status = "1"
                                                       userid = "<?php echo $_settings->userdata('id') ?>"
                                                       data-id="<?php echo $row['id'] ?>">
                                                    <span class="fa fa-undo text-primary"></span> Recall</a>
                                                    <div class="dropdown-divider"></div>
                                                    <?php endif; ?>

				                    <?php if($row['status'] == 1 and $row['office1'] == $_settings->userdata('department_id') && $row['finished'] =='N'): ?>
				                    <a class="dropdown-item finish_data" href="javascript:void(0)"
                                                       office1="<?php echo $row['office1'] ?>"
                                                       office2="<?php echo $row['office2'] ?>"
                                                       status = "<?php echo $row['status'] ?>"
                                                       userid = "<?php echo $_settings->userdata('id') ?>"
                                                       data-id="<?php echo $row['id'] ?>">
                                                    <span class="fa fa-flag-checkered text-primary"></span> Finish</a>
                                                    <div class="dropdown-divider"></div>
                                                    <?php endif; ?>

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
            location.replace("./?page=transactions&status="+($('#status').val()));
        })

		$('.delete_data').click(function(){
			_conf("Are you sure to delete this record permanently?","delete_transaction",[$(this).attr('data-id')])
		})
		
		$('.view_data').click(function(){
			uni_modal("QR","./transactions/view_qr.php?id="+$(this).attr('data-id'))
		})
		
		$('.receive_data').click(function(){
			_conf("Are you sure to receive this document?","receive_transaction",[$(this).attr('data-id'),$(this).attr('status'),$(this).attr('office1'),$(this).attr('office2'),$(this).attr('userid')])
		})
		$('.recall_data').click(function(){
			_conf("Are you sure to recall this document?","recall_transaction",[$(this).attr('data-id'),$(this).attr('status'),$(this).attr('office1'),$(this).attr('office2'),$(this).attr('userid')])
		})
		$('.finish_data').click(function(){
			_conf("Are you sure to tag this document as finished?","finish_transaction",[$(this).attr('data-id'),$(this).attr('status'),$(this).attr('office1'),$(this).attr('office2'),$(this).attr('userid')])
		})
		$('.forward_data').click(function(){
			uni_modal("<i class='fa fa-list'></i> Forward to",'transactions/forward_document.php?id='+$(this).attr('data-id'))
                 })
		$('.view_tracking').click(function(){
			uni_modal("<i class='fa fa-list'></i>Tracking History" ,'transactions/view_application.php?id='+$(this).attr('data-id'))
		})


                $('#example').DataTable( {
                stateSave: true
                } );
                
                //$('#example').DataTable( {stateSave: true
	})
	function delete_transaction($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_transaction",
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
	function receive_transaction($id,$status,$office1,$office2,$userid){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=receive_transaction",
			method:"POST",
			data:{id: $id, status: $status, office1: $office1, office2: $office2, userid: $userid},
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
	function recall_transaction($id,$status,$office1,$office2,$userid){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=recall_transaction",
			method:"POST",
			data:{id: $id, status: $status, office1: $office1, office2: $office2, userid: $userid},
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
	function finish_transaction($id,$status,$office1,$office2,$userid){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=finish_transaction",
			method:"POST",
			data:{id: $id, status: $status, office1: $office1, office2: $office2, userid: $userid},
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



