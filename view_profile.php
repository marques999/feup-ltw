<?
	include_once('database/connection.php');
	include_once('database/users.php');
    include_once('get_country.php');
	include('template/header.php');

    $defaultUser = array(
            'idUser' => 0,
            'username' => 'guest',
            'name' => 'John Doe',
            'email' => 'nobody@loves.me',
            'location' => 'gl');
    $thisUser = $defaultUser;

	if (isset($_GET['id'])) {
		
        $getUser = users_listById($_GET['id']);

        if (count($getUser) > 0) {
            $thisUser = $getUser[0];
        }

		$thisEvents = users_listAllEvents($_GET['id']);
        $ownEvents = users_listOwnEvents($_GET['id']);
	}

	$isOwner = ($_SESSION['username'] == $thisUser['username']);
	$numberEvents = count($thisEvents);
    $numberEventsCreated = count($ownEvents);
?>
<script>
	$(document).ready(function() {
		$('#nav_profile').addClass('active');
	});
</script>
<div class="ink-grid all-80 large-80 medium-90 small-90">
	<div class="column-group gutters">
		<div class="all-15 large-15 medium-10 small-10 tiny-10"></div>
		<div class="vertical-space all-15 large-15 medium-15 small-80 tiny-80">
			<img src="images/avatars/<?=$thisUser['idUser']?>.png"/>
			<?if($isOwner){?>
				<p class="align-center">
				<a href="action_logout.php">
				<button class="vertical-space ink-button red">Log out
				</button></a></p>
			<?}?>
			</div>
		<div class="xlarge-50 large-70 medium-80 small-100 tiny-100">
			<article>
				<img src="images/flags/<?=$thisUser['location']?>.png"></img><h1><?=$thisUser['username']?></h1>
				<p>
				<?if($isOwner){?>
					<span class="ink-tooltip" data-tip-where="mousemove" 
					data-tip-text="change your current display name">
					<a href="update_profile.php"><i class="fa fa-plus-circle"></i></a>
					</span>
				<?}?>
				<?=$thisUser['name']?></p>
				<p class="no-margin">
				<?if($isOwner){?>
					<span class="ink-tooltip" data-tip-where="mousemove" 
					data-tip-text="change your contact information">
					<a href="update_profile.php"><i class="fa fa-plus-circle"></i></a>
					</span>
				<?}?>
				<b>Contact: </b><a href="mailto:<?=$thisUser['email']?>"><?=$thisUser['email']?></a></p>
				<p class="no-margin">
				<?if($isOwner){?>
					<span class="ink-tooltip" data-tip-where="mousemove" 
					data-tip-text="change your location">
					<a href="update_profile.php"><i class="fa fa-plus-circle"></i></a>
					</span>
				<?}?>
				<b>Location: </b><?=getCountry($thisUser['location'])?></p>
				<h3 class="half-top-space">Events attended (<?=$numberEvents?>)</h3>
				<div class="half-vertical-space">
				<?if ($numberEvents>0){
				foreach($thisEvents as $currentEvent){?>
					<div class="half-vertical-space all-100">
					<img src="holder.js/100x64/auto/ink"/>
					<b class="quarter-space">
					<a href="view_event.php?id=<?=$currentEvent['idEvent']?>"><?=$currentEvent['name']?></a>
					</b></div>
				<?}
				}else{?>
					<p>This user is currently not attending any events :(</p>
				<?}?>
				</div>
	            <h3 class="half-top-space">Events created (<?=$numberEventsCreated?>)</h3>
	            <div class="half-vertical-space">
	            <?if ($numberEventsCreated>0) {
	            foreach($ownEvents as $currentEvent){?>
	                <div class="half-vertical-space all-50">
	                <?if($isOwner){?>
	                	<span class="ink-tooltip quarter-horizontal-space" data-tip-where="mousemove" 
						data-tip-text="delete this event">
	                	<a href="delete_event.php"><i class="fa fa-trash"></i></a>
	                	</span>
	                <?}?>
	                <img src="holder.js/100x64/auto/ink"/>
	                <b class="quarter-space">
	                <a href="view_event.php?id=<?=$currentEvent['idEvent']?>"><?=$currentEvent['name']?></a>
	                </b></div>
	            <?}
	            }else{?>
	                <p>This user has not created any events :(</p>
	            <?}?>
	            </div>
			</article>
		<div class="xlarge-15 large-15 medium-10 small-5 tiny-5">
		</div>
	</div>
	</div>
</div>
<?include('template/footer.php')?>