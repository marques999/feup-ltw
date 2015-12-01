<?
	include_once('database/connection.php');
	include_once('database/forum.php');
	include_once('database/users.php');
	include('template/header.php');

	$threadId=0;
	$thisThread=$defaultThread;

	if (isset($_GET['id'])){
		$threadId=safe_getId($_GET, 'id');
		$getThread=thread_listById($threadId);
		if(count($getThread)>0){
			$thisThread=$getThread[0];
		}
	}

	if($threadId==0){
		$thisThread=$defaultThread;
	}

	$ownThread=($thisUser==$thisThread['idUser']);
?>

<script>
$(function(){
	$('#nav_forum').addClass('active');
});
</script>

<?if($loggedIn){?>
	<?if($ownThread){?>
	<div class="ink-grid push-center all-50 large-70 medium-80 small-100 tiny-100">
		<div class="column all-100 ink-alert block error">
			<h4>Delete Thread</h4>
			<p>Are you sure you want to delete thread <b><?=$thisThread['title']?></b>?</p>
			<p><strong>WARNING: </strong>All posts from the current thread will be deleted automatically...</p>
			<form action="actions/action_delete_thread.php" method="post" class="ink-form">
				<input type="hidden" name="idThread" value="<?=$threadId?>"/>
				<p class="align-center">
					<button type="submit" class="ink-button">Delete</button>
					<button type="reset" class="ink-button" onclick="history.go(-1)">Keep</button>
				</p>
		 	</form>
		 </div>
	</div>
	<?}else{?>
	<div class="ink-grid push-center all-50 large-70 medium-80 small-100 tiny-100">
	<div class="column ink-alert block error">
		<h4>Forbidden</h4>
		<p>You don't have permission to access this page!</p>
		<p>Are you trying to delete forum threads from other users?</p>
	</div>
	</div>
	<?}?>
<?}else{
	include_once('message_guest.php');
}
include('template/footer.php');
?>