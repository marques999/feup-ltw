<?
    include_once('database/connection.php');
    include_once('database/events.php');
    include_once('database/users.php');
    include('template/header.php');

	$eventParticipants = events_countParticipants();
	$eventInvited = events_countInvites();

	if ($loggedIn) {
		$upcomingEvents = users_listFutureEvents($thisUser, $currentDate);
		$numberUpcomingEvents = count($upcomingEvents);
		$myEvents = users_listOwnEvents($thisUser, true);
		$numberOwnEvents = count($myEvents);
	}
?>

<script>
$(function() {
	$('#nav_events').addClass('active');
});
</script>

<?if($loggedIn){?>
<div class="ink-grid all-100">
<div class="column-group gutters half-vertical-space">

	
<!-- BEGIN CURRENT EVENTS SECTION -->
<div class="column all-50 medium-100 small-100 tiny-100">
	<h3 class="slab half-vertical-space">My Events</h3>
    <ul class="column-group unstyled">
	<?if($numberOwnEvents>0) {					
		foreach($myEvents as $currentEvent) {			
		$eventId = $currentEvent['idEvent'] - 1;
		if ($eventId < 0){
			$eventId = 0;
		}?>
	        <li class="panel half-right-space all-33 large-45 medium-45 small-100 tiny-100">
			<img class="all-100" src="holder.js/400x256/auto/ink"/>
			<div class="all-100 half-vertical-space">
				<b class="half-vertical-space">
					<a href="view_event.php?id=<?=$currentEvent['idEvent']?>"><?=events_getName($currentEvent)?></a>
				</b>
	            <p class="no-margin">
	            	<small class="slab">
	            	<i class="fa fa-calendar"></i>
	            	<?=date("l, d/m/Y H:i", $currentEvent['date'])?>
	            	</small>
	            </p>
	            <p class="half-bottom-space">
                	<small class="half-right-space"><i class="fa fa-check-circle"></i> <?=$eventParticipants[$eventId]['count']?> following</small>
                	<small><i class="fa fa-question-circle"></i> <?=$eventInvited[$eventId]['count']?> invited</small>
                </p>
                <p class="no-margin">
	            	 <small><button class="ink-button red"><i class="fa fa-pencil"></i> Edit</button></small>
	            	 <small><button class="ink-button red"><i class="fa fa-remove"></i> Remove</button></small>
	           	</p>
				</div>
	        </li>
		<?}?>
    <?}else{?>
		<li class="panel all-100">
			<span>You have no events :(</span>
		</li>
	<?}?>
	</ul>
</div>
<!-- END CURRENT EVENTS SECTION -->


<!-- BEGIN UPCOMING EVENTS SECTION -->
<div class="column all-50 medium-100 small-100 tiny-100">
	<h3 class="slab half-vertical-space">Upcoming Events</h3>
    <ul class="stage column-group unstyled">
	<?if($numberUpcomingEvents>0){?>
		<?foreach($upcomingEvents as $currentEvent){
		$eventId = $currentEvent['idEvent'] - 1;
		if ($eventId < 0){
			$eventId = 0;
		}?>
		<li class="slide panel half-padding half-right-space all-30 large-45 medium-30 small-45 tiny-45">	   
            <img class="all-100" src="holder.js/200x128/auto/ink"/>
            <div class="all-100 half-vertical-space">
				<b class="half-vertical-space">
					<a href="view_event.php?id=<?=$currentEvent['idEvent']?>"><?=events_getName($currentEvent)?></a>
				</b>
	            <p class="quarter-bottom-space">
	            	<small class="quarter-bottom-space slab">
	            	<i class="fa fa-calendar"></i>
	            	<?=date("l, d/m/Y H:i", $currentEvent['date'])?>
	            	</small>
	            </p>
	            <p class="half-bottom-space">
                	<small class="half-right-space"><i class="fa fa-check-circle"></i> <?=$eventParticipants[$eventId]['count']?></small>
                	<small><i class="fa fa-question-circle"></i> <?=$eventInvited[$eventId]['count']?></small>
                </p>
         	</div>
		</li>
		<?}?>
	<?}else{?>
		<li class="panel all-100">
			<span>You have no upcoming events :(</span>
		</li>
	<?}?>
</div>
<!-- END UPCOMING EVENTS SECTION -->


</div>
</div>
<?}else{?>
<div class="ink-grid all-45 large-60 medium-80 small-100 tiny-100">
	<div class="column ink-alert block error">
		<h4>Forbidden</h4>
		<p>You don't have permission to access this page!</p>
		<p>Please <a href="login.php">log in</a> with your account first.</p>
	</div>
</div>
<?}?>

<?
	include('template/footer.php');
?>