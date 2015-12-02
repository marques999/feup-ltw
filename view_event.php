<?
	include_once('database/connection.php');
	include_once('database/comments.php');
	include_once('database/events.php');
	include_once('database/users.php');
	include('template/header.php');

	$thisEvent = $defaultEvent;
	$thisOwner = $defaultUser;
	$isParticipating = false;
	$isOwner = false;
	$isPrivate = false;
	$eventId = 0;
	$wasInvited = false;

	if (isset($_GET['id'])) {

		$eventId = intval($_GET['id']);
		$getEvent = events_listById($eventId);

		if (count($getEvent) > 0) {
			$thisEvent = $getEvent[0];
		}

		$isPrivate = ($thisEvent['private'] == 1);
		$thisParticipants = events_listParticipants($eventId);
		$thisComments = listCommentsByEvent($eventId);
		$ownerId = $thisEvent['idUser'];
		$getOwner = users_listById($ownerId);

		if (count($getOwner) > 0) {
			$thisOwner = $getOwner[0];
		}

		$loggedUser = 0;
		$canFollow=$currentDate<$thisEvent['date'];

		if ($loggedIn) {

			$loggedUser = $_SESSION['userid'];
			$isOwner = ($thisOwner['idUser'] == $loggedUser);
			$isParticipating = users_isParticipating($loggedUser, $eventId);
			$getSender = users_listInvite($loggedUser, $eventId);
			$wasInvited = $getSender !== false;
			$sender = $defaultUser;

			if ($wasInvited && isset($getSender['idSender'])) {
				$senderId = $getSender['idSender'];
				$sender = $allUsers[$senderId];
			}
		}
	}

	$numberParticipants = count($thisParticipants);
	$numberComments = count($thisComments);
	$startId = 0;
?>

