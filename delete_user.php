<?
	include_once('database/connection.php');
	include_once('database/session.php');
	include_once('database/users.php');
	include('template/header.php');

	$userId=0;
	$sameUser=false;

	if(isset($_GET['id'])&&$loggedIn){
		$userId=intval($_GET['id']);
		$userExists=users_idExists($userId);
		if($userExists){
			$sameUser=($userId==$thisUser);
		}
	}
?>

<div class="ink-grid push-center all-50 large-70 medium-80 small-100 tiny-100">
<?if($loggedIn){?>
	<?if($sameUser){?>
	<div class="column all-100 ink-alert block info">
		<h4>Delete Account</h4>
		<p>Are you sure you want to delete your <strong>user account</strong>?</p>
		<p>You will be signed out automatically...</p>
		<form action="actions/action_delete_event.php" class="ink-form">
		<div class="control-group column-group half-gutters">
			<div class="control all-100 align-center">
				<span class="align-center">
					<input type="submit" name="result" value="Yes" class="ink-button all-20"/>
					<input type="reset" name="result" value="No" class="ink-button all-20"/>
				</span>
			</div>
		</div>
		</form>
	</div>
	<?}else{?>
	<div class="column ink-alert block error">
		<h4>Forbidden</h4>
		<p>You don't have permission to access this page!</p>
		<p>Are you trying to delete accounts from other users?</p>
	</div>
	<?}?>
<?}else{?>
<div class="column ink-alert block error">
	<h4>Forbidden</h4>
	<p>You don't have permission to access this page!</p>
	<p>Please <a href="login.php">log in</a> with your account first.</p>
</div>
<?}?>
</div>

<?
	include('template/footer.php');
?>