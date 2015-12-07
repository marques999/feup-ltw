<?
	include_once('database/connection.php');
	include_once('database/forum.php');
	include_once('database/users.php');
	include('template/header.php');

	$allThreads=forum_allThreads();
	$numberReplies=forum_countReplies();
	$lastReplies=forum_lastReplies();
?>
<script>
$(function(){
	$('#nav_forum').addClass('active');
});
</script>
<?if($loggedIn){?>
<div class="ink-grid push-center all-100">
<p><small>
	<a href="create_thread.php" class="ink-button"><i class="fa fa-plus"></i> New Thread</a>
</small></p>
<table class="ink-table alternating hover">
<thead>
	<th>Topic</th>
	<th>Replies</th>
	<th>Author</th>
	<th>Views</th>
	<th>Last Post</th>
</thead>
<tbody>
<?foreach($allThreads as $currentThread){
	$threadId=$currentThread['idThread'];
	$hasReplies=isset($lastReplies[$threadId]);
	$threadUser=$currentThread['idUser'];
	$thisOP=$allUsers[$threadUser];
	if($hasReplies){
		$lastPosterId=$lastReplies[$threadId]['idUser'];
		$lastPoster=$allUsers[$lastPosterId];
		$lastReply=$lastReplies[$threadId];
	}else{
		$lastPosterId=$threadUser;
		$lastPoster=$allUsers[$lastPosterId];
		$lastReply=$currentThread;
	}
	if($threadId<0){
		$threadId=0;
	}?>
	<tr>
		<td>
			<a href="<?=forum_viewThread($currentThread)?>"><?=$currentThread['title']?></a>
			<p><small><?=forum_printDate($currentThread)?></small></p>
		</td>
		<?if($hasReplies){?>
			<td class="align-center"><?=$numberReplies[$threadId]?></td>
		<?}else{?>
			<td class="align-center">0</td>
		<?}?>
		<td>
			<a href="<?=users_viewProfile($threadUser)?>">
				<img src="<?=users_getSmallAvatar($threadUser)?>">
			</a>
			<span class="half-horizontal-space vertical-space">
				<a href="<?=users_viewProfile($threadUser)?>"><?=$thisOP['username']?></a>
			</span>
		</td>
		<td class="align-center"><?=$currentThread['hits']?></td>
		<td>
			<i class="fa fa-user"></i>
			<a href="<?=users_viewProfile($lastPosterId)?>"><?=$lastPoster['username']?></a>
			<p><small><?=forum_printDate($lastReply)?></small></p>
		</td>
	</tr>
<?}?>
</tbody>
</table>
</div>
<?}else{
	include_once('message_guest.php');
}
include('template/footer.php');
?>