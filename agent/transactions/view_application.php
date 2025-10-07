<?php
require_once('../../config.php');
if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT * from tracking where transactions_id = '{$_GET['id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
    }
}
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
			<table class="table" id ="table">
				<colgroup>
					<col width="5%">
					<col width="20%">
					<col width="20%">
					<col width="10%">
					<col width="20%">
                                        <col width="15%">
                                        <col width="10%">
				</colgroup>
				<thead>
					<tr>
					    <th>#</th>
					    <th>Date</th>
					    <th>Office</th>
					    <th>Action</th>
					    <th>Office</th>
					    <th>Status</th>
					    <th>User</th>

					</tr>
				</thead>
				<tbody>

					<?php
					$i = 1;
					$department_qry = $conn->query("SELECT id,name FROM department_list");
					$dept_arr = array_column($department_qry->fetch_all(MYSQLI_ASSOC),'name','id');

					$user_qry = $conn->query("SELECT id,username FROM users");
					$user_arr = array_column($user_qry->fetch_all(MYSQLI_ASSOC),'username','id');

						$qry = $conn->query("SELECT * from tracking where transactions_id = '{$_GET['id']}'");
						while($row = $qry->fetch_assoc()):
					?>
						<tr>
							<td class=""><?php echo $i++; ?></td>
                                                        <td><?php echo date("m-d-Y", strtotime($row['date_created'])) ?></td>

                                                        <td ><?php echo isset($dept_arr[$row['office1']]) ? $dept_arr[$row['office1']] : 'N/A' ?></td>

                                                        <td class="">
								<?php if($row['status'] == 1): ?>
									<span >received from</span>
								<?php elseif($row['status'] == 2): ?>
									<span>forwarded to</span>
								<?php else: ?>
									<span class="badge badge-danger">Invalid</span>
								<?php endif; ?>
							</td>
							<td ><?php echo isset($dept_arr[$row['office2']]) ? $dept_arr[$row['office2']] : 'N/A' ?></td>

                                                        <td class="">
								<?php if($row['finished'] == 'N'): ?>
									<span class="badge badge-success">Ongoing</span>
								<?php elseif($row['finished'] == 'Y'): ?>
									<span class="badge badge-primary">Finished</span>
								<?php else: ?>
									<span class="badge badge-danger">Invalid</span>
								<?php endif; ?>
							</td>
							<td ><?php echo isset($user_arr[$row['user_id']]) ? $user_arr[$row['user_id']] : 'N/A' ?></td>
						</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
    <div class="w-100 d-flex justify-content-end mb-2">
         <button class="btn" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>

    </div>
</div>












