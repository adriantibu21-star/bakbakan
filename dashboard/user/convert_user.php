<?php
  require_once('../../config.php');
if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT * FROM `users` WHERE id = '{$_GET['id']}'");

    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
    }
}
?>
<div class="container-fluid">
	<form action="" id="transaction-form">   
    <input type="hidden" name ="id" value="<?php echo isset($id) ? $id : '' ?>"> 
    <input type="hidden" name ="agent_rate" value="<?php echo $_settings->userdata('rate')?>"> 
        <div class="form-group">
        <label for="role" class="control-label">TYPE</label>   		
                <select name="role" id="role" class="custom-select rounded-0" required>
                <?php if($_settings->userdata('type') == 1): ?>
                    <option value="1" >FINANCER ACCOUNT</option>
                    <option value="2" >OPERATOR</option>
                    <option value="3" >SUB-OPERATOR</option>
                    <option value="4" >MASTER-AGENT</option>
                <?php else: ?>  

                <?php if($_settings->userdata('role') == 1): ?>
                    <option value="2" >OPERATOR</option>
                    <option value="3" >SUB-OPERATOR</option>
                    <option value="4" >MASTER-AGENT</option>
                <?php elseif($_settings->userdata('role') == 2): ?>
                    <option value="3" >SUB-OPERATOR</option>
                    <option value="4" >MASTER-AGENT</option>
                <?php elseif($_settings->userdata('role') == 3): ?>
                    <option value="4" >MASTER-AGENT</option>
                <?php else: ?> 
                <?php endif; ?> 

                <?php endif; ?> 
                </select>  		    
        </div>
        <div class="form-group">
        <label for="rate" class="control-label">RATE</label>   		    
        <input name="rate" id="rate" type="number" inputmode="numeric" pattern="[0-9]*" min="0" max="11.00" step=".1" class="form-control form  rounded-0" value="<?php echo isset($rate) ? $rate : '0.00' ?>" >
        </div>
	</form>
</div>
<script>
$(document).ready(function(){
    $('#transaction-form').submit(function(e){
        //check max rate
        if ($("#rate").val() > 11){
            alert_toast("Max rate is 11!",'error');
			end_loader();
            return false; 
        }
        
        //check if encoded value is numeric
        if (!$.isNumeric($('#rate').val())) {
            alert_toast("Invalid Rate",'error');
			end_loader();
            return false; 
        }

	e.preventDefault();
             var _this = $(this)
             var _this = $(this)
	     $('.err-msg').remove();
	     start_loader();
	     $.ajax({
	     url:_base_url_+"classes/Users.php?f=convert",
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





