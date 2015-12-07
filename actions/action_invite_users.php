<?
	if (!isset($_SESSION)) {
		session_start();
	}

	include_once('../database/action.php');
	include_once('../database/events.php');
	include_once('../database/users.php');
	include_once('../database/session.php');

	if ($loggedIn && safe_check($_POST, 'idEvent') && safe_check($_POST, 'idSender')) {
		$eventId = safe_getId($_POST, 'idEvent');
		$senderId = safe_getId($_POST, 'idSender');
		$invitedUsers = unserialize($_POST['invited']);
		foreach($invitedUsers as $currentUser) {
			$userId = $currentUser['idUser'];
			if (users_idExists($userId) && !users_isParticipating($userId, $eventId)) {
				$stmt = $db->prepare('INSERT INTO Invites VALUES(:idUser, :idEvent, :idSender)');
				$stmt->bindParam(':idUser', $userId, PDO::PARAM_INT);
				$stmt->bindParam(':idEvent', $eventId, PDO::PARAM_INT);
				$stmt->bindParam(':idSender', $senderId, PDO::PARAM_INT);
				$stmt->execute();
			}
		}

		header("Location: ../message_invites.php?id=$eventId");
	}
	else {
		safe_redirect('../index.php');
	}
?>