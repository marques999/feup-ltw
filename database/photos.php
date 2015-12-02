<?
	function image_writeFile($img, $dst, $ext) {
		if ($ext == 'jpg' || $ext == 'jpeg') {
			imagejpeg($img, $dst, 90);
		}
		else if ($ext == 'gif') {
			imagegif($img, $dst);
		}
		else {
			imagepng($img, $dst);
		}
	}

	function image_readFile($src, $ext) {
		
		if ($ext == 'jpg' || $ext == 'jpeg') {
			return imagecreatefromjpeg($src);
		}
		
		if ($ext == 'png') {
			return imagecreatefrompng($src);
		}
		
		if ($ext == 'bmp') {
			return imagecreatefromwbmp($src);
		}

		if ($ext == 'gif') {
			return imagecreatefromgif($src);
		}

		return null;
	}

	////////////////////////////////////////////////////////////////
	// MEDIUM $mediumSize * $newHeight || $newWidth * $mediumSize //
	////////////////////////////////////////////////////////////////

	function image_resize($src, $largest, $ext) {

		$originalWidth = imagesx($src);
		$originalHeight = imagesy($src);
	
		if ($originalHeight > $originalWidth && $originalHeight > $largest) {
			/* Portrait */
			$newHeight = $largest;
			$newWidth = $newHeight * ($originalWidth / $originalHeight);
		}
		else if($originalWidth > $originalHeight && $originalWidth > $largest) {
			/* Landscape */
			$newWidth = $largest;
			$newHeight = $newWidth * ($originalHeight / $originalWidth);
		}
		else {
			$newWidth = $originalWidth;
			$newHeight = $originalHeight;
		}

		$resizedImage = imagecreatetruecolor($newWidth, $newHeight);

		if ($ext == 'gif' || $ext == 'png') {
			imagecolortransparent($resizedImage, imagecolorallocatealpha($resizedImage, 0, 0, 0, 127));
			imagealphablending($resizedImage, false);
			imagesavealpha($resizedImage, true);
		}

		imagecopyresampled($resizedImage, $src,	0, 0, 0, 0,
			$newWidth, $newHeight, $originalWidth, $originalHeight);

		return $resizedImage;
	}

	////////////////////////////////////////////////////////////
	// THUMBNAIL $thumbnailSize * $thumbnailSize			  //
	////////////////////////////////////////////////////////////

	function image_crop($src, $largest, $ext) {

		$newWidth = imagesx($src);
		$newHeight = imagesy($src);
		$thumbnailImage = imagecreatetruecolor($largest, $largest);

		if ($ext == 'gif' || $ext == 'png') {
			imagecolortransparent($thumbnailImage, imagecolorallocatealpha($thumbnailImage, 0, 0, 0, 127));
			imagealphablending($thumbnailImage, false);
			imagesavealpha($thumbnailImage, true);
		}

		if ($newHeight == $newWidth) {
			imagecopyresampled($thumbnailImage, $src, 0, 0, 0, 0,
				$largest, $largest, $newWidth, $newHeight);
		}
		else if ($newWidth > $newHeight) {
			$difference = $newWidth - $newHeight;
			imagecopyresampled($thumbnailImage, $src, 0, 0, $difference / 2, 0,
				$largest, $largest, $newHeight, $newHeight);
		}
		else {
			$difference = $newHeight - $newWidth;
			imagecopyresampled($thumbnailImage, $src, 0, 0, 0, $difference / 2,
				$largest, $largest, $newWidth, $newWidth);
		}

		return $thumbnailImage;
	}
?>