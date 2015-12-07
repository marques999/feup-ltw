<?
	if (!isset($_SESSION)) {
		session_start();
	}

	include_once('../database/action.php');
	include_once('../database/forum.php');
	include_once('../database/users.php');
	include_once('../database/session.php');

	if (safe_check($_POST, 'idUser') && safe_check($_POST, 'idThread') && safe_check($_POST, 'message')) {
		$thisThread = safe_getId($_POST, 'idThread');
		$thisParticipant = safe_getId($_POST, 'idUser');
		$userExists = users_idExists($thisParticipant);
		$isQuote = false;
		$currentTime = time();

		if (isset($_POST['idQuote'])) {
			$isQuote = true;
		}

		if ($userExists) {

			$safeMessage = safe_trim($_POST['message']);

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
		else {
			safe_redirect("../forum.php");
		}
	}
	else {
		safe_redirect("../forum.php");
	}
?>