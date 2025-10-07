<div class="card card-outline card-success">
	<div class="card-header bg-gray">
		<h3 class="card-title">Cashout History</h3>
	</div>
	<div class="card-body bg-dark">		  
		<div class="container-fluid">
        <div class="container-fluid">
			<table id ="example" class="table table-hover table-stripped">
				<thead>
					<tr>
					    <th>Date Created</th>
					    <th>Amount</th>
                	    <th>Processed By</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$id  = $_settings->userdata('id');
					$qry = $conn->query("SELECT a.date_created, a.amount, b.username as Requestor, c.firstname as Agent FROM withdrawals a JOIN users b ON a.user_id = b.id JOIN users c ON a.agent_id = c.id WHERE a.user_id = '$id'");
						while($row = $qry->fetch_assoc()):
                             ?>
                            <tr>
                            <td><?php echo date("M-d-Y h:i:sa", strtotime($row['date_created'])) ?></td>
							<td><?php echo $row['amount']?></td>
							<td><?php echo $row['Agent'] ?></td>
						<?php endwhile; ?>

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