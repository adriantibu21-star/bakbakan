<?php if($_settings->userdata('type') == 1 or $_settings->userdata('type') == 2): ?>
<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>

<div class="card card-outline card-success">
	<div class="card-header bg-gray">
		<h3 class="card-title">View History - <?php echo $row['username'] ?></h3>
	</div>

	<div class="card-body">
		<div class="container-fluid">
        <div style="overflow-x:auto;">
			<table class="table table-hover table-stripped" id="example">

				<thead>
					<tr>
					    <th>#</th>
					    <th>Date</th>
					    <th>Fight #</th>
					    <th>Meron</th>
					    <th>Wala</th>
					    <th>Draw</th>
					    <th>Result</th>
					    <th>Earned</th>
					    <th>Balance</th>
					</tr>
				</thead>
				<tbody>

					<?php
					$i = 1;
                    $bal=0;
	

                        $qry = $conn->query("				select date_created,amount,0.00 red_amount,0.00 blue_amount,0.00 yellow_amount,1 as type,'' winner,'' drawno from loading a where active ='N' and user_id = '{$_GET['id']}'
									UNION
									select date_created,amount,0.00 red_amount,0.00 blue_amount,0.00 yellow_amount,2 as type,'' winner,'' drawno from withdrawals a where active ='N' and user_id = '{$_GET['id']}'
									UNION
									select date_created, (earnings-red_amount-blue_amount-yellow_amount) as amount,red_amount,blue_amount,yellow_amount, 4 as type,
									(select winner from draws where id = a.drawid) as winner,(select drawno from draws where id = a.drawid) as drawno
									from bets a where user_id = '{$_GET['id']}'
									order by date_created asc");



						
					while($row = $qry->fetch_assoc()):
					?>
						<tr>
							<td class=""><?php echo $i++; ?></td>
                            				<td><span class="badge"><?php echo date("m-d-Y H:i", strtotime($row['date_created'])) ?></span></td>

								<?php if($row['type'] == 1): ?>
                                    					<?php $bal = $bal + $row['amount']?>
									<td><span class="badge">Cash-In</span></td>
								<?php elseif($row['type'] == 2): ?>
                                    					<?php $bal = $bal - $row['amount']?>
									<td><span class="badge">Cash-Out</span></td>
								<?php elseif($row['type'] == 3): ?>
                                    					<?php $bal = $bal + $row['amount']?>
									<td><span class="badge">Commission</span></td>
								<?php elseif($row['type'] == 4): ?>
									<?php $bal = $bal + $row['amount']?>
									<td><?php echo $row['drawno']?></td>	
								<?php else: ?>
									<?php $bal = $bal + $row['amount']?>
									<td><?php echo $row['drawno']?></td>	
								<?php endif; ?>
						

								<td><?php echo number_format($row['red_amount'],2) ?> </td>
								<td><?php echo number_format($row['blue_amount'],2) ?> </td>
                            					<td><?php echo number_format($row['yellow_amount'],2) ?> </td>

								<td>
								<?php if($row['winner'] == 1): ?>
									<span class="badge badge-danger">Meron</span>
								<?php elseif($row['winner'] == 2): ?>
									<span class="badge badge-primary">Wala</span>
								<?php elseif($row['winner'] == 3): ?>
									<span class="badge badge-success">Draw</span>
								<?php elseif($row['winner'] == 4): ?>
									<span class="badge badge-light">Cancelled</span>
								<?php else: ?>
									<span class="badge badge-light">N/A</span>
								<?php endif; ?>
								</td>

								<?php if($row['type'] == 2): ?>
								<td>-<?php echo number_format($row['amount'],2) ?> </td>
								<?php else: ?>
								<td><?php echo number_format($row['amount'],2) ?> </td>
								<?php endif; ?>
							

								<td><?php echo number_format($bal,2) ?></td>

						</tr>
                          
					<?php endwhile; ?>

				</tbody>
			</table>
		</div>
		</div>

	</div>

	<div class="card-footer">

		<div class="col-md-12">

		<div class="card-tools">
            	<h5>Running Bal: <?php echo number_format($bal,2) ?></h5></br></br>
		</div>	
			<div class="row">
				<a class="btn btn-sm btn-secondary" href="../dashboard/?page=user/list">Close</a>
			</div>
		</div>
	</div>


</div>


<script>
	$(document).ready(function(){
        $('#example').DataTable( {
        stateSave: true
        } );
	})	
</script>

<?php endif;?>









