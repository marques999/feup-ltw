<?
	include_once('database/connection.php');
	include_once('database/forum.php');
	include_once('database/users.php');
	include('template/header.php');

	if(isset($_GET['id'])){

		$threadId=intval($_GET['id']);

		if($threadId<0){
			$threadId=0;
		}

		$thisThread=thread_listById($threadId);
		$thisPosts=thread_listPosts($threadId);

		if(count($thisThread)>0){
			$thisThread=$thisThread[0];
			$stmt=$db->prepare('UPDATE ForumThread SET hits = hits + 1');
			$stmt->execute();
		}
	}

	$numberPosts=count($thisPosts);
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
	&gt; <?=$thisThread['title']?>
</h5>
<div class="column-group panel half-vertical-space">
	<div class="column all-15 small-25 tiny-25">
		<p><a href="<?=users_viewProfile($threadOP)?>">
			<b><?=$thisOP['username']?></b>
		</a></p>
		<img class="half-bottom-space" src="<?=users_getSmallAvatar($threadOP)?>">
	</div>
	<div class=" column all-85 small-75 tiny-75">
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
		<p class="no-margin align-right">
			<small><a href="#" class="ink-button"><i class="fa fa-pencil"></i> Reply</a></small>
			<small><a href="quote_thread.php?id=<?=$threadId?>" class="ink-button"><i class="fa fa-quote-right"></i> Quote</a></small>
			<?if($threadOP==$thisUser){?>
			<small><a href="delete_thread.php?id=<?=$threadId?>" class="ink-button"><i class="fa fa-remove"></i> Delete</a></small>
			<?}?>
		</p>
	</div>
</div>

<?if($numberPosts>0){
	foreach($thisPosts as $currentPost){
	$postId=$currentPost['idPost']-1;
	$thisPoster=$currentPost['idUser'];
	if($postId<0){
		$postId=0;
	}?>
	<div class="column-group panel half-vertical-space">
		<div class="column all-15">
			<p><a href="<?=users_viewProfile($thisPoster)?>">
				<b><?=$currentPost['username']?></b>
			</a></p>
			<img class="half-bottom-space" src="<?=users_getSmallAvatar($thisPoster)?>">
		</div>
		<div class=" column all-85">
		<b>Re: <?=$thisThread['title']?></b>
		<p class="no-margin">
			<small class="slab">
			<i class="fa fa-calendar"></i>
			<?=forum_printDate($currentPost)?>
			</small>
		</p>
		<?if(isset($currentPost['idQuote'])){
			$quoteId = $currentPost['idQuote'];

			if ($quoteId==0) {
				$quotedPost = $thisThread;
			}
			else {
				$quotedPost = $thisPosts[$quoteId];
			}
		?>
		<p class="half-vertical-space">
			<small class="no-margin"><?=$quotedPost['username']?> wrote:</small>
			<blockquote class="no-margin">
				<?=$quotedPost['message']?>
			</blockquote>
		</p>
		<?}?>
		<p class="vertical-space"><?=$currentPost['message']?></p>
			<p class="no-margin align-right">
			<small><a href="#write-comment" class="ink-button"><i class="fa fa-pencil"></i> Reply</a></small>
			<?if($currentPost['idUser']==$thisUser){?>
			<small><a class="ink-button"><i class="fa fa-remove"></i> Delete</a></small>
			<?}else{?>
			<small><a class="ink-button"><i class="fa fa-quote-right"></i> Quote</a></small>
			<?}?>
		</p>
		</div>
	</div>
	<?}?>
<?}else{?>
	<p class="panel">This forum thread has no replies :(</p>
<?}?>

<!-- BEGIN REPLY SECTION -->
<div class="all-50" id="write-comment">
<b>Write a reply</b>
<form action="actions/action_reply.php" method="POST" class="ink-form ink-formvalidation">
	<input type="hidden" name="idUser" value="<?=$_SESSION['userid']?>"></input>
	<input type="hidden" name="idThread" value="<?=$threadId?>"></input>
	<div class="control-group column-group">
		<div class="control required all-100">
			<textarea name="message" rows="4" cols="80" placeholder="Insert your message here..."></textarea>
		</div>
	</div>
	<div class="no-margin all-100">
		<button type="submit" name="sub" class="ink-button success">
		<i class="fa fa-share"></i> Reply
		</button>
		<button type="reset" name="sub" value="Clear" class="ink-button">
		<i class="fa fa-eraser"></i> Clear
		</button>
	</div>
</form>
</div>
<!-- END DYNAMIC SECTION -->
</div>
</div>
<?}else{?>
<div class="ink-grid push-center all-45 large-60 medium-80 small-100 tiny-100">
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