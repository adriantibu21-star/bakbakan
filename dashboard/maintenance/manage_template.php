<?php
if(isset($_GET['id']) && $_GET['id'] > 0){
	require_once('../../config.php');
    $qry = $conn->query("SELECT * from `template` where id = '{$_GET['id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
    }
}
?>
<div class="container-fluid">
	<form action="" id="template-form">
		<input type="hidden" name ="id" value="<?php echo isset($id) ? $id : '' ?>">

		<div class="form-group">
			<label for="game_id" class="control-label text-body">Select Game</label>
			<select name="game_id" id="game_id" class="custom-select rounded-0" required>
				<option value="1" <?php echo isset($game_id) && $game_id == '1' ? "selected" : '' ?>>Sabong</option>
				<option value="2" <?php echo isset($game_id) && $game_id == '2' ? "selected" : '' ?>>Pula Asul</option>
				<option value="3" <?php echo isset($game_id) && $game_id == '3' ? "selected" : '' ?>>Arena 3</option>
            		</select>
		</div>

		<div class="form-group">
			<label for="name" class="control-label text-body">Link</label>
			<input name="name" id="name" type="text" class="form-control form  rounded-0" value="<?php echo isset($name) ? $name : ''; ?>" required/>
		</div>
		<div class="form-group">
			<label for="description" class="control-label text-body">Description</label>
			<textarea name="description" id="description" cols="30" rows="3" style="resize:none !important" class="form-control form no-resize rounded-0" required><?php echo isset($description) ? $description : ''; ?></textarea>
		</div>
		<div class="form-group">
			<label for="odds" class="control-label text-body">Odds</label>
			<select name="odds" id="odds" class="custom-select rounded-0" required>


				<option value="0.950"<?php echo isset($odds) && $odds == '0.950' ? "selected" : '' ?>>190</option>
				<option value="0.945"<?php echo isset($odds) && $odds == '0.945' ? "selected" : '' ?>>189</option>
				<option value="0.940"<?php echo isset($odds) && $odds == '0.940' ? "selected" : '' ?>>188</option>
				<option value="0.935"<?php echo isset($odds) && $odds == '0.935' ? "selected" : '' ?>>187</option>
				<option value="0.930"<?php echo isset($odds) && $odds == '0.930' ? "selected" : '' ?>>186</option>
				<option value="0.925"<?php echo isset($odds) && $odds == '0.925' ? "selected" : '' ?>>185</option>
				<option value="0.920"<?php echo isset($odds) && $odds == '0.920' ? "selected" : '' ?>>184</option>
				<option value="0.915"<?php echo isset($odds) && $odds == '0.915' ? "selected" : '' ?>>183</option>
				<option value="0.910"<?php echo isset($odds) && $odds == '0.910' ? "selected" : '' ?>>182</option>
				<option value="0.905"<?php echo isset($odds) && $odds == '0.905' ? "selected" : '' ?>>181</option>
				<option value="0.90"<?php echo isset($odds) && $odds == '0.90' ? "selected" : '' ?>>180</option>
				<option value="0.895"<?php echo isset($odds) && $odds == '0.895' ? "selected" : '' ?>>179</option>
				<option value="0.89"<?php echo isset($odds) && $odds == '0.89' ? "selected" : '' ?>>178</option>
				<option value="0.885"<?php echo isset($odds) && $odds == '0.885' ? "selected" : '' ?>>177</option>
				<option value="0.88"<?php echo isset($odds) && $odds == '0.88' ? "selected" : '' ?>>176</option>
				<option value="0.875"<?php echo isset($odds) && $odds == '0.875' ? "selected" : '' ?>>175</option>
				<option value="0.87"<?php echo isset($odds) && $odds == '0.87' ? "selected" : '' ?>>174</option>
				<option value="0.865"<?php echo isset($odds) && $odds == '0.865' ? "selected" : '' ?>>173</option>
				<option value="0.86"<?php echo isset($odds) && $odds == '0.86' ? "selected" : '' ?>>172</option>
				<option value="0.855"<?php echo isset($odds) && $odds == '0.855' ? "selected" : '' ?>>171</option>
				<option value="0.85"<?php echo isset($odds) && $odds == '0.85' ? "selected" : '' ?>>170</option>

            		</select>
		</div>
	</form>
</div>
<script>

	$(document).ready(function(){
		$('#template-form').submit(function(e){
			e.preventDefault();
var _this = $(this)
            var _this = $(this)
			 $('.err-msg').remove();
			start_loader();
			$.ajax({
				url:_base_url_+"classes/Master.php?f=save_template",
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