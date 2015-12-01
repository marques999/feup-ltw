<?
	include_once('../database/action.php');
	include_once('../database/forum.php');
	include_once('../database/tags.php');
	include_once('../database/users.php');

	// check if thread with the same name already exists
	if (isset($_POST['idUser']) && isset($_POST['title']) && isset($_POST['message'])) {
		$thisAuthor = safe_getId($_POST, 'idUser');
		$userExists = users_idExists($thisAuthor);
		$currentTime = time();
		$nextId = forum_getNextThread()+1;

		if ($userExists) {
			$safeTitle = strip_tags_content($_POST['title']);
			$safeMessage = strip_tags_content($_POST['message']);
			$stmt = $db->prepare('INSERT INTO ForumThread VALUES(NULL, :idUser, :title, "0", :message, :datetime)');
			$stmt->bindParam(':idUser', $thisAuthor, PDO::PARAM_INT);
			$stmt->bindParam(':title', $safeTitle, PDO::PARAM_STR);
			$stmt->bindParam(':message', $safeMessage, PDO::PARAM_STR);
			$stmt->bindParam(':datetime', $currentTime, PDO::PARAM_INT);

			if ($stmt->execute() != false) {
				header("Location: ../view_thread.php?id=$nextId");
			}
			else {
				header("Location: ../database_error.php");
			}
		}
	} else {
		if (isset($_SERVER['HTTP_REFERER'])) {
			$refererUrl = $_SERVER['HTTP_REFERER'];
		}
		else {
			$refererUrl = '../forum.php';
		}

		header("Location: $refererUrl");
	}
?>