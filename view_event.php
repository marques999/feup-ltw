<?
	include_once('database/connection.php');
	include_once('database/comments.php');
	include_once('database/events.php');
    include_once('database/users.php');
    include_once('template/defaults.php');
	include('template/header.php');

	$thisEvent = $defaultEvent;
	$thisOwner = $defaultUser;
	$isParticipating = false;
	$isOwner = false;
	$isPrivate = false;
	$eventId = 0;
	$wasInvited = false;

	if (isset($_GET['id'])) {

		$eventId = $_GET['id'];
		$getEvent = events_listById($eventId);

		if (count($getEvent) > 0) {
			$thisEvent = $getEvent[0];
		}

		$isPrivate = ($thisEvent['private'] == 1);
		$thisParticipants = events_listParticipants($eventId);
		$thisComments = events_listTopComments($eventId, 5);
		$getOwner = users_listById($thisEvent['idUser']);

		if (count($getOwner) > 0) {
			$thisOwner = $getOwner[0];
		}

		$loggedUser = 0;

        if ($loggedIn) {

        	$loggedUser = $_SESSION['userid'];
        	$isOwner = ($thisOwner['idUser'] == $loggedUser);
        	$isParticipating = users_isParticipating($loggedUser, $eventId);
        	$getSender = users_listInvite($loggedUser, $eventId);
        	$wasInvited = $getSender !== false;    	
        	$sender = $defaultUser;
        	
        	if ($wasInvited && isset($getSender['idSender'])) {
        		$senderId = $getSender['idSender'];
        		$sender = $allUsers[$senderId - 1];
  			}	
        }
	}

	$numberParticipants = count($thisParticipants);
	$numberComments = count($thisComments);
?>

<script src="https://maps.googleapis.com/maps/api/js"></script>
<script>
var mapLoaded = false;
$(document).ready(function() {
	$('#nav_browse').addClass('active');	
	$("#write-comment").hide();	
	$('div#map-container').hide();
	$('a#follow-button').click(function(evt){		

		var postArgs = {'idEvent': <?=$eventId?>, 'idUser': <?=$loggedUser?>};
		var targetPage = 'actions/action_follow.php';		
		
		if ($(evt.target).parent().hasClass('active')) {
			targetPage = 'actions/action_unfollow.php';
		}

		$.post(targetPage, postArgs, function(response) {
	    	location.reload(); 
		});
	});

	$('#comment-button').click(function() {
		$('#write-comment').slideToggle("fast");
		if ($(this).hasClass('active')) {
			$(this).removeClass('active');
		}
		else {
			$(this).addClass('active');
		}
	});

	$('a#location-link').click(function() {
		$('div#map-container').slideToggle('fast');
		if ($('div#map-container').is(":visible")) {
			google.maps.event.trigger(map, 'resize');
		}
	});
});

function gmaps_initialize() {
	var map = null;
	var geocoder = null;
	var infowindow = new google.maps.InfoWindow;
	var marker = null;
	
	geocoder = new google.maps.Geocoder();
	map = new google.maps.Map(document.getElementById('map-location'), {
		center: {lat: 0, lng: 0},
		disableDefaultUI: true,
		zoomControl: true,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	});

	var latlngStr = $('a#location-link').text().split(',', 2);
	var latlng = {lat:parseFloat(latlngStr[0]),lng:parseFloat(latlngStr[1])};

	geocoder.geocode({'location':latlng}, function(results, status) {	    
	    
	    if (status === google.maps.GeocoderStatus.OK && results[1]) {
	        	marker = new google.maps.Marker({position:latlng,map:map});
	       		map.setZoom(16);
	        	map.setCenter(marker.getPosition());
	        	infowindow.setContent('<b>' + results[1].formatted_address + "</b><p>" + marker.getPosition().toString() + '</p>');
	        	infowindow.open(map, marker);
	        	$('a#location-link').text(results[1].formatted_address);
	    }

	    google.maps.event.addListener(marker, 'click', function(event) {
   			infowindow.open(map,marker);
		});
	});
 };

google.maps.event.addDomListener(window, 'load', gmaps_initialize);
</script>