<?if($isPrivate && !$isOwner && !($wasInvited || $isParticipating)){?>
<div class="ink-grid push-center all-50 large-70 medium-80 small-100 tiny-100">
	<div class="column ink-alert block error">
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
<script src="https://maps.googleapis.com/maps/api/js"></script>
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-565c6b43113a666a" async="async"></script>
<script>
var startId=0;
var numberComments=<?=$numberComments?>;
function fetchComments(div, elem){
	$.ajax({
		type: 'post',
		url: 'list_comments.php',
		data: {
			'eventId': <?=$eventId?>,
			'startId': startId
		},
		success: function(destination)
		{
			div.append(destination);
			nextId = startId + 5;
			remainingComments = numberComments - nextId;
			if(remainingComments>0)
			{
				if(remainingComments>5)
				{
					remainingComments=5;
				}
				elem.text("> Next " + remainingComments);
			}
			else
			{
				elem.hide();
			}
			startId=nextId;
		}
	});
}
$(function() {
	var commentsForm = $('#write-comment');
	var commentsSection = $('#comment-section');
	var expandComments = $('#expand-comments');

	$('#nav_browse').addClass('active');
	$(commentsForm).hide();
	fetchComments(commentsSection, expandComments);
	$(expandComments).click(function(evt){
		fetchComments($('#comment-section'), $(this));
	});

	<?if($canFollow){?>
	$('a#follow-button').click(function(evt){
		if ($(evt.target).parent().hasClass('active')) {
			targetPage = 'actions/action_unfollow.php';
		}
		else {
			targetPage = 'actions/action_follow.php';
		}
		$.ajax({
			type: 'post',
			url: targetPage,
			data: {
				'idEvent': <?=$eventId?>,
				'idUser': <?=$loggedUser?>
			},
			success: function(destination) {
				if (destination == window.location.pathname) {
					window.location.reload();
				}
				else {
					window.location = destination;
				}
			}
		});
	});
	<?}?>

	$('#accept-button').click(function() {
		$.ajax({
			type: 'post',
			url: 'actions/action_invite_accept.php',
			data: {
				'idEvent': <?=$eventId?>,
				'idUser': <?=$loggedUser?>
			},
			success: function(destination) {
				if (destination == window.location.pathname) {
					window.location.reload();
				}
				else {
					window.location = destination;
				}
			}
		});
	});
	$('#reject-button').click(function() {
		$.ajax({
			type: 'post',
			url: 'actions/action_invite_reject.php',
			data: {
				'idEvent': <?=$eventId?>,
				'idUser': <?=$loggedUser?>
			},
			success: function(destination) {
				if (destination == window.location.pathname) {
					window.location.reload(true);
				}
				else {
					window.location = destination;
				}
			}
		});
	});
	$('#comment-button').click(function() {
		if (!$(this).parent().hasClass("disabled")) {
			commentsForm.slideToggle('fast');
			$(this).parent().toggleClass('active');
		}
	});
});

google.maps.event.addDomListener(window, 'load', function() {
	var map = new google.maps.Map(document.getElementById('map-location'), {
		center: {lat: 0, lng: 0},
		disableDefaultUI: true,
		zoomControl: true,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	});

	var geocoder = new google.maps.Geocoder();
	var infowindow = new google.maps.InfoWindow;
	var locationString = $('a#location-link');
	var mapContainer = $('div#map-container');
	var marker = null;
	var latlngStr = locationString.text().split(',', 2);
	var latlng = {
		lat:parseFloat(latlngStr[0]),
		lng:parseFloat(latlngStr[1])
	};

	mapContainer.hide();
	geocoder.geocode({'location':latlng}, function(results, status) {
		if (status === google.maps.GeocoderStatus.OK && results[1]) {
			marker = new google.maps.Marker({position:latlng,map:map});
			map.setZoom(16);
			infowindow.setContent('<b>' + results[1].formatted_address + "</b><p>" + marker.getPosition().toString() + '</p>');
			locationString.text(results[1].formatted_address);
		}
		google.maps.event.addListener(marker, 'click', function(event) {
			infowindow.open(map,marker);
		});
	});

	locationString.click(function() {
		mapContainer.slideToggle('fast', function() {
			if (mapContainer.is(":visible")) {
				google.maps.event.trigger(map, 'resize');
				map.setCenter(marker.getPosition());
				infowindow.open(map, marker);
			}
		});
	});
});
</script>
<div class="ink-grid push-center all-70 large-75 medium-80 small-90 tiny-100">

	<!-- BEGIN USER AVATAR -->
	<div class="panel push-right">
		<img src="<?=users_getSmallAvatar($ownerId)?>"/>
		<b class="quarter-space">
		<a href="<?=users_viewProfile($ownerId)?>"><?=$thisOwner['username']?></a>
	</div>
	<!-- END USER AVATAR -->


	<!-- BEGIN EVENT TITLE -->
	<div class="push-left half-vertical-space">
		<h1 class="slab no-margin"><?=$thisEvent['name']?></h1>
		<h5 class="slab no-margin"><?=$thisEvent['type']?></h5>

	</div>
	<!-- END EVENT TITLE -->
<div class="clear addthis_sharing_toolbox"></div>

	<!-- BEGIN EVENT MANAGEMENT -->
	<?if($isOwner) {?>
	<nav id="#nav" class="ink-navigation clear">
		<ul class="pills">
			<!-- check if user is already participating on the event -->
			<li><a href="event_update.php?id=<?=$eventId?>"><i class="fa fa-edit"></i></a></li>
			<li><a href="invite.php"><i class="fa fa-user-plus"></i></a></li>
			<li><a href="event_delete.php?id=<?=$eventId?>"><i class="fa fa-trash"></i></a></li>
		</ul>
	</nav>
	<?}?>
	<!-- END EVENT MANAGEMENT -->


	<!-- BEGIN EVENT DATE -->
	<div class="clear half-vertical-space">
		<p class="no-margin fw-medium">
			<i class="fa fa-calendar"></i>
			<b>Date:</b>
			<?=events_getDate($thisEvent)?>
		</p>
	</div>
	<!-- END EVENT DATE -->


	<!-- BEGIN EVENT PHOTO/DESCRIPTION -->
	<figure class="ink-image half-vertical-space">
		<img src="<?=events_getImage($thisEvent,'original')?>">
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
	<div class="panel all-100 padding half-vertical-space" id="map-container">
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
				<button id="accept-button" class="ink-button"><i class="fa fa-thumbs-up"></i> Accept</button>
			</small>
			<small>
				<button id ="reject-button" class="ink-button"><i class="fa fa-ban"></i> Decline</button>
			</small>
		</div>
	</div>
	<?}?>
	<!-- END INVITE PANEL -->


	<!-- BEGIN USER ACTIONS -->
	<?if($loggedIn){?>
	<nav id="#nav" class="ink-navigation all-100 half-vertical-space">
		<ul class="pills">
		<li class="<?if(!$canFollow){?>disabled<?}else if($isParticipating){?>active<?}?>">
			<a id="follow-button">
			<i class="fa fa-check"></i>
			<?if($isParticipating){?>
				Following
			<?}else{?>
				Follow
			<?}?>
			</a>
		</li>
		<?if($isParticipating){?>
			<li><a id="comment-button" href="#write-comment"><i class="fa fa-comment"></i> Comment</a></li>
		<?}else{?>
			<li class="disabled"><a id="comment-button"><i class="fa fa-comment"></i> Comment</a></li>
		<?}?>
		<?if($isParticipating){?>
			<li><a href="#"><i class="fa fa-upload"></i> Upload Photos</a></li>
		<?}else{?>
			<li class="disabled"><a href="#nav"><i class="fa fa-upload"></i> Upload Photos</a></li>
		<?}?>
		</ul>
	</nav>
	<?}?>
	<!-- END USER ACTIONS -->


	<!-- BEGIN PARTICIPANTS SECTION -->
	<h2>Participants (<?=$numberParticipants?>)</h2>
	<div class="column-group half-vertical-space" id="table-participants">
		<?if($numberParticipants>0){
		for($i=0;$i<$numberParticipants;$i++){
			$currentParticipant=$thisParticipants[$i];
			$currentParticipantId=$currentParticipant['idUser'];?>
			<div class="column quarter-vertical-space all-33 medium-50 small-100 tiny-100">
				<img src="<?=users_getSmallAvatar($currentParticipantId)?>"/>
				<b class="quarter-space">
				<a href="<?=users_viewProfile($currentParticipantId)?>"><?=$currentParticipant['username']?></a>
				</b>
			</div>
			<?}?>
		<?}else{?>
			<p class="panel">This event has no users participating :(</p>
		<?}?>
	</div>
	<!-- END PARTICIPANTS SECTION -->


	<!-- BEGIN DYNAMIC COMMENTS SECTION -->
	<?if($isParticipating || $isOwner){?>
		<h2 class="no-margin" id="comments">Comments (<?=$numberComments?>) </h2>
		<div id="comment-section" class="column-group all-100 half-vertical-space">
		<?if($numberComments<=0){?>
			<p class="panel">This event has no comments :(</p>
		<?}?>
		</div>
		<?if($numberComments>0){?>
		<div class="align-center">
			<button id="expand-comments" class="ink-button"></button>
		</div>
		<?}?>
	<?}?>
	<!-- END DYNAMIC COMMENTS SECTION -->


	<!-- BEGIN WRITE COMMENT SECTION -->
	<div id="write-comment">
	<h5 class="slab no-margin">Write a comment...</h5>
	<form action="actions/action_comment.php" method="POST" class="ink-form ink-formvalidation">
		<input type="hidden" name="idUser" value="<?=$thisUser?>"></input>
		<input type="hidden" name="idEvent" value="<?=$eventId?>"></input>
		<div class="control-group">
			<div class="control required">
				<textarea name="message" rows="4" cols="80" placeholder="Write your comment here..."></textarea>
			</div>
		</div>
		<div class="control-group align-center half-gutters">
			<button type="submit" name="sub" class="ink-button">
			<i class="fa fa-share"></i> Send
			</button>
			<button type="reset" name="sub" value="Clear" class="ink-button">
			<i class="fa fa-eraser"></i> Clear
			</button>
		</div>
	</form>
	</div>
	<!-- END WRITE COMMENT SECTION -->
</div>
<?
	}
	include('template/footer.php')
?>