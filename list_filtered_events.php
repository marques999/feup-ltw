<?
	include_once('database/connection.php');
	include_once('database/events.php');

	$sort_name = $_POST['sortName'];
	$sort_type = $_POST['sortType'];
	$date_options = $_POST['dateOptions'];

	if (!empty($_POST['sortDate1'])) {
		$sort_date1 = strtotime($_POST['sortDate1']) + 60*60;
	}

	if (!empty($_POST['sortDate2'])) {
		$sort_date2 = strtotime($_POST['sortDate2']) + 60*60;
	}

	$results = listFilteredEvents($sort_name, $sort_type, $sort_date1, $sort_date2, $date_options,1, 'name');
	$eventParticipants = events_countParticipants();
	$eventInvited = events_countInvites();
?>
<script type="text/javascript" src="js/holder.min.js"></script>
<ul id="display_events" class="column-group unstyled">
<?foreach($results as $currentEvent){
	$eventId=safe_getId($currentEvent,'idEvent');
	$currentInvited=$eventInvited[$eventId];
	$currentParticipants=$eventParticipants[$eventId];?>
	<li class="column all-25 large-33 medium-50 small-100 tiny-100">
	<div class="panel half-right-space">
		<img class="all-100" src="<?=events_getImage($currentEvent, 'medium')?>"/>
		<div class="all-100 quarter-vertical-space">
			<b class="half-vertical-space">
				<a href="view_event.php?id=<?=$eventId?>"><?=$currentEvent['name']?></a>
			</b>
			<p class="quarter-bottom-space">
				<small class="quarter-bottom-space slab"><i class="fa fa-calendar"></i>
					<?=events_getDate($currentEvent)?>
				</small>
			</p>
			<p class="no-margin">
				<small class="quarter-bottom-space slab"><i class="fa fa-hashtag"></i>
					<?=$currentEvent['type']?>
				</small>
			</p>
			<p class="no-margin">
			<small class="half-right-space"><i class="fa fa-check-circle"></i>
				<?=$currentParticipants['count']?> following
			</small>
			<small><i class="fa fa-question-circle"></i>
				<?=$currentInvited['count']?> invited
			</small>
		</p>
		</div>
	</div>
	</li>
<?}?>
</ul>