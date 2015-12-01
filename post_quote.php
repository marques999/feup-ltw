<?
	include_once('database/connection.php');
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
<div class="ink-grid push-center all-75 medium-90 small-100 tiny-100">
<h5 class="slab half-vertical-space">
	<a href="forum.php">Forum</a>
	&gt; <a href="view_thread.php?id=<?=$threadId?>"><?=$thisThread['title']?></a>
	&gt; Quote
</h5>
<div class="column-group panel gutters half-vertical-space">
	<div class="column all-15">
		<p><a href="<?=users_viewProfile($thisUser)?>">
			<b><?=$postAuthor['username']?></b>
		</a></p>
		<img class="half-bottom-space" src="<?=users_getSmallAvatar($thisUser)?>">
	</div>
	<div class=" column all-85">
		<b>Re: <?=$thisThread['title']?></b>
		<p class="no-margin">
			<small class="slab">
			<i class="fa fa-calendar"></i>
			<?=forum_printDate($thisPost)?>
			</small>
		</p>
		<p class="half-vertical-space">
			<small class="no-margin"><?=$thisOP['username']?> wrote:</small>
			<blockquote class="no-margin">
				<?=$thisPost['message']?>
			</blockquote>
		</p>
		<?
			include('template/emoticons.php')
		?>
		<form action="actions/action_reply.php" method="POST" class="ink-form ink-formvalidation all-100">
			<input type="hidden" name="idUser" value="<?=$thisUser?>"></input>
			<input type="hidden" name="idThread" value="<?=$threadId?>"></input>
			<input type="hidden" name="idQuote" value="<?=$postId?>"></input>
			<div class="control-group">
				<div class="control required all-100">
					<textarea id="message" name="message" rows="4" cols="80" maxlength="512" data-rules="required" placeholder="Enter your message here..."></textarea>
				</div>
			</div>
			<div class="no-margin all-100">
				<button type="submit" class="ink-button"><i class="fa fa-share"></i> Reply</button>
				<button type="reset" class="ink-button"><i class="fa fa-eraser"></i> Clear</button>
			</div>
		</form>
	</div>
</div>
</div>
<?}else{
	include_once('message_guest.php');
}
include('template/footer.php');
?>