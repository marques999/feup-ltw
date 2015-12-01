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
$(function() {
	$('img.emoticon').each(function() {
		$(this).click(function(evt) {
			$('#message').val(function(i, text) {
				evt.preventDefault();
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
	<nav class="ink-navigation half-vertical-space">
    	<ul class="pagination no-padding">
		<li><button><img class="emoticon" alt=":S" src="img/awww.png"></button></li>
		<li><button><img class="emoticon" alt=":@" src="img/angry.png"></button></li>
		<li><button><img class="emoticon" alt=":|" src="img/disheartened.png"></button></li>
		<li><button><img class="emoticon" alt="XD" src="img/ecstatic.png"></button></li>
		<li><button><img class="emoticon" alt=":D" src="img/great.png"></button></li>
		<li><button><img class="emoticon" alt=":x" src="img/mouthshut.png"></button></li>
		<li><button><img class="emoticon" alt=":)" src="img/nice.png"></button></li>
		<li><button><img class="emoticon" alt=":o" src="img/omg.png"></button></li>
		<li><button><img class="emoticon" alt=":(" src="img/sad.png"></button></li>
		<li><button><img class="emoticon" alt=":P" src="img/tongue.png"></button></li>
		</ul>
	</nav>
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