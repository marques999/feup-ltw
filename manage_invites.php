<?
	include_once('database/connection.php');
	include_once('database/events.php');
	include_once('database/users.php');
	include('template/header.php');
?>

<script>
$(function(){
	$('#nav_events').addClass('active');
});
</script>

<?if($loggedIn){
	$myEvents=users_listInvites($thisUser);
	$eventParticipants=events_countParticipants();
	$numberInvites=count($myEvents);
?>

<script>
$(function() {
	$('button.accept').each(function() {
		$(this).click(function(evt) {
		$.ajax({
			type: 'post',
			url: 'actions/action_invite_accept.php',
			data: {
				'idEvent': $(this).attr('event'),
				'idUser': <?=$thisUser?>
			},
			success: function() {
				location.reload();
			}
		});
		});
	});
	$('button.reject').each(function()
	{
		$(this).click(function(evt) {
		$.ajax({
			type: 'post',
			url: 'actions/action_invite_reject.php',
			data: {
				'idEvent': $(this).attr('event'),
				'idUser': <?=$thisUser?>
			},
			success: function() {
				location.reload();
			}
		});
		});
	});
});
</script>

<div class="ink-grid">
<div class="column-group half-vertical-space">
	<h3 class="slab half-vertical-space">My Invites</h3>
	<ul class="column-group unstyled">
	<?if($numberInvites>0){
	foreach($myEvents as $currentEvent){
	$eventId=safe_getId($currentEvent,'idEvent');
	$senderId=safe_getId($currentEvent,'idSender');
	if($senderId>0){
		$sender=$allUsers[$senderId];
	}else{
		$sender=$defaultUser;
	}?>
	<li class="slide all-25 large-33 medium-50 small-100 tiny-100">
	<div class="panel half-right-space">
		<img class="all-100" src="<?=events_getImage($currentEvent, 'medium')?>"/>
		<div class="all-100 half-vertical-space">
			<b class="half-vertical-space">
				<a href="view_event.php?id=<?=$eventId?>">
					<?=events_getName($currentEvent)?>
				</a>
			</b>
			<p class="no-margin">
				<small class="slab"><i class="fa fa-calendar"></i>
					<?=events_getDate($currentEvent)?>
				</small>
			</p>
			<p class="no-margin">
				<small class="half-right-space"><i class="fa fa-check-circle"></i>
					<?=$eventParticipants[$eventId]['count']?> following
				</small>
			</p>
			<p class="half-bottom-space">
				<small><i class="fa fa-user"></i>
					invited by <a href="<?=users_viewProfile($senderId)?>"><?=$sender['username']?></a>
				</small>
			</p>
			<p class="no-margin">
				 <small><button class="ink-button red accept" event="<?=$eventId?>"><i class="fa fa-thumbs-up"></i> Accept</button></small>
				 <small><button class="ink-button red reject" event="<?=$eventId?>"><i class="fa fa-ban"></i> Decline</button></small>
			</p>
		</div>
	</div>
	</li>
	<?}?>
	<?}else{?>
		<li class="panel">You have no pending invites :(</li>
	<?}?>
	</ul>
</div>
</div>
<?}else{
	include_once('message_guest.php');
}?>
<?
	include('template/footer.php');
?>