<?
	include_once('database/connection.php');
	include_once('database/events.php');
	include_once('database/comments.php');
	include_once('database/users.php');

	$thisEvent = $defaultEvent;
	$eventId=safe_getId($_POST,'eventId');
	$startId=safe_getId($_POST,'startId');

	if (isset($_POST['eventId'])) {
		$thisComments=listCommentsByEvent($eventId);
		$numberCommentsPage=5;
		$numberComments=count($thisComments);
	}

	if(isset($_POST['startId'])){	
		$remainingComments=$numberComments-$startId;
		if($remainingComments<5){
			$numberCommentsPage=$remainingComments;
		}
	}

	for($i=$startId;$i<$startId+$numberCommentsPage;$i++){
		$currentComment=$thisComments[$i];
		if(isset($currentComment['idUser'])){
			$commentAuthorId = $currentComment['idUser'];
		}
		else{
			$commentAuthorId = 0;
		}
		$commentAuthor=$allUsers[$commentAuthorId];?>
		<div class="column all-100 half-vertical-space">
			<img class="push-left half-right-space" src="<?=users_getSmallAvatar($commentAuthorId)?>"/>
			<a href="<?=users_viewProfile($commentAuthorId)?>"><?=$commentAuthor['username']?></a>
			<small><?=gmdate("l, d/m/Y H:i", $currentComment['timestamp'])?></small>
			<p class="fw-medium"><?=$currentComment['message']?></p>
		</div>
	<?}?>
</div>