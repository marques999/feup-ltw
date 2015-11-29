<?
	include_once('database/connection.php');
	include_once('database/forum.php');
	include_once('database/users.php');
	include('template/header.php');

	$numberPosts=0;

	if (isset($_GET['id'])){
		$postId=intval($_GET['id']);
		if($postId<0){
			$postId=0;
		}
		$thisThread=thread_listById($threadId);
		$thisPosts=thread_listPosts($threadId);
		$numberPosts=count($thisPosts);
		if(count($thisThread)>0){
			$thisThread=$thisThread[0];
		}else{
			$thisThread=$defaultThread;
		}
	}

	$threadOP=$thisThread['idUser'];
	$thisOP=$allUsers[$threadOP];
?>
<script>
$(function() {
	$('#nav_forum').addClass('active');
});
</script>
<?if($loggedIn){?>
<div class="ink-grid push-center all-75 medium-90 small-100 tiny-100">
<div class="column-group half-vertical-space">
<!-- BEGIN CURRENT EVENTS SECTION -->
<h5 class="slab half-vertical-space">
	<a href="forum.php">Forum</a>
	&gt; <a href="view_thread.php?id=<?=$threadId?>"><?=$thisThread['title']?></a>
	&gt; Reply
</h5>
<div class="column-group panel half-vertical-space">
	<div class="column all-15">
		<p><a href="<?=users_viewProfile($threadOP)?>">
			<b><?=$thisOP['username']?></b>
		</a></p>
		<img class="half-bottom-space" src="<?=users_getSmallAvatar($threadOP)?>">
	</div>
	<div class=" column all-85">
		<b><?=$thisThread['title']?></b>
		<p class="no-margin">
			<small class="slab">
			<i class="fa fa-calendar"></i>
			<?=forum_printDate($thisThread)?>
			</small>
		</p>
		<p class="vertical-space">
			<?=$thisThread['message']?>
		</p>
	</div>
</div>

<!-- BEGIN DYNAMIC SECTION -->
	<div class="all-50"id="write-comment">
	<b>Write a reply</b>
	<form action="actions/action_reply.php" method="POST" class="ink-form ink-formvalidation all-100">
		<input type="hidden" name="idUser" value="<?=$_SESSION['userid']?>"></input>
		<input type="hidden" name="idEvent" value="<?=$eventId?>"></input>
		<div class="control-group column-group">
			<div class="control required all-100">
				<textarea name="message" rows="4" cols="80" placeholder="Insert your message here..."></textarea>
			</div>
		</div>
		<div class="no-margin all-100">
			<button type="submit" name="sub" class="ink-button red success">
			<i class="fa fa-share"></i> Reply
			</button>
			<button type="reset" name="sub" value="Clear" class="ink-button red">
			<i class="fa fa-eraser"></i> Clear
			</button>
		</div>
	</form>
	</div>
	<!-- END DYNAMIC SECTION -->
</div>
<!-- END CURRENT EVENTS SECTION -->
</div>
<?}else{?>
<div class="ink-grid push-center all-50 large-70 medium-80 small-100 tiny-100">
	<div class="column ink-alert block error">
		<h4>Forbidden</h4>
		<p>You don't have permission to access this page!</p>
		<p>Please <a href="login.php">log in</a> with your account first.</p>
	</div>
</div>
<?}?>
<?
	include('template/footer.php');
?>