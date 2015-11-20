<?php
/*	function listPublicEvents() {
		
		global $db;
		$stmt = $db->prepare('SELECT * FROM Events');
		$stmt->execute();
		
		return $stmt->fetchAll();
	}



	function deleteEvent($event_id, $user_id) {

		global $db;
		$stmt = $db->prepare('DELETE FROM Events 
								WHERE idEvent = :idEvent
								AND idUser = :idUser');
		$stmt->bindParam(':idEvent', $event_id, PDO::PARAM_INT);
		$stmt->bindParam(':idUser', $user_id, PDO::PARAM_INT);
		
		return $stmt->execute();
	}
*/


	function events_listParticipants($event_id) {
		global $db;
		$stmt = $db->prepare('SELECT Users.idUser, Users.username FROM Users 
									JOIN UserEvents ON UserEvents.idEvent = :idEvent
									AND UserEvents.idUser = Users.idUser');
		$stmt->bindParam(':idEvent', $event_id, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetchAll();
	}

	function events_listById($event_id) {
		global $db;
		$stmt = $db->prepare('SELECT * FROM Events WHERE idEvent = :idEvent');
		$stmt->bindParam(':idEvent', $event_id, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetchAll();
	}

	function events_randomEvent() {
		global $db;
		$stmt = $db->prepare('SELECT * FROM Events ORDER BY RANDOM() LIMIT 1');
		$stmt->execute();
		return $stmt->fetch();
	}

	function events_listTopEvents($top_n) {
		
		global $db;
		$stmt = $db->prepare('SELECT Events.*, COUNT(*) FROM Events, UserEvents
								WHERE Events.idEvent = UserEvents.idEvent
								GROUP BY UserEvents.idEvent
								LIMIT :topResults');
		$stmt->bindParam(':topResults', $top_n, PDO::PARAM_INT);
		$stmt->execute();

		return $stmt->fetchAll();
	}

	function event_listTypes() {
		global $db;
		$stmt = $db->prepare('SELECT DISTINCT type FROM Events');
		$stmt->execute();
		return $stmt->fetchAll();	
	}
?>