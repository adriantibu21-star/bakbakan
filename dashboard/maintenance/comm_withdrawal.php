<?php
	require_once '../classes/BetInfo.php';
	
	$betInfo = new BetInfo($conn);
	
	$withdrawal_data = $betInfo->getCommWithdrawalOfAgent($_settings->userdata('id'),$_settings->userdata('type'));
	$commWithdrawalOfAgent = $withdrawal_data['rows'];
	$total_withdrawal_sum = $withdrawal_data['total_withdrawal_sum'];
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
		<li class="breadcrumb-item active" style="font-size: 1rem" aria-current="page">Commission History</li>
	</ol>
</nav>

<div class="content-wrapper" style="background-color: #f4f6f9 !important;">
	<div class="pt-4"></div>
	<div class="card card-info card-v2"  style="margin-left: 2%; margin-right: 2%; color: #212529 !important;">
		<div class="card-header">
			<h3 class="card-title ">
				<i class="fas fa-align-justify "></i>   
				<span class="text-white"> Total Commission: </span> ( <?php echo number_format($total_withdrawal_sum, 2)?> )
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
							<th>Username</th>
							<th>Processed By</th>
							<th>Points</th>
							<th>Date of Withdrawal</th>
						</tr>
					</thead>
					<tbody>
						<?php
							foreach($commWithdrawalOfAgent as $row):?>
								<tr>
									<td><?php echo $row['USER_USERNAME'] ?></td>
									<td><?php echo $row['AGENT_USERNAME']?></td>
									<td><?php echo $row['AMOUNT_converted']?></td>
									<td><?php echo $row['DATE']?></td>
								</tr>
							<?php endforeach; 
						?>
					</tbody>
					<tfoot>
						<tr>
							<th>Username</th>
							<th>Processed By</th>
							<th>Points</th>
							<th>Date of Withdrawal</th>
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
				[3, "desc"]
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
