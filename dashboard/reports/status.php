<?php
require_once('../classes/getWorkingDays.php');
?>
<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>
<?php
$date_start = isset($_GET['date_start']) ? $_GET['date_start'] : date("Y-m-d",strtotime(date('Y-m-d')));
$date_end =   isset($_GET['date_end']) ?   $_GET['date_end'] :   date("Y-m-d",strtotime(date('Y-m-d').' 1 days'));
$status = isset($_GET['status']) ? $_GET['status'] : '*';
?>
<div class="card card-outline card-primary">
	<div class="card-header">
		<h3 class="card-title">List of Transactions</h3>
		<div class="card-tools">
	
        </div>
	</div>
	<div class="card-body">




        <div class="row">
            <div class="col-4">
                <div class="form-group">
                    <label for="date_start" class="control-label">Date Start</label>
                    <input type="date" class="form-control" id="date_start" value="<?php echo date("Y-m-d",strtotime($date_start)) ?>">
                </div>
            </div>
            <div class="col-4">
                 <div class="form-group">
                    <label for="date_end" class="control-label">Date End</label>
                    <input type="date" class="form-control" id="date_end" value="<?php echo date("Y-m-d",strtotime($date_end)) ?>">
                </div>
            </div>
            
            <div class="col-4">
                 <div class="form-group">
                    <label for="status" class="control-label">Status</label>
                    <select name="status" id="status" class="form-control select2bs4 select2 rounded-0" required>
                        <option value="*" <?php echo isset($status) && $status == '*' ? "selected" : '' ?> >All</option>
                        <option value="ongoing" <?php echo isset($status) && $status == 'ongoing' ? "selected" : '' ?>>Ongoing</option>
                        <option value="finished" <?php echo isset($status) && $status == 'finished' ? "selected" : '' ?>>Finished</option>
                    </select>
                </div>
            </div>

            <div class="col-2 row align-items-end pb-1">
                <div class="w-100">
                    <div class="form-group d-flex justify-content-between align-middle">
                        <button class="btn btn-flat btn-default bg-lightblue" type="button" id="filter"><i class="fa fa-filter"></i> Filter</button>
                        <button class="btn btn-flat btn-success" type="button" id="print"><i class="fa fa-print"></i> Print</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
        <div class="container-fluid">
			<table class="table table-hover table-stripped" id="print_out">
				<colgroup>
					<col width="5%">
					<col width="10%">
					<col width="5%">
					<col width="10%">
					<col width="10%">
					<col width="5%">
					<col width="20%">
					<col width="15%">
					<col width="10%">
					<col width="10%">

					
				</colgroup>
				<thead>
					<tr>
					    <th>#</th>
					    <th>Tracking No.</th>
					    <th>Type</th>
					    <th>From</th>
					    <th>To</th>
					    <th>Diff</th>
					    <th>Description</th>
					    <th>Remarks</th>
					    <th>Source</th>
					    <th>Status</th>

					    
					</tr>
				</thead>
				<tbody>
					<?php 
					$i = 1;

                    $holidays = array();
                    $holiday_qry = $conn->query("SELECT name FROM holidays");
                        while($h_row = $holiday_qry->fetch_assoc()){
                        array_push($holidays,$h_row['name']);
                        }

                    $department_qry = $conn->query("SELECT id,name FROM department_list");
					$dept_arr = array_column($department_qry->fetch_all(MYSQLI_ASSOC),'name','id');
					
					$user_qry = $conn->query("SELECT id,username FROM users");
					$user_arr = array_column($user_qry->fetch_all(MYSQLI_ASSOC),'username','id');

						
                    
                    if ($status=='*'){
                        $qry = $conn->query("SELECT * from transactions where date_created BETWEEN '$date_start'  and '$date_end'");
                    }
                    elseif ($status=='ongoing'){
                        $qry = $conn->query("SELECT * from transactions where date_created BETWEEN '$date_start'  and '$date_end' and finished='N'");                
                    }
                    else{
                        $qry = $conn->query("SELECT * from transactions where date_created BETWEEN '$date_start'  and '$date_end' and finished='Y'");      
                    }
                    
                    
						while($row = $qry->fetch_assoc()):

                        $startDate =date("Y-m-d", strtotime($row['date_created']));
					?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
                            <td><?php echo $row['trackingno'] ?></td>
							
							<td class="">
								<?php if($row['type'] == 1): ?>
									<span>Simple</span>
								<?php elseif($row['type'] == 2): ?>
									<span>Complex</span>
								<?php else: ?>
									<span>Highly-Technical</span>
								<?php endif; ?>
							</td>
							
							<td><?php echo date("m-d-Y", strtotime($row['date_created'])) ?></td>
							
							<td>
								<?php if($row['finished'] == 'N'): ?>
									<?php echo date("m-d-Y") ?>
								<?php else: ?>
									<?php echo date("m-d-Y", strtotime($row['date_updated'])) ?>
								<?php endif; ?>
							</td>

							<td class="">
                                <?php if($row['finished'] == 'Y'){
									$endDate =date("Y-m-d", strtotime($row['date_updated'])); 
                                } else{
                                    $endDate = date("Y-m-d");
                                } 
                                ?>    
                                <?php if($row['type'] == 1): ?>
                                    <?php if((3-(getWorkingDays($startDate,$endDate,$holidays)-1))>0): ?>
                                    <span class="badge badge-primary"><?php echo 3-(getWorkingDays($startDate,$endDate,$holidays)-1) ; ?></span>
                                    <?php else: ?>
                                    <span class="badge badge-danger"><?php echo 3-(getWorkingDays($startDate,$endDate,$holidays)-1) ; ?></span>
                                    <?php endif; ?>
								<?php elseif($row['type'] == 2): ?>
                                    <?php if((7-(getWorkingDays($startDate,$endDate,$holidays)-1))>0): ?>
                                    <span class="badge badge-primary"><?php echo 7-(getWorkingDays($startDate,$endDate,$holidays)-1) ; ?></span>
                                    <?php else: ?>
                                    <span class="badge badge-danger"><?php echo 7-(getWorkingDays($startDate,$endDate,$holidays)-1) ; ?></span>
                                    <?php endif; ?>
								<?php else: ?>
                                    <?php if((15-(getWorkingDays($startDate,$endDate,$holidays)-1))>0): ?>
                                    <span class="badge badge-primary"><?php echo 15-(getWorkingDays($startDate,$endDate,$holidays)-1) ; ?></span>
                                    <?php else: ?>
                                    <span class="badge badge-danger"><?php echo 15-(getWorkingDays($startDate,$endDate,$holidays)-1) ; ?></span>
                                    <?php endif; ?>
								<?php endif; ?>
							</td>
							
							<td><?php echo $row['description'] ?></td>
							<td><?php echo $row['remarks'] ?></td>
                            <td ><?php echo isset($dept_arr[$row['source']]) ? $dept_arr[$row['source']] : 'N/A' ?></td>
 							
                            <td class="">
								<?php if($row['finished'] == 'N'): ?>
									<span>Ongoing</span>
								<?php else: ?>
									<span>Finished</span>
								<?php endif; ?>
							</td>



						</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>

		</div>
