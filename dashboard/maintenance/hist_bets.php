<?php
	require_once '../classes/UserInfo.php';
	require_once '../classes/UserBalance.php';
	require_once '../classes/BetInfo.php';
	
	$userInfo = new UserInfo($conn);
	$userBalance = new UserBalance($conn, $_settings->userdata('id'), $_settings->userdata('type'));
	$betInfo = new BetInfo($conn);

	$betHistoryOfUser = $betInfo->getBetHistoryOfUser($_settings->userdata('id'));

    $curbal = $userBalance->getUserBalance($_settings->userdata('id'));
?>

<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>

<style>
	.breadcrumb-item.active {
		color: #fff;
	}
	.breadcrumb-item a {
		color: #ffec00;
	}
	.breadcrumb-item li {
		font-size: 1rem !important;
	}
	.breadcrumb-item-cashin.active {
		color: #6c757d;
	}
	.breadcrumb-item-cashin a {
		color: #007bff;
	}
	.breadcrumb-item-cashin li {
		font-size: 1rem !important;
	}
	
	.dropbtn {
		background-color: #4CAF50;
		color: white;
		padding: 16px;
		font-size: 16px;
		border: none;
		cursor: pointer;
	}
	.content-color {
		background-color: black;
	}
	table.dataTable th, table.dataTable td {
		padding: 0.75rem !important;
	}

	

</style>

<nav aria-label="breadcrumb">
	<ol class="breadcrumb my-0 pb-3" style="background-color: transparent;">
		<li class="breadcrumb-item" style="font-size: 1rem"><a href="#">Dashboard</a></li>
		<li class="breadcrumb-item active" style="font-size: 1rem" aria-current="page">Load Logs</li>
	</ol>
</nav>

