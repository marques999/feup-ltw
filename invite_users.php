<?
	include_once('database/connection.php');
	include_once('database/events.php');
	include_once('database/users.php');
	include_once('database/session.php');
	include('template/header.php');

	$eventId = 0;
	$isOwner = false;
	$thisEvent = $defaultEvent;

	if (isset($_GET['id']) && $loggedIn) {
		$eventId = safe_getId($_GET, 'id');
		$thisEvent = events_listById($eventId);

		if (is_array($thisEvent)&&count($thisEvent)>0) {
			$thisEvent = $thisEvent[0];
			$isOwner = $thisUser == $thisEvent['idUser'];
		}
	}
?>
<?if($loggedIn&&$isOwner){?>
<script>
var name = '';
var name_options = 1;
var event_id = <?=$eventId?>;
var user_id = <?=$thisUser?>;
var usersToInvite = {};

$(function() {
	nameField = $('input#name');
	nameSelect = $("#name_select");
	refreshContent(name, name_options, event_id, user_id);
	nameField.keyup(function() {
		if (nameField.val() != name) {
			name = nameField.val();
			refreshContent(name, name_options, event_id, user_id);
		}
	});
	nameSelect.change(function() {
		if (nameSelect.val() != name_options) {
			name_options = nameSelect.val();
			refreshContent(name, name_options, event_id, user_id);
		}
	});
});

function refreshContent(name, name_options, event_id, user_id) {
	$.ajax({
		type: 'post',
		url: 'filter_users.php',
		data: {
			'sortName': name,
			'nameOptions': name_options,
			'eventId': event_id,
			'userId': user_id,
			'userList': JSON.stringify(usersToInvite)
		},
		success: function(data) {
			$('div#container').children().remove();
			$('div#container').append(data);
		}
	});
};

function inviteUser(id, usname) {
	if (usersToInvite[id] == undefined) {
		usersToInvite[id] = {idUser: id, username: usname};
		refreshContent(name, name_options, event_id, user_id);
	}
};

function removeInvite(id) {
	if (usersToInvite[id] != undefined) {
		delete usersToInvite[id];
		refreshContent(name, name_options, event_id, user_id);
	}
};
</script>
<div class="ink-grid push-center all-90 small-95 tiny-100">
<form>
<div class="column-group control-group half-gutters">
	<input type="hidden" name="id" value="<?=$eventId?>">
	<label for="name" class="all-10 medium-10 small-15 tiny-15 align-right"><b>Name</b></label>
	<div class="control all-50 small-40 tiny-40"><input type="text" id="name"></div>
	<label for="name_select" class="all-5 large-10 medium-10 small-15 tiny-15 align-right"><b>Field</b></label>
	<div class="control all-20 medium-25 small-30 tiny-30">
		<select id="name_select">
			<option value="1">Username</option>
			<option value="2">Full Name</option>
		</select>
	</div>
</div>
</form>
<div id="invites" class="column-group no-margin half-gutters">
</div>
<div id="container" class="column-group half-gutters">
</div>
</div>
<?}else{
	include_once('message_guest.php');
}
include('template/footer.php');
?>