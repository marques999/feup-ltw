<?
	include_once('database/connection.php');
	include_once('database/events.php');
	include_once('database/users.php');
	include_once('database/session.php');
	include('template/header.php');

	$thisEvent = $defaultEvent;
	$eventId = 0;
	$ownEvent = false;

	if (isset($_GET['id']) && $loggedIn) {
		$eventId = intval($_GET['id']);
		$thisEvent = events_listById($eventId);
		if (is_array($thisEvent)&&count($thisEvent)>0) {
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
<?if($loggedIn){?>
	<?if($ownEvent){?>
	<div class="ink-grid push-center all-50 large-70 medium-80 small-100 tiny-100">
	<div class="column ink-alert block error">
		<h4>Delete Event</h4>
		<p>Are you sure you want to delete event <b><?=$thisEvent['name']?></b>?</p>
		<p>All comments and invites associated with this event will be deleted automatically...</p>
		<form action="actions/action_delete_event.php" method="post" class="ink-form">
			<input type="hidden" name="idEvent" value="<?=$eventId?>"/>
			<p class="align-center">
				<button type="submit" class="ink-button">Delete</button>
				<button type="reset" class="ink-button" onclick="history.go(-1)">Keep</button>
			</p>
		</form>
	</div>
	<?}else{?>
	<div class="ink-grid push-center all-50 large-70 medium-80 small-100 tiny-100">
		<div class="column ink-alert block error">
			<h4>Forbidden</h4>
			<p>You don't have permission to access this page!</p>
			<p>Are you trying to delete events from other users?</p>
		</div>
	</div>
	<?}?>
<?}else{
	include_once('message_guest.php');
}
include('template/footer.php');
?>