<?php
	$uploadDirectory = './userdata/original/';

	if (isset($_POST['source'])) {
		$imageSource = $_POST['source'];
	}
	else {
		$imageSource = 'user';
	}

	$baseFilename = basename($_FILES['userfile']['name']);
	$outputFilename = $imageSource . '_' . $baseFilename;
	$uploadFile = $uploadDirectory . $outputFilename;
	$smallFile = "./userdata/small/$outputFilename";
	$mediumFile = "./userdata/medium/$outputFilename";

	if (!move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadFile)) {
	    exit(0);
	}

	$fileExtension = strtolower(substr($uploadFile, strrpos($uploadFile, '.') + 1));
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
	$thumbnailSize = 200;
	$mediumSize = 400;

	////////////////////////////////////////////////////////////
	// MEDIUM $mediumSize * $mediumSize                       //
	////////////////////////////////////////////////////////////
	if ($originalHeight > $originalWidth) {
		/* Portrait */
		$newWidth = $mediumSize;
		$newHeight = $newWidth * ($originalHeight / $originalWidth);
	}
	else {
		/* Landscape */
		$newHeight = $mediumSize;
		$newWidth = $newHeight * ($originalWidth / $originalHeight);
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
			-$difference / 2, 0, 0, 0,
			$thumbnailSize + $difference, $thumbnailSize, $newWidth, $newHeight);
	}
	else {
		$difference = $newHeight - $newWidth;
		imagecopyresampled($thumbnailImage, $resizedImage,
			0, -$difference / 2, 0, 0,
			$thumbnailSize, $thumbnailSize + $difference, $newWidth, $newHeight);
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