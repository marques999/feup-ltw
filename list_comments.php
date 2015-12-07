<?
	include_once('database/connection.php');
	include_once('database/events.php');
	include_once('database/comments.php');
	include_once('database/users.php');

	if (safe_check($_POST, 'eventId')) {
		$eventId = safe_getId($_POST,'eventId');
		$thisComments = listCommentsByEvent($eventId);
		$numberCommentsPage = 5;
		$numberComments = count($thisComments);
	}
	else {
		$thisEvent = $defaultEvent;
		$eventId = 0;
	}

	if (isset($_POST['startId'])) {
		$startId = safe_getId($_POST,'startId');
		$remainingComments = $numberComments-$startId;
		if ($remainingComments < 5) {
			$numberCommentsPage = $remainingComments;
		}
	}
	else {
		$startId = 0;
	}

	for($i = $startId; $i < $startId + $numberCommentsPage; $i++) {
		$currentComment = $thisComments[$i];
		if(safe_check($currentComment, 'idUser')) {
			$commentAuthorId = $currentComment['idUser'];
		}
		else {
			$commentAuthorId = 0;
		}
		$commentAuthor = $allUsers[$commentAuthorId];?>
		<div class="column all-100 half-vertical-space">
			<img class="push-left quarter-bottom-space half-right-space" src="<?=users_getSmallAvatar($commentAuthorId)?>"/>
			<a href="<?=users_viewProfile($commentAuthorId)?>"><?=$commentAuthor['username']?></a>
			<small><?=gmdate("l, d/m/Y H:i", $currentComment['timestamp'])?></small>
			<p class="fw-medium"><?=$currentComment['message']?></p>
		</div>
	<?}?>
</div>