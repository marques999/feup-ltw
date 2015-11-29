<?
	include_once('database/connection.php');
	include_once('database/events.php');
	include_once('database/users.php');
	include('template/header.php');

	$thisEvent = $defaultEvent;
	$eventId = 0;
	$ownEvent = false;

	if (isset($_GET['id']) && $loggedIn) {
		$eventId = intval($_GET['id']);
		$thisEvent = events_listById($eventId);

		if (count($thisEvent > 0)) {
			$thisEvent = $thisEvent[0];
			$ownEvent = $thisUser == $thisEvent['idUser'];
		}
	}
?>
<script>
$(function(){
	$('#nav_events').addClass('active');
});
</script>
<div class="ink-grid push-center all-50 large-70 medium-80 small-100 tiny-100">
<?if($loggedIn){?>
	<?if($ownEvent){?>
	<div class="column ink-alert block info">
		<h4>Delete Event</h4>
		<p>Are you sure you want to delete <b><?=$thisEvent['name']?></b>?</p>
		<form action="actions/action_delete_event.php" method="post" class="ink-form">
		<div class="control-group half-gutters">
			<div class="control push-center all-80">
				<span class="align-center">
					<input type="hidden" name="idEvent" value="<?=$eventId?>"/>
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
		<p>Are you trying to delete events from other users?</p>
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