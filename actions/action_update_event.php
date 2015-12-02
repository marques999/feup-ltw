<?
	if (!isset($_SESSION)) {
		session_start();
	}

	include_once('../database/action.php');
	include_once('../database/events.php');
	include_once('../database/users.php');	
	include_once('../database/session.php');

	if (isset($_POST['idEvent'])) {

		$eventId = safe_getId($_POST, 'idEvent');

		if ($eventId <= 0) {
			safe_redirect("../view_event.php?id=$eventId");
		}

		$stmt = $db->prepare('SELECT * FROM Events WHERE idEvent = :idEvent');
		$stmt->bindParam(':idEvent', $eventId, PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetchAll();
		$eventExists =  ($result != false) && is_array($result) && count($result) > 0;
		$currentEvent = $result[0];

		if (!$eventExists) {
			safe_redirect("../view_event.php?id=$eventId");
		}

		// update event name
		$validOperation = false;
		$hasName = isset($_POST['name']);

		if ($hasName) {
			$newName = safe_trim($_POST['name']);
			$validOperation = ($newName != $currentEvent['name']);
		}

		if ($validOperation) {
			$stmt = $db->prepare('UPDATE Events SET name = :name WHERE idEvent = :idEvent');
			$stmt->bindParam(':name', $newName, PDO::PARAM_STR);
			$stmt->bindParam(':idEvent', $eventId, PDO::PARAM_INT);
			$stmt->execute();
		}

		// update event description
		$validOperation = false;
		$hasDescription = isset($_POST['description']);

		if ($hasDescription) {
			$newDescription = safe_trim($_POST['description']);
			$validOperation = ($newDescription != $currentEvent['description']);
		}

		if ($validOperation) {
			$stmt = $db->prepare('UPDATE Events SET description = :description WHERE idEvent = :idEvent');
			$stmt->bindParam(':description', $newDescription, PDO::PARAM_STR);
			$stmt->bindParam(':idEvent', $eventId, PDO::PARAM_INT);
			$stmt->execute();
		}

		// update event location
		$hasLocation = isset($_POST['location']);

		if ($hasLocation && $_POST['location'] != $currentEvent['location']) {
			$stmt = $db->prepare('UPDATE Events SET location = :location WHERE idEvent = :idEvent');
			$stmt->bindParam(':location', $_POST['location'], PDO::PARAM_STR);
			$stmt->bindParam(':idEvent', $eventId, PDO::PARAM_INT);
			$stmt->execute();
		}

		// update event type
		$validOperation = false;
		$hasType = isset($_POST['type']);
		$hasCustomType = isset($_POST['custom-type']);

		if ($hasType && $hasCustomType && $_POST['type'] == 'Other') {
			$validOperation = true;
			$newType = $_POST['custom-type'];
		}
		else if ($hasType) {
			$validOperation = true;
			$newType = $_POST['type'];
		}

		if ($hasType && $newType != $currentEvent['type']) {
			$stmt = $db->prepare('UPDATE Events SET type = :type WHERE idEvent = :idEvent');
			$stmt->bindParam(':type', $newType, PDO::PARAM_STR);
			$stmt->bindParam(':idEvent', $eventId, PDO::PARAM_INT);
			$stmt->execute();
		}	

		// update event date and time
		$hasDatetime = isset($_POST['date']) && isset($_POST['hours']) && isset($_POST['minutes']);
		$newDate = 0;

		if ($hasDatetime && $newDate != $currentEvent['date']) {
			$stmt = $db->prepare('UPDATE Events SET date = :date WHERE idEvent = :idEvent');
			$stmt->bindParam(':date', $newDate, PDO::PARAM_STR);
			$stmt->bindParam(':idEvent', $eventId, PDO::PARAM_INT);
			$stmt->execute();
		}

		// update event photo (maybe?)
	}
	else {
		safe_redirect("../view_event.php?id=$eventId");
	}
?>