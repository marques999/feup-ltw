<?
	if (!isset($_SESSION)) {
		session_start();
	}

	include_once('../database/action.php');
	include_once('../database/events.php');
	include_once('../database/users.php');
	include_once('../database/session.php');

	if (isset($_POST['idEvent']) && $loggedIn) {

		$eventId = safe_getId($_POST, 'idEvent');
		$eventExists = events_idExists($eventId);

		if ($eventId > 0 && $eventExists && $thisUser == $allEvents[$eventId]['idUser']) {

			$stmt = $db->prepare('DELETE FROM Events WHERE idUser = :idUser AND idEvent = :idEvent');
			$stmt->bindParam(':idUser', $thisUser, PDO::PARAM_INT);
			$stmt->bindParam(':idEvent', $eventId, PDO::PARAM_INT);

			if ($stmt->execute()) {
				header("Location: ../manage_events.php");
			}
			else {
				header("Location: ../database_error.php");
			}
		}
		else {
			safe_redirect("../manage_events.php");
		}
	}
	else {
		safe_redirect("../index.php");
	}
?>