<?
	include_once('../database/action.php');
	include_once('../database/country.php');
	include_once('../database/users.php');

	if (isset($_POST['idEvent']) && isset($_POST['idUser'])) {
		$thisEvent = $_POST['idEvent'];
		$thisParticipant = $_POST['idUser'];

		if (users_idExists($thisParticipant) && users_wasInvited($thisParticipant, $thisEvent)) {
			$stmt = $db->prepare('INSERT INTO UserEvents VALUES(:idEvent, :idUser)');
			$stmt->bindParam(':idEvent', $thisEvent, PDO::PARAM_INT);
			$stmt->bindParam(':idUser', $thisParticipant, PDO::PARAM_INT);
			$result = $stmt->execute();

			if ($result) {
				$stmt = $db->prepare('DELETE FROM Invites WHERE idUser = :idUser AND idEvent = :idEvent');
				$stmt->bindParam(':idEvent', $thisEvent, PDO::PARAM_INT);
				$stmt->bindParam(':idUser', $thisParticipant, PDO::PARAM_INT);
				return $stmt->execute();
			}

			return $result;
		}
	}
?>