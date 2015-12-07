<?
	include_once('database/connection.php');
	include_once('database/country.php');
	include_once('database/events.php');
	include_once('database/users.php');
	include_once('database/session.php');
	include('template/header.php');

	$thisUser = $defaultUser;
	$userId = 0;

	if (isset($_GET['id'])) {
		$userId = safe_getId($_GET, 'id');
		$getUser = users_listById($userId);
		if (count($getUser) > 0) {
			$thisUser = $getUser[0];
		}
	}

	$isOwner = $loggedIn && $_SESSION['username'] == $thisUser['username'];
	$thisEvents = users_listAllEvents($userId, $isOwner);
	$ownEvents = users_listOwnEvents($userId, $isOwner);
	$numberEvents = count($thisEvents);
	$numberEventsCreated = count($ownEvents);
?>
<?if($isOwner){?>
<script>
$(function(){
	$('#nav_profile').addClass('active');
});
</script>
<?}?>
<div class="ink-grid push-center all-80 large-90 medium-100 small-90 tiny-100">
<div class="column-group gutters">
	<!-- BEGIN USER AVATAR -->
	<div class="all-30 large-40 medium-40 small-100 tiny-100">
		<?if($isOwner){?>
		<a href="update_profile.php?field=5">
			<img class="all-100 half-padding" src="<?=users_getAvatar($thisUser)?>"/>
		</a>
		<p class="align-center gutters quarter-vertical-space">
			<a href="update_profile.php?field=1"><button class="ink-button"><i class="fa fa-key"></i> Change Password</button></a>
		</p>
		<p class="align-center gutters quarter-vertical-space">
			<a href="delete_user.php?id=<?=$userId?>"><button class="ink-button"><i class="fa fa-user-times"></i> Delete Account</button></a>
		</p>
		<?}else{?>
		<img class="all-100 half-padding" src="<?=users_getAvatar($thisUser)?>"/>
		<?}?>
	</div>
	<!-- END USER AVATAR -->


	<!-- BEGIN PROFILE INFORMATION -->
	<div class="column all-70 large-60 medium-60 small-100 tiny-100">
	<h1 class="push-left"><?=$thisUser['username']?></h1>
	<img class="push-left half-padding" src="<?=users_getCountryFlag($thisUser)?>"></img>
	<div class="clear">
	<?if($isOwner){?>
		<a href="update_profile.php?field=2"><i class="fa fa-plus-circle"></i></a>
	<?}?>
		<?=$thisUser['name']?></p>
		<p class="no-margin">
	<?if($isOwner){?>
		<a href="update_profile.php?field=3"><i class="fa fa-plus-circle"></i></a>
	<?}?>
		<b>Contact: </b><a href="mailto:<?=$thisUser['email']?>"><?=$thisUser['email']?></a></p>
		<p class="no-margin">
	<?if($isOwner){?>
		<a href="update_profile.php?field=4"><i class="fa fa-plus-circle"></i></a>
	<?}?>
		<b>Location: </b><?=users_formatLocation($thisUser)?>
	</div>
	<!-- END PROFILE INFORMATION -->


	<!-- BEGIN ATTENDED EVENTS -->
	<h3 class="half-top-space">Events attended (<?=$numberEvents?>)</h3>
	<div class="bottom-space">
	<?if($numberEvents>0){
		foreach($thisEvents as $currentEvent){?>
		<div class="half-vertical-space all-100">
			<img src="<?=events_getImage($currentEvent, 'small')?>"/>
			<b class="quarter-space">
				<a href="<?=events_viewEvent($currentEvent)?>">
				<?=events_getName($currentEvent)?>
				</a>
			</b>
		</div>
		<?}?>
	<?}else{?>
		<p class="panel">This user is currently not attending any public events :(</p>
	<?}?>
	</div>
	<!-- END ATTENDED EVENTS -->


	<!-- BEGIN USER EVENTS -->
	<h3 class="half-top-space">Events created (<?=$numberEventsCreated?>)</h3>
	<div class="half-vertical-space">
	<?if($numberEventsCreated>0){
		foreach($ownEvents as $currentEvent){?>
		<div class="half-vertical-space all-100">
			<?if($isOwner){?>
			<a href="event_delete.php?id=<?=$currentEvent['idEvent']?>"><i class="fa fa-trash"></i>&nbsp;</a>
			<?}?>
			<img src="<?=events_getImage($currentEvent, 'small')?>"/>
			<b class="quarter-space">
				<a href="<?=events_viewEvent($currentEvent)?>">
				<?=events_getName($currentEvent)?>
				</a>
			</b>
		</div>
		<?}?>
	<?}else{?>
		<p class="panel">This user has not created any public events :(</p>
	<?}?>
	</div>
	</div>
	<!-- END USER EVENTS -->
</div>
</div>
<?
	include('template/footer.php');
?>