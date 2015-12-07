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

	function image_resize($src, $largest, $ext) {

		$originalWidth = imagesx($src);
		$originalHeight = imagesy($src);

		if ($originalHeight > $originalWidth && $originalHeight > $largest) {
			$newHeight = $largest;
			$newWidth = $newHeight * ($originalWidth / $originalHeight);
		}
		else if($originalWidth > $originalHeight && $originalWidth > $largest) {
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

	function image_advancedcrop($src, $target_width, $target_height, $ext) {

		$width = imagesx($src);
		$height = imagesy($src);
		$original_aspect = $width / $height;
		$thumbnail_aspect = $target_width / $target_height;
		$thumbnailImage = imagecreatetruecolor($target_width, $target_height);

		if ($ext == 'gif' || $ext == 'png') {
			imagecolortransparent($thumbnailImage, imagecolorallocatealpha($thumbnailImage, 0, 0, 0, 127));
			imagealphablending($thumbnailImage, false);
			imagesavealpha($thumbnailImage, true);
		}

		if ($original_aspect >= $thumbnail_aspect) {
			$new_height = $target_height;
			$new_width = $width / ($height / $target_height);
		}
		else {
			$new_width = $target_width;
			$new_height = $height / ($width / $target_width);
		}

		imagecopyresampled($thumbnailImage,
		   $src,
		   0 - ($new_width - $target_width) / 2,
		   0 - ($new_height - $target_height) / 2,
		   0, 0,
		   $new_width, $new_height, $width, $height);

		return $thumbnailImage;
	}
?>