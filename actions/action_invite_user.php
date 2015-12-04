<?
	if (!isset($_SESSION)) {
		session_start();
	}

	include_once('../database/actions.php');
	include_once('../database/events.php');
	include_once('../database/users.php');

	if (safe_check($_POST, 'idEvent') && safe_check($_POST, 'idUser')) {
		$thisEvent = safe_getId($_POST, 'idEvent');
		$thisParticipant = safe_getId($_POST, 'idUser');

		if (users_userExists($thisParticipant) && !users_isParticipating($thisParticipant, $thisEvent)) {
			$stmt = $db->prepare('INSERT INTO Invites VALUES(NULL, :idEvent, :idUser)');
			$stmt->bindParam(':idEvent', $_POST['idEvent'], PDO::PARAM_INT);
			$stmt->bindParam(':idUser', $_POST['idUser'], PDO::PARAM_INT);
			return $stmt->execute();
		}
	}
?>