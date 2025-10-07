<?php
  require_once('../../config.php');
?>
<?php

    $qry = $conn->query("select id, com_amount_bal commission, amount wallet, username, '{$_GET['agentid']}' agentid from `users` where id = '{$_GET['id']}'");
    if($qry->num_rows > 0){
        $erow = $qry->fetch_assoc();

	$id = $erow['id'];
        $commission = $erow['commission'];
        $wallet = $erow['wallet'];
        $username = $erow['username'];
        $agentid = $erow['agentid'];
    }
    else
	{
	$id = 0;
        $commission = 0.00;
        $wallet = 0.00;
        $username = "";
        $agentid = 0;
	}

?>

<div class="container-fluid">
	<form action="" id="transaction-form"> 

        <div class="form-group">
            <label">Username : </label>   
		<b><label"> <?php  echo $username ?></label></b>
	</div>
	<div class="form-group">
            <label">Wallet : </label>   
		<b><label"> <?php  echo number_format($wallet,2) ?></label></b>    
	</div>
	<div class="form-group">
            <label">Commission :  </label>   
		<b><label"> <?php  echo number_format($commission,2) ?></label></b>     
	</div>
	<div class="form-group">
            <label">Amount Convert to Wallet:  </label>  
	     <b><input style="height:40px; font-size:20px; color:black;font-weight:bold;text-align: center;" name="convert_amount" id="convert_amount" type="text" class="form-control form  rounded-0" value="<?php  echo $commission ?>" required></b>		    
        </div>

	</form>
</div>

<script>
$(document).ready(function(){
    $('#transaction-form').submit(function(e){


        //check if encoded value is numeric
        if (!$.isNumeric($('#convert_amount').val())) {
            alert_toast("Invalid Amount",'error');
			end_loader();
            return false; 
        }

	e.preventDefault();
	var formData = new FormData(this);

	formData.append('id', <?php echo $id ?>)
    	formData.append('commission',<?php echo $commission ?>)
    	formData.append('agentid',<?php echo $agentid ?>)

             var _this = $(this)
             var _this = $(this)
	     $('.err-msg').remove();
	     start_loader();
	     $.ajax({
	     url:_base_url_+"classes/Master.php?f=post_commission",
	     data: formData,
             cache: false,
             contentType: false,
             processData: false,
             method: 'POST',
             type: 'POST',
             dataType: 'json',
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
				}else if(resp.status == 'failed' && !!resp.msg){
					alert_toast(resp.msg,'error');
                    end_loader()
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})

		})

	})


</script>





