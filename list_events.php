<?
	include_once('database/connection.php');
	include_once('database/events.php');

	$inver_sort = $_POST['sortOrder'];
	$sort_type = $_POST['sortType'];
	$curr_pag = $_POST['startEvent'];
	$events_page = $_POST['eventsPerPag'];
	$results = getEventsOrdered($sort_type, $inver_sort, time());
	$maxEvents = count($results);
	$eventParticipants = events_countParticipants();
	$eventInvited = events_countInvites();
	$n_pags = ceil($maxEvents/$events_page);

	if ($curr_pag >= $n_pags) {
		$curr_pag = $n_pags - 1;
	}

	if ($curr_pag < 0) {
		$curr_pag = 0;
	}

	$start = $curr_pag * $events_page;
	$limit = min($start + $events_page, $maxEvents);
?>
<script type="text/javascript" src="js/holder.min.js"></script>
<ul id="display_events" class="column-group unstyled">
<?for($i=$start;$i<$limit;$i++){
	$currentEvent=$results[$i];
	$eventId=safe_getId($currentEvent,'idEvent');
	$currentInvited=$eventInvited[$eventId];
	$currentParticipants=$eventParticipants[$eventId];?>
	<li class="all-25 large-33 medium-50 small-100 tiny-100">
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
<nav id="nav-bar" class="ink-navigation">
<ul class="pagination push-center unstyled">
<?if($curr_pag>0){?>
	<li><a onclick="previous_page()">Previous</a></li>
<?}else{?>
	<li class="disabled"><a>Previous</a></li>
<?}
for($i = $curr_pag - 2; $i <= $curr_pag + 2; $i++) {
	if($i<0||$i>=$n_pags){?>
		<li class="disabled"><a>&nbsp;&nbsp;</a></li>
	<?}else if($i==$curr_pag){?>
		<li class="disabled"><a><?=$i+1?></a></li>
	<?}else{?>
		<li><a onclick="goToPag(<?=$i?>)"><?=$i+1?></a></li>
	<?}?>
<?}
if($curr_pag==$n_pags-1){?>
	<li class="disabled"><a>Next</a></li>
<?}else {?>
	<li><a onclick="next_page()">Next</a></li>
<?}?>
</ul>
</nav>