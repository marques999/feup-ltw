<?
	include_once('database/connection.php');
	include_once('database/users.php');
	include('template/header.php');

	$thisError = safe_getId($_GET, 'error');
?>
<script>
$(function(){
	$('#nav_profile').addClass('active');
});
</script>
<?if(!$loggedIn){?>
<div class="ink-grid push-center all-40 large-60 medium-80 small-100 tiny-100">
<form action="actions/action_login.php" method="post" class="ink-form ink-formvalidator">
	<fieldset>
		<legend class="align-center">Sign-In / Register</legend>
		<?if($thisError==1){?>
			<div class="ink-alert basic error">
				<p><b>Error:</b> you have entered an invalid username or password</p>
			</div>
		<?}?>
		<div class="control-group required column-group half-gutters">
			<label for="username" class="all-25 align-right">Username</label>
			<div class="control append-symbol all-75">
				<span>
					<input name="username" type="text" data-rules="required" placeholder="Please enter your username">
					<i class="fa fa-user"></i>
				</span>
			</div>
		</div>
		<div class="control-group required column-group half-gutters">
			<label for="password" class="all-25 align-right">Password</label>
			<div class="control append-symbol all-75">
				<span>
					<input name="password" id="password" type="password" data-rules="required" placeholder="Please enter your password">
					<i class="fa fa-key"></i>
				</span>
			</div>
		</div>
		<div class="control-group column-group">
			<div class="control push-center all-20">
				<input type="submit" name="sub" value="Sign In &gt;" class="ink-button" />
			</div>
		</div>
		<div class="control-group column-group">
			<p class="align-center">Don't have an account?
				<a href="register.php">Sign up here!</a>
			</p>
		</div>
	</fieldset>
</form>
</div>
<?}else{
	header('Location: logout.php');
}
include('template/footer.php');
?>