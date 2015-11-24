<?
	include_once('../database/action.php');
	include_once('../database/comments.php');
	include_once('../database/users.php');
	include('../template/header.php');

	if (isset($_POST['idUser']) && isset($_POST['idEvent']) && isset($_SESSION['userid'])) {
		$thisEvent = $_POST['idEvent'];
		$thisParticipant = $_POST['idUser'];
		$currentTime = time();

		if (users_userExists($thisParticipant) && !users_isParticipating($thisParticipant, $thisEvent)) {
			$stmt = $db->prepare('INSERT INTO Comments VALUES(NULL, :idUser, :idEvent, :timestamp, :message)');
			$stmt->bindParam(':idUser', $_POST['idUser'], PDO::PARAM_INT);
			$stmt->bindParam(':idEvent', $_POST['idEvent'], PDO::PARAM_INT);
			$stmt->bindParam(':timestamp', $currentTime, PDO::PARAM_INT);
			$stmt->bindParam(':message', $_POST['message'], PDO::PARAM_STR);

			if ($stmt->execute()) {
				header("Location: view_event.php?id={$_POST['idEvent']}#comments");		
			}
			else {
				include("message_database.php");
			}
		}
	}
?>