<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>

<?php
$department_qry = $conn->query("SELECT id,name FROM department_list");
$dept_arr = array_column($department_qry->fetch_all(MYSQLI_ASSOC),'name','id');
?>
 <?php
$office2 = isset($_GET['office2']) ? $_GET['office2'] : '*';
?>
<div class="card card-outline card-primary">
	    <div class="card-header">
	    <h3 class="card-title">Forwarded Transactions</h3>
        <div class="card-tools">
	    </div>
	    </div>
<div class="card-body">
        <div class="row">
            <div class="col-4">
                <div class="form-group">
                <select name="office2" id="office2" class="form-control select2bs4 select2 rounded-0" required>
                <option value="*" <?php echo isset($office2) && $office2 == '*' ? "selected" : '' ?>>All</option>
                <?php foreach($dept_arr as $k=>$v): ?>
                <option value="<?php echo $k ?>"    <?php echo isset($office2) && $office2 == $k ? "selected" : '' ?>        ><?php echo $v ?></option>
                <?php endforeach; ?>
                </select>
                </div>
            </div>
            <div class="col-4">
                 <div class="form-group">
                 <button class="btn btn-flat btn-default bg-lightblue" type="button" id="filter"><i class="fa fa-filter"></i> Filter</button>
                 <button class="btn btn-flat btn-success" type="button" id="print"><i class="fa fa-print"></i> Print</button>
                 </div>
            </div>
        </div>
        
<div class="container-fluid">
<div class="container-fluid">


			<table class="table table-hover table-stripped" id="print_out">
				<colgroup>
					<col width="5%">
					<col width="5%">
					<col width="5%">
					<col width="10%">
					<col width="20%">
					<col width="15%">
					<col width="10%">
					<col width="5%">
					<col width="10%">
					<col width="10%">
					<col width="5%">
				</colgroup>
				<thead>
					<tr>
					    <th>#</th>
					    <th>Tracking No.</th>
					    <th>Type</th>
					    <th>Date</th>
					    <th>Description</th>
					    <th>Remarks</th>
					    <th>Source</th>
					    <th>Action</th>
                                            <th>Dest.</th>
                                            <th>Signature</th>
                                            <th>Date</th>

					</tr>
				</thead>
				<tbody>
					<?php 
					$i = 1;

					$department_qry = $conn->query("SELECT id,name FROM department_list");
					$dept_arr = array_column($department_qry->fetch_all(MYSQLI_ASSOC),'name','id');
					
					$user_qry = $conn->query("SELECT id,username FROM users");
					$user_arr = array_column($user_qry->fetch_all(MYSQLI_ASSOC),'username','id');


                                        if ($office2=='*'){
                                        $qry = $conn->query("SELECT * from transactions where (office1='{$_settings->userdata('department_id')}' and status ='2' and finished='N')");
                                        }
                                        else{
                                        $qry = $conn->query("SELECT * from transactions where (office2 = '{$office2}' and office1='{$_settings->userdata('department_id')}' and status ='2' and finished='N')");
                                        }
                                        
                                        while($row = $qry->fetch_assoc()):
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
							<td><?php echo $row['description'] ?></td>
							<td><?php echo $row['remarks'] ?></td>

                                                        <td ><?php echo isset($dept_arr[$row['office1']]) ? $dept_arr[$row['office1']] : 'N/A' ?></td>
                                                        <td class="">
								<?php if($row['status'] == '1'): ?>
									<span>received from</span>
								<?php else: ?>
									<span>forwarded to</span>
								<?php endif; ?>
							</td>
                                                        <td ><?php echo isset($dept_arr[$row['office2']]) ? $dept_arr[$row['office2']] : 'N/A' ?></td>
                                                        <td></td>
                                                        <td></td>

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
		$('.table').dataTable({
			columnDefs: [
				{ orderable: false, targets: [11] }
			]
		});
	})
</script> 
<script>
	$(document).ready(function(){
        $('#filter').click(function(){
            location.replace("./?page=reports&office2="+($('#office2').val()));
        })
        $('#print').click(function(){
            start_loader()
            var _h = $('head').clone()
            var _p = $('#print_out').clone();
            var _el = $('<div>')
            _el.append(_h)
            _el.append('<style>html, body, .wrapper {min-height: unset !important;}</style>')
            _p.prepend('<div class="d-flex mb-3 w-100 align-items-center justify-content-center">'+
            '<img class="mx-4" src="<?php echo validate_image($_settings->info('logo')) ?>" width="50px" height="50px"/>'+
            '<div class="px-2">'+
            '<h3 class="text-center"><?php echo $_settings->info('name') ?></h3>'+
            '<h3 class="text-center">TRANSMITTAL REPORT</h3>'+
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

