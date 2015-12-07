<?
	include_once('database/connection.php');
	include_once('database/invites.php');
	include_once('database/users.php');

	function wasInvited($usname, $user_list) {
		return isset($user_list[$usname]);
	}

	if (safe_check($_POST, 'sortName')) {
		$sort_name = $_POST['sortName'];
	}
	else {
		$sort_name = '';
	}

	if (safe_check($_POST, 'nameOptions')) {
		$name_options = $_POST['nameOptions'];
	}
	else {
		$name_options = '';
	}

	$event_id = safe_getId($_POST, 'eventId');
	$user_id = safe_getId($_POST, 'userId');
	$invitedUsers = array();
	$maxUsers = 0;

	if (safe_check($_POST, 'userList')) {
		$user_list = json_decode($_POST['userList'], true);
		$maxUsers = count($user_list);
	}

	$filter_available = invites_filterAvailable($event_id, $user_id, $sort_name, $name_options);
	$filter_invited = invites_filterInvited($event_id, $sort_name, $name_options);
	$filter_following = invites_filterParticipating($event_id, $sort_name, $name_options);
	$filter_queries = array(0 => $filter_available, 1 => $filter_invited, 2 => $filter_following);
	$titles = array(0 => 'Available', 1 => 'Invited', 2 => 'Following');
	$icons = array(0 => 'fa-user-plus', 1 => 'fa-check', 2 => 'fa-check');

	if($maxUsers>0){?>
	<ul class="column-group unstyled">
	<?foreach($user_list as $currentUser){
		$invitedUsers[$currentUser['username']] = true;
	?>
	<li class="all-25 medium-50 small-100 tiny-100 quarter-vertical-space">
		<div class="panel half-right-space">
			<img class="quarter-right-space" src="<?=users_getSmallAvatar($currentUser['idUser'])?>"/>
			<b>
				<a target="_blank" href="<?=users_viewProfile($currentUser['idUser'])?>"><?=$currentUser['username'].' '?></a>
				<a onClick="removeInvite(<?=$currentUser['idUser']?>)"><i class="fa fa-times"></i></a>
			</b>
		</div>
	</li>
	<?}?>
	</ul>
	<form method="post" action="actions/action_invite_users.php">
		<input type="hidden" name="idSender" value="<?=$user_id?>">
		<input type="hidden" name="idEvent" value="<?=$event_id?>">
		<input type="hidden" name="invited" value="<?=htmlentities(serialize($user_list))?>">
		<button class="ink-button"><i class="fa fa-user-plus"></i> Send Invite(s)</button>
	</form>
	<?}
	for($j=0;$j<3;$j++) {
		$currentQuery = $filter_queries[$j];
		$numberUsers=count($currentQuery);
		for($i=0,$n=0;$i<$numberUsers;$i++) {
			if (wasInvited($currentQuery[$i]['username'], $invitedUsers)) {
				unset($filter_queries[$j][$i]);
			}
		}
	}
	for($n=0;$n<3;$n++){
		$currentQuery=$filter_queries[$n];
		$numberUsers=count($currentQuery);
		if($numberUsers<=0){
			continue;
		}?>
		<h3 class="slab half-vertical-space"><?="{$titles[$n]} ($numberUsers)"?></h3>
		<ul class="column-group unstyled">
		<?foreach($currentQuery as $currentUser){?>
		<li class="all-25 large-33 medium-50 small-50 tiny-100">
			<div class="half-right-space">
				<img class="all-50 medium-40 small-40 tiny-30 half-right-space" src="<?=users_getSmallAvatar($currentUser['idUser'])?>"/>
			</div>
			<div>
			<b class="half-vertical-space"><a href="<?=users_viewProfile($currentUser['idUser'])?>"><?=$currentUser['username']?></a></b>
			<p class="small"><?=$currentUser['name']?></p>
			<small><nav class="ink-navigation">
				<ul class="pills">
					<?if($n > 0){?>
					<a class="ink-button">
					<i class="fa <?=$icons[$n]?>"></i> <?=$titles[$n]?></a>
					<?}else{?>
					<a class="ink-button" onClick="inviteUser(<?=$currentUser['idUser']?>, '<?=$currentUser['username']?>')">
					<i class="fa fa-user-plus"></i> Invite
					</a>
					<?}?>
				</ul>
			</nav></small>
			</div>
		</li>
		<?}?>
		</ul>
	<?}?>
<script type="text/javascript" src="js/holder.min.js"></script>