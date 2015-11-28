<?
	$defaultThread = array(
		'idThread' => 0,
		'idUser' => 0,
		'title' => 'Sample Post',
		'hits' => 1,
		'message' => 'QWERTYUIOP :)',
		'timestamp' => 0,
	);

	function forum_allThreads() {
		global $db;
		$stmt = $db->prepare('SELECT * FROM ForumThread');
		$stmt->execute();
		return $stmt->fetchAll();
	}

	function forum_printDate($threadData) {

		if (!is_array($threadData) || !isset($threadData['timestamp'])) {
			$threadData = $defaultThread;
		}

		return date("l, d/m/Y H:i", $threadData['timestamp']);
	}

	function forum_viewThread($threadData) {

		if (!is_array($threadData) || !isset($threadData['idThread'])) {
			$threadData = $defaultThread;
		}

		$thread_id = intval($threadData['idThread']);

		if (!intval($thread_id)) {
			$thread_id = 0;
		}

		return "view_thread.php?id={$thread_id}";
	}

	function forum_countReplies() {
		global $db;
		$stmt = $db->prepare('SELECT idThread, COUNT(idThread) AS count FROM ForumPost GROUP BY idThread');
		$stmt->execute();
		return $stmt->fetchAll();
	}

	function thread_listById($thread_id) {
		global $db;
		$stmt = $db->prepare('SELECT ForumThread.*, username FROM ForumThread JOIN Users
			ON ForumThread.idThread = :idThread
			AND Users.idUser = ForumThread.idUser');
		$stmt->bindParam(':idThread', $thread_id, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetchAll();
	}

	function thread_listPosts($thread_id) {
		global $db;
		$stmt = $db->prepare('SELECT ForumPost.*, username FROM ForumPost JOIN Users 
			ON ForumPost.idThread = :idThread
			AND Users.idUser = ForumPost.idUser');
		$stmt->bindParam(':idThread', $thread_id, PDO::PARAM_INT);
		$stmt->execute();
    	$allPosts = array();

    	while(($result = $stmt->fetch()) != null) {
        	$allPosts[$result['idPost']] = $result;
   		}

   		return $allPosts;
	}

	function forum_lastReplies() {
		global $db;
		$stmt = $db->prepare('SELECT idThread, idUser, MAX(timestamp) AS timestamp FROM ForumPost GROUP BY idThread');	
		$stmt->execute();
		return $stmt->fetchAll();
	}
?>