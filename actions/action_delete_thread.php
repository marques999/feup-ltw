<?
	if (!isset($_SESSION)) {
		session_start();
	}

	include_once('../database/action.php');
	include_once('../database/forum.php');
	include_once('../database/users.php');
	include_once('../database/session.php');

	if (safe_check($_POST, 'idThread') && $loggedIn) {

		$threadId=intval($_POST['idThread']);
		$threadExists=forum_threadExists($threadId);

		if ($threadId > 0 && $threadExists) {

			$thread = thread_listById($threadId);

			if (is_array($thread) && count($thread) > 0) {

				$thread = $thread[0];

				if($thread['idUser'] == $thisUser) {

					$stmt = $db->prepare('DELETE FROM ForumThread WHERE idUser = :idUser AND idThread = :idThread');
					$stmt->bindParam(':idUser', $thisUser, PDO::PARAM_INT);
					$stmt->bindParam(':idThread', $threadId, PDO::PARAM_INT);

					if ($stmt->execute()){
						header("Location: ../forum.php");
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
		}
		else {
			safe_redirect("../forum.php");
		}
	}
	else {
		safe_redirect("../index.php");
	}
?>