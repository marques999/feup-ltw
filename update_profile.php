<?
	include_once('database/connection.php');
	include_once('database/users.php');
	include_once('database/session.php');
	include('template/header.php');

	$field = safe_getId($_GET, 'field');
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

	if($loggedIn) {
?>
<script src="js/imgcentering.min.js"></script>
<script src="upload_photo.js"></script>
<script>
$(function(){
	$('#nav_profile').addClass('active');
	$("#country-list option").remove();
	$.getJSON("json/countries.json", function(data) {
		$.each(data, function(index, item) {
			$("#country-list").append($("<option></option>").text(item).val(index));
		});
		$("#country-list").val('<?=$thisUser['country']?>').change();
	});
});
</script>
<div class="ink-grid push-center all-60 large-80 medium-100 small-100 tiny-100">
<div class="column all-100">
	<form action="actions/action_update_user.php" method="post" enctype="multipart/form-data" class="ink-form all-80 small-100 tiny-100 push-center ink-formvalidator">
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
				<label for="current-password" class="align-right all-35 small-45 tiny-45">Old Password: </label>
				<div class="control append-symbol all-65 small-55 tiny-55">
					<span>
						<input name="current-password" id="current-password" type="password" data-rules="required" placeholder="Please enter your current password">
						<i class="fa fa-key"></i>
					</span>
				</div>
			</div>
			<div class="control-group required column-group half-gutters">
				<label for="next-password" class="align-right all-35 small-45 tiny-45">New Password: </label>
				<div class="control append-symbol all-65 small-55 tiny-55">
					<span>
						<input name="next-password" id="next-password" type="password" data-rules="required|min_length[8]" placeholder="Please enter your new password">
						<i class="fa fa-key"></i>
					</span>
				</div>
			</div>
			<div class="control-group required column-group half-gutters">
				<label for="confirm-password" class="align-right all-35 small-45 tiny-45">Confirm Password: </label>
				<div class="control append-symbol all-65 small-55 tiny-55">
					<span>
						<input name="confirm-password" id="confirm-password" type="password" data-rules="required|min_length[8]" placeholder="Please confirm your new password">
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
				<div class="control append-symbol all-70">
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
			<div class="ink-alert basic">
			 	<p><b>Warning:</b> avatar size must not exceed 200 000 bytes!</p>
			</div>
			<div class="control-group column-group half-gutters">
				<input type="hidden" name="idUser" value="<?=$userId?>">
				<label for="image" class="all-25 align-right">Avatar:</label>
				<div class="control all-75">
					<div class="input-file">
						<input type="file" name="image" id="image">
					</div>
				</div>
				<label for="avatar-parent" class="all-25 align-right half-right-space">Preview:</label>
				<div id="avatar-parent" class="thumb">
					<img id="avatar" src="<?=users_getAvatar($thisUser)?>">
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