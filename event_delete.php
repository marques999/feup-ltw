<?
    include_once('database/connection.php');
    include_once('database/events.php');
    include_once('database/session.php');
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
    $(document).ready(function() {
        $('#nav_events').addClass('active');
    });
</script>

<div class="ink-grid all-50">
<?if($loggedIn&&$ownEvent){?>
<div class="column-group half-vertical-space">
	<div class="column all-100 small-100 tiny-100 ink-alert block info" role="alert">
		<h4>Delete Event</h4>
		<p>Are you sure you want to delete this event?</p>
		<div class="column-group gutters">
			<form action="actions/action_delete_event.php" class="ink-form all-50">
				<div class="control-group column-group align-center half-gutters">
					<div class="control all-30">
						<input type="submit" name="sub" value="Yes" class="ink-button success" />
					</div>
					<div class="control all-30">
						<input type="reset" name="sub" value="No" class="ink-button success" />
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<?}else{?>
	<div class="column-group half-vertical-space">
		<div class="column all-20 large-15 medium-10 small-0 tiny-0"></div>
		<div class="column all-60 large-70 medium-80 small-100 tiny-100 ink-alert block error" role="alert">
			<h4>Forbidden</h4>
			<p>You don't have permission to access this page!</p>
			<p>Please <a href="login.php">log in</a> with your account first.</p>
		</div>
	</div>
<?}?>
</div>
</div>
<?
	include('template/footer.php');
?>