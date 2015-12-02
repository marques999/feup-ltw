<?
	if (!isset($_SESSION)) {
		session_start();
	}

	include_once('../database/action.php');
	include_once('../database/comments.php');
	include_once('../database/users.php');
	include_once('../database/session.php');

	if (safe_check($_POST, 'idEvent') && safe_check($_POST, 'idUser')) {

		$thisEvent = safe_getId($_POST, 'idEvent');
		$thisParticipant = safe_getId($_POST, 'idUser');
		$isParticipating = users_isParticipating($thisParticipant, $thisEvent);
		$userExists = users_idExists($thisParticipant);

		if ($userExists && $isParticipating) {

			$safeMessage = safe_trim($_POST['message']);
			$stmt = $db->prepare('INSERT INTO Comments VALUES(NULL, :idUser, :idEvent, :timestamp, :message)');
			$stmt->bindParam(':idUser', $thisParticipant, PDO::PARAM_INT);
			$stmt->bindParam(':idEvent', $thisEvent, PDO::PARAM_INT);
			$stmt->bindParam(':timestamp', $currentDate, PDO::PARAM_INT);
			$stmt->bindParam(':message', $safeMessage, PDO::PARAM_STR);

			if ($stmt->execute()) {
				header("Location: ../view_event.php?id={$_POST['idEvent']}#comments");
			}
			else {
				header("../database_error.php");
			}
		}
		else {
			safe_redirect("../view_event.php?id=$thisEvent");
		}
	}
	else {
		safe_redirect("../view_event.php?id=$thisEvent");
	}
?>