<?if($isPrivate && (!$isParticipating || !$loggedIn)){?>
<div class="ink-grid all-50 large-80 medium-100 small-100">
	<div class="column ink-alert block error" role="alert">
		<h4>Private Event</h4>
		<p>You don't have permissions to access this page!</p>
		<?if($loggedIn){?>
			<p>Private events are <strong>invite</strong> only, you must be invited to this event by other users.</p>
		<?}else{?>
			<p>Please <a href="login.php">log in</a> with your account first.</p>
		<?}?>
	</div>
</div>
<?}else{?>
<section class="column-group gutters article">
<div class="xlarge-15 large-15 medium-10 small-5 tiny-5">
</div>
<div class="xlarge-70 large-70 medium-80 small-90 tiny-90">
	
	<article>
		
		<div>
			<h1 class="slab no-margin"><?=$thisEvent['name']?></h1>
			<h5 class="slab"><?=$thisEvent['type']?></h5>
		</div>

		<!-- BEGIN USER AVATAR -->
        <div class="panel push-right">
            <img src="<?=users_getSmallAvatar($thisOwner)?>"/>
            <b class="quarter-space">
            <a href="<?=users_viewProfile($thisOwner)?>"><?=$thisOwner['username']?></a>
        </div>
        <!-- END USER AVATAR -->

		<!-- BEGIN EVENT MANAGEMENT -->
		<?if($isOwner) {?>
		<nav id="#nav" class="ink-navigation half-vertical-space">
		    <ul class="pagination pills red">
		    	<!-- check if user is already participating on the event -->
		        <?if($isParticipating){?>
				<li class="active"><a href="#nav"><i class="fa fa-check"></i> Following</a></li>
				<?}else{?>
		        <li><a href="#"><i class="fa fa-edit"></i></a></li>
		        <?}?>
		        <li><a href="#"><i class="fa fa-user-plus"></i></a></li>
		        <li><a href="#"><i class="fa fa-trash"></i></a></li>
		    </ul>
		</nav>
		<?}?>
        <!-- END EVENT MANAGEMENT -->


        <!-- BEGIN EVENT DATE -->
		<div class="half-vertical-space">
			<p class="no-margin fw-medium">
				<i class="fa fa-calendar"></i>
				<b>Date:</b>
				<?=date("l, d/m/Y H:i", $thisEvent['date'])?>
			</p>
		</div>
		<!-- END EVENT DATE -->




		<!-- END EVENT DATE -->


		<!-- BEGIN EVENT PHOTO/DESCRIPTION -->
		<figure class="ink-image half-vertical-space">
			<img src="holder.js/1200x600/auto/ink" alt="">
			<figcaption class="condensed-regular">
				<?=$thisEvent['description']?>
			</figcaption>
		</figure>
		<!-- END EVENT PHOTO/DESCRIPTION -->


		<!-- BEGIN EVENT LOCATION -->
		 <div class="half-vertical-space">
            <p class="no-margin fw-medium">
            	<i class="fa fa-globe"></i>
            	<b>Location:</b>
            	<a id="location-link"><?=$thisEvent['location']?></a>
            </p>
        </div>
        <!-- END EVENT LOCATION -->


        <!-- BEGIN EVENT MAP -->
        <div class="panel padding all-100 half-vertical-space" id="map-container">
        	<div style="height:400px" class="all-100" id="map-location"></div>
        </div>
        <!-- END EVENT MAP -->


		<!-- BEGIN INVITE PANEL -->
		<?if($wasInvited){?>
		<div class="panel all-100 bottom-padding">
			<span>
				<a href="<?=users_viewProfile($sender)?>"><?=$sender['username']?></a> invited you to this event.
			</span>
			<div class="push-right">
				<small>
					<button class="ink-button red accept" event="<?=$eventId ?>"><i class="fa fa-thumbs-up"></i> Accept</button>
				</small>
				<small>
					<button class="ink-button red reject" event="<?=$eventId ?>"><i class="fa fa-ban"></i> Decline</button>
				</small>
			</div>
		</div>
		<?}?>
		<!-- END INVITE PANEL -->

		<?if($loggedIn){?>
		<nav id="#nav" class="ink-navigation half-vertical-space">
		    <ul class="pagination pills red">
	        <?if($isParticipating){?>
				<li class="active"><a id="follow-button"><i class="fa fa-check"></i> Following</a></li>
			<?}else{?>
	        	<li><a id="follow-button"><i class="fa fa-check"></i> Follow</a></li>
	        <?}?>
	        <?if($isParticipating){?>
	        	<li><a id="comment-button" href="#write-comment"><i class="fa fa-comment"></i> Comment</a></li>
	        <?}else{?>
	        	<li class="disabled"><a id="comment-button" href="#write-comment"><i class="fa fa-comment"></i> Comment</a></li>
	        <?}?>
	        <?if($isParticipating){?>
	        	<li><a href="#"><i class="fa fa-upload"></i> Upload Photos</a></li>
	        <?}else{?>
	        	<li class="disabled"><a href="#nav"><i class="fa fa-upload"></i> Upload Photos</a></li>
	        <?}?>
		    </ul>
		</nav>
		<?}?>

	<!-- BEGIN PARTICIPANTS SECTION -->
	<h2>Participants (<?=$numberParticipants?>) </h2>
	<div id="table-participants" class="all-100 half-vertical-space">
		<?if ($numberParticipants > 0) {
		for ($i = 0; $i < $numberParticipants; $i++) {

			$currentParticipant = $thisParticipants[$i];

			if ($i % 3 == 0) {
				echo '<div class="column-group half-gutters">';
			}?>

			<div class="all-30 large-35 medium-40 small-50 tiny-50">
            <img src="<?=users_getSmallAvatar($currentParticipant)?>"/>
			<b class="quarter-space">
			<a href="<?=users_viewProfile($currentParticipant)?>"><?=$currentParticipant['username']?></a>
			</b></div>

			<?if ($i == count($thisParticipants) - 1) {
				echo '</div>';
			}
		}
		} else {?>
			<p>This event has no participants :(</p>
		<?}?>
	</div>
	<!-- END PARTICIPANTS SECTION -->


	<!-- BEGIN COMMENTS SECTION -->
	<h2 id="comments">Comments (<?=$numberComments?>) </h2>
	<div class="column-group vertical-space">
	
	<?if ($numberComments > 0) {

		foreach($thisComments as $currentComment) {

			$commentAuthor = $defaultUser;
			$authorIndex = $currentComment['idUser'] - 1;

			if (isset($currentComment[$authorIndex])){
				$commentAuthor = $allUsers[$authorIndex];
			}?>
			
			<div class="column all-100">
				<img class="push-left half-right-space" src="<?=users_getSmallAvatar($currentComment)?>"/>   
				<a href="<?=users_viewProfile($commentAuthor)?>"><?=$commentAuthor['username']?></a>
				<small><?=date("l, d/m/Y H:i", $currentComment['timestamp'])?></small>
				<p class="fw-medium"><?=$currentComment['message']?></p>	
			</div>
		<?}?>
	<?}
	else{?>
		<p>This event has no comments :(</p>
	<?}?>

	</div>
	<!-- END COMMENTS SECTION -->


	<!-- BEGIN DYNAMIC SECTION -->
	<div id="write-comment">
	<form action="action_comment.php" method="POST" class="ink-form ink-formvalidation all-100">
		<input type="hidden" name="idUser" value="<?=$_SESSION['userid']?>"></input>
		<input type="hidden" name="idEvent" value="<?=$eventId?>"></input>
		<div class="control-group column-group half-gutters">
			<div class="control required all-100">
				<textarea name="message" rows="4" cols="80" placeholder="Insert your comment here..."></textarea>
			</div>
		</div>
		<div class="control-group column-group half-gutters">
			<div class="control all-100">
				<button type="submit" name="sub" class="ink-button red success">
				<i class="fa fa-share"></i> Send
				</button>
				<button type="reset" name="sub" value="Clear" class="ink-button red">
				<i class="fa fa-eraser"></i> Clear
				</button>
			</div>
		</div>
	</form>
	</div>
	<!-- END DYNAMIC SECTION -->
	</article>
</div>
</section>
<?
	}
	include('template/footer.php')
?>