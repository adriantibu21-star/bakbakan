<?php
require_once('../../config.php');
if (isset($_GET['id']) && $_GET['id'] > 0) {
	$qry = $conn->query("SELECT * from `events` where id = '{$_GET['id']}' ");
	if ($qry->num_rows > 0) {
		foreach ($qry->fetch_assoc() as $k => $v) {
			$$k = $v;
		}
	}
}
?>
<div class="container-fluid">
	<form action="" id="event-form" enctype="multipart/form-data">
		<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">


		<div class="form-group">
			<label for="game_id" class="control-label text-body">Select Game</label>
			<select name="game_id" id="game_id" class="custom-select rounded-0" required>
				<option value="1" <?php echo isset($game_id) && $game_id == '1' ? "selected" : '' ?>>Sabong</option>
				<option value="2" <?php echo isset($game_id) && $game_id == '2' ? "selected" : '' ?>>Pula Asul</option>
				<option value="3" <?php echo isset($game_id) && $game_id == '3' ? "selected" : '' ?>>Arena 3</option>
			</select>
		</div>
		<div class="form-group">
			<label for="name" class="control-label text-body">Event</label>
			<input name="name" id="name" type="text" class="form-control form  rounded-0" value="<?php echo isset($name) ? $name : ''; ?>" required />
		</div>
		<div class="form-group">
			<label for="description" class="control-label text-body">Description</label>
			<textarea name="description" id="description" cols="30" rows="3" style="resize:none !important" class="form-control form no-resize rounded-0" required><?php echo isset($description) ? $description : ''; ?></textarea>
		</div>
		<?php if (isset($id)): ?>
			<div class="form-group">
				<label for="active" class="control-label text-body">Status</label>
				<select name="active" id="active" class="custom-select rounded-0" required>
					<option value="N" <?php echo isset($active) && $active == 'N' ? "selected" : '' ?>>Inactive</option>
					<option value="Y" <?php echo isset($active) && $active == 'Y' ? "selected" : '' ?>>Active</option>
				</select>
			</div>
		<?php endif; ?>

		<div class="form-group">
			<label class="control-label" style="color: black;">Event Image</label>
			<div class="custom-file">
				<input type="file" class="custom-file-input rounded-circle" id="event_img" name="event_img" onchange="displayImg(this,$(this))">
				<label class="custom-file-label" for="event_img">Choose Image</label>
			</div>
		</div>

		<div class="form-group d-flex justify-content-center">
			<img src="<?php echo validate_image(isset($event_img) ? $event_img : '') ?>" alt="" id="display_event_img" class="img-fluid img-thumbnail" style="width:85%">
		</div>

		<!-- Handle submit button function and uploading of file -->
		<!-- Load dynamically in the events page, include fetching of image per event -->

	</form>
</div>
<script>
	function displayImg(input, _this) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function(e) {
				$('#display_event_img').attr('src', e.target.result);
				_this.siblings('.custom-file-label').html(input.files[0].name)
			}

			reader.readAsDataURL(input.files[0]);
		}
	}
	$(document).ready(function() {
		$('#event-form').submit(function(e) {
			e.preventDefault();
			var _this = $(this)
			var _this = $(this)
			$('.err-msg').remove();
			start_loader();
			$.ajax({
				url: _base_url_ + "classes/Master.php?f=save_events",
				data: new FormData($(this)[0]),
				cache: false,
				contentType: false,
				processData: false,
				method: 'POST',
				type: 'POST',
				dataType: 'json',
				error: err => {
					console.log(err)
					alert_toast("An error occured", 'error');
					end_loader();
				},
				success: function(resp) {
					if (typeof resp == 'object' && resp.status == 'success') {
						location.reload();
					} else if (resp.status == 'failed' && !!resp.msg) {
						var el = $('<div>')
						el.addClass("alert alert-danger err-msg").text(resp.msg)
						_this.prepend(el)
						el.show('slow')
						$("html, body").animate({
							scrollTop: 0
						}, "fast");
						end_loader()
					} else {
						alert_toast("An error occured", 'error');
						end_loader();
						console.log(resp)
					}
				}
			})
		})

	})
</script>