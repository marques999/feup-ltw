<?
	if (!isset($_SESSION)) {
		session_start();
	}

	include_once('../database/action.php');
	include_once('../database/photos.php');
	include_once('../database/salt.php');
	include_once('../database/users.php');

	if (safe_check($_POST, 'idUser') && safe_check($_POST, 'field')) {

		$userId = safe_getId($_POST, 'idUser');
		$changedField = safe_getId($_POST, 'field');
		$stmt = $db->prepare('SELECT * FROM Users WHERE idUser = :idUser');
		$stmt->bindParam(':idUser', $userId, PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetchAll();
		$userExists =  ($result != false) && is_array($result) && count($result) > 0;
		$currentUser = $result[0];
		$oldPassword = $currentUser['password'];
		$validOperation = false;

		if ($changedField == 1) {

			if (isset($_POST['current-password']) && isset($_POST['next-password']) && isset($_POST['confirm-password'])) {

				$currentPassword = safe_trim($_POST['current-password']);
				$confirmPassword = safe_trim($_POST['confirm-password']);
				$safePassword = safe_trim($_POST['next-password']);

				if (!validate_password($currentPassword, $oldPassword)){
					header("Location: ../update_profile.php?field=$changedField&error=1");
				}
				else if (validate_password($safePassword, $oldPassword)){
					header("Location: ../update_profile.php?field=$changedField&error=3");
				}
				else if ($safePassword != $confirmPassword) {
					header("Location: ../update_profile.php?field=$changedField&error=2");
				}
				else {
					$validOperation = true;
					$newPassword = create_hash($safePassword);
					$stmt = $db->prepare('UPDATE Users SET password = :password WHERE idUser = :idUser');
					$stmt->bindParam(':password', $newPassword, PDO::PARAM_STR);
				}
			}
		}
		else if ($changedField == 2) {

			if (safe_check($_POST, 'first-name') && safe_check($_POST, 'last-name')) {
				$validOperation = true;
				$fullName = "{$_POST['first-name']} {$_POST['last-name']}";
				$safeName = safe_trim($fullName);
				$stmt = $db->prepare('UPDATE Users SET name = :name WHERE idUser = :idUser');
				$stmt->bindParam(':name', $safeName, PDO::PARAM_STR);
			}
		}
		else if ($changedField == 3) {

			if (safe_check($_POST, 'email')) {
				$validOperation = true;
				$safeEmail = safe_trim($_POST['email']);
				$stmt = $db->prepare('UPDATE Users SET email = :email WHERE idUser = :idUser');
				$stmt->bindParam(':email', $safeEmail, PDO::PARAM_STR);
			}
		}
		else if ($changedField == 4) {

			if (isset($_POST['location']) && isset($_POST['country'])) {
				$validOperation = true;
				$safeLocation = safe_trim($_POST['location']);
				$stmt = $db->prepare('UPDATE Users SET location = :location, country = :country WHERE idUser = :idUser');
				$stmt->bindParam(':location', $safeLocation, PDO::PARAM_STR);
				$stmt->bindParam(':country', $_POST['country'], PDO::PARAM_STR);
			}
		}
		else if ($changedField == 5 && users_fileUploaded()) {
			$uploadDirectory = '../img/avatars/';
			$baseFilename = basename($_FILES['image']['name']);
			$fileExtension = strtolower(substr($baseFilename, strrpos($baseFilename, '.') + 1));
			$outputFilename = "{$userId}_original.{$fileExtension}";
			$uploadFile = $uploadDirectory . $outputFilename;
			$smallFile = "../img/avatars/{$userId}_small.{$fileExtension}";
			$mediumFile = "../img/avatars/{$userId}.{$fileExtension}";
			array_map('unlink', glob("../img/avatars/{$userId}_original.{jpg,jpeg,gif,png}", GLOB_BRACE));
	
			if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)){
				header("Location: message_photo.php");
			}

			$originalImage = image_readFile($uploadFile, $fileExtension);

			if ($originalImage == null) {
				header("Location: ../message_photo.php");
			}

			$resizedImage = image_resize($originalImage, 500, $fileExtension);
			$thumbnailImage = image_crop($resizedImage, 64, 64);
			array_map('unlink', glob("../img/avatars/{$userId}.{jpg,jpeg,gif,png}", GLOB_BRACE));
			array_map('unlink', glob("../img/avatars/{$userId}_small.{jpg,jpeg,gif,png}", GLOB_BRACE));
			image_writeFile($resizedImage, $mediumFile, $fileExtension);
			image_writeFile($thumbnailImage, $smallFile, $fileExtension);
			imagedestroy($originalImage);
			imagedestroy($resizedImage);
			imagedestroy($thumbnailImage);
		}

		if ($validOperation) {

			$stmt->bindParam(':idUser', $userId, PDO::PARAM_INT);

			if (!$stmt->execute()) {
				header("Location: ../database_error.php");
			}

			header("Location: ../view_profile.php?id=$userId");
		}
	}
	else {
		safe_redirect("../index.php");
	}
?>