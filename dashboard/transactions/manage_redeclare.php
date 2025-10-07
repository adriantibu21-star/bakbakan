<?php
  require_once('../../config.php');
?>

<?php
$eventid = $_GET['eventid'];
?>

<?php //for combobox

	$qry_redeclare = $conn->query("select id drawid, concat(drawno,' - ', DATE_FORMAT(a.date_created, '%m/%d/%Y %r'),' (', 
		case
			when winner = 1 then 'MERON'
			when winner = 2 then 'WALA'
			when winner = 3 then 'DRAW'
			when winner = 4 then 'CANCELLED'
		end,')'
	) fightno_time
	from draws a where a.eventid = '{$eventid}' and a.status = 3 order by id desc limit 500");
	$arr_redeclare = array_column($qry_redeclare->fetch_all(MYSQLI_ASSOC),'drawid','fightno_time'); 
?>

<div class="container-fluid">
	<form action="" id="transaction-form"> 

	<input type="hidden" name ="user_id" value="<?php echo $_settings->userdata('id') ?>">
        <input type="hidden" name ="date_created" value="<?php echo date("Y-m-d H:i") ?>">
        <input type="hidden" name ="eventid" id="eventid"  value="<?php echo $eventid ?>">

        <div class="form-group">
            <label">SELECT FIGHT NO. AND DATETIME</label>   		    

                     <select name="drawid" id="drawid" class="form-control select2 rounded-0" data-placeholder="Please Select" required>		 

                     	<?php foreach($arr_redeclare as $k=>$v): ?>
                     	<option value="<?php echo $v ?>"> <?php echo $k ?> </option>
                     	<?php endforeach; ?>

                     </select>

        </div>
     
        <div class="form-group">
		<label">WINNER</label>   
                <select name="winner" id="winner" class="custom-select rounded-0" required>
                <option value="1" >MERON</option>
                <option value="2" >WALA</option>
                <option value="3" >DRAW</option>
                <option value="4" >CANCEL</option>
                </select>  		    
        </div>
	</form>
</div>
<script>
$(document).ready(function(){
    $('#transaction-form').submit(function(e){
	e.preventDefault();
             var _this = $(this)
             var _this = $(this)
	     $('.err-msg').remove();
	     start_loader();
	     $.ajax({
	     url:_base_url_+"classes/Master.php?f=redeclare_transaction",
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
						//location.reload();
                                                var dialog = $('.modal');
                                                if (typeof dialog.modal == 'function') {
                                                dialog.modal('hide');
                  	                        alert_toast("FIGHT FINISHED.",'success');
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





