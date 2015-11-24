<?
	include_once('../database/action.php');
	include_once('../database/country.php');
	include_once('../database/salt.php');
	include_once('../database/users.php');

	if (isset($_POST['idEvent']) && isset($_POST['idUser'])) {

		$thisEvent = $_POST['idEvent'];
		$thisParticipant = $_POST['idUser'];
		$isParticipating = users_isParticipating($thisParticipant, $thisEvent);
		$wasInvited = users_wasInvited($thisParticipant, $thisEvent);

		if (!$isParticipating) {

			$stmt = $db->prepare('INSERT INTO UserEvents VALUES(:idEvent, :idUser)');
			$stmt->bindParam(':idEvent', $thisEvent, PDO::PARAM_INT);
			$stmt->bindParam(':idUser', $thisParticipant, PDO::PARAM_INT);
			$result = $stmt->execute();

			if ($result != false && $wasInvited) {
				$stmt = $db->prepare('DELETE FROM Invites WHERE idUser = :idUser AND idEvent = :idEvent');
				$stmt->bindParam(':idEvent', $thisEvent, PDO::PARAM_INT);
				$stmt->bindParam(':idUser', $thisParticipant, PDO::PARAM_INT);
				$stmt->execute();
			}

			return $result;
		}
	}
?>