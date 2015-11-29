<?
	include_once('../database/action.php');
	include_once('../database/forum.php');
	include_once('../database/tags.php');
	include_once('../database/users.php');

	// check if thread exists
	if (isset($_POST['idUser']) && isset($_POST['title']) && isset($_POST['message'])) {
		$thisAuthor = intval($_POST['idUser']);
		$userExists = users_idExists($thisAuthor);
		$currentTime = time();

		if ($userExists) {
			$safeTitle = strip_tags_content($_POST['title']);
			$safeMessage = strip_tags_content($_POST['message']);
			$stmt = $db->prepare('INSERT INTO ForumPost VALUES(NULL, :idUser, :title, 0, :message, :timestamp)');
			$stmt->bindParam(':idUser', $thisParticipant, PDO::PARAM_INT);
			$stmt->bindParam(':title', $safeTitle, PDO::PARAM_STR);
			$stmt->bindParam(':message', $safeMessage, PDO::PARAM_STR);
			$stmt->bindParam(':timestamp', $currentTime, PDO::PARAM_INT);

			if ($stmt->execute()) {
				header("Location: ../view_thread.php?id={$thisThread}#comments");
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