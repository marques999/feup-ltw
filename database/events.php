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

	while (($result = $stmt->fetch()) != null) {
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

		if ($imageSize == 'small') {
			$smallLocation = glob("img/events/{$event_id}_small.{jpg,jpeg,gif,png}", GLOB_BRACE);
			return $smallLocation != false ? $smallLocation[0] : "holder.js/200x128/auto/ink";
		}

		if ($imageSize == 'medium') {
			$mediumLocation = glob("img/events/{$event_id}_medium.{jpg,jpeg,gif,png}", GLOB_BRACE);
			return $mediumLocation != false ? $mediumLocation[0] : "holder.js/400x256/auto/ink";
		}

		$originalLocation = glob("img/events/{$event_id}.{jpg,jpeg,gif,png}", GLOB_BRACE);
		return $originalLocation != false ? $originalLocation[0] : "holder.js/1200x580/auto/ink";
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

		while (($result = $stmt->fetch()) != null) {
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

		while (($result = $stmt->fetch()) != null) {
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

	function events_getNextId() {

		global $db;
		$stmt = $db->prepare("SELECT * FROM SQLITE_SEQUENCE WHERE name='Events'");
		$stmt->execute();
		$result = $stmt->fetch();

		if ($result != false && is_array($result)) {
			return $result['seq'] + 1;
		}

		return -1;
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

	function getEventsOrdered($type, $order, $curr_time) {

		global $db;

		if ($type == 'popularity') {
			if ($order == 1) {
				$stmt = $db->prepare("SELECT Events.*, COUNT(Events.idUser) AS following FROM Events
					JOIN UserEvents ON UserEvents.idEvent = Events.idEvent
					AND Events.private = 0 AND :curr_time < Events.date
					GROUP BY UserEvents.idEvent
					ORDER BY following DESC");
			}
			else {
				$stmt = $db->prepare("SELECT Events.*, COUNT(Events.idUser) AS following FROM Events
					JOIN UserEvents ON UserEvents.idEvent = Events.idEvent
					AND Events.private = 0 AND :curr_time < Events.date
					GROUP BY UserEvents.idEvent
					ORDER BY following ASC");
			}
		}
		else {
			if ($order == 1) {
				$stmt = $db->prepare("SELECT * FROM Events WHERE private = 0 AND :curr_time < date ORDER BY $type DESC");
			}
			else {
				$stmt = $db->prepare("SELECT * FROM Events WHERE private = 0 AND :curr_time < date ORDER BY $type ASC");
			}
		}

		$stmt->bindParam(':curr_time', $curr_time, PDO::PARAM_INT);
		$stmt->execute();

		return $stmt->fetchAll();
	};

	function listFilteredEvents($name, $type, $date1, $date2, $date_options, $order, $orderBy) {

		global $db;
		$query = 'SELECT * FROM Events WHERE private = 0';

		if ($name != '') {
			$query .= " AND name LIKE '%{$name}%'";
		}

		if ($type != '') {
			$query .= ' AND type = :type';
		}

		if ($date_options == 0) {
			$date1 = $date1 + 3600 * 24;
			$query .= ' AND date >= :date1';
		}
		else if ($date_options == 1) {
			$query .= ' AND date <= :date1';
		}
		else if ($date_options == 3 || $date_options == 2) {
			$date2 = $date2 + 3600 * 24;
			$query .= ' AND date BETWEEN :date1 AND :date2';
		}

		if($order == 1) {
			$query .= ' ORDER BY $orderBy DESC';
		}
		else {
			$query .= ' ORDER BY $orderBy ASC';
		}

		$stmt = $db->prepare($query);

		if($type != '') {
			$stmt->bindParam(':type', $type, PDO::PARAM_STR);
		}

		if ($date_options == 3) {
			$stmt->bindParam(':date1', $date1, PDO::PARAM_INT);
			$stmt->bindParam(':date2', $date2, PDO::PARAM_INT);
		}
		else if ($date_options == 2) {
			$endOfDay = $date1 + 60*60*24;
			$stmt->bindParam(':date1', $date1, PDO::PARAM_INT);
			$stmt->bindParam(':date2', $endOfDay, PDO::PARAM_INT);
		}
		else if ($date_options >= 0) {
			$stmt->bindParam(':date1', $date1, PDO::PARAM_INT);
		}

		$stmt->execute();

		return $stmt->fetchAll();
	};
?>