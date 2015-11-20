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
<div class="ink-grid all-80 medium-90 small-100 tiny-100">
<div class="column-group vertical-space">
	<div class="column all-20 large-10 medium-10 small-0 tiny-0">
	</div>
	<form action="action_create_user.php" method="POST" class="ink-form ink-formvalidator">
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
						<input name="password" id="password" type="password" data-rules="required|min_length[8]" placeholder="Please enter your password">
						<i class="fa fa-key"></i>
					</span>
				</div>
			</div>
			<div class="control-group required column-group half-gutters">
				<label for="first-name" class="all-25 align-right">Name:</label>
				<div class="control all-35">
					<input type="text" name="first-name" data-rules="required">
					<p class="tip">First Name</p>
				</div>
				<div class="control all-35">
					<input type="text" name="last-name" data-rules="required">
					<p class="tip">Last Name</p>
				</div>
			</div>
			<div class="control-group required column-group half-gutters">
				<label for="email" class="all-25 align-right">E-mail:</label>
				<div class="control append-symbol all-70">
					<span>
					<input type="text" name="email" data-rules="required|email">
					<i class="fa fa-envelope-o"></i>
					</span>
				</div>
			</div>
			<div class="control-group required column-group half-gutters">
				<label for="location" class="all-25 align-right">Location:</label>
				<div class="control all-40">
					<input type="text" name="location" data-rules="required">
				</div>
				<div class="control all-30">
					<select>
						<option value="Portugal">Volvo</option>
						<option value="Spain">Saab</option>
						<option value="Turkey">Mercedes</option>
						<option value="Ukraine">Audi</option>
					</select>
				</div>
			</div>
			<div class="control-group column-group half-gutters">
				<label for="file-input" class="all-25 align-right">Avatar:</label>
				<div class="control all-70">
					<div class="input-file">
						<input type="file" name="" id="file-input">
					</div>
				</div>
			</div>
			<div class="control-group column-group half-gutters">
			<label for="" class="all-25 align-right"></label>
				<div class="column control all-30">
					<input type="submit" value="Submit" class="ink-button success" />
				</div>
				<div class="column control all-30">
					<input type="reset" value="Reset" class="ink-button" />
				</div>
			</div>
		</fieldset>
	</form>
	<div class="all-25 align-center small-100 tiny-100 vertical-space">
		<img src="holder.js/200x200/auto/ink" alt="">
		<div class="ink-alert basic">
		 <p><b>Warning:</b> Avatar size must not exceed 50000 bytes!</p>
		</div>
	</div>
</div>
</div>
<?include('template/footer.php')?>