<?
	include_once('../database/connection.php');
	include_once('../database/country.php');
	include_once('../database/salt.php');
	include_once('../database/users.php');

	if (isset($_POST['idEvent']) && isset($_POST['idUser'])) {
		$thisEvent = $_POST['idEvent'];
		$thisParticipant = $_POST['idUser'];

		if (users_userExists($thisParticipant) && !users_isParticipating($thisParticipant, $thisEvent)) {
			$stmt = $db->prepare('INSERT INTO Invites VALUES(NULL, :idEvent, :idUser)');
			$stmt->bindParam(':idEvent', $_POST['idEvent'], PDO::PARAM_INT);
			$stmt->bindParam(':idUser', $_POST['idUser'], PDO::PARAM_INT);
			return $stmt->execute();
		}
	}
?>