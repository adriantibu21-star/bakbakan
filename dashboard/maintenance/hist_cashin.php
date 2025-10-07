<div class="card card-outline card-success">
	<div class="card-header bg-gray">
		<h3 class="card-title">Cashin History</h3>
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
					$qry = $conn->query("SELECT a.id,a.username as 'Requestor' ,b.amount, b.date_created, c.firstname as Processor FROM users a  INNER JOIN loading b ON a.id = b.user_id INNER JOIN users c ON c.id = b.agent_id WHERE a.id = '$id'");
						while($row = $qry->fetch_assoc()):
                             ?>
                            <tr>
                            <td><?php echo date("M-d-Y h:i:sa", strtotime($row['date_created'])) ?></td>
							<td><?php echo $row['amount']?></td>
							<td><?php echo $row['Processor'] ?></td>
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