<div class="content-wrapper" style="background-color: #f4f6f9 !important;">
	<div class="pt-4"></div>
	<div class="card card-info card-v2"  style="margin-left: 2%; margin-right: 2%; color: #212529 !important;">
		<div class="card-header">
			<h3 class="card-title ">
				<i class="fas fa-align-justify "></i>   
				<span class="text-white"> Betting History
			</h3>
		</div>
		<div class="card-body " style="padding: 0px;">
			<form action="" method="POST">                    
				<div class="row justify-content-center">
					<div class="col-sm-12">
						<div class="form-group px-3">

							<label>Username:</label>
							<div class="input-group ">
								<input type="text" name="search" class="form-control" id="searchUsername" placeholder="Search">
								<span class="input-group-append">
									<button type="submit" value="Search" class="btn btn-primary ">  SEARCH</button>
								</span>
							</div>
						</div>
					</div>
				</div>
			</form>               
				
				<table id="example1" class="table table-bordered table-striped  ">
					<thead>
						<tr>
							<th>Date</th>
							<th>Username</th>
							<th>Fight# - Event</th>
							<th>Meron/Pula</th>
							<th>Wala/Asul</th>
							<th>Draw</th>
							<th>Result</th>
							<th>Earned</th>
							<th>Balance</th>
							<th>Account Type/Fight</th>
						</tr>
					</thead>
					<tbody>
						<?php
							if (!empty($betHistoryOfUser) && is_array($betHistoryOfUser)) {
								// 1. Reverse the array to show NEWEST transaction first, as done in the JS logic
								$transactions = array_reverse($betHistoryOfUser); 

								foreach ($transactions as $row) {
									$formattedDate = date('m-d-Y H:i:s', strtotime($row['date_created']));
									$formattedBalance = number_format((float)$row['current_balance'], 2, '.', ',');
									$type = (int)$row['type'];

									// --- A. Process Amount Display & Status (Replicating JS logic) ---
									$amount = (float)($row['amount'] ?? 0);
									$amountDisplay = number_format(abs($amount), 2);
									$winnerStatus = '';
									
									if (in_array($type, [2, 5]) && $amount > 0) {
										// Debits (Cash-Out, Cash-In Downline) are displayed with a negative sign
										$amountDisplay = '-' . $amountDisplay;
									}

									// --- B. Determine Winner Badge (Column 7) and Win/Lose Status (Column 8) ---
									$winnerBadge = '<span class="badge badge-light">N/A</span>';
									
									if ($type === 4) { // Only applies to Winnings/Bets (type 4)
										// Need drawno, winner, red/blue/yellow amounts here, but the data array lacks 'drawno' and 'winner' 
										// from the bets UNION part. Assuming the full bets data is available if needed.
										// For a clean conversion, we rely on the amount sign:
										
										// NOTE: If the SQL was updated to include 'drawno' and 'winner' in the type 4 union, 
										// you'd need to fetch those fields (e.g., $row['drawno']) here.
										
										$winner = strval($row['winner'] ?? '0'); // Assuming winner is fetched in the SQL UNION ALL
										
										if ($winner === '1') $winnerBadge = '<span class="badge badge-danger">Meron/Pula</span>';
										else if ($winner === '2') $winnerBadge = '<span class="badge badge-primary">Wala/Asul</span>';
										else if ($winner === '3') $winnerBadge = '<span class="badge badge-success">Draw</span>';
										else if ($winner === '4') $winnerBadge = '<span class="badge badge-light">Cancelled</span>';

										// Win/Lose status for the Amount column (Column 8)
										if ($amount < 0) {
											$winnerStatus = ' <span class="badge badge-danger">Lose</span>';
											$amountDisplay = number_format(abs($amount), 2); // Show loss as positive, then append Lose badge
										} else if ($amount > 0) {
											$winnerStatus = ' <span class="badge badge-success">Win</span>';
											$amountDisplay = number_format($amount, 2); 
										}
									}
									
									// Column 10: Account Type/Process By (Combined)
									// The SQL calculated accttyp and processby, so we use them directly.
									$accountProcessContent = ($row['accttyp'] && $row['accttyp'] !== 'N/A') ? $row['accttyp'] : ($row['processby'] ?: 'N/A');

									echo '<tr>';
									// Column 1: Date
									echo '<td><span>' . htmlspecialchars($formattedDate) . '</span></td>'; 
									// Column 2: User Name
									echo '<td class="">' . htmlspecialchars($curbal["username"]) . '</td>'; // Use the external $userName variable
									// Column 3: Type / Drawno
									echo '<td>' . htmlspecialchars($row['transaction_type_name']) . '</td>'; 
									
									// Columns 4, 5, 6: Red, Blue, Yellow Amounts (Assumes these fields are fetched in the SQL UNION ALL)
									// The JS used parseFloat(row.red_amount || 0).toFixed(2), so we'll fetch them from the row if available.
									echo '<td>' . htmlspecialchars(number_format((float)($row['red_amount'] ?? 0), 2)) . '</td>';
									echo '<td>' . htmlspecialchars(number_format((float)($row['blue_amount'] ?? 0), 2)) . '</td>';
									echo '<td>' . htmlspecialchars(number_format((float)($row['yellow_amount'] ?? 0), 2)) . '</td>';

									// Column 7: Winner Badge
									echo '<td>' . $winnerBadge . '</td>'; 
									// Column 8: Amount + Win/Lose Badge
									echo '<td>' . htmlspecialchars($amountDisplay) . $winnerStatus . '</td>'; 
									// Column 9: Running Balance
									echo '<td>' . htmlspecialchars($formattedBalance) . '</td>'; 
									
									// Column 10: Account Type/Process By (Combined)
									echo '<td>' . htmlspecialchars($accountProcessContent) . '</td>'; 

									echo '</tr>';
								}
							} else {
								// No data found
								echo '<tr><td colspan="10" class="text-center">No transaction history found for this user.</td></tr>';
							}
							?>
					</tbody>
					<tfoot>
						<tr>
							<th>Date</th>
							<th>Username</th>
							<th>Fight# - Event</th>
							<th>Meron/Pula</th>
							<th>Wala/Asul</th>
							<th>Draw</th>
							<th>Result</th>
							<th>Earned</th>
							<th>Balance</th>
							<th>Account Type/Fight</th>
						</tr>
					</tfoot>

			</table>
			
		</div>


	</div>
</div>

<script>
	$(document).ready(function(){

		var loadLogsTable = $('#example1').DataTable({
			lengthChange: false,
			dom: 'lrtip',
			stateSave: true,
			"order": [
				[0, "desc"]
			]
		});
		
		$('#searchUsername').val('');
		loadLogsTable.search('').draw();

        $('#searchUsername').on('keyup', function() {
            var searchValue = $(this).val();
            loadLogsTable.search(searchValue).draw();
        });

	})

</script>
