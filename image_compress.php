<?php
/**
 * Resizes and crops image to fit provided $width and $height.
 *
 * @param $file
 *   Image to change. Pass the entire $_FILE['file_name'] array
 * @param int $width
 *   New desired width.
 * @param int $height
 *   New desired height.
 * @param int $destination_path
 *   Desired path where you want the compressed image as a function output
 */
function compress_resize_image($file, $width, $height, $destination_path) {
	$image = new imagick($file['tmp_name']);
	$ratio = $width / $height;

	$image_name = pathinfo($file["name"], PATHINFO_FILENAME);

	$img_ext = pathinfo($file["name"], PATHINFO_EXTENSION);

	// Original image dimensions.
	$old_width = $image->getImageWidth();
	$old_height = $image->getImageHeight();
	$old_ratio = $old_width / $old_height;

	// Determine new image dimensions to scale to.
	// Also determine cropping coordinates.
	if ($ratio > $old_ratio) {
	  $new_width = $width;
	  $new_height = $width / $old_width * $old_height;
	  $crop_x = 0;
	  $crop_y = intval(($new_height - $height) / 2);
	}
	else {
	  $new_width = $height / $old_height * $old_width;
	  $new_height = $height;
	  $crop_x = intval(($new_width - $width) / 2);
	  $crop_y = 0;
	}

	// Scale image to fit minimal of provided dimensions.
	$image->resizeImage($new_width, $new_height, imagick::FILTER_LANCZOS, 0.9, true);

	// Now crop image to exactly fit provided dimensions.
	//$image->cropImage($new_width, $new_height, $crop_x, $crop_y);

	$file_name = $image_name."_".round($width)."x".round($height).".".$img_ext;

	$destination = rtrim($destination_path, '\\') . '\\'.$file_name;

	$image->writeImage( $destination );
}

//compress_resize_image($_FILES["file"], 80, 80, "D:\\xampp\\htdocs\\imgComp");  //Example Function Call
