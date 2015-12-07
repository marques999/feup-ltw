<?
	include_once('database/connection.php');
	include_once('database/events.php');
	include_once('database/users.php');
	include('template/header.php');

	$eventParticipants=events_countParticipants();
	$eventInvited=events_countInvites();
?>

<script>
$(function(){
	$('#nav_events').addClass('active');
});
</script>

<?if($loggedIn){
	$upcomingEvents=users_listFutureEvents($thisUser,$currentDate);
	$numberUpcomingEvents=count($upcomingEvents);
	$myEvents=users_listOwnEvents($thisUser, true);
	$numberOwnEvents=count($myEvents);
?>
<div class="ink-grid all-100">
<div class="column-group gutters">
<div class="column all-50 medium-100 small-100 tiny-100">
	<h3 class="slab half-vertical-space">My Events</h3>
	<p><small>
	<a href="event_create.php" class="ink-button"><i class="fa fa-plus"></i> New Event</a>
	</small></p>
	<?if($numberOwnEvents>0){?>
	<ul class="column-group unstyled">
	<?foreach($myEvents as $currentEvent){
		$eventId=safe_getId($currentEvent,'idEvent');
		$currentInvited=$eventInvited[$eventId];
		$currentParticipants=$eventParticipants[$eventId];
	?>
	<li class="all-50 small-100 tiny-100">
	<div class="panel half-right-space">
		<img class="all-100" src="<?=events_getImage($currentEvent, 'medium')?>"/>
		<div class="all-100 quarter-vertical-space">
			<b class="half-vertical-space">
				<a href="view_event.php?id=<?=$eventId?>"><?=events_getName($currentEvent)?></a>
			</b>
			<p class="no-margin">
				<small class="slab"><i class="fa fa-calendar"></i>
					<?=events_getDate($currentEvent)?>
				</small>
			</p>
			<p class="half-bottom-space">
				<small class="half-right-space"><i class="fa fa-check-circle"></i>
					<?=$currentParticipants['count']?> following
				</small>
				<small><i class="fa fa-question-circle"></i>
					<?=$currentInvited['count']?> invited
				</small>
			</p>
			<p class="no-margin">
				<small><a href="event_update.php?id=<?=$eventId?>" class="ink-button"><i class="fa fa-pencil"></i> Edit</a></small>
				<small><a href="event_delete.php?id=<?=$eventId?>" class="ink-button"><i class="fa fa-remove"></i> Remove</a></small>
			</p>
		</div>
	</div>
	</li>
	<?}?>
	</ul>
	<?}else{?>
		<p class="panel">You have no events :(</p>
	<?}?>
</div>
<div class="column all-50 medium-100 small-100 tiny-100">
	<h3 class="slab half-vertical-space">Upcoming Events</h3>
	<?if($numberUpcomingEvents>0){?>
	<ul class="stage column-group unstyled">
		<?foreach($upcomingEvents as $currentEvent){
		$eventId=safe_getId($currentEvent,'idEvent');
		$currentInvited=$eventInvited[$eventId];
		$currentParticipants=$eventParticipants[$eventId];
	?>
	<li class="all-33 large-50 medium-33 small-50 tiny-50">
	<div class="panel half-right-space">
		<img class="all-100" src="<?=events_getImage($currentEvent, 'medium')?>"/>
		<div class="all-100 half-vertical-space">
			<b class="half-vertical-space">
				<a href="view_event.php?id=<?=$eventId?>">
					<?=events_getName($currentEvent)?>
				</a>
			</b>
			<p class="quarter-bottom-space">
				<small class="quarter-bottom-space slab"><i class="fa fa-calendar"></i>
					<?=events_getDate($currentEvent)?>
				</small>
			</p>
			<p class="half-bottom-space">
				<small class="half-right-space"><i class="fa fa-check-circle"></i>
					<?=$currentParticipants['count']?>
				</small>
				<small><i class="fa fa-question-circle"></i>
					<?=$currentInvited['count']?>
				</small>
			</p>
		</div>
	</div>
	</li>
	<?}?>
	</ul>
	<?}else{?>
		<p class="panel">You have no upcoming events :(</p>
	<?}?>
</div>
</div>
</div>
<?}else{
	include_once('message_guest.php');
}
include('template/footer.php');
?>