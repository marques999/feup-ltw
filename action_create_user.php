<?
	include_once('database/connection.php');
	include_once('database/country.php');
	include_once('database/salt.php');
	include_once('database/users.php');
	include('template/header.php');
?>

<script>
	$(document).ready(function() {
		$('#nav_profile').addClass('active');
	});
</script>

<?
	if (!isset($_POST['username']) || !isset($_POST['password'])) {
		exit(0);
	}

	function logError($message) {?>
	<div class="column-group half-vertical-space">
		<div class="column all-20 large-15 medium-10 small-0 tiny-0"></div>
		<div class="column all-60 large-70 medium-80 small-100 tiny-100 ink-alert block error" role="alert">
			<h4>Database Error</h4>
			<p><?=$message?></p>
			<p>Please click <a href="register.php">here</a> to continue</p>
		</div>
	</div>
	<?exit(0);
}

$userExists = users_usernameExists($_POST['username']);
$emailExists = users_emailExists($_POST['email']);
?>

<?if($userExists) logError('User account creation failed! (username already taken)');?>
<?if($emailExists) logError('User account creation failed! (another user with the same e-mail address exists)');?>
<?$uploadDirectory = 'img/avatars/';

	if (isset($_POST['idUser'])) {
		$thisUser = $_POST['idUser'];
	} else {
		?><div class="column-group half-vertical-space">
		<div class="column all-20 large-15 medium-10 small-0 tiny-0"></div>
		<div class="column all-60 large-70 medium-80 small-100 tiny-100 ink-alert block error" role="alert">
			<h4>Database Error</h4>
			<p>User account creation failed! (invalid avatar uploaded)</p>
			<p>Please click <a href="register.php">here</a> to continue</p>
		</div>
	</div>
	<?}

	$baseFilename = basename($_FILES['userfile']['name']);
	$fileExtension = strtolower(substr($baseFilename, strrpos($baseFilename, '.') + 1));
	$outputFilename = "{$thisUser}_original.{$fileExtension}";
	$uploadFile = $uploadDirectory . $outputFilename;
	$smallFile = "img/avatars/{$thisUser}_small.{$fileExtension}";
	$mediumFile = "img/avatars/{$thisUser}.{$fileExtension}";

	echo $baseFilename.'<br>';
	echo $fileExtension.'<br>';
	echo $uploadFile.'<br>';
	echo $outputFilename.'<br>';
	echo $smallFile.'<br>';
	echo $mediumFile.'<br>';

	if (!move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadFile)) {
	    	?><div class="column-group half-vertical-space">
		<div class="column all-20 large-15 medium-10 small-0 tiny-0"></div>
		<div class="column all-60 large-70 medium-80 small-100 tiny-100 ink-alert block error" role="alert">
			<h4>Database Error</h4>
			<p>User account creation failed! (user has choosen an invalid image format)</p>
			<p>Please click <a href="register.php">here</a> to continue</p>
		</div>
	</div>
	  <?  exit(0);
	}

	$originalImage = null;

	if ($fileExtension == 'jpg' || $fileExtension == 'jpeg') {
		$originalImage = imagecreatefromjpeg($uploadFile);
	}
	else if ($fileExtension == 'png') {
		$originalImage = imagecreatefrompng($uploadFile);
	}
	else if ($fileExtension == 'bmp') {
		$originalImage = imagecreatefromwbmp($uploadFile);
	}
	else if ($fileExtension == 'gif') {
		$originalImage = imagecreatefromgif($uploadFile);
	}
	else {
		exit(0);
	}

	$originalWidth = imagesx($originalImage); // obter comprimento da imagem original
	$originalHeight = imagesy($originalImage); // obter largura da imagem original
	$thumbnailSize = 64;
	$mediumSize = 500;

	////////////////////////////////////////////////////////////
	// MEDIUM $mediumSize * $mediumSize                       //
	////////////////////////////////////////////////////////////

	if ($originalHeight > $originalWidth && $originalHeight > $mediumSize) {
		/* Portrait */
		$newHeight = $mediumSize;
		$newWidth = $newHeight * ($originalWidth / $originalHeight);
	}
	else if($originalWidth > $originalHeight && $originalWidth > $mediumSize) {
		/* Landscape */
		$newWidth = $mediumSize;
		$newHeight = $newWidth * ($originalHeight / $originalWidth);
	}
	else {
		$newWidth = $originalWidth;
		$newHeight = $originalHeight;
	}

	$resizedImage = imagecreatetruecolor($newWidth, $newHeight);

	// preserve transparency from original image (PNG/GIF only)
	if ($fileExtension == 'gif' || $fileExtension == 'png') {
	    imagecolortransparent($resizedImage, imagecolorallocatealpha($resizedImage, 0, 0, 0, 127));
	    imagealphablending($resizedImage, false);
	    imagesavealpha($resizedImage, true);
	}

	imagecopyresampled($resizedImage, $originalImage,
		0, 0, 0, 0,
		$newWidth, $newHeight, $originalWidth, $originalHeight);

	if ($fileExtension == 'jpg' || $fileExtension == 'jpeg') {
		imagejpeg($resizedImage, $mediumFile, 90);
	}
	else if ($fileExtension == 'gif') {
		imagegif($resizedImage, $mediumFile);
	}
	else {
		imagepng($resizedImage, $mediumFile);
	}

	////////////////////////////////////////////////////////////
	// THUMBNAIL $thumbnailSize * $thumbnailSize			  //
	////////////////////////////////////////////////////////////

	$thumbnailImage = imagecreatetruecolor($thumbnailSize, $thumbnailSize);

	// preserve transparency from original image (PNG/GIF only)
	if ($fileExtension == 'gif' || $fileExtension == 'png') {
	    imagecolortransparent($thumbnailImage, imagecolorallocatealpha($thumbnailImage, 0, 0, 0, 127));
	    imagealphablending($thumbnailImage, false);
	    imagesavealpha($thumbnailImage, true);
	}

	if ($newHeight == $newWidth) {
		imagecopyresampled($thumbnailImage, $resizedImage,
			0, 0, 0, 0,
			$thumbnailSize, $thumbnailSize, $newWidth, $newHeight);
	}
	else if ($newWidth > $newHeight) {
		$difference = $newWidth - $newHeight;
		imagecopyresampled($thumbnailImage, $resizedImage,
			0, 0, $difference / 2, 0,
			$thumbnailSize, $thumbnailSize, $newHeight, $newHeight);
	}
	else {
		$difference = $newHeight - $newWidth;
		imagecopyresampled($thumbnailImage, $resizedImage,
			0, 0, 0, $difference / 2,
			$thumbnailSize, $thumbnailSize, $newWidth, $newWidth);
	}

	if ($fileExtension == 'jpg' || $fileExtension == 'jpeg') {
		imagejpeg($thumbnailImage, $smallFile, 90);
	}
	else if ($fileExtension == 'gif') {
		imagegif($thumbnailImage, $smallFile);
	}
	else {
		imagepng($thumbnailImage, $smallFile);
	}

	imagedestroy($originalImage);
	imagedestroy($resizedImage);
	imagedestroy($thumbnailImage);

	// BEGIN USER INFORMATION

	$newPassword = create_hash($_POST['password']);
	$fullName = "{$_POST['first-name']} {$_POST['last-name']}";
	$stmt = $db->prepare('INSERT INTO Users VALUES(NULL, :username, :password, :name, :email, :location, :country)');

	// parameter 'username'
	$safeUsername = strip_tags_content($_POST['username']);
	$stmt->bindParam(':username', $_POST['username'], PDO::PARAM_STR);

	// parameter 'password'
	$stmt->bindParam(':password', $newPassword, PDO::PARAM_STR);

	// parameter 'fullname'
	$safeName = strip_tags_content($fullName);
	$stmt->bindParam(':name', $safeName, PDO::PARAM_STR);

	// parameter 'email'
	$safeEmail = strip_tags_content($_POST['email']);
	$stmt->bindParam(':email', $_POST['email'], PDO::PARAM_STR);

	// parameter 'location' & 'country'
	$safeLocation = strip_tags_content($_POST['location']);
	$stmt->bindParam(':location', $safeLocation, PDO::PARAM_STR);
	$stmt->bindParam(':country', $_POST['country'], PDO::PARAM_STR);

	<?if ($stmt->execute() == true){?>
	<div class="ink-grid all-45 large-60 medium-80 small-100 tiny-100">
		<div class="column ink-alert block success">
			<h4>Information</h4>
			<p>User account created successfully!</p>
			<p>You will be taken shortly to the login page...</p>
		</div>
	</div>
	<?}
	else {
		header("../database_error.php");
	}

	include('template/footer.php');
?>