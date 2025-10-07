<?php
  require_once('../../config.php');

$eventid = $_GET['eventid'];

if(isset($_GET['betid']) && $_GET['betid'] > 0){
    $qry = $conn->query("SELECT * FROM `bets` WHERE drawid = (select id FROM draws WHERE eventid = '{$eventid}' and active = 'Y' order by id desc limit 1 ) and user_id = '{$_settings->userdata('id')}'");
    
        if($qry->num_rows > 0){
            foreach($qry->fetch_assoc() as $k => $v){
                $$k=$v;
            }
        }else{

            $ry = $conn->query("SELECT * FROM draws WHERE eventid = '{$eventid}' and active = 'Y' order by id desc limit 1");  
            if($ry->num_rows > 0){
                $row = $ry->fetch_assoc();
                $drawid = $row['id'];
                $id='';
                $user_id = $_settings->userdata('id');

            }else{
                $drawid='';
                $id='';
                $user_id = $_settings->userdata('id');
            }


    }


}
//echo 'amt='.$_GET['bet'];
//echo 'betid='.$_GET['betid'];
//echo 'drawid='.$drawid;
// echo 'userid='.$_settings->userdata('id');
// echo 'hh';
?>


<div class="container-fluid">
	<form action="" id="transaction-form">
		<input type="hidden" name ="id" value="<?php echo isset($id) ? $id : '' ?>">
        <input type="hidden" id="drawid" name ="drawid" value="<?php echo isset($drawid) ? $drawid : '' ?>">      
  
        <div class="form-group">
        <?php if($_GET['betid'] == 1): ?>
            <label for="red_amount" class="control-label"style="color:black">ARE YOU SURE?</label>   		    
		    <input name="red_amount" id="red_amount" type="number" inputmode="numeric" pattern="[0-9]*" min="10" max="20000"step="1" class="form-control form  rounded-0" value=<?php echo $_GET['bet']?> readonly >
            <input type="hidden" name ="blue_amount" id="blue_amount" value="0">
            <input type="hidden" name ="yellow_amount" id="yellow_amount" value="0">

        <?php elseif($_GET['betid'] == 2): ?>
            <label for="blue_amount" class="control-label" style="color:black">ARE YOU SURE?</label>   		    
		    <input name="blue_amount" id="blue_amount" type="number" inputmode="numeric" pattern="[0-9]*" min="10" max="20000"step="1" class="form-control form  rounded-0" value=<?php echo $_GET['bet']?> readonly >
            <input type="hidden" name ="red_amount" id="red_amount"value="0">
            <input type="hidden" name ="yellow_amount" id="yellow_amount" value="0">

        <?php else: ?>
            <label for="yellow_amount" class="control-label"style="color:black">ARE YOU SURE?</label>   		    
		    <input name="yellow_amount" id="yellow_amount" type="number" inputmode="numeric" pattern="[0-9]*" min="10" max="20000"step="1" class="form-control form  rounded-0" value=<?php echo $_GET['bet']?> readonly >
            <input type="hidden" name ="red_amount" id="red_amount"value="0">
            <input type="hidden" name ="blue_amount" id="blue_amount" value="0">

        <?php endif; ?>
        </div>

	</form>
</div>

<script>
$(document).ready(function(){
    $('#transaction-form').submit(function(e){

        //check if encoded value is numeric
        if (!$.isNumeric($('#red_amount').val())) {
            alert_toast("Invalid Amount",'error');
			end_loader();
            return false; 
        }
        if (!$.isNumeric($('#blue_amount').val())) {
            alert_toast("Invalid Amount",'error');
			end_loader();
            return false; 
        }

        if (!$.isNumeric($('#yellow_amount').val())) {
            alert_toast("Invalid Amount",'error');
			end_loader();
            return false; 
        }
        if ($('#drawid').val() == '' ) {
            alert_toast("Betting is closed",'error');
			end_loader();
            return false; 
        }


	e.preventDefault();
	var formData = new FormData(this);

	formData.append('betid', <?php echo $_GET['betid']?>)
    	formData.append('user_id', <?php echo $_settings->userdata('id') ?>)
    	formData.append('date_created','<?php echo isset($date_created) ? $date_created : date("Y-m-d H:i") ?>')
	formData.append('eventid', <?php echo $eventid?>)

             var _this = $(this)
             var _this = $(this)
	     $('.err-msg').remove();
	     start_loader();
	     $.ajax({
	     url:_base_url_+"classes/Master.php?f=save_bets",
	     data: formData,
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
						//location.reload();
                        var dialog = $('.modal');
                        if (typeof dialog.modal == 'function') {
                        dialog.modal('hide');
                  	    alert_toast("BET SUCCESSFULLY PLACED!",'success');
						end_loader();
                        }
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





