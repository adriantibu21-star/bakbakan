<?php if($_settings->userdata('type') == 1 or $_settings->userdata('type') == 4): ?>

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
		<h3 class="card-title">Events</h3>
		<div class="card-tools">
			<a href="javascript:void(0)" class="btn btn-flat btn-primary" id="create_new"><span class="fas fa-plus"></span>  Create New</a>
		</div>

	</div>
	<div class="card-body">

		<div class="row">
            <div class="col-4">
                <div class="form-group">
				<label for="status" class="control-label">Status</label>
                <select name="status" id="status" class="form-control select2bs4 select2 rounded-0" required>
                <option value="*" <?php echo isset($status) && $status == '*' ? "selected" : '' ?> >All</option>
                <option value="Y" <?php echo isset($status) && $status == 'Y' ? "selected" : '' ?>>Active</option>
                <option value="N" <?php echo isset($status) && $status == 'N' ? "selected" : '' ?>>Inactive</option>
                </select>
                </div>
            </div>

            <div class="col-2 row align-items-end pb-2">
                <div class="w-100">
				<label for="action" class="control-label">Action</label>
                    <div class="form-group d-flex justify-content-between align-middle">
                 	<button class="btn btn-flat btn-default bg-lightblue" type="button" id="filter"><i class="fa fa-filter"></i> Filter</button>
					</div>
                </div>
			</div>

        </div>


		<div class="container-fluid">
        <div class="container-fluid">
			<table class="table table-stripped table-hover" id="example">
				<thead>
					<tr>
						<th>#</th>
						<th>Game Name</th>
						<th>Event</th>
						<th>Description</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$i = 1;
                    if ($status=='*'){
						$qry = $conn->query("SELECT *,(select name from games where id = `events`.game_id) game_name from `events` order by id desc ");
                    }
                    elseif ($status=='Y'){
						$qry = $conn->query("SELECT *,(select name from games where id = `events`.game_id) game_name from `events` where active ='Y' order by id desc ");					                
                    }
                    else{
						$qry = $conn->query("SELECT *,(select name from games where id = `events`.game_id) game_name from `events` where active ='N' order by id desc ");
                    }
						while($row = $qry->fetch_assoc()):
                            $row['description'] = strip_tags(stripslashes(html_entity_decode($row['description'])));
					?>
						<tr title="<?php echo $row['description'] ?>">
							<td class="text-center"><?php echo $i++; ?></td>
							<td><?php echo $row['game_name'] ?></td>
							<td><?php echo $row['name'] ?></td>
							<td ><?php echo $row['description'] ?></td>
                            <td>
								<?php if($row['active'] == 'Y'): ?>
									<span class="badge badge-success">ACTIVE</span>
								<?php else: ?>
									<span class="badge badge-danger">INACTIVE</span>
								<?php endif; ?>
							</td>
							<td align="center">
								 <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-boundary="viewport" data-toggle="dropdown">
				                  		Action
				                    <span class="sr-only">Toggle Dropdown</span>
				                  </button>
				                  <div class="dropdown-menu" role="menu">
				                    <a class="dropdown-item edit_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary"></span> Edit</a>
				                    <div class="dropdown-divider"></div>
				                    <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> Delete</a>
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
			location.replace("./?page=maintenance/events&status="+($('#status').val()) );
        })
		$('.delete_data').click(function(){
			_conf("Are you sure to delete this permanently?","delete_event",[$(this).attr('data-id')])
		})
		$('.edit_data').click(function(){
			uni_modal("<i class='fa fa-edit'></i> Edit Details",'maintenance/manage_events.php?id='+$(this).attr('data-id'))
		})
		$('#create_new').click(function(){
			uni_modal("<i class='fa fa-plus'></i> Create New",'maintenance/manage_events.php')
		})
		$('#example').DataTable( {
                stateSave: true
                } );
	})
	function delete_event($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_events",
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
<?php endif; ?>