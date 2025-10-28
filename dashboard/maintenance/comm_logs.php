<?php
	require_once '../classes/UserBalance.php';
	require_once '../classes/BetInfo.php';
	
	$userBalance = new UserBalance($conn, $_settings->userdata('id'), $_settings->userdata('type'));
	$betInfo = new BetInfo($conn);

	$commLogsOfAgent = $betInfo->getCommLogsOfAgent($_settings->userdata('id'));

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
		<li class="breadcrumb-item active" style="font-size: 1rem" aria-current="page">Comm Logs</li>
	</ol>
</nav>

<div class="content-wrapper" style="background-color: #f4f6f9 !important;">
	<div class="pt-4"></div>
	<div class="card card-info card-v2"  style="margin-left: 2%; margin-right: 2%; color: #212529 !important;">
		<div class="card-header">
			<h3 class="card-title ">
				<i class="fas fa-align-justify "></i>   
				<span class="text-white"> Your Commission: </span> ( <?php echo number_format($curbal['com_amount_bal'], 2)?> )
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
							<th>Fight #</th>
							<th>Event</th>
							<th>Player</th>
							<th>Agent</th>
							<th>Bet</th>
							<th>Commission</th>
							<th>Balance</th>
							<th>% Earn</th>
						</tr>
					</thead>
					<tbody>
						<?php
							foreach($commLogsOfAgent as $row):?>
								<tr>
									<td><?php echo $row['DATE_formatted'] ?></td>
									<td><?php echo $row['FIGHT#']?></td>
									<td><?php echo $row['EVENT']?></td>
									<td><?php echo $row['USERNAME']?></td>
									<td><?php echo $row['AGENT']?></td>
									<td><?php echo $row['BET_formatted'] ?></td>
									<td><?php echo $row['COMMISSION_formatted']?></td>
									<td><?php echo $row['RUNNING_BALANCE_formatted']?></td>
									<td><?php echo $row['%EARN']?></td>
								</tr>
							<?php endforeach; 
						?>
					</tbody>
					<tfoot>
						<tr>
							<th>Date</th>
							<th>Fight #</th>
							<th>Event</th>
							<th>Player</th>
							<th>Agent</th>
							<th>Bet</th>
							<th>Commission</th>
							<th>Balance</th>
							<th>% Earn</th>
						</tr>
					</tfoot>

			</table>
			
		</div>


	</div>
</div>

<script>
	$(document).ready(function(){

		var commLogsTable = $('#example1').DataTable({
			lengthChange: false,
			dom: 'lrtip',
			stateSave: true,
			"order": [
				[0, "desc"]
			]
		});
		
		$('#searchUsername').val('');
		commLogsTable.search('').draw();

        $('#searchUsername').on('keyup', function() {
            var searchValue = $(this).val();
            commLogsTable.search(searchValue).draw();
        });

	})

</script>
<?php endif;?>
