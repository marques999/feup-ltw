<?
	include_once('database/connection.php');
	include_once('database/emoticons.php');
	include_once('database/forum.php');
	include_once('database/users.php');
	include('template/header.php');

	$threadId=0;
	$validReference=false;

	if(isset($_GET['id'])){
		$threadId=safe_getId($_GET, 'id');
		$thisThread=thread_listById($threadId);
		if(count($thisThread)>0){
			$thisThread=$thisThread[0];
			$stmt=$db->prepare('UPDATE ForumThread SET hits = hits + 1 WHERE idThread = :idThread');
			$stmt->bindParam(':idThread', $threadId, PDO::PARAM_INT);
			$stmt->execute();
			$validReference=true;
		}
	}

	if(!$validReference){
		$thisThread=$defaultThread;
	}

	$thisPosts=thread_listPosts($threadId);
	$numberPosts=count($thisPosts);
	$threadOP=$thisThread['idUser'];
	$thisOP=$allUsers[$threadOP];
?>
<script>
$(function(){
	$('#nav_forum').addClass('active');
});
</script>
<?if($loggedIn){?>
<div class="ink-grid push-center all-75 large-85 medium-95 small-100 tiny-100">
<div class="column-group half-vertical-space">
<h5 class="slab half-vertical-space">
	<a href="forum.php">Forum</a>
	&gt; <?=$thisThread['title']?>
</h5>
<div class="column-group panel half-vertical-space">
	<div class="column all-15 medium-20 small-25 tiny-25">
		<p><a href="<?=users_viewProfile($threadOP)?>">
			<b><?=$thisOP['username']?></b>
		</a></p>
		<img class="half-bottom-space" src="<?=users_getSmallAvatar($threadOP)?>">
	</div>
	<div class="column all-85 medium-80 small-75 tiny-75">
		<b><?=$thisThread['title']?></b>
		<p class="no-margin">
			<small class="slab">
			<i class="fa fa-calendar"></i>
			<?=forum_printDate($thisThread)?>
			</small>
		</p>
		<?forum_printPost(null, $thisThread)?>
		<p class="no-margin align-right">
			<small><a href="post_reply.php?id=<?=$threadId?>" class="ink-button"><i class="fa fa-pencil"></i> Reply</a></small>
			<small><a href="post_quote.php?tid=<?=$threadId?>" class="ink-button"><i class="fa fa-quote-right"></i> Quote</a></small>
			<?if($threadOP==$thisUser){?>
			<small><a href="delete_thread.php?id=<?=$threadId?>" class="ink-button"><i class="fa fa-remove"></i> Delete</a></small>
			<?}?>
		</p>
	</div>
</div>
<?$i=0;?>
<?if($numberPosts>0){
	foreach($thisPosts as $currentPost){
	$postId=$currentPost['idPost'];
	$thisPoster=$currentPost['idUser'];
	if($postId<0){
		$postId=0;
	}
	if($i==$numberPosts-1){?>
	<div id="last" class="column-group panel half-vertical-space">
	<?}else{?>
	<div class="column-group panel half-vertical-space">
	<?}?>
	<div class="column all-15 medium-20 small-25 tiny-25">
		<p><a href="<?=users_viewProfile($thisPoster)?>">
			<b><?=$currentPost['username']?></b>
		</a></p>
		<img class="half-bottom-space" src="<?=users_getSmallAvatar($thisPoster)?>">
	</div>
	<div class="column all-85 medium-80 small-75 tiny-75">
		<b>Re: <?=$thisThread['title']?></b>
		<p class="no-margin">
			<small class="slab">
			<i class="fa fa-calendar"></i>
			<?=forum_printDate($currentPost)?>
			</small>
		</p>
		<?forum_printPost($currentPost, $thisThread)?>
		<p class="no-margin align-right">
			<small><a href="post_reply.php?id=<?=$threadId?>" class="ink-button"><i class="fa fa-pencil"></i> Reply</a></small>
			<small><a href="post_quote.php?tid=<?=$threadId?>&id=<?=$postId?>" class="ink-button"><i class="fa fa-quote-right"></i> Quote</a></small>
			<?if($currentPost['idUser']==$thisUser){?>
			<small><a href="delete_post.php?id=<?=$postId?>" class="ink-button"><i class="fa fa-remove"></i> Delete</a></small>
			<?}?>
		</p>
	</div>
	</div>
	<?$i++?>
	<?}?>
<?}else{?>
	<p class="panel">This forum thread has no replies :(</p>
<?}?>
</div>
</div>
<?}else{
	include_once('message_guest.php');
}
include('template/footer.php');
?>