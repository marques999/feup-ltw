<?
	include_once('database/connection.php');
	include_once('database/users.php');
	include_once('database/session.php');
	include('template/header.php');

	$field = safe_getId($_GET, 'field');
	$nextId = users_getNextId() + 1;
	$thisUser = $defaultUser;
	$userId = 0;

	if (isset($_SESSION['userid'])) {
		$userId = safe_getId($_SESSION, 'userid');
		$getUser = users_listById($userId);
		if (count($getUser) > 0) {
			$thisUser = $getUser[0];
		}
	}

	$thisError = safe_getId($_GET, 'error');
	$splitName = explode(" ", $thisUser['name']);
?>

<?if($loggedIn){?>
<script>
var wrapper = $('<div/>').css({height:0,width:0,'overflow':'hidden'});
var fileInput = $('#file-input').wrap(wrapper);
var userCountry = "<?=$thisUser['country']?>";
$(function(){
	$('#nav_profile').addClass('active');
	$("#country-list option").remove();
	$.getJSON("json/countries.json", function(data) {
		$.each(data, function(index, item) {
			$("#country-list").append($("<option></option>").text(item).val(index));
		});
		$("#country-list").val(userCountry).change();
	});

	wrapper = $('<div/>').css({height:0,width:0,'overflow':'hidden'});
	fileInput = $('#file-input');
/*	fileInput.change(function(){
		$.ajax({
		type: 'post',
		url: 'actions/action_upload_photo.php',
		data: {
				'source': 'user',
				'idUser': <?=$nextId?>
		},
		contentType: false,
		cache: false,
		processData: false,
		success: function(destination) {
			if (destination == window.location.pathname) {
				window.location.reload();
			}
			else {
				window.location = destination;
			}
		}
		});
	})*/
});

function readURL(input) {

	if (input.files && input.files[0]) {
		var goUpload = true;
		var uploadFile = input.files[0];

		if (!(/\.(bmp|gif|jpg|jpeg|png)$/i).test(uploadFile.name)) {
			fileInput.effect("shake");
			fileInput.text('You must select an image file only');
				console.log("here2");
			setTimeout(function() {
				fileInput.text('Choose an avatar...');
			} ,5000);

			return false;
		}
			console.log("here3");
		if (uploadFile.size > 2000000) { // 2mb
		   fileInput.text('Please upload a smaller image, max size is 2 MB');

			setTimeout(function() {
				fileInput.text('Choose an avatar...');
			}, 5000);

			return false;
		}

		fileInput.text("Uploading "+uploadFile.name);

		var reader = new FileReader();

		reader.onload = function (e) {

			$('img#blah').attr('src', e.target.result);

			var width = $('#blah').width(); // Current image width
			var height = $('#blah').height(); // Current image height

			if (width > 200 || height > 200) {

				var newHeight = 120 * (height / width);
				var newWidth = 120 * (width / height);

				if (width > height) {
					$('img#blah').css("width", newWidth + 'px'); // set new width
					$('img#blah').css("height", '120px'); // scale height based on ratio
				}
				else if (height > width) {
					$('img#blah').css("height", newHeight + 'px'); // set new width
					$('img#blah').css("width", '120px'); // scale height based on ratio
				}

				fileInput.text(uploadFile.name);
			}
			else {
				fileInput.text("Minimum file size: 120x120px");

				setTimeout(function() {
					fileInput.text('Choose file');
				}, s5000);
			}
		}

		reader.readAsDataURL(uploadFile);
	}
}
</script>

<div class="ink-grid push-center all-50 large-60 medium-80 small-100 tiny-100">
<div class="column all-100 gutters">
	<form action="actions/action_update_user.php" method="post" enctype="multipart/form-data" class="ink-form all-75 push-center ink-formvalidator">
		<input name="field" type="hidden" value="<?=$field?>">
		<input name="idUser" type="hidden" value="<?=$userId?>">
		<fieldset>
			<?if($field==1){?>
				<legend class="align-center">Change Password</legend>
			<?}else if($field==3){?>
				<legend class="align-center">Change E-mail</legend>
			<?}else if($field==2){?>
				<legend class="align-center">Change Name</legend>
			<?}else if($field==5){?>
				<legend class="align-center">Change Avatar</legend>
			<?}else if($field==4){?>
				<legend class="align-center">Change Location</legend>
			<?}?>
			
			<?if($thisError==1){?>
			<div class="ink-alert basic error">
				<p><b>Error:</b> you must enter your current password as the old password!</p>
			</div>
			<?}else if($thisError==2){?>			
			<div class="ink-alert basic error">
				<p><b>Error:</b> new and confirmation passwords don't match!</p>
			</div>
			<?}else if($thisError==3){?>
			<div class="ink-alert basic error">
				<p><b>Error:</b> old password and new password must be different!</p>
			</div>
			<?}?>

			<!-- BEGIN UPDATE PASSWORD -->
			<?if($field==1){?>
			<div class="control-group required column-group half-gutters">	
				<label for="current-password" class="align-right all-30">Old Password: </label>
				<div class="control append-symbol all-70">				
					<span>
						<input name="current-password" id="curr-password" type="password" data-rules="required" placeholder="Please enter your password">
						<i class="fa fa-key"></i>
					</span>
				</div>
			</div>
			<div class="control-group required column-group half-gutters">	
				<label for="next-password" class="align-right all-30">New Password: </label>
				<div class="control append-symbol all-70">		
					<span>
						<input name="next-password" id="next-password" type="password" data-rules="required|min_length[8]" placeholder="Please enter your password">
						<i class="fa fa-key"></i>
					</span>
				</div>
			</div>
			<div class="control-group required column-group half-gutters">	
				<label for="confirm-password" class="align-right all-30">Confirm Password: </label>
				<div class="control append-symbol all-70">			
					<span>
						<input name="confirm-password" id="confirm-password" type="password" data-rules="required|min_length[8]" placeholder="Please enter your password">
						<i class="fa fa-key"></i>
					</span>
				</div>
			</div>
			<!-- END UPDATE PASSWORD -->


			<!-- BEGIN UPDATE NAME -->
			<?}else if($field==2){?>
			<div class="control-group required column-group half-gutters">
				<label for="first-name" class="all-20 align-right">Name:</label>
				<div class="control all-40">
					<input type="text" name="first-name" value="<?=$splitName[0]?>" data-rules="required">
					<p class="tip">First Name</p>
				</div>
				<div class="control all-40">
					<input type="text" name="last-name" value="<?=$splitName[1]?>" data-rules="required">
					<p class="tip">Last Name</p>
				</div>
			</div>
			<!-- END UPDATE NAME -->


			<!-- BEGIN UPDATE E-MAIL -->
			<?}else if($field==3){?>
			<div class="control-group required column-group half-gutters">
				<label for="email" class="all-20 align-right">E-mail:</label>
				<div class="control append-symbol all-80">
					<span>
					<input type="text" name="email" value="<?=$thisUser['email']?>" data-rules="required|email">
					<i class="fa fa-envelope-o"></i>
					</span>
				</div>
			</div>
			<!-- END UPDATE E-MAIL -->


			<!-- BEGIN UPDATE LOCATION -->
			<?}else if($field==4){?>
			<div class="control-group required column-group half-gutters">
				<label for="location" class="all-30 align-right">Location:</label>
				<div class="control all-60">
					<input type="text" name="location" value="<?=$thisUser['location']?>" data-rules="required">
				</div>
			</div>
			<div class="control-group required column-group half-gutters">
				<label for="country-list" class="all-30 align-right">Country:</label>
				<div class="control all-60">
					<select name="country" id="country-list">
					</select>
				</div>
			</div>
			<!-- END UPDATE LOCATION -->


			<!-- BEGIN UPDATE AVATAR -->
			<?}else if($field==5){?>
			<div id="blah" class="padding double-vertical-space all-30 align-center small-100 tiny-100">
				<img id="blah" src="holder.js/200x200/auto/ink" alt="">
				<div class="ink-alert basic">
				 <p><b>Warning:</b> avatar size must not exceed 200 000 bytes!</p>
				</div>
			</div>
			<div class="control-group column-group half-gutters">
				<label for="file-input" class="all-25 align-right">Avatar:</label>
				<input type="hidden" name="idUser" value="<?=$nextId?>">
				<div class="control all-75">
					<div class="input-file">
						<input type="file" name="userfile" id="file-input">
					</div>
				</div>
			</div>
			<?}?>
			<!-- END UPDATE AVATAR -->


			<div class="align-center">
				<button class="ink-button" type="submit">Update</button>
				<button class="ink-button" onclick="history.go(-1)" type="reset">Cancel</button>
			</div>
		</fieldset>
	</form>
</div>
</div>
<?}else{
	include_once('message_guest.php');
}
include('template/footer.php');
?>