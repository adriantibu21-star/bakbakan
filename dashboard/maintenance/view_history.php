<?php if($_settings->userdata('type') == 1 or $_settings->userdata('type') == 2): ?>
<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>

<?php
require_once('../../config.php');
?>
<style>
    p,label{
        margin-bottom:5px;
    }
    #uni_modal .modal-footer{
        display:none !important;
    }
</style>
<div class="container-fluid">

			<table  class="table table-hover table-stripped" id ="table">
				<thead>
					<tr>
					    <th>#</th>
					    <th>Date</th>
					    <th>Amount</th>
					    <th>Balance</th>
					    <th>Type</th>
					    <th>AccountType/Fight</th>
					    <th>Process By</th>
					</tr>
				</thead>
				<tbody>

					<?php
					$i = 1;
                    $bal=0;




			$qryBeginingbalance = $conn->query("SELECT ending_asof, amount from endingbalance where user_id = '{$_GET['id']}' order by ending_asof desc limit 1");

			if($checqry ->num_rows > 0){

				$rowevent = $checkevent->fetch_assoc();
				$ending_asof = $rowevent['ending_asof']; 
				$ending_amount = $rowevent['amount'];

			}

                        $qry = $conn->query(" select '{$ending_asof}' date_created, '{$ending_amount}', 0 as type, 'NA' accttyp, 'NA' processby
									UNION ALL
									select date_created,amount,1 as type,
									(select
									case
										when type = 1 then 'Financer Account'
										when type = 2 then 'Operator'
										when type = 3 then 'Sub-Operator'
										when type = 4 then 'Master Agent'
										when type = 5 then 'Player'
										else ''
									end
									from users where id = a.agent_id) accttyp,
									(select username from users where id = a.agent_id) processby from loading a where active ='N' and user_id = '{$_GET['id']}' and date_create >= '{$ending_asof}'
									UNION ALL
									select date_created,amount,2 as type,
									(select
									case
										when type = 1 then 'Financer Account'
										when type = 2 then 'Operator'
										when type = 3 then 'Sub-Operator'
										when type = 4 then 'Master Agent'
										when type = 5 then 'Player'
										else ''
									end
									from users where id = a.agent_id) accttyp,
									(select username from users where id = a.agent_id) processby from withdrawals a where active ='N' and user_id = '{$_GET['id']}' and date_create >= '{$ending_asof}'
									UNION ALL

									select date_created,sum(amount) amount,3  as type,
									(select
									case
										when type = 1 then 'Financer Account'
										when type = 2 then 'Operator'
										when type = 3 then 'Sub-Operator'
										when type = 4 then 'Master Agent'
										when type = 5 then 'Player'
										else ''
									end
									from users where id = a.agent_id) accttyp,
									(select username from users where id = a.agent_id) processby from coms a where active ='N' and user_id = '{$_GET['id']}' and date_create >= '{$ending_asof}' group by convert(date_created,date), eventid

									UNION ALL
									select date_created, (earnings-red_amount-blue_amount-yellow_amount) as amount, 4 as type,'' accttyp,'' processby from bets a where user_id = '{$_GET['id']}' and date_create >= '{$ending_asof}'
									order by date_created asc
									");



						
					while($row = $qry->fetch_assoc()):
					?>
						<tr>
							<td class=""><?php echo $i++; ?></td>
                            				<td><span class="badge"><?php echo date("m-d-Y H:i", strtotime($row['date_created'])) ?></span></td>
							<?php if($row['type'] == 2): ?>
								<td>-<?php echo number_format($row['amount'],2) ?> </td>
							<?php else: ?>
								<td><?php echo number_format($row['amount'],2) ?> </td>
							<?php endif; ?>


								<?php if($row['type'] == 0): ?>
                                    					<?php $bal = $bal + $row['amount']?>
									<td><?php echo number_format($bal,2) ?></td>
									<td><span class="badge">Beginning Balance</span></td>                  
								<?php if($row['type'] == 1): ?>
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
            <h7 style="color:green">Running Bal: <?php echo number_format($bal,2) ?></h7></br>

    <div class="w-100 d-flex justify-content-end mb-8">
         <button class="btn" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>

    </div>
</div>
<script>
	$(document).ready(function(){
	})
</script>


<?php endif;?>







