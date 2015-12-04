<?
	include_once('database/connection.php');
	include_once('database/events.php');

	$sort_name = $_POST['sortName'];
	$sort_type = $_POST['sortType'];
	$date_options = $_POST['dateOptions'];

	if($_POST['sortDate1'] != "") {
		 $sort_date1 = strtotime($_POST['sortDate1']) + 60*60;
	}

	if($_POST['sortDate2'] != "") {
		$sort_date2 = strtotime($_POST['sortDate2']) + 60*60;
	}

	$results = listFilteredEvents('', $sort_type, $sort_date1, $sort_date2, $date_options,1, 'name');
	$eventParticipants=events_countParticipants();
	$eventInvited=events_countInvites();
	$maxEvents = count($results);
?>

<script type="text/javascript" src="js/holder.min.js"></script>
<script>
$(function(){
	$('#nav_browse').addClass('active');
});
</script>
<ul id="display_events" class="stage column-group unstyled">
	<?for($i = 0; $i < $maxEvents; $i++) {
		$currentEvent = $results[$i];
		if ($sort_name != '' && stripos($currentEvent['name'], $sort_name) === false) {
			continue;
		}
		$eventId=safe_getId($currentEvent, 'idEvent');
		$currentInvited=$eventInvited[$eventId];
		$currentParticipants=$eventParticipants[$eventId];
	?>
	<li class="all-25 large-50 medium-50 small-100 tiny-50">
		<div class="panel half-right-space">
		<img class="all-100" src="holder.js/200x128/auto/ink"/>
		<div class="all-100 half-vertical-space">
			<b class="half-vertical-space">
				<a href="view_event.php?id=<?=$eventId?>"><?=$currentEvent['name']?></a>
			</b>
			<p/>
			<p class="quarter-bottom-space">
				<small class="quarter-bottom-space slab"><i class="fa fa-calendar"></i>
					<?=gmdate("l, d/m/Y H:i",$currentEvent['date'])?>
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