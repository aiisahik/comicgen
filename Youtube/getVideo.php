<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
	$url = (isset($_GET['url']) && !empty($_GET['url'])) ? $_GET['url'] : false;
	if (!$url) {
		$arr_output = array('error' => "Please enter a URL.");
		echo json_encode($arr_output);
	} else {
		$url = "http://www.youtube.com/watch?v=".$_GET['url'];

		//$url=$_GET['video_id'];
		$source = file_get_contents($url);
		$source = urldecode($source);
		//echo $url;
		// Extract video title.
		$vTitle_results_1 = explode('<title>', $source);
		$vTitle_results_2 = explode('</title>', $vTitle_results_1[1]);
		
		$title = trim(str_replace(' - YouTube', '', trim($vTitle_results_2[0])));
		
		// Extract video download URL.
		$dURL_results_1 = explode('url_encoded_fmt_stream_map": "url=', $source);
		$dURL_results_2 = explode('\u0026quality', $dURL_results_1[1]);
		
		// Force download of video.
		//$file = str_replace(' ', '_', strtolower($title)).'.mp4';
		$file = $_GET['url'].'.mp4';
		
		// header("Cache-Control: public");
		// header("Content-Description: File Transfer");
		// header("Content-Disposition: attachment; filename=$file");
		// header("Content-Type: video/mp4");
		// header("Content-Transfer-Encoding: binary");
		
		// readfile($dURL_results_2[0]);
		if (file_exists($file)) {
			unlink($file);
		}
		$data=file_get_contents($dURL_results_2[0]); 

		
		$fh = fopen($file, 'w') or die("can't open file");
		fwrite($fh, $data);
		fclose($fh);

		$arr_output = array('filename' => $file);
		echo json_encode($arr_output);
		exit;
	}
}
?>