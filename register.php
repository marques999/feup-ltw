<?
	include_once('database/connection.php');
	include_once('database/users.php');
	include('template/header.php');

	$thisError=0;
	$nextId=users_getNextId()+1;

	if (isset($_GET['error'])){
		$thisError=$_GET['error'];
	}

	if(!$loggedIn){
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
	});
});
</script>
<div class="ink-grid push-center all-50 large-60 medium-80 small-100 tiny-100">
<div class="column all-70 gutters">
	<form action="actions/action_create_user.php" method="post" enctype="multipart/form-data" class="ink-formvalidator">
		<fieldset>
			<legend class="align-center">Register Account</legend>
			<div class="control-group required column-group half-gutters">
				<label for="username" class="all-25 align-right">Username</label>
				<div class="control append-symbol all-70">
					<span>
						<input name="username" id="username" type="text" data-rules="required|alpha_numeric" placeholder="Please enter your desired username">
						<i class="fa fa-user"></i>
					</span>
				</div>
			</div>
			<div class="control-group required column-group half-gutters">
				<label for="password" class="all-25 align-right">Password</label>
				<div class="control append-symbol all-70">
					<span>
						<input name="password" id="password" type="password" data-rules="required|alpha_numeric|min_length[8]" placeholder="Please enter your password">
						<i class="fa fa-key"></i>
					</span>
				</div>
			</div>
			<div class="control-group required column-group half-gutters">
				<label for="first-name" class="all-25 align-right">Name:</label>
				<div class="control all-35">
					<input type="text" id="first-name" name="first-name" data-rules="required">
					<p class="tip">First Name</p>
				</div>
				<div class="control all-35">
					<input type="text" id="last-name" name="last-name" data-rules="required">
					<p class="tip">Last Name</p>
				</div>
			</div>
			<div class="control-group required column-group half-gutters">
				<label for="email" class="all-25 align-right">E-mail:</label>
				<div class="control append-symbol all-70">
					<span>
					<input type="text" id="email" name="email" data-rules="required|email">
					<i class="fa fa-envelope-o"></i>
					</span>
				</div>
			</div>
			<div class="control-group required column-group half-gutters">
				<label for="location" class="all-25 align-right">Location:</label>
				<div class="control all-40">
					<input type="text" id="location" name="location" data-rules="required">
				</div>
				<div class="control all-30">
					<select name="country" id="country-list">
					</select>
				</div>
			</div>
			<div class="control-group column-group half-gutters">
				<label for="file-input" class="all-25 align-right">Avatar:</label>
				<input type="hidden" name="idUser" value="<?=$nextId?>">
				<div class="control all-70">
					<div class="input-file">
						<input type="file" name="image" id="image">
					</div>
				</div>
			</div>
			<div class="align-center">
				<button class="ink-button" type="submit">Submit</button>
				<button class="ink-button" type="reset">Reset</button>
			</div>
		</fieldset>
	</form>
</div>
<div class="padding align-center all-30 small-100 tiny-100">
	<div id="avatar-parent">
		<img id="avatar" src="holder.js/200x200/auto/ink">
	</div>
	<div class="ink-alert basic">
		<p><b>Warning:</b> avatar size must not exceed 200 000 bytes!</p>
	</div>
</div>
</div>
<?}else{
	header('Location: logout.php');
}
include('template/footer.php');
?>