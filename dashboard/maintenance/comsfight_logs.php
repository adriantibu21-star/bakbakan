<div class="card card-outline card-success">
	<div class="card-header bg-gray">
		<h3 class="card-title">Commission Fight Logs</h3>
	</div>
	<div class="card-body bg-dark">		  
		<div class="container-fluid">
        <div class="container-fluid">
			<table id ="example" class="table table-hover table-stripped">
				<thead>
					<tr>
					    <th>Date</th>
					    <th>Fight #</th>
					    <th>Event</th>
					    <th>Player</th>
					    <th>Agent</th>
					    <th>Bet</th>
                	    		    <th>Commission</th>
					    <th>% Earn</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$id  = $_settings->userdata('id');
					$qry = $conn->query("SELECT 
						a.date_created as 'DATE',
						(select drawno from draws where id = a.drawid) as 'FIGHT#',
						(select name from events where id = (select eventid from draws where id = a.drawid)) 'EVENT',
						(select username from users where id = a.user_id) as 'USERNAME',
						(select username from users where id = b.user_id) as 'AGENT',
						a.blue_amount + a.red_amount + a.yellow_amount as 'BET',
						b.amount as 'COMMISSION',
						concat(b.com_rate, '% ',
							(select
							case
								when (select parentid from users where id = a.user_id) = b.user_id then 'Earn direct player'
								when type = 1 then 'Earn Operator'
								when type = 2 then 'Earn Sub-Operator'
								when type = 3 then 'Earn Master Agent'
								when type = 4 then 'Earn Gold Agent'
								when type = 5 then 'Earn Player'
								else ''
							end
							from users where id = b.user_id) ) as '%EARN'
						FROM bets a
						INNER JOIN coms b
						ON b.drawid = a.drawid and b.player_id = a.user_id
						WHERE b.user_id = '$id' and b.active = 'Y'");

						while($row = $qry->fetch_assoc()):
                             			?>
						<tr>
                      				<!-- <td><?php echo date("M-d-Y h:i:sa", strtotime($row['DATE'])) ?></td> -->
									<td><?php echo date("M-d-Y H:i:s", strtotime($row['DATE'])) ?></td>
                            			<td><?php echo $row['FIGHT#']?></td>
                            			<td><?php echo $row['EVENT']?></td>
						<td><?php echo $row['USERNAME']?></td>
						<td><?php echo $row['AGENT']?></td>
						<td><?php echo number_format($row['BET'],2) ?></td>
						<td><?php echo number_format($row['COMMISSION'],2)?></td>
                            			<td><?php echo $row['%EARN']?></td>
						<?php endwhile;?>

				</tbody>
				
			</table>
			
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








