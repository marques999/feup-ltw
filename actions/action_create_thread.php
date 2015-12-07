<?
	if (!isset($_SESSION)) {
		session_start();
	}

	include_once('../database/action.php');
	include_once('../database/forum.php');
	include_once('../database/users.php');

	if (safe_check($_POST, 'idUser') && safe_check($_POST, 'title') && safe_check($_POST, 'message')) {
		$thisAuthor = safe_getId($_POST, 'idUser');
		$userExists = users_idExists($thisAuthor);
		$nextId = forum_getNextThread() + 1;
		$currentTime = time();

		if ($userExists) {
			$safeTitle = safe_trim($_POST['title']);
			$safeMessage = safe_trim($_POST['message']);
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
		else {
			safe_redirect("../forum.php");
		}
	}
	else {
		safe_redirect("../index.php");
	}
?>