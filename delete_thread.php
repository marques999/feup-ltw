<?
	include_once('database/connection.php');
	include_once('database/forum.php');
	include_once('database/users.php');
	include('template/header.php');

	$threadId=0;
	$ownThread=false;

	if (isset($_GET['id'])){
		$threadId=intval($_GET['id']);
		if($threadId<0){
			$threadId=0;
		}
		$thisThread=thread_listById($threadId);
		if(count($thisThread)>0){
			$thisThread=$thisThread[0];
			$ownThread=($thisUser==$thisThread['idUser']);
		}else{
			$thisThread=$defaultThread;
		}
	}
?>
<script>
$(function(){
	$('#nav_forum').addClass('active');
});
</script>
<div class="ink-grid push-center all-50 large-70 medium-80 small-100 tiny-100">
<?if($loggedIn){?>
	<?if($ownThread){?>
	<form action="actions/action_delete_thread.php" method="post" class="ink-form">
	<fieldset class="align-center">
		<legend class="align-center">Delete Thread</legend>
		<p>Are you sure you want to delete thread <b><?=$thisThread['title']?></b>?</p>
		<input type="hidden" name="idThread" value="<?=$threadId?>"/>
		<p class="all-100 align-center">
			<button type="submit" class="ink-button">Delete</button>
			<button type="reset" class="ink-button" onclick="history.go(-1)">Keep</button>
		</p>
	</fieldset>
	 </form>
	<?}else{?>
	<div class="column ink-alert block error">
		<h4>Forbidden</h4>
		<p>You don't have permission to access this page!</p>
		<p>Are you trying to delete events from other users?</p>
	</div>
	<?}?>
<?}else{?>
<div class="column ink-alert block error">
	<h4>Forbidden</h4>
	<p>You don't have permission to access this page!</p>
	<p>Please <a href="login.php">log in</a> with your account first.</p>
</div>
<?}?>
</div>
<?
	include('template/footer.php');
?>