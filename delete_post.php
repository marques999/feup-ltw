<?
	if(!isset($_SESSION)){
		session_start();
	}

	include_once('database/connection.php');
	include_once('database/forum.php');
	include_once('database/users.php');
	include_once('database/session.php');

	if (isset($_GET['id']) && $loggedIn) {
		$postId = safe_getId($_GET, 'id');

		if ($postId > 0 && forum_postExists($postId)) {
			$thisPost = post_listById($postId);

			if (count($thisPost) > 0 && $thisUser == $thisPost[0]['idUser']) {
				$stmt = $db->prepare('DELETE FROM ForumPost WHERE idUser = :idUser AND idPost = :idPost');
				$stmt->bindParam(':idUser', $thisUser, PDO::PARAM_INT);
				$stmt->bindParam(':idPost', $postId, PDO::PARAM_INT);
				$stmt->execute();
			}

			safe_redirect("view_thread.php?id={$thisPost['idThread']}");
		}
		else {
			safe_redirect("forum.php");
		}
	}
	else {
		safe_redirect("index.php");
	}
?>