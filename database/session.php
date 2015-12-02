<?
	$loggedIn = (isset($_SESSION['username']) == true);
	$currentDate = time();
	$numberInvites = 0;
	$thisUser = 0;

	if ($loggedIn) {
		$thisUser = $_SESSION['userid'];
		$numberInvites = users_countInvites($thisUser);
		if ($numberInvites == null) {
			$numberInvites = 0;
		}
	}
?>