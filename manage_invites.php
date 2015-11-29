<?
	include_once('database/connection.php');
	include_once('database/events.php');
	include_once('database/users.php');
	include('template/header.php');
?>
<script>
$(function() {
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
	$('button.reject').each(function() {
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
<div class="ink-grid push-center all-80 medium-100 small-100 tiny-100">
<div class="column-group half-vertical-space">
	<h3 class="slab half-vertical-space">My Invites</h3>
	<ul class="stage column-group quarter-vertical-space unstyled">
	<?if($numberInvites>0) {
	foreach($myEvents as $currentEvent){
	$eventId=0;
	if(isset($currentEvent['idEvent'])){
		$eventId=$currentEvent['idEvent'];
	}
	if(isset($currentEvent['idSender'])){
		$senderId=$currentEvent['idSender'];
		$sender=$allUsers[$senderId];
	}else{
		$sender=$defaultUser;
	}?>
	<li class="all-25 large-33 medium-50 small-100 tiny-100">
	<div class="panel half-right-space">
		<img class="all-100" src="holder.js/400x256/auto/ink"/>
		<div class="all-100 half-vertical-space">
			<b class="half-vertical-space">
				<a href="view_event.php?id=<?=$eventId?>"><?=events_getName($currentEvent)?></a>
			</b>
			<p class="no-margin">
				<small class="slab">
					<i class="fa fa-calendar"></i>
					<?=gmdate("l, d/m/Y H:i", $currentEvent['date'])?>
				</small>
			</p>
			<p class="no-margin">
				<small class="half-right-space"><i class="fa fa-check-circle"></i> <?=$eventParticipants[$eventId]['count']?> following</small>
			</p>
			<p class="half-bottom-space">
				<small><i class="fa fa-user"></i>
					invited by <a href="<?=users_viewProfile($senderId)?>"><?=$sender['username']?></a>
				</small>
			</p>
			<p class="no-margin">
				 <small><button class="ink-button red accept" event="<?=$currentEvent['idEvent']?>"><i class="fa fa-thumbs-up"></i> Accept</button></small>
				 <small><button class="ink-button red reject" event="<?=$currentEvent['idEvent']?>"><i class="fa fa-ban"></i> Decline</button></small>
			</p>
		</div>
	</div>
	</li>
	<?}?>
	<?}else{?>
		<p class="panel">You have no pending invites :(</p>
	<?}?>
	</ul>
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