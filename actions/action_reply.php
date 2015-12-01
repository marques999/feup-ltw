<?
	include_once('../database/action.php');
	include_once('../database/forum.php');
	include_once('../database/session.php');
	include_once('../database/tags.php');
	include_once('../database/users.php');

	if (isset($_POST['idUser']) && isset($_POST['idThread'])) {
		$thisThread = safe_getId($_POST, 'idThread');
		$thisParticipant = safe_getId($_POST, 'idUser');
		$userExists = users_idExists($thisParticipant);
		$isQuote = false;
		$currentTime = time();

		if (isset($_POST['idQuote'])) {
			$isQuote = true;
		}

		if ($userExists) {

			$safeMessage = strip_tags_content($_POST['message']);

			if ($isQuote) {
				$stmt = $db->prepare('INSERT INTO ForumPost VALUES(NULL, :idThread, :idUser, :message, :timestamp, :idQuote)');
				$stmt->bindParam(':idQuote', $_POST['idQuote'], PDO::PARAM_INT);
			}
			else {
				$stmt = $db->prepare('INSERT INTO ForumPost VALUES(NULL, :idThread, :idUser, :message, :timestamp, NULL)');
			}

			$stmt->bindParam(':idThread', $thisThread, PDO::PARAM_INT);
			$stmt->bindParam(':idUser', $thisParticipant, PDO::PARAM_INT);
			$stmt->bindParam(':message', $safeMessage, PDO::PARAM_STR);
			$stmt->bindParam(':timestamp', $currentTime, PDO::PARAM_INT);

			if ($stmt->execute()) {
				header("Location: ../view_thread.php?id={$thisThread}#last");
			}
			else {
				header("Location: ../database_error.php");
			}
		}
	}
	else {

		if (isset($_SERVER['HTTP_REFERER'])) {
			$refererUrl = $_SERVER['HTTP_REFERER'];
		}
		else {
			$refererUrl = '../forum.php';
		}

		header("Location: $refererUrl");
	}
?>