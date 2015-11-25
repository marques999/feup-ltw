<?php

	function events_listPublic() {		
		global $db;
		$stmt = $db->prepare('SELECT * FROM Events WHERE private = 0');
		$stmt->execute();	
		return $stmt->fetchAll();
	}

	function events_listPrivate() {		
		global $db;
		$stmt = $db->prepare('SELECT * FROM Events WHERE private = 1');
		$stmt->execute();	
		return $stmt->fetchAll();
	}

	function events_viewEvent($eventData) {

        $event_id = intval($eventData['idEvent']);

        if (!intval($event_id)) {
            $event_id = 0;
        }

        return "view_event.php?id={$event_id}";     
    }

	function events_getName($eventData) {

		$event_id = intval($eventData['idEvent']);
        $isPrivate = intval($eventData['private']);

        if (!intval($event_id)) {
            $event_id = 0;
        }

       	if ($isPrivate) {
       		return "<i class=\"fa fa-lock\"></i> {$eventData['name']}";
       	}

        return $eventData['name'];
	}

	function events_listParticipants($event_id) {
		global $db;
		$stmt = $db->prepare('SELECT Users.idUser, Users.username FROM Users 
								JOIN UserEvents ON UserEvents.idEvent = :idEvent
								AND UserEvents.idUser = Users.idUser');
		$stmt->bindParam(':idEvent', $event_id, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetchAll();
	}

	function events_countParticipants() {
		global $db;
		$stmt = $db->prepare('SELECT Events.idEvent, COUNT(UserEvents.idEvent) AS count FROM Events
								LEFT JOIN UserEvents ON UserEvents.idEvent = Events.idEvent
								GROUP BY Events.idEvent');
		$stmt->execute();
		return $stmt->fetchAll();
	}

	function events_countInvites() {
		global $db;
		$stmt = $db->prepare('SELECT Events.idEvent, COUNT(Invites.idEvent) AS count FROM Events
								LEFT JOIN Invites ON Invites.idEvent = Events.idEvent
								GROUP BY Events.idEvent');
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
		$stmt = $db->prepare('SELECT * FROM Events WHERE private = 0 ORDER BY RANDOM() LIMIT 1');
		$stmt->execute();
		return $stmt->fetch();
	}

	function events_listTopEvents($top_n) {
		
		global $db;
		$stmt = $db->prepare('SELECT Events.*, COUNT(*) FROM Events, UserEvents
								WHERE Events.idEvent = UserEvents.idEvent
								AND private = 0
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