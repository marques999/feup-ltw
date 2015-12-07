<?
	if (!isset($_SESSION)) {
		session_start();
	}

	include_once('../database/action.php');
	include_once('../database/photos.php');
	include_once('../database/users.php');
	include_once('../database/salt.php');
	include_once('../database/session.php');

	$userId = users_getNextId() + 1;
	$userExists = users_usernameExists($_POST['username']);

	if ($userExists) {
		header("Location: ../message_register.php?id=1");
	}

	$emailExists = users_emailExists($_POST['email']);

	if ($emailExists) {
		header("Location: ../message_register.php?id=2");
	}

	if (safe_check($_POST, 'idUser')) {
		$thisUser = $_POST['idUser'];
	}
	else {
		safe_redirect("../register.php");
	}

	if (safe_check($_POST, 'username')) {
		$safeUsername = safe_trim($_POST['username']);
	}
	else {
		safe_redirect("../register.php");
	}

	if (safe_check($_POST, 'password')) {
		$newPassword = create_hash($_POST['password']);
	}
	else {
		safe_redirect("../register.php");
	}

	if (safe_check($_POST, 'first-name') && safe_check($_POST, 'last-name')) {
		$safeFirstName = safe_trim($_POST['first-name']);
		$safeLastName = safe_trim($_POST['last-name']);
		$fullName = "$safeFirstName $safeLastName";
	}
	else {
		safe_redirect("../register.php");
	}

	if (safe_check($_POST, 'email')) {
		$safeEmail = safe_trim($_POST['email']);
	}
	else {
		safe_redirect("../register.php");
	}

	if (safe_check($_POST, 'location') && safe_check($_POST, 'country')) {
		$safeLocation = safe_trim($_POST['location']);
	}
	else {
		safe_redirect("../register.php");
	}

	$stmt = $db->prepare('INSERT INTO Users VALUES(NULL, :username, :password, :name, :email, :location, :country)');
	$stmt->bindParam(':username', $safeUsername, PDO::PARAM_STR);
	$stmt->bindParam(':password', $newPassword, PDO::PARAM_STR);
	$stmt->bindParam(':name', $fullName, PDO::PARAM_STR);
	$stmt->bindParam(':email', $safeEmail, PDO::PARAM_STR);
	$stmt->bindParam(':location', $safeLocation, PDO::PARAM_STR);
	$stmt->bindParam(':country', $_POST['country'], PDO::PARAM_STR);

	if (users_fileUploaded()) {
		$uploadDirectory = '../img/avatars/';
		$baseFilename = basename($_FILES['image']['name']);
		$fileExtension = strtolower(substr($baseFilename, strrpos($baseFilename, '.') + 1));
		$outputFilename = "{$thisUser}_original.{$fileExtension}";
		$uploadFile = $uploadDirectory . $outputFilename;
		$smallFile = "../img/avatars/{$thisUser}_small.{$fileExtension}";
		$mediumFile = "../img/avatars/{$thisUser}.{$fileExtension}";

		if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)){
			header("Location: message_photo.php");
		}

		$originalImage = image_readFile($uploadFile, $fileExtension);

		if ($originalImage == null) {
			header("Location: ../message_photo.php");
		}

		$resizedImage = image_resize($originalImage, 500, $fileExtension);
		$thumbnailImage = image_crop($resizedImage, 64, 64);
		image_writeFile($resizedImage, $mediumFile, $fileExtension);
		image_writeFile($thumbnailImage, $smallFile, $fileExtension);
		imagedestroy($originalImage);
		imagedestroy($resizedImage);
		imagedestroy($thumbnailImage);
	}

	if($stmt->execute()){
		header("Location: ../message_register.php?id=0");
	}
	else {
		header("Location: ../database_error.php");
	}
?>