<?
	if (!isset($_SESSION)) {
		session_start();
	}

	include_once('../database/action.php');
	include_once('../database/events.php');
	include_once('../database/photos.php');
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

		$hasDescription = isset($_POST['description']);

		if ($hasDescription) {
			$newDescription = safe_trim($_POST['description']);
		}

		if ($hasDescription && $newDescription != $currentEvent['description']) {
			$stmt = $db->prepare('UPDATE Events SET description = :description WHERE idEvent = :idEvent');
			$stmt->bindParam(':description', $newDescription, PDO::PARAM_STR);
			$stmt->bindParam(':idEvent', $eventId, PDO::PARAM_INT);
			$stmt->execute();
		}

		$hasLocation = isset($_POST['location']);

		if ($hasLocation && $_POST['location'] != $currentEvent['location']) {
			$tempLocation = trim($_POST['location'], '()');
			$newLocation = safe_trim($tempLocation);
			$stmt = $db->prepare('UPDATE Events SET location = :location WHERE idEvent = :idEvent');
			$stmt->bindParam(':location', $newLocation, PDO::PARAM_STR);
			$stmt->bindParam(':idEvent', $eventId, PDO::PARAM_INT);
			$stmt->execute();
		}

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

		$hasDatetime = isset($_POST['date']) && isset($_POST['hours']) && isset($_POST['minutes']);

		if ($hasDatetime) {
			$newHours = safe_getId($_POST, 'hours');
			$newMinutes = safe_getId($_POST, 'minutes');
			$newDay = safe_trim($_POST['date']);
			$newDate = strtotime("$newDay {$newHours}:{$newMinutes} GMT");
		}

		if ($hasDatetime && $newDate != $currentEvent['date']) {
			$stmt = $db->prepare('UPDATE Events SET date = :date WHERE idEvent = :idEvent');
			$stmt->bindParam(':date', $newDate, PDO::PARAM_STR);
			$stmt->bindParam(':idEvent', $eventId, PDO::PARAM_INT);
			$stmt->execute();
		}

		if (users_fileUploaded()) {
			$baseFilename = basename($_FILES['image']['name']);
			$fileExtension = strtolower(substr($baseFilename, strrpos($baseFilename, '.') + 1));
			$outputFilename = "{$eventId}.{$fileExtension}";
			$uploadDirectory = '../img/events/';
			$uploadFile = "{$uploadDirectory}{$outputFilename}";
			$smallFile = "{$uploadDirectory}{$eventId}_small.{$fileExtension}";
			$mediumFile = "{$uploadDirectory}{$eventId}_medium.{$fileExtension}";
			array_map('unlink', glob("../img/events/{$eventId}.{jpg,jpeg,gif,png}", GLOB_BRACE));
	
			if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)){
				header("Location: ../message_photo.php");
			}

			$originalImage = image_readFile($uploadFile, $fileExtension);

			if ($originalImage == null) {
				header("Location: ../message_photo.php");
			}

			$mediumImage = image_advancedcrop($originalImage, 400, 256, $fileExtension);
			$smallImage = image_resize($mediumImage, 200, $fileExtension);
			array_map('unlink', glob("../img/events/{$eventId}_small.{jpg,jpeg,gif,png}", GLOB_BRACE));
			array_map('unlink', glob("../img/events/{$eventId}_medium.{jpg,jpeg,gif,png}", GLOB_BRACE));
			image_writeFile($mediumImage, $mediumFile, $fileExtension);
			image_writeFile($smallImage, $smallFile, $fileExtension);
			imagedestroy($originalImage);
			imagedestroy($mediumImage);
			imagedestroy($smallImage);
		}
	}

	header("Location: ../view_event.php?id=$eventId");
?>