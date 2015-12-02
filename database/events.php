<?
	$defaultEvent = array(
		'idEvent' => 0,
		'name' => 'Sample Event',
		'date' => 0,
		'location' => 'Test',
		'description' => 'Hello World!',
		'private' => 0,
		'type' => 'Dummy',
		'idUser' => 0);

	$stmt = $db->prepare('SELECT * FROM Events');
	$stmt->execute();
	$allEvents = array();
	$allEvents[0] = $defaultEvent;

	while(($result = $stmt->fetch()) != null) {
		$allEvents[$result['idEvent']] = $result;
	}

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

	function events_idExists($event_id) {
		global $db;
		$stmt = $db->prepare('SELECT idEvent FROM Events WHERE idEvent = :idEvent');
		$stmt->bindParam(':idEvent', $event_id, PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetchAll();
		return $result != false && is_array($result) && count($result) > 0;
	}

	function events_nameExists($name) {
		global $db;
		$stmt = $db->prepare('SELECT idEvent FROM Events WHERE name = :name');
		$stmt->bindParam(':name', $name, PDO::PARAM_STR);
		$stmt->execute();
		$result = $stmt->fetchAll();
		return $result != false && is_array($result) && count($result) > 0;
	}

	function events_viewEvent($eventData) {

		if (!is_array($eventData) || !isset($eventData['idEvent'])) {
			$userData = $defaultUser;
		}

		$event_id = intval($eventData['idEvent']);

		if (!intval($event_id)) {
			$event_id = 0;
		}

		return "view_event.php?id={$event_id}";
	}

	function events_getName($eventData) {

		if (!is_array($eventData) || !isset($eventData['idEvent'])) {
			$eventData = $defaultUser;
		}

		if (isset($eventData['private']) && intval($eventData['private']) == 1) {
			return "<i class=\"fa fa-lock\"></i> {$eventData['name']}";
		}

		return $eventData['name'];
	}

	function events_getDate($eventData) {

		if (!is_array($eventData) || !isset($eventData['idEvent'])) {
			$eventData = $defaultEvent;
		}
		
		return gmdate("l, d/m/Y H:i", $eventData['date']);
	}

	function events_getImage($eventData, $imageSize) {

		if (!is_array($eventData) || !isset($eventData['idEvent'])) {
			$eventData = $defaultEvent;
		}

		$event_id = safe_getId($eventData, 'idEvent');

		//return glob("img/events/$event_id.{jpg,jpeg,gif,png}", GLOB_BRACE)[0];
		if ($imageSize == 'small') {
			return "holder.js/200x100/auto/ink";
		}

		if ($imageSize == 'medium') {
			return "holder.js/400x256/auto/ink";
		}

		return "holder.js/1200x580/auto/ink";
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
		global $defaultEvent;
		$stmt = $db->prepare('SELECT Events.idEvent, COUNT(UserEvents.idEvent) AS count FROM Events
								LEFT JOIN UserEvents ON UserEvents.idEvent = Events.idEvent
								GROUP BY Events.idEvent');
		$stmt->execute();
		$eventList = array();
		$eventList[0] = $defaultEvent;

		while(($result = $stmt->fetch()) != null) {
			$eventList[$result['idEvent']] = $result;
		}

		return $eventList;
	}

	function events_countInvites() {
		global $db;
		global $defaultEvent;
		$stmt = $db->prepare('SELECT Events.idEvent, COUNT(Invites.idEvent) AS count FROM Events
				LEFT JOIN Invites ON Invites.idEvent = Events.idEvent
				GROUP BY Events.idEvent');
		$stmt->execute();
		$eventList = array();
		$eventList[0] = $defaultEvent;

		while(($result = $stmt->fetch()) != null) {
			$eventList[$result['idEvent']] = $result;
		}

		return $eventList;
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
		$stmt = $db->prepare('SELECT Events.*, COUNT(UserEvents.idUser) AS participants 
			FROM Events, UserEvents
			WHERE Events.idEvent = UserEvents.idEvent
			AND private = 0
			GROUP BY UserEvents.idEvent
			ORDER BY participants DESC
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