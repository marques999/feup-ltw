<?
	$imageSource = 'user';
	$thisUser = 4;
	$uploadDirectory = '../img/avatars/';

	if (isset($_POST['source']) && isset($_POST['idUser'])) {
		$imageSource = $_POST['source'];
		$thisUser = $_POST['idUser']);
	}

	$baseFilename = basename($_FILES['userfile']['name']);
	$fileExtension = strtolower(substr($baseFilename, strrpos($baseFilename, '.') + 1));

	echo $baseFilename.'<br>';
	echo $fileExtension.'<br>';

	if ($imageSource == 'user') {
		$outputFilename = "{$thisUser}_original.{$fileExtension}";
	}

	$uploadFile = $uploadDirectory . $outputFilename;
	$smallFile = "../img/avatars/{$thisUser}_small.{$fileExtension}";
	$mediumFile = "../img/avatars/{$thisUser}.{$fileExtension}";

	echo $uploadFile.'<br>';
	echo $outputFilename.'<br>';
	echo $smallFile.'<br>';
	echo $mediumFile.'<br>';

	if (!move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadFile)) {
		exit(0);
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
?>