<?
    include_once('database/connection.php');
    include_once('database/events.php');
    include_once('database/users.php');
    include('template/header.php');
	
    $thisUser = 0;
	$loggedIn = (isset($_SESSION['username']) == true);

	if ($loggedIn) {
		$thisUser = $_SESSION['userid'];
		$myEvents = users_listOwnEvents($thisUser);
	}

    $eventTypes = event_listTypes();
?>
<script>
    $(document).ready(function() {
        $('#nav_events').addClass('active');
    });
</script>
<div class="ink-grid all-80 large-80 medium-90 small-90">
<?if($loggedIn){?>
	<div class="column-group gutters half-vertical-space">

	<!-- BEGIN EVENT ACTIONS SECTION -->
	<div class="all-100">
		<a href="action_logout.php">
			<button class="vertical-space ink-button red"><i class="fa fa-plus"></i> Create New...</button>
		</a>
		<a href="action_logout.php">
			<button class="vertical-space ink-button red"><i class="fa fa-trash"></i> Log out</button>
		</a>
	</div>
	<!-- END EVENT ACTIONS SECTION -->

	<!-- BEGIN CURRENT EVENTS SECTION -->
	<div class="column all-80">
		<h3 class="slab half-vertical-space">My Events</h3>
		<ul class="stage column-group half-gutters unstyled">
		<?foreach($myEvents as $currentEvent){?>
	        <li class="slide xlarge-25 large-33 medium-50 small-100 tiny-100">
				<h4 class="no-margin"><a href="view_event.php?id=<?=$currentEvent['idEvent']?>"><?=$currentEvent['name']?></a></h4>
                <h5 class="slab"><?=date("l, d/m/Y H:i", $currentEvent['date'])?></h5>
				<img src="holder.js/200x128/auto/ink"/>
	            <div class="half-vertical-space">
			        <button class="ink-button red"><i class="fa fa-search"></i></button>
	            	<button class="ink-button red"><i class="fa fa-pencil"></i></button>
	            	<button class="ink-button red"><i class="fa fa-remove"></i></button>
				</div>
	        </li>
	       <?}?>
		</ul>
	</div>
	<!-- END CURRENT EVENTS SECTION -->

	<!-- BEGIN UPCOMING EVENTS SECTION -->
	<div class="column all-20">
		<h3 class="slab">Upcoming Events</h3>
			<div>
	            <img src="holder.js/100x64/auto/ink"/>
	            <div class="half-vertical-space">
			        <button class="ink-button red"><i class="fa fa-search"></i></button>
	            	<button class="ink-button red"><i class="fa fa-pencil"></i></button>
	            	<button class="ink-button red"><i class="fa fa-remove"></i></button>
				</div>
				<b class="quarter-space">
				<a href="view_profile.php?id=<?=$currentParticipant['idUser']?>"><?=$currentParticipant['username']?></a>
				</b>
			</div>
		<div>
            <img src="holder.js/100x64/auto/ink"/>
            <div class="half-vertical-space">
		        <button class="ink-button red"><i class="fa fa-search"></i></button>
            	<button class="ink-button red"><i class="fa fa-pencil"></i></button>
            	<button class="ink-button red"><i class="fa fa-remove"></i></button>
			</div>
			<b class="quarter-space">
			<a href="view_profile.php?id=<?=$currentParticipant['idUser']?>"><?=$currentParticipant['username']?></a>
			</b>
		</div>
		<div>
            <img src="holder.js/100x64/auto/ink"/>
            <div class="half-vertical-space">
		        <button class="ink-button red"><i class="fa fa-search"></i></button>
            	<button class="ink-button red"><i class="fa fa-pencil"></i></button>
            	<button class="ink-button red"><i class="fa fa-remove"></i></button>
			</div>
			<b class="quarter-space">
			<a href="view_profile.php?id=<?=$currentParticipant['idUser']?>"><?=$currentParticipant['username']?></a>
			</b>
		</div>
	</div>
	<!-- END UPCOMING EVENTS SECTION -->
	</div>
<?}else{?>
	<div class="column-group half-vertical-space">
		<div class="column all-20 large-15 medium-10 small-0 tiny-0"></div>
		<div class="column all-60 large-70 medium-80 small-100 tiny-100 ink-alert block error" role="alert">
			<h4>Forbidden</h4>
			<p>You don't have permission to access this page!</p>
			<p>Please <a href="login.php">log in</a> with your account first.</p>
		</div>
	</div>
<?}?>
</div>
</div>
<?include('template/footer.php')?>