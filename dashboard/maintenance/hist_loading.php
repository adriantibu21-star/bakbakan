<?php if($_settings->userdata('type') == 2 ): ?> 

<?php
$agent_qry = $conn->query("SELECT usercode,concat(firstname,' ',lastname) as name FROM users where parentid = '{$_settings->userdata('id')}' order by firstname asc");
$agent_arr = array_column($agent_qry->fetch_all(MYSQLI_ASSOC),'name','usercode');
?>
<?php
$agent = isset($_GET['agent']) ? $_GET['agent'] : '*';
?>
<div class="card card-outline card-primary">
	<div class="card-header">
		<h3 class="card-title">Cash-In History</h3>
	</div>
	<div class="card-body">

	<div class="row">
            <div class="col-2">
                 <div class="form-group">
    
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
                    <div class="form-group d-flex justify-content-between align-middle">
                 	<button class="btn btn-flat btn-default bg-lightblue" type="button" id="filter"><i class="fa fa-filter"></i> Filter</button>
					</div>
                </div>
			</div>

    </div>

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
					</tr>
				</thead>
				<tbody>
					<?php 
					$i = 1;
					$bal = 0;		
					$agent_qry = $conn->query("SELECT id,concat(firstname,' ',lastname) as name FROM users");
					$agent_arr = array_column($agent_qry->fetch_all(MYSQLI_ASSOC),'name','id');	
					if ($agent=='*'){
					$qry = $conn->query("SELECT * from loading where active ='N' and user_id IN (Select id from users where parentid = '{$_settings->userdata('id')}'    ) ");
					}else{
					$qry = $conn->query("SELECT * from loading where active ='N' and user_id IN (Select id from users where parentid = '{$_settings->userdata('id')}'  and usercode ='{$agent}'  ) ");	
					}
						while($row = $qry->fetch_assoc()):
                             ?>
                            <tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td><?php echo date("m-d-Y", strtotime($row['date_created'])) ?></td>
							<td ><?php echo isset($agent_arr[$row['user_id']]) ? $agent_arr[$row['user_id']] : 'N/A' ?></td>
                            <td><?php echo number_format($row['amount'],2) ?> </td>
							<td><?php echo $row['description'] ?></td>
							</tr>
							<?php $bal = $bal + $row['amount']?>
						<?php endwhile; ?>
						<h6><strong>Total Cash-in: <?php echo number_format($bal,2) ?></strong></h6>
				</tbody>
			</table>
		</div>
		</div>
</div>
</div>


<script>
	$(document).ready(function(){
		$('#filter').click(function(){
			location.replace("./?page=maintenance/hist_loading&agent="+($('#agent').val()) );
        })
        $('#example').DataTable( {
        stateSave: true
        } );
	})
</script>
<?php endif;?>



