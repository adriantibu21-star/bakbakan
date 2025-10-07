<?php
if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT * from `loading` where id = '{$_GET['id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
    }
}
if($_settings->userdata('type') == 1){
    $agent_qry = $conn->query("SELECT id,concat(firstname,' ',lastname) as name FROM users order by firstname");
}else{
    $agent_qry = $conn->query("SELECT id,concat(firstname,' ',lastname) as name FROM users where parentid = '{$_settings->userdata('id')}' order by firstname"); 
}

$agent_arr = array_column($agent_qry->fetch_all(MYSQLI_ASSOC),'name','id');

$status = isset($_GET['status']) ? $_GET['status'] : '*';
$agent = isset($_GET['agent']) ? $_GET['agent'] : '*';
?>

<div class="card card-outline card-primary">
<div class="card-body">
<div class="container-fluid">
	<form action="" id="withdrawal-form">
		<input type="hidden" name ="id" value="<?php echo isset($id) ? $id : '' ?>">  
        <input type="hidden" name ="date_created" value="<?php echo isset($date_created) ? $date_created : date("Y-m-d H:i") ?>">
            <div class="form-group">
                  <label for="user_id">Agent</label>
                     <select name="user_id" id="user_id" class="form-control select2bs4 select2 rounded-0" data-placeholder="Please Select" required>
                     <?php foreach($agent_arr as $k=>$v): ?>
                     <option value="<?php echo $k ?>" <?php echo (isset($user_id) && $user_id == $k) ? 'selected' : '' ?>><?php echo $v ?></option>
                     <?php endforeach; ?>
                     </select>
            </div>

        <?php if($_settings->userdata('type') == 1): ?>       
        <?php if(isset($id)): ?>   
		<div class="form-group">         
			<label for="active" class="control-label">Status</label>
			<select name="active" id="active" class="custom-select rounded-0" required>
				<option value="N" <?php echo isset($active) && $active == 'N' ? "selected" : '' ?>>Inactive</option>
				<option value="Y" <?php echo isset($active) && $active == 'Y' ? "selected" : '' ?>>Active</option>
                  </select>
		</div>
        <?php endif; ?>
        <?php endif; ?>
        
		<div class="form-group">
			<label for="amount" class="control-label">Amount</label>
                  <input name="amount" id="amount" type="number" min="0" inputmode="numeric" pattern="[0-9]*" class="form-control form  rounded-0" value= <?php echo isset($amount) ? $amount : '0.00'; ?> >
		</div>  


		<div class="form-group">
			<label for="description" class="control-label">Details</label>
			<textarea name="description" id="description" cols="30" rows="3" style="resize:none !important" class="form-control form no-resize rounded-0" required><?php echo isset($description) ? $description : ''; ?></textarea>
        </div>  

	</form>
</div>



</div>
        <div class="card-footer">
		<button class="btn btn-flat btn-primary" form="withdrawal-form">Save</button>
        <?php if($_settings->userdata('type') == 1): ?>
		    <a class="btn btn-flat btn-default" href="?page=maintenance/loading&status=<?php echo $status ?>&agent=<?php echo $agent ?>">Cancel</a>
        <?php else: ?>
            <a class="btn btn-flat btn-default" href="?page=maintenance/loading">Cancel</a>
        <?php endif; ?> 
	</div>

</div>


<script>
	$(document).ready(function(){
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
		url:_base_url_+"classes/Master.php?f=save_loading",
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
                    <?php if($_settings->userdata('type') == 1): ?>
                    var ex = '<?=$_GET['status']?>';
                    var ag = '<?=$_GET['agent']?>';  
                    <?php endif; ?>    
					if(typeof resp =='object' && resp.status == 'success'){
                        <?php if($_settings->userdata('type') == 1): ?>
				            location.href = "./?page=maintenance/loading&status="+ex+"&agent="+ag; 
                        <?php else: ?> 
                            location.href = "./?page=maintenance/loading";    
                        <?php endif; ?>      
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



