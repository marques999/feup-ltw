<?
	function listCommentsByEvent($event_id) {
		global $db;
		$stmt = $db->prepare('SELECT * FROM Comments WHERE idEvent = :idEvent ORDER BY timestamp DESC');
		$stmt->bindParam(':idEvent', $event_id, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetchAll();
	}
?>