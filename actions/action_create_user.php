<?
	include_once('../database/action.php');
	include_once('../database/country.php');
	include_once('../database/salt.php');
	include_once('../database/users.php');
	include('../template/header.php');
?>

<script>
	$(document).ready(function() {
		$('#nav_profile').addClass('active');
	});
</script>

<?if (!isset($_POST['username']) || !isset($_POST['password'])) {
	exit(0);
}

$userExists = users_userExists($_POST['username']);
$emailExists = users_emailExists($_POST['email']);

if($userExists){?>
	<div class="column-group half-vertical-space">
		<div class="column all-20 large-15 medium-10 small-0 tiny-0"></div>
		<div class="column all-60 large-70 medium-80 small-100 tiny-100 ink-alert block error" role="alert">
			<h4>Database Error</h4>
			<p>User account creation failed! (username already taken)</p>
			<p>Please click <a href="register.php">here</a> to continue</p>
		</div>
	</div>
<?}
else if($emailExists){?>
	<div class="column-group half-vertical-space">
		<div class="column all-20 large-15 medium-10 small-0 tiny-0"></div>
		<div class="column all-60 large-70 medium-80 small-100 tiny-100 ink-alert block error" role="alert">
			<h4>Database Error</h4>
			<p>User account creation failed! (another user with the same e-mail address exists)</p>
			<p>Please click <a href="register.php">here</a> to continue</p>
		</div>
	</div>
<?}else{
	$newPassword = create_hash($_POST['password']);
	$fullName = "{$_POST['first-name']} {$_POST['last-name']}";
	$stmt = $db->prepare('INSERT INTO Users VALUES(NULL, :username, :password, :name, :email, :location, :country)');
	$stmt->bindParam(':username', $_POST['username'], PDO::PARAM_STR);
	$stmt->bindParam(':password', $newPassword, PDO::PARAM_STR);
	$stmt->bindParam(':name', $fullName, PDO::PARAM_STR);
	$stmt->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
	$stmt->bindParam(':location', $_POST['location'], PDO::PARAM_STR);
	$stmt->bindParam(':country', $_POST['country'], PDO::PARAM_STR);?>
	<div class="ink-grid all-80 medium-90 small-90">
	<?if ($stmt->execute() == true){?>
		<div class="column-group half-vertical-space">
			<div class="column all-20 large-15 medium-10 small-0 tiny-0"></div>
			<div class="column all-60 large-70 medium-80 small-100 tiny-100 ink-alert block success" role="alert">
				<h4>Information</h4>
				<p>User account created successfully!</p>
				<p>You will be taken shortly to the login page...</p>
			</div>
		</div>
		<?header("Refresh: 5;URL=login.php");
	}else{
		include('message_database.php');
	}?>
	</div>
<?}
	include('../template/footer.php');
?>