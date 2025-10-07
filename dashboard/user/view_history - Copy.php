<?php if($_settings->userdata('type') == 1 or $_settings->userdata('type') == 2): ?>
<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>

<div class="card card-outline card-success">
	<div class="card-header bg-gray">
		<h3 class="card-title">View History</h3>
	</div>

	<div class="card-body">
		<div class="container-fluid">
        <div style="overflow-x:auto;">
			<table class="table table-hover table-stripped" id="example">

				<thead>
					<tr>
					    <th>#</th>
					    <th>Date</th>
					    <th>Amount</th>
					    <th>Balance</th>
					    <th>Type</th>
					    <th>AccountType/Fight</th>
					    <th>ProcessBy</th>
					</tr>
				</thead>
				<tbody>

					<?php
					$i = 1;
                    $bal=0;
 

			$qryBeginingbalance = $conn->query("SELECT T1.ending_asof,T1.amount,T1.type, (select com_amount_accu - com_amount_bal from users where  id = '{$_GET['id']}') com_amount_accu, coalesce((select sum(amount_converted) from coms_converted  where  user_id = '{$_GET['id']}'),0.00) sum_amount_converted FROM 
							(SELECT ending_asof, amount, 'Cut-off Balance' type from endingbalance where user_id = '{$_GET['id']}'
							UNION SELECT DATE_FORMAT(date_added, '%Y-%m-%d %H:%i') date_added, 0,'Beginning Balance' type from users where id = '{$_GET['id']}'
							order by ending_asof desc limit 1) T1");

				
			if($qryBeginingbalance ->num_rows > 0){

				$rowevent = $qryBeginingbalance ->fetch_assoc();
				$ending_asof = $rowevent['ending_asof']; 
				$ending_amount = $rowevent['amount'];
				$type = $rowevent['type'];
				$com_amount_accu = $rowevent['com_amount_accu'];
				$sum_amount_converted = $rowevent['sum_amount_converted'];


			}else{
				$ending_asof = '1900-01-01'; 
				$ending_amount = 0;
				$type = 'Unknown';
			}
	



                        $qry = $conn->query("select '{$ending_asof}' date_created, '{$ending_amount}' amount, 0 as type, '' accttyp, '' processby
									UNION ALL
										select CONVERT('2022-09-15 07:00',datetime) date_created, '{$com_amount_accu}' - '{$sum_amount_converted}' amount, 3 as type, '' accttyp, '' processby
									UNION ALL
									select date_created,amount,1 as type,
									(select
									concat(case
										when role = 1 then 'Operator'
										when role = 2 then 'Sub-Operator'
										when role = 3 then 'Master Agent'
										when role = 4 then 'Gold Agent'
										when role = 5 then 'Player'
										else ''
									end,' - ', username)
									from users where id = a.agent_id) accttyp,
									(select username from users where id = a.agent_id) processby from loading a where active ='N' and user_id = '{$_GET['id']}' and date_created >= '{$ending_asof}'
									UNION ALL
									select date_created,amount,2 as type,
									(select
									concat(case
										when role = 1 then 'Operator'
										when role = 2 then 'Sub-Operator'
										when role = 3 then 'Master Agent'
										when role = 4 then 'Gold Agent'
										when role = 5 then 'Player'
										else ''
									end,' - ', username)
									from users where id = a.agent_id) accttyp,
									(select username from users where id = a.agent_id) processby from withdrawals a where active ='N' and user_id = '{$_GET['id']}' and date_created >= '{$ending_asof}'
									UNION ALL
									select date_created,amount,5 as type,
									(select
									concat(case
										when role = 1 then 'Operator'
										when role = 2 then 'Sub-Operator'
										when role = 3 then 'Master Agent'
										when role = 4 then 'Gold Agent'
										when role = 5 then 'Player'
										else ''
									end,' - ', username)
									from users where id = a.user_id) accttyp,
									(select username from users where id = a.agent_id) processby from loading a where active ='N' and agent_id = '{$_GET['id']}' and date_created >= '{$ending_asof}'
									UNION ALL
									select date_created,amount,6 as type,
									(select
									concat(case
										when role = 1 then 'Operator'
										when role = 2 then 'Sub-Operator'
										when role = 3 then 'Master Agent'
										when role = 4 then 'Gold Agent'
										when role = 5 then 'Player'
										else ''
									end,' - ', username)
									from users where id = a.user_id) accttyp,
									(select username from users where id = a.agent_id) processby from withdrawals a where active ='N' and agent_id = '{$_GET['id']}' and date_created >= '{$ending_asof}'
									UNION ALL

									select date_created, amount_converted as amount,3  as type,
									(select
									concat(case
										when role = 1 then 'Operator'
										when role = 2 then 'Sub-Operator'
										when role = 3 then 'Master Agent'
										when role = 4 then 'Gold Agent'
										when role = 5 then 'Player'
										else ''
									end,' - ', username)
									from users where id = a.agent_id) accttyp,
									(select username from users where id = a.agent_id) processby from coms_converted a where a.user_id = '{$_GET['id']}'

									UNION ALL
									select date_created, (earnings-red_amount-blue_amount-yellow_amount) as amount, 4 as type,concat((select drawno from draws where id = a.drawid),'-', (select name from events where id = (select eventid from draws where id = a.drawid))) accttyp,'' processby from bets a where user_id = '{$_GET['id']}' and date_created >= '{$ending_asof}'
									order by date_created asc
									");



						
					while($row = $qry->fetch_assoc()):
					?>
						<tr>
							<td class=""><?php echo $i++; ?></td>
                            				<td><span class="badge"><?php echo date("m-d-Y H:i", strtotime($row['date_created'])) ?></span></td>
							<?php if($row['type'] == 2): ?>
								<td>-<?php echo number_format($row['amount'],2) ?> </td>
							<?php elseif($row['type'] == 5): ?>
								<td>-<?php echo number_format($row['amount'],2) ?> </td>
							<?php else: ?>
								<td><?php echo number_format($row['amount'],2) ?> </td>
							<?php endif; ?>

								<?php if($row['type'] == 0): ?>
                                    					<?php $bal = $bal + $row['amount']?>
									<td><?php echo number_format($bal,2) ?></td>
									<td><span class="badge"><?php echo $type?></span></td>
								<?php elseif($row['type'] == 1): ?>
                                    					<?php $bal = $bal + $row['amount']?>
									<td><?php echo number_format($bal,2) ?></td>
									<td><span class="badge">Cash-In</span></td>
								<?php elseif($row['type'] == 2): ?>
                                    					<?php $bal = $bal - $row['amount']?>
									<td><?php echo number_format($bal,2) ?></td>
									<td><span class="badge">Cash-Out</span></td>
								<?php elseif($row['type'] == 3): ?>
                                    					<?php $bal = $bal + $row['amount']?>
									<td><?php echo number_format($bal,2) ?></td>
									<td><span class="badge">Commission</span></td>
								<?php elseif($row['type'] == 4): ?>
									<?php $bal = $bal + $row['amount']?>
									<td><?php echo number_format($bal,2) ?></td>
									<td><span class="badge">Winnings/Bets</span></td>
								<?php elseif($row['type'] == 5): ?>
                                    					<?php $bal = $bal - $row['amount']?>
									<td><?php echo number_format($bal,2) ?></td>
									<td><span class="badge">Cash-In (Downline)</span></td>
								<?php elseif($row['type'] == 6): ?>
                                    					<?php $bal = $bal + $row['amount']?>
									<td><?php echo number_format($bal,2) ?></td>
									<td><span class="badge">Cash-Out (Downline)</span></td>
								<?php else: ?>
									<?php $bal = $bal + $row['amount']?>
									<td><?php echo number_format($bal,2) ?></td>
									<td><span class="badge">Winnings/Bets</span></td>	
								<?php endif; ?>

							<td><?php echo $row['accttyp'] ?></td>
							<td><?php echo $row['processby'] ?></td>
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
				<a class="btn btn-sm btn-secondary" href="./?page=user/list">Close</a>
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
