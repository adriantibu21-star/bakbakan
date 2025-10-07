<?php
if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT * from `coms` where id = '{$_GET['id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
    }
}
$agent_qry = $conn->query("SELECT id,firstname FROM users order by firstname");
$agent_arr = array_column($agent_qry->fetch_all(MYSQLI_ASSOC),'firstname','id');

$status = isset($_GET['status']) ? $_GET['status'] : '*';
$agent = isset($_GET['agent']) ? $_GET['agent'] : '*';
?>

<div class="card card-outline card-primary">
<div class="card-body">

<div class="container-fluid">
	<form action="" id="transaction-form">
		<input type="hidden" name ="id" value="<?php echo isset($id) ? $id : '' ?>">  
            <input type="hidden" name ="date_created" value="<?php echo isset($date_created) ? $date_created : date("Y-m-d H:i") ?>">
         

            <div class="form-group">
                  <?php if($_settings->userdata('type') == 1): ?>
                  <label for="user_id">Agent</label>
                     <select name="user_id" id="user_id" class="form-control select2bs4 select2 rounded-0" data-placeholder="Please Select" required>
                     <?php foreach($agent_arr as $k=>$v): ?>
                     <option value="<?php echo $k ?>" <?php echo (isset($user_id) && $user_id == $k) ? 'selected' : '' ?>><?php echo $v ?></option>
                     <?php endforeach; ?>
                     </select>
                  <?php endif; ?>
            </div>
                
		<div class="form-group">
                  <?php if($_settings->userdata('type') == 1): ?>
			<label for="active" class="control-label">Status</label>
			<select name="active" id="active" class="custom-select rounded-0" required>
				<option value="N" <?php echo isset($active) && $active == 'N' ? "selected" : '' ?>>Inactive</option>
				<option value="Y" <?php echo isset($active) && $active == 'Y' ? "selected" : '' ?>>Active</option>
                  </select>
                  <?php endif; ?>
		</div>

		<div class="form-group">
			<label for="amount" class="control-label">Amount</label>
                  <input name="amount" id="amount" type="number" inputmode="numeric" pattern="[0-9]*" class="form-control form  rounded-0" value= <?php echo isset($amount) ? $amount : '0.00'; ?> >
		</div>    

	</form>
</div>



</div>
        <div class="card-footer">
		<button class="btn btn-flat btn-primary" form="transaction-form">Save</button>
		<a class="btn btn-flat btn-default" href="?page=maintenance/commission&status=<?php echo $status ?>&agent=<?php echo $agent ?>">Cancel</a>
	</div>

</div>


<script>
	$(document).ready(function(){
		$('#transaction-form').submit(function(e){

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
		url:_base_url_+"classes/Master.php?f=save_commission",
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

                    var ex = '<?=$_GET['status']?>';                
                    var ag = '<?=$_GET['agent']?>';     
					if(typeof resp =='object' && resp.status == 'success'){
				            location.href = "./?page=maintenance/commission&status="+ex+"&agent="+ag;     // &status="+ex /
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



