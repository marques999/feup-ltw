<?
	include('template/header.php');

	$thisError = 0;

	if (isset($_GET['error'])) {
		$thisError = $_GET['error'];
	}
?>

<script>
	$(document).ready(function() {
		$('#nav_profile').addClass('active');
	});
</script>

<div class="column-group half-vertical-space">
	<div class="column all-33 large-25 medium-15 small-10 tiny-5">
	</div>
	<?if(!isset($_SESSION['username'])){?>
	<div class="column all-33 large-50 medium-70 small-80 tiny-90">
	<form action="actions/action_login.php" method="POST" class="ink-form ink-formvalidator">
		<fieldset>
			<legend class="align-center">Sign-In / Register</legend>
			<?if($thisError == 1){?>
				<div class="column all-100 ink-alert basic error" role="alert">
				    <p><b>Error:</b> you have entered an invalid username or password</p>
				</div>
			<?}?>
			<div class="control-group required column-group half-gutters">
				<label for="username" class="all-25 align-right">Username</label>
				<div class="control append-symbol all-75">
					<span>
						<input name="username" id="username" type="text" data-rules="required" placeholder="Please enter your username">
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
			<div class="control-group column-group align-center half-gutters">
				<div class="control all-100">
					<span class="align-center">
						<input type="submit" name="sub" value="Sign In &gt;" class="ink-button success" />
					</span>
				</div>
			</div>
			<div class="control-group column-group half-gutters">
				<div class="control all-100">
					<p class="align-center">Don't have an account?
						<a href="register.php">Sign up here</a>
					</p>
				</div>
			</div>
		</fieldset>
	</form>
	</div>
	<?}else{?>
		<form action="actions/action_logout.php" method="POST" class="ink-form all-33 large-60 medium-80 small-100 tiny-100">
			<fieldset>
				<legend class="align-center">Logout</legend>
				<div class="control-group column-group half-gutters">
					<p>Are you sure you want to terminate your session?</p>
				</div>
				<div class="control-group column-group half-gutters">
				<label for="" class="all-25 align-right"></label>
				<div class="control all-20 align-right">
					<input type="submit" name="sub" value="Log out &gt;" class="ink-button success" />
				</div>
			</div>
			</fieldset>
		</form>
	<?}?>
</div>

<?
	include('template/footer.php');
?>