<?
	if (!isset($_SESSION)) {
		session_start();
	}

	include_once('../database/action.php');
	include_once('../database/events.php');
	include_once('../database/photos.php');
	include_once('../database/users.php');
	include_once('../database/session.php');

	if (safe_check($_POST, 'name') && events_nameExists($_POST['name'])){
		header("Location: ../message_event.php?id=1");
	}

	$eventId = events_getNextId();

	if (safe_check($_POST, 'name')) {
		$safeName = safe_trim($_POST['name']);
	}
	else {
		safe_redirect("../create_event.php");
	}

	if (safe_check($_POST, 'idUser')) {
		$userId = safe_getId($_POST, 'idUser');
	}
	else {
		safe_redirect("../create_event.php");
	}

	if (safe_check($_POST, 'description')) {
		$safeDescription = safe_trim($_POST['description']);
	}
	else {
		safe_redirect("../create_event.php");
	}

	if (safe_check($_POST, 'location')) {
		$tempLocation = trim($_POST['location'], '()');
		$newLocation = safe_trim($tempLocation);
	}
	else {
		safe_redirect("../create_event.php");
	}

	if (isset($_POST['private'])) {
		$newPrivate = 1;
	}
	else {
		$newPrivate = 0;
	}

	if (safe_check($_POST, 'type')) {

		if (safe_check($_POST, 'custom-type') && $_POST['type'] == 'Other') {
			$newType = $_POST['custom-type'];
		}
		else {
			$newType = $_POST['type'];
		}
	}
	else {
		safe_redirect("../create_event.php");
	}

	if (safe_check($_POST, 'date') && isset($_POST['hours']) && isset($_POST['minutes'])) {
		$newHours = safe_getId($_POST, 'hours');
		$newMinutes = safe_getId($_POST, 'minutes');
		$newDay = safe_trim($_POST['date']);
		$newDate = strtotime("$newDay {$newHours}:{$newMinutes} GMT");
	}
	else {
		safe_redirect("../create_event.php");
	}

	$stmt = $db->prepare('INSERT INTO Events VALUES(NULL, :name, :date, :location, :description, :private, :type, :idUser)');
	$stmt->bindParam(':name', $safeName, PDO::PARAM_STR);
	$stmt->bindParam(':date', $newDate, PDO::PARAM_INT);
	$stmt->bindParam(':location', $newLocation, PDO::PARAM_STR);
	$stmt->bindParam(':description', $safeDescription, PDO::PARAM_STR);
	$stmt->bindParam(':private', $newPrivate, PDO::PARAM_INT);
	$stmt->bindParam(':type', $newType, PDO::PARAM_STR);
	$stmt->bindParam(':idUser', $userId, PDO::PARAM_INT);

	if (users_fileUploaded()) {
		$baseFilename = basename($_FILES['image']['name']);
		$fileExtension = strtolower(substr($baseFilename, strrpos($baseFilename, '.') + 1));
		$outputFilename = "{$eventId}.{$fileExtension}";
		$uploadDirectory = '../img/events/';
		$uploadFile = "{$uploadDirectory}{$outputFilename}";
		$smallFile = "{$uploadDirectory}{$eventId}_small.{$fileExtension}";
		$mediumFile = "{$uploadDirectory}{$eventId}_medium.{$fileExtension}";

		if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)){
			header("Location: ../message_photo.php");
		}

		$originalImage = image_readFile($uploadFile, $fileExtension);

		if ($originalImage == null) {
			header("Location: ../message_photo.php");
		}

		$mediumImage = image_advancedcrop($originalImage, 400, 256, $fileExtension);
		$smallImage = image_resize($mediumImage, 200, $fileExtension);
		image_writeFile($mediumImage, $mediumFile, $fileExtension);
		image_writeFile($smallImage, $smallFile, $fileExtension);
		imagedestroy($originalImage);
		imagedestroy($mediumImage);
		imagedestroy($smallImage);
	}

	if ($stmt->execute()){
		header("Location: ../message_event.php?id=0&event=$eventId");
	}
	else {
		header("Location: ../database_error.php");
	}
?>