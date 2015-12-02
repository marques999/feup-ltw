<?
	include_once('database/connection.php');
	include_once('database/forum.php');
	include_once('database/users.php');
	include('template/header.php');

	if($loggedIn){
		$postAuthor=$allUsers[$thisUser];
	}
?>

<script>
$(function(){
	$('#nav_forum').addClass('active');
});
</script>

<?if($loggedIn){?>
<script>
$(function(){
	$('img.emoticon').each(function(){
		$(this).click(function(evt){
			evt.preventDefault();
			$('#message').val(function(i, text){		
				return text + ' ' + $(evt.target).attr('alt');
			});
		});
	});
});
</script>
<div class="ink-grid push-center all-75 medium-90 small-100 tiny-100">
<h5 class="slab half-vertical-space">
	<a href="forum.php">Forum</a>
	&gt; New Thread
</h5>
<div class="column-group panel gutters half-vertical-space">
	<div class="column all-15">
		<p><a href="<?=users_viewProfile($thisUser)?>">
			<b><?=$postAuthor['username']?></b>
		</a></p>
		<img class="half-bottom-space" src="<?=users_getSmallAvatar($thisUser)?>">
	</div>
	<form action="actions/action_create_thread.php" method="post" class="ink-form ink-formvalidation">
	<div class="column all-85">
	<div class="control-group required">
		<div class="control">
			<input name="title" type="text" data-rules="required|alpha_numeric" placeholder="Enter a thread title...">
		</div>
	</div>
	<p class="no-margin">
		<small class="slab">
		<i class="fa fa-calendar"></i>
		<?=gmdate("l, d/m/Y H:i", $currentDate)?>
		</small>
	</p>
	<?
		include('template/emoticons.php')
	?>
	<input type="hidden" name="idUser" value="<?=$thisUser?>"></input>
		<div class="control-group column-group">
			<div class="control required">
				<textarea id="message" name="message" rows="4" cols="80" placeholder="Enter your message here..."></textarea>
			</div>
		</div>
		<div>
			<button type="submit" name="sub" class="ink-button"><i class="fa fa-share"></i> Submit</button>
			<button type="reset" name="sub" value="Clear" class="ink-button"><i class="fa fa-eraser"></i> Clear</button>
		</div>
	</div>
</form>
</div>
</div>
<?}else{
	include_once('message_guest.php');
}
include('template/footer.php');
?>