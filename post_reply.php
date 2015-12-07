<?
	include_once('database/connection.php');
	include_once('database/forum.php');
	include_once('database/users.php');
	include('template/header.php');

	$numberPosts=0;
	$isOriginalPost=false;

	if (isset($_GET['id'])){
		$threadId=safe_getId($_GET, 'id');
		$thisThread=thread_listById($threadId);
		if(count($thisThread)>0){
			$thisThread=$thisThread[0];
		}
		else{
			$thisThread=$defaultThread;
		}
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
	&gt; Reply
</h5>
<div class="column-group panel half-vertical-space">
	<div class="column all-15 medium-20 small-100 tiny-100">
		<p><a href="<?=users_viewProfile($thisUser)?>">
			<b><?=$postAuthor['username']?></b>
		</a></p>
		<img class="half-bottom-space" src="<?=users_getSmallAvatar($thisUser)?>">
	</div>
	<form action="actions/action_reply.php" method="post" class="ink-formvalidation all-85 medium-80 small-100 tiny-100">
		<b>Re: <?=$thisThread['title']?></b>
		<p class="no-margin">
			<small class="slab">
			<i class="fa fa-calendar"></i>
			<?=gmdate("l, d/m/Y H:i", $currentDate)?>
			</small>
		</p>
		<?
			include('template/emoticons.php')
		?>
		<input type="hidden" name="idUser" value="<?=$thisUser?>">
		<input type="hidden" name="idThread" value="<?=$threadId?>">
		<div class="control-group column-group required">
			<div class="control">
				<textarea id="message" name="message" data-rules="required" rows="4" cols="80" maxlength="512" data-rules="required" placeholder="Enter your message here..."></textarea>
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