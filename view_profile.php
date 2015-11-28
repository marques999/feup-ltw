<?
	include_once('database/connection.php');
	include_once('database/country.php');
	include_once('database/events.php');
	include_once('database/users.php');
	include('template/header.php');

	$thisUser = $defaultUser;
	$userId = 0;

	if (isset($_GET['id'])) {

		$userId = intval($_GET['id']);
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
	$(document).ready(function() {
		$('#nav_profile').addClass('active');
	});
	</script>
<?}?>

<div class="ink-grid all-80 medium-90 small-90 tiny-100">
<div class="column-group gutters">


	<!-- BEGIN USER AVATAR -->
	<div class="column vertical-space align-center all-25 large-40 medium-40 small-100 tiny-100">
		<img class="all-75" src="<?=users_getAvatar($thisUser)?>"/>
		<?if($isOwner){?>
		<p class="all-100">
		<a href="action_logout.php"><button class="vertical-space ink-button red"><i class="fa fa-key"></i> Log out</button></a>
		<a href="delete_user.php?id=<?=$thisUser?>"><button class="vertical-space ink-button red"><i class="fa fa-user-times"></i> Remove</button></a>
		</p>
		<?}?>
	</div>
	<!-- END USER AVATAR -->


	<!-- BEGIN PROFILE INFORMATION -->
	<div class="column all-75 large-60 medium-60 small-100 tiny-100">
	<img src="<?=users_getCountryFlag($thisUser)?>"></img>
	<h1 class="quarter-top-space"><?=$thisUser['username']?></h1>
	<p>
	<?if($isOwner){?>
		<a href="update_profile.php?field=name"><i class="fa fa-plus-circle"></i></a>
	<?}?>
		<?=$thisUser['name']?></p>
		<p class="no-margin">
	<?if($isOwner){?>
		<a href="update_profile.php?field=email"><i class="fa fa-plus-circle"></i></a>
	<?}?>
		<b>Contact: </b><a href="mailto:<?=$thisUser['email']?>"><?=$thisUser['email']?></a></p>
		<p class="no-margin">
	<?if($isOwner){?>
		<a href="update_profile.php?field=location"><i class="fa fa-plus-circle"></i></a>
	<?}?>
	<b>Location: </b><?=users_formatLocation($thisUser)?></p>
	<!-- END PROFILE INFORMATION -->


	<!-- BEGIN ATTENDED EVENTS -->
	<h3 class="half-top-space">Events attended (<?=$numberEvents?>)</h3>
	<div class="bottom-space">
	<?if($numberEvents>0){
		foreach($thisEvents as $currentEvent){?>
		<div class="half-vertical-space all-100">
			<img src="holder.js/100x64/auto/ink"/>
			<b class="quarter-space">
				<a href="<?=events_viewEvent($currentEvent)?>">
				<?=events_getName($currentEvent)?>
				</a>
			</b>
		</div>
		<?}?>
	<?}else{?>
		<p class="panel">This user is currently not attending any events :(</p>
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
			<a href="event_delete.php?id=<?=$currentEvent['idEvent']?>"><i class="fa fa-trash"></i></a>
		<?}?>
			<img src="holder.js/100x64/auto/ink"/>
			<b class="quarter-space">
				<a href="<?=events_viewEvent($currentEvent)?>">
				<?=events_getName($currentEvent)?>
				</a>
			</b>
		</div>
	<?}?>
	<?}else{?>
		<p class="panel">This user has not created any events :(</p>
	<?}?>
	</div>
	</div>
	<!-- END USER EVENTS -->


</div>
</div>

<?
	include('template/footer.php');
?>