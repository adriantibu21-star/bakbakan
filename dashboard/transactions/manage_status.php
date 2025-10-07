<?php
  require_once('../../config.php');
?>

<?php
$eventid = $_GET['eventid'];
?>

<div class="container-fluid">
	<form action="" id="transaction-form">      
        <div class="form-group">

		<input type="hidden" name="eventid" id="eventid"  value="<?php echo $eventid ?>">

		<div class="col p-1 text-center pt-0 dark-bg"><button class="btn btn-success btn-grad-green" onclick="toggleOpen()">OPEN</button></div>
		<div class="col p-1 text-center pt-0 dark-bg"><button class="btn btn-success btn-grad-blue" onclick="toggleLastCall()">LAST-CALL</button></div>
		<div class="col p-1 text-center pt-0 dark-bg"><button class="btn btn-success btn-grad-red" onclick="toggleLClose()">CLOSE</button></div>

                <select name="status" id="status" class="custom-select rounded-0" style="display: none;"  required>
                <option value="1" >OPEN</option>
                <option value="2" >LAST-CALL</option>
                <option value="3" >CLOSE</option>
                </select>  

		<br>
    		<div class="w-100 d-flex justify-content-end mb-8">
         		<button class="btn" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
   		</div>	
		    
        </div>
	</form>
</div>
<style>
    p,label{
        margin-bottom:5px;
    }
    #uni_modal .modal-footer{
        display:none !important;
    }
</style>

<script>

  function toggleOpen() {
    $('#status').val(1);
  }
  function toggleLastCall() {
    $('#status').val(2);
  }
  function toggleLClose() {
    $('#status').val(3);
  }

$(document).ready(function(){
    $('#transaction-form').submit(function(e){
	e.preventDefault();
             var _this = $(this)
             var _this = $(this)
	     $('.err-msg').remove();
	     start_loader();
	     $.ajax({
	     url:_base_url_+"classes/Master.php?f=update_status",
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
                  	                        alert_toast("FIGHT STATUS UPDATED!",'success');
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





