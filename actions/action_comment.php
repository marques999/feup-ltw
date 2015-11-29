<?
	include_once('../database/action.php');
	include_once('../database/comments.php');
	include_once('../database/tags.php');
	include_once('../database/users.php');

	if (isset($_POST['idUser']) && isset($_POST['idEvent'])) {
		$thisEvent = intval($_POST['idEvent']);
		$thisParticipant = intval($_POST['idUser']);
		$isParticipating = users_isParticipating($thisParticipant, $thisEvent);
		$userExists = users_idExists($thisParticipant);
		$currentTime = time();

		if ($userExists && $isParticipating) {
			$safeMessage = strip_tags_content($_POST['message']);
			$stmt = $db->prepare('INSERT INTO Comments VALUES(NULL, :idUser, :idEvent, :timestamp, :message)');
			$stmt->bindParam(':idUser', $thisParticipant, PDO::PARAM_INT);
			$stmt->bindParam(':idEvent', $thisEvent, PDO::PARAM_INT);
			$stmt->bindParam(':timestamp', $currentTime, PDO::PARAM_INT);
			$stmt->bindParam(':message', $safeMessage, PDO::PARAM_STR);

			if ($stmt->execute()) {
				header("Location: ../view_event.php?id={$_POST['idEvent']}#comments");
			}
			else {
				header("../database_error.php");
			}
		}
	}

	if (isset($_SERVER['HTTP_REFERER'])) {
		$refererUrl = $_SERVER['HTTP_REFERER'];
	}
	else {
		$refererUrl = 'index.php';
	}

	header("Location: $refererUrl");
?>