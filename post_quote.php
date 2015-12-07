<?
	include_once('database/connection.php');
	include_once('database/emoticons.php');
	include_once('database/forum.php');
	include_once('database/users.php');
	include('template/header.php');

	$numberPosts=0;
	$isOriginalPost=false;

	if (isset($_GET['tid'])){
		$threadId=safe_getId($_GET, 'tid');
		$thisThread=thread_listById($threadId);
		if(count($thisThread)>0){
			$thisThread=$thisThread[0];
		}
		else{
			$thisThread=$defaultThread;
		}
	}

	if(isset($_GET['id'])){
		$postId=safe_getId($_GET, 'id');
		$thisPost=post_listById($postId);
		if(count($thisPost)>0){
			$thisPost=$thisPost[0];
		}
		$threadOP=$thisPost['idUser'];
	}
	else{
		$postId=0;
		$thisPost=$thisThread;
		$threadOP=$thisThread['idUser'];
	}

	$thisOP=$allUsers[$threadOP];
	$postAuthor=$allUsers[$thisUser];
?>
<script>
$(function(){
	$('#nav_forum').addClass('active');
});
</script>
<?if($loggedIn){?>
<script>
$(function(){
	$('img.emoticon').each(function() {
		$(this).click(function(evt) {
			$('#message').val(function(i, text) {
				return text + ' ' + $(evt.target).attr('alt');
			});
		});
	});
});
</script>
<div class="ink-grid push-center all-75 large-85 medium-95 small-100 tiny-100">
<h5 class="slab half-vertical-space">
	<a href="forum.php">Forum</a>
	&gt; <a href="view_thread.php?id=<?=$threadId?>"><?=$thisThread['title']?></a>
	&gt; Quote
</h5>
<div class="column-group panel half-vertical-space">
	<div class="column all-15 medium-20 small-100 tiny-100">
		<p><a href="<?=users_viewProfile($thisUser)?>">
			<b><?=$postAuthor['username']?></b>
		</a></p>
		<img class="half-bottom-space" src="<?=users_getSmallAvatar($thisUser)?>">
	</div>
	<form action="actions/action_reply.php" method="POST" class="ink-formvalidation all-85 medium-80 small-100 tiny-100">
		<b>Re: <?=$thisThread['title']?></b>
		<p class="no-margin">
			<small class="slab">
				<i class="fa fa-calendar"></i>
				<?=gmdate("l, d/m/Y H:i", $currentDate)?>
			</small>
		</p>
		<p class="half-vertical-space">
			<small class="no-margin"><?=$thisOP['username']?> wrote:</small>
			<blockquote class="no-margin">
				<?forum_printPost($thisPost, $thisThread)?>
			</blockquote>
		</p>
		<?
			include('template/emoticons.php')
		?>
		<input type="hidden" name="idUser" value="<?=$thisUser?>">
		<input type="hidden" name="idThread" value="<?=$threadId?>">
		<input type="hidden" name="idQuote" value="<?=$postId?>">
		<div class="control-group column-group required">
			<div class="control">
				<textarea id="message" name="message" data-rules="required" rows="4" cols="80" maxlength="512" placeholder="Enter your message here..."></textarea>
			</div>
		</div>
		<div>
			<button type="submit" class="ink-button"><i class="fa fa-share"></i> Reply</button>
			<button type="reset" class="ink-button"><i class="fa fa-eraser"></i> Clear</button>
		</div>
	</form>
</div>
</div>
<?}else{
	include_once('message_guest.php');
}
include('template/footer.php');
?>