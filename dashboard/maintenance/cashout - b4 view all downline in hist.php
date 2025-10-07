<?php if($_settings->userdata('type') == 2 or $_settings->userdata('type') == 1): ?>

<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>

<?php //for search
if($_settings->userdata('type') == 2){

$y = $conn->query("SELECT GROUP_CONCAT(lv SEPARATOR ',') as id #lv
FROM (SELECT @pv :=(SELECT GROUP_CONCAT(id SEPARATOR ',') FROM users WHERE FIND_IN_SET(parentid, @pv)) AS lv 
FROM users JOIN (SELECT @pv := '{$_settings->userdata('id')}') tmp) tmp2"); 
if($y->num_rows > 0){
	$row1 = $y->fetch_assoc();
	
	if(!is_null($row1['id'])){ //palaging me
	$myarray = explode(',', $row1['id']); 
	$count=0;
	foreach($myarray as $val) {
		if ($count == 0){
			$sq="SELECT usercode,username from users where id =". $val;
		}else{
			$sq .= " or id= ".$val;
		}
		$count++;	
	}
	}else{
		$sq="SELECT usercode,username FROM users where id=0";
	}
	$sq.= " order by username";
	$b = $conn->query($sq);
	$b_arr = array_column($b->fetch_all(MYSQLI_ASSOC),'username','usercode'); 
}

}else{
$agent_qry = $conn->query("SELECT usercode,username FROM users where id <> '{$_settings->userdata('id')}' order by username");
$b_arr = array_column($agent_qry->fetch_all(MYSQLI_ASSOC),'username','usercode');
}
?>

<?php //for combobox
if($_settings->userdata('type') == 2){
$x = $conn->query("SELECT GROUP_CONCAT(lv SEPARATOR ',') as id #lv
FROM (SELECT @pv :=(SELECT GROUP_CONCAT(id SEPARATOR ',') FROM users WHERE FIND_IN_SET(parentid, @pv)) AS lv 
FROM users JOIN (SELECT @pv := '{$_settings->userdata('id')}') tmp) tmp2"); 

if($x->num_rows > 0){
	$row = $x->fetch_assoc();
	if(!is_null($row['id'])){ //palaging me
	$arrayko = explode(',', $row['id']); 
	$counter=0;
	foreach($arrayko as $value) {
		if ($counter == 0){
			$sql="SELECT id,username from users where id =". $value;
		}else{
			$sql .= " or id= ".$value;
		}
		$counter++;	
	}
	}else{
		$sql="SELECT id,username FROM users where id=0";
	}
	$sql .= " and active='Y' order by username";
	$a = $conn->query($sql);
	$a_arr = array_column($a->fetch_all(MYSQLI_ASSOC),'username','id'); 
}

}else{
	$aa = $conn->query("SELECT id,username FROM users where id <> '{$_settings->userdata('id')}' order by username");
	$a_arr = array_column($aa->fetch_all(MYSQLI_ASSOC),'username','id'); 
}
?>

<?php
$agent = isset($_GET['agent']) ? $_GET['agent'] : '*';
?>
<?php 
    $qrybal = $conn->query("SELECT * from users where id ='{$_settings->userdata('id')}' "); //$_settings->userdata('id')
    $curbal = $qrybal->fetch_assoc();
?>
<div class="card card-outline card-primary">
<div class="card-header bg-gray">
<h3 class="card-title">Cash-Out Transactions / Current Balance: <?php echo number_format($curbal['amount'],2)?></h3>
</div>
<div class="card-body">
<div class="container-fluid">
	<form action="" id="withdrawal-form">
		<input type="hidden" name ="id" value="">  
		<input type="hidden" name ="agent_id" value="<?php echo $_settings->userdata('id') ?>">  
        <input type="hidden" name ="date_created" value="<?php echo date("Y-m-d H:i") ?>">
            <div class="form-group">
                  <label for="user_id">Agent</label>
                     <select name="user_id" id="user_id" class="form-control select2bs4 select2 rounded-0" data-placeholder="Please Select" required>
					 
						<option value="" disabled <?php echo !isset($user_id) ? 'selected' : '' ?>></option>
                     	<?php foreach($a_arr as $k=>$v): ?>
                     	<option value="<?php echo $k ?>" <?php echo (isset($user_id) && $user_id == $k) ? 'selected' : '' ?>><?php echo $v ?></option>
                     	<?php endforeach; ?>
                     	</select>

            </div>
		<div class="form-group">
			<label for="amount" class="control-label">Amount</label>
                  <input name="amount" id="amount" type="number" inputmode="numeric" pattern="[0-9]*" class="form-control form  rounded-0" value= <?php echo isset($amount) ? $amount : '0.00'; ?> >
		</div>  
		<div class="form-group">
			<label for="description" class="control-label">Details</label>
			<textarea name="description" id="description" cols="30" rows="1" style="resize:none !important" class="form-control form no-resize rounded-0" required><?php echo isset($description) ? $description : ''; ?></textarea>
        </div>  
		<button class="btn btn-flat btn-primary" form="withdrawal-form">Approve</button>
	</form>
</div>
</div>
</div>




<div class="card card-outline card-primary">
	<div class="card-header bg-success">
		<h3 class="card-title">Cash-Out History</h3>
	</div>
	<div class="card-body">	
	<div class="row">
            <div class="col-2">
                 <div class="form-group">
    
                    <select name="agent" id="agent" class="form-control select2bs4 select2 rounded-0" required>
                        <option value="*" <?php echo isset($agent) && $agent == '*' ? "selected" : '' ?>>All</option>
                        <?php foreach($b_arr as $k=>$v): ?>
                        <option value="<?php echo $k ?>"<?php echo isset($agent) && $agent == $k ? "selected" : '' ?>><?php echo $v ?></option>
                        <?php endforeach; ?>
                    </select>


                </div>
            </div>

            <div class="col-2">
                <div class="w-100">
                    <div class="form-group d-flex justify-content-between align-middle">
                 	<button class="btn btn-flat btn-default bg-primary" type="button" id="filter"><i class="fa fa-filter"></i> Filter</button>
					</div>
                </div>
			</div>
    </div>
		<div class="container-fluid">
        <div class="container-fluid">
			<table id ="example" class="table table-hover table-stripped">
				<thead>
					<tr>
					    <th>#</th>
					    <th>Date</th>
					    <th>Name</th>
					    <th>Amount</th>
						<th>Details</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$bal = 0;		
					$agent_qry = $conn->query("SELECT id,username FROM users");
					$agent_arr = array_column($agent_qry->fetch_all(MYSQLI_ASSOC),'username','id');	
					if ($agent=='*'){
						if($_settings->userdata('type') == 1){
							$qry = $conn->query("SELECT * from withdrawals where active ='N'");
						}else{
							$qry = $conn->query("SELECT * from withdrawals where active ='N' and agent_id ='{$_settings->userdata('id')}'");
						}
					}else{
						if($_settings->userdata('type') == 1){
							$qry = $conn->query("SELECT * from withdrawals where active ='N' and user_id ='{$agent}'");
						}else{
							$qry = $conn->query("SELECT * from withdrawals where active ='N' and agent_id ='{$_settings->userdata('id')}' and user_id ='{$agent}'");
						}
					}
						while($row = $qry->fetch_assoc()):
                             ?>
                            <tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td><?php echo date("m-d-Y", strtotime($row['date_created'])) ?></td>
							<td ><?php echo isset($agent_arr[$row['user_id']]) ? $agent_arr[$row['user_id']] : 'N/A' ?></td>
                            <td><?php echo number_format($row['amount'],2) ?> </td>
							<td><?php echo $row['description'] ?></td>
							</tr>
							<?php $bal = $bal + $row['amount']?>
						<?php endwhile; ?>
						<h6><strong>Total Cash-Out: <?php echo number_format($bal,2) ?></strong></h6>
				</tbody>
			</table>
		</div>
		</div>
</div>
</div>


<script>
	$(document).ready(function(){
		$('#filter').click(function(){
			location.replace("./?page=maintenance/cashout&agent="+($('#agent').val()) );
        })
        $('#example').DataTable( {
        stateSave: true
        } );
        

		$('#withdrawal-form').submit(function(e){
		//check if encoded value is numeric
		if (!$.isNumeric($('#amount').val())) {
		alert_toast("Invalid Amount",'error');
		end_loader();
		return false; 
		}
		e.preventDefault();
		var _this = $(this)
		var _this = $(this)
   		$('.err-msg').remove();               
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=save_withdrawals1",
			data: new FormData($(this)[0]),
			cache: false,
			contentType: false,
			processData: false,
			method: 'POST',
			type: 'POST',
			dataType: 'json',
			error:err=>{
			console.log(err)
			alert_toast("An error occured",'error');
			end_loader();
		},
		success:function(resp){
			if(typeof resp =='object' && resp.status == 'success'){
					location.href = "./?page=maintenance/cashout"; 
			}else if(resp.status == 'failed' && !!resp.msg){
				var el = $('<div>')
					el.addClass("alert alert-danger err-msg").text(resp.msg)
					_this.prepend(el)
					el.show('slow')
					$("html, body").animate({ scrollTop: 0 }, "fast");
					end_loader()
			}else{
				alert_toast("An error occured",'error');
				end_loader();
				console.log(resp)
				
			}
		}
		})
		})

	})
</script>
<?php endif;?>



