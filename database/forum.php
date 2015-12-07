<?
	$defaultThread = array(
		'idThread' => 0,
		'idUser' => 0,
		'title' => 'Sample Post',
		'hits' => 1,
		'message' => 'QWERTYUIOP :)',
		'timestamp' => 0
	);

	$defaultPost = array(
		'idPost' => 0,
		'idThread' => 0,
		'idUser' => 0,
		'message' => 'QWERTYUIOP :)',
		'timestamp' => 0,
		'idQuote' => 0
	);

	$stmt = $db->prepare('SELECT ForumPost.*, username FROM ForumPost JOIN Users ON Users.idUser = ForumPost.idUser');
	$stmt->execute();
	$allPosts = array();
	$allPosts[0] = $defaultPost;

	while (($result = $stmt->fetch()) != null) {
		$allPosts[$result['idPost']] = $result;
	}

	function forum_getNextThread() {

		global $db;
		$stmt = $db->prepare("SELECT * FROM SQLITE_SEQUENCE WHERE name='ForumThread'");
		$stmt->execute();
		$result = $stmt->fetch();

		if ($result != false && is_array($result)) {
			return $result['seq'];
		}

		return -1;
	}

	function forum_getNextPost() {

		global $db;
		$stmt = $db->prepare("SELECT * FROM SQLITE_SEQUENCE WHERE name='ForumPost'");
		$stmt->execute();
		$result = $stmt->fetch();

		if ($result != false && is_array($result)) {
			return $result['seq'];
		}

		return -1;
	}

	function forum_printPost($currentPost, $thisThread) {

		global $allPosts;

		if(isset($currentPost['idQuote'])){

			$quoteId=$currentPost['idQuote'];
			$validReference=false;

			if ($quoteId == 0) {
				$quotedPost = $thisThread;
				$validReference = true;
			}
			else if (isset($allPosts[$quoteId])) {
				$quotedPost = $allPosts[$quoteId];
				$validReference = true;
			}

			if($validReference){?>
			<p class="half-vertical-space">
				<small class="no-margin"><?=$quotedPost['username']?> wrote:</small>
				<blockquote class="no-margin">
					<?forum_printPost($quotedPost, $thisThread)?>
				</blockquote>
			</p>
			<?}?>
		<?}?>

		<?if($currentPost==null){
			$postMessage=preg_split('/\r\n|\r|\n/', parseEmoticons($thisThread['message']));
		}
		else{
			$postMessage=preg_split('/\r\n|\r|\n/', parseEmoticons($currentPost['message']));
		}
		foreach($postMessage as $line){?>
			<p class="half-vertical-space">
			<?=$line?>
			</p>
		<?}?>
	<?}

	function forum_printDate($threadData) {

		global $defaultThread;

		if (!is_array($threadData) || !isset($threadData['timestamp'])) {
			$threadData = $defaultThread;
		}

		return gmdate("l, d/m/Y H:i", $threadData['timestamp']);
	}

	function forum_viewThread($threadData) {

		global $defaultThread;

		if (!is_array($threadData) || !isset($threadData['idThread'])) {
			$threadData = $defaultThread;
		}

		$thread_id = intval($threadData['idThread']);

		if (!intval($thread_id)) {
			$thread_id = 0;
		}

		return "view_thread.php?id={$thread_id}";
	}

	function forum_allThreads() {
		global $db;
		$stmt = $db->prepare('SELECT ForumThread.*, MAX(ForumPost.timestamp) AS last
			FROM ForumThread
			LEFT JOIN ForumPost
			ON ForumPost.idThread = ForumThread.idThread
			GROUP BY ForumThread.idThread
			ORDER BY CASE WHEN last IS NULL
			THEN ForumThread.timestamp
			ELSE last END DESC');
		$stmt->execute();
		return $stmt->fetchAll();
	}

	function forum_countReplies() {

		global $db;
		$stmt = $db->prepare('SELECT idThread, COUNT(idThread) AS count FROM ForumPost GROUP BY idThread');
		$stmt->execute();
		$allThreads = array();

		while (($result = $stmt->fetch()) != null) {
			$allThreads[$result['idThread']] = $result['count'];
		}

		return $allThreads;
	}

	function forum_lastReplies() {

		global $db;
		$stmt = $db->prepare('SELECT idThread, idUser, MAX(timestamp) AS timestamp FROM ForumPost GROUP BY idThread');
		$stmt->execute();
		$allThreads = array();

		while (($result = $stmt->fetch()) != null) {
			$allThreads[$result['idThread']] = $result;
		}

		return $allThreads;
	}

	function thread_listById($thread_id) {
		global $db;
		$stmt = $db->prepare('SELECT ForumThread.*, username FROM ForumThread JOIN Users ON ForumThread.idThread = :idThread	AND Users.idUser = ForumThread.idUser');
		$stmt->bindParam(':idThread', $thread_id, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetchAll();
	}

	function post_listById($post_id) {
		global $db;
		$stmt = $db->prepare('SELECT ForumPost.*, username FROM ForumPost JOIN Users ON ForumPost.idPost = :idPost AND Users.idUser = ForumPost.idUser');
		$stmt->bindParam(':idPost', $post_id, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetchAll();
	}

	function forum_postExists($post_id) {
		global $db;
		$stmt = $db->prepare('SELECT idPost FROM ForumPost WHERE idPost = :idPost');
		$stmt->bindParam(':idPost', $post_id, PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetchAll();
		return $result != false && is_array($result) && count($result) > 0;
	}

	function forum_threadExists($thread_id) {
		global $db;
		$stmt = $db->prepare('SELECT idThread FROM ForumThread WHERE idThread = :idThread');
		$stmt->bindParam(':idThread', $thread_id, PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetchAll();
		return $result != false && is_array($result) && count($result) > 0;
	}

	function thread_listPosts($thread_id) {

		global $db;
		$stmt = $db->prepare('SELECT ForumPost.*, username FROM ForumPost JOIN Users ON ForumPost.idThread = :idThread AND Users.idUser = ForumPost.idUser');
		$stmt->bindParam(':idThread', $thread_id, PDO::PARAM_INT);
		$stmt->execute();
		$allPosts = array();

		while (($result = $stmt->fetch()) != null) {
			$allPosts[$result['idPost']] = $result;
		}

		return $allPosts;
	}
?>