<?
	include_once('../database/action.php');
	include_once('../database/forum.php');
	include_once('../database/users.php');

	if (isset($_POST['idThread']) && $loggedIn) {
		$threadId = intval($_POST['idThread']);
		$threadExists = forum_idExist($threadId);
		
		if ($threadId > 0 && $threadExists) {
			$thread = thread_listById($threadId);

			if ($thisUser == $allEvents[$eventId]['idUser']) {
				$stmt = $db->prepare('DELETE FROM ForumThread WHERE idUser = :idUser AND idThread = :idThread');
				$stmt->bindParam(':idUser', $thisUser, PDO::PARAM_INT);
				$stmt->bindParam(':idThread', $threadId, PDO::PARAM_INT);
				
				if (!$stmt->execute()) {
					header("../database_error.php");
				}
			}
		}
	}

	if (isset($_SERVER['HTTP_REFERER'])) {
		$refererUrl = $_SERVER['HTTP_REFERER'];
	}
	else {
		$refererUrl = '../forum.php';
	}

	header("Location: $refererUrl");
?>