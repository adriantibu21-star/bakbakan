<?php
  require_once('../../config.php');
?>
<div class="container-fluid">
	<form action="" id="transaction-form">      
        <div class="form-group">
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
	     url:_base_url_+"classes/Master.php?f=finish_transaction",
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





