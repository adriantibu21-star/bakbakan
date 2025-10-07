<?php
  require_once('../../config.php');
?>

<?php
$game_id = $_GET['game_id'];
?>

<div class="container-fluid">
	<form action="" id="transaction-form">      
        <div class="form-group">

		<input type="hidden" name="game_id" id="game_id"  value="<?php echo $game_id ?>">

		<div class="row">
		<div class="col p-1 text-center pt-0 dark-bg"><button class="btn btn-success btn-grad-red" onclick="toggleMeron()">MERON</button></div>
		<div class="col p-1 text-center pt-0 dark-bg"><button class="btn btn-success btn-grad-blue" onclick="toggleWala()">WALA</button></div>
		</div>
		<div class="row">
		<div class="col p-1 text-center pt-0 dark-bg"><button class="btn btn-success btn-grad-green" onclick="toggleDraw()">DRAW</button></div>
		<div class="col p-1 text-center pt-0 dark-bg"><button class="btn btn-success btn-grad-silver" onclick="toggleCancel()">CANCEL</button></div>
		</div>

                <select name="winner" id="winner" style="display: none;" required>
                <option value="1" >MERON</option>
                <option value="2" >WALA</option>
                <option value="3" >DRAW</option>
                <option value="4" >CANCEL</option>
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

  function toggleMeron() {
    $('#winner').val(1);
  }
  function toggleWala() {
    $('#winner').val(2);
  }
  function toggleDraw() {
    $('#winner').val(3);
  }
  function toggleCancel() {
    $('#winner').val(4);
  }



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





