<?
	include_once('database/connection.php');
	include_once('database/users.php');
	include_once('database/session.php');
	include('template/header.php');
	
	if(safe_check($_GET,'id')&&$loggedIn){
		$userId=safe_getId($_GET,'id');
		$userExists=users_idExists($userId);
		$isOwner=$userExists&&($userId==$thisUser);
	}
	else {
		$isOwner=false;
		$userId=0;
	}
?>

<script>
$(function(){
	$('#nav_profile').addClass('active');
});
</script>

<?if($loggedIn){?>
	<?if($isOwner){?>
	<div class="ink-grid push-center all-50 large-70 medium-80 small-100 tiny-100">
		<div class="column all-100 ink-alert block error">
			<h4>Delete Account</h4>
			<p>Are you sure you want to delete your user account <strong><?=$_SESSION['username']?></strong>?</p>
			<p><strong>WARNING: </strong>All your events, comments and forum posts will be deleted automatically and you will be signed out shortly after...</p>
			<form action="actions/action_delete_user.php" method="post" class="ink-form">
				<input type="hidden" name="idUser" value="<?=$userId?>"/>
				<p class="all-100 align-center">
					<button type="submit" class="ink-button">Yes</button>
					<button type="reset" class="ink-button" onclick="history.go(-1)">No</button>
				</p>
			</form>
		</div>
	</div>
	<?}else{?>
	<div class="ink-grid push-center all-50 large-70 medium-80 small-100 tiny-100">
		<div class="column ink-alert block error">
			<h4>Forbidden</h4>
			<p>You don't have permission to access this page!</p>
			<p>Are you trying to delete accounts from other users?</p>
		</div>
	</div>
	<?}?>
<?}else{
	include_once('message_guest.php');
}?>
<?
	include('template/footer.php');
?>