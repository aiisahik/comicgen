  <?php


	if ($_SERVER['REQUEST_METHOD'] == 'GET')
	{
		$url = (isset($_GET['videofile']) && !empty($_GET['videofile'])) ? $_GET['videofile'] : false;
		if (!$url) {
			$arr_output = array('error' => "Please enter a URL.");
			echo json_encode($arr_output);
		} else {

			$arr_output = array('result'=> 'success', 'filename' => getFrame($_GET['videofile'],$_GET['timestamp'],$_GET['index']));
			echo json_encode($arr_output);
		}
	}


  function getFrame($videoFile,$second,$frameNum)
  {
        // Full path to ffmpeg (make sure this binary has execute permission for PHP)
		$ffmpeg = "/Applications/XAMPP/xamppfiles/htdocs/ffmpeg";
		 
		// // Full path to the video file
		//$videoFile = "/Applications/XAMPP/xamppfiles/htdocs/that_gotye_song.mp4";
		 
		// // Full path to output image file (make sure the containing folder has write permissions!)
		$imgOut = str_replace('.', "_", $videoFile."_".$second).".jpg";
		 
		// // Number of seconds into the video to extract the frame
		//$second = 10;
		 
		// // Setup the command to get the frame image
		 $cmd = $ffmpeg." -i \"".$videoFile."\" -an -ss ".$second." -t 00:00:1 -r 1 -y -f mjpeg \"".$imgOut."\" 2>&1";
		 

		// Get any feedback from the command
		 $feedback = `$cmd`;
		 //echo $feedback;
		 return $imgOut;
	}

?>