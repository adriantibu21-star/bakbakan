<?php if($_settings->userdata('type') == 1): ?> 

<?php
$DateSearch = isset($_GET['DateSearch']) ? $_GET['DateSearch'] : '*';
?>

<div class="card card-outline card-success">
	<div class="card-header bg-gray">
		<h3 class="card-title">Fight Logs</h3>
	</div>

	<div class="card-body">
		<div class="container-fluid">
        <div style="overflow-x:auto;">


     
			<input type="date" id="DateSearch" name="Date" value="<?php echo isset($_GET['DateSearch']) ? $_GET['DateSearch'] : date("Y-m-d") ?>">

                 	<button class="btn btn-flat btn-default bg-primary" type="button" id="filter"><i class="fa fa-filter"></i> Filter</button>
	
			<table class="table table-hover table-stripped" id="example">

				<thead>
					<tr>
					    <th>#</th>
					    <th>Date</th>
					    <th>Fight #</th>
					    <th>Username</th>
					    <th>Meron</th>
					    <th>Wala</th>
					    <th>Draw</th>
					    <th>Result</th>
					    <th>Earned</th>
					</tr>
				</thead>
				<tbody>

					<?php
					$i = 1;
                    			$bal=0;
	

					if ($DateSearch=='*'){
                        			$qry = $conn->query("				
									select b.username username, date_created, (earnings-red_amount-blue_amount-yellow_amount) as amount,red_amount,blue_amount,yellow_amount, 4 as type,
									(select winner from draws where id = a.drawid) as winner,(select drawno from draws where id = a.drawid) as drawno
									from bets a
									join users b on b.id = a.user_id
									where convert(date_created,date) = convert(now(),date)
									order by date_created asc");

					}else{
                        			$qry = $conn->query("				
									select b.username  username, date_created, (earnings-red_amount-blue_amount-yellow_amount) as amount,red_amount,blue_amount,yellow_amount, 4 as type,
									(select winner from draws where id = a.drawid) as winner,(select drawno from draws where id = a.drawid) as drawno
									from bets a
									join users b on b.id = a.user_id
									where convert(date_created,date) = convert('{$DateSearch}',date) 
									order by date_created asc");

					}
	


						
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
						

								<td><?php echo $row['username'] ?> </td>

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

		</div>	
			<div class="row">
				<a class="btn btn-sm btn-secondary" href="../dashboard/">Close</a>
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

	$('#filter').click(function(){
			location.replace("./?page=maintenance/admin_hist_bets&DateSearch="+($('#DateSearch').val()) );
        })



</script>





<?php endif;?>










