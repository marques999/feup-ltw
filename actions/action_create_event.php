<?
	if (!isset($_SESSION)) {
		session_start();
	}

	include_once('../database/action.php');
	include_once('../database/events.php');
	include_once('../database/photos.php');
	include_once('../database/users.php');
	include_once('../database/session.php');

	$eventId = events_getNextId();
?>

<script>
$(function() {
	$('#nav_profile').addClass('active');
});
</script>

<?if(safe_check($_POST, 'name') && events_nameExists($_POST['name'])){?>
	<div class="column-group half-vertical-space">
		<div class="column all-20 large-15 medium-10 small-0 tiny-0"></div>
		<div class="column all-60 large-70 medium-80 small-100 tiny-100 ink-alert block error" role="alert">
			<h4>Database Error</h4>
			<p>Event creation failed! An event with the same name already exists in the database.</p>
			<p>Please click <a href="event_create.php">here</a> to continue</p>
		</div>
	</div>
<?} else {
	
	$baseFilename = basename($_FILES['image']['name']);
	$fileExtension = strtolower(substr($baseFilename, strrpos($baseFilename, '.') + 1));
	$outputFilename = "{$eventId}.{$fileExtension}";
	$uploadDirectory = '../img/events/';
	$uploadFile = "{$uploadDirectory}{$outputFilename}";
	$smallFile = "{$uploadDirectory}{$eventId}_small.{$fileExtension}";
	$mediumFile = "{$uploadDirectory}{$eventId}_medium.{$fileExtension}";

	echo $baseFilename.'<br>';
	echo $fileExtension.'<br>';
	echo $uploadFile.'<br>';
	echo $outputFilename.'<br>';
	echo $smallFile.'<br>';
	echo $mediumFile.'<br>';

	if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)){
		header("Location: ../message_photo.php");
	}

	$originalImage = image_readFile($uploadFile, $fileExtension);
	
	if ($originalImage == null) {
		header("Location: ../message_photo.php");
	}

	$smallSize = 200;
	$mediumWidth = 400;
	$mediumHeight = 256;
	$mediumImage = image_advancedcrop($originalImage, $mediumWidth, $mediumHeight, $fileExtension);
	$smallImage = image_resize($mediumImage, $smallSize, $fileExtension);

	image_writeFile($mediumImage, $mediumFile, $fileExtension);
	image_writeFile($smallImage, $smallFile, $fileExtension);
	imagedestroy($originalImage);
	imagedestroy($mediumImage);
	imagedestroy($smallImage);
	
	// parameter 'name'
	if (safe_check($_POST, 'name')) {
		$safeName = safe_trim($_POST['name']);
	}
	else {
		//safe_redirect("../create_event.php");
	}

	// parameter 'idUser'
	if (safe_check($_POST, 'idUser')) {
		$userId = safe_getId($_POST, 'idUser');
	}
	else {
		//safe_redirect("../create_event.php");
	}

	// parameter 'description'
	if (safe_check($_POST, 'description')) {
		$safeDescription = safe_trim($_POST['description']);
	}
	else {
		//safe_redirect("../create_event.php");
	}

	// parameter 'location'
	if (safe_check($_POST, 'location')) {
		$tempLocation = trim($_POST['location'], '()');
		$newLocation = safe_trim($tempLocation);
	}
	else {
		//safe_redirect("../create_event.php");
	}

	/*// parameter 'private'
	if (safe_check($_POST, 'private')) {
		$newPrivate = intval($_POST['private']) ? 1 : 0;
	}
	else {
		safe_redirect("../create_event.php");
	}*/

	// parameter 'type'
	if (safe_check($_POST, 'type')) {

		if (safe_check($_POST, 'custom-type') && $_POST['type'] == 'Other') {
			$newType = $_POST['custom-type'];
		}
		else {
			$newType = $_POST['type'];
		}
	}
	else {
		//safe_redirect("../create_event.php");
	}

	// parameter 'date'
	if (safe_check($_POST, 'date') && isset($_POST['hours']) && isset($_POST['minutes'])) {
		$newHours = safe_getId($_POST, 'hours');
		$newMinutes = safe_getId($_POST, 'minutes');
		$newDay = safe_trim($_POST['date']);
		$newDate = strtotime("$newDay {$newHours}:{$newMinutes}");
	}
	else {
		//safe_redirect("../create_event.php");
	}

	$stmt = $db->prepare('INSERT INTO Events VALUES(NULL, :name, :date, :location, :description, :private, :type, :idUser');
	$stmt->bindParam(':name', $safeName, PDO::PARAM_STR);
	$stmt->bindParam(':date', $newDate, PDO::PARAM_STR);
	$stmt->bindParam(':location', $safeLocation, PDO::PARAM_STR);
	$stmt->bindParam(':description', $safeDescription, PDO::PARAM_STR);
	$stmt->bindParam(':private', $newPrivate, PDO::PARAM_INT);
	$stmt->bindParam(':type', $newType, PDO::PARAM_STR);
	$stmt->bindParam(':idUser', $userId, PDO::PARAM_INT);
	$stmt->execute();
?>
<div class="ink-grid all-80 medium-90 small-90">
<?if ($stmt->execute()){?>
	<div class="column-group half-vertical-space">
		<div class="column all-20 large-15 medium-10 small-0 tiny-0"></div>
		<div class="column all-60 large-70 medium-80 small-100 tiny-100 ink-alert block success" role="alert">
			<h4>Information</h4>
			<p>User account created successfully!</p>
			<p>You will be taken shortly to the login page...</p>
		</div>
	</div>
	<?header("Refresh: 5;URL=login.php");
}else{
	include('message_database.php');
}?>
</div>
<?}
	include('../template/footer.php');
?>