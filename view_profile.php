<?
	include_once('database/connection.php');
	include_once('database/country.php');
	include_once('database/events.php');
	include_once('database/users.php');
	include_once('database/session.php');
	include_once('template/defaults.php');
	include('template/header.php');

	$thisUser = $defaultUser;
	$isOwner = false;

	if (isset($_GET['id'])) {

		$getUser = users_listById($_GET['id']);

		if (count($getUser) > 0) {
			$thisUser = $getUser[0];
		}
	}

	if ($loggedIn) {
		$isOwner = ($_SESSION['username'] == $thisUser['username']);
	}

	if ($isOwner) {
		$thisEvents = users_listAllEvents($_GET['id'], true);
		$ownEvents = users_listOwnEvents($_GET['id'], true);
	} else {
		$thisEvents = users_listAllEvents($_GET['id'], false);
		$ownEvents = users_listOwnEvents($_GET['id'], false);
	}

	$numberEvents = count($thisEvents);
	$numberEventsCreated = count($ownEvents);
?>

<script>
$(document).ready(function() {
	$('#nav_profile').addClass('active');
});
</script>

<div class="ink-grid all-80 medium-90 small-90 tiny-100">
	<div class="column-group gutters">
		<div class="align-center vertical-space all-25 large-40 medium-40 small-100 tiny-100">
			<img class="all-75" src="<?=users_getAvatar($thisUser)?>"/>
			<?if($isOwner){?>
				<p class="all-100">
				<a href="actions/action_logout.php">
					<button class="vertical-space ink-button red">Logout</button>
				</a>
				</p>
			<?}?>
		</div>
		<div class="all-75 large-60 medium-60 small-100 tiny-100">

		<!-- BEGIN USER PROFILE -->		
		<img src="<?=users_getCountryFlag($thisUser)?>"></img>
		<h1 class="no-margin half-vertical-space"><?=$thisUser['username']?></h1>
		<p>
		<?if($isOwner){?>
			<span class="ink-tooltip" data-tip-where="mousemove" data-tip-text="change your current display name">
				<a href="update_profile.php"><i class="fa fa-plus-circle"></i></a>
			</span>
		<?}?>
		<?=$thisUser['name']?></p>
		<p class="no-margin">
		<?if($isOwner){?>
			<span class="ink-tooltip" data-tip-where="mousemove" data-tip-text="change your contact information">
				<a href="update_profile.php"><i class="fa fa-plus-circle"></i></a>
			</span>
		<?}?>
		<b>Contact: </b><a href="mailto:<?=$thisUser['email']?>"><?=$thisUser['email']?></a></p>
		<p class="no-margin">
		<?if($isOwner){?>
			<span class="ink-tooltip" data-tip-where="mousemove" data-tip-text="change your location">
				<a href="update_profile.php"><i class="fa fa-plus-circle"></i></a>
			</span>
		<?}?>
		<b>Location: </b><?=users_formatLocation($thisUser)?></p>
		<!-- END USER PROFILE -->


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
			<p>This user is currently not attending any events :(</p>
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
            	<span class="ink-tooltip quarter-horizontal-space" data-tip-where="mousemove" data-tip-text="delete this event">
            	<a href="delete_event.php"><i class="fa fa-trash"></i></a>
            	</span>
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
            <p>This user has not created any events :(</p>
        <?}?>
        </div>
        <!-- END USER EVENTS -->

		</div>
	</div>
</div>
<?
	include('template/footer.php');
?>