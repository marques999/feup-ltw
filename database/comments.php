<?

	function listCommentsByEvent($event_id) {
		global $db;
		$stmt = $db->prepare('SELECT * FROM Comments WHERE idEvent = :idEvent');
		$stmt->bindParam(':idEvent', $event_id, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetchAll();
	}

	function events_listTopComments($event_id, $number) {
		
		global $db;
		$stmt = $db->prepare('SELECT * FROM Comments 
								WHERE idEvent = :idEvent
								ORDER BY timestamp DESC
								LIMIT :number');
		$stmt->bindParam(':idEvent', $event_id, PDO::PARAM_INT);
		$stmt->bindParam(':number', $number, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetchAll();
	}
?>