</div>
</div>
<script>
$(document).ready(function(){
        $('#filter').click(function(){
            location.replace("./?page=reports/status&date_start="+($('#date_start').val())+"&date_end="+($('#date_end').val()) +"&status="+($('#status').val()) );
        })
        
        $('#print').click(function(){
            start_loader()
            var _h = $('head').clone()
            var _p = $('#print_out').clone();
            var _el = $('<div>')
            _el.append(_h)
            _el.append('<style>html, body, .wrapper {min-height: unset !important;}</style>')
            
            var rdate="";
            rdate = "<?php echo date("M d, Y", strtotime($date_start)) ?> - <?php echo date("M d, Y", strtotime($date_end)) ?>";
            _p.prepend('<div class="d-flex mb-3 w-100 align-items-center justify-content-center">'+
            '<img class="mx-4" src="<?php echo validate_image($_settings->info('logo')) ?>" width="50px" height="50px"/>'+
            '<div class="px-2">'+
            '<h3 class="text-center"><?php echo $_settings->info('name') ?></h3>'+
            '<h3 class="text-center">STATUS REPORT</h3>'+
            '<h5 class="text-center">'+rdate+'</h5>'+
            '</div>'+
            '</div><hr/>');
            _el.append(_p)
            var nw = window.open("","_blank","width=1200,height=1200")
                nw.document.write(_el.html())
                nw.document.close()
                setTimeout(() => {
                    nw.print()
                    setTimeout(() => {
                        nw.close()
                        end_loader()
                    }, 300);
                }, 500);
        })
        
        
})
</script>

<script>
	$(document).ready(function(){
		$('.table').dataTable({
			columnDefs: [
				{ orderable: false, targets: [11] }
			]
		});
	})
</script> 