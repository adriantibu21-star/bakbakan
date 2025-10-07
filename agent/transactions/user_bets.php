<?php
if(isset($_GET['id']) && $_GET['id'] > 0){
	require_once('../../config.php');
    $qry = $conn->query("SELECT * from `transactions` where id = '{$_GET['id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
    }
}
$department_qry = $conn->query("SELECT id,name FROM department_list");
$dept_arr = array_column($department_qry->fetch_all(MYSQLI_ASSOC),'name','id');
?>
<div class="container-fluid">
	<form action="" id="forward-form">
		<input type="hidden" name ="id" value="<?php echo $id ?>">
		<input type="hidden" name ="office1" value="<?php echo $office1 ?>">
		<input type="hidden" name ="userid" value="<?php echo $_settings->userdata('id') ?>">

                <div class="form-group">
                <label for="department_id">Destination</label>
                <select name="department_id" id="department_id" class="form-control select2bs4 select2 rounded-0" data-placeholder="Please Select Office here" required>
                <?php foreach($dept_arr as $k=>$v): ?>
                <option value="<?php echo $k ?>" <?php echo (isset($source) && $source == $k) ? 'selected' : '' ?>><?php echo $v ?></option>
                <?php endforeach; ?>
                </select>
		</div>
	</form>
</div>
<script>

	$(document).ready(function(){
        $('#forward-form').submit(function(e){
	e.preventDefault();
             var _this = $(this)
             var _this = $(this)
	     $('.err-msg').remove();
	     start_loader();
	     $.ajax({
	     url:_base_url_+"classes/Master.php?f=forward_transaction",
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
						location.reload();
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