<?php
	require_once '../classes/UserInfo.php';
	require_once '../classes/UserBalance.php';
	require_once '../classes/BetInfo.php';
	
	$userInfo = new UserInfo($conn);
	$userBalance = new UserBalance($conn, $_settings->userdata('id'), $_settings->userdata('type'));
	$betInfo = new BetInfo($conn);

	$playersUnderAgent = $userInfo->getAllAgentsUnderAgent($_settings->userdata('id'), $_settings->userdata('type'));
	$loadLogsOfAgent = $betInfo->getLoadLogsOfAgent($_settings->userdata('id'));

    $curbal = $userBalance->getUserBalance($_settings->userdata('id'));
?>

<?php if($_settings->userdata('type') == 1 or $_settings->userdata('type') == 2): ?>
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
				<span class="text-white"> Current Points: </span> ( <?php echo number_format($curbal['amount'], 2)?> )
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
							<th>Date Loaded</th>
							<th>Amount</th>
							<th>Balance</th>
							<th>Type</th>
							<th>Account Type/Fight</th>
							<th>Process By</th>
						</tr>
					</thead>
					<tbody>
						<?php
							if (!empty($loadLogsOfAgent)) {
								foreach ($loadLogsOfAgent as $loadLog) {
									?>
									
									<?php
								}
							}
						?>  
						<?php
							if (!empty($loadLogsOfAgent) && is_array($loadLogsOfAgent)) {
								$transactions = array_reverse($loadLogsOfAgent); 

								foreach ($transactions as $loadLog) {
									$formattedDate = date('m-d-Y H:i:s', strtotime($loadLog['date_created']));
									$amountDisplay = $loadLog['display_amount'];
									$formattedBalance = number_format((float)$loadLog['current_balance'], 2, '.', ',');
									$badgeClass = 'badge-info'; 
									switch ((int)$loadLog['type']) {
										case 0: // Beginning Balance
											$badgeClass = 'badge-secondary';
											break;
										case 1: // Cash-In
										case 3: // Commission
										case 4: // Winnings (if data has pre-calculated wins)
										case 6: // Cash-Out (Downline - credit for the agent)
											$badgeClass = 'badge-success';
											break;
										case 2: // Cash-Out
										case 5: // Cash-In (Downline - debit for the agent)
											$badgeClass = 'badge-danger';
											break;
									}
									$transactionType = $loadLog['transaction_type_name'];
									
									echo '<tr>';
									echo '<td><span>' . htmlspecialchars($formattedDate) . '</span></td>';
									echo '<td class="text-right">' . htmlspecialchars($amountDisplay) . '</td>';
									echo '<td class="text-right">' . htmlspecialchars($formattedBalance) . '</td>';
									echo '<td class="text-center"><span class="badge ' . htmlspecialchars($badgeClass) . '">' . htmlspecialchars($transactionType) . '</span></td>';
									echo '<td>' . htmlspecialchars($loadLog['accttyp']) . '</td>';
									echo '<td>' . htmlspecialchars($loadLog['processby']) . '</td>';
									echo '</tr>';
								}
							} else {
								// No data found
								echo '<tr><td colspan="6" class="text-center">No transaction history found for this agent.</td></tr>';
							}
						?>
					</tbody>
					<tfoot>
						<tr>
							<th>Date Loaded</th>
							<th>Amount</th>
							<th>Balance</th>
							<th>Type</th>
							<th>Account Type/Fight</th>
							<th>Process By</th>
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
<?php endif;?>
