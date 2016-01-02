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
<script src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-565c6b43113a666a" async="async"></script>
<script src="//maps.googleapis.com/maps/api/js?key=AIzaSyAObn6F3iTncHgv8HrByEfgnlAbNSnfPOE"></script>
<script src="view_event.js"></script>
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

	var buttonAccept = $('button#accept-button');
	var buttonReject = $('button#reject-button');
	var buttonFollow = $('a#follow-button');
	var buttonComment = $('a#comment-button');
	//===========================================
	var buttonDelete = $('li#button-delete');
	var buttonInvite = $('li#button-invite');
	var buttonUpdate = $('li#button-update');
	//===========================================
	var labelDelete = $('span#label-delete');
	var labelInvite = $('span#label-invite');
	var labelUpdate = $('span#label-update');
	//===========================================
	var navigationBrowse = $('#nav_browse');
	var commentsForm = $('#write-comment');
	var commentsSection = $('#comment-section');
	var expandComments = $('#expand-comments');
	//===========================================

	navigationBrowse.addClass('active');
	commentsForm.hide();
	fetchComments(commentsSection, expandComments);

	expandComments.click(function(evt){
		fetchComments(commentsSection, $(this));
	});


	buttonInvite.hover(function(){
		labelInvite.stop(true,true).fadeToggle('fast');
	});

	buttonUpdate.hover(function(){
		labelUpdate.stop(true,true).fadeToggle('fast');
	});

	buttonDelete.hover(function(){
		labelDelete.stop(true,true).fadeToggle('fast');
	});

	<?if($canFollow){?>

	buttonFollow.click(function(evt){
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

	buttonAccept.click(function() {
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

	buttonReject.click(function() {
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

	buttonComment.click(function() {
		if (!$(this).parent().hasClass("disabled")) {
			commentsForm.slideToggle('fast');
			$(this).parent().toggleClass('active');
		}
	});
});
</script>

<div class="ink-grid push-center all-70 large-80 medium-90 small-100 tiny-100">


	<!-- BEGIN EVENT TITLE -->
	<div class="push-left half-vertical-space">
	<?if($isOwner){?>
	<nav class="ink-navigation half-top-space">
		<ul class="unstyled pills">
			<li id="button-update"><a href="event_update.php?id=<?=$eventId?>">
				<i class="fa fa-edit"></i>
				<span class="hidden" id="label-update">Update Event</span>
			</a></li>
			<?if($canFollow){?>
			<li id="button-invite"><a href="invite_users.php?id=<?=$eventId?>">
			<?}else{?>
			<li class="disabled" id="button-invite"><a>
			<?}?>
				<i class="fa fa-user-plus"></i>
				<span class="hidden" id="label-invite">Invite Users</span>
			</a></li>
			<li id="button-delete"><a href="event_delete.php?id=<?=$eventId?>">
				<i class="fa fa-trash"></i>
				<span class="hidden" id="label-delete">Delete Event</span>
			</a></li>
		</ul>
	</nav>
	<?}?>
	<h1 class="slab no-margin"><?=$thisEvent['name']?></h1>
	<h5 class="slab no-margin"><?=$thisEvent['type']?></h5>
	</div>
	<!-- END EVENT TITLE -->


	<!-- BEGIN USER AVATAR -->
	<div class="panel push-right">
		<img src="<?=users_getSmallAvatar($ownerId)?>"/>
		<b class="quarter-space">
		<a href="<?=users_viewProfile($ownerId)?>"><?=$thisOwner['username']?></a>
	</div>
	<!-- END USER AVATAR -->


	<!-- BEGIN SHARE BUTTONS -->
	<?if($loggedIn){?>
	<div class="clear addthis_sharing_toolbox">
	</div>
	<?}?>
	<!-- END SHARE BUTTONS -->


	<!-- BEGIN EVENT DATE -->
	<div class="clear half-vertical-space">
		<p class="no-margin fw-medium">
			<i class="fa fa-calendar"></i><b>&nbsp;Date:</b>
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
			<i class="fa fa-globe"></i><b>&nbsp;Location:</b>
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
	<div class="panel all-100">
		<a href="<?=users_viewProfile($sender)?>"><?=$sender['username']?></a> invited you to this event.
		<small class="push-right">
			<button id="accept-button" class="ink-button"><i class="fa fa-thumbs-up"></i> Accept</button>
			<button id ="reject-button" class="ink-button"><i class="fa fa-ban"></i> Decline</button>
		</small>
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
			<span id="follow-text">
			<?if($isParticipating){?>
				Following
			<?}else{?>
				Follow
			<?}?>
			</span>
			</a>
		</li>
		<?if($isParticipating){?>
			<li><a id="comment-button" href="#write-comment"><i class="fa fa-comment"></i> Comment</a></li>
		<?}else{?>
			<li class="disabled"><a id="comment-button"><i class="fa fa-comment"></i> Comment</a></li>
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