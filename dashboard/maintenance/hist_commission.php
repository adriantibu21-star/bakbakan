<?php if($_settings->userdata('type') == 2 or $_settings->userdata('type') == 1): ?>

<?php //for search
$b_qry = $conn->query("SELECT usercode,username FROM users where parentid = '{$_settings->userdata('id')}' order by username");
$b_arr = array_column($b_qry->fetch_all(MYSQLI_ASSOC),'username','usercode');
?>

<?php
$agent = isset($_GET['agent']) ? $_GET['agent'] : '*';
?>
<div class="card card-outline card-primary">
	<div class="card-header">
		<h3 class="card-title">History</h3>
	</div>

	<div class="card-body">	

	<div class="row">
            <div class="col-2">
                 <div class="form-group">
                    <select name="agent" id="agent" class="form-control select2bs4 select2 rounded-0" required>
                        <option value="*" <?php echo isset($agent) && $agent == '*' ? "selected" : '' ?>>All</option>
                        <?php foreach($b_arr as $k=>$v): ?>
                        <option value="<?php echo $k ?>"<?php echo isset($agent) && $agent == $k ? "selected" : '' ?>><?php echo $v ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="col-2">
                <div class="w-100">
                    <div class="form-group d-flex justify-content-between align-middle">
                 	<button class="btn btn-flat btn-default bg-primary" type="button" id="filter"><i class="fa fa-filter"></i> Filter</button>
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
					    <th>Member</th>
					    <th>Amount</th>
					    <th>Processedby</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$i = 1;
					$bal=0;
                                        
					$agent_qry = $conn->query("SELECT id,username FROM users");
					$agent_arr = array_column($agent_qry->fetch_all(MYSQLI_ASSOC),'username','id');

					$event_qry = $conn->query("SELECT id,name FROM events");
					$event_arr = array_column($event_qry->fetch_all(MYSQLI_ASSOC),'name','id');
	
					if ($_settings->userdata('type') == 1){
						$qry = $conn->query("SELECT * from coms_converted  order by id desc");
					}else{
						$qry = $conn->query("SELECT * from coms_converted  where user_id IN (Select id from users where parentid = '{$_settings->userdata('id')}') order by id desc");	
					} 

	

						while($row = $qry->fetch_assoc()):
							$bal = $bal + $row['amount_converted'];
                             ?>
                            <tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td><?php echo date("m-d-Y H:i:s", strtotime($row['date_created'])) ?></td>
							<td ><?php echo isset($agent_arr[$row['user_id']]) ? $agent_arr[$row['user_id']] : 'N/A' ?></td>
                            				<td><?php echo number_format($row['amount_converted'],2) ?> </td>
							<td ><?php echo isset($agent_arr[$row['agent_id']]) ? $agent_arr[$row['agent_id']] : 'N/A' ?></td>
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
	$(document).ready(function(){

        $('#filter').click(function(){
			location.replace("./?page=maintenance/hist_commission&agent="+($('#agent').val()) );
        })

        $('#example').DataTable( {
        stateSave: true
        } );
	})	
</script>
<?php endif;?>










