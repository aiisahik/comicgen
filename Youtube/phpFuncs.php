<?php
	function editImage($filename, $textpos, $text, $fontsize){
		$image = imagecreatefromjpeg ( $filename );
		$white = imagecolorallocate($image, 255, 255, 255);
		$black = imagecolorallocate($image, 0, 0, 0);
	
		$box = imagefttext($image, $fontsize, 0, $textpos[0], $textpos[1], 0, "Comic_Book.ttf" , $text);
		imagefilledrectangle($image, $box[6]-10, $box[7]-10, $box[2]+10, $box[3]+10, $black);
		imagefilledrectangle($image, $box[6]-5, $box[7]-5, $box[2]+5, $box[3]+5, $white);
		imagefttext($image, $fontsize, 0, $textpos[0], $textpos[1], 0, "Comic_Book.ttf" , $text);
		
		$file_exist_string ="edited_".$filename;

		if (file_exists($file_exist_string)){
			unlink($file_exist_string);
		}

		imagejpeg ( $image, "edited_".$filename);
		
		return "edited_".$filename;
	}
	
	// Takes in an array of image resources, and the width and height of each image
	// Each image is supposed to be of the same size
	function stitchImages($image_files)
	{
		$dims = getimagesize($image_files[0]);
		$num_images = count($image_files);
		$width = $dims[0];
		$height = $dims[1];
		$images = array();
		foreach ($image_files as $filename)
		{
			array_push($images, imagecreatefromjpeg($filename));
		}
		$panel_width = 400;
		$ratio = $width/$panel_width;
		$panel_height = (int) $height/$ratio;
		$result_x = $panel_width*2;
		$result_y = $panel_height * (int)(($num_images+1)/2);
		$result = imagecreatetruecolor($result_x, $result_y);
		for ($i=0; $i<$num_images; $i++)
		{
			if($i % 2 == 0)
				imagecopyresized($result, $images[$i], 0+5, $panel_height*((int)($i/2))+5, 0, 0, $panel_width-10, $panel_height-10, $width, $height);
			else
				imagecopyresized($result, $images[$i], $panel_width+5, $panel_height*((int)($i/2))+5, 0, 0, $panel_width-10, $panel_height-10, $width, $height);
		}
		imagejpeg($result, "stitched.jpg");
		return "stitched.jpg";
	}
?>