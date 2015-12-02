<?
	if (!isset($_SESSION)) {
		session_start();
	}

	include_once('../database/action.php');
	include_once('../database/users.php');

	if (safe_check($_POST, 'idEvent') && safe_check($_POST, 'idUser')) {
		$thisEvent = safe_getId($_POST, 'idEvent');
		$thisParticipant = safe_getId($_POST, 'idUser');

		if (users_isParticipating($thisParticipant, $thisEvent)) {
			$stmt = $db->prepare('DELETE FROM UserEvents WHERE idEvent = :idEvent AND idUser = :idUser');
			$stmt->bindParam(':idEvent', $thisEvent, PDO::PARAM_INT);
			$stmt->bindParam(':idUser', $thisParticipant, PDO::PARAM_INT);
			return $stmt->execute();
		}
	}
?>