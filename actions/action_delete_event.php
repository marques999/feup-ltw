<?
	include_once('../database/action.php');
	include_once('../database/events.php');
	include_once('../database/session.php');

	if (isset($_POST['idEvent']) && $loggedIn) {
		$eventId = intval($_POST['idEvent']);
		$eventExists = events_idExists($eventId);

		if ($eventId > 0 && $eventExists && $thisUser == $allEvents[$eventId]['idUser']) {
			$stmt = $db->prepare('DELETE FROM Events WHERE idUser = :idUser AND idEvent = :idEvent');
			$stmt->bindParam(':idUser', $thisUser, PDO::PARAM_INT);
			$stmt->bindParam(':idEvent', $eventId, PDO::PARAM_INT);

			if (!$stmt->execute()) {
				header("../database_error.php");
			}
		}
	}

	header("Location: ../index.php");
?>